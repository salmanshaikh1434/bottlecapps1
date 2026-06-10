<?php
/**
 * Template Name: SIPN Blocked users
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php
if (!is_user_logged_in()) {
	wp_redirect('/login');
	exit;
  }

global $wpdb;
$cur_user = wp_get_current_user();
$current_user_id = $cur_user->data->ID;
$query = $wpdb->prepare("
    SELECT u.ID, u.display_name 
    FROM wp_users_blocked b
    INNER JOIN wp_users u ON u.ID = b.blocked_user
    WHERE b.blocked_by = %d
", $cur_user->data->ID);

$blocked_users_data = $wpdb->get_results($query);
$blocked_users = array();

if (!empty($blocked_users_data)) {
	foreach ($blocked_users_data as $blocked_user) {
		$avatar = get_avatar($blocked_user->ID);

		$blocked_users[] = array(
			'user_id' => $blocked_user->ID,
			'display_name' => $blocked_user->display_name,
			'avatar' => $avatar
		);
	}
}

?>
<?php get_header(); ?>

<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container mh-550">
				<h1 class="heading-main-blocked">Blocked Users</h1>
				<div class="row ml-0">
					<div class="users-blocked">
						<ul>
							<?php
							foreach ($blocked_users as $value) { ?>
								<li>
									<div class="row">
										<div class="col-md-2 col-sm-2 col-xs-3 avatar">
											<div class="newprofile-img blocked-user">
												<?= $value['avatar'] ?>
											</div>
										</div>
										<div class="col-md-7 col-sm-7 col-xs-5 user-name">
											<p class="blocked-name"><?= $value['display_name'] ?></p>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-4 btn-block-unblock">
											<button type="submit" class="unblock-btn"
												data-blockuserid="<?= $value['user_id'] ?>"
												data-curuserid="<?= $current_user_id ?>">Unblock</button>
										</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<script>
				$('body').on('click', '.unblock-btn', function (e) {
					var blockuserid = $(this).attr('data-blockuserid');
					var curuserid = $(this).attr('data-curuserid');
					var search_data = { "user_id": curuserid, "block_user_id": blockuserid, "type": 'unblock' };
					var requesting;

					/* if request is in-process, kill it */
					if (requesting) {
						alert('2');
						requesting.abort();
					};

					requesting = $.ajax({
						type: 'POST',
						async: true,
						dataType: 'json',
						contentType: "application/json;",
						url: '/wp-json/users/v2/UserBlock/',
						data: JSON.stringify(search_data),
					}).done(function (data) {
							location.reload();
					});
				});
			</script>

			<?php sipn_footer(); ?>