<?php
/**
 * Template Name: SIPN email verification
 *
 * @package Neve
 */
remove_all_actions('template_redirect');

get_header();
?>
<div class="wrapper-top">
<div class="container" style="padding:60px 20px; text-align:center;">

<?php
global $wpdb;

// 1️⃣ Check email exists in URL
if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo '<h3>Invalid verification link.</h3>';
    exit;
}

// 2️⃣ Sanitize email
$email = sanitize_email($_GET['email']);

if (!is_email($email)) {
    echo '<h3>Invalid email address.</h3>';

    exit;
}

// 3️⃣ Get user by email
$user = get_user_by('email', $email);

if (!$user) {
    echo '<h3>User not found.</h3>';
    exit;
}

// 4️⃣ Check already verified
$already_verified = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT validate_email FROM {$wpdb->users} WHERE ID = %d",
        $user->ID
    )
);

if ($already_verified == 0) {
    echo '<h3>Your email is already verified ✅</h3>';
    echo '<script>
    setTimeout(function() {
        window.location.href = "' . home_url() . '";
    }, 2000);
</script>';
exit;
}

// 5️⃣ Update verification status
$updated = $wpdb->update(
    $wpdb->users,
    ['validate_email' => 0],
    ['ID' => $user->ID],
    ['%d'],
    ['%d']
);

// 6️⃣ Result
if ($updated !== false) {
    echo '<h2>Email verified successfully 🎉</h2>';
    echo '<script>
    setTimeout(function() {
        window.location.href = "' . home_url() . '";
    }, 2000);
</script>';
exit;

} else {
    echo '<h3>Something went wrong. Please contact support.</h3>';
}
?>

</div>
</div>

