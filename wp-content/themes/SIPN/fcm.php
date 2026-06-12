<?php
require 'vendor/autoload.php';

use Google\Client;

class FCM
{
    private $url;

    function __construct()
    {
        $this->url = 'https://fcm.googleapis.com/v1/projects/sipn-a164d/messages:send';
    }

     public function send_notification($registration_ids, $payload, $device_type, $notification_id = 0)
{
    global $wpdb;
    $responses = [];

    // Decode title and body to fix HTML entity issue like &amp;
    $title = html_entity_decode($payload['title']);
    $body = html_entity_decode($payload['body']);

    $accessToken = $this->getAccessToken();

   foreach ($registration_ids as $value) {
        if (!$accessToken) {
            $responses[] = ['success' => false, 'message' => 'Unable to obtain access token'];
            continue;
        }

        $message = [
            'message' => [
                'token' => $value,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'notification_id' => (string) $notification_id,
                ]
            ]
        ];

        $query = $wpdb->prepare("SELECT user_id FROM `wp_devices` WHERE device_id = '%s'", $value);
        $list = $wpdb->get_results($query);
        $uid = 0;

        if (!empty($list) && isset($list[0]->user_id)) {
            $uid = $list[0]->user_id;
        }

        // Log every delivery (user_id 0 when the device is not linked to a user)
        // so notification stats reflect the true number sent.
        $query = $wpdb->prepare(
            "INSERT INTO `notification_log` (`device_id`, `user_id`, `device_type`, `notification_id`) VALUES (%s, %d, %s, %d)",
            $value,
            $uid,
            $device_type,
            $notification_id
        );
        $wpdb->query($query);

        try {
            $response = $this->send($message, $accessToken);
                       $decoded = json_decode($response, true);

               
                error_log('FCM SUCCESS: ' . json_encode($decoded));

               
                if (isset($decoded['error'])) {
                    error_log('FCM ERROR RESPONSE: ' . json_encode($decoded));
                }

                $responses[] = [
                    'device_id' => $value, 
                    'response' => $decoded
                ];

        } catch (Exception $e) {
                        error_log('FCM EXCEPTION: ' . $e->getMessage());

                $responses[] = [
                    'device_id' => $value,
                    'success' => false,
                    'message' => 'Failed to send notification: ' . $e->getMessage()
                ];
        }
    }

    return $responses;
}
    private function getAccessToken()
    {
        $client = new Client();
        $credentialsfilepath = get_template_directory() . '/key/key.json';
        $client->setAuthConfig($credentialsfilepath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'] ?? null;
    }

    private function send($message, $accessToken)
    {
        $headers = [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            error_log('Curl error: ' . curl_error($ch));
            throw new Exception('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}