<?php
/**
 * LOCAL-ONLY WordPress password reset helper.
 * Run once in the browser, then DELETE this file.
 * Refuses to run unless accessed via localhost / 127.0.0.1.
 */

$host = $_SERVER['HTTP_HOST'] ?? '';
$ip   = $_SERVER['REMOTE_ADDR'] ?? '';
if ( strpos( $host, 'localhost' ) === false && $ip !== '127.0.0.1' && $ip !== '::1' ) {
	http_response_code( 403 );
	exit( 'Forbidden: this tool only runs on localhost.' );
}

require __DIR__ . '/wp-load.php';
header( 'Content-Type: text/plain; charset=utf-8' );

/* ============================================================
 *  EDIT THESE TWO VALUES, then reload this page in the browser
 * ============================================================ */
$username     = 'demo';       // the admin login
$new_password = 'Admin@123';  // the new password you want to set
/* ============================================================ */

if ( $username === '' || $new_password === '' ) {
	echo "Existing users  (login  —  email  —  role)\n";
	echo "-------------------------------------------\n";
	foreach ( get_users() as $u ) {
		echo $u->user_login . '   —   ' . $u->user_email . '   —   ' . implode( ',', $u->roles ) . "\n";
	}
	echo "\nNext: open reset-pw.php in your editor, fill in \$username and \$new_password,\n";
	echo "save, reload this page, then DELETE reset-pw.php.\n";
	exit;
}

$user = get_user_by( 'login', $username );

if ( ! $user ) {
	// No such user — create a fresh administrator.
	$uid = wp_insert_user( array(
		'user_login' => $username,
		'user_pass'  => $new_password,
		'user_email' => 'admin@localhost.test',
		'role'       => 'administrator',
	) );
	if ( is_wp_error( $uid ) ) {
		exit( 'Could not create user: ' . $uid->get_error_message() );
	}
	echo "Created new administrator '{$username}' with the given password.\n\n";
} else {
	wp_set_password( $new_password, $user->ID );
	// Make sure the account actually has admin rights.
	$user->set_role( 'administrator' );
	echo "Password successfully updated for '{$username}' (administrator).\n\n";
}

echo "IMPORTANT: delete this file (reset-pw.php) now, then log in at http://localhost/wp-login.php\n";
