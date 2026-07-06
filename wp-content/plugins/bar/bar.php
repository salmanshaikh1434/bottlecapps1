<?php
/**
 * Plugin Name: Bar
 * Plugin URI: 
 * Description: Bar plugin - enable the users to create their own bar.
 * Author: Sagar
 * Author URI: 
 * License: MIT
 *
 */

if (!defined('ABSPATH'))
	exit;

// Act on plugin activation
register_activation_hook(__FILE__, "activate_bar");

// Act on plugin de-activation
register_deactivation_hook(__FILE__, "deactivate_bar");

// Activate Plugin
function activate_bar()
{

	// Execute tasks on Plugin activation

	// Insert DB Tables
	init_db_bar();
}

// De-activate Plugin
function deactivate_bar()
{

	// Execute tasks on Plugin de-activation
}


function init_db_bar()
{
	global $wpdb;

	$bar_table_name = $wpdb->prefix . 'bar';

	$charset_collate = $wpdb->get_charset_collate();
	// Create Customer Table if not exist
	if ($wpdb->get_var("show tables like '$bar_table_name'") != $bar_table_name) {

		$sql_1 = "CREATE TABLE $bar_table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			owner bigint(20) NOT NULL,
			shared boolean NOT NULL DEFAULT 0,
			created timestamp DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";


		$shelves_table_name = $wpdb->prefix . 'bar_shelves';

		$charset_collate = $wpdb->get_charset_collate();

		$sql_2 = "CREATE TABLE $shelves_table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			bar_id bigint(20) NOT NULL,
			weight bigint(20) NOT NULL,
			created timestamp DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";


		$shelve_prodcuts_table_name = $wpdb->prefix . 'bar_shelves_products';

		$charset_collate = $wpdb->get_charset_collate();

		$sql_3 = "CREATE TABLE $shelve_prodcuts_table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			shelve_id bigint(20) NOT NULL,
			product_id bigint(20) NOT NULL,
			weight bigint(20) NOT NULL,
			created timestamp DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";



		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_1);
		dbDelta($sql_2);
		dbDelta($sql_3);
	}

}

function wp_get_bar_table_columns($table = 'bar')
{
	if ($table == 'bar') {
		return array(
			'id' => '%d',
			'name' => '%s',
			'owner' => '%d'
		);
	} else if ($table == 'shelves') {
		return array(
			'id' => '%d',
			'name' => '%s',
			'bar_id' => '%d'
		);
	} else if ($table == 'products') {
		return array(
			'id' => '%d',
			'shelve_id' => '%d',
			'product_id' => '%d'
		);
	}

}

/**
 * Inserts a bar into the database
 *
 *@param $data array An array of key => value pairs to be inserted
 *@return int The log ID of the created activity log. Or WP_Error or false on failure.
 */
function wp_bar_insert_bar($data = array())
{
	global $wpdb;

	//Set default values
	$data = wp_parse_args($data, array(
		'name' => $data['name'],
		'owner' => $data['owner'],
	));


	//Initialise column format array
	$column_formats = wp_get_bar_table_columns('bar');

	//Force fields to lower case
	$data = array_change_key_case($data);

	//White list columns
	$data = array_intersect_key($data, $column_formats);

	//Reorder $column_formats to match the order of columns given in $data
	$data_keys = array_keys($data);
	$column_formats = array_merge(array_flip($data_keys), $column_formats);

	$wpdb->insert($wpdb->wp_bar, $data, $column_formats);

	return $wpdb->insert_id;
}

add_action('rest_api_init', function () {
	register_rest_route('bar/v1', '/all', array(
		'methods' => 'GET',
		'callback' => 'handle_get_all_bars',
		'permission_callback' => function ($request) {
			if (current_user_can('manage_options'))
				return true;
		}
	));

	register_rest_route('bar/v1', '/mybar', array(
		'methods' => 'GET',
		'callback' => 'handle_get_my_bar',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('bar/v1', '/add', array(
		'methods' => 'POST',
		'callback' => 'handle_bar_add',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			if ($item["owner_email"] == '') {
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			}
			if ($item["owner_email"] != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (is_bar_exist($item["owner_email"])) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar already exists for the user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('bar/v1', '/edit', array(
		'methods' => 'POST',
		'callback' => 'handle_bar_edit',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			//echo "--->".is_bar_exist($item["owner_email"]);exit;
			//print_r($cur_user);
			if ($item["owner_email"] != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($item["owner_email"])) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar not exists', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('bar-shelf/v1', '/edit', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_edit',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);

			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			} else if ($item['id'] == '' || $item['id'] <= 0) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid shelf id', array('status' => 403));
			} else {
				return true;
			}
		}
	));



	register_rest_route('bar-shelf-products/v1', '/add', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_product_add',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);
			$shelf_details = get_shelf_details($item['shelve_id']);

			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			}
			if ($shelf_details->id == '' || $shelf_details->id <= 0) {
				return new WP_Error('rest_forbidden', 'Shelf doesnt exists', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('bar-shelf-products/v1', '/delete', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_product_delete',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);
			$shelf_details = get_shelf_details($item['shelve_id']);

			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			} else if ($shelf_details->id == '' || $shelf_details->id <= 0) {
				return new WP_Error('rest_forbidden', 'Shelf doesnt exists', array('status' => 403));
			} else if ($item['product_id'] == '' || $item['product_id'] <= 0) {
				return new WP_Error('rest_forbidden', 'Invalid Product Id', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('bar-shelf-products/v1', '/reorder', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_product_reorder',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);
			$shelf_details = get_shelf_details($item['shelve_id']);

			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			} else if ($shelf_details->id == '' || $shelf_details->id <= 0) {
				return new WP_Error('rest_forbidden', 'Shelf doesnt exists', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('bar-shelf-products/v1', '/reorder-crossshelf', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_product_reorder_crossshelf',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);
			$shelf_details = get_shelf_details($item['shelve_id']);

			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			} else if ($shelf_details->id == '' || $shelf_details->id <= 0) {
				return new WP_Error('rest_forbidden', 'Shelf doesnt exists', array('status' => 403));
			} else {
				return true;
			}
		}
	));



	register_rest_route('bar-shelf-products/v1', '/updateshelves', array(
		'methods' => 'POST',
		'callback' => 'handle_barshelf_update',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();
			$bar_details = get_bar_details($item['bar_id']);


			if ($bar_details->owner_email != $cur_user->data->user_email) {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else if (!is_bar_exist($bar_details->owner_email)) {
				//return false;
				return new WP_Error('rest_forbidden', 'Bar doesnt exists for the user', array('status' => 403));
			} else {
				return true;
			}
		}
	));



	register_rest_route('events/v2', '/list', array(
		'methods' => 'POST',
		'callback' => 'handle_events_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('events/v2', '/listweb', array( //for web
		'methods' => 'POST',
		'callback' => 'handle_eventsweb_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('events/v2', '/event', array(
		'methods' => 'POST',
		'callback' => 'handle_event_detail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('blogs/v2', '/list', array(
		'methods' => 'GET',
		'callback' => 'handle_blog_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('blogs/v2', '/article', array(
		'methods' => 'POST',
		'callback' => 'handle_blog_detail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('products/v2', '/list', array(
		'methods' => 'POST',
		'callback' => 'handle_products_list_new',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('products/v2', '/featured', array(
		'methods' => 'POST',
		'callback' => 'handle_featured_products',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('products/v2', '/product', array(
		'methods' => 'POST',
		'callback' => 'handle_product_detail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('topics/v2', '/list', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('topics/v2', '/details', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_details',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('reply/v2', '/add', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_add',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/add-post', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_addpost',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/edit-post', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_editpost',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('timeline/v2', '/editpostwithmultpart', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_editpostwithmultpart',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('timeline/v2', '/delete-post', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_deletepost',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('timeline/v2', '/delete-comment', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_deletepost',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/edit-comment', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_editpost',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/list', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	register_rest_route('timeline/v2', '/list_new', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_list_test_new',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/chat-bubbles', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_chatbubbles',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/get-comments', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_comments',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('reply/v2', '/edit', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_edit',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('users/v2', '/profile', array(
		'methods' => 'GET',
		'callback' => 'handle_user_detail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/profile', array(
		'methods' => 'POST',
		'callback' => 'handle_user_edit',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/register', array(
		'methods' => 'POST',
		'callback' => 'handle_user_register',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	register_rest_route('users/v2', '/UserBlock', array(
		'methods' => 'POST',
		'callback' => 'handle_user_UserBlock',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/UsersBlocked', array(
		'methods' => 'POST',
		'callback' => 'handle_user_UsersBlocked',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('products/v2', '/wishlist', array(
		'methods' => 'POST',
		'callback' => 'handle_user_wishlist',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				if ($item['product_id'] <= 0 || $item['product_id'] == '') {
					return new WP_Error('rest_forbidden', 'Invalid product id', array('status' => 403));
				} else {
					return true;
				}
			}
		}
	));

	register_rest_route('products/v2', '/mywishlist', array(
		'methods' => 'POST',
		'callback' => 'handle_getuser_wishlist',
		'permission_callback' => function ($request) {

			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('users/v2', '/bar', array(
		'methods' => 'POST',
		'callback' => 'handle_user_bar',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('pages/v2', '/details', array(
		'methods' => 'POST',
		'callback' => 'handle_get_page',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('home/v2', '/videos', array(
		'methods' => 'GET',
		'callback' => 'handle_get_videos',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('home/v2', '/videos', array(
		'methods' => 'POST',
		'callback' => 'handle_get_videos_list',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('user/v2', '/device', array(
		'methods' => 'POST',
		'callback' => 'handle_device',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('user/v2', '/likecomment', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_likes',
		'permission_callback' => function ($request) {
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('user/v2', '/likeprofile', array(
		'methods' => 'POST',
		'callback' => 'handle_profile_likes',
		'permission_callback' => function ($request) {
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('user/v2', '/getprofilelikes', array(
		'methods' => 'POST',
		'callback' => 'handle_get_profile_like_count',
		'permission_callback' => function ($request) {
			return true;
		}
	));



	register_rest_route('notifications/v2', '/list', array(
		'methods' => 'GET',
		'callback' => 'handle_notifications',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('notifications/v2', '/newlist', array(
		'methods' => 'POST',
		'callback' => 'handle_notifications_new',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/likepost', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_likes',
		'permission_callback' => function ($request) {
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));


	register_rest_route('timeline/v2', '/reportpost', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_report',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	register_rest_route('timeline/v2', '/likecomment', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_likes',
		'permission_callback' => function ($request) {
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	//for verify email by sumeeth
	register_rest_route('users/v2', '/verifyemail', array(
		'methods' => 'GET',
		'callback' => 'handle_user_verifyemail',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	//by sumeeth for multipart

	register_rest_route('timeline/v2', '/addpostwithmultpart', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_addpostwithmultpart',
		'permission_callback' => function ($request) {

			return true;

		}
	));


	//delete profile

	register_rest_route('timeline/v2', '/deleteprofile', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_deleteprofile',
		'permission_callback' => function ($request) {

			return true;

		}
	));

	//delete profile with userid

	register_rest_route('timeline/v2', '/deleteprofilewithuserid', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_deleteprofilewithuserid',
		'permission_callback' => function ($request) {

			return true;

		}
	));

	//for bar
	register_rest_route('timeline/v2', '/reportbar', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_reportbar',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	//for forums
	register_rest_route('timeline/v2', '/reportforums', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_reportforums',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	//for collections
	register_rest_route('home/v2', '/collections', array(
		'methods' => 'POST',
		'callback' => 'handle_get_collections',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	//for colections list
	register_rest_route('home/v2', '/getcollectiondetails', array(
		'methods' => 'POST',
		'callback' => 'handle_getcollectiondetails',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	//for contact us dropdown list

	register_rest_route('users/v2', '/getcontactdropdownlist', array(
		'methods' => 'GET',
		'callback' => 'handle_getcontactdropdownlist',
		'permission_callback' => function ($request) {
			return true;
		}
	));



	//for storing location mot found for product

	register_rest_route('timeline/v2', '/ajaxaddlocationbuynow', array(
		'methods' => 'POST',
		'callback' => 'ajaxaddlocationbuynow',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	//sending email for unverified users

	register_rest_route('users/v2', '/register-resendemail', array(
		'methods' => 'POST',
		'callback' => 'handle_user_registerresendemail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	//for sponsored ad's add comment

	register_rest_route('reply/v2', '/sponsadd', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_sponsadd',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	//for sponsored ad's edit comment
	register_rest_route('reply/v2', '/sponsedit', array(
		'methods' => 'POST',
		'callback' => 'handle_topics_sponsedit',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/get-sponscomments', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_sponscomments',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	//for sponsored likes
	register_rest_route('timeline/v2', '/likesponsoredpost', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_sponsoredlikes',
		'permission_callback' => function ($request) {
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/delete-sponscomment', array(
		'methods' => 'POST',
		'callback' => 'handle_timeline_deletesponscomment',
		'permission_callback' => function ($request) {
			$item = $request->get_json_params();
			$cur_user = wp_get_current_user();

			if ($cur_user->data->user_email == '') {
				//return false;
				return new WP_Error('rest_forbidden', 'Invalid user', array('status' => 403));
			} else {
				return true;
			}
		}
	));

	register_rest_route('timeline/v2', '/reportsponspost', array(
		'methods' => 'POST',
		'callback' => 'handle_reply_sponsreport',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/test', array(
		'methods' => 'POST',
		'callback' => 'handle_test',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/unsubscribe', array(
		'methods' => 'POST',
		'callback' => 'handle_unsubscribe',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	//for unsubscribe email by sumeeth
	register_rest_route('users/v2', '/unsubscribeemail', array(
		'methods' => 'GET',
		'callback' => 'handle_user_unsubscribeemail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('notifications/v2', '/getunsubscribestatus', array(
		'methods' => 'GET',
		'callback' => 'handle_getunsubscribestatus',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	register_rest_route('timeline/v2', '/gettotalproducts', array(
		'methods' => 'POST',
		'callback' => 'handle_gettotalproducts',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/gettotalposts', array(
		'methods' => 'POST',
		'callback' => 'handle_gettotalposts',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	//created by raghu
	register_rest_route('timeline/v2', '/usertotalposts', array(
		'methods' => 'POST',
		'callback' => 'handle_usertotalposts',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	register_rest_route('timeline/v2', '/recentsearchitems', array(
		'methods' => 'POST',
		'callback' => 'handle_recentsearchitems',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	register_rest_route('timeline/v2', '/usersearchitems', array(
		'methods' => 'POST',
		'callback' => 'handle_usersearchitems',
		'permission_callback' => function ($request) {
			return true;
		}
	));
	//end by raghu
	register_rest_route('users/v2', '/ajaxsendingindexdata', array(
		'methods' => 'POST',
		'callback' => 'handle_ajaxsendingindexdata',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/resendverificationemail', array(
		'methods' => 'POST',
		'callback' => 'handle_user_resendverificationemail',
		'permission_callback' => function ($request) {
			return true;
		}
	));


	register_rest_route('users/v2', '/emailverificationstatus', array(
		'methods' => 'POST',
		'callback' => 'handle_user_emailverificationstatus',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/ajaxpostdetail', array(
		'methods' => 'POST',
		'callback' => 'ajaxpostdetail',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('timeline/v2', '/opensearchapi', array(
		'methods' => 'POST',
		'callback' => 'opensearchapi',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/recordsponsoredaddclick', array(
		'methods' => 'POST',
		'callback' => 'handle_user_recordsponsoredaddclick',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/logout', array(
		'methods' => 'POST',
		'callback' => 'handle_user_logout',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('users/v2', '/readcountfornotifications', array(
		'methods' => 'POST',
		'callback' => 'handle_user_readcountfornotifications',
		'permission_callback' => function ($request) {
			return true;
		}
	));

	register_rest_route('tracking/v2', '/events', array(
        'methods' => 'POST',
        'callback' => 'handle_event_tracking',
        'permission_callback' => function ($request) {
            return true; // Change this as per your permission logic
        }
    ));

	register_rest_route('users/v2', '/challanges', array(
        'methods' => 'POST',
        'callback' => 'handle_challanges_dashboard',
        'permission_callback' => function ($request) {
            return true;
        }
    ));
	register_rest_route('users/v2', '/recent_activity', array(
        'methods' => 'POST',
        'callback' => 'handle_user_reward_history',
        'permission_callback' => function ($request) {
            return true;
        }
    ));
    register_rest_route('users/v2', '/get_badge', array(
        'methods' => 'POST',
        'callback' => 'get_levels',
        'permission_callback' => function ($request) {
            return true;
        }
    ));
    register_rest_route('users/v2', '/run_script', array(
        'methods' => 'GET',
        'callback' => 'comments_rewards',
        'permission_callback' => function ($request) {
            return true;
        }
    ));

	register_rest_route('users/v2', '/invite_friends', array(
			'methods' => 'GET',
	        'callback' => 'invite_friends',
	        'permission_callback' => function ($request) {
	            return true;
	        }
	    ));


	register_rest_route('users/v2', '/reward', array(
        'methods' => 'POST',
        'callback' => 'add_rewards_point',
        'permission_callback' => function ($request) {
            return true;
        }
    ));

	register_rest_route('users/v2', '/order_status', array(
        'methods' => 'POST',
        'callback' => 'order_status_update',
        'permission_callback' => function ($request) {
            return true;
        } 
    ));

    //opensearch indexing
    register_rest_route('users/v2', '/doc_indexing', array(
        'methods' => 'GET',
        'callback' => 'sync_wp_reply_to_opensearch',
        'permission_callback' => function ($request) {
            return true;
        }
    ));
    register_rest_route('users/v2', '/doc_indexing_products', array(
        'methods' => 'GET',
        'callback' => 'sync_wp_products_to_opensearch',
        'permission_callback' => function ($request) {
            return true;
        }
    ));    

  register_rest_route('user/v1', '/reset-password', [
        'methods' => 'POST',
        'callback' => 'sipn_send_reset_otp',
        'permission_callback' => function ($request) {
            return true;
        }
    ]);

    register_rest_route('user/v1', '/set-password', [
        'methods' => 'POST',
        'callback' => 'sipn_verify_otp_and_reset',
        'permission_callback' => function ($request) {
            return true;
        }
    ]);


});

function sipn_verify_otp_and_reset(WP_REST_Request $request) {

    $params = $request->get_json_params();

    $email    = sanitize_email($params['email']);
    $otp      = sanitize_text_field($params['code']);
    $password = $params['password'];

    if (!$email || !$otp || !$password) {
        return new WP_REST_Response(['message' => 'Missing required fields'], 400);
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return new WP_REST_Response(['message' => 'User not found'], 404);
    }

    $stored_otp = get_user_meta($user->ID, 'reset_otp', true);
    $otp_time   = get_user_meta($user->ID, 'reset_otp_time', true);

    // Expire after 10 minutes
    if ((time() - $otp_time) > 600) {
        return new WP_REST_Response(['message' => 'OTP expired'], 400);
    }

    if ($otp != $stored_otp) {
        return new WP_REST_Response(['message' => 'Invalid OTP'], 400);
    }

    // Update password
    wp_set_password($password, $user->ID);

    // Clear OTP
    delete_user_meta($user->ID, 'reset_otp');
    delete_user_meta($user->ID, 'reset_otp_time');

    return ['message' => 'Password changed successfully'];
}


function sipn_send_reset_otp(WP_REST_Request $request) {

    $params = $request->get_json_params();
    $email  = sanitize_email($params['email']);

    if (!$email) {
        return new WP_REST_Response(['message' => 'Email is required'], 400);
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return new WP_REST_Response(['message' => 'User not found'], 404);
    }

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in user meta
    update_user_meta($user->ID, 'reset_otp', $otp);
    update_user_meta($user->ID, 'reset_otp_time', time());

    // Send Email
    $subject = "Your OTP for Password Reset";
    $message = "Your OTP is: " . $otp . "\n\nThis OTP expires in 10 minutes.";

    wp_mail($email, $subject, $message);

    return ['message' => 'OTP sent to your email'];
}


function sync_wp_reply_to_opensearch() {
	global $wpdb;
	$paged = 1;
	$per_page = 1000;
	$noimage = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/noimage.webp';

	do {
		$args = [
			'post_type'      => 'reply',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged
		];

		$posts = get_posts($args);
		if (empty($posts)) break;

		foreach ($posts as $post) {
			$post_id = $post->ID;
			$title = $post->post_content;
			$handled_title = strtolower(preg_replace('/[^a-z0-9]+/', ' ', $title));
			$post_url = "https://sipnbourbon.com/timeline/?q={$post_id}";
			$image = get_the_post_thumbnail_url($post_id, 'full') ?: $noimage;

			// Get tagged product via meta
			$query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = %d AND meta_key = '_bbp_product_id'", $post_id);
			$p_list = $wpdb->get_results($query1);
			$pid = $p_list[0]->pid ?? '';
			$tagged_product = '';
			$taggedproduct_image = $noimage;

			if (!empty($pid)) {
				$productlis = get_post($pid);
				$tagged_product = $productlis->post_title;
				$pimage = get_the_post_thumbnail_url($pid, 'full');
				if (!empty($pimage)) {
					$taggedproduct_image = $pimage;
				}
			}

			// Skip if both title and tagged product are empty
			if (empty($title) && empty($tagged_product)) continue;

			$data = [
				'post_id'             => $post_id,
				'post_title'          => $title,
				'handled_post_title'  => $handled_title,
				'post_url'            => $post_url,
				'post_image'          => $image,
				'tagged_product'      => $tagged_product,
				'taggedproduct_image' => $taggedproduct_image,
			];

			// PUT to avoid duplicates
			$response = wp_remote_request("https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnpost_prod/_doc/{$post_id}", [
				'method'  => 'PUT',
				'headers' => ['Content-Type' => 'application/json'],
				'body'    => wp_json_encode($data),
				'timeout' => 15,
			]);

			if (is_wp_error($response)) {
				error_log("Failed to index REPLY {$post_id}: " . $response->get_error_message());
			}
		}
		$paged++;
	} while (count($posts) === $per_page);

	echo 'Reply post indexing completed.';
}
function sync_wp_products_to_opensearch() {
	$paged = 1;
	$per_page = 1000;
	$noimage = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/noimage.webp';

	do {
		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged
		];

		$products = get_posts($args);
		if (empty($products)) break;

		foreach ($products as $product_post) {
			$title = $product_post->post_title;
			$product_id    = $product_post->ID;
        	$the_product = wc_get_product($product_id);
		    $product_title = $title;
		    $product_price = $the_product->price ? $the_product->price : 0;
		    $product_flavor = get_post_meta($product_id, 'flavor', true);
		    $product_image = get_the_post_thumbnail_url($product_id, 'full') ?: $noimage;
		    $product_link  = get_permalink($product_id);

			$product_id = $product_post->ID;
			$handled_product_title = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($product_title));
			$product = wc_get_product($product_id);

			if (!$product) continue;

			$product_flavor = get_post_meta($product_id, 'flavor', true);
			$product_image  = get_the_post_thumbnail_url($product_id, 'full') ?: $noimage;
			$product_link   = get_permalink($product_id);

			$data = [
				'product_id'            => $product_id,
				'product_title'         => $product_title,
				'handled_product_title' => $handled_product_title,
				'product_price'         => floatval($product_price),
				'product_flavor'        => $product_flavor,
				'product_image'         => $product_image,
				'product_link'          => $product_link,
			];

			// PUT to avoid duplicates
			$response = wp_remote_request("https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnproduct_prod/_doc/{$product_id}", [
				'method'  => 'PUT',
				'headers' => ['Content-Type' => 'application/json'],
				'body'    => wp_json_encode($data),
				'timeout' => 15,
			]);

			if (is_wp_error($response)) {
				error_log("Failed to index PRODUCT {$product_id}: " . $response->get_error_message());
			}
		}
		$paged++;
	} while (count($products) === $per_page);

	echo 'Product indexing completed.';
}
function order_status_update(WP_REST_Request $request) {
	
    global $wpdb;
    $params = $request->get_json_params();
   	$orderId = sanitize_text_field($params['order_id'] ?? '');
	$orderTotal = $params['order_total'];
	$email = sanitize_email($params['email'] ?? '');

	// $user = $wpdb->get_row($wpdb->prepare(
    //     "SELECT ID FROM wp_users WHERE validate_email = 1 AND user_email = %s", $email
    // ));

    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT ID FROM wp_users WHERE user_email = %s", $email
    ));

    $user_id = $user->ID; //? $user->ID : 0;
  
  	$query = $wpdb->prepare("INSERT INTO `wp_user_order_history` (`user_id`,`email`, `order_id`, `order_total`) VALUES (%d, %s, %s, %s)", $user_id, $email, $orderId, $orderTotal );

	$res = $wpdb->query($query);

    if ($res > 0) {
    	if($user_id > 0){
    		add_rewards_point($user_id, $orderId);
    	}
    	
        return new WP_REST_Response(['success' => true], 200);
    } else {
        return new WP_REST_Response(['error' => 'Insert failed'], 500);
    }
}

function add_rewards_point($user_id, $orderId) {

    global $wpdb;
   
	$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND order_id = %s", $user_id, $orderId));
	if($list == 0){
		reward_points("add", 17, $user_id, null, null, $orderId);	
	}
    return true;
}


function invite_friends(){
	global $wpdb;
	$referral_link = site_url().'/sign-up?ref=' . get_current_user_id();
	$data['referral_link'] = $referral_link;

	$user_id = get_current_user_id();
	$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $user_id, 4));
    if ($user_id && $list <= 10) {
		reward_points("add",(int)4, $user_id);
		$message['status'] = 'true';
		$message['message'] = 'Rewards addedd successfully.';
		$message['referral_link'] = $referral_link;
	}else{
		$message['status'] = 'false';
		$message['message'] = 'Maximum referral reward limit of 10 has been reached.';
		$message['referral_link'] = $referral_link;
	}
	return rest_ensure_response($message);
}
function comments_rewards(){
	global $wpdb;
	$posts_per_page = -1;
	$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
				)
			),
			'numberposts' => $posts_per_page,
		];

		$replies = get_posts($args);
		foreach($replies as $reply){
			// echo $reply->post_author .' - '. $reply->ID ."<br>";
			echo reward_points("add",(int)8,$reply->post_author, $reply->ID);
		}
}
function virtualbarlike_reward(){
	global $wpdb;
	$sql = "select profile_id, user_id from wp_profile_likes";
	$lists = $wpdb->get_results($sql);

	foreach ($lists as $list) {
		 echo reward_points('add', (int)16, $list->profile_id, null, $list->user_id);
	}
}
function virtualbar_reward(){
	global $wpdb;
	$sql = "SELECT DISTINCT user_id FROM (
        SELECT u.ID as user_id
        FROM wp_users u
        JOIN wp_bar b ON b.owner_email = u.user_email
        JOIN wp_bar_shelves bs ON bs.bar_id = b.id
        JOIN wp_bar_shelves_products bsp ON bsp.shelve_id = bs.id
        WHERE bsp.product_id IS NOT NULL

        UNION

        SELECT urh.user_id
        FROM user_reward_history urh
        WHERE urh.user_id IS NOT NULL
    ) AS `combined_users` ";

	$lists = $wpdb->get_results($sql);

	foreach ($lists as $list) {
		 echo $reward_msg = reward_points("add",(int)15, $list->user_id);
	}
}
function post_rewards(){
	global $wpdb;
	$sql = "SELECT 
			    p.ID, 
			    p.post_author
			FROM 
			    wp_posts p
			INNER JOIN 
			    wp_users u ON u.ID = p.post_author
			WHERE 
			    p.post_author != 5
			    AND p.post_type IN ('reply', 'post')
			    AND p.post_status = 'publish'";

	$lists = $wpdb->get_results($sql);
	foreach ($lists as $list) {
		 echo $reward_msg = reward_points("add",(int)6, $list->post_author, $list->ID);
	}
}
function add_account_complete_reward(){
	global $wpdb;
	$sql = "select ID from wp_users where profile_edited = 1 AND validate_email = 0";
	$lists = $wpdb->get_results($sql);
	foreach ($lists as $list) {
		echo reward_points("add", (int)1, $list->ID);
	}
}
function add_email_reward(){
	global $wpdb;
	$sql = "select ID from wp_users where validate_email = 0";
	$lists = $wpdb->get_results($sql);
	foreach ($lists as $list) {
		echo reward_points("add", (int)2, $list->ID);
	}
}
function get_unique_like() {
	global $wpdb;

	$sql = "SELECT DISTINCT user_id, reply_id
			FROM wp_reply_likes
			WHERE status = 1";


	$lists = $wpdb->get_results($sql);

	foreach ($lists as $list) {
		echo reward_points("add", 7, (int)$list->user_id, (int)$list->reply_id);
		// echo $message
	}
}

function get_levels(){
	global $wpdb;
	$user_id = get_current_user_id();
	$queryuid = $wpdb->prepare("SELECT 
			    l.id AS level_id,
			    l.level_name,
			    l.min_points,
			    ur.total_points,
			    ur.current_level_id,
			    CASE 
			        WHEN ur.total_points >= l.min_points THEN 0
			        ELSE l.min_points - ur.total_points
			    END AS points_to_reach,
			    CASE 
			        WHEN ur.current_level_id = l.id THEN 'Yes'
			        ELSE 'No'
			    END AS is_current_level
			FROM 
			    levels l
			CROSS JOIN 
			    users_rewards ur
			WHERE 
			    ur.user_id = %d
			ORDER BY 
			    l.min_points DESC", $user_id);
	$challenges = $wpdb->get_results($queryuid, ARRAY_A);
	return $challenges;
}

function birthday_rewards(){
	global $wpdb;
	$user_id = get_current_user_id();
	$today = date('m-d');
	$current_year = date('Y');
	// This compares just month and day, ignoring year
	$sql = "
	    SELECT user_id
	    FROM wp_usermeta
	    WHERE meta_key = 'date_of_birth'
	      AND meta_value IS NOT NULL
	      AND meta_value != ''
	      AND DATE_FORMAT(STR_TO_DATE(meta_value, '%m-%d-%Y'), '%m-%d') <= DATE_FORMAT(CURDATE(), '%m-%d')
	      AND user_id = $user_id
	";
	$results = $wpdb->get_results($sql);
	if (!empty($results) && isset($results[0]->user_id)) {
	    $reward_check = $wpdb->prepare(
	        "SELECT COUNT(*) FROM user_reward_history 
	        WHERE user_id = %d 
	        AND challenge_id = 5 
	        AND YEAR(created_at) = %d",
	        $user_id, 
	        $current_year
	    );
	    $already_rewarded = $wpdb->get_var($reward_check);
	    if (!$already_rewarded) {
	        reward_points('add', (int)5, $user_id);
	    }
	}
}

// Reward Points 
function update_rewards(){
	global $wpdb;
	$user_id = get_current_user_id();
	//count the total number of posts by user
	$postCount = handle_usertotalposts_test();


	$challenge_id = 0;
	
	if($postCount >= 100){
	    $challenge_id = (int)11;
	} else if($postCount >= 25){
		$challenge_id = (int)10;
	} else if($postCount >= 10){
		$challenge_id = (int)9;
	}
	if($challenge_id > 0){
		$sql = $wpdb->prepare(
		    "SELECT challenge_id FROM `user_reward_history` 
		    WHERE `user_id` = %d 
		    AND `challenge_id` IN (9, 10, 11)", 
		    $user_id
		);
		$existing_rewards = $wpdb->get_col($sql);

		$rewards = [];

		// If post count is >= 100 and challenge 11 is not given
		if ($postCount >= 100 && !in_array(11, $existing_rewards)) {
		    $rewards[] = 11;
		}

		// If post count is >= 25 and challenge 10 is not given
		if ($postCount >= 25 && !in_array(10, $existing_rewards)) {
		    $rewards[] = 10;
		}

		// If post count is >= 10 and challenge 9 is not given
		if ($postCount >= 10 && !in_array(9, $existing_rewards)) {
		    $rewards[] = 9;
		}

		foreach ($rewards as $challenge_id) {
		    reward_points('add', $challenge_id, $user_id);
		}
	}

}
function getUserLevelId($points) {
    global $wpdb;
    
    $levels = $wpdb->get_results("SELECT id, min_points FROM levels ORDER BY min_points DESC");

    foreach ($levels as $level) {
        if ($points >= $level->min_points) {
            return $level->id;
        }
    }

    return null; 
}
function getUserLevelName($points) {
    global $wpdb;
    
    $levels = $wpdb->get_results("SELECT id, min_points, level_name FROM levels ORDER BY min_points DESC");

    foreach ($levels as $level) {
        if ($points >= $level->min_points) {
            return $level->level_name;
        }
    }

    return null; 
}

function reward_points($action = null ,$challenge_id = null,$user_id, $reply_id = null, $isBarLike = null, $orderId = null){

	global $wpdb;
	
	$cur_user = wp_get_current_user();
	$challenge = $wpdb->get_row($wpdb->prepare("SELECT * FROM reward_challenges WHERE id = %d", $challenge_id));
	$return_msg = "";
	if ($challenge) {
		if($action == 'add'){
			if($reply_id != null){
				$querystore = $wpdb->prepare(
					"INSERT INTO user_reward_history (user_id, challenge_id, points_earned, post_id) VALUES (%d, %d, %d, %d)",
					$user_id,
					$challenge->id,
					$challenge->points,
					$reply_id
				);
			}else if($isBarLike != null){
				$querystore = $wpdb->prepare(
					"INSERT INTO user_reward_history (user_id, challenge_id, points_earned, bar_like_id) VALUES (%d, %d, %d, %d)",
					$user_id,
					$challenge->id,
					$challenge->points,
					$isBarLike
				);
			}else if($orderId != null){
				$querystore = $wpdb->prepare(
					"INSERT INTO user_reward_history (user_id, challenge_id, points_earned, order_id) VALUES (%d, %d, %d, %s)",
					$user_id,
					$challenge->id,
					$challenge->points,
					$orderId
				);
			}else{
				$querystore = $wpdb->prepare(
					"INSERT INTO user_reward_history (user_id, challenge_id, points_earned) VALUES (%d, %d, %d)",
					$user_id,
					$challenge->id,
					$challenge->points
				);
			}
			$wpdb->query($querystore);
		}else if($action == 'remove' && $reply_id != null){
			$sql = $wpdb->prepare("
			    DELETE FROM user_reward_history 
			    WHERE post_id = %d 
			    AND challenge_id = %d 
			    AND user_id = %d
			    AND points_earned = %d
			", $reply_id, $challenge_id, $user_id, $challenge->points);
			$wpdb->query($sql);
		}else if($action == 'remove' && $isBarLike != null){
			$sql = $wpdb->prepare("
			    DELETE FROM user_reward_history 
			    WHERE bar_like_id = %d 
			    AND challenge_id = %d 
			    AND user_id = %d
			    AND points_earned = %d
			", $isBarLike, $challenge_id, $user_id, $challenge->points);
			$wpdb->query($sql);
		}
		
		$querycheck = $wpdb->prepare("SELECT * FROM users_rewards WHERE user_id = %d", $user_id);
		$user_rewards = $wpdb->get_row($querycheck);
		
		//send reward_message for first ever like by user
		if($action == 'add'){
			$checkFirstLike = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $user_id, $challenge_id));
			if($checkFirstLike == 1){
				switch ($challenge_id) {
				    case 1:
				        $return_msg = "Congrats! You've got ".$challenge->points." point for completing your account profile.";
				        break;
				    case 6:
				        $return_msg = "Congrats! You've got ".$challenge->points." point for your first post.";
				        break;
				    case 7:
				        $return_msg = "Congrats! You've got ".$challenge->points." point on your first like.";
				        break;
				    case 8:
				        $return_msg = "Congrats! You've got ".$challenge->points." point for leaving first comment.";
				        break;
				    case 15:
				        $return_msg = "Congrats! You've got ".$challenge->points." point for creating first virtual bar.";
				        break;
				    case 17:
				        $return_msg = "Congrats! You've got ".$challenge->points." point for order.";
				        break;
				}
			}
		}
		
		if (!empty($user_rewards)) {
			if($action == 'add'){
				$total_points = $user_rewards->total_points + $challenge->points;
			}else if($action == 'remove'){
				$total_points = $user_rewards->total_points - $challenge->points;
			}

			$user_level = getUserLevelId($total_points);
			$user_level_name = getUserLevelName($total_points);
	
			$update_query = $wpdb->prepare(
				"UPDATE users_rewards SET total_points = %d, current_level_id = %d, updated_at = NOW() WHERE user_id = %d",
				$total_points,
				$user_level,
				$user_id
			);
			$wpdb->query($update_query);
			if ($user_rewards->current_level_id != $user_level) {
                $list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $user_id, 3));
                if ($user_rewards->current_level_id < $user_level && $list <= 4) {
                    reward_points("add", (int)3, $user_id);
                    $return_msg = "Congrats! You've leveled up to ".$user_level_name;
                    //email for leveled up
                    send_mail_for_levelup($cur_user->data->user_email, $cur_user->data->display_name, $user_level_name);
                } else if ($user_rewards->current_level_id > $user_level) {
                    $wpdb->query($wpdb->prepare("DELETE FROM user_reward_history WHERE user_id = %d AND challenge_id = %d ORDER BY id DESC LIMIT 1", 
                        $user_id, 3));
                    reward_points("remove", (int)3, $user_id);
                }
            }
		} else {
			$query_rewards = $wpdb->prepare(
				"INSERT INTO users_rewards (user_id, total_points, current_level_id) VALUES (%d, %d, %d)",
				$user_id,
				$challenge->points,
				1
			);
			$wpdb->query($query_rewards);
		}
	

		$updated = $wpdb->update(
			"{$wpdb->prefix}users",
			array('profile_edited' => 1),
			array('ID' => $user_id),
			array('%d'),
			array('%d')
		);
		return $return_msg;
	} else {
		echo "No challenge found with ID 10.<br>";
	}

}

function send_mail_for_levelup($to, $user_name, $level_name){

		$subject = "Congrats ".$user_name." – You Leveled Up to ".$level_name."!";
		$message = "
				<html>
				<head>
				  <style>
				    body { font-family: Arial, sans-serif; color: #333; }
				    .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; background-color: #f9f9f9; }
				    .header { background-color: #4CAF50; padding: 10px; color: white; text-align: center; }
				    .content { padding: 20px; }
				    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 20px; }
				  </style>
				</head>
				<body>
				  <div class='container'>
					  	<div style='background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;' >
				            <div style='text-align: center; vertical-align:middle; padding-top:22px;'><img style='width: 31%;' src='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png'></div>
				        </div>
				    <div class='header'>
				      <h2>🎉 Level Up Unlocked!</h2>
				    </div>
				    <div class='content'>
				      <p>Hi <strong>".$user_name."</strong>,</p>
				      <p>Congratulations! You've just leveled up to <strong>".$level_name."</strong>! 🚀</p>
				      <p>Keep engaging with the community and completing challenges to unlock even more rewards.</p>
				      <p>We're proud to have you with us on this journey!</p>
				      <p>- The Sipn Bourbon Team</p>
				    </div>
				   <div style='background: #2d2d2c; padding:10px; float:left; width: 100%; text-align: center;'>
					    <div style='text-align: center; vertical-align:middle; padding-top:0px;'>
					        <img style='width: 10%;' src='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-footer.png'>
					    </div>

					    <span style='color:white; margin-top: 5px; text-align: center; display: block; font-size: 14px;'> 
					        Please click on <a href='https://sipnbourbon.com/wp-json/users/v2/unsubscribeemail?email=".$to."' style='color: #bca665; text-decoration: none;'>Unsubscribe</a> to stop receiving emails from SIPN. 
					    </span>

					    <div class='clearfix'></div>
					    <ul style='padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block; float: left; '>
					        <li style='margin-right:2px; display: inline-block;'>
					            <a href='https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=' target='_blank'>
					                <img src='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png' style='max-width: 20px;'>
					            </a>
					        </li>
					        <li style='margin-right:2px; display: inline-block;'>
					            <a href='https://www.facebook.com/sipnbourbon' target='_blank'>
					                <img src='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png' style='max-width: 20px;'>
					            </a>
					        </li>
					        <li style='display: inline-block;'>
					            <a href='https://twitter.com/sipnbourbon' target='_blank'>
					                <img src='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter-white.png' style='max-width: 20px;'>
					            </a>
					        </li>
					    </ul>
					    <div class='clearfix'></div>
					    <ul style='padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;'>
					        <li style='margin-right:3px; display: inline-block;'>
					            <a style='color: #bca665;' href='https://sipnbourbon.com/terms'>Terms</a>
					        </li>
					        <li style='display: inline-block;'>
					            <a style='color: #bca665;' href='https://sipnbourbon.com/privacy-policy'>Privacy Policy</a>
					        </li>
					    </ul>
					    <div>
					</div>

				  </div>
				</body>
				</html>
				";
		$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Sipn Bourbon <social@sipnbourbon.com>');
		if (wp_mail($to, $subject, $message, $headers)) {
			return true;
		} else {
			return false;
		}
}
function handle_user_reward_history(WP_REST_Request $request) {
    global $wpdb;

    // Get user ID from request
    $user_id = get_current_user_id();

    if (!$user_id) {
        return new WP_Error('unauthorized', 'User not logged in', ['status' => 401]);
    }

    // Fetch reward history for the user
    $query = $wpdb->prepare(
        "SELECT 
            urh.id,
            urh.user_id,
            urh.challenge_id,
            rc.challenge_name,
            urh.post_id,
            urh.points_earned,
            urh.created_at
        FROM user_reward_history urh
        LEFT JOIN reward_challenges rc ON urh.challenge_id = rc.id
        WHERE urh.user_id = %d
        ORDER BY urh.created_at DESC",
        $user_id
    );

    $reward_history = $wpdb->get_results($query, ARRAY_A);

    if (!$reward_history) {
        return new WP_Error('no_data', 'No reward history found for this user', ['status' => 404]);
    }

    return rest_ensure_response($reward_history);
}



function handle_challanges_dashboard(WP_REST_Request $request) {
    global $wpdb;

    $user_id = get_current_user_id();
    // Get total points from users_rewards
    $total_points_earned = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COALESCE(total_points, 0) 
             FROM users_rewards 
             WHERE user_id = %d",
            $user_id
        )
    );

    // Get the next min_points from levels table based on total_points_earned
    $next_level_points = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT min_points 
             FROM levels 
             WHERE min_points > %d 
             ORDER BY min_points ASC 
             LIMIT 1",
            $total_points_earned
        )
    );

	$level_name = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT level_name 
             FROM levels 
             WHERE min_points < %d 
             ORDER BY min_points DESC 
             LIMIT 1",
            $total_points_earned
        )
    );

    // If user's points are below the lowest level, show earned points instead
    $total_points = $next_level_points > 0 ? $next_level_points : $total_points_earned;

    // Fetch challenge categories
    $categories = $wpdb->get_results(
        "SELECT id AS category_id, category_name FROM challenge_categories order by category_id ASC",
        ARRAY_A
    );

    // Query to get challenge-wise breakdown from user_reward_history
    $query = $wpdb->prepare(
        "SELECT  
            cc.id AS category_id,
            cc.category_name,
            rc.id AS challenge_id,
            rc.challenge_name,
            COALESCE(SUM(urh.points_earned), 0) AS total_points_earned
        FROM reward_challenges rc
        JOIN challenge_categories cc ON rc.category_id = cc.id
        LEFT JOIN user_reward_history urh  
            ON rc.id = urh.challenge_id  
            AND urh.user_id = %d
        GROUP BY cc.id, rc.id
        ORDER BY cc.id, rc.id",
        $user_id
    );

    $challenges = $wpdb->get_results($query, ARRAY_A);

    // Result structure
    $result = [
        'total_points' => $total_points,  
		'level' => $level_name,
        'total_points_earned' => $total_points_earned,  
        'categories' => []
    ];

    // Initialize categories
    foreach ($categories as $category) {
        $category_id = $category['category_id'];
        $result['categories'][$category_id] = [
            'category_name' => $category['category_name'],
            'total_points_earned' => 0,
            'challenges' => []
        ];
    }

    // Process challenges
    foreach ($challenges as $row) {
        $category_id = $row['category_id'];
        $points_earned = (int) $row['total_points_earned'];

        $result['categories'][$category_id]['challenges'][] = [
            'challenge_name' => $row['challenge_name'],
            'points_earned' => $points_earned
        ];

        // Sum total points earned per category and overall
        $result['categories'][$category_id]['total_points_earned'] += $points_earned;
    }

    return rest_ensure_response($result);
}



function handle_event_tracking(WP_REST_Request $request){
	global $wpdb;
	$item = $request->get_json_params();
	$fields = array();
	$values = array();
	foreach ($item as $key => $val) {
		array_push($fields, preg_replace("/[^A-Za-z0-9_]/", '', $key));
		array_push($values, $wpdb->prepare('%s', $val));
	}
	$fields = implode(", ", $fields);
	$values = implode(", ", $values);
	// return true;exit;

	// echo json_encode($item);
	$upc = $item['upc'];
	$action = $item['action'];
	$device_type = $item['device_type'];
	if($upc && $action){
		$query = $wpdb->prepare("INSERT INTO `wp_event_tracking` (`upc`, `event_type`, `click_hit`, `device_type`) VALUES ('$upc', '$action', 1, '$device_type') ON DUPLICATE KEY UPDATE click_hit = click_hit + 1, updated_at = CURRENT_TIMESTAMP");
		$res = $wpdb->query($query);
		// echo 'Query '.$query;
		if ($res) {
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
// echo $item['action'];
}

function handle_get_all_bars($data)
{
	global $wpdb;
	$query = "SELECT * FROM `wp_bar`";
	$list = $wpdb->get_results($query);
	return $list;
}

function is_bar_exist($owner_email)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_bar` WHERE owner_email = '%s'", $owner_email);
	$list = $wpdb->get_results($query);

	if ($list[0]->cnt >= 1) {
		return true;
	} else {
		return false;
	}
}

function get_bar_count_by_owner_email($owner_email)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_bar` WHERE owner_email = '%s'", $owner_email);
	$list = $wpdb->get_results($query);
	return $list;
}


function get_bar_details($bar_id)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM `wp_bar` WHERE id = '%d'", $bar_id);
	$list = $wpdb->get_row($query);
	return $list;
}

function get_shelf_details($shelf_id)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves` WHERE id = '%d'", $shelf_id);
	$list = $wpdb->get_row($query);
	return $list;
}

function handle_bar_add(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$fields = array();
	$values = array();
	foreach ($item as $key => $val) {
		array_push($fields, preg_replace("/[^A-Za-z0-9_]/", '', $key));
		array_push($values, $wpdb->prepare('%s', $val));
	}
	$fields = implode(", ", $fields);
	$values = implode(", ", $values);
	$query = $wpdb->prepare("INSERT INTO `wp_bar` ($fields) VALUES ($values)");
	$res = $wpdb->query($query);
	if ($res) {
		$query = $wpdb->prepare("SELECT * FROM `wp_bar` WHERE owner_email = '%s'", $item["owner_email"]);
		$list = $wpdb->get_results($query);
		//return $list;
		$bar_id = $list[0]->id;
		$ins1_query = $wpdb->prepare("INSERT INTO `wp_bar_shelves` (`id`, `name`, `bar_id`, `weight`) VALUES ('', 'Top Shelf', $bar_id, '1')");
		$res1_query = $wpdb->query($ins1_query);

		$ins2_query = $wpdb->prepare("INSERT INTO `wp_bar_shelves` (`id`, `name`, `bar_id`, `weight`) VALUES ('', 'Mid Shelf', $bar_id, '2')");
		$res2_query = $wpdb->query($ins2_query);

		$ins3_query = $wpdb->prepare("INSERT INTO `wp_bar_shelves` (`id`, `name`, `bar_id`, `weight`) VALUES ('', 'Well', $bar_id, '3')");
		$res3_query = $wpdb->query($ins3_query);

		$cur_user = wp_get_current_user();

		$bar_output = array();
		$bar_output['shelves'] = array();
		$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $cur_user->data->user_email);
		$shelves = $wpdb->get_results($query);
		//print_r($shelves);
		$bar_output['bar_id'] = $shelves[0]->bar_id;
		$bar_output['bar_name'] = $shelves[0]->bar_name;
		foreach ($shelves as $shelf) {

			$products_query = $wpdb->prepare("SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight  FROM wp_bar_shelves bs LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id LEFT JOIN wp_posts p ON bsp.product_id = p.id WHERE bsp.shelve_id = '%d' ORDER BY product_weight ASC", $shelf->shelf_id);
			$prods = $wpdb->get_results($products_query);
			$the_prods = array();
			foreach ($prods as $prod) {
				$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
				$product_small_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
				array_push($the_prods, array('product_id' => $prod->product_id, 'product_name' => $prod->product_name, 'product_weight' => $prod->product_weight, 'product_image' => $product_image, 'product_sm_image' => $product_small_image));
			}

			array_push($bar_output['shelves'], array('shelf_id' => $shelf->shelf_id, 'shelf_name' => $shelf->shelf_name, 'shelf_weight' => $shelf->shelf_weight, 'products' => $the_prods));
		}
		return $bar_output;

	}
}

function handle_bar_edit(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$query = $wpdb->prepare("UPDATE `wp_bar` SET name='%s', shared='%d' WHERE owner_email = '%s'", $item["name"], $item["public"], $item["owner_email"]);
	$res = $wpdb->query($query);

	$query = $wpdb->prepare("SELECT * FROM `wp_bar` WHERE owner_email = '%s'", $item["owner_email"]);
	$list = $wpdb->get_results($query);
	return $list;
}


function handle_barshelf_add(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$fields = array();
	$values = array();
	foreach ($item as $key => $val) {
		array_push($fields, preg_replace("/[^A-Za-z0-9_]/", '', $key));
		array_push($values, $wpdb->prepare('%s', $val));
	}
	$fields = implode(", ", $fields);
	$values = implode(", ", $values);
	$query = $wpdb->prepare("INSERT INTO `wp_bar_shelves` ($fields) VALUES ($values)");
	$res = $wpdb->query($query);
	if ($res) {
		$query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves` WHERE id = '%s'", $res);
		$list = $wpdb->get_results($query);
		return $list;
	}
}

function handle_barshelf_edit(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$query = $wpdb->prepare("UPDATE `wp_bar_shelves` SET name='%s', weight='%d' WHERE id='%d'", $item["name"], $item["weight"], $item["id"]);
	$res = $wpdb->query($query);
	if ($res) {
		$query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves` WHERE id = '%d'", $item["id"]);
		$list = $wpdb->get_results($query);
		return $list;
	}
}

function handle_barshelf_product_add(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$shelve_id = $item['shelve_id'];
	$Weight = $item['weight'];
	$product_id = $item['product_id'];

	// $query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves` WHERE bar_id = '%d'", $item["bar_id"]);
	// $list = $wpdb->get_results($query);

	// $shelf1 = $list[0]->id;
	// $shelf2 = $list[1]->id;
	// $shelf3 = $list[2]->id;
	// $product_exist = $wpdb->get_var($wpdb->prepare("SELECT count(1) FROM wp_bar_shelves_products WHERE (shelve_id =  %d  OR shelve_id =  %d OR shelve_id =  %d) AND product_id = %d", 
	// 	$shelf1,
	// 	$shelf2,
	// 	$shelf3,
	// 	$product_id
	// ));

	// if($product_exist == 1){
	// 	return new WP_Error( 'rest_forbidden', 'Product already exists in bar', array( 'status' => 403 ) );
	// }


	// position is filled
	$filled_position = $wpdb->get_var($wpdb->prepare(
		"SELECT count(1) FROM wp_bar_shelves_products WHERE shelve_id =  %d  AND weight = %d",
		$shelve_id,
		$Weight
	));


	if ($filled_position == 1) {
		$query = $wpdb->prepare(
			"UPDATE wp_bar_shelves_products SET product_id = %d WHERE shelve_id = %d AND weight =%d",
			$product_id,
			$shelve_id,
			$Weight
		);
	} else {
		$query = $wpdb->prepare(
			"INSERT INTO `wp_bar_shelves_products` (shelve_id,product_id,weight) VALUES (%d,%d,%d)",
			$shelve_id,
			$product_id,
			$Weight
		);
	}


	$res = $wpdb->query($query);

	if ($res) {


$return_msg = "";
		$cur_user = wp_get_current_user();
		$bar_output = array();
		$bar_output['shelves'] = array();
		$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $cur_user->data->user_email);
		$shelves = $wpdb->get_results($query);

		if (!empty($shelves)) {

			$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $cur_user->data->ID, 15));
			if($list == 0){
				$return_msg = reward_points('add', (int)15, $cur_user->data->ID);
			}
			$bar_output['bar_id'] = $shelves[0]->bar_id;
			$bar_output['bar_name'] = $shelves[0]->bar_name;
			foreach ($shelves as $shelf) {

				$products_query = $wpdb->prepare("SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight  FROM wp_bar_shelves bs LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id LEFT JOIN wp_posts p ON bsp.product_id = p.id WHERE bsp.shelve_id = '%d' ORDER BY product_weight ASC", $shelf->shelf_id);
				$prods = $wpdb->get_results($products_query);
				$the_prods = array();
				if (!empty($prods)) {
					foreach ($prods as $prod) {
						$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
						$product_small_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
						array_push($the_prods, array('product_id' => $prod->product_id, 'product_name' => $prod->product_name, 'product_weight' => $prod->product_weight, 'product_image' => $product_image, 'product_sm_image' => $product_small_image));
					}
				}
				array_push($bar_output['shelves'], array('shelf_id' => $shelf->shelf_id, 'shelf_name' => $shelf->shelf_name, 'shelf_weight' => $shelf->shelf_weight, 'products' => $the_prods));

			}
			$bar_output["reward_message"] = $return_msg;
		}
		return $bar_output;

	} else {
		return new WP_Error('rest_forbidden', 'Product already exists in shelf', array('status' => 403));
	}
}

function handle_barshelf_product_delete(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();


	$query = $wpdb->prepare("SELECT weight FROM `wp_bar_shelves_products` WHERE shelve_id = '%d' AND product_id = '%d'", $item["shelve_id"], $item["product_id"]);
	$deleted_product_weight = $wpdb->get_var($query);

	if ($deleted_product_weight !== null) {

		$query = $wpdb->prepare("UPDATE `wp_bar_shelves_products` SET product_id = 0  WHERE shelve_id = '%d' AND weight = '%d'", $item["shelve_id"], $deleted_product_weight);
		$res = $wpdb->query($query);

		if ($res) {
			$query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves_products` WHERE shelve_id = '%d'", $item['shelve_id']);
			$list = $wpdb->get_results($query);
			return $list;
		} else {
			return new WP_Error('rest_forbidden', 'Error deleting the product', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_invalid', 'Product not found', array('status' => 404));
	}
}


function handle_barshelf_product_reorder(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$weights = $item['product_orders'];
	foreach ($weights as $product) {
		$query = $wpdb->prepare("UPDATE `wp_bar_shelves_products` SET weight='%d' WHERE shelve_id = '%d' AND product_id = '%d'", $product['order'], $item["shelve_id"], $product['pid']);
		$res = $wpdb->query($query);
	}

	$query = $wpdb->prepare("SELECT * FROM `wp_bar_shelves_products` WHERE shelve_id = '%s'", $item['shelve_id']);
	$list = $wpdb->get_results($query);
	return $list;
}

// added by salman for shelf reorder in ios
function handle_barshelf_product_reorder_crossshelf(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	session_start();
	$weights = $item['product_orders'];


	if (!isset($item['product_orders'])) {
		// print_r(json_encode($item));
		return new WP_Error('rest_forbidden', 'Product orders are misssing.', array('status' => 403));
	}
	$_SESSION['weight'] = $weights;
	$delete_shelf_products = $wpdb->prepare("DELETE FROM `wp_bar_shelves_products` WHERE shelve_id = %d", $item["shelve_id"]);
	$res = $wpdb->query($delete_shelf_products);

	if (!isset($weights)) {
		$weights = $_SESSION['weight'];
	}


	foreach ($weights as $product) {

		$query = $wpdb->prepare(
			"INSERT INTO `wp_bar_shelves_products` (shelve_id, product_id, weight) VALUES (%d, %d, %d)",
			$product['new_shelve_id'],
			$product['pid'],
			$product['order']
		);
		$res = $wpdb->query($query);

		if ($product['old_shelve_id'] !== $product['new_shelve_id']) {
			// Delete the old entry for the product in the old shelf position
			$delete_query = $wpdb->prepare(
				"DELETE FROM `wp_bar_shelves_products` WHERE shelve_id = %d AND product_id = %d",
				$product['old_shelve_id'],
				$product['pid']
			);
			$wpdb->query($delete_query);

			// Update the weights of products with a weight greater than the current product's weight
			$update_query = $wpdb->prepare(
				"UPDATE `wp_bar_shelves_products` 
                SET weight = weight - 1 
                WHERE shelve_id = %d AND weight > %d",
				$product['old_shelve_id'],
				$product['order']
			);
			$wpdb->query($update_query);
		}
	}



	$cur_user = wp_get_current_user();

	$bar_output = array();
	$bar_output['shelves'] = array();
	$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, b.shared as shared, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $cur_user->data->user_email);
	$shelves = $wpdb->get_results($query);

	$barname = $shelves[0]->bar_name;
	if (empty($barname)) {
		$barname = $cur_user->data->display_name;
	}
	if (!empty($shelves)) {
		$bar_output['bar_id'] = $shelves[0]->bar_id;
		$bar_output['bar_name'] = $barname;
		$bar_output['is_public'] = $shelves[0]->shared;


		foreach ($shelves as $shelf) {
			$products_query = $wpdb->prepare(
				"SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight 
				 FROM wp_bar_shelves bs 
				 LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id 
				 LEFT JOIN wp_posts p ON bsp.product_id = p.id 
				 WHERE bsp.shelve_id = '%d' 
				 ORDER BY product_weight ASC",
				$shelf->shelf_id
			);
			$prods = $wpdb->get_results($products_query);
			$the_prods = array();
			$max_weight = 3; // Assuming a maximum of 15 bottles per shelf



			$existing_weights = array();


			if (!empty($prods)) {
				foreach ($prods as $prod) {

					$existing_weights[] = (int) $prod->product_weight;
					$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
					$product_small_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
					$prod_price = (float) get_post_meta($prod->product_id, '_price', true);

					array_push($the_prods, array(
						'product_id' => $prod->product_id,
						'product_name' => $prod->product_name,
						'product_weight' => $prod->product_weight,
						'product_image' => isset($product_image) ? ($product_image == false ? 'null' : $product_image) : 'null',
						'product_sm_image' => isset($product_small_image) ? ($product_small_image == false ? 'null' : $product_small_image) : 'null',
						'product_price' => $prod_price
					));
				}
			}

			// Check for missing weights and add default data
			for ($weight = 1; $weight <= $max_weight; $weight++) {
				if (!in_array($weight, $existing_weights)) {
					array_push($the_prods, array(
						'product_id' => null,
						'product_name' => null,
						'product_weight' => (string) $weight,
						'product_image' => null,
						'product_sm_image' => null,
						'product_price' => null
					));
				}
			}

			usort($the_prods, function ($a, $b) {
				return $a['product_weight'] - $b['product_weight'];
			});

			array_push($bar_output['shelves'], array(
				'shelf_id' => $shelf->shelf_id,
				'shelf_name' => $shelf->shelf_name,
				'shelf_weight' => $shelf->shelf_weight,
				'products' => $the_prods
			));
		}

		$user_details = get_user_meta($cur_user->data->ID);



		$my_profile = array();
		if ($cur_user->data->ID) {
			$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
			$my_profile['user_id'] = $cur_user->data->ID;
			$my_profile['user_email'] = $cur_user->data->user_email;
			$my_profile['name'] = $cur_user->data->display_name;
			$my_profile['phone_number'] = $user_details['phone_number'][0];
			$my_profile['bio'] = $user_details['bio'][0];
			$my_profile['address'] = $user_details['address'][0];
			$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
			$my_profile['city'] = $user_details['city'][0];
			$my_profile['state'] = $user_details['state'][0];
			$my_profile['zipcode'] = $user_details['zipcode'][0];
			$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
			$my_profile['is_verified'] = $user_details['is_verified'][0];
			$my_profile['avatar'] = $avatar;
			$my_profile['likes'] = get_likes_count($cur_user->data->ID);
			$my_profile['is_profile_liked'] = get_profile_like_flag($cur_user->data->ID); //by sumeeth for self like
			$bar_output['user_details'] = $my_profile;
		}

		$bar_output['bar_link'] = bbp_get_user_profile_url($cur_user->data->ID);

		unset($_SESSION['weight']);
		return $bar_output;
	} else {
		return new WP_Error('rest_forbidden', 'Bar does not exist.', array('status' => 403));
	}


}



function handle_barshelf_update(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$shelves = $item['shelves'];
	$bar_id = $item['bar_id'];
	foreach ($shelves as $product) {
		$shelve_id = $product['shelve_id'];
		$product_id = $product['pid'];
		$weight = $product['order'];

		$exists_query = $wpdb->prepare(
			"SELECT COUNT(*) FROM `wp_bar_shelves_products` WHERE shelve_id = %d AND weight = %d",
			$shelve_id,
			$weight
		);
		$exists = $wpdb->get_var($exists_query);

		if ($exists) {
			$query = $wpdb->prepare(
				"UPDATE `wp_bar_shelves_products` SET shelve_id = %d, product_id = %d WHERE shelve_id = %d AND weight = %d",
				$shelve_id,
				$product_id,
				$shelve_id,
				$weight
			);
			$wpdb->query($query);
		} else {
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO `wp_bar_shelves_products` (shelve_id, product_id, weight) VALUES (%d, %d, %d)",
					$shelve_id,
					$product_id,
					$weight
				)
			);
		}
	}
	$query1 = $wpdb->prepare("SELECT * FROM `wp_bar_shelves_products` AS sp JOIN `wp_bar_shelves` AS s ON s.id = sp.shelve_id WHERE s.bar_id = %d AND sp.product_id <> 0", $bar_id);
	$list = $wpdb->get_results($query1);
	return $list;
}

function handle_get_my_bar(WP_REST_Request $request)
{
	global $wpdb;
	$cur_user = wp_get_current_user();

	$bar_output = array();
	$bar_output['shelves'] = array();
	$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, b.shared as shared, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $cur_user->data->user_email);
	$shelves = $wpdb->get_results($query);

	$barname = $shelves[0]->bar_name;
	if (empty($barname)) {
		$barname = $cur_user->data->display_name;
	}
	if (!empty($shelves)) {
		$bar_output['bar_id'] = $shelves[0]->bar_id;
		$bar_output['bar_name'] = $barname;
		$bar_output['is_public'] = $shelves[0]->shared;


		foreach ($shelves as $shelf) {
			$products_query = $wpdb->prepare(
				"SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight 
				 FROM wp_bar_shelves bs 
				 LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id 
				 LEFT JOIN wp_posts p ON bsp.product_id = p.id 
				 WHERE bsp.shelve_id = '%d' 
				 ORDER BY product_weight ASC",
				$shelf->shelf_id
			);
			$prods = $wpdb->get_results($products_query);
			$the_prods = array();
			$max_weight = 3; // Assuming a maximum of 15 bottles per shelf



			$existing_weights = array();


			if (!empty($prods)) {
				foreach ($prods as $prod) {

					$existing_weights[] = (int) $prod->product_weight;
					$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
					$product_small_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
					$prod_price = (float) get_post_meta($prod->product_id, '_price', true);

					array_push($the_prods, array(
						'product_id' => $prod->product_id,
						'product_name' => $prod->product_name,
						'product_weight' => $prod->product_weight,
						'product_image' => isset($product_image) ? ($product_image == false ? 'null' : $product_image) : 'null',
						'product_sm_image' => isset($product_small_image) ? ($product_small_image == false ? 'null' : $product_small_image) : 'null',
						'product_price' => $prod_price
					));
				}
			}

			// Check for missing weights and add default data
			for ($weight = 1; $weight <= $max_weight; $weight++) {
				if (!in_array($weight, $existing_weights)) {
					array_push($the_prods, array(
						'product_id' => null,
						'product_name' => null,
						'product_weight' => (string) $weight,
						'product_image' => null,
						'product_sm_image' => null,
						'product_price' => null
					));
				}
			}


			// if ($consecutive_null_count >= 3) {
			// 	$the_prods = array_slice($the_prods, 0, 3);
			// }

			usort($the_prods, function ($a, $b) {
				return $a['product_weight'] - $b['product_weight'];
			});

			array_push($bar_output['shelves'], array(
				'shelf_id' => $shelf->shelf_id,
				'shelf_name' => $shelf->shelf_name,
				'shelf_weight' => $shelf->shelf_weight,
				'products' => $the_prods
			));
		}

		$user_details = get_user_meta($cur_user->data->ID);

		$is_verified = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT `validate_email` FROM `wp_users` WHERE ID = %d",
				$cur_user->data->ID
			)
		);



		$my_profile = array();
		if ($cur_user->data->ID) {
			$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
			$my_profile['user_id'] = $cur_user->data->ID;
			$my_profile['user_email'] = $cur_user->data->user_email;
			$my_profile['name'] = $cur_user->data->display_name;
			$my_profile['phone_number'] = $user_details['phone_number'][0];
			$my_profile['bio'] = $user_details['bio'][0];
			$my_profile['address'] = $user_details['address'][0];
			$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
			$my_profile['city'] = $user_details['city'][0];
			$my_profile['state'] = $user_details['state'][0];
			$my_profile['zipcode'] = $user_details['zipcode'][0];
			$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
			$my_profile['is_verified'] = $is_verified;
			$my_profile['avatar'] = $avatar;
			$my_profile['likes'] = get_likes_count($cur_user->data->ID);
			$my_profile['is_profile_liked'] = get_profile_like_flag($cur_user->data->ID); //by sumeeth for self like
			$bar_output['user_details'] = $my_profile;
			$bar_output['total_rewards'] = get_total_rewards($cur_user->data->ID);
		}

		$bar_output['bar_link'] = bbp_get_user_profile_url($cur_user->data->ID);

		return $bar_output;
	} else {
		return new WP_Error('rest_forbidden', 'Bar does not exist.', array('status' => 403));
	}

}

function get_total_rewards($user_id){
	global $wpdb;
	$query = "SELECT total_points FROM `users_rewards` where user_id = ". $user_id;
	$rewards = $wpdb->get_var($query);
	return $rewards;
}
function get_likes_count($profile_id)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $profile_id);
	$list = $wpdb->get_results($query);
	if ($list[0]->cnt)
		return $list[0]->cnt;
	else
		return "0";
}
function handle_events_list(WP_REST_Request $request)
{
	global $wpdb;
	$all_events = array();
	$item = $request->get_json_params();
	$val = $item['value'];
	if ($val == '0') {
		$query_cond11 = "AND ( pm.meta_key = 'event_price' AND pm.meta_value ='' )";
	}
	$page = $item['page'] ? $item['page'] : 1;
	$events_per_page = $item['events_per_page'] ? $item['events_per_page'] : 10;
	$keyword = $item['keyword'] ? $item['keyword'] : '';
	$keyword = esc_sql(sanitize_text_field($keyword));
	$splitted = "%$keyword%";
	$query_cond = "";
	if ($keyword) {

		$query_cond .= " OR (";
		$query_cond .= " pm.meta_key='event_venue' AND pm.meta_value LIKE '%$keyword%' ";
		$query_cond .= ") ";
	}
	$day = date('Ymd');
	$query = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond )  AND p.post_type='events' AND p.post_status = 'publish' )";
	$products = $wpdb->get_results($query);
	foreach ($products as $key => $event) {
		$arr[] = $event->ID;
	}
	$query1 = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  (( pm.meta_key = 'event_start_date' AND pm.meta_value >=$day ) OR ( pm.meta_key = 'event_end_date' AND pm.meta_value >=$day ) )  AND p.post_type='events' AND p.post_status = 'publish' )";
	$products1 = $wpdb->get_results($query1);
	foreach ($products1 as $key => $event1) {
		$arr1[] = $event1->ID;
	}
	if ($arr1 == '') {
		$arr1 = array();
	}
	if ($arr == '') {
		$arr = array();
	}
	//print_r($arr1);exit;
	$result = array_intersect($arr, $arr1);
	$total = count($result);
	$bar_output['searched_keyword'] = $item['keyword'];
	$i = 0;
	foreach ($result as $event3) {
		$products_query = $wpdb->prepare("SELECT DISTINCT p.*,pm.* FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE p.ID=$event3 $query_cond11 GROUP BY post_title");
		$events1 = $wpdb->get_results($products_query);
		foreach ($events1 as $key => $event) {
			$event_start_date = date('jS M Y', strtotime(get_post_meta($event->ID, 'event_start_date', true)));
			$event_end_date = date('jS M Y', strtotime(get_post_meta($event->ID, 'event_end_date', true)));
			$event_id = $event->ID;
			$event_title = html_entity_decode($event->post_title);
			$event_image = get_the_post_thumbnail_url($event->ID, 'full');
			$post_name = $event->post_name;
			if (get_post_meta($event->ID, 'event_venue', true)) {
				$event_venue = get_post_meta($event->ID, 'event_venue', true);
			} else {
				$event_venue = (object) null;
			}
			$event_price = get_post_meta($event->ID, 'event_price', true);
			if (get_post_meta($event->ID, 'all_day_event', true)) {
				$event_start_time = 'All day';
				$event_end_time = 'All day';
			} else {
				$event_start_time = get_post_meta($event->ID, 'event_start_time', true);
				$event_end_time = get_post_meta($event->ID, 'event_end_time', true);
			}
			$the_prods[] = array('event_start_date' => $event_start_date, 'event_end_date' => $event_end_date, 'event_id' => $event_id, 'event_title' => $event_title, 'post_name' => $post_name, 'event_venue' => $event_venue, 'event_price' => $event_price, 'event_start_time' => $event_start_time, 'event_end_time' => $event_end_time, 'event_image' => $event_image);
			$bar_output['events'] = $the_prods;
			$i++;
		}
	}
	$bar_output['total_events'] = $i;
	return $bar_output;
}

// function handle_eventsweb_list( WP_REST_Request $request ){ 
// 	global $wpdb;
// 	$all_events = array();
// 	$item = $request->get_json_params();

// 	$page = $item['page'] ? $item['page'] : 1;
// 	$events_per_page = $item['events_per_page']? $item['events_per_page'] : 10;
// 	$keyword = $item['keyword'] ? $item['keyword'] : '';
// 	$keyword =  esc_sql(sanitize_text_field($keyword));
// 	$splitted="%$keyword%";

// 	$query_cond = "";
// 	if($keyword){
// 		//$key_word_arr = explode(' ', $keyword);
// 		//print_r($key_word_arr);
// 		$query_cond .= " OR (";
// 		//$s_cnt = 0;
// 	//	foreach($key_word_arr as $spiltted_key){

// 			//if($s_cnt>0)
// 		//	$query_cond .= " AND p.post_title LIKE '%$spiltted_key%' OR (pm.meta_key='event_venue' AND pm.meta_value LIKE '%$spiltted_key%') ";
// 			//else
// 			$query_cond .= " pm.meta_key='event_venue' AND pm.meta_value LIKE '%$keyword%' ";

// 			//$s_cnt++;
// 		//}
// 		$query_cond .= ") ";
// 	}
// 	$day=date('Ymd');
// 	$query = "(SELECT DISTINCT p.*,pm.* FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  (( pm.meta_key = 'event_start_date' AND pm.meta_value >=$day ) OR ( pm.meta_key = 'event_end_date' AND pm.meta_value >=$day ) ) AND p.post_type='events' AND p.post_status = 'publish'  AND (p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond   GROUP BY p.post_title  ORDER BY p.post_title ASC LIMIT 0, 10)";
// 	//print_r($query);exit;
// 	$products = $wpdb->get_results( $query);	


// 	if($keyword){
// 		$args['s'] = $keyword;
// 	}
// 	//print_r($args['s']);exit;

// 	$events = get_posts($args);
// 	//echo "<pre>";print_r($events);exit;

// 		$total = count($products);
// 		//print_r($total);exit;
// 	$all_events['total_events'] = $total;
// 	foreach($products as $key => $event){
// 		//echo "<pre>";print_r($event);exit;

// 		$all_events['events'][$key]['event_start_date']= date('jS M Y',strtotime(get_post_meta($event->ID, 'event_start_date', true))); 
// 		$all_events['events'][$key]['event_end_date']=date('jS M Y',strtotime(get_post_meta($event->ID, 'event_end_date', true)));
// 		$all_events['events'][$key]['event_id'] = $event->ID;
// 		$all_events['events'][$key]['event_title'] = $event->post_title;
// 		$all_events['events'][$key]['post_name'] = $event->post_name;
// 		//$all_events['events'][$key]['event_image'] = get_the_post_thumbnail_url( $event->ID, 'full' );
// 		//$all_events['events'][$key]['event_start_date'] = get_post_meta($event->ID, 'event_start_date', true);
// 		//$all_events['events'][$key]['event_end_date'] = get_post_meta($event->ID, 'event_end_date', true);
// 		if(get_post_meta($event->ID, 'event_venue', true)){
// 			$all_events['events'][$key]['event_venue'] = get_post_meta($event->ID, 'event_venue', true);
// 		}else{
// 			$all_events['events'][$key]['event_venue'] = (object) null;
// 		}
// 		$all_events['events'][$key]['event_price'] = get_post_meta($event->ID, 'event_price', true);


// 		if(get_post_meta($event->ID, 'all_day_event', true)){
// 			$all_events['events'][$key]['event_start_time'] = 'All day';
// 			$all_events['events'][$key]['event_end_time'] = 'All day';
// 		}else{
// 			$all_events['events'][$key]['event_start_time'] = get_post_meta($event->ID, 'event_start_time', true);
// 			$all_events['events'][$key]['event_end_time'] = get_post_meta($event->ID, 'event_end_time', true);
// 		}
// 	}
// 	return $all_events;
// }

function handle_eventsweb_list(WP_REST_Request $request)
{
	global $wpdb;
	$all_events = array();
	$item = $request->get_json_params();

	$page = $item['page'] ? $item['page'] : 1;
	$events_per_page = $item['events_per_page'] ? $item['events_per_page'] : 10;
	$keyword = $item['keyword'] ? $item['keyword'] : '';
	$keyword = esc_sql(sanitize_text_field($keyword));
	$splitted = "%$keyword%";

	$query_cond = "";
	if ($keyword) {

		$query_cond .= " OR (";
		$query_cond .= " pm.meta_key='event_venue' AND pm.meta_value LIKE '%$keyword%' ";
		$query_cond .= ") ";
	}
	$day = date('Ymd');
	$query = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond )  AND p.post_type='events' AND p.post_status = 'publish' GROUP BY p.post_title  ORDER BY p.post_title ASC )";
	$products = $wpdb->get_results($query);
	foreach ($products as $key => $event) {
		$arr[] = $event->ID;
	}
	$query1 = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  (( pm.meta_key = 'event_start_date' AND pm.meta_value >=$day ) OR ( pm.meta_key = 'event_end_date' AND pm.meta_value >=$day ) )  AND p.post_type='events' AND p.post_status = 'publish' GROUP BY p.post_title  ORDER BY p.post_title ASC )";
	$products1 = $wpdb->get_results($query1);
	foreach ($products1 as $key => $event1) {
		$arr1[] = $event1->ID;
	}
	$result = array_intersect($arr, $arr1);
	$total = count($result);
	$bar_output['total_events'] = $total;

	foreach ($result as $event3) {
		$products_query = $wpdb->prepare("SELECT DISTINCT p.*,pm.* FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE p.ID=$event3 GROUP BY post_title");
		$events1 = $wpdb->get_results($products_query);
		foreach ($events1 as $key => $event) {
			$event_start_date = date('jS M Y', strtotime(get_post_meta($event->ID, 'event_start_date', true)));
			$event_end_date = date('jS M Y', strtotime(get_post_meta($event->ID, 'event_end_date', true)));
			$event_id = $event->ID;
			$event_title = html_entity_decode($event->post_title);
			$post_name = $event->post_name;
			if (get_post_meta($event->ID, 'event_venue', true)) {
				$event_venue = get_post_meta($event->ID, 'event_venue', true);
			} else {
				$event_venue = (object) null;
			}
			$event_price = get_post_meta($event->ID, 'event_price', true);
			if (get_post_meta($event->ID, 'all_day_event', true)) {
				$event_start_time = 'All day';
				$event_end_time = 'All day';
			} else {
				$event_start_time = get_post_meta($event->ID, 'event_start_time', true);
				$event_end_time = get_post_meta($event->ID, 'event_end_time', true);
			}

			//$product_small_image = get_the_post_thumbnail_url( $prod->product_id, 'medium' );
			$the_prods[] = array('event_start_date' => $event_start_date, 'event_end_date' => $event_end_date, 'event_id' => $event_id, 'event_title' => $event_title, 'post_name' => $post_name, 'event_venue' => $event_venue, 'event_price' => $event_price, 'event_start_time' => $event_start_time, 'event_end_time' => $event_end_time);

			$bar_output['events'] = $the_prods;


		}



	}

	return $bar_output;


}


function handle_event_detail(WP_REST_Request $request)
{
	global $wpdb;
	$all_events = array();
	$item = $request->get_json_params();
	$event = get_post($item['ID']);

	if ($event->post_type == 'events' && $event->post_status == 'publish') {
		$all_events['event_id'] = $event->ID;
		$all_events['event_url'] = get_permalink($event->ID);
		$all_events['event_title'] = html_entity_decode($event->post_title);
		$all_events['event_image'] = get_the_post_thumbnail_url($event->ID, 'full');
		$all_events['event_start_date'] = get_post_meta($event->ID, 'event_start_date', true);
		$all_events['event_end_date'] = get_post_meta($event->ID, 'event_end_date', true);

		if (get_post_meta($event->ID, 'event_venue', true)) {
			$all_events['event_venue'] = get_post_meta($event->ID, 'event_venue', true);
		} else {
			$all_events['event_venue'] = (object) null;
		}
		$all_events['event_price'] = get_post_meta($event->ID, 'event_price', true);
		$all_events['event_desc'] = html_entity_decode($event->post_content);

		if (get_post_meta($event->ID, 'all_day_event', true)) {
			$all_events['event_start_time'] = 'All day';
			$all_events['event_end_time'] = 'All day';
		} else {
			$all_events['event_start_time'] = get_post_meta($event->ID, 'event_start_time', true);
			$all_events['event_end_time'] = get_post_meta($event->ID, 'event_end_time', true);
		}
		return $all_events;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid Event ID', array('status' => 403));
	}
}

function handle_blog_list(WP_REST_Request $request)
{
	global $wpdb;
	$all_posts = array();

	$events = get_posts(
		[
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'DESC'
		]
	);
	foreach ($events as $key => $post) {
		$all_posts[$key]['post_id'] = $post->ID;
		$all_posts[$key]['post_title'] = $post->post_title;

		if (get_the_post_thumbnail_url($post->ID, 'full'))
			$all_posts[$key]['post_image'] = get_the_post_thumbnail_url($post->ID, 'full');
		else
			$all_posts[$key]['post_image'] = '';

		$all_posts[$key]['post_desc'] = $post->post_content;

		$posttags = get_the_tags($post->ID);
		$tags = array();
		if ($posttags) {
			foreach ($posttags as $tag) {
				$tags[$tag->term_id] = $tag->name;
			}
		}
		$all_posts[$key]['post_tags'] = $tags;

		$categories = get_the_terms($post->ID, 'category');
		$cats = array();
		if ($categories) {
			foreach ($categories as $cat) {
				$cats[$cat->term_id] = $cat->name;
			}
		}
		$all_posts[$key]['post_categories'] = $cats;

	}
	return $all_posts;
}

function handle_blog_detail(WP_REST_Request $request)
{
	global $wpdb;
	$all_posts = array();
	$item = $request->get_json_params();
	$post = get_post($item['ID']);

	if ($post->post_type == 'post' && $post->post_status == 'publish') {
		$all_posts['post_id'] = $post->ID;
		$all_posts['post_title'] = $post->post_title;

		if (get_the_post_thumbnail_url($post->ID, 'full'))
			$all_posts['post_image'] = get_the_post_thumbnail_url($post->ID, 'full');
		else
			$all_posts['post_image'] = '';

		$all_posts['post_desc'] = $post->post_content;

		$posttags = get_the_tags($post->ID);
		$tags = array();
		if ($posttags) {
			foreach ($posttags as $tag) {
				$tags[$tag->term_id] = $tag->name;
			}
		}
		$all_posts['post_tags'] = $tags;

		$categories = get_the_terms($post->ID, 'category');
		$cats = array();
		if ($categories) {
			foreach ($categories as $cat) {
				$cats[$cat->term_id] = $cat->name;
			}
		}
		$all_posts['post_categories'] = $cats;

		return $all_posts;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid Post ID', array('status' => 403));
	}
}

function bar_get_product_category_by_id($category_id)
{
	global $wpdb;
	$term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');
	return $term['name'];
}

function bar_get_product_tag_by_id($tag_id)
{
	global $wpdb;
	$term = get_term_by('id', $tag_id, 'product_tag', 'ARRAY_A');
	return $term['name'];
}

function handle_products_list(WP_REST_Request $request)
{
	global $wpdb;
	$all_products = array();
	$item = $request->get_json_params();
	//print_r($item);exit;
	$all_products['request'] = $item;
	$page = $item['page'] ? $item['page'] : 1;
	$products_per_page = $item['products_per_page'] ? $item['products_per_page'] : 1000;
	$keyword = $item['keyword'] ? $item['keyword'] : '';
	$keyword12 = esc_sql(sanitize_text_field($keyword));
	$keyword = ltrim($keyword12, "0");
	//echo "serach ===>".$keyword;exit;
	// $item['sort_by']="popularity"; //by sumeeth only for popularity
	// $sort_by = isset($item['sort_by']) ? $item['sort_by'] : 'popularity';
	$sort_by = $item['sort_by'];
	$sort_type = $item['sort_type'] ? $item['sort_type'] : 'ASC';

	if ($sort_by == 'price' || $sort_by == 'product_price') {
		$sort_by = '_price';
	}
	if ($sort_by == 'rating' || $sort_by == 'product_rating') {
		$sort_by = '_wc_average_rating';
	}
	if ($sort_by == 'popularity' || $sort_by == 'product_popularity') {
		$sort_by = 'popularity';
		$sort_type = 'DESC';
	}

	$query_cond = "";
	if ($keyword) {
		$key_word_arr = explode(' ', $keyword);
		//print_r($key_word_arr);exit;
		$query_cond .= " OR (";
		$s_cnt = 0;
		foreach ($key_word_arr as $spiltted_key) {

			if ($s_cnt > 0)
				$query_cond .= " AND p.post_title LIKE '%$spiltted_key%' ";
			else
				$query_cond .= " p.post_title LIKE '%$spiltted_key%' ";

			$s_cnt++;
		}
		$query_cond .= ") ";
	}




	if (is_numeric($keyword)) {
		$keyword_1 = substr($keyword, 1);
		if (strlen($keyword) > 5) {
			$query = "(SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond) AND pm.meta_key='$sort_by' AND p.post_type='product' AND p.post_status = 'publish')
		UNION
		(SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$keyword%' AND p.post_type='product' AND p.post_status = 'publish' GROUP BY p.ID)";
		} else {
			$query = "(SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond) AND pm.meta_key='$sort_by' AND p.post_type='product' AND p.post_status = 'publish')";
		}
	} else {
		$query = "(SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond) AND pm.meta_key='$sort_by' AND p.post_type='product' AND p.post_status = 'publish')
		UNION
		(SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$keyword%' AND p.post_type='product' AND p.post_status = 'publish' GROUP BY p.ID)";
	}
	//$item['price_limit'] = '1';
	//print_r($query);exit;
	if ($item['price_limit']) {

		$price_limit = explode('-', $item['price_limit']);
		if ($price_limit[1] != '') {
			$conditional_query = "SELECT DISTINCT Cond_Table.ID as ID, Cond_Table.post_title as post_title, Cond_Table.post_content as post_content, Cond_Table.post_content as post_date, Cond_Table.meta_value as meta_value FROM (${query}) AS Cond_Table LEFT JOIN wp_postmeta pm2 ON Cond_Table.ID = pm2.post_id WHERE pm2.meta_key='_price' AND pm2.meta_value>=$price_limit[0] AND pm2.meta_value<=$price_limit[1]";
		} else {
			$conditional_query = "SELECT DISTINCT Cond_Table.ID as ID, Cond_Table.post_title as post_title, Cond_Table.post_content as post_content, Cond_Table.post_content as post_date, Cond_Table.meta_value as meta_value FROM (${query}) AS Cond_Table LEFT JOIN wp_postmeta pm2 ON Cond_Table.ID = pm2.post_id WHERE pm2.meta_key='_price' AND pm2.meta_value>=$price_limit[0]";
		}
		$total_query = "SELECT COUNT(1) FROM (${conditional_query}) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page * $products_per_page) - $products_per_page;
		//echo $conditional_query . " ORDER BY CONVERT(Cond_Table.meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}";
		$products = $wpdb->get_results($conditional_query . " ORDER BY CONVERT(Cond_Table.meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}");
		if (empty($products)) {
			$ins1_query1 = $wpdb->prepare("INSERT INTO `wp_search_productlist` (`id`, `keyword`) VALUES ('',  '$keyword')");
			$res1_query1 = $wpdb->query($ins1_query1);
			if (strpos($keyword, '#') !== false) {
				$keyword1 = $keyword;
			} else {
				$keyword1 = '#' . $keyword;
			}
			$chilupcquery = "SELECT cu.master_upc FROM wp_childupcs cu WHERE find_in_set('$keyword1',cu.child_upc)";
			//print_r($chilupcquery);exit;
			$mastername = $wpdb->get_results($chilupcquery);
			$fm = $mastername[0]->master_upc;
			if (!empty($fm)) {
				$childupc_query = "SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$fm%' AND p.post_type='product' AND p.post_status = 'publish' GROUP BY p.ID";
				$products = $wpdb->get_results($childupc_query);
				$total = count($products);
			}
		}
	} else if ($item['rating_limit']) {

		$rating_limit = $item['rating_limit'];
		$conditional_query = "SELECT DISTINCT Cond_Table.ID as ID, Cond_Table.post_title as post_title, Cond_Table.post_content as post_content, Cond_Table.post_content as post_date, Cond_Table.meta_value as meta_value FROM (${query}) AS Cond_Table LEFT JOIN wp_postmeta pm2 ON Cond_Table.ID = pm2.post_id WHERE pm2.meta_key='_wc_average_rating' AND pm2.meta_value>=$rating_limit[0]";

		$total_query = "SELECT COUNT(1) FROM (${conditional_query}) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page * $products_per_page) - $products_per_page;
		// echo $conditional_query . " ORDER BY CONVERT(pm2.meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}";
		// exit;
		$products = $wpdb->get_results($conditional_query . " ORDER BY CONVERT(pm2.meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}");

		if (empty($products)) {
			$ins1_query2 = $wpdb->prepare("INSERT INTO `wp_search_productlist` (`id`, `keyword`) VALUES ('',  '$keyword')");
			$res1_query2 = $wpdb->query($ins1_query2);
			if (strpos($keyword, '#') !== false) {
				$keyword1 = $keyword;
			} else {
				$keyword1 = '#' . $keyword;
			}
			$chilupcquery = "SELECT cu.master_upc FROM wp_childupcs cu WHERE find_in_set('$keyword1',cu.child_upc)";
			//print_r($chilupcquery);exit;
			$mastername = $wpdb->get_results($chilupcquery);
			$fm = $mastername[0]->master_upc;
			if (!empty($fm)) {
				$childupc_query = "SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$fm%' AND p.post_type='product' AND p.post_status = 'publish' GROUP BY p.ID";
				$products = $wpdb->get_results($childupc_query);
				$total = count($products);
			}
		}
		// print_r($products);
		// exit;
	} else {
		//$conditional_query = "SELECT DISTINCT Cond_Table.ID as ID, Cond_Table.post_title as post_title, Cond_Table.post_content as post_content, Cond_Table.post_content as post_date, Cond_Table.meta_value as meta_value FROM (${query}) AS Cond_Table LEFT JOIN wp_postmeta pm2 ON Cond_Table.ID = pm2.post_id";

		$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page * $products_per_page) - $products_per_page;
		//echo $query . " ORDER BY CONVERT(meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}";
		$products = $wpdb->get_results($query . " ORDER BY CONVERT(meta_value, DECIMAL(10,2)) $sort_type LIMIT ${offset}, ${products_per_page}");
		if (empty($products)) {
			//for saving no result found value in db by singh
			$ins1_query = $wpdb->prepare("INSERT INTO `wp_search_productlist` (`id`, `keyword`) VALUES ('',  '$keyword')");
			$res1_query = $wpdb->query($ins1_query);
			if (strpos($keyword, '#') !== false) {
				$keyword1 = $keyword;
			} else {
				$keyword1 = '#' . $keyword;
			}
			$chilupcquery = "SELECT cu.master_upc FROM wp_childupcs cu WHERE find_in_set('$keyword1',cu.child_upc)";
			//print_r($chilupcquery);exit;
			$mastername = $wpdb->get_results($chilupcquery);
			$fm = $mastername[0]->master_upc;
			if (!empty($fm)) {
				$childupc_query = "SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$fm%' AND p.post_type='product' AND p.post_status = 'publish' GROUP BY p.ID";
				$products = $wpdb->get_results($childupc_query);
				$total = count($products);
			}
		}

	}
	$all_products['total_products'] = $total;
	if ($total == 0) {
		$all_products['products'] = [];
	}
	//print_r($total);exit;
	foreach ($products as $key => $product) {
		$the_product = wc_get_product($product->ID);
		//print_r($the_product);
		$all_products['products'][$key]['product_id'] = $product->ID;
		$all_products['products'][$key]['product_title'] = $product->post_title;
		$all_products['products'][$key]['product_image'] = get_the_post_thumbnail_url($product->ID, 'full');
		$all_products['products'][$key]['product_sm_image'] = get_the_post_thumbnail_url($product->ID, 'medium');
		$all_products['products'][$key]['product_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $product->post_content));
		$all_products['products'][$key]['product_price'] = $the_product->price;
		//$all_products['list'][$key]['product_regular_price'] = $the_product->regular_price;
		$all_products['products'][$key]['product_link'] = get_permalink($product->ID);
		$all_products['products'][$key]['product_flavor'] = get_post_meta($product->ID, 'flavor', true);
		$all_products['products'][$key]['product_short_desc'] = $the_product->short_description;
		$all_products['products'][$key]['product_sku'] = $the_product->sku;
		$all_products['products'][$key]['product_rating'] = $the_product->average_rating;
		$all_products['products'][$key]['product_rating_count'] = $the_product->review_count;



		$prod_cats = array();
		foreach ($the_product->category_ids as $prod_cat_id) {
			$prod_cat = bar_get_product_category_by_id($prod_cat_id);
			$prod_cats[$prod_cat_id] = $prod_cat;
		}
		//$all_products['products'][$key]['product_categories'] = $prod_cats;
		if (count($the_product->category_ids) > 0) {
			$all_products['products'][$key]['product_categories'] = $prod_cats;
		} else {
			$all_products['products'][$key]['product_categories'] = json_encode(new stdClass);
		}


		$prod_tags = array();
		foreach ($the_product->tag_ids as $prod_tag_id) {
			$prod_tag = bar_get_product_tag_by_id($prod_tag_id);
			$prod_tags[$prod_tag_id] = $prod_tag;
		}
		//$all_products['products'][$key]['product_tags'] = $prod_tags;

		if (count($the_product->tag_ids) > 0) {
			$all_products['products'][$key]['product_tags'] = $prod_tags;
		} else {
			$all_products['products'][$key]['product_tags'] = json_encode(new stdClass);
		}

	}
	return $all_products;
}


//Added by salman on   23rd Aug 2024
function handle_products_list_new(WP_REST_Request $request)
{

	global $wpdb;
	$all_products = [];
	$total = 0;
    $cur_user_id = get_current_user_id();
	$item = $request->get_json_params();
	$page = !empty($item['page']) ? $item['page'] : 1;
	$products_per_page = !empty($item['products_per_page']) ? $item['products_per_page'] : 1000;
	$keyword = ltrim(esc_sql(sanitize_text_field($item['keyword'] ?? '')), "0");
	$sort_by = $item['sort_by'] ?? '';
	$sort_type = $item['sort_type'] ?? 'ASC';

	$sort_by_map = [
		'price' => '_price',
		'product_price' => '_price',
		'rating' => '_wc_average_rating',
		'product_rating' => '_wc_average_rating',
		'popularity' => 'popularity',
		'price_limit' => '25-50'
	];
	$sort_by = $sort_by_map[$sort_by] ?? $sort_by;

	if ($sort_by === 'popularity') {
		$sort_type = 'DESC';
	}


	$query_cond = '';
	if ($keyword) {
		$keywords = explode(' ', $keyword);
		$query_cond = " OR (" . implode(' AND ', array_map(function ($kw) {
			return "p.post_title LIKE '%$kw%'";
		}, $keywords)) . ")";
	}


	$price_limit = !empty($item['price_limit']) ? explode('-', $item['price_limit']) : [];
	$rating_limit = $item['rating_limit'] ?? '';

	$base_query = "
        SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value
        FROM wp_posts p
        LEFT JOIN wp_postmeta pm ON p.ID = pm.post_id
        WHERE (
            p.post_title LIKE '%$keyword%' 
            OR REPLACE(p.post_title, \"'\", '') LIKE '%$keyword%' 
            $query_cond
            OR (pm.meta_key LIKE '%upc%' AND pm.meta_value LIKE '%$keyword%')
        ) 
        AND p.post_type = 'product' 
        AND p.post_status = 'publish'";



	if (!empty($sort_by) && $sort_by == '_price') {
		$query_price_limit = $base_query . " AND pm.meta_key = '_price'";
	}
	if (!empty($price_limit[0])) {
		$query_price_limit .= " AND CAST(pm.meta_value AS DECIMAL(10,2)) >= {$price_limit[0]}";
	}
	if (!empty($price_limit[1])) {
		$query_price_limit .= " AND CAST(pm.meta_value AS DECIMAL(10,2)) <= {$price_limit[1]}";
	}

	if (!empty($rating_limit)) {
		$query_rating = $base_query . " AND pm.meta_key = '_wc_average_rating' AND  CAST(pm.meta_value AS DECIMAL(10,2)) >= {$rating_limit}";
	}

	if ($query_price_limit) {
		$total_query = "SELECT COUNT(1) FROM ($query_price_limit GROUP BY p.ID) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page - 1) * $products_per_page;

		$products = $wpdb->get_results("$query_price_limit GROUP BY p.ID ORDER BY CONVERT(pm.meta_value, DECIMAL(10,2)) $sort_type LIMIT $offset, $products_per_page");

	} else if ($query_rating) {
		$total_query = "SELECT COUNT(1) FROM ($query_rating GROUP BY p.ID) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page - 1) * $products_per_page;
		$products = $wpdb->get_results("$query_rating GROUP BY p.ID ORDER BY CONVERT(pm.meta_value, DECIMAL(10,2)) $sort_type LIMIT $offset, $products_per_page");
	} else {
		$total_query = "SELECT COUNT(1) FROM ($base_query GROUP BY p.ID) AS combined_table";
		$total = $wpdb->get_var($total_query);
		$offset = ($page - 1) * $products_per_page;
		$products = $wpdb->get_results("$base_query GROUP BY p.ID ORDER BY CONVERT(pm.meta_value, DECIMAL(10,2)) $sort_type LIMIT $offset, $products_per_page");
	}






	if (empty($products)) {
		$wpdb->insert('wp_search_productlist', ['keyword' => $keyword]);
		$chilupc_query = $wpdb->prepare("SELECT cu.master_upc FROM wp_childupcs cu WHERE find_in_set(%s, cu.child_upc)", "#$keyword");
		$master_upc = $wpdb->get_var($chilupc_query);
		if ($master_upc) {
			$childupc_query = "
                SELECT DISTINCT p.ID, p.post_title, p.post_content, p.post_date, pm.meta_value 
                FROM wp_posts p 
                LEFT JOIN wp_postmeta pm ON p.ID = pm.post_id 
                WHERE pm.meta_key LIKE '%upc%' 
                AND pm.meta_value LIKE '%$master_upc%' 
                AND p.post_type = 'product' 
                AND p.post_status = 'publish' 
                GROUP BY p.ID";
			$products = $wpdb->get_results($childupc_query);
		}
		$total = count($products);
	}



	$all_products['total_products'] = $total ?? 0;
	$all_products['products'] = [];

	foreach ($products as $key => $product) {
		$the_product = wc_get_product($product->ID);

		// Fetch categories as associative array: [category_id => category_name]
		$categories = [];
		foreach ($the_product->get_category_ids() as $category_id) {
			$categories[$category_id] = bar_get_product_category_by_id($category_id);
		}

		// Fetch tags as associative array: [tag_id => tag_name]
		$tags = [];
		foreach ($the_product->get_tag_ids() as $tag_id) {
			$tags[$tag_id] = bar_get_product_tag_by_id($tag_id);
		}

	    // --- NEW RATING LOGIC ---
        // Fetch the custom aggregated rating meta
        $avg_rating = (float) get_post_meta($product->ID, '_product_average_rating', true);
        $rating_count = (int) get_post_meta($product->ID, '_product_rating_count', true);
        
        // Check if the current user has rated this specific product
        $user_rating = get_user_rating_for_product($product->ID, $cur_user_id);
        // --- END NEW RATING LOGIC ---

		$all_products['products'][$key] = [
			'product_id' => $product->ID,
			'product_title' => html_entity_decode($product->post_title),
			'product_image' => get_the_post_thumbnail_url($product->ID, 'full'),
			'product_sm_image' => get_the_post_thumbnail_url($product->ID, 'medium'),
			'product_desc' => str_replace(['<![CDATA[', ']]>'], '', $product->post_content),
			'product_price' => $the_product->get_price(),
			'product_link' => get_permalink($product->ID),
			'product_flavor' => get_post_meta($product->ID, 'flavor', true),
			'product_short_desc' => $the_product->get_short_description(),
			'product_sku' => $the_product->get_sku(),
			// 'product_rating' => $the_product->get_average_rating(),
			// 'product_rating_count' => $the_product->get_review_count(),
			'product_rating' => $avg_rating,
            'product_rating_count' => $rating_count,
            'current_user_has_rated' => $user_rating ? true : false,
            'current_user_rating' => $user_rating,
			'product_categories' => $categories,
			'product_tags' => !empty($tags) ? $tags : '{}'
		];
	}

	return $all_products;
}



function handle_featured_products(WP_REST_Request $request)
{
	global $wpdb;
	$all_products = array();
	$item = $request->get_json_params();
	$product_visibility_term_ids = wc_get_product_visibility_term_ids();

	$page = $item['page'] ? $item['page'] : 1;
	$products_per_page = $item['products_per_page'] ? $item['products_per_page'] : 100;
	$args = [
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $products_per_page,
		'paged' => $page,
		'order' => 'DESC',
		'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => array($product_visibility_term_ids['featured']),
			),
			array(
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => array($product_visibility_term_ids['exclude-from-catalog']),
				'operator' => 'NOT IN',
			),
		),
	];

	$products = get_posts($args);
	foreach ($products as $key => $product) {
		$the_product = wc_get_product($product->ID);
		//print_r($the_product);
		$all_products[$key]['product_id'] = $product->ID;
		$all_products[$key]['product_title'] = html_entity_decode($product->post_title);
		$all_products[$key]['product_image'] = get_the_post_thumbnail_url($product->ID, 'full');
		$all_products[$key]['product_sm_image'] = get_the_post_thumbnail_url($product->ID, 'medium');
		$all_products[$key]['product_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $product->post_content));
		$all_products[$key]['product_price'] = $the_product->price;
		//$all_products[$key]['product_regular_price'] = $the_product->regular_price;
		//$all_products[$key]['product_sale_price'] = $the_product->sale_price;
		//	$all_products[$key]['product_flavor'] = get_post_meta( $product->ID, 'flavour', true );
		$all_products[$key]['product_short_desc'] = $the_product->short_description;
		//	$all_products[$key]['product_sku'] = $the_product->sku;
		$all_products[$key]['product_rating'] = $the_product->average_rating;
		//$all_products[$key]['product_rating_count'] = $the_product->review_count;

		// $prod_cats = array();
		// foreach($the_product->category_ids as $prod_cat_id){
		// 	$prod_cat = bar_get_product_category_by_id($prod_cat_id);
		// 	$prod_cats[$prod_cat_id] = $prod_cat;
		// }
		// $all_products[$key]['product_categories'] = $prod_cats;


		// $prod_tags = array();
		// foreach($the_product->tag_ids as $prod_tag_id){
		// 	$prod_tag = bar_get_product_tag_by_id($prod_tag_id);
		// 	$prod_tags[$prod_tag_id] = $prod_tag;
		// }
		// $all_products[$key]['product_tags'] = $prod_tags;

	}
	return $all_products;
}

function handle_product_detail(WP_REST_Request $request)
{
	global $wpdb;
	$all_products = array();
	$item = $request->get_json_params();
	$cur_user = wp_get_current_user();
	$product = get_post($item['ID']);


	if ($product->post_type == 'product' && $product->post_status == 'publish') {

		$the_product = wc_get_product($product->ID);

		//print_r($the_product);
		$all_products['product_id'] = $product->ID;
		$all_products['product_url'] = get_permalink($product->ID);
		$all_products['product_title'] = html_entity_decode($product->post_title);
		$all_products['product_image'] = get_the_post_thumbnail_url($product->ID, 'full');
		$all_products['product_sm_image'] = get_the_post_thumbnail_url($product->ID, 'medium');
		$all_products['product_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $product->post_content));
		$all_products['product_price'] = $the_product->price;
		//$all_products['product_regular_price'] = $the_product->regular_price;
		//$all_products['product_sale_price'] = $the_product->sale_price;
		$all_products['product_short_desc'] = $the_product->short_description;
		$all_products['product_sku'] = $the_product->sku;
		// $all_products['product_rating'] = $the_product->average_rating;
		// $all_products['product_rating_count'] = $the_product->review_count;
		$all_products['external_url'] = $product->external_url;

		// ---- ADD THESE NEW LINES ---- //
        $all_products['product_rating'] = (float) get_post_meta($product->ID, '_product_average_rating', true);
        $all_products['product_rating_count'] = (int) get_post_meta($product->ID, '_product_rating_count', true);

        // Get the current user's specific rating, if it exists
        $user_rating = get_user_rating_for_product($product->ID, $cur_user->data->ID);

        $all_products['current_user_has_rated'] = $user_rating ? true : false;
        $all_products['current_user_rating'] = $user_rating; // Send full rating object or null
        // ---- END OF NEW LINES ---- /

		//$all_products['product_upc'] = get_post_meta($product->ID, 'productupc', true); by sumeeth for product upc removing #
		$a = get_post_meta($product->ID, 'productupc', true);
		$all_products['product_upc'] = str_replace("#", "", $a);
		$all_products['product_age'] = get_post_meta($product->ID, 'age', true);
		$all_products['product_flavour'] = get_post_meta($product->ID, 'flavor', true);
		$all_products['product_nose'] = get_post_meta($product->ID, 'nose', true);
		$all_products['product_finish'] = get_post_meta($product->ID, 'finish', true);
		$all_products['product_proof'] = get_post_meta($product->ID, 'proof', true);
		$all_products['product_destillery'] = get_post_meta($product->ID, 'distillery', true);
		$all_products['product_region'] = get_post_meta($product->ID, 'region', true);
		$all_products['product_unitsize'] = get_post_meta($product->ID, 'unitsize', true);

		$all_products['is_added_to_wishlist'] = is_product_found_in_wishlist($product->ID, $cur_user->data->ID);
		$all_products['is_added_to_bar'] = is_product_found_in_bar($product->ID, $cur_user->data->ID);


		$prod_cats = array();
		foreach ($the_product->category_ids as $prod_cat_id) {
			$prod_cat = bar_get_product_category_by_id($prod_cat_id);
			$prod_cats[$prod_cat_id] = $prod_cat;
		}
		$all_products['product_categories'] = $prod_cats;


		$prod_tags = array();
		foreach ($the_product->tag_ids as $prod_tag_id) {
			$prod_tag = bar_get_product_tag_by_id($prod_tag_id);
			$prod_tags[$prod_tag_id] = $prod_tag;
		}
		$all_products['product_tags'] = $prod_tags;


		return $all_products;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid Product ID', array('status' => 403));
	}
}

function handle_user_detail(WP_REST_Request $request)
{
	global $wpdb;
	$cur_user = wp_get_current_user();
	//print_r($cur_user);
	$user_details = get_user_meta($cur_user->data->ID);
	//print_r($user_details);

	$my_profile = array();
	if ($cur_user->data->ID) {
		$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
		$my_profile['user_id'] = $cur_user->data->ID;
		$my_profile['user_email'] = $cur_user->data->user_email;
		$my_profile['name'] = $cur_user->data->display_name;
		$my_profile['phone_number'] = $user_details['phone_number'][0];
		$my_profile['bio'] = $user_details['bio'][0];
		$my_profile['address'] = $user_details['address'][0];
		$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
		$my_profile['city'] = $user_details['city'][0];
		$my_profile['state'] = $user_details['state'][0];
		$my_profile['zipcode'] = $user_details['zipcode'][0];
		$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
		$my_profile['is_verified'] = $user_details['is_verified'][0];
		$my_profile['avatar'] = $avatar;
		return $my_profile;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid User', array('status' => 403));
	}
}


function handle_user_edit(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	
	$name = $item['name'];
	$return_msg = "";
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	$imgdata = base64_decode($item["avatar"]);

	wp_update_user(array(
		'ID' => $user_id,
		'display_name' => $name,
		'phone_number' => $item['phone_number'],
		'bio' => $item['bio'],
		'profile_edited' => 1,
	));
	update_user_meta($user_id, 'phone_number', $item['phone_number']);
	update_user_meta($user_id, 'address', $item['address']);
	update_user_meta($user_id, 'date_of_birth', $item['date_of_birth']);
	update_user_meta($user_id, 'bio', $item['bio']);
	
	update_user_meta($user_id, 'aptsuitefloor', $item['aptsuitefloor']);
	update_user_meta($user_id, 'city', $item['city']);
	update_user_meta($user_id, 'state', $item['state']);
	update_user_meta($user_id, 'zipcode', $item['zipcode']);
	update_user_meta($user_id, 'display_name', $item['name']);


	$newDOB = date("Y-m-d", strtotime($item['date_of_birth']));
	$from = new DateTime($newDOB);
	$to = new DateTime('today');
	$age = $from->diff($to)->y;
	if ($age >= 21 && $item['address'] != '' && $item['name'] != '' && $item['phone_number'] != '') {
		update_user_meta($user_id, 'is_verified', 1);
	} else {
		update_user_meta($user_id, 'is_verified', 0);
	}
	birthday_rewards();
	// Reward point Implementation by salman
	$profile_edited = $wpdb->get_var($wpdb->prepare("SELECT profile_edited FROM wp_users WHERE ID = %d", $user_id));
	

	if ($profile_edited == 1) {
		$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $user_id, 1));
		if($list == 0){
		$return_msg = reward_points("add" ,(int)1,$user_id);	
		}
	}
	//user profile
	if (isset($item['avatar']) && $item['avatar'] != '') {
		$imgdata = base64_decode($item["avatar"]);
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
		$type_file = explode('/', $mime_type);
		//$avatar = time() . '.' . $type_file[1];
		$avatar = time() . '.' . 'webp';

		$uploaddir = wp_upload_dir();
		$myDirPath = $uploaddir["path"];
		$myDirUrl = $uploaddir["url"];

		file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

		$filename = $myDirUrl . '/' . basename($avatar);
		$wp_filetype = wp_check_filetype(basename($filename), null);
		$uploadfile = $uploaddir["path"] . '/' . basename($filename);

		if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/webp' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
			$attachment = array(
				"post_mime_type" => $wp_filetype["type"],
				"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
				"post_content" => "",
				"post_status" => "inherit",
				'guid' => $uploadfile,
			);

			require_once(ABSPATH . '/wp-load.php');
			require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
			require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
			$attachment_id = wp_insert_attachment($attachment, $uploadfile);
			$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
			wp_update_attachment_metadata($attachment_id, $attach_data);

			update_post_meta($attachment_id, '_wp_attachment_wp_user_avatar', $cur_user);
			update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
		}
	} else if (isset($item['avatar']) && $item['avatar'] == '') {
		update_user_meta($user_id, 'wp_user_avatar', '');
	}

	$cur_user = wp_get_current_user();

	$user_details = get_user_meta($cur_user->data->ID);
	//print_r($user_details);exit;
	$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
	$my_profile = array();

	if ($cur_user->data->ID) {
		$my_profile['user_id'] = $cur_user->data->ID;
		$my_profile['user_email'] = $cur_user->data->user_email;
		$my_profile['name'] = $user_details['display_name'][0];
		$my_profile['phone_number'] = $user_details['phone_number'][0];
		$my_profile['bio'] = $user_details['bio'][0];
		$my_profile['address'] = $user_details['address'][0];
		$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
		$my_profile['city'] = $user_details['city'][0];
		$my_profile['state'] = $user_details['state'][0];
		$my_profile['zipcode'] = $user_details['zipcode'][0];
		$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
		$my_profile['is_verified'] = $user_details['is_verified'][0];
		$my_profile['avatar'] = $avatar;
		$my_profile['reward_message'] = $return_msg;
		return $my_profile;
	}
}

function handle_user_UsersBlocked(WP_REST_Request $request)
{

	global $wpdb;
	$item = $request->get_json_params();
	$profiles = array();
	if (!empty($item['user_id'])) {

		$query1 = $wpdb->prepare("SELECT blocked_user  FROM wp_users_blocked WHERE blocked_by='" . $item['user_id'] . "'");
		$userexist = $wpdb->get_results($query1);
		if (count($userexist) > 0) {
			foreach ($userexist as $userids) {

				if ($userids->blocked_by != $item['user_id']) {
					$buser = $userids->blocked_by;
				}
				if ($userids->blocked_user != $item['user_id']) {
					$buser = $userids->blocked_user;
				}

				$user_details = get_user_meta($buser);

				$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');

				$users = $wpdb->prepare("SELECT * FROM `wp_users` WHERE ID =" . $buser);
				$userlist = $wpdb->get_results($users);

				foreach ($userlist as $user) {


					$user_details = get_user_meta($user->ID);

					$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
					array_push($profiles, array('User_id' => $user->ID, 'name' => $user->display_name, 'email' => $user->user_email, 'image' => $avatar));
				}

			}


		} else {
			array_push($profiles, array('message' => 'No Users'));
		}

	} else {
		array_push($profiles, array('message' => 'Invalid User Id'));
	}
	echo json_encode($profiles);
}
function handle_user_UserBlock(WP_REST_Request $request)
{

	global $wpdb;
	$item = $request->get_json_params();

	$uinfo = array();
	$uinfo['user_id'] = $item['user_id'];
	$uinfo['block_user_id'] = $item['block_user_id'];
	$uinfo['type'] = $item['type'];

	if ($item['type'] == "block") {
		$query1 = $wpdb->prepare("SELECT *  FROM wp_users_blocked WHERE blocked_by='" . $item['user_id'] . "' and blocked_user='" . $item['block_user_id'] . "'");
		$userexist = $wpdb->get_results($query1);
		if (count($userexist) > 0) {
			$uinfo['message'] = "User already blocked";
		} else {

			$querystore = $wpdb->prepare("INSERT INTO `wp_users_blocked` (`blocked_by`, `blocked_user`) VALUES (%d, %d)", $item['user_id'], $item['block_user_id']);
			$res = $wpdb->query($querystore);
			if ($res) {
				$uinfo['message'] = "User blocked success";
			} else {
				$uinfo['message'] = "User unable to block";
			}
		}

	} else if ($item['type'] == "unblock") {

		$dquery = $wpdb->prepare("DELETE FROM `wp_users_blocked` WHERE blocked_by='" . $item['user_id'] . "' and blocked_user='" . $item['block_user_id'] . "'");
		$dres = $wpdb->query($dquery);

		if ($dres) {
			$uinfo['message'] = "User unblocked success";
		} else {
			$uinfo['message'] = "User unable to unblock";
		}
	} else {
		$uinfo['message'] = "Invalid type";
	}
	return $uinfo;
}

function handle_user_register(WP_REST_Request $request)
{
	global $wpdb;
	wp_logout();
	$uip = $_SERVER['REMOTE_ADDR'];
	$browser = getBrowser(null, true);
	$bname = $browser['name'];
	$version = $browser['version'];
	$platform = $browser['platform'];
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	$password = esc_attr($item['password']);
	$email = sanitize_email($item['email']);
	$uname = $item['uname'];
	//$full_name =   $item['full_name'];

	//$item['post_url']="https://sipnbourbon.com/wp-json/users/v2/verifyemail?email=".$email. "";
	$userdata = array(
		'user_login' => $uname,
		'user_email' => $email,
		'user_pass' => $password
	);
	if (!$user_id) {

		$new_user = wp_insert_user($userdata);
		//print_r($new_user);exit;
		if (!isset($new_user->errors)) {
			// 	$to = $email;
			// //print_r($item['post_url']);exit;
			// $subject = 'Confirmation email';
			// $message = "Hello, <br> Please check the below url for Confirmation:<br>";
			// $message .= "Post URL: ".$item['post_url']. "<br>";
			// //$message .= "Reason: ".$item['reason']. "<br>";
			// $headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: sumeethtechmatic80990@gmail.com');
			// if(wp_mail( $to, $subject, $message, $headers )){
			// 	//return true;		
			// }else {
			// 	//return false;
			// }
			$user_obj = get_user_by('id', $new_user);
			//wp_new_user_notification($user_obj->data->ID, null, 'user');
			handle_user_sendemailsignup($email);
			// if ($full_name ==' ' || empty($full_name) || $full_name == null) {
			// 	$new_user_login = 'user-'.$user_obj->data->ID;
			// }else{
			// 	$new_user_login = $full_name;
			// }
			//
			$new_user_login = $user_obj->data->display_name;
			$new_user_loginnew = 'user-' . $user_obj->data->ID;
			//print_r($new_user_login);exit;
			$wpdb->update($wpdb->users, array('user_login' => $new_user_loginnew), array('ID' => $user_obj->data->ID));

			$user_id = wp_update_user(array('ID' => $user_obj->data->ID, 'display_name' => $new_user_login, 'user_nicename' => $new_user_loginnew));

			update_user_meta($new_user, 'is_verified', 0);
			$query = $wpdb->prepare("UPDATE wp_users SET browser_name='" . $bname . "',browser_version='" . $version . "',platform='" . $platform . "',user_ip='" . $uip . "' WHERE user_email ='" . $user_obj->data->user_email . "'");
			$res = $wpdb->query($query);

			$my_profile = array();
			$my_profile['user_id'] = $user_obj->data->ID;
			$my_profile['user_email'] = $user_obj->data->user_email;
			$my_profile['user_password'] = $password;
			$my_profile['message'] = 'Registration Success..Please verify your email using the link sent to your email.';

			$info = array();
			$info['user_login'] = $email;
			$info['user_password'] = $password;
			$info['remember'] = true;

			$user_signon = wp_signon($info, false);

			return $my_profile;
		} else {
			return $new_user;
		}
	} else {
		return $new_user;
	}
}

function getBrowser()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	} elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes seperately and for good reason
	if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	} elseif (preg_match('/Firefox/i', $u_agent)) {
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	} elseif (preg_match('/Chrome/i', $u_agent)) {
		$bname = 'Google Chrome';
		$ub = "Chrome";
	} elseif (preg_match('/Safari/i', $u_agent)) {
		$bname = 'Apple Safari';
		$ub = "Safari";
	} elseif (preg_match('/Opera/i', $u_agent)) {
		$bname = 'Opera';
		$ub = "Opera";
	} elseif (preg_match('/Netscape/i', $u_agent)) {
		$bname = 'Netscape';
		$ub = "Netscape";
	}

	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}

	// check if we have a number
	if ($version == null || $version == "") {
		$version = "?";
	}

	return array(
		'userAgent' => $u_agent,
		'name' => $bname,
		'version' => $version,
		'platform' => $platform,
		'pattern' => $pattern
	);
}

function handle_topics_list(WP_REST_Request $request)
{
	global $wpdb;
	$all_topics = array();
	$item = $request->get_json_params();

	$page = $item['page'] ? $item['page'] : 1;
	$topics_per_page = $item['topics_per_page'] ? $item['topics_per_page'] : 10;
	$args = [
		'post_type' => 'topic',
		'post_status' => 'publish',
		'posts_per_page' => $topics_per_page,
		'exclude' => array('35832'),
		'paged' => $page,
		'order' => 'ASC'
	];

	$topics = get_posts($args);
	foreach ($topics as $key => $topic) {

		$all_topics[$key]['topic_id'] = $topic->ID;
		$all_topics[$key]['topic_title'] = $topic->post_title;

		$thumb_image_id = get_post_meta($topic->ID, 'thumb_image', true);
		$thumb_featured_image_id = get_post_meta($topic->ID, 'featured_image', true);

		$featured_img = wp_get_attachment_image_src($thumb_featured_image_id, 'full');
		$thumb_img = wp_get_attachment_image_src($thumb_image_id, 'full');

		$all_topics[$key]['topic_featured_image'] = $featured_img[0];
		$all_topics[$key]['topic_thumb_image'] = $thumb_img[0];
		$all_topics[$key]['topic_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $topic->post_content));
		$all_topics[$key]['topic_short_desc_top'] = get_post_meta($topic->ID, 'short_description_top', true);
		$all_topics[$key]['topic_short_desc_bottom'] = get_post_meta($topic->ID, 'short_description_bottom', true);

	}
	return $all_topics;
}


function handle_timeline_comments(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$parent_id = $item['parent_id'];
	$page = $item['page'] ? $item['page'] : 1;
	$posts_per_page = $item['posts_per_page'] ? $item['posts_per_page'] : 10;

	if (!$page || $page <= 0) {
		$page = 1;
	}

	if ($parent_id > 0) {
		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
					'value' => $parent_id,
				)
			),
			'paged' => $page,
			'numberposts' => $posts_per_page,
		];

		//print_r($args);
		$replies = get_posts($args);
		//print_r($replies);
		$all_replies = array();
		foreach ($replies as $reply) {
			$author_id = $reply->post_author;
			$author_details = get_user_by('id', $author_id);
			$author_meta = get_user_meta($author_id);
			$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
			$cnt_list = $wpdb->get_results($query);
			$likes_count = $cnt_list[0]->cnt;

			$reply_f_image = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'single-post-thumbnail');
			if ($reply_f_image[0]) {
				$reply_image_path = $reply_f_image[0];
			} else {
				$reply_image_path = '';
			}
			//$next_page = $page+1;
			$reply_date = timeline_time_ago($reply->post_date);

			$query = array(
				'post_type' => 'reply',
				'post_status' => 'publish',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => '_bbp_reply_to',
						'value' => $reply->ID,
					)
				),
			);

			$results = new WP_Query($query);
			$total_replies_count = $results->found_posts; //// This is 0...
			//echo $results->count_posts; //// This is 0...
			wp_reset_postdata();

			$replies = get_timeline_replies($reply->ID, 1);
			$url = get_home_url() . "/timeline/?q=" . $reply->ID;
			array_push($all_replies, array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'avatar' => $avatar, 'url' => $url, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'replies' => $replies));
		}


		return $all_replies;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid Topic ID', array('status' => 403));
	}
}



function get_timeline_list($page, $posts_per_page)
{
	global $wpdb;

	$page = $page ? $page : 1;
	$posts_per_page = $posts_per_page ? $posts_per_page : 10;

	$topic_ID = 35832;
	$all_topics = array();


	$cur_user = wp_get_current_user();
	$cur_user_id = $cur_user->data->ID;
	if (!$cur_user_id) {
		$cur_user_id = '0';
	}

	// Fetch users that the current user has blocked
	$query_blocked_by_user = $wpdb->prepare("
							SELECT blocked_user 
							FROM wp_users_blocked 
							WHERE blocked_by = %d
							", $cur_user_id);

	$blocked_by_user = $wpdb->get_col($query_blocked_by_user);

	// Fetch users who have blocked the current user
	$query_blocked_by_others = $wpdb->prepare("
							SELECT blocked_by 
							FROM wp_users_blocked 
							WHERE blocked_user = %d
							", $cur_user_id);

	$blocked_by_others = $wpdb->get_col($query_blocked_by_others);

	// Combine both lists of blocked users
	$mutually_blocked_users = array_merge($blocked_by_user, $blocked_by_others);

	// If no users are blocked, set the array to contain a non-existent user ID
	if (empty($mutually_blocked_users)) {
		$mutually_blocked_users = [0]; // Set to 0 or any invalid user ID to avoid issues
	}


	// First query (for replies)
	$args = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'DESC',
		'post_parent' => $topic_ID,
		'posts_per_page' => $posts_per_page,
		'paged' => $page,
		'author__not_in' => $mutually_blocked_users,
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'compare' => 'NOT EXISTS'
			)
		),
	];

	// Second query (for counting replies)
	$args2 = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'ASC',
		'fields' => 'ids',
		'post_parent' => $topic_ID,
		'numberposts' => -1,
		'author__not_in' => $mutually_blocked_users,
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'compare' => 'NOT EXISTS'
			)
		),
	];

	// Get replies and count
	$posts_replies = get_posts($args2);
	$posts_cnt = count($posts_replies);

	// Fetch the actual posts (replies)
	$replies = get_posts($args);


	$all_topics['total_posts'] = $posts_cnt;
	$all_topics['user_id'] = $cur_user_id;
	$all_topics['sponsored_ads'] = array();
	$day = date('Ymd');
	$query = $wpdb->prepare("SELECT *  FROM wp_sponsored_ads WHERE  end_date >='%d' and status=0 order by id desc", $day);
	$notifications_list = $wpdb->get_results($query);
	//	print_r($notifications_list);exit;
	//$notifications['count']=count($notifications_list);
	$k = 0;
	$sponsored_verify = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-sponsored.png";
	foreach ($notifications_list as $not) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM wp_sponsored_likes WHERE  spons_id =$not->id");
		$cnt_list = $wpdb->get_results($query);
		$likes_count = $cnt_list[0]->cnt;
		//$likes_count =255+$likes_count;

		$userid = $cur_user->data->ID;
		//print_r($userid);exit;
		if ($userid > 0) {
			$query1 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $userid, $not->id);
			//print_r($query1);exit;
			$list = $wpdb->get_results($query1);
			//print_r($list);exit;
			if ($list[0]->cnt >= 1) {
				$is_liked = "1";
			} else {
				$is_liked = "0";
			}
		} else {
			$is_liked = "0";
		}
		// Resolve linked product dynamically; stays blank when no product is linked.
		$pid           = (!empty($not->product_id) && (int) $not->product_id > 0) ? (int) $not->product_id : '';
		$product_title = '';
		$product_image = '';
		if ($pid !== '') {
			$productlis = get_post($pid);
			if ($productlis) {
				$product_title = $productlis->post_title;
			}
			$product_image = get_the_post_thumbnail_url($pid, 'full');
			if (!$product_image) {
				$product_image = '';
			}
		}
		$query2 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_comments` WHERE spons_id = '%d'", $not->id);
		$cnt_list2 = $wpdb->get_results($query2);
		$total_replies_count = (int) $cnt_list2[0]->cnt;
		$url = get_home_url() . "/timeline_sponsads/?q=" . $not->id;
		$reply_date = timeline_time_ago($not->created_at);
		$spons_replies = get_timeline_sponsreplies($not->id, 1);
		$all_topics['sponsored_ads'][$k]['spons_id'] = $not->id;
		$all_topics['sponsored_ads'][$k]['company_name'] = $not->company_name;
		$all_topics['sponsored_ads'][$k]['company_logo'] = $not->company_logo;
		$all_topics['sponsored_ads'][$k]['description'] = $not->description;
		$all_topics['sponsored_ads'][$k]['image'] = $not->image;
		$all_topics['sponsored_ads'][$k]['link'] = $not->link;
		$all_topics['sponsored_ads'][$k]['spons_date'] = $reply_date;
		$all_topics['sponsored_ads'][$k]['spons_verified'] = $sponsored_verify;
		$all_topics['sponsored_ads'][$k]['total_replies_count'] = $total_replies_count;
		$all_topics['sponsored_ads'][$k]['url'] = $url;
		$all_topics['sponsored_ads'][$k]['likes_count'] = $likes_count;
		$all_topics['sponsored_ads'][$k]['is_liked'] = $is_liked;
		$all_topics['sponsored_ads'][$k]['product_id'] = $pid;
		$all_topics['sponsored_ads'][$k]['product_title'] = $product_title;
		$all_topics['sponsored_ads'][$k]['product_image'] = $product_image;
		$all_topics['sponsored_ads'][$k]['is_bar'] = $not->is_bar;
		$all_topics['sponsored_ads'][$k]['replies'] = $spons_replies;
		$all_topics['sponsored_ads'][$k]['buynow_text'] = $not->ad_type;



		$k++;
	}
	$all_topics['replies'] = array();
	foreach ($replies as $reply) {
		$parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
		//print_r($parent_info);echo "<br><hr><br>";
		if (!$parent_info) {
			$author_id = $reply->post_author;
			$author_details = get_user_by('id', $author_id);
			$author_meta = get_user_meta($author_id);
			$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

			if (!$avatar) {
				$avatar = get_avatar_url($author_id);
			}

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
			$cnt_list = $wpdb->get_results($query);
			$likes_count = $cnt_list[0]->cnt;

			// $reply_f_image = wp_get_attachment_image_src( get_post_thumbnail_id( $reply->ID ), 'single-post-thumbnail' );
			// //print_r($reply_f_image);exit;
			// if($reply_f_image[0]){
			// 	$reply_image_path = $reply_f_image[0];
			// }
			// else{
			// 	$reply_image_path = '';
			// }
			//for multipart images multiple images
			$thumbnail_id = get_post_meta($reply->ID, '_thumbnail_id');
			//print_r($thumbnail_id);echo "<br>";
			$imd = implode("", $thumbnail_id);


			$t = explode(',', $imd);
			$reply_f_image = [];
			foreach ($t as $key => $value) {

				$reply_f_image[] = wp_get_attachment_image_src($value, 'medium'); // single-post-thumbnail for original image n image optimization change by sumeeth
			}
			//print_r($key);exit;
			if ($key == 0 && $reply_f_image[0][0]) {
				$reply_image_path = $reply_f_image[0][0];
			} else if ($key == 1 && $reply_f_image[1][0]) {
				$reply_image_path = $reply_f_image[0][0] . ',' . $reply_f_image[1][0];
			} else if ($key == 2 && $reply_f_image[2][0]) {
				$reply_image_path = $reply_f_image[0][0] . ',' . $reply_f_image[1][0] . ',' . $reply_f_image[2][0];
			} else if ($key == 3 && $reply_f_image[3][0]) {
				$reply_image_path = $reply_f_image[0][0] . ',' . $reply_f_image[1][0] . ',' . $reply_f_image[2][0] . ',' . $reply_f_image[3][0];
			} else if ($key == 4 && $reply_f_image[4][0]) {
				$reply_image_path = $reply_f_image[0][0] . ',' . $reply_f_image[1][0] . ',' . $reply_f_image[2][0] . ',' . $reply_f_image[3][0] . ',' . $reply_f_image[4][0];
			} else {
				$reply_image_path = '';
			}


			$query = array(
				'post_type' => 'reply',
				'post_status' => 'publish',
				'order' => 'ASC',
				'author__not_in' => $mutually_blocked_users,
				'meta_query' => array(
					array(
						'key' => '_bbp_reply_to',
						'value' => $reply->ID,
					)
				),
			);
			$query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_product_id'");

			$p_list = $wpdb->get_results($query1);
			$pid = $p_list[0]->pid;
			if ($pid == '') {
				$pid = '';
			}


			//for tagged location
			$query2 = $wpdb->prepare("SELECT meta_value as lid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_tagged_location'");

			$l_list = $wpdb->get_results($query2);
			$lid = $l_list[0]->lid;
			if ($lid == '') {
				$lid = '';
			}


			$productlis = get_post($pid);

			$product_title = $productlis->post_title;
			if (strlen($product_title) > 30) {
				$product_title = substr($product_title, 0, 29) . '...';
			} else {
				$product_title = $productlis->post_title;
			}
			if ($product_title == '') {
				$product_title = '';
			}
			$product_image = get_the_post_thumbnail_url($pid, 'full');
			if ($product_image == '') {
				$product_image = '';
			}
			$abc = get_post_meta($pid, 'productupc', true);
			$product_upc = str_replace("#", "", $abc);
			if ($product_upc == '') {
				$product_upc = '';
			}
			$the_product = wc_get_product($pid);
			$product_rating = $the_product->average_rating;
			if ($product_rating == '') {
				$product_rating = 0;
			}
			$product_price = $the_product->price;
			if ($product_price == '') {
				$product_price = '';
			}

			$results = new WP_Query($query);
			$total_replies_count = $results->found_posts; //// This is 0...
			//echo $results->count_posts; //// This is 0...
			wp_reset_postdata();

			$replies = get_timeline_replies($reply->ID, 1);

			$reply_date = timeline_time_ago($reply->post_date);
			$url = get_home_url() . "/timeline/?q=" . $reply->ID;
			//$replies = bbp_get_reply_ancestors($reply->ID)
			//print_r($replies);
			//print_r($author_details);
			array_push($all_topics['replies'], array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'product_id' => $pid, 'tagged_location' => $lid, 'product_title' => $product_title, 'product_image' => $product_image, 'product_upc' => $product_upc, 'product_rating' => $product_rating, 'product_price' => $product_price, 'replies' => $replies));
		}
	}


	return $all_topics;

}

function handle_timeline_list_test_new(WP_REST_Request $request)
{    //echo 1; exit;

    global $wpdb;
    $item = $request->get_json_params();

    $page = $item['page'] ? $item['page'] : 1;
    $posts_per_page = $item['posts_per_page'] ? $item['posts_per_page'] : 10;

    $topic_ID = 35832;

    $cur_user = wp_get_current_user();
    $cur_user_id = $cur_user->data->ID;
    if (!$cur_user_id) {
        $cur_user_id = '0';
    }
    if($cur_user > 0){
        birthday_rewards();
        update_rewards();
    }

    // Fetch users that the current user has blocked
    $query_blocked_by_user = $wpdb->prepare("
                            SELECT blocked_user 
                            FROM wp_users_blocked 
                            WHERE blocked_by = %d
                            ", $cur_user_id);

    $blocked_by_user = $wpdb->get_col($query_blocked_by_user);

    // Fetch users who have blocked the current user
    $query_blocked_by_others = $wpdb->prepare("
                            SELECT blocked_by 
                            FROM wp_users_blocked 
                            WHERE blocked_user = %d
                            ", $cur_user_id);

    $blocked_by_others = $wpdb->get_col($query_blocked_by_others);

    // Combine both lists of blocked users
    $mutually_blocked_users = array_merge($blocked_by_user, $blocked_by_others);

    // If no users are blocked, set the array to contain a non-existent user ID
    if (empty($mutually_blocked_users)) {
        $mutually_blocked_users = [0]; // Set to 0 or any invalid user ID to avoid issues
    }

    
    $all_topics = array();


    $args = [
        'post_type' => 'reply',
        'post_status' => 'publish',
        'order' => 'DESC',
        'post_parent' => $topic_ID,
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'author__not_in' => $mutually_blocked_users,
        'meta_query' => array(
            array(
                'key' => '_bbp_reply_to',
                'compare' => 'NOT EXISTS'
            )
        ),
    ];

    $args2 = [
        'post_type' => 'reply',
        'post_status' => 'publish',
        'order' => 'ASC',
        'fields' => 'ids',
        'post_parent' => $topic_ID,
        'author__not_in' => $mutually_blocked_users,
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_bbp_reply_to',
                'compare' => 'NOT EXISTS'
            )
        ),
    ];

    $posts_replies = get_posts($args2);
    $posts_cnt = count($posts_replies);

    $cur_user = wp_get_current_user();
    $cur_user_id = $cur_user->data->ID;
    if (!$cur_user_id) {
        $cur_user_id = '0';
    }

    $replies = get_posts($args);
    $all_topics['total_posts'] = $posts_cnt;
    $all_topics['user_id'] = $cur_user_id;
    //for sposnored ad's
    $all_topics['sponsored_ads'] = array();
    $day = date('Ymd');
    $query = $wpdb->prepare("SELECT *  FROM wp_sponsored_ads WHERE  end_date >=$day and status=0 order by id desc");
    $notifications_list = $wpdb->get_results($query);
    //  print_r($notifications_list);exit;
    //$notifications['count']=count($notifications_list);
    $k = 0;
    $sponsored_verify = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-sponsored.png";
    update_rewards();
    foreach ($notifications_list as $not) {
        // Resolve product info dynamically. Ads may have no product linked, so
        // default every field to empty and only populate when a valid product
        // actually exists. Keeps the API keys present (no hardcoded references).
        $pid            = (!empty($not->product_id) && (int) $not->product_id > 0) ? (int) $not->product_id : '';
        $product_title  = '';
        $external_url   = '';
        $product_image  = '';
        $product_upc    = '';
        $product_rating = 0;
        $product_price  = '';
        $p_sku          = '';

        if ($pid !== '') {
            $productlis = get_post($pid);
            if ($productlis) {
                $product_title = $productlis->post_title;
                $external_url  = isset($productlis->external_url) ? $productlis->external_url : '';
            }
            $product_image = get_the_post_thumbnail_url($pid, 'full');
            if (!$product_image) {
                $product_image = '';
            }
            $abc         = get_post_meta($pid, 'productupc', true);
            $product_upc = str_replace("#", "", (string) $abc);

            if (function_exists('wc_get_product')) {
                $the_product = wc_get_product($pid);
                if ($the_product) {
                    $product_rating = $the_product->get_average_rating();
                    if ($product_rating == '') {
                        $product_rating = 0;
                    }
                    $product_price = $the_product->get_price();
                    if ($product_price == '') {
                        $product_price = '';
                    }
                    $p_sku = $the_product->get_sku();
                    if ($p_sku == '') {
                        $p_sku = '';
                    }
                }
            }
        }

        $query = $wpdb->prepare("SELECT count(*) as cnt FROM wp_sponsored_likes WHERE  spons_id =$not->id");
        $cnt_list = $wpdb->get_results($query);
        $likes_count = $cnt_list[0]->cnt;
        //$likes_count =255+$likes_count;

        $userid = $cur_user->data->ID;
        //print_r($userid);exit;
        if ($userid > 0) {
            $query1 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $userid, $not->id);
            //print_r($query1);exit;
            $list = $wpdb->get_results($query1);
            //print_r($list);exit;
            if ($list[0]->cnt >= 1) {
                $is_liked = "1";
            } else {
                $is_liked = "0";
            }
        } else {
            $is_liked = "0";
        }
        if ($not->is_bar == '1') {
            $isbar = 1;
        } else {
            $isbar = 0;
        }
        $query2 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_comments` WHERE spons_id = '%d'", $not->id);
        $cnt_list2 = $wpdb->get_results($query2);
        $total_replies_count = (int) $cnt_list2[0]->cnt;
        $url = get_home_url() . "/timeline_sponsads/?q=" . $not->id;
        $spons_replies = get_timeline_sponsreplies($not->id, 1);
        $lurl = explode('/', $not->collection_link);
        if ($lurl[3] == 'bourbon-collection') {
            $curl = 1;
        } else {
            $curl = 0;
        }
        $str = str_replace("-", " ", $lurl[4]);
        $query3 = $wpdb->prepare("SELECT collection_id FROM `wp_collections` WHERE collection_orgname = '%s'", $str);
        $cid = $wpdb->get_results($query3);
        if ($cid[0]->collection_id == '') {
            $cid = '';
        } else {
            $cid = $cid[0]->collection_id;
        }

        $reply_date = timeline_time_ago($not->created_at);
        $all_topics['sponsored_ads'][$k]['spons_id'] = $not->id;
        $all_topics['sponsored_ads'][$k]['company_name'] = $not->company_name;
        $all_topics['sponsored_ads'][$k]['company_logo'] = $not->company_logo;
        $all_topics['sponsored_ads'][$k]['description'] = strip_tags($not->description);
        $all_topics['sponsored_ads'][$k]['image'] = $not->image;
        $all_topics['sponsored_ads'][$k]['link'] = $not->link;
        $all_topics['sponsored_ads'][$k]['is_collection'] = $curl;
        $all_topics['sponsored_ads'][$k]['is_bar'] = $isbar;
        $all_topics['sponsored_ads'][$k]['collection_id'] = $cid;
        $all_topics['sponsored_ads'][$k]['spons_date'] = $reply_date;
        $all_topics['sponsored_ads'][$k]['spons_verified'] = $sponsored_verify;
        $all_topics['sponsored_ads'][$k]['total_replies_count'] = $total_replies_count;
        $all_topics['sponsored_ads'][$k]['url'] = $url;
        $all_topics['sponsored_ads'][$k]['likes_count'] = $likes_count;
        $all_topics['sponsored_ads'][$k]['is_liked'] = $is_liked;
        $all_topics['sponsored_ads'][$k]['product_id'] = $pid;
        $all_topics['sponsored_ads'][$k]['product_sku'] = $p_sku;
        $all_topics['sponsored_ads'][$k]['product_title'] = $product_title;
        $all_topics['sponsored_ads'][$k]['product_image'] = $product_image;
        $all_topics['sponsored_ads'][$k]['product_upc'] = $product_upc;
        $all_topics['sponsored_ads'][$k]['product_rating'] = $product_rating;
        $all_topics['sponsored_ads'][$k]['product_price'] = $product_price;
        $all_topics['sponsored_ads'][$k]['replies'] = $spons_replies;
        $all_topics['sponsored_ads'][$k]['external_url'] = $external_url;
        $all_topics['sponsored_ads'][$k]['buynow_text'] = $not->ad_type;
        // $all_topics['sponsored_ads'][$k]['external_url'] = "https://arkaybeverages.com/product/arkay-version-of-alcohol-free-on-fire-whisky-flavoured-drink-best-seller-gluten-free-sugar-free-guilt-free-free-shipping/";


        $k++;
    }//for sposnored ad's
    $all_topics['replies'] = array();
    $badge_icon = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/badge-gold.png";
    foreach ($replies as $reply) {
        $parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
        if (!$parent_info) {
            $author_id = $reply->post_author;
            $author_details = get_user_by('id', $author_id);
            $author_meta = get_user_meta($author_id);
            $avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

            if (!$avatar) {
                $avatar = get_avatar_url($author_id);
            }

            $query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
            $cnt_list = $wpdb->get_results($query);
            $likes_count = $cnt_list[0]->cnt;

            $reply_f_image1 = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'medium_large');
            if ($reply_f_image1[0]) {
                $reply_image_path = $reply_f_image1[0];
            } else {
                $reply_image_path = '';
            }


		$thumbnail_id = get_post_meta($reply->ID, '_thumbnail_id', true);
		$t = explode(',', $thumbnail_id);

		// Final output arrays
		$reply_imagearray = [];         // Only image URLs
		$reply_videoarray = [];         // Mixed ordered image/video array

		foreach ($t as $val) {
		    $mime_type = get_post_mime_type($val);

		    // === IMAGE ===
		    if (strpos($mime_type, 'image') !== false) {
		        $img_data = wp_get_attachment_image_src($val, 'medium_large');
		        if ($img_data && isset($img_data[0]) && !empty($img_data[0])) {
		            $url = $img_data[0];

		            // Skip if it's a video thumbnail (by filename pattern)
		            if (!preg_match('/(_thumb\.jpg|_thumb-[0-9]+x[0-9]+\.jpg)$/i', basename($url))) {
		                // Add to image-only array
		                $reply_imagearray[] = $url;

		                // Also add to combined array
		                $reply_videoarray[] = [
		                    'image' => $url
		                ];
		            }
		        }
		    }

		    // === VIDEO ===
		    if (strpos($mime_type, 'video') !== false) {
		        $video_url = wp_get_attachment_url($val);
		        if ($video_url) {
		            $video_thumbnail = '';

		            // Custom meta thumbnail
		            $thumbnail_id = get_post_meta($val, 'video_thumbnail_id', true);
		            if ($thumbnail_id) {
		                $video_thumbnail = wp_get_attachment_url($thumbnail_id);
		            }

		            // Auto-detect fallback
		            if (!$video_thumbnail) {
		                $video_filename = basename(get_attached_file($val));
		                $thumbnail_filename = str_replace(['.mp4', '.mov', '.webm', '.mkv'], '_thumb.jpg', $video_filename);

		                $args_thumb = [
		                    'post_type' => 'attachment',
		                    'post_status' => 'inherit',
		                    'posts_per_page' => 1,
		                    'meta_query' => [
		                        [
		                            'key' => '_wp_attached_file',
		                            'value' => $thumbnail_filename,
		                            'compare' => 'LIKE'
		                        ]
		                    ]
		                ];
		                $thumb_query = new WP_Query($args_thumb);
		                if ($thumb_query->have_posts()) {
		                    $thumb = $thumb_query->posts[0];
		                    $video_thumbnail = wp_get_attachment_url($thumb->ID);
		                }
		                wp_reset_postdata();
		            }

		            $reply_videoarray[] = [
		                'video' => $video_url,
		                'thumbnail_url' => $video_thumbnail ?: ''
		            ];
		        }
		    }
		}

            $query = array(
                'post_type' => 'reply',
                'post_status' => 'publish',
                'order' => 'ASC',
                'author__not_in' => $mutually_blocked_users,
                'meta_query' => array(
                    array(
                        'key' => '_bbp_reply_to',
                        'value' => $reply->ID,
                    )
                ),
            );

            //for tagged location
            $query2 = $wpdb->prepare("SELECT meta_value as lid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_tagged_location'");

            $l_list = $wpdb->get_results($query2);
            $lid = $l_list[0]->lid;
            if ($lid == '') {
                $lid = '';
            }
            //for product tag


            $query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_product_id'");

            $p_list = $wpdb->get_results($query1);
            $pid = $p_list[0]->pid;
            if ($pid == '') {
                $pid = '';
            }
            $productlis = get_post($pid);

            $product_title = $productlis->post_title;
            if ($product_title == '') {
                $product_title = '';
            }
            $product_image = get_the_post_thumbnail_url($pid, 'full');
            if ($product_image == '') {
                $product_image = '';
            }
            $abc = get_post_meta($pid, 'productupc', true);
            $product_upc = str_replace("#", "", $abc);
            if ($product_upc == '') {
                $product_upc = '';
            }
            $the_product = wc_get_product($pid);
            $product_rating = $the_product->average_rating;
            if ($product_rating == '') {
                $product_rating = 0;
            }
            $product_price = $the_product->price;
            if ($product_price == '') {
                $product_price = '';
            }
            $p_sku = $the_product->sku;
            if ($p_sku == '') {
                $p_sku = '';
            }
            $external_url = $productlis->external_url;
            if ($external_url == '') {
                $external_url = '';
            }

            $results = new WP_Query($query);
            $total_replies_count = $results->found_posts; //// This is 0...
            //echo $results->count_posts; //// This is 0...
            wp_reset_postdata();

            $replies = get_timeline_replies($reply->ID, 1);

            $reply_date = timeline_time_ago($reply->post_date);
            $url = get_home_url() . "/timeline/?q=" . $reply->ID;
            array_push($all_topics['replies'], array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_imagearray' => $reply_imagearray,  'reply_videoarray' => $reply_videoarray, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'video_thumbnail_url' => $video_thumbnail_url, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'badge' => $badge_icon, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'product_id' => $pid, 'product_sku' => $p_sku, 'product_title' => $product_title, 'product_image' => $product_image, 'product_upc' => $product_upc, 'product_rating' => $product_rating, 'product_price' => $product_price, 'tagged_location' => $lid, 'external_url' => $external_url, 'replies' => $replies));
        }
    }


    return $all_topics;

}


function handle_timeline_list(WP_REST_Request $request)
{	
	//echo 1; exit;
    global $wpdb;
	$item = $request->get_json_params();

	$page = $item['page'] ? $item['page'] : 1;
	$posts_per_page = $item['posts_per_page'] ? $item['posts_per_page'] : 10;

	$topic_ID = 35832;

	$cur_user = wp_get_current_user();
	$cur_user_id = $cur_user->data->ID;
	if (!$cur_user_id) {
		$cur_user_id = '0';
	}
	if($cur_user > 0){
		birthday_rewards();
		update_rewards();
	}

	// Fetch users that the current user has blocked
	$query_blocked_by_user = $wpdb->prepare("
							SELECT blocked_user 
							FROM wp_users_blocked 
							WHERE blocked_by = %d
							", $cur_user_id);

	$blocked_by_user = $wpdb->get_col($query_blocked_by_user);

	// Fetch users who have blocked the current user
	$query_blocked_by_others = $wpdb->prepare("
							SELECT blocked_by 
							FROM wp_users_blocked 
							WHERE blocked_user = %d
							", $cur_user_id);

	$blocked_by_others = $wpdb->get_col($query_blocked_by_others);

	// Combine both lists of blocked users
	$mutually_blocked_users = array_merge($blocked_by_user, $blocked_by_others);

	// If no users are blocked, set the array to contain a non-existent user ID
	if (empty($mutually_blocked_users)) {
		$mutually_blocked_users = [0]; // Set to 0 or any invalid user ID to avoid issues
	}

	
	$all_topics = array();


	$args = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'DESC',
		'post_parent' => $topic_ID,
		'posts_per_page' => $posts_per_page,
		'paged' => $page,
		'author__not_in' => $mutually_blocked_users,
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'compare' => 'NOT EXISTS'
			)
		),
	];

	$args2 = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'ASC',
		'fields' => 'ids',
		'post_parent' => $topic_ID,
		'author__not_in' => $mutually_blocked_users,
		'numberposts' => -1,
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'compare' => 'NOT EXISTS'
			)
		),
	];

	$posts_replies = get_posts($args2);
	$posts_cnt = count($posts_replies);

	$cur_user = wp_get_current_user();
	$cur_user_id = $cur_user->data->ID;
	if (!$cur_user_id) {
		$cur_user_id = '0';
	}

	$replies = get_posts($args);
	$all_topics['total_posts'] = $posts_cnt;
	$all_topics['user_id'] = $cur_user_id;
	//for sposnored ad's
	$all_topics['sponsored_ads'] = array();
	$day = date('Ymd');
	$query = $wpdb->prepare("SELECT *  FROM wp_sponsored_ads WHERE  end_date >=$day and status=0 order by id desc");
	$notifications_list = $wpdb->get_results($query);
	//	print_r($notifications_list);exit;
	//$notifications['count']=count($notifications_list);
	$k = 0;
	$sponsored_verify = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-sponsored.png";
	update_rewards();
	foreach ($notifications_list as $not) {
		// Resolve product info dynamically. Ads may have no product linked, so
		// default every field to empty and only populate when a valid product
		// actually exists. Keeps the API keys present (no hardcoded references).
		$pid            = (!empty($not->product_id) && (int) $not->product_id > 0) ? (int) $not->product_id : '';
		$product_title  = '';
		$external_url   = '';
		$product_image  = '';
		$product_upc    = '';
		$product_rating = 0;
		$product_price  = '';
		$p_sku          = '';

		if ($pid !== '') {
			$productlis = get_post($pid);
			if ($productlis) {
				$product_title = $productlis->post_title;
				$external_url  = isset($productlis->external_url) ? $productlis->external_url : '';
			}
			$product_image = get_the_post_thumbnail_url($pid, 'full');
			if (!$product_image) {
				$product_image = '';
			}
			$abc         = get_post_meta($pid, 'productupc', true);
			$product_upc = str_replace("#", "", (string) $abc);

			if (function_exists('wc_get_product')) {
				$the_product = wc_get_product($pid);
				if ($the_product) {
					$product_rating = $the_product->get_average_rating();
					if ($product_rating == '') {
						$product_rating = 0;
					}
					$product_price = $the_product->get_price();
					if ($product_price == '') {
						$product_price = '';
					}
					$p_sku = $the_product->get_sku();
					if ($p_sku == '') {
						$p_sku = '';
					}
				}
			}
		}

		$query = $wpdb->prepare("SELECT count(*) as cnt FROM wp_sponsored_likes WHERE  spons_id =$not->id");
		$cnt_list = $wpdb->get_results($query);
		$likes_count = $cnt_list[0]->cnt;
		//$likes_count =255+$likes_count;

		$userid = $cur_user->data->ID;
		//print_r($userid);exit;
		if ($userid > 0) {
			$query1 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $userid, $not->id);
			//print_r($query1);exit;
			$list = $wpdb->get_results($query1);
			//print_r($list);exit;
			if ($list[0]->cnt >= 1) {
				$is_liked = "1";
			} else {
				$is_liked = "0";
			}
		} else {
			$is_liked = "0";
		}
		if ($not->is_bar == '1') {
			$isbar = 1;
		} else {
			$isbar = 0;
		}
		$query2 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_comments` WHERE spons_id = '%d'", $not->id);
		$cnt_list2 = $wpdb->get_results($query2);
		$total_replies_count = (int) $cnt_list2[0]->cnt;
		$url = get_home_url() . "/timeline_sponsads/?q=" . $not->id;
		$spons_replies = get_timeline_sponsreplies($not->id, 1);
		$lurl = explode('/', $not->collection_link);
		if ($lurl[3] == 'bourbon-collection') {
			$curl = 1;
		} else {
			$curl = 0;
		}
		$str = str_replace("-", " ", $lurl[4]);
		$query3 = $wpdb->prepare("SELECT collection_id FROM `wp_collections` WHERE collection_orgname = '%s'", $str);
		$cid = $wpdb->get_results($query3);
		if ($cid[0]->collection_id == '') {
			$cid = '';
		} else {
			$cid = $cid[0]->collection_id;
		}

		$reply_date = timeline_time_ago($not->created_at);
		$all_topics['sponsored_ads'][$k]['spons_id'] = $not->id;
		$all_topics['sponsored_ads'][$k]['company_name'] = $not->company_name;
		$all_topics['sponsored_ads'][$k]['company_logo'] = $not->company_logo;
		$all_topics['sponsored_ads'][$k]['description'] = strip_tags($not->description);
		$all_topics['sponsored_ads'][$k]['image'] = $not->image;
		$all_topics['sponsored_ads'][$k]['link'] = $not->link;
		$all_topics['sponsored_ads'][$k]['is_collection'] = $curl;
		$all_topics['sponsored_ads'][$k]['is_bar'] = $isbar;
		$all_topics['sponsored_ads'][$k]['collection_id'] = $cid;
		$all_topics['sponsored_ads'][$k]['spons_date'] = $reply_date;
		$all_topics['sponsored_ads'][$k]['spons_verified'] = $sponsored_verify;
		$all_topics['sponsored_ads'][$k]['total_replies_count'] = $total_replies_count;
		$all_topics['sponsored_ads'][$k]['url'] = $url;
		$all_topics['sponsored_ads'][$k]['likes_count'] = $likes_count;
		$all_topics['sponsored_ads'][$k]['is_liked'] = $is_liked;
		$all_topics['sponsored_ads'][$k]['product_id'] = $pid;
		$all_topics['sponsored_ads'][$k]['product_sku'] = $p_sku;
		$all_topics['sponsored_ads'][$k]['product_title'] = $product_title;
		$all_topics['sponsored_ads'][$k]['product_image'] = $product_image;
		$all_topics['sponsored_ads'][$k]['product_upc'] = $product_upc;
		$all_topics['sponsored_ads'][$k]['product_rating'] = $product_rating;
		$all_topics['sponsored_ads'][$k]['product_price'] = $product_price;
		$all_topics['sponsored_ads'][$k]['replies'] = $spons_replies;
		$all_topics['sponsored_ads'][$k]['external_url'] = !empty($external_url) ? $external_url : $not->link;
		$all_topics['sponsored_ads'][$k]['buynow_text'] = $not->ad_type;
		//$all_topics['sponsored_ads'][$k]['external_url'] = "https://arkaybeverages.com/product/arkay-version-of-alcohol-free-on-fire-whisky-flavoured-drink-best-seller-gluten-free-sugar-free-guilt-free-free-shipping/";

		$k++;
	}//for sposnored ad's
	$all_topics['replies'] = array();
	$badge_icon = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/badge-gold.png";
	foreach ($replies as $reply) {
		$parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
		if (!$parent_info) {
			$author_id = $reply->post_author;
			$author_details = get_user_by('id', $author_id);
			$author_meta = get_user_meta($author_id);
			$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

			if (!$avatar) {
				$avatar = get_avatar_url($author_id);
			}

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
			$cnt_list = $wpdb->get_results($query);
			$likes_count = $cnt_list[0]->cnt;

			$reply_f_image1 = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'medium_large');
			if ($reply_f_image1[0]) {
				$reply_image_path = $reply_f_image1[0];
			} else {
				$reply_image_path = '';
			}


			$thumbnail_id = get_post_meta($reply->ID, '_thumbnail_id', true);
			$t = explode(',', $thumbnail_id);
			$reply_f_image = [];
			foreach ($t as $keyss => $value) {

				$reply_f_image[] = wp_get_attachment_image_src($value, 'medium_large');
			}
			$array1 = array();
			if ($keyss == 0 && $reply_f_image[0][0]) {
				array_push($array1, $reply_f_image[0][0]);
			} else if ($keyss == 1 && $reply_f_image[1][0]) {
				array_push($array1, $reply_f_image[0][0], $reply_f_image[1][0]);
			} else if ($keyss == 2 && $reply_f_image[2][0]) {
				array_push($array1, $reply_f_image[0][0], $reply_f_image[1][0], $reply_f_image[2][0]);
			} else if ($keyss == 3 && $reply_f_image[3][0]) {
				array_push($array1, $reply_f_image[0][0], $reply_f_image[1][0], $reply_f_image[2][0], $reply_f_image[3][0]);
			} else if ($keyss == 4 && $reply_f_image[4][0]) {
				array_push($array1, $reply_f_image[0][0], $reply_f_image[1][0], $reply_f_image[2][0], $reply_f_image[3][0], $reply_f_image[4][0]);
			} else {
			}





			$query = array(
				'post_type' => 'reply',
				'post_status' => 'publish',
				'order' => 'ASC',
				'author__not_in' => $mutually_blocked_users,
				'meta_query' => array(
					array(
						'key' => '_bbp_reply_to',
						'value' => $reply->ID,
					)
				),
			);

			//for tagged location
			$query2 = $wpdb->prepare("SELECT meta_value as lid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_tagged_location'");

			$l_list = $wpdb->get_results($query2);
			$lid = $l_list[0]->lid;
			if ($lid == '') {
				$lid = '';
			}
			//for product tag


			$query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $reply->ID AND meta_key ='_bbp_product_id'");

			$p_list = $wpdb->get_results($query1);
			$pid = $p_list[0]->pid;
			if ($pid == '') {
				$pid = '';
			}
			$productlis = get_post($pid);

			$product_title = $productlis->post_title;
			if ($product_title == '') {
				$product_title = '';
			}
			$product_image = get_the_post_thumbnail_url($pid, 'full');
			if ($product_image == '') {
				$product_image = '';
			}
			$abc = get_post_meta($pid, 'productupc', true);
			$product_upc = str_replace("#", "", $abc);
			if ($product_upc == '') {
				$product_upc = '';
			}
			$the_product = wc_get_product($pid);
			$product_rating = $the_product->average_rating;
			if ($product_rating == '') {
				$product_rating = 0;
			}
			$product_price = $the_product->price;
			if ($product_price == '') {
				$product_price = '';
			}
			$p_sku = $the_product->sku;
			if ($p_sku == '') {
				$p_sku = '';
			}
			$external_url = $productlis->external_url;
			if ($external_url == '') {
				$external_url = '';
			}

			$results = new WP_Query($query);
			$total_replies_count = $results->found_posts; //// This is 0...
			//echo $results->count_posts; //// This is 0...
			wp_reset_postdata();

			$replies = get_timeline_replies($reply->ID, 1);

			$reply_date = timeline_time_ago($reply->post_date);
			$url = get_home_url() . "/timeline/?q=" . $reply->ID;
			array_push($all_topics['replies'], array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_imagearray' => $array1, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'badge' => $badge_icon, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'product_id' => $pid, 'product_sku' => $p_sku, 'product_title' => $product_title, 'product_image' => $product_image, 'product_upc' => $product_upc, 'product_rating' => $product_rating, 'product_price' => $product_price, 'tagged_location' => $lid, 'external_url' => $external_url, 'replies' => $replies));
		}
	}


	return $all_topics;

}


function handle_topics_details(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$topic = get_post($item['ID']);
	$all_topics = array();


	if ($topic->post_type == 'topic' && $topic->post_status == 'publish') {

		$all_topics['topic_id'] = $topic->ID;
		$all_topics['forum_url'] = get_permalink($topic->ID);
		$all_topics['topic_title'] = $topic->post_title;

		$thumb_image_id = get_post_meta($topic->ID, 'thumb_image', true);
		$thumb_featured_image_id = get_post_meta($topic->ID, 'featured_image', true);

		$featured_img = wp_get_attachment_image_src($thumb_featured_image_id, 'full');
		$thumb_img = wp_get_attachment_image_src($thumb_image_id, 'full');

		$all_topics['topic_featured_image'] = $featured_img[0];
		$all_topics['topic_thumb_image'] = $thumb_img[0];
		$all_topics['topic_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $topic->post_content));
		$all_topics['topic_short_desc_top'] = get_post_meta($topic->ID, 'short_description_top', true);
		$all_topics['topic_short_desc_bottom'] = get_post_meta($topic->ID, 'short_description_bottom', true);


		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'ASC',
			'post_parent' => $topic->ID,
			'numberposts' => -1
		];

		$replies = get_posts($args);
		$all_topics['replies'] = array();
		foreach ($replies as $reply) {
			$a = $reply->post_content;

			if (!empty($a)) {
				$b = explode('<', $a);

				$text = $b[0];
				$img = $b[1];

				preg_match_all('/(alt|title|src)=("[^"]*")/i', $a, $result);

				$f1image = $result[2][0];
				$fimage = trim($f1image, '\'"');


			}
			if ($fimage == null) {
				$fimage = "";
			}
			$parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
			//print_r($parent_info);echo "<br><hr><br>";
			if (!$parent_info) {
				$author_id = $reply->post_author;
				$author_details = get_user_by('id', $author_id);
				$author_meta = get_user_meta($author_id);
				$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
				$cnt_list = $wpdb->get_results($query);
				$likes_count = $cnt_list[0]->cnt;

				$replies = get_replies($reply->ID);
				//$replies = bbp_get_reply_ancestors($reply->ID)
				//print_r($replies);
				//print_r($author_details);
				array_push($all_topics['replies'], array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'replies' => $replies, 'text' => $text, 'image' => $fimage));
			}
		}


		return $all_topics;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid Topic ID', array('status' => 403));
	}
}

function get_like_flag($reply_id)
{
	global $wpdb;
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE user_id = '%d' AND reply_id = '%d' and status ='0'", $user_id, $reply_id);
		$list = $wpdb->get_results($query);
		if ($list[0]->cnt >= 1) {
			return '1';
		} else {
			return '0';
		}
	} else {
		return '0';
	}

}

function get_timeline_replies($parent_id, $page)
{
	global $wpdb;
	if (!$page || $page <= 0) {
		$page = 1;
	}

	$args = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'value' => $parent_id,
			)
		),
		'paged' => $page,
		'numberposts' => 1,
	];

	//print_r($args);
	$replies = get_posts($args);
	//print_r($replies);
	$all_replies = array();
	foreach ($replies as $reply) {
		$author_id = $reply->post_author;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		if (!$avatar) {
			$avatar = get_avatar_url($author_id);
		}

		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
		$cnt_list = $wpdb->get_results($query);
		$likes_count = $cnt_list[0]->cnt;

		$reply_f_image = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'single-post-thumbnail');
		if ($reply_f_image[0]) {
			$reply_image_path = $reply_f_image[0];
		} else {
			$reply_image_path = '';
		}
		//$next_page = $page+1;
		$reply_date = timeline_time_ago($reply->post_date);

		$query = array(
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
					'value' => $reply->ID,
				)
			),
		);

		$results = new WP_Query($query);
		$total_replies_count = $results->found_posts; //// This is 0...
		//echo $results->count_posts; //// This is 0...
		wp_reset_postdata();

		$replies = get_timeline_replies($reply->ID, 1);
		$url = get_home_url() . "/timeline/?q=" . $reply->ID;

		array_push($all_replies, array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'replies' => $replies));
	}


	return $all_replies;
}

function get_replies($parent_id)
{
	global $wpdb;
	$args = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'value' => $parent_id,
			)
		)
	];
	//print_r($args);
	$replies = get_posts($args);
	//print_r($replies);
	$all_replies = array();
	foreach ($replies as $reply) {
		$a = $reply->post_content;

		if (!empty($a)) {
			$b = explode('<', $a);

			$text = $b[0];
			$img = $b[1];

			preg_match_all('/(alt|title|src)=("[^"]*")/i', $a, $result);

			$f1image = $result[2][0];
			$fimage = trim($f1image, '\'"');


		}
		if ($fimage == null) {
			$fimage = "";
		}
		$author_id = $reply->post_author;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
		$cnt_list = $wpdb->get_results($query);
		$likes_count = $cnt_list[0]->cnt;

		$replies = get_replies($reply->ID);

		array_push($all_replies, array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'replies' => $replies, 'text' => $text, 'image' => $fimage));
	}


	return $all_replies;
}

function handle_user_wishlist(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	if ($item['wishlist'] != '0') {
		$existing_wishlist = $user_details['wishlist'][0];
		//print_r($existing_wishlist);
		if ($existing_wishlist) {
			$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
			//$existing_wishlist_arr = explode(',',$existing_wishlist);
			array_push($existing_wishlist_arr, $item['product_id']);
			$unique_wishlist = array_unique($existing_wishlist_arr);
			//print_r($unique_wishlist);
			update_user_meta($cur_user->data->ID, 'wishlist', $unique_wishlist);
			return array("message" => "Product added to wishlist");
		} else {
			update_user_meta($cur_user->data->ID, 'wishlist', array($item['product_id']));
			return array("message" => "Product added to wishlist");
		}
	} else {
		$existing_wishlist = $user_details['wishlist'][0];
		//print_r($existing_wishlist);
		if ($existing_wishlist) {
			$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
			if (($key = array_search($item['product_id'], $existing_wishlist_arr)) !== false) {
				unset($existing_wishlist_arr[$key]);
			} else {
				return new WP_Error('rest_forbidden', 'Product does not exists in your wishlist', array('status' => 403));
			}
			//print_r($existing_wishlist_arr);
			if (update_user_meta($cur_user->data->ID, 'wishlist', $existing_wishlist_arr)) {
				return array_values($existing_wishlist_arr);
			}
		} else {
			return new WP_Error('rest_forbidden', 'Product does not exists in your wishlist', array('status' => 403));
		}

	}
}

function handle_getuser_wishlist(WP_REST_Request $request)
{
	global $wpdb;

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);

	$existing_wishlist = $user_details['wishlist'][0];
	//print_r($existing_wishlist);
	if ($existing_wishlist) {
		$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
		$unique_wishlist = array_unique($existing_wishlist_arr);
		$wish_list_prods = array_values($unique_wishlist);

		$all_products = array();
		if (!empty($wish_list_prods)) {
			$args = [
				'post_type' => 'product',
				'post_status' => 'publish',
				'include' => $wish_list_prods,
				'order' => 'ASC'
			];

			$products = get_posts($args);
			foreach ($products as $key => $product) {
				$the_product = wc_get_product($product->ID);
				//print_r($the_product);
				$all_products[$key]['product_id'] = $product->ID;
				$all_products[$key]['product_title'] = $product->post_title;
				$all_products[$key]['product_image'] = get_the_post_thumbnail_url($product->ID, 'full');
				$all_products[$key]['product_sm_image'] = get_the_post_thumbnail_url($product->ID, 'medium');
				$all_products[$key]['product_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $product->post_content));
				$all_products[$key]['product_price'] = $the_product->price;
				//$all_products[$key]['product_regular_price'] = $the_product->regular_price;
				//$all_products[$key]['product_sale_price'] = $the_product->sale_price;
				$all_products[$key]['product_short_desc'] = $the_product->short_description;
				$all_products[$key]['product_sku'] = $the_product->sku;
				// $all_products[$key]['product_rating'] = $the_product->average_rating;
				// $all_products[$key]['product_rating_count'] = $the_product->review_count;

				// ---- ADD THESE NEW LINES ---- //
				$all_products['product_rating'] = (float) get_post_meta($product->ID, '_product_average_rating', true);
				$all_products['product_rating_count'] = (int) get_post_meta($product->ID, '_product_rating_count', true);

				// Get the current user's specific rating, if it exists
				$user_rating = get_user_rating_for_product($product->ID, $cur_user->data->ID);

				$all_products['current_user_has_rated'] = $user_rating ? true : false;
				$all_products['current_user_rating'] = $user_rating; // Send full rating object or null
				// ---- END OF NEW LINES ---- /

				$prod_cats = array();
				foreach ($the_product->category_ids as $prod_cat_id) {
					$prod_cat = bar_get_product_category_by_id($prod_cat_id);
					$prod_cats[$prod_cat_id] = $prod_cat;
				}
				$all_products[$key]['product_categories'] = $prod_cats;


				$prod_tags = array();
				foreach ($the_product->tag_ids as $prod_tag_id) {
					$prod_tag = bar_get_product_tag_by_id($prod_tag_id);
					$prod_tags[$prod_tag_id] = $prod_tag;
				}
				$all_products[$key]['product_tags'] = $prod_tags;

			}
			return $all_products;
		} else {
			return new WP_Error('rest_forbidden', 'Your wishlist is empty, start adding products to your wishlist', array('status' => 403));
		}

	} else {
		return new WP_Error('rest_forbidden', 'Your wishlist is empty, start adding products to your wishlist', array('status' => 403));
	}
}

function handle_timeline_addpost(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$item['topic_id'] = 35832;

	if ($item['reply_to'] == '' || $item['reply_to'] <= 0) {
		$item['reply_to'] = 35832;
	}
	if (isset($item['product_id']) == '') {
		$pid = '';
	} else {
		$pid = $item['product_id'];
	}
	$from_device = $item['from_device'];


	$cur_user = wp_get_current_user();
	//added by sumeeth
	$author_name = $cur_user->data->display_name;
	$author_id = $cur_user->data->ID;
	//added by sumeeth
	$user_details = get_user_meta($cur_user->data->ID);
	$pavatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
	if ($item['topic_id'] > 0 && $item['reply_to'] > 0) { //by sumeeth

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);
				//print_r($attach_data);exit;

				//$attach_url = wp_get_attachment_url($attachment_id);

				//$item['reply'] = "<img src='".$attach_url."' class='reply_attach'><br>".$item['reply'];
				//update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$cur_user);
				//update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
			//update_user_meta($user_id, 'wp_user_avatar', '');
		}

		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'from_device' => $from_device, 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		$new_reply_id = bbp_insert_reply($reply_data, $reply_meta);
		if ($new_reply_id) {
			// added by salman for reward points
			if($item['reply_to'] == 35832){
				$reward_msg = reward_points("add",(int)6, $cur_user->data->ID, $new_reply_id);
				update_rewards();
			}else{
				$reward_msg = reward_points("add",(int)8,$cur_user->data->ID, $new_reply_id);
			}
			$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $item['reply_to']);
			$lists = $wpdb->get_results($queryuid);
			$uid = $lists[0]->uid;
			$rid = $item['reply_to'];
			//to check whether it is comment or not
			$queryrepl = $wpdb->prepare("SELECT meta_value as reply_id FROM wp_postmeta WHERE post_id =$rid  AND meta_key ='_bbp_reply_to'");

			$p_listrep = $wpdb->get_results($queryrepl);
			$prep = $p_listrep[0]->reply_id;

			if ($prep == '') {
				$prep = $item['reply_to'];
			} else {
				$prep = $prep;
			}

			$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $cur_user->data->ID);
			$listss = $wpdb->get_results($queryuname);
			$uname = $listss[0]->uname;
			//print_r($uid);exit;
			$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = '%d'", $uid);
			$andriod_recipents = $wpdb->get_results($query, ARRAY_N);

			$andriod_device_ids = array();
			foreach ($andriod_recipents as $andriod_recipent) {
				array_push($andriod_device_ids, $andriod_recipent[0]);
			}
			//print_r($andriod_device_ids);exit;
			$arrNotification = array();
			//$arrNotification["body"] = "1 like"; //for removing html tags
			//print_r(wp_encode_emoji($uname));		
			$content_text = $uname . ' Commented on your Post';
			$arrNotification["title"] = 'SIPN';
			$arrNotification["body"] = $uname . ' Commented on your Post';  //strip_tags($post->post_content); //for removing html tags
			$arrNotification["sound"] = "default";
			$arrNotification["targetID"] = $prep;
			$arrNotification["targetType"] = "postdetail";
			$arrNotification["type"] = 1;
			//$arrNotification["badge"] = 1;
			$arrNotification["targetContent"]["targetID"] = $prep;
			$arrNotification["targetContent"]["targetType"] = "postdetail";
			//print_r($arrNotification);exit;
			// INCLUDE YOUR FCM FILE
			//include_once 'fcm.php'; 
			include_once ABSPATH . 'wp-content/themes/SIPN/fcm.php';
			$fcm = new FCM();
			if ($uid != $cur_user->data->ID) {
				$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`, `comment_id`) VALUES (%s, %d, %s, %d, %d, %s, %s)", 'Comment', $prep, $content_text, $cur_user->data->ID, $uid, 'Commentfromapp', $new_reply_id);
				$res = $wpdb->query($querystore);
				$noti_id = $wpdb->insert_id;
				$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Commentfromapp", $noti_id);
			}
			if ($attachment_id) {
				add_post_meta($new_reply_id, '_thumbnail_id', $attachment_id);
			}
			//return array("message"=>"your post is submitted successfully.");
			//added  by sumeeth
			return array("author" => $author_name, "author_id" => $author_id, "avatar" => $pavatar, "reply_id" => $new_reply_id, "product_id" => $pid, "message" => "your post is submitted successfully.", "reward_message" => $reward_msg);
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );
}

function handle_timeline_deletepost(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();


	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	$user_id = $cur_user->data->ID;
	if ($item['reply_id'] != '') {

		$reply_id = $item['reply_id'];

		if (!current_user_can('edit_reply', $reply_id)) {
			return new WP_Error('rest_forbidden', 'You do not have permission to delete that post.', array('status' => 403));
		}

		if (wp_trash_post($reply_id)) {
			$query = $wpdb->prepare("INSERT INTO `wp_users_activity` (`post_comment_id`, `post_type`, `deleted_by`) VALUES (%d, %s, %d)", $reply_id, 'post', $user_id);
			$res = $wpdb->query($query);

			$query = $wpdb->prepare("UPDATE `notification_table` SET status='1', platform='Deletecommentfromapp' WHERE comment_id = '%d'", $reply_id);
			$res = $wpdb->query($query);
			if($res){
				reward_points("remove",(int)8,$user_id, $reply_id);
			}
			//for delete post
			$query = $wpdb->prepare("UPDATE `notification_table` SET status='1', platform='Deletepostfromapp' WHERE content = '%d'", $reply_id);
			$res = $wpdb->query($query);
			//return array("message"=>"your post is deleted successfully.");
			//added by sumeeth
			reward_points("remove",(int)6,$user_id, $reply_id);
			update_rewards();
			return array("reply_id" => $reply_id, "message" => "your post is deleted successfully.");
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not deleted.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not deleted. Please check the provided data.', array('status' => 403));
	}

}


function handle_timeline_editpost(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$item['topic_id'] = 35832;

	if ($item['reply_to'] == '' || $item['reply_to'] <= 0) {
		$item['reply_to'] = 35832;
	}
	if (isset($item['product_id']) == '') {
		$pid = 'null';
	} else {
		$pid = $item['product_id'];
	}

	if (isset($item['from_device']) == '') {
		$from_device = $item['from_device'];
	} else {
		$from_device = $item['from_device'];
	}

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	if ($item['reply_id'] > 0) { //by sumeeth

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);
				//print_r($attach_data);exit;

				//$attach_url = wp_get_attachment_url($attachment_id);

				//$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']); 

				//$item['reply'] = "<img src='".$attach_url."' class='reply_attach'><br>".$item['reply'];
				//update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$cur_user);
				//update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
			//added by sumeeth for edit image empty
			update_post_meta($item['reply_id'], '_thumbnail_id', '');
			//update_user_meta($user_id, 'wp_user_avatar', '');
			//$content = preg_replace("/<img[^>]+\>/i", " ", $content); 
			//$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']); 
		}

		$reply_id = $item['reply_id'];
		$querypid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$pid' WHERE post_id =$reply_id AND meta_key='_bbp_product_id'");
		//print_r($querypid);exit;
		$res123 = $wpdb->query($querypid);

		$queryfrom = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$from_device' WHERE post_id =$reply_id AND meta_key='_bbp_from_device'");
		//print_r($queryfrom);exit;
		$res12345 = $wpdb->query($queryfrom);

		$reply_content = $item['reply'];
		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		if (!current_user_can('edit_reply', $reply_id)) {
			return new WP_Error('rest_forbidden', 'You do not have permission to edit that reply.', array('status' => 403));
		}

		/** Reply Topic ***********************************************************/

		$topic_id = bbp_get_reply_topic_id($reply_id);

		/** Topic Forum ***********************************************************/

		//$forum_id = bbp_get_topic_forum_id( $topic_id );

		$reply_data = apply_filters('bbp_edit_reply_pre_insert', array(
			'ID' => $reply_id,
			'post_content' => $reply_content,
			'post_parent' => $topic_id,
			'post_author' => $reply_data['post_author'],
			'post_type' => 'reply'
		));

		$reply_id = wp_update_post($reply_data);
		if (wp_update_post($reply_data)) {
			if ($attachment_id) {
				delete_post_thumbnail($reply_id);
				add_post_meta($reply_id, '_thumbnail_id', $attachment_id);
			}
			//bbp_update_reply( $reply_id, $reply_meta['topic_id'], '0', array(), $reply_data['post_author'], true, $reply_meta['reply_to'] );
			//return array("message"=>"your post is updated successfully.");
			//added by sumeeth
			return array("reply_id" => $reply_id, "product_id" => $pid, "message" => "your post is updated successfully.");

		} else {
			return new WP_Error('rest_forbidden', 'Your post is not updated.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );

}


function handle_topics_add(WP_REST_Request $request)
{

	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);

	if (($item['reply'] != '' || $item['reply_img'] != '') && $item['topic_id'] > 0 && $item['reply_to'] > 0) {

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);
				//print_r($attach_data);exit;

				$attach_url = wp_get_attachment_url($attachment_id);
				$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';
				//$item['reply'] = "<img src='".$attach_url."' class='reply_attach'><br>".$item['reply'];
				//update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$cur_user);
				//update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
			//update_user_meta($user_id, 'wp_user_avatar', '');
		}

		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);
		$reward_msg = reward_points("add",(int)8,$cur_user->data->ID, $item['reply_to']);
		if (bbp_insert_reply($reply_data, $reply_meta)) {
			return array("message" => "your post is submitted successfully.", "reward_message" => $reward_msg);
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );
}

function handle_topics_edit(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	if ($item['reply'] != '' && $item['reply_id'] > 0) {

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);
				//print_r($attach_data);exit;

				$attach_url = wp_get_attachment_url($attachment_id);

				$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
				$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';
				//$item['reply'] = "<img src='".$attach_url."' class='reply_attach'><br>".$item['reply']; by sumeeth
				//update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$cur_user);
				//update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
			//update_user_meta($user_id, 'wp_user_avatar', '');
			//$content = preg_replace("/<img[^>]+\>/i", " ", $content); 
			$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
		}

		$reply_id = $item['reply_id'];
		$reply_content = $item['reply'];
		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		if (!current_user_can('edit_reply', $reply_id)) {
			return new WP_Error('rest_forbidden', 'You do not have permission to edit that reply.', array('status' => 403));
		}

		/** Reply Topic ***********************************************************/

		$topic_id = bbp_get_reply_topic_id($reply_id);

		/** Topic Forum ***********************************************************/

		//$forum_id = bbp_get_topic_forum_id( $topic_id );

		$reply_data = apply_filters('bbp_edit_reply_pre_insert', array(
			'ID' => $reply_id,
			'post_content' => $reply_content,
			'post_parent' => $topic_id,
			'post_author' => $reply_data['post_author'],
			'post_type' => 'reply'
		));

		$reply_id = wp_update_post($reply_data);
		if (wp_update_post($reply_data)) {

			//bbp_update_reply( $reply_id, $reply_meta['topic_id'], '0', array(), $reply_data['post_author'], true, $reply_meta['reply_to'] );
			return array("message" => "your post is updated successfully.");

		} else {
			return new WP_Error('rest_forbidden', 'Your post is not updated.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );
}

function handle_user_bar(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	if ($item['user_id']) {
		$user_dets = get_userdata($item['user_id']);
		$bar_output = array();

		$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, b.shared as shared, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $user_dets->data->user_email);
		$shelves = $wpdb->get_results($query);

		if ($shelves[0]->shared) {
			if (!empty($shelves)) {
				$bar_output['shelves'] = array();
				$bar_output['bar_id'] = $shelves[0]->bar_id;
				$bar_output['bar_name'] = $shelves[0]->bar_name;
				$bar_output['is_public'] = $shelves[0]->shared;


				foreach ($shelves as $shelf) {
					$products_query = $wpdb->prepare("SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight FROM wp_bar_shelves bs LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id LEFT JOIN wp_posts p ON bsp.product_id = p.id WHERE bsp.shelve_id = '%d' ORDER BY product_weight ASC", $shelf->shelf_id);
					$prods = $wpdb->get_results($products_query);
					$the_prods = array();

					// Check if all product_ids are null
					$all_product_ids_null = true;
					if (!empty($prods)) {
						foreach ($prods as $prod) {
							if ($prod->product_id !== null || !empty($prod->product_id)) {
								$all_product_ids_null = false;
								break;
							}
						}

						// If not all product_ids are null, process the products
						if (!$all_product_ids_null) {
							foreach ($prods as $prod) {
								$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
								$product_sm_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
								$prod_price = (float) get_post_meta($prod->product_id, '_price', true);

								array_push($the_prods, array(
									'product_id' => $prod->product_id,
									'product_name' => $prod->product_name,
									'product_weight' => $prod->product_weight,
									'product_image' => $product_image,
									'product_sm_image' => $product_sm_image,
									'product_price' => $prod_price
								));
							}
						}
					}

					// Add shelf to the output
					array_push($bar_output['shelves'], array(
						'shelf_id' => $shelf->shelf_id,
						'shelf_name' => $shelf->shelf_name,
						'shelf_weight' => $shelf->shelf_weight,
						'products' => $the_prods
					));
				}


				$user_details = get_user_meta($user_dets->data->ID);
				//print_r($user_details);

				$my_profile = array();
				$badge_icon = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-sponsored.png";
				if ($user_dets->data->ID) {
					$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
					$my_profile['user_id'] = $user_dets->data->ID;
					$my_profile['user_email'] = $user_dets->data->user_email;
					$my_profile['name'] = $user_dets->data->display_name;
					$my_profile['bio'] = $user_details['bio'][0];
					$my_profile['phone_number'] = $user_details['phone_number'][0];
					$my_profile['address'] = $user_details['address'][0];
					$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
					$my_profile['city'] = $user_details['city'][0];
					$my_profile['state'] = $user_details['state'][0];
					$my_profile['zipcode'] = $user_details['zipcode'][0];
					$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
					$my_profile['is_verified'] = $user_details['is_verified'][0];
					$my_profile['avatar'] = $avatar;
					$my_profile['badge'] = $badge_icon;
					$my_profile['is_profile_liked'] = get_profile_like_flag($user_dets->data->ID);
					$my_profile['likes'] = get_likes_count($user_dets->data->ID);
					$bar_output['user_details'] = $my_profile;
					$bar_output['bar_link'] = bbp_get_user_profile_url($item['user_id']);
				}

				return $bar_output;
			} else {
				//return new WP_Error( 'rest_forbidden', 'No Bar found for user.', array( 'status' => 403 ) );
				$user_details = get_user_meta($user_dets->data->ID);
				//print_r($user_details);

				$my_profile = array();
				if ($user_dets->data->ID) {
					$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
					$my_profile['user_id'] = $user_dets->data->ID;
					$my_profile['user_email'] = $user_dets->data->user_email;
					$my_profile['name'] = $user_dets->data->display_name;
					$my_profile['phone_number'] = $user_details['phone_number'][0];
					$my_profile['bio'] = $user_details['bio'][0];
					$my_profile['address'] = $user_details['address'][0];
					$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
					$my_profile['city'] = $user_details['city'][0];
					$my_profile['state'] = $user_details['state'][0];
					$my_profile['zipcode'] = $user_details['zipcode'][0];
					$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
					$my_profile['is_verified'] = $user_details['is_verified'][0];
					$my_profile['avatar'] = $avatar;
					$my_profile['is_profile_liked'] = get_profile_like_flag($user_dets->data->ID);
					$bar_output['user_details'] = $my_profile;
				}

				return $bar_output;
			}
		} else {
			if ($shelves[0]->shared == '0') {
				$user_details = get_user_meta($user_dets->data->ID);
				//print_r($user_details);
				$bar_output['shelves'] = array();
				$bar_output['bar_id'] = '';
				$bar_output['bar_name'] = '';
				$bar_output['is_public'] = 0;
				$bar_output['bar_link'] = '';
				$my_profile = array();
				if ($user_dets->data->ID) {
					$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
					$my_profile['user_id'] = $user_dets->data->ID;
					$my_profile['user_email'] = $user_dets->data->user_email;
					$my_profile['name'] = $user_dets->data->display_name;
					$my_profile['phone_number'] = $user_details['phone_number'][0];
					$my_profile['bio'] = $user_details['bio'][0];
					$my_profile['address'] = $user_details['address'][0];
					$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
					$my_profile['city'] = $user_details['city'][0];
					$my_profile['state'] = $user_details['state'][0];
					$my_profile['zipcode'] = $user_details['zipcode'][0];
					$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
					$my_profile['is_verified'] = $user_details['is_verified'][0];
					$my_profile['avatar'] = $avatar;
					$my_profile['is_profile_liked'] = get_profile_like_flag($user_dets->data->ID);
					$bar_output['user_details'] = $my_profile;
				}


				return $bar_output;
			} else {
				return new WP_Error('rest_forbidden', 'Bar doesnt exist', array('status' => 403));
			}
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid user details.', array('status' => 403));
	}

}


function get_profile_like_flag($profile_id)
{
	global $wpdb;
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE user_id = '%d' AND profile_id = '%d'", $user_id, $profile_id);
		$list = $wpdb->get_results($query);
		if ($list[0]->cnt >= 1) {
			return '1';
		} else {
			return '0';
		}
	} else {
		return '0';
	}
}

function handle_get_page(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	//print_r($item);
	if ($item['name'] == 'terms-of-service') {
		$item['name'] = 'terms';
	}
	if ($item['name']) {
		$page = get_posts([
			'name' => $item['name'],
			'post_type' => 'page'
		]);

		$page_details = array();
		$page_details['title'] = $page[0]->post_title;
		$page_details['content'] = nl2br($page[0]->post_content);
		return $page_details;
	} else {
		return new WP_Error('rest_forbidden', 'Invalid request.', array('status' => 403));
	}
}


function handle_get_videos_list(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$page = $item['page'] ? $item['page'] : 1;
	$videos_per_page = $item['videos_per_page'] ? $item['videos_per_page'] : 9;

	$start_index = $page * $videos_per_page - $videos_per_page;
	$end_index = $start_index + $videos_per_page;

	$frontpage_id = get_option('page_on_front');
	$videos = array();
	$home_videos = get_post_meta($frontpage_id, 'videos');
	$videos = array_filter(explode("\n", str_replace("\r", "", $home_videos[0])));
	$all_vids = array();
	$k = 0;

	if ($end_index > count($videos)) {
		$end_index = count($videos);
	}

	$new_videos = array_slice($videos, $start_index, $end_index);
	$all_vids['total_videos'] = count($videos);
	$all_vids['videos_per_page'] = $videos_per_page;
	$all_vids['videos'] = array();
	foreach ($new_videos as $video) {
		if ($k < $end_index && $k < count($new_videos)) {
			$video_details = explode("|", $video);
			$all_vids['videos'][$k]['urlyoutube'] = $video_details[0];
			$all_vids['videos'][$k]['url'] = $video_details[4]; //by sumeeth for videos vimeo and youtube
			$all_vids['videos'][$k]['thumb'] = $video_details[1];
			$all_vids['videos'][$k]['title'] = $video_details[2];
			$all_vids['videos'][$k]['description'] = $video_details[3]; //by sumeeth for videos description
			$k++;
		}
	}
	return $all_vids;

}

function handle_get_videos(WP_REST_Request $request)
{
	global $wpdb;

	$frontpage_id = get_option('page_on_front');
	$videos = array();
	$home_videos = get_post_meta($frontpage_id, 'videos');
	$videos = array_filter(explode("\n", str_replace("\r", "", $home_videos[0])));
	$all_vids = array();
	$k = 0;
	foreach ($videos as $video) {
		$video_details = explode("|", $video);
		if ($video_details[3] == '') {
			$video_details[3] = '';
		}
		$all_vids[$k]['urlyoutube'] = $video_details[0];
		$all_vids[$k]['url'] = $video_details[4];
		$all_vids[$k]['thumb'] = $video_details[1];
		$all_vids[$k]['title'] = $video_details[2];
		$all_vids[$k]['description'] = $video_details[3]; //by sumeeth for videos description
		$k++;
	}
	return $all_vids;

}

function handle_device(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($item['device_id'] != '' && $item['device_type'] != '') {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_devices` WHERE device_id = '%s'", $item['device_id']);
		$list = $wpdb->get_results($query);

		if ($list[0]->cnt >= 1) {
			if ($user_id)
				$query = $wpdb->prepare("UPDATE `wp_devices` SET device_type = %s, user_id = %d WHERE device_id = 
		%s", $item['device_type'], $user_id, $item['device_id']);
			else
				$query = $wpdb->prepare("UPDATE `wp_devices` SET device_type = %s WHERE device_id = 
		%s", $item['device_type'], $item['device_id']);

			$res = $wpdb->query($query);

			return array("message" => "your device info is updated successfully.");
		} else {

			$query = $wpdb->prepare("INSERT INTO `wp_devices` (`device_id`, `device_type`, `user_id`) VALUES (%s, %s, %d)", $item['device_id'], $item['device_type'], $user_id);
			$res = $wpdb->query($query);

			return array("message" => "your device info is updated successfully.");
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid device details.', array('status' => 403));
	}
}

function handle_reply_likes(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	$return_msg = "";
	if ($item['reply_id'] != '' && $item['like'] != '' && $user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE user_id = '%d' AND reply_id = '%d' and status ='0'", $user_id, $item['reply_id']);
		$list = $wpdb->get_results($query);
	
		if ($list[0]->cnt >= 1) {
			if ($item['like'] == 0) {

				$query = $wpdb->prepare("UPDATE `wp_reply_likes` SET status='1' WHERE user_id = '%d' AND reply_id = '%d'", $user_id, $item['reply_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("UPDATE `notification_table` SET status='1' WHERE notification_by = '%d' AND content = '%d'", $user_id, $item['reply_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $item['reply_id']);
				$list = $wpdb->get_results($query);
				reward_points("remove",(int)7,$user_id, $item['reply_id']);
				$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $item['reply_id']);
				$lists = $wpdb->get_results($queryuid);
				$uid = $lists[0]->uid;
				// reward_points("remove",(int)7,$uid, $item['reply_id'], 1);
				return array("message" => "Record updated successfully.", "likes" => $list[0]->cnt, "reply_id" => $item['reply_id'], "is_like" => '0');
			} else {
				return new WP_Error('rest_forbidden', 'Record already exists.', array('status' => 403));
			}
		} else {

			$query = $wpdb->prepare("INSERT INTO `wp_reply_likes` (`reply_id`, `user_id`) VALUES (%d, %d)", $item['reply_id'], $user_id);
			$res = $wpdb->query($query);
			$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $user_id);
			$listss = $wpdb->get_results($queryuname);
			$uname = $listss[0]->uname;
			$return_msg = reward_points("add",(int)7, $user_id, $item['reply_id']);
		
			$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $item['reply_id']);
			$lists = $wpdb->get_results($queryuid);
			$uid = $lists[0]->uid;
			// reward_points("add",(int)7,$uid, $item['reply_id'], 1);
			
			$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = '%d'", $uid);
			$andriod_recipents = $wpdb->get_results($query, ARRAY_N);

			$andriod_device_ids = array();
			foreach ($andriod_recipents as $andriod_recipent) {
				array_push($andriod_device_ids, $andriod_recipent[0]);
			}
		
			$arrNotification = array();
		
			$content_text = $uname . ' Liked your Post';
			$arrNotification["title"] = 'SIPN';
			$arrNotification["body"] = $uname . ' Liked your Post';
			$arrNotification["sound"] = "default";
			$arrNotification["targetID"] = $item['reply_id'];
			$arrNotification["targetType"] = "postdetail";
			$arrNotification["type"] = 1;
			$arrNotification["targetContent"]["targetID"] = $item['reply_id'];
			$arrNotification["targetContent"]["targetType"] = "postdetail";

			
			include_once ABSPATH . 'wp-content/themes/SIPN/fcm.php';
			$fcm = new FCM();
			if ($uid != $user_id) {
				$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`) VALUES (%s, %d, %s, %d, %d, %s)", 'Like', $item['reply_id'], $content_text, $user_id, $uid, 'Likefromapp');
				$res = $wpdb->query($querystore);
				$noti_id = $wpdb->insert_id;
				$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Likefromapp", $noti_id);
			}

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $item['reply_id']);
			$list = $wpdb->get_results($query);

			return array("message" => "Record updated successfully.", "likes" => $list[0]->cnt, "reply_id" => $item['reply_id'], "is_like" => '1', 'reward_message' => $return_msg);
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid request.', array('status' => 403));
	}
}


function handle_reply_comment_likes(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($item['reply_id'] != '' && $item['like'] != '' && $user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE user_id = '%d' AND reply_id = '%d' and status ='0'", $user_id, $item['reply_id']);
		$list = $wpdb->get_results($query);
	
		if ($list[0]->cnt >= 1) {
			if ($item['like'] == 0) {
				$query = $wpdb->prepare("UPDATE `wp_reply_likes` SET status='1' WHERE user_id = '%d' AND reply_id = '%d'", $user_id, $item['reply_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("UPDATE `notification_table` SET status='1' WHERE notification_by = '%d' AND content = '%d'", $user_id, $item['reply_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $item['reply_id']);
				$list = $wpdb->get_results($query);

				return array("message" => "Record updated successfully.", "likes" => $list[0]->cnt, "reply_id" => $item['reply_id'], "is_like" => '0');
			} else {
				return new WP_Error('rest_forbidden', 'Record already exists.', array('status' => 403));
			}
		} else {

			$query = $wpdb->prepare("INSERT INTO `wp_reply_likes` (`reply_id`, `user_id`) VALUES (%d, %d)", $item['reply_id'], $user_id);
			$res = $wpdb->query($query);
			$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $user_id);
			$listss = $wpdb->get_results($queryuname);
			$uname = $listss[0]->uname;
		
			$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $item['reply_id']);
			$lists = $wpdb->get_results($queryuid);
			$uid = $lists[0]->uid;
			
			$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = '%d'", $uid);
			$andriod_recipents = $wpdb->get_results($query, ARRAY_N);

			$andriod_device_ids = array();
			foreach ($andriod_recipents as $andriod_recipent) {
				array_push($andriod_device_ids, $andriod_recipent[0]);
			}
		
			$arrNotification = array();
		
			$content_text = $uname . ' Liked your Post';
			$arrNotification["title"] = 'SIPN';
			$arrNotification["body"] = $uname . ' Liked your Post';
			$arrNotification["sound"] = "default";
			$arrNotification["targetID"] = $item['reply_id'];
			$arrNotification["targetType"] = "postdetail";
			$arrNotification["type"] = 1;
			$arrNotification["targetContent"]["targetID"] = $item['reply_id'];
			$arrNotification["targetContent"]["targetType"] = "postdetail";

			
			include_once ABSPATH . 'wp-content/themes/SIPN/fcm.php';
			$fcm = new FCM();
			if ($uid != $user_id) {
				$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`) VALUES (%s, %d, %s, %d, %d, %s)", 'Like', $item['reply_id'], $content_text, $user_id, $uid, 'Likefromapp');
				$res = $wpdb->query($querystore);
				$noti_id = $wpdb->insert_id;
				$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Likefromapp", $noti_id);
			}

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $item['reply_id']);
			$list = $wpdb->get_results($query);

			return array("message" => "Record updated successfully.", "likes" => $list[0]->cnt, "reply_id" => $item['reply_id'], "is_like" => '1');
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid request.', array('status' => 403));
	}
}



function handle_notifications(WP_REST_Request $request)
{
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID, post_title, post_content  FROM wp_posts WHERE post_type='push-notification' AND post_status='publish' ORDER BY post_modified DESC limit 15");
	$notifications_list = $wpdb->get_results($query);
	//print_r($notifications_list);
	$notifications = array();
	foreach ($notifications_list as $noti) {
		$notification = array();
		$notification['title'] = $noti->post_title;
		$notification['message'] = $noti->post_content;
		$notification_image = get_the_post_thumbnail_url($noti->ID, 'medium');
		if ($notification_image) {
			$notification['image'] = $notification_image;
		} else {
			$notification['image'] = '';
		}

		if (get_post_meta($noti->ID, 'notification_type', true)) {
			$notification["targetContent"]["targetType"] = get_post_meta($noti->ID, 'notification_type', true);


			$meta_key = 'select_content_' . strtolower(str_replace(' ', '', get_post_meta($noti->ID, 'notification_type', true)));
			$notification["targetContent"]["targetID"] = get_post_meta($noti->ID, $meta_key, true);
		}

		array_push($notifications, $notification);
	}
	nocache_headers();
	return $notifications;
}

function handle_notifications_new(WP_REST_Request $request)
{
	global $wpdb;
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	//print_r($user_id);exit;
	$query = $wpdb->prepare("SELECT ID, post_title, post_content  FROM wp_posts WHERE post_type='push-notification' AND post_status='publish' ORDER BY post_modified DESC limit 15");
	$notifications_list = $wpdb->get_results($query);
	//print_r($notifications_list);
	$notifications = array();

	$query1231 = $wpdb->prepare("SELECT *  FROM notification_table WHERE notification_to=$user_id and status='0' order by created_at DESC ");
	$notificationslist = $wpdb->get_results($query1231);
	foreach ($notificationslist as $notic) {
		if ($notic->platform == 'Barlikefromapp' || $notic->platform == 'Barlikefromwebsite') {
			$targettype = "bar";
		} else if ($notic->platform == 'Commentfromwebsite' || $notic->platform == 'Commentfromapp' || $notic->platform == 'Likefromwebsite' || $notic->platform == 'Likefromapp') {
			$targettype = "postdetail";
		}
		$notification1 = array();
		$notification1['title'] = $notic->content_text;
		$notification1["targetID"] = $notic->content;
		$notification1["comment_by"] = $notic->notification_by;
		$notification1["targetType"] = $targettype;
		$notification1["targetContent"]["targetID"] = $notic->content;
		$notification1["targetContent"]["targetType"] = $targettype;
		array_push($notifications, $notification1);
	}

	foreach ($notifications_list as $noti) {
		$notification = array();
		$notification['title'] = $noti->post_title;
		$notification['message'] = $noti->post_content;
		$notification_image = get_the_post_thumbnail_url($noti->ID, 'medium');
		if ($notification_image) {
			$notification['image'] = $notification_image;
		} else {
			$notification['image'] = '';
		}

		if (get_post_meta($noti->ID, 'notification_type', true)) {
			$notification["targetContent"]["targetType"] = get_post_meta($noti->ID, 'notification_type', true);


			$meta_key = 'select_content_' . strtolower(str_replace(' ', '', get_post_meta($noti->ID, 'notification_type', true)));
			$notification["targetContent"]["targetID"] = get_post_meta($noti->ID, $meta_key, true);
		}

		array_push($notifications, $notification);

	}

	// $notification1 = array();
	// 	$notification1['title'] = 'Ragini Liked your Bar';
	// 	$notification1['message'] ='';

	nocache_headers();
	return $notifications;
}
function get_timeline_chatbubbles()
{
	global $wpdb;
	//40105,40101,40096,40093,35832  

	//$topic_ids = array(15587 => '#5c6d53', 2333 => '#dfd7a4', 2330 => '#787676', 2324 => '#b19e84');
	$topic_ids = array(40105 => '#5c6d52', 40101 => '#a09a75', 40096 => '#797777', 40093 => '#b29f85');

	$latest_chats = array();

	foreach ($topic_ids as $topic_ID => $color_code) {

		$topic_details = get_post($topic_ID);

		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'numberposts' => 1,
			'post_parent' => $topic_ID
		];

		$posts_replies = get_posts($args);

		$author_id = $posts_replies[0]->post_author;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		$timestamp = $posts_replies[0]->post_date;

		$time_ago = strtotime($timestamp);
		$current_time = time();
		$time_difference = $current_time - $time_ago;
		$seconds = $time_difference;

		$minutes = round($seconds / 60); // value 60 is seconds  
		$hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  

		$new_flag = 0;

		if ($hours <= 24) {
			$new_flag = 1;
		}

		$latest_chats[] = array('topic_id' => $topic_ID, 'topic' => $topic_details->post_title, 'reply' => $posts_replies[0]->post_content, 'reply_id' => $posts_replies[0]->ID, 'color_code' => $color_code, 'new_flag' => $new_flag, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'avatar' => $avatar);
	}
	return $latest_chats;

}

function handle_reply_report(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	if ($item['post_id'] && $item['post_url'] != '') {
		$to = 'social@sipnbourbon.com';
		$subject = 'Report post';
		$message = "Hello, <br>The following post is reported. please check the details below:<br>";
		$message .= "Post URL: " . $item['post_url'] . "<br>";
		$message .= "Reason: " . $item['reason'] . "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: raghu@bottlecapps.com');
		if (wp_mail($to, $subject, $message, $headers)) {
			return true;
		} else {
			return false;
		}
	}
}

function handle_timeline_chatbubbles(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	//40105,40101,40096,40093,35832

	//$topic_ids = array(15587 => '#5c6d53', 2333 => '#dfd7a4', 2330 => '#787676', 2324 => '#b19e84');
	$topic_ids = array(40105 => '#5c6d52', 40101 => '#a09a75', 40096 => '#797777', 40093 => '#b29f85');

	$latest_chats = array();

	foreach ($topic_ids as $topic_ID => $color_code) {

		$topic_details = get_post($topic_ID);

		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'numberposts' => 1,
			'post_parent' => $topic_ID
		];

		$posts_replies = get_posts($args);

		$author_id = $posts_replies[0]->post_author;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		$timestamp = $posts_replies[0]->post_date;

		$time_ago = strtotime($timestamp);
		$current_time = time();
		$time_difference = $current_time - $time_ago;
		$seconds = $time_difference;

		$minutes = round($seconds / 60); // value 60 is seconds  
		$hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  

		$new_flag = 0;

		if ($hours <= 24) {
			$new_flag = 1;
		}

		$latest_chats[] = array('topic_id' => $topic_ID, 'topic' => $topic_details->post_title, 'reply' => $posts_replies[0]->post_content, 'reply_id' => $posts_replies[0]->ID, 'color_code' => $color_code, 'new_flag' => $new_flag, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'avatar' => $avatar);
	}
	return $latest_chats;

}

function handle_get_profile_like_count(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	if ($item['profile_id'] != '') {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $item['profile_id']);
		$list = $wpdb->get_results($query);
		return array('likes' => $list[0]->cnt);
	} else {
		return new WP_Error('rest_forbidden', 'Invalid data.', array('status' => 403));
	}
}

function handle_profile_likes(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($item['profile_id'] != '' && $item['like'] != '' && $user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE user_id = '%d' AND profile_id = '%d'", $user_id, $item['profile_id']);
		$list = $wpdb->get_results($query);

		if ($list[0]->cnt >= 1) {
			if ($item['like'] == 0) {
				$query = $wpdb->prepare("DELETE FROM `wp_profile_likes` WHERE user_id = '%d' AND profile_id = '%d'", $user_id, $item['profile_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $item['profile_id']);
				$list = $wpdb->get_results($query);
				reward_points('remove', (int)16, $item['profile_id'], null, $user_id);
				return array("message" => "Record updated successfully.", "is_liked" => $item['like'], "likes" => $list[0]->cnt); //by sumeeth
			} else {
				return new WP_Error('rest_forbidden', 'Record already exists.', array('status' => 403));
			}
		} else {

			$query = $wpdb->prepare("INSERT INTO `wp_profile_likes` (`profile_id`, `user_id`) VALUES (%d, %d)", $item['profile_id'], $user_id);
			$res = $wpdb->query($query);

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $item['profile_id']);
			$list = $wpdb->get_results($query);

			$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $user_id);
			reward_points('add', (int)16, $item['profile_id'], null, $user_id);
			$listss = $wpdb->get_results($queryuname);
			$uname = $listss[0]->uname;
			//print_r($uname);exit;

			//print_r($uid);exit;
			$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = '%d'", $item['profile_id']);
			$andriod_recipents = $wpdb->get_results($query, ARRAY_N);

			$andriod_device_ids = array();
			foreach ($andriod_recipents as $andriod_recipent) {
				array_push($andriod_device_ids, $andriod_recipent[0]);
			}
			//print_r($andriod_device_ids);exit;
			$arrNotification = array();
			//$arrNotification["body"] = "1 like"; //for removing html tags
			//print_r(wp_encode_emoji($uname));	
			$content_text = $uname . ' Liked your Bar';
			$arrNotification["title"] = 'SIPN';
			$arrNotification["body"] = $uname . ' Liked your Bar';
			$arrNotification["sound"] = "default";
			$arrNotification["targetID"] = $item['profile_id'];
			$arrNotification["targetType"] = "bar";
			$arrNotification["type"] = 1;
			//$arrNotification["badge"] = 1;
			$arrNotification["targetContent"]["targetID"] = $item['profile_id'];
			$arrNotification["targetContent"]["targetType"] = "bar";
			//print_r($arrNotification);exit;
			// INCLUDE YOUR FCM FILE
			include_once ABSPATH . 'wp-content/themes/SIPN/fcm.php';
			$fcm = new FCM();
			if ($user_id != $item['profile_id']) {
				$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`) VALUES (%s, %d, %s, %d, %d, %s)", 'Like', $item['profile_id'], $content_text, $user_id, $item['profile_id'], 'Barlikefromapp');
				$res = $wpdb->query($querystore);
				$noti_id = $wpdb->insert_id;

				$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Barlikefromapp", $noti_id);
			}
			return array("message" => "Record updated successfully.", "is_liked" => $item['like'], "likes" => $list[0]->cnt); //added by sumeeth
			//return array("message"=>"Record updated successfully.", "likes"=>$list[0]->cnt);
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid request.', array('status' => 403));
	}
}

function is_product_found_in_wishlist($product_id, $user_id)
{
	global $wpdb;
	$user_details = get_user_meta($user_id);
	$existing_wishlist = $user_details['wishlist'][0];

	if ($existing_wishlist) {
		$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
		if (in_array($product_id, $existing_wishlist_arr)) {
			return '1';
		} else {
			return '0';
		}
	} else {
		return '0';
	}
}

function is_product_found_in_bar($product_id, $user_id)
{
	global $wpdb;
	$bar_id = get_bar_id_by_user_id($user_id);
	$query = $wpdb->prepare("SELECT COUNT(*) AS cnt FROM `wp_bar_shelves_products` p LEFT JOIN wp_bar_shelves s ON p.shelve_id=s.id LEFT JOIN wp_bar b ON b.id=s.bar_id WHERE b.id = '%s' AND p.product_id = '%s'", $bar_id, $product_id);
	$list = $wpdb->get_results($query);
	if ($list[0]->cnt > 0) {
		return '1';
	} else {
		return '0';
	}
}

function get_bar_id_by_user_id($user_id)
{
	global $wpdb;
	$cur_user = get_userdata($user_id);
	$query = $wpdb->prepare("SELECT id FROM `wp_bar` WHERE owner_email = '%s'", $cur_user->data->user_email);
	$list = $wpdb->get_results($query);
	if ($list[0]->id) {
		return $list[0]->id;
	} else {
		return 0;
	}
}

register_meta('user', 'phone_number', array(
	"type" => "string",
	"show_in_rest" => true,
	"single" => true,
));

register_meta('user', 'date_of_birth', array(
	"type" => "string",
	"show_in_rest" => true,
	"single" => true,
));

register_meta('user', 'address', array(
	"type" => "string",
	"show_in_rest" => true,
	"single" => true,
));


function timeline_time_ago($timestamp)
{

	//date_default_timezone_set("Asia/Kolkata");         
	$time_ago = strtotime($timestamp);
	$current_time = time();
	$time_difference = $current_time - $time_ago;
	$seconds = $time_difference;

	$minutes = round($seconds / 60); // value 60 is seconds  
	$hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
	$days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
	$weeks = round($seconds / 604800); // 7*24*60*60;  
	$months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
	$years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

	if ($seconds <= 60) {

		return "Just Now";

	} else if ($minutes <= 60) {

		if ($minutes == 1) {

			return "1 minute ago";

		} else {

			return "$minutes minutes ago";

		}

	} else if ($hours <= 24) {

		if ($hours == 1) {

			return "1 hour ago";

		} else {

			return "$hours hours ago";

		}

	} else if ($days <= 7) {

		if ($days == 1) {

			return "1 day ago";

		} else {

			return "$days days ago";

		}

	} else if ($weeks <= 4.3) {

		if ($weeks == 1) {

			return "1 week ago";

		} else {

			return "$weeks weeks ago";

		}

	} else if ($months <= 12) {

		if ($months == 1) {

			return "1 month ago";

		} else {

			return "$months months ago";

		}

	} else {

		if ($years == 1) {

			return "1 year ago";

		} else {

			return "$years years ago";

		}
	}
}


function handle_user_verifyemail(WP_REST_Request $request)
{
	global $wpdb;
	$myemail = $_GET['email'];


	$query = "UPDATE wp_users SET validate_email='0' WHERE user_email ='" . $myemail . "' OR ID = '".$myemail."'";
	$resverify = $wpdb->query($query);
	if ($resverify == '1') {
		$sql = "SELECT ID FROM wp_users WHERE user_email ='" . $myemail . "' OR ID = '".$myemail."'";

		$user_id = $wpdb->get_var($sql);
		if ($user_id) {
		    reward_points("add",(int)2, $user_id);
		}
		echo "Thankyou for verifying your email.Now you can sign in https://sipnbourbon.com/login ";
		//header("location:https://sipnbourbon.com");exit();
		header("location:https://sipnbourbon.com/thankyou");
		exit();
		//header("location:https://sipn.page.link/verify");exit();
	} else {
		echo "Email already verified please sign in https://sipnbourbon.com/login .";
		//header("location:https://sipnbourbon.com");exit();
		//header("location:https://sipn.page.link/verify");exit();
		header("location:https://sipnbourbon.com/thankyou");
		exit();
	}
	// return $my_profile;	
}

function handle_timeline_addpostwithmultpart(WP_REST_Request $request) {
    global $wpdb;
    $item = $_POST;

    // Default values
    $item['topic_id'] = 35832;
    $return_msg = "";
    
    if (empty($item['reply_to']) || $item['reply_to'] <= 0) {
        $item['reply_to'] = 35832;
    }

    // Extract additional data
    $pid = $item['product_id'] ?? '';
    $lid = $item['tagged_location'] ?? '';
    $from_device = $item['from_device'] ?? '';

    // Get current user info
    $cur_user = wp_get_current_user();
    $author_name = $cur_user->data->display_name;
    $author_id = $cur_user->data->ID;
    $user_details = get_user_meta($author_id);
    $pavatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0] ?? '', 'thumbnail');

    $error = [];
    $image_extensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
    $video_extensions = ['mp4', 'webm', 'mov', 'mkv', 'avi', 'flv'];
    $attid = [];

    if ($item['topic_id'] > 0 && $item['reply_to'] > 0) {
        if (isset($_FILES["files"]["tmp_name"]) && is_array($_FILES["files"]["tmp_name"])) {
            foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                $original_name = $_FILES["files"]["name"][$key];
                $file_size = $_FILES["files"]["size"][$key];
                $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                
                // Validate file
                if ($file_size > wp_max_upload_size()) {
                    $error[] = "$original_name (file too large)";
                    continue;
                }

                if (!in_array($ext, array_merge($image_extensions, $video_extensions))) {
                    $error[] = "$original_name (unsupported type)";
                    continue;
                }

                $filename = round(microtime(true)) . '_' . preg_replace('/\s+/', '', $original_name);
                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir["path"] . '/' . $filename;
                $upload_url = $upload_dir["url"] . '/' . $filename;

                // Move uploaded file
                if (!move_uploaded_file($tmp_name, $upload_path)) {
                    $error[] = "$original_name (upload failed)";
                    continue;
                }

                // Register attachment
                $mime_type = wp_check_filetype($filename)['type'];
                $attachment = [
                    'post_mime_type' => $mime_type,
                    'post_title' => sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'guid' => $upload_url
                ];

                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = wp_insert_attachment($attachment, $upload_path);
                $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_path);
                wp_update_attachment_metadata($attachment_id, $attach_data);

                $attid[] = $attachment_id;

                // Generate thumbnail for video files
                // Generate thumbnail for video files
				if (in_array($ext, $video_extensions)) {
				    $thumbnail_filename = pathinfo($filename, PATHINFO_FILENAME) . '_thumb.jpg';
				    $thumbnail_path = $upload_dir["path"] . '/' . $thumbnail_filename;

				    // Generate thumbnail using ffmpeg (try multiple points)
				    $ffmpeg = '/usr/bin/ffmpeg';
				    $success = false;
				    
				    // Try multiple points in case first frame is blank
				    $time_points = [1, 5, 10]; // seconds
				    
				    foreach ($time_points as $point) {
				        $cmd = "$ffmpeg -i " . escapeshellarg($upload_path) . 
				               " -ss 00:00:" . str_pad($point, 2, '0', STR_PAD_LEFT) . ".000 -vframes 1 -q:v 2 " . 
				               escapeshellarg($thumbnail_path) . " 2>&1";
				        exec($cmd, $output, $return_var);
				        
				        if ($return_var === 0 && file_exists($thumbnail_path)) {
				            $success = true;
				            break;
				        }
				    }

				    if ($success) {
				        $thumb_type = wp_check_filetype($thumbnail_filename)['type'];
				        $thumb_attachment = [
				            'post_mime_type' => $thumb_type,
				            'post_title' => sanitize_file_name($thumbnail_filename),
				            'post_content' => '',
				            'post_status' => 'inherit',
				            'guid' => $upload_dir["url"] . '/' . $thumbnail_filename
				        ];

				        $thumb_id = wp_insert_attachment($thumb_attachment, $thumbnail_path);
				        $thumb_data = wp_generate_attachment_metadata($thumb_id, $thumbnail_path);
				        wp_update_attachment_metadata($thumb_id, $thumb_data);

				        // Add thumbnail ID first so it appears first
				        array_unshift($attid, $thumb_id);
				    } else {
				        $error[] = "$original_name (thumbnail generation failed)";
				    }
				}
            }
        }

        // Create the reply post
        $reply_data = [
            'reply_to' => $item['reply_to'],
            'post_parent' => $item['topic_id'],
            'post_content' => $item['reply'],
            'post_type' => 'reply',
            'post_author' => $author_id
        ];

        $reply_meta = [
            'forum_id' => '0',
            'topic_id' => $item['topic_id'],
            'product_id' => $pid,
            'tagged_location' => $lid,
            'from_device' => $from_device,
            'reply_to' => $item['reply_to']
        ];

        $new_reply_id = bbp_insert_reply($reply_data, $reply_meta);

        if ($new_reply_id) {
            // Handle rewards
            $return_msg = reward_points("add", (int)6, $author_id, $new_reply_id);
            update_rewards();

            // Store attachments
            if (!empty($attid)) {
                $cleaned = implode(',', $attid);
                update_post_meta($new_reply_id, '_thumbnail_id', $cleaned);
            }

            return [
                "author" => $author_name,
                "author_id" => $author_id,
                "avatar" => $pavatar,
                "reply_id" => $new_reply_id,
                "product_id" => $pid,
                "tagged_location" => $lid,
                "message" => "Your post is submitted successfully.",
                "reward_message" => $return_msg
            ];
        } else {
            return new WP_Error('rest_forbidden', 'Your post was not published.', ['status' => 403]);
        }
    } else {
        return new WP_Error('rest_forbidden', 'Invalid topic or reply ID.', ['status' => 403]);
    }
}
function handle_timeline_addpostwithmultpart_Old(WP_REST_Request $request)
{
	global $wpdb;
	$item = $_POST;

	$item['topic_id'] = 35832;
	$return_msg = "";
	if ($item['reply_to'] == '' || $item['reply_to'] <= 0) {
		$item['reply_to'] = 35832;
	}
	//for product
	if (isset($item['product_id']) == '') {
		$pid = '';
	} else {
		$pid = $item['product_id'];
	}
	//for location
	if (isset($item['tagged_location']) == '') {
		$lid = '';
	} else {
		$lid = $item['tagged_location'];
	}
	$from_device = $item['from_device'];

	$cur_user = wp_get_current_user();
	//added by sumeeth
	$author_name = $cur_user->data->display_name;
	$author_id = $cur_user->data->ID;
	//added by sumeeth
	$user_details = get_user_meta($cur_user->data->ID);
	$pavatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
	//$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format

	$error = array();
	$extension = array("jpeg", "jpg", "png", "gif", "webp");

	if ($item['topic_id'] > 0 && $item['reply_to'] > 0) { //by sumeeth

		if (isset($_FILES["files"]["tmp_name"]) && $_FILES["files"]["tmp_name"] != '') {

			foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
				$k = round(microtime(true));

				$file_name = $k . $_FILES["files"]["name"][$key];
				// $file_names[]=$k.$_FILES["files"]["name"][$key];
				$file_tmp = $_FILES["files"]["tmp_name"][$key];
				$ext = 'webp';
				$info = pathinfo($file_name);
				$newFileName3 = str_replace(' ', '', $info['filename']);
				$file_names[] = $newFileName3 . "." . $ext;
				// $ext=pathinfo($file_name,PATHINFO_EXTENSION);
				$uploaddir = wp_upload_dir();
				if (in_array($ext, $extension)) {
					$newFileName2 = $info['filename'] . "." . $ext;
					$newFileName1 = str_replace(' ', '', $newFileName2);
					if (!file_exists($uploaddir["path"] . $txtGalleryName . "/" . $file_name)) {
						// Compress size and upload image 
						$compressedImage = compressImage($_FILES["files"]["tmp_name"][$key], $uploaddir["path"] . $txtGalleryName . "/" . $newFileName1, 75);
						// print_r($compressedImage);exit;
						// move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],$uploaddir["path"].$txtGalleryName."/".$newFileName1);
					} else {
						$filename = basename($file_name, $ext);
						$newFileName2 = $filename . time() . "." . $ext;
						$newFileName = str_replace(' ', '', $newFileName2);
						$compressedImage = compressImage($_FILES["files"]["tmp_name"][$key], $uploaddir["path"] . $txtGalleryName . "/" . $newFileName, 75);
					}
				} else {
					array_push($error, "$file_name, ");
				}
			}

			foreach ($file_names as $key => $value) {
				$wp_filetype = wp_check_filetype(basename($value), null);
				$uploadfile = $uploaddir["path"] . '/' . basename($value);


				if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/webp' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
					$attachment = array(
						"post_mime_type" => $wp_filetype["type"],
						"post_title" => preg_replace("/\.[^.]+$/", "", basename($file_name)),
						"post_content" => "",
						"post_status" => "inherit",
						'guid' => $uploadfile,
					);
					//print_r($attachment);exit;
					require_once(ABSPATH . '/wp-load.php');
					require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
					require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
					$attachment_id = wp_insert_attachment($attachment, $uploadfile);
					$attid[] = $attachment_id;
					$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
					wp_update_attachment_metadata($attachment_id, $attach_data);
				}

			}



		}


		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'tagged_location' => $lid, 'from_device' => $from_device, 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		// added by salman for reward points

		

		$new_reply_id = bbp_insert_reply($reply_data, $reply_meta);
		if ($new_reply_id) {
			$return_msg = reward_points("add",(int)6,$author_id, $new_reply_id);
			update_rewards();
			if ($attid) {
				$a = json_encode($attid);
				if (!empty($a)) {
					$b = trim($a, "[ ]");
				}

				add_post_meta($new_reply_id, '_thumbnail_id', $b);
			}
			//return array("message"=>"your post is submitted successfully.");
			//added  by sumeeth
			return array("author" => $author_name, "author_id" => $author_id, "avatar" => $pavatar, "reply_id" => $new_reply_id, "product_id" => $pid, "tagged_location" => $lid, "message" => "your post is submitted successfully.", "reward_message" => $return_msg);
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}


}



function compressImage($source, $destination, $quality)
{
	// Get image info 
	$imgInfo = getimagesize($source);
	$mime = $imgInfo['mime'];
	//print_r($mime);exit;
	// Create a new image from file 
	$newImageWidth = 700;
	$newImageHeight = 700;
	$width = getWidth($source);
	$height = getHeight($source);
	//print_r($width);exit;
	//Scale the image if it is greater than the width set above
	// if ($width > $max_width){
	// 	$scale = $max_width/$width;
	// 	print_r($scale);exit;
	// 	$uploaded = resizeImage($uploaddir["path"].$txtGalleryName."/".$newFileName1,$width,$height,$scale, $ext);
	// } else {
	// 	$scale = 1;
	// 	$uploaded = resizeImage($uploaddir["path"].$txtGalleryName."/".$newFileName1,$width,$height,$scale, $ext);
	// }	

	$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
	switch ($mime) {
		case 'image/jpeg':
			$image = imagecreatefromjpeg($source);

			//imagejpeg($image, $destination, $quality);
			break;
		case 'image/png':
			$image = imagecreatefrompng($source);
			//imagepng($image, $destination, $quality);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($source);
			//imagegif($image, $destination, $quality);
			break;
		case 'image/webp':
			$image = imagecreatefromwebp($source);
			//imagewebp($image, $destination, $quality);
			break;
		case 'image/jpg':
			$image = imagecreatefromjpeg($source);
			//imagejpg($image, $destination, $quality);
			break;
		default:
			$image = false;
			break;
	}


	imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
	//imagejpeg($newImage,$image,90);
	imagewebp($newImage, $destination, $quality);
	// print_r($new_image);exit;

	// Return compressed image 
	return $destination;
}

/*  Function to get image height. */
function getHeight($image)
{
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}
/* Function to get image width */
function getWidth($image)
{
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}

function handle_timeline_editpostwithmultpart(WP_REST_Request $request)
{	
	global $wpdb;

	$item = $_POST;

	$item['topic_id'] = 35832;

	if ($item['reply_to'] == '' || $item['reply_to'] <= 0) {
		$item['reply_to'] = 35832;
	}
	if (isset($item['product_id']) == '') {
		$pid = 'null';
	} else {
		$pid = $item['product_id'];
	}
	if (isset($item['tagged_location']) == '') {
		$lid = 'null';
	} else {
		$lid = $item['tagged_location'];
	}
	$from_device = $item['from_device'];

	$cur_user = wp_get_current_user();
	
	$user_details = get_user_meta($cur_user->data->ID);
	$error = array();
	$extension = array("jpeg", "jpg", "png", "gif", "webp");
	$error = [];
    $image_extensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
    $video_extensions = ['mp4', 'webm', 'mov', 'mkv', 'avi', 'flv'];
    $attid = [];
	if ($item['reply_id'] > 0) { //by sumeeth
		if (isset($_FILES["files"]["tmp_name"]) && $_FILES["files"]["tmp_name"] != '') {

			foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                $original_name = $_FILES["files"]["name"][$key];
                $file_size = $_FILES["files"]["size"][$key];
                $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                
                // Validate file
                if ($file_size > wp_max_upload_size()) {
                    $error[] = "$original_name (file too large)";
                    continue;
                }

                if (!in_array($ext, array_merge($image_extensions, $video_extensions))) {
                    $error[] = "$original_name (unsupported type)";
                    continue;
                }

                $filename = round(microtime(true)) . '_' . preg_replace('/\s+/', '', $original_name);
                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir["path"] . '/' . $filename;
                $upload_url = $upload_dir["url"] . '/' . $filename;

                // Move uploaded file
                if (!move_uploaded_file($tmp_name, $upload_path)) {
                    $error[] = "$original_name (upload failed)";
                    continue;
                }

                // Register attachment
                $mime_type = wp_check_filetype($filename)['type'];
                $attachment = [
                    'post_mime_type' => $mime_type,
                    'post_title' => sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'guid' => $upload_url
                ];

                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = wp_insert_attachment($attachment, $upload_path);
                $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_path);
                wp_update_attachment_metadata($attachment_id, $attach_data);

                $attid[] = $attachment_id;

                // Generate thumbnail for video files
                // Generate thumbnail for video files
				if (in_array($ext, $video_extensions)) {
				    $thumbnail_filename = pathinfo($filename, PATHINFO_FILENAME) . '_thumb.jpg';
				    $thumbnail_path = $upload_dir["path"] . '/' . $thumbnail_filename;

				    // Generate thumbnail using ffmpeg (try multiple points)
				    $ffmpeg = '/usr/bin/ffmpeg';
				    $success = false;
				    
				    // Try multiple points in case first frame is blank
				    $time_points = [1, 5, 10]; // seconds
				    
				    foreach ($time_points as $point) {
				        $cmd = "$ffmpeg -i " . escapeshellarg($upload_path) . 
				               " -ss 00:00:" . str_pad($point, 2, '0', STR_PAD_LEFT) . ".000 -vframes 1 -q:v 2 " . 
				               escapeshellarg($thumbnail_path) . " 2>&1";
				        exec($cmd, $output, $return_var);
				        
				        if ($return_var === 0 && file_exists($thumbnail_path)) {
				            $success = true;
				            break;
				        }
				    }

				    if ($success) {
				        $thumb_type = wp_check_filetype($thumbnail_filename)['type'];
				        $thumb_attachment = [
				            'post_mime_type' => $thumb_type,
				            'post_title' => sanitize_file_name($thumbnail_filename),
				            'post_content' => '',
				            'post_status' => 'inherit',
				            'guid' => $upload_dir["url"] . '/' . $thumbnail_filename
				        ];

				        $thumb_id = wp_insert_attachment($thumb_attachment, $thumbnail_path);
				        $thumb_data = wp_generate_attachment_metadata($thumb_id, $thumbnail_path);
				        wp_update_attachment_metadata($thumb_id, $thumb_data);

				        // Add thumbnail ID first so it appears first
				        array_unshift($attid, $thumb_id);
				    } else {
				        $error[] = "$original_name (thumbnail generation failed)";
				    }
				}
            }



		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {

		}

		if ($item['delete_image'] == 1) {

			update_post_meta($item['reply_id'], '_thumbnail_id', '');
		}

		//old code

		//for updating product 
		$reply_id = $item['reply_id'];
		$querypid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$pid' WHERE post_id =$reply_id AND meta_key='_bbp_product_id'");
		$res123 = $wpdb->query($querypid);

		//for updating tagged location
		$querylid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$lid' WHERE post_id =$reply_id AND meta_key='_bbp_tagged_location'");
		$res1234 = $wpdb->query($querylid);

		$queryfrom = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$from_device' WHERE post_id =$reply_id AND meta_key='_bbp_from_device'");
		//print_r($queryfrom);exit;
		$res12345 = $wpdb->query($queryfrom);



		$reply_content = $item['reply'];
		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'tagged_location' => $lid, 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		if (!current_user_can('edit_reply', $reply_id)) {
			return new WP_Error('rest_forbidden', 'You do not have permission to edit that reply.', array('status' => 403));
		}

		/** Reply Topic ***********************************************************/

		$topic_id = bbp_get_reply_topic_id($reply_id);

		/** Topic Forum ***********************************************************/

		//$forum_id = bbp_get_topic_forum_id( $topic_id );

		$reply_data = apply_filters('bbp_edit_reply_pre_insert', array(
			'ID' => $reply_id,
			'post_content' => $reply_content,
			'post_parent' => $topic_id,
			'post_author' => $reply_data['post_author'],
			'post_type' => 'reply'
		));

		$reply_id = wp_update_post($reply_data);
		if (wp_update_post($reply_data)) {

			if ($attid) {
				$a = json_encode($attid);
				if (!empty($a)) {
					$b = trim($a, "[ ]");
				}
				delete_post_thumbnail($reply_id);
				add_post_meta($reply_id, '_thumbnail_id', $b);
			}



			//bbp_update_reply( $reply_id, $reply_meta['topic_id'], '0', array(), $reply_data['post_author'], true, $reply_meta['reply_to'] );
			//return array("message"=>"your post is updated successfully.");
			//added by sumeeth
			return array("reply_id" => $reply_id, "product_id" => $pid, "tagged_location" => $lid, "message" => "your post is updated successfully.");

		} else {
			return new WP_Error('rest_forbidden', 'Your post is not updated.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );

}

function handle_timeline_editpostwithmultpart_Old(WP_REST_Request $request)
{
	global $wpdb;
	$item = $_POST;

	$item['topic_id'] = 35832;

	if ($item['reply_to'] == '' || $item['reply_to'] <= 0) {
		$item['reply_to'] = 35832;
	}
	if (isset($item['product_id']) == '') {
		$pid = 'null';
	} else {
		$pid = $item['product_id'];
	}
	if (isset($item['tagged_location']) == '') {
		$lid = 'null';
	} else {
		$lid = $item['tagged_location'];
	}
	$from_device = $item['from_device'];

	$cur_user = wp_get_current_user();

	$user_details = get_user_meta($cur_user->data->ID);
	$error = array();
	$extension = array("jpeg", "jpg", "png", "gif", "webp");
	if ($item['reply_id'] > 0) { //by sumeeth
		if (isset($_FILES["files"]["tmp_name"]) && $_FILES["files"]["tmp_name"] != '') {

			foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
				$k = round(microtime(true));

				$file_name = $k . $_FILES["files"]["name"][$key];
				// $file_names[]=$k.$_FILES["files"]["name"][$key];
				$file_tmp = $_FILES["files"]["tmp_name"][$key];
				$ext = 'webp';
				$info = pathinfo($file_name);
				$newFileName3 = str_replace(' ', '', $info['filename']);
				$file_names[] = $newFileName3 . "." . $ext;
				// $ext=pathinfo($file_name,PATHINFO_EXTENSION);
				$uploaddir = wp_upload_dir();
				if (in_array($ext, $extension)) {
					$newFileName2 = $info['filename'] . "." . $ext;
					$newFileName1 = str_replace(' ', '', $newFileName2);

					if (!file_exists($uploaddir["path"] . $txtGalleryName . "/" . $file_name)) {
						$compressedImage = compressImage($_FILES["files"]["tmp_name"][$key], $uploaddir["path"] . $txtGalleryName . "/" . $newFileName1, 75);
						// move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],$uploaddir["path"].$txtGalleryName."/".$newFileName1);
					} else {
						$filename = basename($file_name, $ext);
						// move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],$uploaddir["path"].$txtGalleryName."/".$newFileName);
						$newFileName2 = $filename . time() . "." . $ext;
						$newFileName = str_replace(' ', '', $newFileName2);
						$compressedImage = compressImage($_FILES["files"]["tmp_name"][$key], $uploaddir["path"] . $txtGalleryName . "/" . $newFileName, 75);
					}
				} else {
					array_push($error, "$file_name, ");
				}
			}

			foreach ($file_names as $key => $value) {
				$wp_filetype = wp_check_filetype(basename($value), null);
				$uploadfile = $uploaddir["path"] . '/' . basename($value);


				if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/webp' || $wp_filetype["type"] == 'image/webp' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
					$attachment = array(
						"post_mime_type" => $wp_filetype["type"],
						"post_title" => preg_replace("/\.[^.]+$/", "", basename($file_name)),
						"post_content" => "",
						"post_status" => "inherit",
						'guid' => $uploadfile,
					);
					require_once(ABSPATH . '/wp-load.php');
					require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
					require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
					$attachment_id = wp_insert_attachment($attachment, $uploadfile);
					$attid[] = $attachment_id;
					$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
					wp_update_attachment_metadata($attachment_id, $attach_data);
				}

			}



		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {

		}

		if ($item['delete_image'] == 1) {

			update_post_meta($item['reply_id'], '_thumbnail_id', '');
		}

		//old code

		//for updating product 
		$reply_id = $item['reply_id'];
		$querypid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$pid' WHERE post_id =$reply_id AND meta_key='_bbp_product_id'");
		$res123 = $wpdb->query($querypid);

		//for updating tagged location
		$querylid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$lid' WHERE post_id =$reply_id AND meta_key='_bbp_tagged_location'");
		$res1234 = $wpdb->query($querylid);

		$queryfrom = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$from_device' WHERE post_id =$reply_id AND meta_key='_bbp_from_device'");
		//print_r($queryfrom);exit;
		$res12345 = $wpdb->query($queryfrom);



		$reply_content = $item['reply'];
		$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
		$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'tagged_location' => $lid, 'reply_to' => $item['reply_to']);
		//get the topic IP so we can reset it later
		$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

		if (!current_user_can('edit_reply', $reply_id)) {
			return new WP_Error('rest_forbidden', 'You do not have permission to edit that reply.', array('status' => 403));
		}

		/** Reply Topic ***********************************************************/

		$topic_id = bbp_get_reply_topic_id($reply_id);

		/** Topic Forum ***********************************************************/

		//$forum_id = bbp_get_topic_forum_id( $topic_id );

		$reply_data = apply_filters('bbp_edit_reply_pre_insert', array(
			'ID' => $reply_id,
			'post_content' => $reply_content,
			'post_parent' => $topic_id,
			'post_author' => $reply_data['post_author'],
			'post_type' => 'reply'
		));

		$reply_id = wp_update_post($reply_data);
		if (wp_update_post($reply_data)) {

			if ($attid) {
				$a = json_encode($attid);
				if (!empty($a)) {
					$b = trim($a, "[ ]");
				}
				delete_post_thumbnail($reply_id);
				add_post_meta($reply_id, '_thumbnail_id', $b);
			}



			//bbp_update_reply( $reply_id, $reply_meta['topic_id'], '0', array(), $reply_data['post_author'], true, $reply_meta['reply_to'] );
			//return array("message"=>"your post is updated successfully.");
			//added by sumeeth
			return array("reply_id" => $reply_id, "product_id" => $pid, "tagged_location" => $lid, "message" => "your post is updated successfully.");

		} else {
			return new WP_Error('rest_forbidden', 'Your post is not updated.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );

}



function handle_timeline_deleteprofile()
{
	global $wpdb;


$cur_user = wp_get_current_user();
$id = $cur_user->ID; 
$email = $cur_user->user_email;

if ( ! $cur_user->exists() ) {
    return new WP_Error('rest_forbidden', 'User  not logged in', array('status' => 403));
}

$delete_user = $wpdb->query($wpdb->prepare("DELETE FROM `wp_users` WHERE ID = %d", $id));
$delete_usermeta = $wpdb->query($wpdb->prepare("DELETE FROM `wp_usermeta` WHERE user_id = %d", $id));
$delete_posts = $wpdb->query($wpdb->prepare("DELETE FROM `wp_posts` WHERE post_author = %d", $id));
$delete_bar = $wpdb->query($wpdb->prepare("DELETE FROM `wp_bar` WHERE owner_email = %s", $email));
$delete_reward = $wpdb->query($wpdb->prepare("DELETE FROM `users_rewards` WHERE user_id = %d", $id));
$delete_reward_history = $wpdb->query($wpdb->prepare("DELETE FROM `user_reward_history` WHERE user_id = %d", $id));
$delete_user = $wpdb->query($wpdb->prepare("DELETE FROM `wp_users` WHERE ID = %d", $id));
$delete_usermeta = $wpdb->query($wpdb->prepare("DELETE FROM `wp_usermeta` WHERE user_id = %d", $id));


if ($wpdb->last_error) {
    return new WP_Error('rest_forbidden', 'Profile not deleted: ' . $wpdb->last_error, array('status' => 403));
} else {
    return array(
        "message" => "Hey There! Your Profile has been Successfully Removed From SIPN Bourbon. It would take 5-10 Minutes To Clear Your Data From Our Systems. Please Sign-Up After Some Time.",
        "status" => 1
    );
}

}



function handle_reply_reportbar(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	if ($item['bar_link'] && $item['bar_link'] != '') {
		$to = 'social@sipnbourbon.com';
		//$to = 'sumeeth@bottlecapps.com';
		$subject = 'Report bar';
		$message = "Hello, <br>The following bar is reported. please check the details below:<br>";
		$message .= "Bar Name: " . $item['bar_name'] . "<br>";
		$message .= "Bar Link: " . $item['bar_link'] . "<br>";
		$message .= "Reason: " . $item['reason'] . "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: raghu@bottlecapps.com');
		if (wp_mail($to, $subject, $message, $headers)) {
			return array("message" => "Bar reported successfully.", "status" => 1);
		} else {
			return new WP_Error('rest_forbidden', 'Bar not reported', array('status' => 403));
		}
	}
}

function handle_reply_reportforums(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	if ($item['topic_title'] && $item['topic_title'] != '') {
		$to = 'social@sipnbourbon.com';
		//$to = 'raghu@bottlecapps.com';
		$subject = 'Report forum';
		$message = "Hello, <br>The following forum reply is reported. please check the details below:<br>";
		$message .= "Forum Topic: " . $item['topic_title'] . "<br>";
		$message .= "Forum Reply: " . $item['reply'] . "<br>";
		$message .= "Forum Author: " . $item['author'] . "<br>";
		$message .= "Forum URL: " . $item['forum_url'] . "<br>";
		$message .= "Reason: " . $item['reason'] . "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: raghu@bottlecapps.com');
		if (wp_mail($to, $subject, $message, $headers)) {
			return array("message" => "Forum reported successfully.", "status" => 1);
		} else {
			return new WP_Error('rest_forbidden', 'Forum not reported', array('status' => 403));
		}
	}
}


function handle_timeline_deleteprofilewithuserid(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$id = $item['user_id'];
	$mybaremail = $item['user_email'];
	//print_r($id);
	// $query = $wpdb->prepare("SELECT user_email FROM `wp_users` WHERE ID = $id");
	//    $list = $wpdb->get_results($query);
	// $email1=$list[0]->user_email;
	// //print_r($email);exit;
	// //print_r($email);exit;
	// //print_r($id);
	// if($email1 == '' && $email1 == null){
	// 	return array("message"=>"Email not found", "status"=>0);
	// }
	//print_r($email);exit;
	$queryuid = $wpdb->prepare("DELETE FROM 'wp_users' WHERE ID = $id");
	//print_r($queryuid);exit;
	$res = $wpdb->query($queryuid);
	//echo "string";
	//print_r($res);
	// $queryumid= $wpdb->prepare("DELETE FROM `wp_usermeta` WHERE user_id = $id");
	// $res1 = $wpdb->query($queryumid);
	// $querypostid= $wpdb->prepare("DELETE FROM `wp_posts` WHERE post_author = $id");
	// $res2 = $wpdb->query($querypostid);
	// $querybarid= $wpdb->prepare("DELETE FROM `wp_bar` WHERE owner_email = '$mybaremail'");
	// //print_r($querybarid);exit;
	// $res30 = $wpdb->query($querybarid);
	//print_r($res30);
	if ($res) {
		return array("message" => "Hey there! Your profile has been successfully removed from SIPN Bourbon. It will take 5-10 minutes to clear your data from our systems. Please sign up after some time.", "status" => 1);
		//return array("message"=>"Profile deleted successfully.", "status"=>1);
	} else {
		return new WP_Error('rest_forbidden', 'Profile not deleted', array('status' => 403));
	}

}


function ajaxaddlocationbuynow(WP_REST_Request $request)
{
	global $wpdb;
	$keyword = $_POST['Keyword'];
	$key = $_POST['Key'];
	$ins1_query1 = $wpdb->prepare("INSERT INTO `wp_search_buynowlist` (`id`, `keyword`, `product_name`) VALUES ('',  '$keyword','$key')");
	//echo $ins1_query1;exit;
	$res1_query1 = $wpdb->query($ins1_query1);
	echo $res1_query1;
}


//for collections
function handle_get_collections(WP_REST_Request $request)
{
	global $wpdb;
	$day = date('Ymd');
	//print_r($day);exit;
	//$query = $wpdb->prepare("SELECT *  FROM wp_collections WHERE collection_start_date >=$day AND collection_end_date >=$day");
	//for future collections
	$query = $wpdb->prepare("SELECT *  FROM wp_collections WHERE  collection_end_date >=$day order by collection_id DESC");
	$notifications_list = $wpdb->get_results($query);
	//	print_r($notifications_list);exit;
	$notifications = array();
	//$notifications['count']=count($notifications_list);
	$k = 0;
	foreach ($notifications_list as $not) {

		$notifications[$k]['collection_id'] = $not->collection_id;
		$notifications[$k]['collection_name'] = $not->collection_name;
		$notifications[$k]['collection_long_description'] = $not->collection_long_description;
		$notifications[$k]['collection_short_description'] = $not->collection_short_description;
		$notifications[$k]['collection_image'] = $not->collection_image;
		$notifications[$k]['collection_products'] = $not->collection_products;
		$notifications[$k]['collection_start_date'] = $not->collection_start_date;
		$notifications[$k]['collection_end_date'] = $not->collection_end_date;
		$notifications[$k]['collection_orgname'] = $not->collection_orgname;

		$author_id = $not->author;
		//$author_details = get_user_by('id', $author_id);
		//$author_name = $author_details->data->display_name;
		$notifications[$k]['author'] = $author_id;
		$notifications[$k]['location'] = $not->location;
		$k++;
	}
	return $notifications;

}


function handle_getcollectiondetails(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$id = $item['collection_id'];
	//print_r($id);
	$query = $wpdb->prepare("SELECT *  FROM wp_collections where collection_id=$id");
	$notifications_list = $wpdb->get_results($query);
	//print_r($notifications_list);exit;
	$notifications = array();
	//$notifications['count']=count($notifications_list);
	$k = 0;
	foreach ($notifications_list as $not) {
		$c = strip_tags($not->collection_long_description);
		$str = str_replace(" ", "-", $not->collection_orgname);
		$notifications[$k]['collection_name'] = $not->collection_name;
		$notifications[$k]['collection_long_description'] = nl2br($c);
		$notifications[$k]['collection_short_description'] = $not->collection_short_description;
		$notifications[$k]['collection_image'] = $not->collection_image;
		$notifications[$k]['collection_products'] = $not->collection_products;
		$notifications[$k]['collection_start_date'] = $not->collection_start_date;
		$notifications[$k]['collection_end_date'] = $not->collection_end_date;
		$notifications[$k]['collection_share_link'] = site_url() . '/bourbon-collection/' . $str;
		$notifications[$k]['collection_orgname'] = $not->collection_orgname;
		$author_id = $not->author;
		//$author_details = get_user_by('id', $author_id);
		//$author_name = $author_details->data->display_name;
		$notifications[$k]['author'] = $author_id;
		$notifications[$k]['location'] = $not->location;

		$c = $not->collection_products;
		$cv = explode(',', $c);
		$prod = array();
		foreach ($cv as $key => $value) {
			if ($value != '') {
				$the_product = wc_get_product($value);
				//print_r($the_product);exit;
				$img = get_the_post_thumbnail_url($value, 'medium');
				if ($img == '') {
					$img = get_stylesheet_directory_uri() . "/assets/images/default-bottle.jpg";
				}
				$prod[$key]['product_id'] = $value;
				$prod[$key]['product_image'] = $img;
				$prod[$key]['product_link'] = get_permalink($value);
				$prod[$key]['product_name'] = $the_product->name;
				$p = $the_product->price;
				if ($p != 'null') {
					$p = $p;
				} else {
					$p = '';
				}
				$prod[$key]['product_price'] = $p;
			}
		}
		$notifications[$k]['product_details'] = $prod;
		$k++;
	}
	return $notifications;

}


function handle_getcontactdropdownlist()
{
	$k = 0;
	$all_vids = array();
	$all_lists = array("Account Related", "Order Related", "Website or App Feedback", "Supplier Partnership", "Retailer Partnership", "Advertising", "Other", "Delete Account Request");
	foreach ($all_lists as $val) {


		$all_vids[$k]['reason'] = $val;
		$k++;
	}
	return $all_vids;
}

function handle_user_sendemailsignup($input)
{
	
	global $wpdb;

	$too = $input;
	$a = explode(',', $too);
	//print_r($a);exit;
	//print_r($item['post_url']);exit;
	$subject = 'Welcome to SIPN';
	foreach ($a as $key => $value) {
		$b = $key;
		$to = $value;
		// $to = 'randhir.techmaticsys@gmail;
		$b++;


		$message = '<body style="font-family:"Helvetica"; padding: 0; margin: 0;">
    <div style="width: 599px; margin:0 auto;">
        <div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;" >
            <div style="text-align: center; vertical-align:middle; padding-top:22px;"><img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png"></div>
        </div>
        <div style="background-color: #fff; text-align:center; padding: 13px;">
            <h1 style="color: #bca665; font-size:20px; font-weight:600;font-family:"Helvetica"; margin: 0; padding: 0;">
                Welcome to SIPN
            </h1>
            <p style="color: #020001; font-size:14px; margin: 0;">A bourbon community for the curious to the connoisseur.</p>
        </div>
        <div style="background: #2d2d2c; padding:15px; text-align: center;">
            <h2 style="color: #fff; font-size:20px; font-weight:18px; padding:0; margin:0;">Please Verify Your<br>
                Email Address</h2>
            <p style="color: #fff; font-size:14px;">To finish signing up,<br> please confirm your email.</p>  
           
<a href="https://redirect.sipnbourbon.com/verifyemail?email=' . $to . '" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:16px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Verify Email</a>

        </div>
        <div style="background-color: #fff; padding: 15px;">
            <div style="width:30%; float:left; display: inline-block;"><img style="max-width: 80%" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/img1.jpg">
                <div class="clearfix"></div>
            </div>
            <div style="float:right; width: 70%;">
                <ul style="padding: 0; margin:0px 0 15px 15px; font-size:14px; color:#020001; line-height:22px; float: left; display: inline-block;">
                    <h3 style="padding: 0; margin:0 0 5px 0; color: #bca665; width: 100%; font-size: 17px !important; float: left;">Once your are verified Sip back. Stay a while.<br>
                        Welcome to your happy hour.</h3>
                    <li>Find out about upcoming bourbon social events</li>
                    <li>Be the first to know about new bourbon releases</li>
                    <li>Connect with other bourbon connoisseurs</li>
                    <li>Find and shop your favorite bourbon</li>
                    <li>Read tasting notes and reviews</li>
                </ul> 
                <div class="clearfix"></div>   
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="background: #2d2d2c; padding:10px; float:left; width: 100%; text-align: center;">
            <!-- <div style="background-color: #fff; border-radius:50px; height:15px; width:15px; margin: 0 auto;"></div> -->
                    <div style="text-align: center; vertical-align:middle; padding-top:0px;"><img style="width: 10%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-footer.png"></div>

                        <span style="color:white; margin-top: 5px; text-align: center; display: block; font-size: 14px;"> Please click on <a href="#" style="color: #bca665; text-decoration: none;" >Unsubscribe</a> to stop receiving emails from SIPN. </span>
                    
                        <div class="clearfix"></div>
                    <ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block; float: left; ">
                        <li style="margin-right:2px; display: inline-block;"><a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;"></a></li>
                        <li style="margin-right:2px; display: inline-block;"><a href="https://www.facebook.com/sipnbourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;" ></a></li>
                        <li style="display: inline-block;"><a href="https://twitter.com/sipnbourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;"></a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
                        <li style="margin-right:3px; display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a></li>
                        <li style=" display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a></li>
                    </ul>
            <div>
        </div>
</div>
    </div>

</body>';
		//$message .= "Post URL: ".$item['post_url']. "<br>";
		//$message .= "Reason: ".$item['reason']. "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if (wp_mail($to, $subject, $message, $headers)) {

		} else {
			return false;
		} 
	}
	//print_r($b);
}

function handle_user_registerresendemail(WP_REST_Request $request)
{
	global $wpdb;

	$too = $_POST['email'];
	$a = explode(',', $too);
	//print_r($a);exit;
	//print_r($item['post_url']);exit;
	$subject = 'Welcome to SIPN';
	foreach ($a as $key => $value) {
		$b = $key;
		$to = $value;
		$b++;


		$message = '<body style="font-family:"Helvetica"; padding: 0; margin: 0;">
    <div style="width: 599px; margin:0 auto;">
        <div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;" >
            <div style="text-align: center; vertical-align:middle; padding-top:22px;"><img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png"></div>
        </div>
        <div style="background-color: #fff; text-align:center; padding: 13px;">
            <h1 style="color: #bca665; font-size:20px; font-weight:600;font-family:"Helvetica"; margin: 0; padding: 0;">
                Welcome to SIPN
            </h1>
            <p style="color: #020001; font-size:14px; margin: 0;">A bourbon community for the curious to the connoisseur.</p>
        </div>
        <div style="background: #2d2d2c; padding:15px; text-align: center;">
            <h2 style="color: #fff; font-size:20px; font-weight:18px; padding:0; margin:0;">Please Verify Your<br>
                Email Address</h2>
            <p style="color: #fff; font-size:14px;">To finish signing up,<br> please confirm your email.</p>  
           
<a href="https://redirect.sipnbourbon.com/verifyemail?email=' . $to . '" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:16px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Verify Email</a>

        </div>
        <div style="background-color: #fff; padding: 15px;">
            <div style="width:30%; float:left; display: inline-block;"><img style="max-width: 80%" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/img1.jpg">
                <div class="clearfix"></div>
            </div>
            <div style="float:right; width: 70%;">
                <ul style="padding: 0; margin:0px 0 15px 15px; font-size:14px; color:#020001; line-height:22px; float: left; display: inline-block;">
                    <h3 style="padding: 0; margin:0 0 5px 0; color: #bca665; width: 100%; font-size: 17px !important; float: left;">Once your are verified Sip back. Stay a while.<br>
                        Welcome to your happy hour.</h3>
                    <li>Find out about upcoming bourbon social events</li>
                    <li>Be the first to know about new bourbon releases</li>
                    <li>Connect with other bourbon connoisseurs</li>
                    <li>Find and shop your favorite bourbon</li>
                    <li>Read tasting notes and reviews</li>
                </ul> 
                <div class="clearfix"></div>   
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="background: #2d2d2c; padding:10px; float:left; width: 100%; text-align: center;">
            <!-- <div style="background-color: #fff; border-radius:50px; height:15px; width:15px; margin: 0 auto;"></div> -->
                    <div style="text-align: center; vertical-align:middle; padding-top:0px;"><img style="width: 10%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-footer.png"></div>

                        <span style="color:white; margin-top: 5px; text-align: center; display: block; font-size: 14px;"> Please click on <a href="#" style="color: #bca665; text-decoration: none;" >Unsubscribe</a> to stop receiving emails from SIPN. </span>
                    
                        <div class="clearfix"></div>
                    <ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block; float: left; ">
                        <li style="margin-right:2px; display: inline-block;"><a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;"></a></li>
                        <li style="margin-right:2px; display: inline-block;"><a href="https://www.facebook.com/sipnbourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;" ></a></li>
                        <li style="display: inline-block;"><a href="https://twitter.com/sipnbourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;"></a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
                        <li style="margin-right:3px; display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a></li>
                        <li style=" display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a></li>
                    </ul>
            <div>
        </div>
</div>
    </div>

</body>';
		//$message .= "Post URL: ".$item['post_url']. "<br>";
		//$message .= "Reason: ".$item['reason']. "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if (wp_mail($to, $subject, $message, $headers)) {

		} else {
			return false;
		}
	}
	print_r($b);
}


function handle_topics_sponsadd(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	$user_id = $cur_user->data->ID;
	if (($item['reply'] != '' || $item['reply_img'] != '') && $item['reply_to'] > 0) {

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);
				$attach_url = wp_get_attachment_url($attachment_id);
				$img = $attach_url;
				$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';
			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
			$img = '';
		}
		$from_device = $item['from_device'];

		$query = $wpdb->prepare("INSERT INTO `wp_sponsored_comments` (`spons_id`, `comment`, `reply_img`, `user_id`, `from_device`) VALUES (%s, %s, %s, %d, %s)", $item['reply_to'], $item['reply'], $img, $user_id, $from_device);
		$res = $wpdb->query($query);
		$lastid = $wpdb->insert_id;
		$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
		$reward_msg = "";
		$reward_msg = reward_points("add",(int)8,$cur_user->data->ID, $item['reply_to']);
		return array("author" => $cur_user->data->display_name, "reply_id" => $lastid, "author_id" => $cur_user->data->ID, "avatar" => $avatar, "message" => "your post is submitted successfully.", "reward_message" => $reward_msg);


	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );
}



function handle_topics_sponsedit(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	$user_id = $cur_user->data->ID;
	if ($item['reply'] != '' && $item['reply_id'] > 0) {

		if (isset($item['reply_img']) && $item['reply_img'] != '') {
			$imgdata = base64_decode($item["reply_img"]);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$type_file = explode('/', $mime_type);
			$avatar = time() . '.' . $type_file[1];

			$uploaddir = wp_upload_dir();
			$myDirPath = $uploaddir["path"];
			$myDirUrl = $uploaddir["url"];

			file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

			$filename = $myDirUrl . '/' . basename($avatar);
			$wp_filetype = wp_check_filetype(basename($filename), null);
			$uploadfile = $uploaddir["path"] . '/' . basename($filename);

			if ($wp_filetype["type"] == 'image/jpeg' || $wp_filetype["type"] == 'image/jpg' || $wp_filetype["type"] == 'image/pjpeg' || $wp_filetype["type"] == 'image/png' || $wp_filetype["type"] == 'image/bmp' || $wp_filetype["type"] == 'image/gif') {
				$attachment = array(
					"post_mime_type" => $wp_filetype["type"],
					"post_title" => preg_replace("/\.[^.]+$/", "", basename($filename)),
					"post_content" => "",
					"post_status" => "inherit",
					'guid' => $uploadfile,
				);

				require_once(ABSPATH . '/wp-load.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
				require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
				$attachment_id = wp_insert_attachment($attachment, $uploadfile);
				$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
				wp_update_attachment_metadata($attachment_id, $attach_data);

				$attach_url = wp_get_attachment_url($attachment_id);
				$img = $attach_url;
				$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
				$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';

			}
		} else if (isset($item['reply_img']) && $item['reply_img'] == '') {

			$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
			$img = '';
		}

		$reply_id = $item['reply_id'];
		$reply_content = $item['reply'];
		$from_device = $item['from_device'];
		$query = $wpdb->prepare("UPDATE `wp_sponsored_comments` SET  comment='%s', reply_img='%s', user_id='%d', from_device='%s' WHERE comment_id = " . $reply_id, $reply_content, $img, $user_id, $from_device);
		//print_r( $query); exit;
		$res = $wpdb->query($query);
		/*if(){$res}{

																					  }else{

																					  }*/
		return array("message" => "your post is updated successfully.");



	} else {
		return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
	}
	//reset the topic IP
	//update_post_meta( $item['topic_id'], '_bbp_author_ip', $ip, false );
}

function handle_timeline_sponscomments(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$parent_id = $item['parent_id'];
	$page = $item['page'] ? $item['page'] : 1;
	//$posts_per_page = 9+ $page;
	if ($page == 1) {
		$posts_per_page = 0;
	} else {
		$posts_per_page = $page * 10 - 10;
	}
	$end_per_page = 10;
	$cur_user = wp_get_current_user();


	$query = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d' order by created_at Desc Limit $posts_per_page,$end_per_page ", $parent_id);
	$cnt_list = $wpdb->get_results($query);
	//$total_replies_count=count($cnt_list);
	$replies = $cnt_list;
	$all_replies = array();
	foreach ($replies as $reply) {
		$author_id = $reply->user_id;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		if (!$avatar) {
			$avatar = get_avatar_url($author_id);
		}
		$userid = $cur_user->data->ID;
		//print_r($userid);exit;

		if ($userid > 0) {
			$query1 = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d' ", $reply->comment_id);
			$cnt_list1 = $wpdb->get_results($query1);

			$total_replies_count = count($cnt_list1);

			//print_r($total_replies_count);exit;



			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE  comment_id='%d'", $reply->spons_id, $reply->comment_id);
			$cnt_list = $wpdb->get_results($query);
			if ($cnt_list[0]->cnt >= 1) {
				$is_liked = "1";
			} else {
				$is_liked = "0";
			}
		} else {
			$is_liked = "0";
		}
		$likes_count = $cnt_list[0]->cnt;

		$reply_f_image = $reply->reply_img;
		if ($reply_f_image) {
			$reply_image_path = $reply_f_image;
		} else {
			$reply_image_path = '';
		}
		$reply_date = timeline_time_ago($reply->created_at);


		$url = get_home_url() . "/timeline_comment/?q=" . $reply->spons_id;

		array_push($all_replies, array('reply_id' => (int) $reply->comment_id, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', strip_tags($reply->comment))), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->created_at, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => $is_liked));
		//print_r($all_replies);
	}


	return $all_replies;
}


function get_timeline_sponsreplies($parent_id, $page)
{
	global $wpdb;
	if (!$page || $page <= 0) {
		$page = 1;
	}
	$cur_user = wp_get_current_user();






	$query = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d' order by created_at Desc limit 0,1", $parent_id);
	$cnt_list = $wpdb->get_results($query);
	$replies = $cnt_list;
	$all_replies = array();
	foreach ($replies as $reply) {
		$author_id = $reply->user_id;
		$author_details = get_user_by('id', $author_id);
		$author_meta = get_user_meta($author_id);
		$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

		if (!$avatar) {
			$avatar = get_avatar_url($author_id);
		}
		$userid = $cur_user->data->ID;
		//print_r($userid);exit;
		if ($userid > 0) {

			//print_r($list);exit;

			$replies = get_timeline_sponsreplies($reply->comment_id, 1);

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d' ", $reply->comment_id);
			$cnt_list = $wpdb->get_results($query);
			if ($cnt_list[0]->cnt >= 1) {
				$is_liked = "1";
			} else {
				$is_liked = "0";
			}
		} else {
			$is_liked = "0";
		}
		$likes_count = $cnt_list[0]->cnt;

		$reply_f_image = $reply->reply_img;
		if ($reply_f_image) {
			$reply_image_path = $reply_f_image;
		} else {
			$reply_image_path = '';
		}
		$reply_date = timeline_time_ago($reply->created_at);


		$query1 = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d'", $reply->comment_id);
		$cnt_list1 = $wpdb->get_results($query1);
		$total_replies_count = count($cnt_list1);

		$url = get_home_url() . "/timeline_sponsads/?q=" . $reply->spons_id;

		array_push($all_replies, array('reply_id' => (int) $reply->comment_id, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', strip_tags($reply->comment))), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->created_at, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => $is_liked, 'replies' => $replies));
	}


	return $all_replies;
}

function handle_reply_sponsoredlikes(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;

	if ($item['spons_id'] != '' && $item['like'] != '' && $user_id > 0) {
		$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $user_id, $item['spons_id']);
		$list = $wpdb->get_results($query);

		if ($list[0]->cnt >= 1) {
			if ($item['like'] == 0) {
				$query = $wpdb->prepare("DELETE FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $user_id, $item['spons_id']);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d'", $item['spons_id']);
				$list = $wpdb->get_results($query);
				reward_points("remove",(int)7, $user_id, $item['spons_id']);
				return array("message" => "Record updated successfully.", "likes" => $list[0]->cnt, "spons_id" => $item['spons_id'], "is_like" => '0');
			} else {
				return new WP_Error('rest_forbidden', 'Record already exists.', array('status' => 403));
			}
		} else {
			$return_msg = "";
			$query = $wpdb->prepare("INSERT INTO `wp_sponsored_likes` (`spons_id`, `user_id`) VALUES (%d, %d)", $item['spons_id'], $user_id);
			$res = $wpdb->query($query);

			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d'", $item['spons_id']);
			$list = $wpdb->get_results($query);
			$return_msg = reward_points("add",(int)7, $user_id, $item['spons_id']);
			return array("message" => "Record inserted successfully.", "likes" => $list[0]->cnt, "spons_id" => $item['spons_id'], "is_like" => '1', "reward_message" => $return_msg);
		}
	} else {
		return new WP_Error('rest_forbidden', 'Invalid request.', array('status' => 403));
	}
}


function handle_timeline_deletesponscomment(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();


	$cur_user = wp_get_current_user();
	$user_details = get_user_meta($cur_user->data->ID);
	if ($item['reply_id'] != '') {

		$reply_id = $item['reply_id'];

		// if ( ! current_user_can( 'edit_reply', $reply_id ) ) {
		// 		return new WP_Error( 'rest_forbidden', 'You do not have permission to delete that post.', array( 'status' => 403 ) );
		// }
		$query = $wpdb->prepare("DELETE FROM `wp_sponsored_comments` WHERE comment_id = '%d'", $reply_id);
		$res = $wpdb->query($query);
		//print_r($res);exit;
		if ($res == 1) {
			//return array("message"=>"your post is deleted successfully.");
			//added by sumeeth 
			reward_points("remove",(int)8,$cur_user->data->ID, $reply_id);
			return array("reply_id" => $reply_id, "message" => "your comment is deleted successfully.");
		} else {
			return new WP_Error('rest_forbidden', 'Your comment is not deleted.', array('status' => 403));
		}
	} else {
		return new WP_Error('rest_forbidden', 'Your comment is not deleted. Please check the provided data.', array('status' => 403));
	}

}


function handle_reply_sponsreport(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	if ($item['post_id'] && $item['post_url'] != '') {
		$to = 'raghu@bottlecapps.com';
		//$to = 'raghu@bottlecapps.com';
		$subject = 'Report Sponsored Ad';
		$message = "Hello, <br>The following add is reported. please check the details below:<br>";
		$message .= "Post URL: " . $item['post_url'] . "<br>";
		$message .= "Reason: " . $item['reason'] . "<br>";
		$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: raghu@bottlecapps.com');
		if (wp_mail($to, $subject, $message, $headers)) {
			return true;
		} else {
			return false;
		}
	}
}

function handle_test(WP_REST_Request $request)
{
	global $wpdb;
	$page = $page ? $page : 1;
	$args2 = [
		'post_type' => 'reply',
		'post_status' => 'publish',
		'order' => 'ASC',
		'post_parent' => 35832,
		'posts_per_page' => 30,
		'date_query' => array(array('after' => date('Y-m-d', strtotime('-7 days')))),
		'meta_query' => array(
			array(
				'key' => '_bbp_reply_to',
				'compare' => 'NOT EXISTS'
			)
			// 'relation' => 'OR', 
			// array(
			// 	'key' => 'post_date',
			// 	'value' => date("Ymd"), // date format error
			// 	'compare' => '>='
			// ) ,
			// array(
			// 	'key' => 'event_end_date',
			// 	'value' => date("Ymd"), // date format error
			// 	'compare' => '>='
			// )
		),

	];

	$replies2 = get_posts($args2);
	print_r($replies2);
	exit;


}


function handle_unsubscribe(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();

	$cur_user = wp_get_current_user();
	$id = $cur_user->data->ID;
	//print_r($id);exit;
	$sval = $item['unsubscribe_flag'];
	//print_r($sval);exit;
	$queryupusid = $wpdb->prepare("UPDATE wp_users SET unsubscribe='$sval' WHERE ID =$id");
	//print_r($queryupusid);exit;
	$res3 = $wpdb->query($queryupusid);
	if ($res3 == 1 && $sval == 1) {
		//return array("message"=>"your post is deleted successfully.");
		//added by sumeeth
		return array("message" => "you have unsubscribed successfully.");
	} else if ($res3 == 1 && $sval == 0) {
		return array("message" => "you have subscribed successfully.");
	} else {
		//return new WP_Error( 'rest_forbidden', 'wrong parameters.', array( 'status' => 403 ) );
	}

}



function handle_user_unsubscribeemail(WP_REST_Request $request)
{
	global $wpdb;
	$myemail1 = $_GET['email'];
	$myemail = base64_decode($myemail1);

	//print_r($myemail);exit;
	$query = $wpdb->prepare("UPDATE wp_users SET unsubscribe='1' WHERE user_email ='" . $myemail . "'");
	$resverify = $wpdb->query($query);
	// print_r($res);exit;
	if ($resverify == '1') {

		echo "Thankyou for unsubscribing your email.";
		//header("location:https://sipnstg.wpengine.com");exit();
		header("location:https://sipnbourbon.com/thankyouunsubscribe");
		exit();
		//header("location:https://sipn.page.link/verify");exit();
	} else {
		echo "Email already unsubscribed please sign in https://sipnbourbon.com/login .";
		//header("location:https://sipnstg.wpengine.com");exit();
		//header("location:https://sipn.page.link/verify");exit();
		header("location:https://sipnbourbon.com/thankyouunsubscribe");
		exit();
	}
	// return $my_profile;	
}


function handle_getunsubscribestatus(WP_REST_Request $request)
{
	global $wpdb;
	$cur_user = wp_get_current_user();
	$id = $cur_user->data->unsubscribe;
	return array("status" => $id);


}


function handle_gettotalproducts(WP_REST_Request $request)
{
	global $wpdb;
	$total_args = [
		'post_type' => 'product',
		'posts_per_page' => 100000,
		'post_status' => 'publish',
		'order' => 'ASC',


	];
	$total_products_list = get_posts($total_args);
	foreach ($total_products_list as $product) {
		$the_product = wc_get_product($product->ID);
		$product_title = $product->post_title;
		$prod_url = get_the_post_thumbnail_url($product->ID, 'medium');
		if (!$prod_url)
			$prod_url = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
		$product_image = $prod_url;
		$product_price = number_format((float) $the_product->price, 2, '.', '');
		$product_flavor = get_post_meta($product->ID, 'flavor', true);
		$product_link = get_permalink($product->ID);
		$handled_product_title = preg_replace('/[^A-Z a-z0-9\-]/', '', $product_title);
		$totalproducts[] = array("product_title" => $product_title, "handled_product_title" => $handled_product_title, "product_image" => $product_image, "product_flavor" => $product_flavor, "product_price" => $product_price, "product_link" => $product_link, "product_id" => $product->ID);

	}



	$tp = array("product_list" => $totalproducts);
	$data = json_encode($tp);
	print_r($data);



}



function handle_gettotalposts_old(WP_REST_Request $request)
{
	global $wpdb;
	//for posts
	$queryposs = $wpdb->prepare("SELECT *  FROM `wp_posts` WHERE `post_parent` = 35832 AND `post_type` LIKE 'reply'");
	$post_list = $wpdb->get_results($queryposs);

	foreach ($post_list as $vals) {


		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'posts_per_page' => 10000,
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
					'value' => $vals->ID,
					'compare' => 'NOT EXISTS'
				)
			),


		];

		$replies = get_posts($args);



	}
	foreach ($replies as $post) {
		$post_id = $post->ID;
		$post_title = strip_tags($post->post_content);
		$handled_post_titlee = preg_replace('/[^A-Z a-z0-9\-]/', '', $post_title);
		$postimg_url = get_the_post_thumbnail_url($post->ID, 'medium');
		if (!$postimg_url)
			$postimg_url = get_stylesheet_directory_uri() . '/assets/images/no-image-available.png';
		$post_image = $postimg_url;
		$query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $post_id AND meta_key ='_bbp_product_id'");

		$p_list = $wpdb->get_results($query1);
		$pid = $p_list[0]->pid;
		if ($pid == '') {
			$pid = '';
		}
		$productlis = get_post($pid);

		$product_title = $productlis->post_title;

		$post_url = "https://sipnbourbon.com/timeline/?q=$post_id";

		$pimage = get_the_post_thumbnail_url($pid, 'full');
		if ($pimage == '') {
			$pimage = '';
		}

		$totalposts[] = array("post_id" => $post_id, "post_title" => $post_title, "handled_post_title" => $handled_post_titlee, "post_image" => $post_image, "tagged_product" => $product_title, "post_url" => $post_url, "taggedproduct_image" => $pimage);

	}


	$tp = array("post_list" => $totalposts);
	$data = json_encode($tp);
	print_r($data);



}

function handle_gettotalposts(WP_REST_Request $request)
{
	global $wpdb;

	$parent_id = 35832; 
	$query = $wpdb->prepare("CALL GetTotalPosts(%d)", $parent_id);
	$post_list = $wpdb->get_results($query); 

	$totalposts = []; 

	
	foreach ($post_list as $post) {
		$post_id = $post->post_id;
		$post_title = strip_tags($post->post_title); 
		$handled_post_title = preg_replace('/[^A-Z a-z0-9\-]/', '', $post_title); 

		
		$post_image = $post->post_image_url ?: 'https://sipnbourbon.com/assets/images/no-image-available.png';

		
		$pimage = '';
		if (!empty($post->product_image_id)) {
			$query_product_image = $wpdb->prepare("
				SELECT guid FROM wp_posts WHERE ID = %d AND post_type = 'attachment'
			", $post->product_image_id);
			$pimage_result = $wpdb->get_var($query_product_image);
			$pimage = $pimage_result ?: '';
		}

		
		$post_url = "https://sipnbourbon.com/timeline/?q=$post_id";

		
		$totalposts[] = array(
			"post_id" => $post_id,
			"post_title" => $post_title,
			"handled_post_title" => $handled_post_title,
			"post_image" => $post_image,
			"tagged_product" => $post->product_title,
			"post_url" => $post_url,
			"taggedproduct_image" => $pimage
		);
	}

	
	$response_data = array("post_list" => $totalposts);
	$data = json_encode($response_data); 
	print_r($data);
	
}

function handle_usersearchitems(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$user_id = $item['user_id'];
	$s_type = $item['search_type'];
	$s_keyword = $item['search_keyword'];
	if (!empty($s_type) && !empty($s_keyword)) {
		$query1 = $wpdb->prepare("INSERT INTO `wp_recent_searches` (`s_user_id`,`s_keyword`, `s_type`) VALUES (%d, %s, %s)", $user_id, $s_keyword, $s_type);
		$res = $wpdb->query($query1);
	}
	$searchitems = $wpdb->prepare("SELECT *  FROM `wp_recent_searches` WHERE `s_user_id` = '" . $user_id . "' order by s_id DESC limit 5");
	$search = $wpdb->get_results($searchitems);

	foreach ($search as $sitem) {

		$searchresults[] = array("user_id" => $sitem->s_user_id, "search_type" => $sitem->s_type, "search_keyword" => $sitem->s_keyword);
	}

	$sdata = json_encode($searchresults);
	print_r($sdata);
}
function handle_recentsearchitems(WP_REST_Request $request)
{
	global $wpdb;

	//print_r("user_id".$request['user_id']); exit;
	$searchitems = $wpdb->prepare("SELECT *  FROM `wp_recent_searches` WHERE `s_user_id` =" . $request['user_id']);
	$search = $wpdb->get_results($searchitems);
	/*$search_res = json_encode($search);
												 echo $search_res; exit;*/
	foreach ($search as $sitem) {
		$type = $sitem->s_type;
		$title = $sitem->s_title;
		$url = $sitem->s_url;
		if ($sitem->s_type == 'sipnpost') {
			$image = site_url() . $sitem->s_img;
		} else {
			$image = $sitem->s_img;
		}

		$flavor = $sitem->s_flavor;
		$price = $sitem->s_price;

		$searchresults[] = array("Type" => $type, "Title" => $title, "URL" => $url, "Image" => $image, "Flavor" => $flavor, "Price" => $price);
	}
	$stp = array("Search_list" => $searchresults);
	$sdata = json_encode($stp);
	print_r($sdata);
}
function handle_usertotalposts(WP_REST_Request $request)
{
	$cur_user_id = get_current_user_id();


	global $wpdb;

	$queryposs = $wpdb->prepare("SELECT *  FROM `wp_posts` WHERE `post_parent` = 35832 and `post_author` = $cur_user_id AND `post_type` LIKE 'reply'");

	$post_list = $wpdb->get_results($queryposs);

	foreach ($post_list as $vals) {


		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'posts_per_page' => 10000,
			'author' => $cur_user_id,
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
					'value' => $vals->ID,
					'compare' => 'NOT EXISTS'
				)
			),


		];

		//print_r($args);
		$replies = get_posts($args);

	}
	foreach ($replies as $post) {
		$post_id = $post->ID;
		$post_date = $post->post_date;
		$post_updated = $post->post_modified;
		$post_title = strip_tags($post->post_content);
		$handled_post_titlee = preg_replace('/[^A-Z a-z0-9\-]/', '', $post_title);
		$postimg_url = get_the_post_thumbnail_url($post->ID, 'medium');
		if (!$postimg_url)
			$postimg_url = get_stylesheet_directory_uri() . '/assets/images/noimage.webp';
		$post_image = $postimg_url;
		$query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $post_id AND meta_key ='_bbp_product_id'");

		$p_list = $wpdb->get_results($query1);
		$pid = $p_list[0]->pid;
		if ($pid == '') {
			$pid = '';
		}
		$productlis = get_post($pid);

		$product_title = $productlis->post_title;

		$post_url = "https://sipnbourbon.com/timeline/?q=$post_id";

		$pimage = get_the_post_thumbnail_url($pid, 'full');
		if ($pimage == '') {
			$pimage = '';
		}
		$date = date('d M', strtotime($post_date));
		$totalposts[] = array("post_id" => $post_id, "post_title" => $post_title, "handled_post_title" => $handled_post_titlee, "post_image" => $post_image, "tagged_product" => $product_title, "post_url" => $post_url, "taggedproduct_image" => $pimage, "post_date" => $date, "post_updated" => $post_updated);

	}
	$tp = array("post_list" => $totalposts);
	$data = json_encode($tp);
	print_r($data);
	//print_r('Test-2');


}

//This function work on  web.. 
function handle_usertotalposts_test()
{
	global $wpdb;
	$cur_user_id = get_current_user_id();
	$queryposs = $wpdb->prepare("SELECT *  FROM `wp_posts` WHERE `post_parent` = 35832 and `post_author` = $cur_user_id AND `post_type` LIKE 'reply'");

	$post_list = $wpdb->get_results($queryposs);

	foreach ($post_list as $vals) {

		$args = [
			'post_type' => 'reply',
			'post_status' => 'publish',
			'order' => 'DESC',
			'posts_per_page' => 10000,
			'author' => $cur_user_id,
			'meta_query' => array(
				array(
					'key' => '_bbp_reply_to',
					'value' => $vals->ID,
					'compare' => 'NOT EXISTS'
				)
			),

		];

		//print_r($args);
		$replies = get_posts($args);

	}
	$totalPosts = [];
	foreach ($replies as $post) {
		$totalPosts[] = $post->ID;
	}
	$totalPostCount = count($totalPosts);
	// $data = json_encode($tp);
	return $totalPostCount;
	
	//print_r('Test-2');


}

function handle_ajaxsendingindexdata_old(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$searchtxt = $item['searchtxt'];
	$param = $item['option'];

	$data = '{
    "size": 10,
			"query": {
				"bool": {
					"must": [
						{
							"bool": {
								"should": [
									{
							"multi_match": {
								"query": "' . $searchtxt . '",
								"fields": [
									"product_title^5.0",
									"handled_product_title^4.0",
									"tagged_product^3.0",
									"post_title^2.0",
									"handled_post_title"
								],
								"type": "phrase_prefix",
								"boost": 5
							}
						},
						{
							"multi_match": {
								"query": "' . $searchtxt . '",
								"fields": [
									"product_title^5.0",
									"handled_product_title^4.0",
									"tagged_product^3.0",
									"post_title^2.0",
									"handled_post_title"
								],
								"type": "bool_prefix",
								"operator": "and",
								"boost": 2
							}
						},
						{
							"multi_match": {
								"query": "' . $searchtxt . '",
								"fields": [
								"product_title",
									"handled_product_title",
									"tagged_product",
									"post_title",
									"handled_post_title"
								],
								"type": "bool_prefix",
								"fuzziness": "2",
								"operator": "and"
							}
						},
						{
							"multi_match": {
								"query": "' . $searchtxt . '",
								"fields": [
									"product_title",
									"handled_product_title",
									"tagged_product",
									"post_title",
									"handled_post_title"
								],
								"type": "most_fields",
								"fuzziness": "2",
								"operator": "and"
							}
									}
								]
							}
						}
					]
				}
			}
		}';
	if ($param == "All") {
		$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnproduct,sipnpost/_search';
	} else if ($param == "post") {

		$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnpost/_search';
	} else if ($param == "product") {

		$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnproduct/_search';
	}
	/*if ($param == "All") {
		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnproduct_prod,sipnpost_prod/_search';
	} else if ($param == "post") {

		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnpost_prod/_search';
	} else if ($param == "product") {

		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnproduct_prod/_search';
	}*/



	$data1 = wp_remote_post($url, array(
		'headers' => array(
			'Content-Type' => 'application/json'
		),
		'body' => $data,
		'timeout' => 15

	));



	$response = wp_remote_retrieve_body($data1);

        print_r($response);
       
}

function handle_ajaxsendingindexdata(WP_REST_Request $request)
{
	global $wpdb;

	$item = $request->get_json_params();

	$searchtxt = sanitize_text_field($item['searchtxt']);
	$param     = sanitize_text_field($item['option']);

	$response = [
		'hits' => [
			'hits' => []
		]
	];

	$like = '%' . $wpdb->esc_like($searchtxt) . '%';

	/*
	|--------------------------------------------------------------------------
	| PRODUCTS
	|--------------------------------------------------------------------------
	*/
	if ($param == 'All' || $param == 'product') {

		$product_query = $wpdb->prepare("
			SELECT 
				p.ID as product_id,
				p.post_title as product_title
			FROM {$wpdb->posts} p
			WHERE p.post_type = 'product'
			AND p.post_status = 'publish'
			AND p.post_title LIKE %s
			ORDER BY p.post_title ASC
			LIMIT 5
		", $like);

		$products = $wpdb->get_results($product_query);

		foreach ($products as $product) {

			$product_id = $product->product_id;

			$product_image = get_the_post_thumbnail_url($product_id, 'full');
			$product_image = $product_image ? $product_image : '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';

			$product_price = get_post_meta($product_id, '_price', true);
			$product_flavor = get_post_meta($product_id, 'flavor', true);

			$response['hits']['hits'][] = [
				'_index' => 'sipnproduct_prod',
				'_source' => [
					'product_title'  => $product->product_title,
					'product_link'   => get_permalink($product_id),
					'product_image'  => $product_image,
					'product_price'  => (float)$product_price,
					'product_flavor' => $product_flavor
				]
			];
		}
	}

	/*
	|--------------------------------------------------------------------------
	| POSTS
	|--------------------------------------------------------------------------
	*/
	if ($param == 'All' || $param == 'post') {

		$post_query = $wpdb->prepare("
			SELECT 
				p.ID as post_id,
				p.post_title,
				p.post_content,
				pm.meta_value as tagged_product
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm 
				ON p.ID = pm.post_id 
				AND pm.meta_key = 'tagged_product'
			WHERE p.post_type IN ('post', 'reply')
			AND p.post_status = 'publish'
			AND (
				p.post_title LIKE %s
				OR pm.meta_value LIKE %s
				OR p.post_content LIKE %s
			)
			ORDER BY p.ID DESC
			LIMIT 5
		", $like, $like, $like);

		$posts = $wpdb->get_results($post_query);

		foreach ($posts as $post) {

			$post_title = trim($post->post_title);

			// fallback to content
			if (empty($post_title)) {

				$post_content = wp_strip_all_tags($post->post_content);

				$post_title = mb_substr($post_content, 0, 80);

				if (mb_strlen($post_content) > 80) {
					$post_title .= '...';
				}
			}

			$response['hits']['hits'][] = [
				'_index' => 'sipnpost_prod',
				'_source' => [
					'post_title'     => $post_title,
					'post_url'       => get_permalink($post->post_id),
					'tagged_product' => $post->tagged_product
				]
			];
		}
	}

    return rest_ensure_response($response);
}


function handle_user_resendverificationemail(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$to = $item['email'];
	$subject = 'Welcome to SIPN';

	$message = '<body style="font-family:"Helvetica"; padding: 0; margin: 0;">
    <div style="width: 599px; margin:0 auto;">
        <div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;" >
            <div style="text-align: center; vertical-align:middle; padding-top:22px;"><img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png"></div>
        </div>
        <div style="background-color: #fff; text-align:center; padding: 13px;">
            <h1 style="color: #bca665; font-size:20px; font-weight:600;font-family:"Helvetica"; margin: 0; padding: 0;">
                Welcome to SIPN
            </h1>
            <p style="color: #020001; font-size:14px; margin: 0;">A bourbon community for the curious to the connoisseur.</p>
        </div>
        <div style="background: #2d2d2c; padding:15px; text-align: center;">
            <h2 style="color: #fff; font-size:20px; font-weight:18px; padding:0; margin:0;">To finish signing up,<br> please confirm your email<br></h2>
         
<a href="https://redirect.sipnbourbon.com/verifyemail?email=' . $to . '" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:18px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Verify Email</a>

        </div>
        <div style="background-color: #fff; padding: 15px;">
            <div style="width:30%; float:left; display: inline-block;"><img style="max-width: 80%" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/img1.jpg">
                <div class="clearfix"></div>
            </div>
            <div style="float:right; width: 70%;">
                <ul style="padding: 0; margin:0px 0 15px 15px; font-size:14px; color:#020001; line-height:22px; float: left; display: inline-block;">
                    <h3 style="padding: 0; margin:0 0 5px 0; color: #bca665; width: 100%; font-size: 17px !important; float: left;">Once your are verified Sip back. Stay a while.
                       </h3>
                    <li>Find out about upcoming bourbon social events</li>
                    <li>Be the first to know about new bourbon releases</li>
                    <li>Connect with other bourbon connoisseurs</li>
                    <li>Find and shop your favorite bourbon</li>
                    <li>Read tasting notes and reviews</li>
                </ul> 
                <div class="clearfix"></div>   
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="background: #2d2d2c; padding:10px; float:left; width: 100%; text-align: center;">
            <!-- <div style="background-color: #fff; border-radius:50px; height:15px; width:15px; margin: 0 auto;"></div> -->
                    <div style="text-align: center; vertical-align:middle; padding-top:0px;"><img style="width: 10%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-footer.png"></div>

                        <span style="color:white; margin-top: 5px; text-align: center; display: block; font-size: 14px;"> Please click on <a href="#" style="color: #bca665; text-decoration: none;" >Unsubscribe</a> to stop receiving emails from SIPN. </span>
                    
                        <div class="clearfix"></div>
                    <ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block; float: left; ">
                        <li style="margin-right:2px; display: inline-block;"><a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;"></a></li>
                        <li style="margin-right:2px; display: inline-block;"><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;" ></a></li>
                        <li style="display: inline-block;"><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;"></a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
                        <li style="margin-right:3px; display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a></li>
                        <li style=" display: inline-block;"><a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a></li>
                    </ul>
            <div>
        </div>
</div>
    </div>

</body>';
	//$message .= "Post URL: ".$item['post_url']. "<br>";
	//$message .= "Reason: ".$item['reason']. "<br>";
	$headers = array('Content-Type: text/html; charset=UTF-8');
	if (wp_mail($to, $subject, $message, $headers)) {
		return array("message" => "Verification Email sent successfully.");
	} else {
		return array("message" => "Verification Email sent failed.");
	}

}

function handle_user_emailverificationstatus(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$to = $item['email'];
	//print_r($to);exit;
	$query1 = $wpdb->prepare("SELECT validate_email  FROM wp_users  WHERE user_email ='%s'", $to);

	$p_list = $wpdb->get_results($query1);
	$pid = $p_list[0]->validate_email;
	return array("status" => $pid);


}

function ajaxpostdetail(WP_REST_Request $request)
{
    global $wpdb;

    $item    = $request->get_json_params();
    $post_id = $item['post_id'];

    $args = [
        'include'     => [$post_id],
        'post_type'   => 'reply',
        'post_status' => 'publish',
        'order'       => 'DESC'
    ];

    $cur_user    = wp_get_current_user();
    $cur_user_id = $cur_user->data->ID ?? 0;

    $replies               = get_posts($args);
    $all_topics['replies'] = [];

    foreach ($replies as $reply) {
        $author_id      = (int) $reply->post_author;
        $author_details = get_user_by('id', $author_id);
        $author_meta    = get_user_meta($author_id);
        $avatar         = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0] ?? '', 'thumbnail');

        if (!$avatar) {
            $avatar = get_avatar_url($author_id);
        }

        // Likes count
        $query       = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = %d and status ='0'", $reply->ID);
        $cnt_list    = $wpdb->get_results($query);
        $likes_count = isset($cnt_list[0]->cnt) ? (int) $cnt_list[0]->cnt : 0;

        // Collect attached IDs
        $thumbnail_id = get_post_meta($reply->ID, '_thumbnail_id', true);
        $t            = !empty($thumbnail_id) ? explode(',', $thumbnail_id) : [];

        // Combined images + videos array
        $reply_media_array = [];
        if (!empty($t)) {
            foreach ($t as $val) {
                $mime_type = get_post_mime_type($val);

                if (strpos($mime_type, 'video') !== false) {
                    // -------------------------
                    // Handle video attachments
                    // -------------------------
                    $video_url = wp_get_attachment_url($val);
                    if ($video_url) {
                        $video_thumbnail    = '';
                        $thumbnail_id_video = get_post_meta($val, 'video_thumbnail_id', true);

                        if ($thumbnail_id_video) {
                            $video_thumbnail = wp_get_attachment_url($thumbnail_id_video);
                        }

                        if (!$video_thumbnail) {
                            $video_filename     = basename(get_attached_file($val));
                            $thumbnail_filename = str_replace(
                                ['.mp4', '.mov', '.webm', '.mkv'],
                                '_thumb.jpg',
                                $video_filename
                            );

                            $args_thumb = [
                                'post_type'      => 'attachment',
                                'post_status'    => 'inherit',
                                'posts_per_page' => 1,
                                'meta_query'     => [
                                    [
                                        'key'     => '_wp_attached_file',
                                        'value'   => $thumbnail_filename,
                                        'compare' => 'LIKE'
                                    ]
                                ]
                            ];
                            $thumb_query = new WP_Query($args_thumb);
                            if ($thumb_query->have_posts()) {
                                $thumb           = $thumb_query->posts[0];
                                $video_thumbnail = wp_get_attachment_url($thumb->ID);
                            }
                            wp_reset_postdata();
                        }

                        $reply_media_array[] = [
                            'video'         => $video_url,
                            'thumbnail_url' => $video_thumbnail ?: ''
                        ];
                    }
                } elseif (strpos($mime_type, 'image') !== false) {
                    // -------------------------
                    // Handle image attachments
                    // -------------------------
                    $image_url = wp_get_attachment_url($val);

                    // Skip auto-generated video thumbnails (_thumb.jpg)
                    if ($image_url && !preg_match('/_thumb\.jpg$/i', $image_url)) {
                        $reply_media_array[] = [
                            'image' => $image_url
                        ];
                    }
                }
            }
        }

        // Tagged location
        $query2 = $wpdb->prepare("SELECT meta_value as lid FROM wp_postmeta WHERE post_id = %d AND meta_key ='_bbp_tagged_location'", $reply->ID);
        $l_list = $wpdb->get_results($query2);
        $lid    = (isset($l_list[0]->lid) && $l_list[0]->lid !== '' && strtolower($l_list[0]->lid) !== 'null') ? $l_list[0]->lid : null;

        // Product tag
        $query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = %d AND meta_key ='_bbp_product_id'", $reply->ID);
        $p_list = $wpdb->get_results($query1);
        $pid    = (isset($p_list[0]->pid) && $p_list[0]->pid !== '' && strtolower($p_list[0]->pid) !== 'null') ? (int) $p_list[0]->pid : null;

        $productlis    = !empty($pid) ? get_post($pid) : '';
        $product_title = !empty($productlis->post_title) ? $productlis->post_title : '';
        $product_image = $pid ? (get_the_post_thumbnail_url($pid, 'full') ?: '') : '';
        $abc           = $pid ? get_post_meta($pid, 'productupc', true) : '';
        $product_upc   = !empty($abc) ? str_replace("#", "", $abc) : '';

        $the_product    = !empty($pid) ? wc_get_product($pid) : false;
        $product_rating = $the_product ? $the_product->average_rating : 0;
        $product_price  = $the_product ? $the_product->price : '';

        // Count total replies
        $query   = [
            'post_type'   => 'reply',
            'post_status' => 'publish',
            'order'       => 'ASC',
            'meta_query'  => [
                [
                    'key'   => '_bbp_reply_to',
                    'value' => $reply->ID,
                ]
            ],
        ];
        $results             = new WP_Query($query);
        $total_replies_count = $results->found_posts;
        wp_reset_postdata();

        $replies_child = get_timeline_replies($reply->ID, 1);

        $reply_date = timeline_time_ago($reply->post_date);
        $url        = get_home_url() . "/timeline?q=" . $reply->ID;

        $all_topics['replies'][] = [
            'reply_id'           => (int) $reply->ID,
            'reply'              => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)),
            'reply_videoarray'   => $reply_media_array,
            'reply_date'         => $reply_date,
            'reply_gmt_date'     => $reply->post_date,
            'total_replies_count'=> (int) $total_replies_count,
            'author'             => $author_details->data->display_name ?? '',
            'author_id'          => $author_id,
            'author_city'        => $author_meta['city'][0] ?? '',
            'author_state'       => $author_meta['state'][0] ?? '',
            'url'                => $url,
            'avatar'             => $avatar,
            'likes'              => $likes_count,
            'is_liked'           => (int) get_like_flag($reply->ID),
            'product_id'         => $pid,
            'product_title'      => $product_title,
            'product_image'      => $product_image,
            'product_upc'        => $product_upc,
            'product_rating'     => (float) $product_rating,
            'product_price'      => $product_price,
            'tagged_location'    => $lid,
            'replies'            => $replies_child
        ];
    }

    return new WP_REST_Response($all_topics, 200);
}


function opensearchapiold(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$searchtxt = $item['searchtxt'];
	$param = $item['option'];
	//print_r($param);exit;
	//print_r($searchtxt);exit;

	$data = '{
    "size": 10,
    "query": {
        "bool": {
            "must": [
                {
                    "bool": {
                        "should": [
                            {
                    "multi_match": {
                        "query": "' . $searchtxt . '",
                        "fields": [
                            "product_title^5.0",
                            "handled_product_title^4.0",
                            "tagged_product^3.0",
                            "post_title^2.0",
                            "handled_post_title"
                        ],
                        "type": "phrase_prefix",
                        "boost": 5
                    }
                },
                {
                    "multi_match": {
                        "query": "' . $searchtxt . '",
                        "fields": [
                            "product_title^5.0",
                            "handled_product_title^4.0",
                            "tagged_product^3.0",
                            "post_title^2.0",
                            "handled_post_title"
                        ],
                        "type": "bool_prefix",
                        "operator": "and",
                        "boost": 2
                    }
                },
                {
                    "multi_match": {
                        "query": "' . $searchtxt . '",
                        "fields": [
                           "product_title",
                            "handled_product_title",
                            "tagged_product",
                            "post_title",
                            "handled_post_title"
                        ],
                        "type": "bool_prefix",
                        "fuzziness": "2",
                        "operator": "and"
                    }
                },
                {
                    "multi_match": {
                        "query": "' . $searchtxt . '",
                        "fields": [
                            "product_title",
                            "handled_product_title",
                            "tagged_product",
                            "post_title",
                            "handled_post_title"
                        ],
                        "type": "most_fields",
                        "fuzziness": "2",
                        "operator": "and"
                    }
                            }
                        ]
                    }
                }
            ]
        }
    }
}';
	// if ($param == "All") {
	// 	$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnproduct,sipnpost/_search';
	// } else if ($param == "post") {

	// 	$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnpost/_search';
	// } else if ($param == "product") {

	// 	$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnproduct/_search';
	// }
	if ($param == "All") {
		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnproduct_prod,sipnpost_prod/_search';
	} else if ($param == "post") {

		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnpost_prod/_search';
	} else if ($param == "product") {

		$url = 'https://search-sipn-opensearch-staging-7fzr3ynmqc6op3logjqwa6vxsy.us-east-1.es.amazonaws.com/sipnproduct_prod/_search';
	}

	$data1 = wp_remote_post($url, array(
		'headers' => array(
			'Content-Type' => 'application/json'
		),
		'body' => $data,
		'timeout' => 15

	));

	$response = wp_remote_retrieve_body($data1);

	$arr = json_decode($response, TRUE);
	$arr['input'] = ['option' => $param, 'searchtxt' => $searchtxt];
	$json = json_encode($arr);

	print_r($json);



}

function opensearchapi(WP_REST_Request $request)
{
	global $wpdb;

	$item = $request->get_json_params();

	$searchtxt = sanitize_text_field($item['searchtxt']);
	$param     = sanitize_text_field($item['option']);

	$like = '%' . $wpdb->esc_like($searchtxt) . '%';

	$response = [
		'took' => 1,
		'timed_out' => false,
		'hits' => [
			'total' => [
				'value' => 0,
				'relation' => 'eq'
			],
			'hits' => []
		]
	];

	/*
	|--------------------------------------------------------------------------
	| PRODUCTS
	|--------------------------------------------------------------------------
	*/
	if ($param == 'All' || $param == 'product') {

		$product_query = $wpdb->prepare("
			SELECT 
				p.ID as product_id,
				p.post_title as product_title
			FROM {$wpdb->posts} p
			WHERE p.post_type = 'product'
			AND p.post_status = 'publish'
			AND p.post_title LIKE %s
			ORDER BY p.ID DESC
			LIMIT 5
		", $like);

		$products = $wpdb->get_results($product_query);

		foreach ($products as $product) {

			$product_id = $product->product_id;

			$product_image = get_the_post_thumbnail_url($product_id, 'full');
			$product_image = $product_image ? $product_image : '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';

			$product_price  = get_post_meta($product_id, '_price', true);
			$product_flavor = get_post_meta($product_id, 'flavor', true);

			$response['hits']['hits'][] = [
				'_index' => 'sipnproduct_prod',
				'_id'    => (string)$product_id,
				'_score' => 1,
				'_source' => [
					'product_id'            => (int)$product_id,
					'product_title'         => $product->product_title,
					'handled_product_title' => preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($product->product_title)),
					'product_link'          => get_permalink($product_id),
					'product_image'         => $product_image,
					'product_price'         => (float)$product_price,
					'product_flavor'        => $product_flavor
				]
			];
		}
	}

	/*
	|--------------------------------------------------------------------------
	| POSTS + REPLIES
	|--------------------------------------------------------------------------
	*/
	if ($param == 'All' || $param == 'post') {

		$post_query = $wpdb->prepare("
			SELECT 
				p.ID as post_id,
				p.post_title,
				p.post_content,
				pm.meta_value as tagged_product
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm 
				ON p.ID = pm.post_id 
				AND pm.meta_key = 'tagged_product'
			WHERE p.post_type IN ('post', 'reply')
			AND p.post_status = 'publish'
			AND (
				p.post_title LIKE %s
				OR p.post_content LIKE %s
				OR pm.meta_value LIKE %s
			)
			ORDER BY p.ID DESC
			LIMIT 5
		", $like, $like, $like);

		$posts = $wpdb->get_results($post_query);

		foreach ($posts as $post) {

			$post_title = trim($post->post_title);

			// fallback title from content
			if (empty($post_title)) {

				$post_content = wp_strip_all_tags($post->post_content);

				$post_title = mb_substr($post_content, 0, 80);

				if (mb_strlen($post_content) > 80) {
					$post_title .= '...';
				}
			}

			$post_image = get_the_post_thumbnail_url($post->post_id, 'full');
			$post_image = $post_image ? $post_image : '';

			$response['hits']['hits'][] = [
				'_index' => 'sipnpost_prod',
				'_id'    => (string)$post->post_id,
				'_score' => 1,
				'_source' => [
					'post_id'         => (int)$post->post_id,
					'post_title'      => $post_title,
					'post_image'      => $post_image,
					'post_url'        => get_permalink($post->post_id),
					'tagged_product'  => $post->tagged_product
				]
			];
		}
	}

	$response['hits']['total']['value'] = count($response['hits']['hits']);

	$response['input'] = [
		'option'    => $param,
		'searchtxt' => $searchtxt
	];

	return rest_ensure_response($response);
}

function handle_user_recordsponsoredaddclick(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$spons_id = $item['id'];
	$from = $item['from'];
	$actiontype = $item['actiontype'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	$query = $wpdb->prepare("INSERT INTO `wp_sponsored_ad_clicks` (`spons_id`, `from_device`, `actiontype`, `ip`,  `created_by`) VALUES (%d, %s, %s, %s, %d)", $spons_id, $from, $actiontype, $ip, $user_id);
	$res = $wpdb->query($query);
	if ($res) {
		return array("message" => "Click recorded successfully.");
	} else {
		return array("message" => "Recording click failed.");
	}


}

function handle_user_logout(WP_REST_Request $request)
{
	global $wpdb;
	$item = $request->get_json_params();
	$device_id = $item['device_id'];
	$query = $wpdb->prepare("UPDATE `wp_devices` SET  user_id = '' WHERE device_id = 
		%s", $device_id);
	$res = $wpdb->query($query);
	if ($res) {
		return array("message" => "Logout successfully.");
	} else {
		return array("message" => "Logout unsuccessfull.");
	}


}

function handle_user_readcountfornotifications(WP_REST_Request $request)
{
	global $wpdb;
	//$item = $request->get_json_params();
	//$item['notification_id']=[18,19,20,21];
	//$notic_array=$item['notification_id'];
	//foreach ($notic_array as $key => $value) {
	$cur_user = wp_get_current_user();
	$user_id = $cur_user->data->ID;
	$query = $wpdb->prepare("UPDATE `notification_table` SET read_notification='1' WHERE notification_to = '%d'", $user_id);
	$res = $wpdb->query($query);

	//		}
	if ($res) {
		return array("message" => "Read status change successfully.");
	} else {
		return array("message" => "Read status change unsuccessfull.");
	}


}


/** Ratings **/
add_action('rest_api_init', function () {
	/**
     * RATING API: Submit or Update a Rating
     * POST /ratings/v1/submit
     */
    register_rest_route('ratings/v1', '/submit', array(
        'methods' => 'POST',
        'callback' => 'handle_submit_rating',
        'permission_callback' => 'is_user_logged_in' // Secure: Only logged-in users can rate
    ));

    /**
     * RATING API: Get Top Rated Products
     * GET /products/v1/top-rated
     */
    register_rest_route('products/v1', '/top-rated', array(
        'methods' => 'GET',
        'callback' => 'handle_get_top_rated_products',
        'permission_callback' => '__return_true' // Public: Anyone can see top-rated products
    ));

    /**
     * RATING API: Get Current User's Rating List (for "Rating History")
     * GET /ratings/v1/my-ratings
     */
    register_rest_route('ratings/v1', '/my-ratings', array(
        'methods' => 'GET',
        'callback' => 'handle_get_my_ratings_list',
        'permission_callback' => 'is_user_logged_in'
    ));

    /**
     * RATING API: Get Current User's Detailed Rating for one product
     * GET /ratings/v1/my-rating/(?P<product_id>\d+)
     */
    register_rest_route('ratings/v1', '/my-rating/(?P<product_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'handle_get_my_rating_detail',
        'permission_callback' => 'is_user_logged_in'
    ));
});

/**
 * API CALLBACK: Submit or Update a Rating
 * This function uses "INSERT ... ON DUPLICATE KEY UPDATE" to handle
 * both new ratings and editing old ratings in one secure query.
 */
function handle_submit_rating(WP_REST_Request $request) {
    global $wpdb;
    $params = $request->get_json_params();
    $user_id = get_current_user_id();

    // 1. Validate input data
    $product_id = intval($params['product_id']);
    $nose = intval($params['nose']);
    $palate = intval($params['palate']);
    $finish = intval($params['finish']);
    // $experience = intval($params['experience']);
    $value = intval($params['value']);
    $notes = sanitize_textarea_field($params['notes']);

    if (empty($product_id) || $nose < 1 || $palate < 1 || $finish < 1 || $value < 1) {
        return new WP_Error('invalid_data', 'Invalid rating data submitted.', ['status' => 400]);
    }

    // 2. Calculate the overall rating (average of the 4 scores)
    $overall = ($nose + $palate + $finish + $value) / 4.0;

    $ratings_table = $wpdb->prefix . 'ratings';

    // 3. Securely insert or update the rating in the database
    $query = $wpdb->prepare(
        "INSERT INTO $ratings_table
         (product_id, user_id, rating_overall, rating_nose, rating_palate, rating_finish, rating_experience, rating_value, tasting_notes)
         VALUES (%d, %d, %f, %d, %d, %d, %d, %d, %s)
         ON DUPLICATE KEY UPDATE
         rating_overall = %f,
         rating_nose = %d,
         rating_palate = %d,
         rating_finish = %d,
         rating_experience = %d,
         rating_value = %d,
         tasting_notes = %s",
        $product_id, $user_id, $overall, $nose, $palate, $finish, $experience, $value, $notes,
        $overall, $nose, $palate, $finish, $experience, $value, $notes // Values for the UPDATE part
    );

    $result = $wpdb->query($query);

    if ($result === false) {
        return new WP_Error('db_error', 'Could not save rating.', ['status' => 500]);
    }

    // 4. Recalculate and store the new product average rating
    recalculate_product_average_rating($product_id);

    // 5. Return the newly saved rating
    return new WP_REST_Response([
        'success' => true,
        'message' => 'Rating submitted successfully.',
        'new_overall_rating' => $overall
    ], 200);
}

/**
 * HELPER: Recalculates and saves the average rating for a product.
 * This is the high-performance way to store aggregate ratings.
 */
function recalculate_product_average_rating($product_id) {
    global $wpdb;
    $product_id = intval($product_id);
    $ratings_table = $wpdb->prefix . 'ratings';

    // Securely calculate the new average and count
    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT AVG(rating_overall) as average, COUNT(id) as count
         FROM $ratings_table
         WHERE product_id = %d",
        $product_id
    ));

    if ($stats) {
        // Update the product's meta fields
        update_post_meta($product_id, '_product_average_rating', $stats->average);
        update_post_meta($product_id, '_product_rating_count', $stats->count);
    }
}

/**
 * API CALLBACK: Get Top Rated Products (for Homepage)
 * Uses WP_Query for a safe and performant query.
 */
function handle_get_top_rated_products(WP_REST_Request $request) {
    $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 3;

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'meta_key' => '_product_average_rating', // Order by our new meta field
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => [
            [
                'key' => '_product_rating_count', // Only include items with at least one rating
                'compare' => '>',
                'value' => 0
            ]
        ]
    ];

    $products_query = new WP_Query($args);
    $products = [];

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_id = get_the_ID();

            $products[] = [
                'product_id' => $product_id,
                'product_name' => get_the_title(),
                'product_image' => get_the_post_thumbnail_url($product_id, 'medium'),
                'average_rating' => (float) get_post_meta($product_id, '_product_average_rating', true),
                'rating_count' => (int) get_post_meta($product_id, '_product_rating_count', true)
            ];
        }
    }
    wp_reset_postdata();

    return new WP_REST_Response($products, 200);
}

/**
 * API CALLBACK: Get Current User's Rating List (for "Rating History")
 */
function handle_get_my_ratings_list(WP_REST_Request $request) {
    global $wpdb;
    $user_id = get_current_user_id();

    $ratings_table = $wpdb->prefix . 'ratings';
    $posts_table = $wpdb->prefix . 'posts';

    $my_ratings = $wpdb->get_results($wpdb->prepare(
        "SELECT r.product_id, r.rating_overall, p.post_title
         FROM $ratings_table r
         JOIN $posts_table p ON r.product_id = p.ID
         WHERE r.user_id = %d
         ORDER BY r.updated_at DESC",
        $user_id
    ));

    $response = [];
    foreach ($my_ratings as $rating) {
        $response[] = [
            'product_id' => (int) $rating->product_id,
            'product_name' => $rating->post_title,
            'product_image' => get_the_post_thumbnail_url($rating->product_id, 'medium'),
            'overall_rating' => (float) $rating->rating_overall
        ];
    }

    return new WP_REST_Response($response, 200);
}

/**
 * API CALLBACK: Get Current User's Detailed Rating for one product
 * (Updated to include product name, image, and other details)
 */
function handle_get_my_rating_detail(WP_REST_Request $request) {
    global $wpdb;

    $product_id = intval($request['product_id']);
    $user_id = get_current_user_id();

    // 1. Get the user's rating details (using the helper we already built)
    $rating_details = get_user_rating_for_product($product_id, $user_id);

    if (!$rating_details) {
        return new WP_Error('not_found', 'You have not rated this product.', ['status' => 404]);
    }

    // 2. Get the product post object
    $product_post = get_post($product_id);

    if (!$product_post || $product_post->post_type !== 'product') {
         return new WP_Error('not_found', 'Product not found.', ['status' => 404]);
    }

    // 3. Get extra product meta details (based on your designs and other functions)
    $distillery = get_post_meta($product_id, 'distillery', true);
    $age = get_post_meta($product_id, 'age', true);
    // Note: Your design says ABV, but your other code uses 'proof'
    $abv = get_post_meta($product_id, 'proof', true);

    // 4. Combine all data into a single response
    $response = [
        'product_id' => $product_id,
        'product_name' => $product_post->post_title,
        'product_brand' => $distillery ?: '', // e.g., "Buffalo Trace"
        'product_image' => get_the_post_thumbnail_url($product_id, 'full'),
        'age' => $age ?: '',
        'abv' => $abv ?: '',
        'my_rating' => $rating_details // Nests all the user's scores (nose, palate, etc.)
    ];

    return new WP_REST_Response($response, 200);
}

/**
 * HELPER: Gets a user's detailed rating for a single product.
 * We will also use this in the Product Detail API.
 */
function get_user_rating_for_product($product_id, $user_id) {
    global $wpdb;
    $ratings_table = $wpdb->prefix . 'ratings';

    $rating = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $ratings_table WHERE product_id = %d AND user_id = %d",
        $product_id, $user_id
    ));

    if (!$rating) {
        return null;
    }

    // Format the output
    return [
        'product_id' => (int) $rating->product_id,
        'overall_rating' => (float) $rating->rating_overall,
        'nose' => (int) $rating->rating_nose,
        'palate' => (int) $rating->rating_palate,
        'finish' => (int) $rating->rating_finish,
        // 'experience' => (int) $rating->rating_experience,
        'value' => (int) $rating->rating_value,
        'tasting_notes' => $rating->tasting_notes
    ];
}

/* =========================================================================
 * Featured Product Click Tracking
 * Records clicks on featured products (from web carousel and mobile app)
 * and exposes a report under the WordPress admin menu.
 * ========================================================================= */

define('BAR_FEATURED_CLICKS_DB_VERSION', '1.0');

/**
 * Returns the featured-clicks table name.
 */
function bar_featured_clicks_table()
{
	global $wpdb;
	return $wpdb->prefix . 'bar_featured_clicks';
}

/**
 * Creates the featured-clicks table if it does not exist yet.
 * Runs on every load but only executes dbDelta when the stored DB version
 * is out of date, so existing installs get the table without reactivating.
 */
function bar_init_featured_clicks_table()
{
	if (get_option('bar_featured_clicks_db_version') === BAR_FEATURED_CLICKS_DB_VERSION) {
		return;
	}

	global $wpdb;
	$table_name = bar_featured_clicks_table();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		product_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL DEFAULT 0,
		source varchar(10) NOT NULL DEFAULT 'web',
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY product_id (product_id),
		KEY created (created)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('bar_featured_clicks_db_version', BAR_FEATURED_CLICKS_DB_VERSION);
}
add_action('init', 'bar_init_featured_clicks_table');

/**
 * Inserts a featured-product click into the tracking table.
 *
 * @param int    $product_id The clicked product ID.
 * @param string $source     'web' or 'app'.
 * @param int    $user_id    Logged-in user ID, 0 for guests.
 * @return bool True on success.
 */
function bar_record_featured_click($product_id, $source = 'web', $user_id = 0)
{
	global $wpdb;

	$product_id = (int) $product_id;
	if ($product_id <= 0) {
		return false;
	}

	$source = ($source === 'app') ? 'app' : 'web';

	$inserted = $wpdb->insert(
		bar_featured_clicks_table(),
		array(
			'product_id' => $product_id,
			'user_id'    => (int) $user_id,
			'source'     => $source,
		),
		array('%d', '%d', '%s')
	);

	return $inserted !== false;
}

/**
 * REST callback: records a featured-product click.
 * Accepts JSON { product_id: int, source: "web"|"app" }.
 */
function handle_featured_click(WP_REST_Request $request)
{
	$params = $request->get_json_params();
	if (empty($params)) {
		$params = $request->get_params();
	}

	$product_id = isset($params['product_id']) ? (int) $params['product_id'] : 0;
	$source     = isset($params['source']) ? sanitize_text_field($params['source']) : 'web';

	if ($product_id <= 0) {
		return new WP_REST_Response(array(
			'success' => false,
			'message' => 'A valid product_id is required.',
		), 400);
	}

	$ok = bar_record_featured_click($product_id, $source, get_current_user_id());

	return new WP_REST_Response(array(
		'success' => (bool) $ok,
	), $ok ? 200 : 500);
}

add_action('rest_api_init', function () {
	register_rest_route('products/v2', '/featured-click', array(
		'methods' => 'POST',
		'callback' => 'handle_featured_click',
		'permission_callback' => function ($request) {
			return true;
		}
	));
});

/**
 * Registers the admin report page.
 */
function bar_featured_clicks_admin_menu()
{
	// Single top-level "Stats" menu that houses every report as a submenu.
	add_menu_page(
		'Stats',                    // page title
		'Stats',                    // menu title
		'manage_options',           // capability
		'sipn-stats',               // parent slug
		'bar_render_featured_views_report', // default callback (Featured Views)
		'dashicons-chart-area',     // icon
		'26.1'                      // position: immediately after "Sponsored
		                            // Ads" (26). Unique float avoids the
		                            // WooCommerce menu cluster at 55-58 that
		                            // was silently dropping this menu.
	);

	// Featured Views (default page; reuse parent slug so it isn't duplicated).
	add_submenu_page(
		'sipn-stats',
		'Featured Views',
		'Featured Views',
		'manage_options',
		'sipn-stats',
		'bar_render_featured_views_report'
	);

	// Buy Now Clicks (slug preserved for existing links/bookmarks).
	add_submenu_page(
		'sipn-stats',
		'Buy Now Clicks',
		'Buy Now Clicks',
		'manage_options',
		'bar-buynow-clicks',
		'bar_render_buynow_clicks_report'
	);

	// Push Notifications (slug preserved).
	add_submenu_page(
		'sipn-stats',
		'Push Notifications',
		'Push Notifications',
		'manage_options',
		'bar-push-stats',
		'bar_render_push_stats_report'
	);

	// Verified Users (new).
	add_submenu_page(
		'sipn-stats',
		'Verified Users',
		'Verified Users',
		'manage_options',
		'bar-verified-users',
		'bar_render_verified_users_report'
	);

	// User Engagement (new).
	add_submenu_page(
		'sipn-stats',
		'User Engagement',
		'User Engagement',
		'manage_options',
		'bar-user-engagement',
		'bar_render_user_engagement_report'
	);

	// Sponsored Ads (new).
	add_submenu_page(
		'sipn-stats',
		'Sponsored Ads',
		'Sponsored Ads',
		'manage_options',
		'bar-sponsored-ads',
		'bar_render_sponsored_ads_report'
	);

	// Redirect/Alias for bar-featured-views so old links/bookmarks don't break
	add_submenu_page(
		null,
		'Featured Views',
		'Featured Views',
		'manage_options',
		'bar-featured-views',
		'bar_render_featured_views_report'
	);
}
add_action('admin_menu', 'bar_featured_clicks_admin_menu');

/**
 * Renders the featured-clicks report table in wp-admin.
 */
function bar_render_featured_clicks_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$table_name = bar_featured_clicks_table();

	// Date-range filter.
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created <= %s', $end_date . ' 23:59:59');
	}

	$totals = $wpdb->get_row(
		"SELECT
			COUNT(*) AS total,
			SUM(source = 'web') AS web,
			SUM(source = 'app') AS app
		 FROM $table_name
		 WHERE $where"
	);

	$rows = $wpdb->get_results(
		"SELECT
			product_id,
			COUNT(*) AS total,
			SUM(source = 'web') AS web,
			SUM(source = 'app') AS app,
			MAX(created) AS last_click
		 FROM $table_name
		 WHERE $where
		 GROUP BY product_id
		 ORDER BY total DESC"
	);

	$export_url = add_query_arg(array(
		'action'     => 'bar_export_featured_clicks',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'_wpnonce'   => wp_create_nonce('bar_export_featured_clicks'),
	), admin_url('admin-ajax.php'));
	?>
	<div class="wrap">
		<h1>Featured Product Clicks</h1>

		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="sipn-stats" />
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=sipn-stats')); ?>">Reset</a>
			<?php } ?>
			<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
		</form>

		<?php if ($start_date !== '' || $end_date !== '') { ?>
			<p class="description">Showing
				<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
				<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
			</p>
		<?php } ?>

		<p>
			<strong>Total clicks:</strong> <?php echo (int) ($totals->total ?? 0); ?>
			&nbsp;|&nbsp; <strong>Web:</strong> <?php echo (int) ($totals->web ?? 0); ?>
			&nbsp;|&nbsp; <strong>App:</strong> <?php echo (int) ($totals->app ?? 0); ?>
		</p>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Product</th>
					<th>Product ID</th>
					<th>Total Clicks</th>
					<th>Web</th>
					<th>App</th>
					<th>Last Click</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="6">No clicks recorded yet.</td></tr>
				<?php } else {
					foreach ($rows as $row) {
						$title = get_the_title($row->product_id);
						if (!$title) {
							$title = '(deleted product)';
						}
						$edit_link = get_edit_post_link($row->product_id);
						?>
						<tr>
							<td>
								<?php if ($edit_link) { ?>
									<a href="<?php echo esc_url($edit_link); ?>"><?php echo esc_html($title); ?></a>
								<?php } else {
									echo esc_html($title);
								} ?>
							</td>
							<td><?php echo (int) $row->product_id; ?></td>
							<td><strong><?php echo (int) $row->total; ?></strong></td>
							<td><?php echo (int) $row->web; ?></td>
							<td><?php echo (int) $row->app; ?></td>
							<td><?php echo esc_html($row->last_click); ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * AJAX handler: exports Featured Clicks as an Excel-compatible CSV file.
 */
add_action('wp_ajax_bar_export_featured_clicks', 'bar_ajax_export_featured_clicks');
function bar_ajax_export_featured_clicks()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_featured_clicks');

	global $wpdb;
	$table_name = bar_featured_clicks_table();

	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created <= %s', $end_date . ' 23:59:59');
	}

	$rows = $wpdb->get_results(
		"SELECT
			product_id,
			COUNT(*) AS total,
			SUM(source = 'web') AS web,
			SUM(source = 'app') AS app,
			MAX(created) AS last_click
		 FROM $table_name
		 WHERE $where
		 GROUP BY product_id
		 ORDER BY total DESC",
		ARRAY_A
	);

	$filename = 'featured-clicks' . ($start_date ? '-' . $start_date : '') . ($end_date ? '-to-' . $end_date : '') . '.xls';

	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
	echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
	echo '<table border="1">';
	echo '<tr><th>Product</th><th>Product ID</th><th>Total Clicks</th><th>Web</th><th>App</th><th>Last Click</th></tr>';
	foreach ($rows as $row) {
		$title = get_the_title((int) $row['product_id']);
		if (!$title) {
			$title = '(deleted product)';
		}
		echo '<tr>';
		echo '<td>' . esc_html($title) . '</td>';
		echo '<td>' . (int) $row['product_id'] . '</td>';
		echo '<td>' . (int) $row['total'] . '</td>';
		echo '<td>' . (int) $row['web'] . '</td>';
		echo '<td>' . (int) $row['app'] . '</td>';
		echo '<td>' . esc_html($row['last_click']) . '</td>';
		echo '</tr>';
	}
	echo '</table></body></html>';
	exit;
}

/**
 * Renders the Buy Now clicks report in wp-admin.
 * Reads from the existing wp_event_tracking table (event_type = 'buy_now'),
 * which the Buy Now button already populates on every click (web and app).
 */
function bar_render_buynow_clicks_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'event_tracking';

	// Date-range filter.
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = "event_type = 'buy_now'";
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created_at >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created_at <= %s', $end_date . ' 23:59:59');
	}

	// Aggregate buy_now clicks per UPC, split web vs app/other.
	$rows = $wpdb->get_results(
		"SELECT
			upc,
			SUM(click_hit) AS total,
			SUM(CASE WHEN device_type = 'web' THEN click_hit ELSE 0 END) AS web,
			SUM(CASE WHEN device_type <> 'web' THEN click_hit ELSE 0 END) AS app,
			MAX(created_at) AS last_click
		 FROM $table_name
		 WHERE $where
		 GROUP BY upc
		 ORDER BY total DESC"
	);

	// Build a UPC -> product lookup from postmeta (productupc, '#' stripped to match tracking value).
	$upc_map = array();
	$meta_rows = $wpdb->get_results(
		"SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'productupc'"
	);
	foreach ($meta_rows as $m) {
		$clean = str_replace('#', '', $m->meta_value);
		if ($clean !== '') {
			$upc_map[$clean] = (int) $m->post_id;
		}
	}

	// Featured-product filter. ?featured=1 shows only currently featured products.
	$featured_only = (isset($_GET['featured']) && $_GET['featured'] === '1');
	$featured_ids = array();
	if (function_exists('wc_get_featured_product_ids')) {
		$featured_ids = array_map('intval', wc_get_featured_product_ids());
	}
	$featured_lookup = array_flip($featured_ids);

	// Apply the filter and compute totals from the visible rows only.
	$display_rows = array();
	$grand_total = 0;
	$grand_web = 0;
	$grand_app = 0;
	foreach ($rows as $row) {
		$product_id = isset($upc_map[$row->upc]) ? $upc_map[$row->upc] : 0;
		if ($featured_only && !isset($featured_lookup[$product_id])) {
			continue;
		}
		$row->_product_id = $product_id;
		$display_rows[] = $row;
		$grand_total += (int) $row->total;
		$grand_web   += (int) $row->web;
		$grand_app   += (int) $row->app;
	}

	$base_url = admin_url('admin.php?page=bar-buynow-clicks');
	?>
	<div class="wrap">
		<h1>Buy Now Clicks</h1>

		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="bar-buynow-clicks" />
			<?php if ($featured_only) { ?>
				<input type="hidden" name="featured" value="1" />
			<?php } ?>
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<a class="button" href="<?php echo esc_url(add_query_arg(array('page' => 'bar-buynow-clicks', 'featured' => $featured_only ? '1' : false), admin_url('admin.php'))); ?>">Reset</a>
			<?php } ?>
		</form>

		<?php if ($start_date !== '' || $end_date !== '') { ?>
			<p class="description">Showing
				<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
				<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
			</p>
		<?php } ?>

		<ul class="subsubsub">
			<li>
				<a href="<?php echo esc_url(add_query_arg(array('start_date' => $start_date ?: false, 'end_date' => $end_date ?: false), $base_url)); ?>" class="<?php echo $featured_only ? '' : 'current'; ?>">All products</a> |
			</li>
			<li>
				<a href="<?php echo esc_url(add_query_arg(array('featured' => '1', 'start_date' => $start_date ?: false, 'end_date' => $end_date ?: false), $base_url)); ?>" class="<?php echo $featured_only ? 'current' : ''; ?>">Featured only</a>
			</li>
		</ul>
		<p>
			&nbsp;|&nbsp; <strong><?php echo $featured_only ? 'Featured products — ' : ''; ?>Total clicks:</strong> <?php echo (int) $grand_total; ?>
			&nbsp;|&nbsp; <strong>Web:</strong> <?php echo (int) $grand_web; ?>
			&nbsp;|&nbsp; <strong>App/Other:</strong> <?php echo (int) $grand_app; ?>
		</p>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Product</th>
					<th>UPC</th>
					<th>Total Clicks</th>
					<th>Web</th>
					<th>App/Other</th>
					<th>Last Click</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($display_rows)) { ?>
					<tr><td colspan="6"><?php echo $featured_only ? 'No Buy Now clicks recorded for featured products yet.' : 'No Buy Now clicks recorded yet.'; ?></td></tr>
				<?php } else {
					foreach ($display_rows as $row) {
						$product_id = (int) $row->_product_id;
						$title = $product_id ? get_the_title($product_id) : '';
						if (!$title) {
							$title = '(unmatched UPC)';
						}
						$edit_link = $product_id ? get_edit_post_link($product_id) : '';
						?>
						<tr>
							<td>
								<?php if ($edit_link) { ?>
									<a href="<?php echo esc_url($edit_link); ?>"><?php echo esc_html($title); ?></a>
								<?php } else {
									echo esc_html($title);
								} ?>
							</td>
							<td><?php echo esc_html($row->upc); ?></td>
							<td><strong><?php echo (int) $row->total; ?></strong></td>
							<td><?php echo (int) $row->web; ?></td>
							<td><?php echo (int) $row->app; ?></td>
							<td><?php echo esc_html($row->last_click); ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/* =========================================================================
 * Push Notification Statistics
 * Reports on broadcast push-notification campaigns using the notification_log
 * table (populated by FCM::send_notification with the campaign post ID).
 * ========================================================================= */

/**
 * Ensures notification_log has the columns needed for per-campaign stats.
 * Adds notification_id and created_at if missing; creates the table if absent.
 */
function bar_init_notification_log_schema()
{
	if (get_option('bar_notification_log_version') === '1.0') {
		return;
	}

	global $wpdb;
	$table = 'notification_log';
	$charset_collate = $wpdb->get_charset_collate();

	if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
		$sql = "CREATE TABLE $table (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			device_id varchar(255) NOT NULL,
			user_id bigint(20) NOT NULL DEFAULT 0,
			device_type varchar(50) NOT NULL DEFAULT '',
			notification_id bigint(20) NOT NULL DEFAULT 0,
			created_at timestamp DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY notification_id (notification_id)
		) $charset_collate;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	} else {
		$cols = $wpdb->get_col("SHOW COLUMNS FROM `$table`");
		if (!in_array('notification_id', $cols)) {
			$wpdb->query("ALTER TABLE `$table` ADD COLUMN `notification_id` bigint(20) NOT NULL DEFAULT 0");
			$wpdb->query("ALTER TABLE `$table` ADD KEY `notification_id` (`notification_id`)");
		}
		if (!in_array('created_at', $cols)) {
			$wpdb->query("ALTER TABLE `$table` ADD COLUMN `created_at` timestamp DEFAULT CURRENT_TIMESTAMP");
		}
	}

	update_option('bar_notification_log_version', '1.0');
}
add_action('init', 'bar_init_notification_log_schema');

/**
 * Creates the notification_clicks table (records taps/opens of a push).
 */
function bar_init_notification_clicks_table()
{
	if (get_option('bar_notification_clicks_version') === '1.0') {
		return;
	}

	global $wpdb;
	$table = $wpdb->prefix . 'notification_clicks';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		notification_id bigint(20) NOT NULL DEFAULT 0,
		user_id bigint(20) NOT NULL DEFAULT 0,
		device_type varchar(50) NOT NULL DEFAULT '',
		created_at timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY notification_id (notification_id),
		KEY created_at (created_at)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('bar_notification_clicks_version', '1.0');
}
add_action('init', 'bar_init_notification_clicks_table');

/**
 * Returns the notification_clicks table name.
 */
function bar_notification_clicks_table()
{
	global $wpdb;
	return $wpdb->prefix . 'notification_clicks';
}

/**
 * REST callback: records a notification tap/open.
 * Accepts JSON { notification_id: int, device_type: "IOS"|"Android" }.
 * The app should call this when the user opens a push, passing the
 * notification_id it received in the FCM data payload.
 */
function handle_notification_click(WP_REST_Request $request)
{
	global $wpdb;

	$params = $request->get_json_params();
	if (empty($params)) {
		$params = $request->get_params();
	}

	$notification_id = isset($params['notification_id']) ? (int) $params['notification_id'] : 0;
	$device_type     = isset($params['device_type']) ? sanitize_text_field($params['device_type']) : '';

	if ($notification_id <= 0) {
		return new WP_REST_Response(array(
			'success' => false,
			'message' => 'A valid notification_id is required.',
		), 400);
	}

	$ok = $wpdb->insert(
		bar_notification_clicks_table(),
		array(
			'notification_id' => $notification_id,
			'user_id'         => (int) get_current_user_id(),
			'device_type'     => $device_type,
		),
		array('%d', '%d', '%s')
	);

	return new WP_REST_Response(array('success' => ($ok !== false)), ($ok !== false) ? 200 : 500);
}

add_action('rest_api_init', function () {
	register_rest_route('notifications/v2', '/click', array(
		'methods' => 'POST',
		'callback' => 'handle_notification_click',
		'permission_callback' => function ($request) {
			return true;
		}
	));
});

/**
 * Registers the Push Notifications report admin menu.
 */
function bar_push_stats_admin_menu()
{
	// Push Notifications is now registered as a submenu of the unified "Stats"
	// menu (see bar_featured_clicks_admin_menu). Intentionally left empty so we
	// don't create a duplicate top-level menu. The render callback
	// bar_render_push_stats_report() is still used by that submenu.
}
add_action('admin_menu', 'bar_push_stats_admin_menu');

/**
 * Renders the push-notification statistics report.
 */
function bar_render_push_stats_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$table = 'notification_log';

	// Date-range filter (inclusive). Expects YYYY-MM-DD.
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	// Broadcast campaigns are identified by platform device_type ('IOS'/'Android').
	// This includes historic rows sent before per-campaign tracking existed.
	$where = "device_type IN ('IOS', 'Android')";
	if ($start_date !== '') {
		$where .= $wpdb->prepare(" AND created_at >= %s", $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(" AND created_at <= %s", $end_date . ' 23:59:59');
	}

	// Overall totals across all broadcast sends.
	$totals = $wpdb->get_row(
		"SELECT
			COUNT(*) AS total_sent,
			COUNT(DISTINCT CASE WHEN notification_id > 0 THEN notification_id END) AS campaigns,
			COUNT(DISTINCT user_id) AS unique_users,
			SUM(CASE WHEN device_type = 'IOS' THEN 1 ELSE 0 END) AS ios,
			SUM(CASE WHEN device_type = 'Android' THEN 1 ELSE 0 END) AS android
		 FROM $table
		 WHERE $where"
	);

	// Per-notification (post-wise) breakdown.
	// Newer sends carry the exact campaign ID (notification_id). Historic sends
	// have notification_id = 0, so we attribute them to the push-notification post
	// that was published most recently before each send (matched by created_at).
	$posts = $wpdb->get_results(
		"SELECT ID, post_title, post_date
		 FROM {$wpdb->posts}
		 WHERE post_type = 'push-notification' AND post_status = 'publish'
		 ORDER BY post_date ASC"
	);

	// Build a CASE expression that maps each row to a campaign post ID.
	$title_map = array();
	if (!empty($posts)) {
		$pid_case = "CASE WHEN notification_id > 0 THEN notification_id ";
		$pid_case .= $wpdb->prepare("WHEN created_at < %s THEN 0 ", $posts[0]->post_date);
		$count = count($posts);
		for ($i = 1; $i < $count; $i++) {
			$pid_case .= $wpdb->prepare("WHEN created_at < %s THEN %d ", $posts[$i]->post_date, (int) $posts[$i - 1]->ID);
			$title_map[(int) $posts[$i - 1]->ID] = $posts[$i - 1]->post_title;
		}
		$pid_case .= $wpdb->prepare("ELSE %d END", (int) $posts[$count - 1]->ID);
		$title_map[(int) $posts[$count - 1]->ID] = $posts[$count - 1]->post_title;
	} else {
		$pid_case = "notification_id";
	}

	$rows = $wpdb->get_results(
		"SELECT
			($pid_case) AS pid,
			COUNT(*) AS total_sent,
			SUM(CASE WHEN device_type = 'IOS' THEN 1 ELSE 0 END) AS ios,
			SUM(CASE WHEN device_type = 'Android' THEN 1 ELSE 0 END) AS android,
			COUNT(DISTINCT user_id) AS unique_users,
			MIN(created_at) AS first_sent,
			MAX(created_at) AS last_sent,
			MAX(notification_id) AS has_exact
		 FROM $table
		 WHERE $where
		 GROUP BY pid
		 ORDER BY first_sent DESC"
	);

	// Number of distinct attributed campaigns (pid > 0).
	$campaigns_count = 0;
	foreach ($rows as $r) {
		if ((int) $r->pid > 0) {
			$campaigns_count++;
		}
	}

	// Notification clicks (taps/opens), keyed by notification_id. Respects the date filter.
	$clicks_table = bar_notification_clicks_table();
	$click_where = "notification_id > 0";
	if ($start_date !== '') {
		$click_where .= $wpdb->prepare(" AND created_at >= %s", $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$click_where .= $wpdb->prepare(" AND created_at <= %s", $end_date . ' 23:59:59');
	}

	$click_rows = $wpdb->get_results(
		"SELECT notification_id, COUNT(*) AS clicks
		 FROM $clicks_table
		 WHERE $click_where
		 GROUP BY notification_id"
	);
	$clicks_map = array();
	$total_clicks = 0;
	foreach ($click_rows as $cr) {
		$clicks_map[(int) $cr->notification_id] = (int) $cr->clicks;
		$total_clicks += (int) $cr->clicks;
	}

	$overall_ctr = ((int) ($totals->total_sent ?? 0) > 0)
		? round($total_clicks / (int) $totals->total_sent * 100, 1)
		: 0;

	$push_export_url = add_query_arg(array(
		'action'     => 'bar_export_push_stats',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'_wpnonce'   => wp_create_nonce('bar_export_push_stats'),
	), admin_url('admin-ajax.php'));
	?>
	<div class="wrap">
		<h1>Push Notification Statistics</h1>
		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="bar-push-stats" />
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=bar-push-stats')); ?>">Reset</a>
			<?php } ?>
			<a class="button" href="<?php echo esc_url($push_export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
		</form>
		<?php if ($start_date !== '' || $end_date !== '') { ?>
			<p class="description">Showing
				<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
				<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
			</p>
		<?php } ?>
		<h2>Overall</h2>
		<table class="wp-list-table widefat fixed striped" style="max-width:640px;">
			<tbody>
				<tr><td><strong>Total push notifications sent</strong></td><td><?php echo (int) ($totals->total_sent ?? 0); ?></td></tr>
				<tr><td>Total clicks</td><td><?php echo (int) $total_clicks; ?></td></tr>
				<tr><td>Click-through rate</td><td><?php echo esc_html($overall_ctr); ?>%</td></tr>
				<tr><td>Campaigns sent</td><td><?php echo (int) $campaigns_count; ?></td></tr>
				<tr><td>Unique users reached</td><td><?php echo (int) ($totals->unique_users ?? 0); ?></td></tr>
				<tr><td>iOS</td><td><?php echo (int) ($totals->ios ?? 0); ?></td></tr>
				<tr><td>Android</td><td><?php echo (int) ($totals->android ?? 0); ?></td></tr>
			</tbody>
		</table>

		<h2 style="margin-top:24px;">Per Notification</h2>
		<p class="description">
			Sends made before per-campaign tracking are attributed to the campaign published just before each send (matched by time, marked <em>approx.</em>).
			Newer sends use the exact campaign ID.
		</p>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Notification</th>
					<th>Sent (devices)</th>
					<th>iOS</th>
					<th>Android</th>
					<th>Unique Users</th>
					<th>Clicks</th>
					<th>CTR</th>
					<th>First Sent</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="8">No campaign push notifications recorded yet.</td></tr>
				<?php } else {
					foreach ($rows as $row) {
						$pid = (int) $row->pid;
						$approx = ((int) $row->has_exact === 0); // no exact ID present in this group
						$clicks = ($pid > 0 && isset($clicks_map[$pid])) ? $clicks_map[$pid] : 0;
						$ctr = ((int) $row->total_sent > 0) ? round($clicks / (int) $row->total_sent * 100, 1) : 0;
						if ($pid === 0) {
							$title = 'Unattributed (before first campaign)';
							$edit_link = '';
						} else {
							$title = isset($title_map[$pid]) ? $title_map[$pid] : get_the_title($pid);
							if (!$title) {
								$title = '(notification #' . $pid . ')';
							}
							$edit_link = get_edit_post_link($pid);
						}
						?>
						<tr>
							<td>
								<?php if ($edit_link) { ?>
									<a href="<?php echo esc_url($edit_link); ?>"><?php echo esc_html($title); ?></a>
								<?php } else {
									echo esc_html($title);
								} ?>
								<?php if ($pid > 0 && $approx) { ?>
									<em style="color:#888;">(approx.)</em>
								<?php } ?>
							</td>
							<td><strong><?php echo (int) $row->total_sent; ?></strong></td>
							<td><?php echo (int) $row->ios; ?></td>
							<td><?php echo (int) $row->android; ?></td>
							<td><?php echo (int) $row->unique_users; ?></td>
							<td><?php echo (int) $clicks; ?></td>
							<td><?php echo esc_html($ctr); ?>%</td>
							<td><?php echo esc_html($row->first_sent); ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * AJAX handler: exports Push Notification Stats as an Excel-compatible file.
 */
add_action('wp_ajax_bar_export_push_stats', 'bar_ajax_export_push_stats');
function bar_ajax_export_push_stats()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_push_stats');

	global $wpdb;
	$table = 'notification_log';

	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = "device_type IN ('IOS', 'Android')";
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created_at >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created_at <= %s', $end_date . ' 23:59:59');
	}

	$posts = $wpdb->get_results(
		"SELECT ID, post_title, post_date
		 FROM {$wpdb->posts}
		 WHERE post_type = 'push-notification' AND post_status = 'publish'
		 ORDER BY post_date ASC"
	);

	$title_map = array();
	if (!empty($posts)) {
		$pid_case = "CASE WHEN notification_id > 0 THEN notification_id ";
		$pid_case .= $wpdb->prepare("WHEN created_at < %s THEN 0 ", $posts[0]->post_date);
		$count = count($posts);
		for ($i = 1; $i < $count; $i++) {
			$pid_case .= $wpdb->prepare("WHEN created_at < %s THEN %d ", $posts[$i]->post_date, (int) $posts[$i - 1]->ID);
			$title_map[(int) $posts[$i - 1]->ID] = $posts[$i - 1]->post_title;
		}
		$pid_case .= $wpdb->prepare("ELSE %d END", (int) $posts[$count - 1]->ID);
		$title_map[(int) $posts[$count - 1]->ID] = $posts[$count - 1]->post_title;
	} else {
		$pid_case = "notification_id";
	}

	$rows = $wpdb->get_results(
		"SELECT
			($pid_case) AS pid,
			COUNT(*) AS total_sent,
			SUM(CASE WHEN device_type = 'IOS' THEN 1 ELSE 0 END) AS ios,
			SUM(CASE WHEN device_type = 'Android' THEN 1 ELSE 0 END) AS android,
			COUNT(DISTINCT user_id) AS unique_users,
			MIN(created_at) AS first_sent,
			MAX(notification_id) AS has_exact
		 FROM $table
		 WHERE $where
		 GROUP BY pid
		 ORDER BY first_sent DESC",
		ARRAY_A
	);

	$clicks_table = bar_notification_clicks_table();
	$click_where = "notification_id > 0";
	if ($start_date !== '') {
		$click_where .= $wpdb->prepare(' AND created_at >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$click_where .= $wpdb->prepare(' AND created_at <= %s', $end_date . ' 23:59:59');
	}
	$click_rows = $wpdb->get_results(
		"SELECT notification_id, COUNT(*) AS clicks FROM $clicks_table WHERE $click_where GROUP BY notification_id"
	);
	$clicks_map = array();
	foreach ($click_rows as $cr) {
		$clicks_map[(int) $cr->notification_id] = (int) $cr->clicks;
	}

	$filename = 'push-notification-stats' . ($start_date ? '-' . $start_date : '') . ($end_date ? '-to-' . $end_date : '') . '.xls';

	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
	echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
	echo '<table border="1">';
	echo '<tr><th>Notification</th><th>Sent (Devices)</th><th>iOS</th><th>Android</th><th>Unique Users</th><th>Clicks</th><th>CTR (%)</th><th>First Sent</th><th>Attribution</th></tr>';
	foreach ($rows as $row) {
		$pid    = (int) $row['pid'];
		$approx = ((int) $row['has_exact'] === 0);
		$clicks = ($pid > 0 && isset($clicks_map[$pid])) ? $clicks_map[$pid] : 0;
		$ctr    = ((int) $row['total_sent'] > 0) ? round($clicks / (int) $row['total_sent'] * 100, 1) : 0;
		if ($pid === 0) {
			$title = 'Unattributed (before first campaign)';
		} else {
			$title = isset($title_map[$pid]) ? $title_map[$pid] : get_the_title($pid);
			if (!$title) {
				$title = '(notification #' . $pid . ')';
			}
		}
		$attribution = ($pid > 0 && $approx) ? 'approx.' : 'exact';
		echo '<tr>';
		echo '<td>' . esc_html($title) . '</td>';
		echo '<td>' . (int) $row['total_sent'] . '</td>';
		echo '<td>' . (int) $row['ios'] . '</td>';
		echo '<td>' . (int) $row['android'] . '</td>';
		echo '<td>' . (int) $row['unique_users'] . '</td>';
		echo '<td>' . (int) $clicks . '</td>';
		echo '<td>' . esc_html($ctr . '%') . '</td>';
		echo '<td>' . esc_html($row['first_sent']) . '</td>';
		echo '<td>' . esc_html($attribution) . '</td>';
		echo '</tr>';
	}
	echo '</table></body></html>';
	exit;
}

/* =====================================================================
 * PRODUCT REQUESTS - user-submitted products pending admin review
 * Table: wp_product_requests
 * Endpoint (Android/iOS + Web): POST /wp-json/products/v2/request-add
 * Admin review panel: wp-admin -> "Product Requests"
 * ===================================================================== */

define('BAR_PRODUCT_REQUESTS_DB_VERSION', '1.0');

/**
 * Returns the product-requests table name.
 */
function bar_product_requests_table()
{
	global $wpdb;
	return $wpdb->prefix . 'product_requests';
}

/**
 * Creates the product-requests table if it does not exist yet.
 * Runs on every load but only executes dbDelta when the stored DB version
 * is out of date, so existing installs get the table without reactivating.
 */
function bar_init_product_requests_table()
{
	if (get_option('bar_product_requests_db_version') === BAR_PRODUCT_REQUESTS_DB_VERSION) {
		return;
	}

	global $wpdb;
	$table_name = bar_product_requests_table();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		product_name varchar(255) NOT NULL,
		product_description longtext NOT NULL,
		product_price varchar(50) DEFAULT NULL,
		product_image varchar(500) DEFAULT NULL,
		keyword varchar(255) DEFAULT NULL,
		submitted_by bigint(20) NOT NULL DEFAULT 0,
		source varchar(10) NOT NULL DEFAULT 'app',
		status varchar(20) NOT NULL DEFAULT 'pending',
		created_product_id bigint(20) NOT NULL DEFAULT 0,
		reviewed_by bigint(20) NOT NULL DEFAULT 0,
		reviewed_at datetime DEFAULT NULL,
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY status (status),
		KEY submitted_by (submitted_by)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('bar_product_requests_db_version', BAR_PRODUCT_REQUESTS_DB_VERSION);
}
add_action('init', 'bar_init_product_requests_table');

/**
 * Decodes a base64 image (with or without data-URI prefix) and stores it in
 * the WordPress uploads directory. Returns the public URL, or '' on failure.
 */
function bar_save_product_request_image($base64)
{
	if (empty($base64) || !is_string($base64)) {
		return '';
	}

	// Strip a data URI prefix like "data:image/png;base64,...."
	if (strpos($base64, 'base64,') !== false) {
		$base64 = substr($base64, strpos($base64, 'base64,') + 7);
	}
	$base64 = trim(str_replace(' ', '+', $base64));

	$imgdata = base64_decode($base64, true);
	if ($imgdata === false || strlen($imgdata) === 0) {
		return '';
	}

	$f = finfo_open();
	$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
	finfo_close($f);

	$allowed = array(
		'image/jpeg' => 'jpg',
		'image/jpg'  => 'jpg',
		'image/pjpeg' => 'jpg',
		'image/png'  => 'png',
		'image/webp' => 'webp',
		'image/gif'  => 'gif',
		'image/bmp'  => 'bmp',
	);
	if (!isset($allowed[$mime_type])) {
		return '';
	}
	$ext = $allowed[$mime_type];

	$uploaddir = wp_upload_dir();
	$filename = 'prodreq_' . time() . '_' . wp_rand(1000, 9999) . '.' . $ext;
	$path = trailingslashit($uploaddir['path']) . $filename;

	if (file_put_contents($path, $imgdata) === false) {
		return '';
	}

	return trailingslashit($uploaddir['url']) . $filename;
}

/**
 * Register the product-request submission endpoint.
 * Login is required (app sends a JWT, web sends the cookie + REST nonce).
 */
add_action('rest_api_init', function () {
	register_rest_route('products/v2', '/request-add', array(
		'methods' => 'POST',
		'callback' => 'handle_product_request_add',
		'permission_callback' => function ($request) {
			if (get_current_user_id() > 0) {
				return true;
			}
			return new WP_Error('rest_forbidden', 'You must be logged in to submit a product.', array('status' => 401));
		}
	));
});

/**
 * Handles a new product-request submission from app or web.
 *
 * Body (JSON): product_name (required), product_description (required),
 *              product_price (optional), product_image (optional base64),
 *              keyword (optional), source ('app'|'web', optional).
 */
function handle_product_request_add(WP_REST_Request $request)
{
	global $wpdb;

	$item = $request->get_json_params();
	if (empty($item)) {
		$item = $request->get_params();
	}

	$user_id = get_current_user_id();

	$name        = isset($item['product_name']) ? sanitize_text_field(trim($item['product_name'])) : '';
	$description = isset($item['product_description']) ? wp_kses_post(trim($item['product_description'])) : '';
	$price       = isset($item['product_price']) ? sanitize_text_field(trim($item['product_price'])) : '';
	$keyword     = isset($item['keyword']) ? sanitize_text_field(trim($item['keyword'])) : '';
	$image_b64   = isset($item['product_image']) ? $item['product_image'] : '';
	$source      = (isset($item['source']) && $item['source'] === 'web') ? 'web' : 'app';

	// Mandatory fields
	if ($name === '' || $description === '') {
		return new WP_REST_Response(array(
			'status'  => 'error',
			'message' => 'Product name and description are required.'
		), 400);
	}

	$table = bar_product_requests_table();

	// Duplicate guard 1: an existing published product with the same name
	$existing_product = $wpdb->get_var($wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' AND LOWER(post_title) = LOWER(%s) LIMIT 1",
		$name
	));
	if ($existing_product) {
		return new WP_REST_Response(array(
			'status'  => 'error',
			'message' => 'A product with this name already exists.'
		), 409);
	}

	// Duplicate guard 2: an identical request already awaiting review
	$existing_pending = $wpdb->get_var($wpdb->prepare(
		"SELECT id FROM $table WHERE status = 'pending' AND LOWER(product_name) = LOWER(%s) LIMIT 1",
		$name
	));
	if ($existing_pending) {
		return new WP_REST_Response(array(
			'status'  => 'error',
			'message' => 'This product has already been submitted and is awaiting review.'
		), 409);
	}

	// Optional image
	$image_url = '';
	if (!empty($image_b64)) {
		$image_url = bar_save_product_request_image($image_b64);
	}

	$wpdb->insert(
		$table,
		array(
			'product_name'        => $name,
			'product_description' => $description,
			'product_price'       => $price !== '' ? $price : null,
			'product_image'       => $image_url !== '' ? $image_url : null,
			'keyword'             => $keyword !== '' ? $keyword : null,
			'submitted_by'        => $user_id,
			'source'              => $source,
			'status'              => 'pending',
		),
		array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s')
	);

	$request_id = $wpdb->insert_id;

	if (!$request_id) {
		return new WP_REST_Response(array(
			'status'  => 'error',
			'message' => 'Could not save your submission. Please try again.'
		), 500);
	}

	return new WP_REST_Response(array(
		'status'     => 'success',
		'message'    => 'Your product ' . $name . ' has been added',
		'request_id' => (int) $request_id,
	), 200);
}

/* ------------------------- ADMIN REVIEW PANEL ------------------------- */

/**
 * Registers the "Product Requests" admin menu.
 */
function bar_product_requests_admin_menu()
{
	global $wpdb;
	$table = bar_product_requests_table();
	$pending = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'pending'");

	$menu_title = 'Product Requests';
	if ($pending > 0) {
		$menu_title .= ' <span class="awaiting-mod">' . $pending . '</span>';
	}

	add_menu_page(
		'Product Requests',
		$menu_title,
		'manage_options',
		'bar-product-requests',
		'bar_render_product_requests',
		'dashicons-plus-alt',
		57
	);
}
add_action('admin_menu', 'bar_product_requests_admin_menu');

/**
 * Approve handler: creates a DRAFT WooCommerce product from the request,
 * attaches the image (if any), and marks the request approved.
 */
add_action('admin_post_bar_approve_product_request', 'bar_handle_approve_product_request');
function bar_handle_approve_product_request()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	$request_id = isset($_POST['request_id']) ? (int) $_POST['request_id'] : 0;
	check_admin_referer('bar_product_request_' . $request_id);

	global $wpdb;
	$table = bar_product_requests_table();
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $request_id));

	if (!$row || $row->status !== 'pending') {
		wp_safe_redirect(add_query_arg(array('page' => 'bar-product-requests', 'msg' => 'invalid'), admin_url('admin.php')));
		exit;
	}

	$product_id = 0;
	if (function_exists('wc_get_product') && class_exists('WC_Product_Simple')) {
		$product = new WC_Product_Simple();
		$product->set_name($row->product_name);
		$product->set_description($row->product_description);
		$product->set_status('draft');
		if ($row->product_price !== null && $row->product_price !== '' && is_numeric($row->product_price)) {
			$product->set_regular_price((string) $row->product_price);
		}
		$product_id = $product->save();
	} else {
		// Fallback: plain product post as draft
		$product_id = wp_insert_post(array(
			'post_title'   => $row->product_name,
			'post_content' => $row->product_description,
			'post_status'  => 'draft',
			'post_type'    => 'product',
		));
	}

	if ($product_id && !empty($row->product_image)) {
		$attach_id = bar_attach_image_url_to_product($row->product_image, $product_id);
		if ($attach_id) {
			set_post_thumbnail($product_id, $attach_id);
		}
	}

	$wpdb->update(
		$table,
		array(
			'status'             => 'approved',
			'created_product_id' => (int) $product_id,
			'reviewed_by'        => get_current_user_id(),
			'reviewed_at'        => current_time('mysql'),
		),
		array('id' => $request_id),
		array('%s', '%d', '%d', '%s'),
		array('%d')
	);

	wp_safe_redirect(add_query_arg(array('page' => 'bar-product-requests', 'msg' => 'approved'), admin_url('admin.php')));
	exit;
}

/**
 * Reject handler: marks the request rejected. No product is created.
 */
add_action('admin_post_bar_reject_product_request', 'bar_handle_reject_product_request');
function bar_handle_reject_product_request()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	$request_id = isset($_POST['request_id']) ? (int) $_POST['request_id'] : 0;
	check_admin_referer('bar_product_request_' . $request_id);

	global $wpdb;
	$table = bar_product_requests_table();
	$wpdb->update(
		$table,
		array(
			'status'      => 'rejected',
			'reviewed_by' => get_current_user_id(),
			'reviewed_at' => current_time('mysql'),
		),
		array('id' => $request_id, 'status' => 'pending'),
		array('%s', '%d', '%s'),
		array('%d', '%s')
	);

	wp_safe_redirect(add_query_arg(array('page' => 'bar-product-requests', 'msg' => 'rejected'), admin_url('admin.php')));
	exit;
}

/**
 * Creates an attachment from an image URL that already lives in the uploads
 * directory and returns the attachment ID (0 on failure).
 */
function bar_attach_image_url_to_product($image_url, $post_id)
{
	$uploaddir = wp_upload_dir();
	$file_path = str_replace($uploaddir['baseurl'], $uploaddir['basedir'], $image_url);

	if (!file_exists($file_path)) {
		return 0;
	}

	$filetype = wp_check_filetype(basename($file_path), null);
	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $image_url,
	);

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
	if (is_wp_error($attach_id) || !$attach_id) {
		return 0;
	}
	$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
	wp_update_attachment_metadata($attach_id, $attach_data);

	return $attach_id;
}

/**
 * Renders the Product Requests review screen.
 */
function bar_render_product_requests()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$table = bar_product_requests_table();

	$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'pending';
	if (!in_array($status, array('pending', 'approved', 'rejected', 'all'), true)) {
		$status = 'pending';
	}

	$counts = $wpdb->get_row("SELECT
		SUM(status = 'pending') AS pending,
		SUM(status = 'approved') AS approved,
		SUM(status = 'rejected') AS rejected,
		COUNT(*) AS total
		FROM $table");

	if ($status === 'all') {
		$rows = $wpdb->get_results("SELECT * FROM $table ORDER BY created DESC");
	} else {
		$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE status = %s ORDER BY created DESC", $status));
	}

	$msg = isset($_GET['msg']) ? sanitize_text_field($_GET['msg']) : '';
	?>
	<div class="wrap">
		<h1>Product Requests</h1>

		<?php if ($msg === 'approved') { ?>
			<div class="notice notice-success is-dismissible"><p>Request approved. A <strong>draft</strong> product was created &mdash; review and publish it from the Products screen.</p></div>
		<?php } elseif ($msg === 'rejected') { ?>
			<div class="notice notice-warning is-dismissible"><p>Request rejected.</p></div>
		<?php } elseif ($msg === 'invalid') { ?>
			<div class="notice notice-error is-dismissible"><p>That request could not be processed (already reviewed or not found).</p></div>
		<?php } ?>

		<ul class="subsubsub">
			<li><a href="<?php echo esc_url(add_query_arg(array('page' => 'bar-product-requests', 'status' => 'pending'), admin_url('admin.php'))); ?>" class="<?php echo $status === 'pending' ? 'current' : ''; ?>">Pending <span class="count">(<?php echo (int) ($counts->pending ?? 0); ?>)</span></a> |</li>
			<li><a href="<?php echo esc_url(add_query_arg(array('page' => 'bar-product-requests', 'status' => 'approved'), admin_url('admin.php'))); ?>" class="<?php echo $status === 'approved' ? 'current' : ''; ?>">Approved <span class="count">(<?php echo (int) ($counts->approved ?? 0); ?>)</span></a> |</li>
			<li><a href="<?php echo esc_url(add_query_arg(array('page' => 'bar-product-requests', 'status' => 'rejected'), admin_url('admin.php'))); ?>" class="<?php echo $status === 'rejected' ? 'current' : ''; ?>">Rejected <span class="count">(<?php echo (int) ($counts->rejected ?? 0); ?>)</span></a> |</li>
			<li><a href="<?php echo esc_url(add_query_arg(array('page' => 'bar-product-requests', 'status' => 'all'), admin_url('admin.php'))); ?>" class="<?php echo $status === 'all' ? 'current' : ''; ?>">All <span class="count">(<?php echo (int) ($counts->total ?? 0); ?>)</span></a></li>
		</ul>

		<table class="wp-list-table widefat fixed striped" style="margin-top:10px;">
			<thead>
				<tr>
					<th style="width:70px;">Image</th>
					<th>Product Name</th>
					<th>Description</th>
					<th style="width:80px;">Price</th>
					<th style="width:130px;">Searched For</th>
					<th style="width:150px;">Submitted By</th>
					<th style="width:90px;">Source</th>
					<th style="width:150px;">Submitted</th>
					<th style="width:90px;">Status</th>
					<th style="width:170px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="10">No requests in this view.</td></tr>
				<?php } else {
					foreach ($rows as $row) {
						$user = $row->submitted_by ? get_userdata($row->submitted_by) : null;
						$user_label = $user ? $user->display_name . ' (' . $user->user_email . ')' : 'User #' . (int) $row->submitted_by;
						?>
						<tr>
							<td>
								<?php if (!empty($row->product_image)) { ?>
									<a href="<?php echo esc_url($row->product_image); ?>" target="_blank"><img src="<?php echo esc_url($row->product_image); ?>" style="width:56px;height:56px;object-fit:cover;border-radius:4px;"></a>
								<?php } else { echo '&mdash;'; } ?>
							</td>
							<td><strong><?php echo esc_html($row->product_name); ?></strong></td>
							<td><?php echo esc_html(wp_trim_words(wp_strip_all_tags($row->product_description), 25)); ?></td>
							<td><?php echo $row->product_price !== null && $row->product_price !== '' ? '$' . esc_html($row->product_price) : '&mdash;'; ?></td>
							<td><?php echo $row->keyword ? esc_html($row->keyword) : '&mdash;'; ?></td>
							<td><?php echo esc_html($user_label); ?></td>
							<td><?php echo esc_html(strtoupper($row->source)); ?></td>
							<td><?php echo esc_html($row->created); ?></td>
							<td>
								<?php
								$badge_color = array('pending' => '#b26a00', 'approved' => '#1a7f37', 'rejected' => '#b32d2e');
								$c = $badge_color[$row->status] ?? '#555';
								?>
								<span style="color:#fff;background:<?php echo $c; ?>;padding:3px 8px;border-radius:10px;font-size:11px;text-transform:uppercase;"><?php echo esc_html($row->status); ?></span>
							</td>
							<td>
								<?php if ($row->status === 'pending') { ?>
									<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;">
										<input type="hidden" name="action" value="bar_approve_product_request">
										<input type="hidden" name="request_id" value="<?php echo (int) $row->id; ?>">
										<?php wp_nonce_field('bar_product_request_' . $row->id); ?>
										<button type="submit" class="button button-primary" onclick="return confirm('Approve and create a draft product?');">Approve</button>
									</form>
									<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;">
										<input type="hidden" name="action" value="bar_reject_product_request">
										<input type="hidden" name="request_id" value="<?php echo (int) $row->id; ?>">
										<?php wp_nonce_field('bar_product_request_' . $row->id); ?>
										<button type="submit" class="button" onclick="return confirm('Reject this request?');">Reject</button>
									</form>
								<?php } elseif ($row->status === 'approved' && $row->created_product_id) {
									$edit_link = get_edit_post_link($row->created_product_id);
									if ($edit_link) { ?>
										<a class="button" href="<?php echo esc_url($edit_link); ?>">Edit draft product</a>
									<?php } else { echo '&mdash;'; }
								} else { echo '&mdash;'; } ?>
							</td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}


/* =====================================================================
 * FEATURED PRODUCT VIEWS (impressions) tracking
 * Table: wp_bar_featured_views
 * Endpoint: POST /wp-json/products/v2/featured-view
 *   body: { product_id: int, source: "web"|"app" }
 *      or { product_ids: [int, ...], source: "web"|"app" }
 * Admin report: wp-admin -> Featured Clicks -> Featured Views
 * ===================================================================== */

define('BAR_FEATURED_VIEWS_DB_VERSION', '1.0');

/**
 * Returns the featured-views table name.
 */
function bar_featured_views_table()
{
	global $wpdb;
	return $wpdb->prefix . 'bar_featured_views';
}

/**
 * Creates the featured-views table if it does not exist yet.
 */
function bar_init_featured_views_table()
{
	if (get_option('bar_featured_views_db_version') === BAR_FEATURED_VIEWS_DB_VERSION) {
		return;
	}

	global $wpdb;
	$table_name = bar_featured_views_table();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		product_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL DEFAULT 0,
		source varchar(10) NOT NULL DEFAULT 'web',
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY product_id (product_id),
		KEY created (created)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	update_option('bar_featured_views_db_version', BAR_FEATURED_VIEWS_DB_VERSION);
}
add_action('init', 'bar_init_featured_views_table');

/**
 * Inserts a featured-product view (impression) into the tracking table.
 *
 * @param int    $product_id The viewed product ID.
 * @param string $source     'web' or 'app'.
 * @param int    $user_id    Logged-in user ID, 0 for guests.
 * @return bool True on success.
 */
function bar_record_featured_view($product_id, $source = 'web', $user_id = 0)
{
	global $wpdb;

	$product_id = (int) $product_id;
	if ($product_id <= 0) {
		return false;
	}

	$source = ($source === 'app') ? 'app' : 'web';

	$inserted = $wpdb->insert(
		bar_featured_views_table(),
		array(
			'product_id' => $product_id,
			'user_id'    => (int) $user_id,
			'source'     => $source,
		),
		array('%d', '%d', '%s')
	);

	return $inserted !== false;
}

/**
 * REST callback: records one or more featured-product views.
 * Accepts JSON { product_id: int } or { product_ids: [int, ...] }, plus optional source.
 */
function handle_featured_view(WP_REST_Request $request)
{
	$params = $request->get_json_params();
	if (empty($params)) {
		$params = $request->get_params();
	}

	$source = isset($params['source']) ? sanitize_text_field($params['source']) : 'web';

	$ids = array();
	if (isset($params['product_ids']) && is_array($params['product_ids'])) {
		foreach ($params['product_ids'] as $pid) {
			$pid = (int) $pid;
			if ($pid > 0) {
				$ids[] = $pid;
			}
		}
	}
	if (isset($params['product_id']) && (int) $params['product_id'] > 0) {
		$ids[] = (int) $params['product_id'];
	}
	$ids = array_values(array_unique($ids));

	if (empty($ids)) {
		return new WP_REST_Response(array(
			'success' => false,
			'message' => 'A valid product_id is required.',
		), 400);
	}

	$user_id = get_current_user_id();
	$recorded = 0;
	foreach ($ids as $pid) {
		if (bar_record_featured_view($pid, $source, $user_id)) {
			$recorded++;
		}
	}

	return new WP_REST_Response(array(
		'success'  => $recorded > 0,
		'recorded' => $recorded,
	), $recorded > 0 ? 200 : 500);
}

add_action('rest_api_init', function () {
	register_rest_route('products/v2', '/featured-view', array(
		'methods' => 'POST',
		'callback' => 'handle_featured_view',
		'permission_callback' => function ($request) {
			return true;
		}
	));
});

/**
 * Adds the "Featured Views" report as a submenu under the existing
 * "Featured Clicks" menu. Registered on a later priority so the parent
 * menu (priority 10) already exists.
 */
// Featured Views is now registered as a submenu of the unified "Stats" menu
// (see bar_featured_clicks_admin_menu). This hook is intentionally left as a
// no-op to avoid a duplicate submenu entry.
add_action('admin_menu', function () {
}, 11);

/**
 * Renders the Featured Views report: per-product impressions, clicks and CTR.
 */
function bar_render_featured_views_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$views_table  = bar_featured_views_table();
	$clicks_table = bar_featured_clicks_table();

	// Date-range filter.
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND v.created >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND v.created <= %s', $end_date . ' 23:59:59');
	}

	$totals_where = str_replace('v.created', 'created', $where);
	$totals = $wpdb->get_row(
		"SELECT
			COUNT(*) AS total,
			SUM(source = 'web') AS web,
			SUM(source = 'app') AS app
		 FROM $views_table
		 WHERE $totals_where"
	);

	$rows = $wpdb->get_results(
		"SELECT
			v.product_id,
			COUNT(*) AS views,
			SUM(v.source = 'web') AS web,
			SUM(v.source = 'app') AS app,
			MAX(v.created) AS last_view,
			(SELECT COUNT(*) FROM $clicks_table c WHERE c.product_id = v.product_id) AS clicks
		 FROM $views_table v
		 WHERE $where
		 GROUP BY v.product_id
		 ORDER BY views DESC"
	);

	$export_url = add_query_arg(array(
		'action'     => 'bar_export_featured_views',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'_wpnonce'   => wp_create_nonce('bar_export_featured_views'),
	), admin_url('admin-ajax.php'));
	?>
	<div class="wrap">
		<h1>Featured Product Views</h1>

		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="sipn-stats" />
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=sipn-stats')); ?>">Reset</a>
			<?php } ?>
			<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
		</form>

		<?php if ($start_date !== '' || $end_date !== '') { ?>
			<p class="description">Showing
				<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
				<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
			</p>
		<?php } ?>

		<p>
			<strong>Total views:</strong> <?php echo (int) ($totals->total ?? 0); ?>
			&nbsp;|&nbsp; <strong>Web:</strong> <?php echo (int) ($totals->web ?? 0); ?>
			&nbsp;|&nbsp; <strong>App:</strong> <?php echo (int) ($totals->app ?? 0); ?>
		</p>
		<p class="description">A view is recorded the first time a featured product becomes visible in the carousel (once per page load). CTR = clicks &divide; views.</p>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Product</th>
					<th>Product ID</th>
					<th>Views</th>
					<th>Clicks</th>
					<th>CTR</th>
					<th>Web</th>
					<th>App</th>
					<th>Last Viewed</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="8">No views recorded yet.</td></tr>
				<?php } else {
					foreach ($rows as $row) {
						$title = get_the_title($row->product_id);
						if (!$title) {
							$title = '(deleted product)';
						}
						$edit_link = get_edit_post_link($row->product_id);
						$views = (int) $row->views;
						$clicks = (int) $row->clicks;
						$ctr = $views > 0 ? round(($clicks / $views) * 100, 1) . '%' : '&mdash;';
						?>
						<tr>
							<td>
								<?php if ($edit_link) { ?>
									<a href="<?php echo esc_url($edit_link); ?>"><?php echo esc_html($title); ?></a>
								<?php } else {
									echo esc_html($title);
								} ?>
							</td>
							<td><?php echo (int) $row->product_id; ?></td>
							<td><strong><?php echo $views; ?></strong></td>
							<td><?php echo $clicks; ?></td>
							<td><?php echo $ctr; ?></td>
							<td><?php echo (int) $row->web; ?></td>
							<td><?php echo (int) $row->app; ?></td>
							<td><?php echo esc_html($row->last_view); ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * AJAX handler: exports Featured Views as an Excel-compatible file.
 */
add_action('wp_ajax_bar_export_featured_views', 'bar_ajax_export_featured_views');
function bar_ajax_export_featured_views()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_featured_views');

	global $wpdb;
	$views_table  = bar_featured_views_table();
	$clicks_table = bar_featured_clicks_table();

	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND v.created >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND v.created <= %s', $end_date . ' 23:59:59');
	}

	$rows = $wpdb->get_results(
		"SELECT
			v.product_id,
			COUNT(*) AS views,
			SUM(v.source = 'web') AS web,
			SUM(v.source = 'app') AS app,
			MAX(v.created) AS last_view,
			(SELECT COUNT(*) FROM $clicks_table c WHERE c.product_id = v.product_id) AS clicks
		 FROM $views_table v
		 WHERE $where
		 GROUP BY v.product_id
		 ORDER BY views DESC",
		ARRAY_A
	);

	$filename = 'featured-views' . ($start_date ? '-' . $start_date : '') . ($end_date ? '-to-' . $end_date : '') . '.xls';

	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
	echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
	echo '<table border="1">';
	echo '<tr><th>Product</th><th>Product ID</th><th>Views</th><th>Clicks</th><th>CTR (%)</th><th>Web</th><th>App</th><th>Last Viewed</th></tr>';
	foreach ($rows as $row) {
		$title = get_the_title((int) $row['product_id']);
		if (!$title) {
			$title = '(deleted product)';
		}
		$views_val  = (int) $row['views'];
		$clicks_val = (int) $row['clicks'];
		$ctr = $views_val > 0 ? round(($clicks_val / $views_val) * 100, 1) : 0;
		echo '<tr>';
		echo '<td>' . esc_html($title) . '</td>';
		echo '<td>' . (int) $row['product_id'] . '</td>';
		echo '<td>' . $views_val . '</td>';
		echo '<td>' . $clicks_val . '</td>';
		echo '<td>' . esc_html($ctr . '%') . '</td>';
		echo '<td>' . (int) $row['web'] . '</td>';
		echo '<td>' . (int) $row['app'] . '</td>';
		echo '<td>' . esc_html($row['last_view']) . '</td>';
		echo '</tr>';
	}
	echo '</table></body></html>';
	exit;
}

/**
 * STATS: Sponsored Ads
 * Reports on impressions, clicks, CTR, and actiontypes for sponsored ads
 * using the wp_sponsored_ad_clicks table.
 */
function bar_render_sponsored_ads_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	global $wpdb;
	$ads_table    = $wpdb->prefix . 'sponsored_ads';
	$clicks_table = 'wp_sponsored_ad_clicks';

	// Date-range filter.
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';
	$spons_id   = isset($_GET['spons_id']) ? (int) $_GET['spons_id'] : 0;

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created_at >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created_at <= %s', $end_date . ' 23:59:59');
	}

	$export_url = add_query_arg(array(
		'action'     => 'bar_export_sponsored_ads',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'spons_id'   => $spons_id ?: false,
		'_wpnonce'   => wp_create_nonce('bar_export_sponsored_ads'),
	), admin_url('admin-ajax.php'));

	if ($spons_id > 0) {
		// Drill-down View
		$ad = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ads_table WHERE id = %d", $spons_id));
		if (!$ad) {
			echo '<div class="wrap"><div class="notice notice-error"><p>Sponsored Ad not found.</p></div></div>';
			return;
		}

		$rows = $wpdb->get_results(
			$wpdb->prepare("
				SELECT
					DATE(created_at) AS ActivityDate,
					COALESCE(SUM(CASE WHEN actiontype = 'View' THEN 1 END), 0) AS ViewCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Like' THEN 1 END), 0) AS LikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Click' THEN 1 END), 0) AS ClickCount,
					COALESCE(SUM(CASE WHEN actiontype = 'BuyNow' THEN 1 END), 0) AS BuyNowCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Share' THEN 1 END), 0) AS ShareCount,
					COALESCE(SUM(CASE WHEN actiontype = 'ProductLink' THEN 1 END), 0) AS ProductLinkCount,
					COALESCE(SUM(CASE WHEN actiontype = 'UnLike' THEN 1 END), 0) AS UnLikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Comment' THEN 1 END), 0) AS CommentCount
				FROM $clicks_table
				WHERE spons_id = %d
				AND $where
				GROUP BY DATE(created_at)
				ORDER BY ActivityDate DESC
			", $spons_id)
		);

		// Calculate grand totals for this specific ad
		$grand_totals = $wpdb->get_row(
			$wpdb->prepare("
				SELECT
					COALESCE(SUM(CASE WHEN actiontype = 'View' THEN 1 END), 0) AS ViewCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Like' THEN 1 END), 0) AS LikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Click' THEN 1 END), 0) AS ClickCount,
					COALESCE(SUM(CASE WHEN actiontype = 'BuyNow' THEN 1 END), 0) AS BuyNowCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Share' THEN 1 END), 0) AS ShareCount,
					COALESCE(SUM(CASE WHEN actiontype = 'ProductLink' THEN 1 END), 0) AS ProductLinkCount,
					COALESCE(SUM(CASE WHEN actiontype = 'UnLike' THEN 1 END), 0) AS UnLikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Comment' THEN 1 END), 0) AS CommentCount
				FROM $clicks_table
				WHERE spons_id = %d
				AND $where
			", $spons_id)
		);
		?>
		<div class="wrap">
			<h1>Sponsored Ad Daily Statistics: <?php echo esc_html($ad->company_name); ?></h1>
			<p><a href="<?php echo esc_url(admin_url('admin.php?page=bar-sponsored-ads')); ?>" class="button">&larr; Back to All Ads</a></p>

			<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
				<input type="hidden" name="page" value="bar-sponsored-ads" />
				<input type="hidden" name="spons_id" value="<?php echo (int) $spons_id; ?>" />
				<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
				<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
				<button type="submit" class="button button-primary">Filter</button>
				<?php if ($start_date !== '' || $end_date !== '') { ?>
					<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=bar-sponsored-ads&spons_id=' . $spons_id)); ?>">Reset</a>
				<?php } ?>
				<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
			</form>

			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<p class="description">Showing
					<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
					<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
				</p>
			<?php } ?>

			<div style="margin: 15px 0; background: #fff; border: 1px solid #ccd0d4; padding: 15px; border-radius: 4px; display: flex; gap: 20px; align-items: center;">
				<?php if (!empty($ad->company_logo)) { ?>
					<img src="<?php echo esc_url($ad->company_logo); ?>" style="max-height:60px; max-width: 120px;" />
				<?php } ?>
				<div>
					<strong>Company:</strong> <?php echo esc_html($ad->company_name); ?><br>
					<strong>Status:</strong> <?php echo (int) $ad->status === 0 ? '<span style="color:#1a7f37;">Active</span>' : '<span style="color:#8c8f94;">Inactive</span>'; ?><br>
					<strong>End Date:</strong> <?php echo !empty($ad->end_date) ? esc_html(date('M j, Y', strtotime($ad->end_date))) : 'No End Date'; ?>
				</div>
			</div>

			<p>
				<strong>Total Views:</strong> <?php echo (int) $grand_totals->ViewCount; ?>
				&nbsp;|&nbsp; <strong>Total Clicks:</strong> <?php echo (int) $grand_totals->ClickCount; ?>
				&nbsp;|&nbsp; <strong>Buy Now:</strong> <?php echo (int) $grand_totals->BuyNowCount; ?>
				&nbsp;|&nbsp; <strong>Likes:</strong> <?php echo (int) $grand_totals->LikeCount; ?>
				&nbsp;|&nbsp; <strong>Comments:</strong> <?php echo (int) $grand_totals->CommentCount; ?>
			</p>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>Date</th>
						<th>Views</th>
						<th>Clicks</th>
						<th>Buy Now</th>
						<th>Likes</th>
						<th>Comments</th>
						<th>Shares</th>
						<th>Product Links</th>
						<th>Unlikes</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($rows)) { ?>
						<tr><td colspan="9">No stats recorded for this ad in the selected date range.</td></tr>
					<?php } else {
						foreach ($rows as $row) { ?>
							<tr>
								<td><strong><?php echo esc_html($row->ActivityDate); ?></strong></td>
								<td><?php echo (int) $row->ViewCount; ?></td>
								<td><?php echo (int) $row->ClickCount; ?></td>
								<td><?php echo (int) $row->BuyNowCount; ?></td>
								<td><?php echo (int) $row->LikeCount; ?></td>
								<td><?php echo (int) $row->CommentCount; ?></td>
								<td><?php echo (int) $row->ShareCount; ?></td>
								<td><?php echo (int) $row->ProductLinkCount; ?></td>
								<td><?php echo (int) $row->UnLikeCount; ?></td>
							</tr>
						<?php }
					} ?>
				</tbody>
			</table>
		</div>
		<?php
	} else {
		// Overview of All Ads
		$ads = $wpdb->get_results("SELECT * FROM $ads_table ORDER BY id DESC");

		// Get aggregate stats for all ads
		$stats_rows = $wpdb->get_results("
			SELECT
				spons_id,
				COALESCE(SUM(CASE WHEN actiontype = 'View' THEN 1 END), 0) AS ViewCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Like' THEN 1 END), 0) AS LikeCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Click' THEN 1 END), 0) AS ClickCount,
				COALESCE(SUM(CASE WHEN actiontype = 'BuyNow' THEN 1 END), 0) AS BuyNowCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Share' THEN 1 END), 0) AS ShareCount,
				COALESCE(SUM(CASE WHEN actiontype = 'ProductLink' THEN 1 END), 0) AS ProductLinkCount,
				COALESCE(SUM(CASE WHEN actiontype = 'UnLike' THEN 1 END), 0) AS UnLikeCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Comment' THEN 1 END), 0) AS CommentCount
			FROM $clicks_table
			WHERE $where
			GROUP BY spons_id
		");

		$stats_by_ad = array();
		foreach ($stats_rows as $row) {
			$stats_by_ad[$row->spons_id] = $row;
		}
		?>
		<div class="wrap">
			<h1>Sponsored Ads Stats</h1>

			<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
				<input type="hidden" name="page" value="bar-sponsored-ads" />
				<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
				<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
				<button type="submit" class="button button-primary">Filter</button>
				<?php if ($start_date !== '' || $end_date !== '') { ?>
					<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=bar-sponsored-ads')); ?>">Reset</a>
				<?php } ?>
				<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
			</form>

			<?php if ($start_date !== '' || $end_date !== '') { ?>
				<p class="description">Showing
					<?php echo $start_date !== '' ? 'from ' . esc_html($start_date) : 'up to'; ?>
					<?php echo $end_date !== '' ? ' to ' . esc_html($end_date) : ($start_date !== '' ? ' onward' : 'all dates'); ?>.
				</p>
			<?php } ?>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th style="width: 50px;">ID</th>
						<th>Ad Banner</th>
						<th>Company Name</th>
						<th>Views</th>
						<th>Clicks</th>
						<th>Buy Now</th>
						<th>Likes</th>
						<th>Comments</th>
						<th>CTR</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($ads)) { ?>
						<tr><td colspan="10">No sponsored ads found.</td></tr>
					<?php } else {
						foreach ($ads as $ad) {
							$ad_stats = isset($stats_by_ad[$ad->id]) ? $stats_by_ad[$ad->id] : null;
							$views = $ad_stats ? (int) $ad_stats->ViewCount : 0;
							$clicks = $ad_stats ? (int) $ad_stats->ClickCount : 0;
							$buynow = $ad_stats ? (int) $ad_stats->BuyNowCount : 0;
							$likes = $ad_stats ? (int) $ad_stats->LikeCount : 0;
							$comments = $ad_stats ? (int) $ad_stats->CommentCount : 0;
							$ctr = $views > 0 ? round(($clicks / $views) * 100, 1) . '%' : '&mdash;';
							
							$drill_url = admin_url('admin.php?page=bar-sponsored-ads&spons_id=' . (int) $ad->id);
							if ($start_date !== '') {
								$drill_url = add_query_arg('start_date', $start_date, $drill_url);
							}
							if ($end_date !== '') {
								$drill_url = add_query_arg('end_date', $end_date, $drill_url);
							}
							?>
							<tr>
								<td><?php echo (int) $ad->id; ?></td>
								<td>
									<?php if (!empty($ad->image)) { ?>
										<img src="<?php echo esc_url($ad->image); ?>" style="max-height: 40px; max-width: 80px;" />
									<?php } else {
										echo '&mdash;';
									} ?>
								</td>
								<td><strong><?php echo esc_html($ad->company_name); ?></strong></td>
								<td><?php echo $views; ?></td>
								<td><?php echo $clicks; ?></td>
								<td><?php echo $buynow; ?></td>
								<td><?php echo $likes; ?></td>
								<td><?php echo $comments; ?></td>
								<td><strong><?php echo $ctr; ?></strong></td>
								<td>
									<a href="<?php echo esc_url($drill_url); ?>" class="button button-small">View Daily Stats</a>
								</td>
							</tr>
						<?php }
					} ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

/**
 * AJAX handler: exports Sponsored Ads stats as an Excel-compatible file.
 */
add_action('wp_ajax_bar_export_sponsored_ads', 'bar_ajax_export_sponsored_ads');
function bar_ajax_export_sponsored_ads()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_sponsored_ads');

	global $wpdb;
	$ads_table    = $wpdb->prefix . 'sponsored_ads';
	$clicks_table = 'wp_sponsored_ad_clicks';

	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';
	$spons_id   = isset($_GET['spons_id']) ? (int) $_GET['spons_id'] : 0;

	$where = '1=1';
	if ($start_date !== '') {
		$where .= $wpdb->prepare(' AND created_at >= %s', $start_date . ' 00:00:00');
	}
	if ($end_date !== '') {
		$where .= $wpdb->prepare(' AND created_at <= %s', $end_date . ' 23:59:59');
	}

	if ($spons_id > 0) {
		$ad = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ads_table WHERE id = %d", $spons_id));
		$ad_name = $ad ? sanitize_title($ad->company_name) : 'ad-' . $spons_id;
		$filename = 'sponsored-ad-' . $ad_name . ($start_date ? '-' . $start_date : '') . ($end_date ? '-to-' . $end_date : '') . '.xls';

		$rows = $wpdb->get_results(
			$wpdb->prepare("
				SELECT
					DATE(created_at) AS ActivityDate,
					COALESCE(SUM(CASE WHEN actiontype = 'View' THEN 1 END), 0) AS ViewCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Like' THEN 1 END), 0) AS LikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Click' THEN 1 END), 0) AS ClickCount,
					COALESCE(SUM(CASE WHEN actiontype = 'BuyNow' THEN 1 END), 0) AS BuyNowCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Share' THEN 1 END), 0) AS ShareCount,
					COALESCE(SUM(CASE WHEN actiontype = 'ProductLink' THEN 1 END), 0) AS ProductLinkCount,
					COALESCE(SUM(CASE WHEN actiontype = 'UnLike' THEN 1 END), 0) AS UnLikeCount,
					COALESCE(SUM(CASE WHEN actiontype = 'Comment' THEN 1 END), 0) AS CommentCount
				FROM $clicks_table
				WHERE spons_id = %d
				AND $where
				GROUP BY DATE(created_at)
				ORDER BY ActivityDate DESC
			", $spons_id)
		);

		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
		echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
		echo '<h2>' . esc_html($ad ? $ad->company_name : 'Sponsored Ad') . ' Daily Stats</h2>';
		echo '<table border="1">';
		echo '<tr><th>Date</th><th>Views</th><th>Clicks</th><th>Buy Now</th><th>Likes</th><th>Comments</th><th>Shares</th><th>Product Links</th><th>Unlikes</th></tr>';
		foreach ($rows as $row) {
			echo '<tr>';
			echo '<td>' . esc_html($row->ActivityDate) . '</td>';
			echo '<td>' . (int) $row->ViewCount . '</td>';
			echo '<td>' . (int) $row->ClickCount . '</td>';
			echo '<td>' . (int) $row->BuyNowCount . '</td>';
			echo '<td>' . (int) $row->LikeCount . '</td>';
			echo '<td>' . (int) $row->CommentCount . '</td>';
			echo '<td>' . (int) $row->ShareCount . '</td>';
			echo '<td>' . (int) $row->ProductLinkCount . '</td>';
			echo '<td>' . (int) $row->UnLikeCount . '</td>';
			echo '</tr>';
		}
		echo '</table></body></html>';
	} else {
		$filename = 'sponsored-ads-overview' . ($start_date ? '-' . $start_date : '') . ($end_date ? '-to-' . $end_date : '') . '.xls';

		$ads = $wpdb->get_results("SELECT * FROM $ads_table ORDER BY id DESC");
		$stats_rows = $wpdb->get_results("
			SELECT
				spons_id,
				COALESCE(SUM(CASE WHEN actiontype = 'View' THEN 1 END), 0) AS ViewCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Like' THEN 1 END), 0) AS LikeCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Click' THEN 1 END), 0) AS ClickCount,
				COALESCE(SUM(CASE WHEN actiontype = 'BuyNow' THEN 1 END), 0) AS BuyNowCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Share' THEN 1 END), 0) AS ShareCount,
				COALESCE(SUM(CASE WHEN actiontype = 'ProductLink' THEN 1 END), 0) AS ProductLinkCount,
				COALESCE(SUM(CASE WHEN actiontype = 'UnLike' THEN 1 END), 0) AS UnLikeCount,
				COALESCE(SUM(CASE WHEN actiontype = 'Comment' THEN 1 END), 0) AS CommentCount
			FROM $clicks_table
			WHERE $where
			GROUP BY spons_id
		");

		$stats_by_ad = array();
		foreach ($stats_rows as $row) {
			$stats_by_ad[$row->spons_id] = $row;
		}

		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
		echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
		echo '<h2>Sponsored Ads Overview Stats</h2>';
		echo '<table border="1">';
		echo '<tr><th>ID</th><th>Company Name</th><th>Views</th><th>Clicks</th><th>Buy Now</th><th>Likes</th><th>Comments</th><th>CTR</th></tr>';
		foreach ($ads as $ad) {
			$ad_stats = isset($stats_by_ad[$ad->id]) ? $stats_by_ad[$ad->id] : null;
			$views = $ad_stats ? (int) $ad_stats->ViewCount : 0;
			$clicks = $ad_stats ? (int) $ad_stats->ClickCount : 0;
			$buynow = $ad_stats ? (int) $ad_stats->BuyNowCount : 0;
			$likes = $ad_stats ? (int) $ad_stats->LikeCount : 0;
			$comments = $ad_stats ? (int) $ad_stats->CommentCount : 0;
			$ctr = $views > 0 ? round(($clicks / $views) * 100, 1) . '%' : '0%';

			echo '<tr>';
			echo '<td>' . (int) $ad->id . '</td>';
			echo '<td>' . esc_html($ad->company_name) . '</td>';
			echo '<td>' . $views . '</td>';
			echo '<td>' . $clicks . '</td>';
			echo '<td>' . $buynow . '</td>';
			echo '<td>' . $likes . '</td>';
			echo '<td>' . $comments . '</td>';
			echo '<td>' . esc_html($ctr) . '</td>';
			echo '</tr>';
		}
		echo '</table></body></html>';
	}
	exit;
}

/* =====================================================================
 * STATS: Verified Users
 * Daily verified vs non-verified user counts by registration date.
 * Convention (see bar.php): wp_users.validate_email = 0 means the email
 * has been verified; validate_email = 1 means not yet verified.
 * ===================================================================== */

/**
 * Resolve the requested date range for the new stat pages.
 * Defaults to the last 30 days (inclusive) when no filter is supplied,
 * so the heavier engagement query never runs unbounded.
 *
 * @return array [ $start_date, $end_date ] as YYYY-MM-DD strings.
 */
function bar_stats_date_range()
{
	$start_date = (isset($_GET['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date'])) ? $_GET['start_date'] : '';
	$end_date   = (isset($_GET['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date'])) ? $_GET['end_date'] : '';

	if ($start_date === '' && $end_date === '') {
		$end_date   = date('Y-m-d');
		$start_date = date('Y-m-d', strtotime('-29 days'));
	} elseif ($start_date === '') {
		$start_date = $end_date;
	} elseif ($end_date === '') {
		$end_date = $start_date;
	}

	// Guard against reversed range.
	if (strtotime($start_date) > strtotime($end_date)) {
		$tmp = $start_date;
		$start_date = $end_date;
		$end_date = $tmp;
	}

	return array($start_date, $end_date);
}

/**
 * Daily verified/non-verified counts for the given range, gaps filled with 0.
 *
 * @return array List of objects: { registration_date, verified, non_verified }.
 */
function bar_get_verified_users_rows($start_date, $end_date)
{
	global $wpdb;

	$grouped = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT DATE(user_registered) AS d,
				SUM(CASE WHEN validate_email = 0 THEN 1 ELSE 0 END) AS verified,
				SUM(CASE WHEN validate_email = 1 THEN 1 ELSE 0 END) AS non_verified
			 FROM {$wpdb->users}
			 WHERE user_registered BETWEEN %s AND %s
			 GROUP BY DATE(user_registered)",
			$start_date . ' 00:00:00',
			$end_date . ' 23:59:59'
		),
		OBJECT_K
	);

	$rows = array();
	$cursor = strtotime($start_date);
	$last   = strtotime($end_date);
	while ($cursor <= $last) {
		$day = date('Y-m-d', $cursor);
		$rows[] = (object) array(
			'registration_date' => $day,
			'verified'          => isset($grouped[$day]) ? (int) $grouped[$day]->verified : 0,
			'non_verified'      => isset($grouped[$day]) ? (int) $grouped[$day]->non_verified : 0,
		);
		$cursor = strtotime('+1 day', $cursor);
	}

	return $rows;
}

/**
 * Renders the Verified Users report.
 */
function bar_render_verified_users_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	list($start_date, $end_date) = bar_stats_date_range();
	$rows = bar_get_verified_users_rows($start_date, $end_date);

	$total_verified = 0;
	$total_non = 0;
	foreach ($rows as $row) {
		$total_verified += $row->verified;
		$total_non      += $row->non_verified;
	}
	$total_all = $total_verified + $total_non;

	$export_url = add_query_arg(array(
		'action'     => 'bar_export_verified_users',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'_wpnonce'   => wp_create_nonce('bar_export_verified_users'),
	), admin_url('admin-ajax.php'));
	?>
	<div class="wrap">
		<h1>Verified Users</h1>

		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="bar-verified-users" />
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
		</form>

		<p class="description">Showing registrations from <?php echo esc_html($start_date); ?> to <?php echo esc_html($end_date); ?>.</p>

		<p>
			<strong>Total registrations:</strong> <?php echo (int) $total_all; ?>
			&nbsp;|&nbsp; <strong>Verified:</strong> <?php echo (int) $total_verified; ?>
			&nbsp;|&nbsp; <strong>Not verified:</strong> <?php echo (int) $total_non; ?>
		</p>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Registration Date</th>
					<th>Verified Users</th>
					<th>Non-Verified Users</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="4">No data for this range.</td></tr>
				<?php } else {
					foreach ($rows as $row) {
						$day_total = $row->verified + $row->non_verified;
						?>
						<tr>
							<td><?php echo esc_html($row->registration_date); ?></td>
							<td><?php echo (int) $row->verified; ?></td>
							<td><?php echo (int) $row->non_verified; ?></td>
							<td><?php echo (int) $day_total; ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Excel export for the Verified Users report.
 */
add_action('wp_ajax_bar_export_verified_users', 'bar_ajax_export_verified_users');
function bar_ajax_export_verified_users()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_verified_users');

	list($start_date, $end_date) = bar_stats_date_range();
	$rows = bar_get_verified_users_rows($start_date, $end_date);

	$filename = 'verified-users-' . $start_date . '-to-' . $end_date . '.xls';

	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
	echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
	echo '<table border="1">';
	echo '<tr><th>Registration Date</th><th>Verified Users</th><th>Non-Verified Users</th><th>Total</th></tr>';
	foreach ($rows as $row) {
		echo '<tr>';
		echo '<td>' . esc_html($row->registration_date) . '</td>';
		echo '<td>' . (int) $row->verified . '</td>';
		echo '<td>' . (int) $row->non_verified . '</td>';
		echo '<td>' . (int) ($row->verified + $row->non_verified) . '</td>';
		echo '</tr>';
	}
	echo '</table></body></html>';
	exit;
}

/* =====================================================================
 * STATS: User Engagement
 * Per-user posts, comments (wp_comments + bbPress replies) and likes
 * within the selected date range.
 * ===================================================================== */

/**
 * Per-user engagement rows for the given range.
 *
 * @return array List of associative rows:
 *               { user_id, user_email, total_posts, total_comments, total_likes }.
 */
function bar_get_user_engagement_rows($start_date, $end_date)
{
	global $wpdb;

	$start = $start_date . ' 00:00:00';
	$end   = $end_date . ' 23:59:59';

	$sql = $wpdb->prepare(
		"SELECT
			u.ID AS user_id,
			u.user_email,
			COUNT(DISTINCT p.ID) AS total_posts,
			COALESCE(MAX(c.comment_count), 0) + COALESCE(MAX(bbp_replies.reply_count), 0) AS total_comments,
			COALESCE(MAX(rl.like_count), 0) AS total_likes
		 FROM {$wpdb->users} u
		 LEFT JOIN {$wpdb->posts} p
			ON p.post_author = u.ID
			AND p.post_status = 'publish'
			AND p.post_type = 'reply'
			AND p.post_date BETWEEN %s AND %s
			AND p.ID NOT IN (
				SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_bbp_reply_to'
			)
		 LEFT JOIN (
			SELECT user_id, COUNT(*) AS comment_count
			FROM {$wpdb->comments}
			WHERE comment_approved = 1
			AND comment_date BETWEEN %s AND %s
			GROUP BY user_id
		 ) c ON c.user_id = u.ID
		 LEFT JOIN (
			SELECT post_author, COUNT(*) AS reply_count
			FROM {$wpdb->posts}
			WHERE post_type = 'reply'
			AND post_status = 'publish'
			AND post_date BETWEEN %s AND %s
			GROUP BY post_author
		 ) bbp_replies ON bbp_replies.post_author = u.ID
		 LEFT JOIN (
			SELECT user_id, COUNT(*) AS like_count
			FROM wp_reply_likes
			WHERE status = '0'
			AND created BETWEEN %s AND %s
			GROUP BY user_id
		 ) rl ON rl.user_id = u.ID
		 WHERE u.ID != 5414
		 AND (
			p.ID IS NOT NULL
			OR COALESCE(c.comment_count, 0) > 0
			OR COALESCE(bbp_replies.reply_count, 0) > 0
			OR COALESCE(rl.like_count, 0) > 0
		 )
		 GROUP BY u.ID, u.user_email
		 ORDER BY total_posts DESC, total_comments DESC, total_likes DESC",
		$start, $end,
		$start, $end,
		$start, $end,
		$start, $end
	);

	return $wpdb->get_results($sql, ARRAY_A);
}

/**
 * Renders the User Engagement report.
 */
function bar_render_user_engagement_report()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	list($start_date, $end_date) = bar_stats_date_range();
	$rows = bar_get_user_engagement_rows($start_date, $end_date);

	$total_posts = 0;
	$total_comments = 0;
	$total_likes = 0;
	foreach ($rows as $row) {
		$total_posts    += (int) $row['total_posts'];
		$total_comments += (int) $row['total_comments'];
		$total_likes    += (int) $row['total_likes'];
	}

	$export_url = add_query_arg(array(
		'action'     => 'bar_export_user_engagement',
		'start_date' => $start_date,
		'end_date'   => $end_date,
		'_wpnonce'   => wp_create_nonce('bar_export_user_engagement'),
	), admin_url('admin-ajax.php'));
	?>
	<div class="wrap">
		<h1>User Engagement</h1>

		<form method="get" style="margin:12px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<input type="hidden" name="page" value="bar-user-engagement" />
			<label>From <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>" /></label>
			<label>To <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>" /></label>
			<button type="submit" class="button button-primary">Filter</button>
			<a class="button" href="<?php echo esc_url($export_url); ?>" style="margin-left:auto;">&#x2193; Export to Excel</a>
		</form>

		<p class="description">Showing activity from <?php echo esc_html($start_date); ?> to <?php echo esc_html($end_date); ?>.</p>

		<p>
			<strong>Active users:</strong> <?php echo (int) count($rows); ?>
			&nbsp;|&nbsp; <strong>Posts:</strong> <?php echo (int) $total_posts; ?>
			&nbsp;|&nbsp; <strong>Comments:</strong> <?php echo (int) $total_comments; ?>
			&nbsp;|&nbsp; <strong>Likes:</strong> <?php echo (int) $total_likes; ?>
		</p>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>User ID</th>
					<th>Email</th>
					<th>Posts</th>
					<th>Comments</th>
					<th>Likes</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="5">No activity for this range.</td></tr>
				<?php } else {
					foreach ($rows as $row) { ?>
						<tr>
							<td><?php echo (int) $row['user_id']; ?></td>
							<td><?php echo esc_html($row['user_email']); ?></td>
							<td><?php echo (int) $row['total_posts']; ?></td>
							<td><?php echo (int) $row['total_comments']; ?></td>
							<td><?php echo (int) $row['total_likes']; ?></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Excel export for the User Engagement report.
 */
add_action('wp_ajax_bar_export_user_engagement', 'bar_ajax_export_user_engagement');
function bar_ajax_export_user_engagement()
{
	if (!current_user_can('manage_options')) {
		wp_die('Unauthorized');
	}
	check_admin_referer('bar_export_user_engagement');

	list($start_date, $end_date) = bar_stats_date_range();
	$rows = bar_get_user_engagement_rows($start_date, $end_date);

	$filename = 'user-engagement-' . $start_date . '-to-' . $end_date . '.xls';

	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
	echo '<head><meta charset="UTF-8"><style>td{mso-number-format:"\@";}</style></head><body>';
	echo '<table border="1">';
	echo '<tr><th>User ID</th><th>Email</th><th>Posts</th><th>Comments</th><th>Likes</th></tr>';
	foreach ($rows as $row) {
		echo '<tr>';
		echo '<td>' . (int) $row['user_id'] . '</td>';
		echo '<td>' . esc_html($row['user_email']) . '</td>';
		echo '<td>' . (int) $row['total_posts'] . '</td>';
		echo '<td>' . (int) $row['total_comments'] . '</td>';
		echo '<td>' . (int) $row['total_likes'] . '</td>';
		echo '</tr>';
	}
	echo '</table></body></html>';
	exit;
}
