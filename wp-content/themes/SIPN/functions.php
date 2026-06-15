	<?php
	add_action('wp_enqueue_scripts', 'my_enqueue_function');

	function my_enqueue_function()
	{
		wp_register_script('site-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery'), '7.0.0');
		wp_enqueue_script('site-script');

		wp_localize_script('site-script', 'site_script_object', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce'),
			'redirecturl' => get_permalink(),
			'loadingmessage' => __('Sending user info, please wait...')
		));
	}

	// Enable the user with no privileges to run ajax_login() in AJAX
	add_action('wp_ajax_nopriv_ajaxwishlist', 'ajaxwishlist');
	add_action('wp_ajax_ajaxwishlist', 'ajaxwishlist');

	add_action('wp_ajax_nopriv_ajaxchatedit', 'ajaxchatedit');
	add_action('wp_ajax_ajaxchatedit', 'ajaxchatedit');

	add_action('wp_ajax_nopriv_ajaxbprofileupdate', 'ajaxbprofileupdate');
	add_action('wp_ajax_ajaxbprofileupdate', 'ajaxbprofileupdate');

	add_action('wp_ajax_nopriv_ajaxremoveprofileimage', 'ajaxremoveprofileimage');
	add_action('wp_ajax_ajaxremoveprofileimage', 'ajaxremoveprofileimage');

	add_action('wp_ajax_nopriv_ajaxchatlike', 'ajaxchatlike');
	add_action('wp_ajax_ajaxchatlike', 'ajaxchatlike');


	add_action('wp_ajax_nopriv_ajaxlikeprofile', 'ajaxlikeprofile');
	add_action('wp_ajax_ajaxlikeprofile', 'ajaxlikeprofile');

	add_action('wp_ajax_nopriv_ajaxbarlist', 'ajaxbarlist');
	add_action('wp_ajax_ajaxbarlist', 'ajaxbarlist');


	add_action('wp_ajax_nopriv_ajaxbarupdate', 'ajaxbarupdate');
	add_action('wp_ajax_ajaxbarupdate', 'ajaxbarupdate');

	add_action('wp_ajax_nopriv_ajaxproductsreorder', 'ajaxproductsreorder');
	add_action('wp_ajax_ajaxproductsreorder', 'ajaxproductsreorder');

	add_action('wp_ajax_nopriv_ajaxsavebarchanges', 'ajaxsavebarchanges');
	add_action('wp_ajax_ajaxsavebarchanges', 'ajaxsavebarchanges');

	add_action('wp_ajax_nopriv_ajaxproductsreordercrossshelf', 'ajaxproductsreordercrossshelf');
	add_action('wp_ajax_ajaxproductsreordercrossshelf', 'ajaxproductsreordercrossshelf');

	add_action('wp_ajax_nopriv_ajaxupdateshelfname', 'ajaxupdateshelfname');
	add_action('wp_ajax_ajaxupdateshelfname', 'ajaxupdateshelfname');

	add_action('wp_ajax_nopriv_ajaxproductdelete', 'ajaxproductdelete');
	add_action('wp_ajax_ajaxproductdelete', 'ajaxproductdelete');

	add_action('wp_ajax_nopriv_ajaxloadtimeline', 'ajaxloadtimeline');
	add_action('wp_ajax_ajaxloadtimeline', 'ajaxloadtimeline');

	add_action('wp_ajax_nopriv_ajaxaddcommenttotimeline', 'ajaxaddcommenttotimeline');
	add_action('wp_ajax_ajaxaddcommenttotimeline', 'ajaxaddcommenttotimeline');

	add_action('wp_ajax_nopriv_ajaxaddposttotimeline', 'ajaxaddposttotimeline');
	add_action('wp_ajax_ajaxaddposttotimeline', 'ajaxaddposttotimeline');

	add_action('wp_ajax_nopriv_ajaxdeletepost', 'ajaxdeletepost');
	add_action('wp_ajax_ajaxdeletepost', 'ajaxdeletepost');

	add_action('wp_ajax_nopriv_ajaxreportpost', 'ajaxreportpost');
	add_action('wp_ajax_ajaxreportpost', 'ajaxreportpost');


	add_action('wp_ajax_nopriv_ajaxeditpost', 'ajaxeditpost');
	add_action('wp_ajax_ajaxeditpost', 'ajaxeditpost');

	add_action('wp_ajax_nopriv_ajaxtimelinecomments', 'ajaxtimelinecomments');
	add_action('wp_ajax_ajaxtimelinecomments', 'ajaxtimelinecomments');

	add_action('wp_ajax_nopriv_ajaxreportforum', 'ajaxreportforum');
	add_action('wp_ajax_ajaxreportforum', 'ajaxreportforum');

	add_action('wp_ajax_nopriv_ajaxajaxblockuser', 'ajaxblockuser');

	add_action('wp_ajax_nopriv_ajaxreportbar', 'ajaxreportbar');
	add_action('wp_ajax_ajaxreportbar', 'ajaxreportbar');

	add_action('wp_ajax_nopriv_ajaxdelprofile', 'ajaxdelprofile');
	add_action('wp_ajax_ajaxdelprofile', 'ajaxdelprofile');

	add_action('wp_ajax_nopriv_ajaxsponslike', 'ajaxsponslike');
	add_action('wp_ajax_ajaxwhishlist', 'ajaxwhishlist');
	add_action('wp_ajax_ajaxsponslike', 'ajaxsponslike');

	add_action('wp_ajax_nopriv_ajaxaddsponscommenttotimeline', 'ajaxaddsponscommenttotimeline');
	add_action('wp_ajax_ajaxaddsponscommenttotimeline', 'ajaxaddsponscommenttotimeline');

	add_action('wp_ajax_nopriv_ajaxeditsponspostt', 'ajaxeditsponspost');
	add_action('wp_ajax_ajaxeditsponspost', 'ajaxeditsponspost');

	add_action('wp_ajax_nopriv_ajaxdeletesponscomment', 'ajaxdeletesponscomment');
	add_action('wp_ajax_ajaxdeletesponscomment', 'ajaxdeletesponscomment');

	add_action('wp_ajax_nopriv_ajaxsponstimelinecomments', 'ajaxsponstimelinecomments');
	add_action('wp_ajax_ajaxsponstimelinecomments', 'ajaxsponstimelinecomments');

	//add_action('mynewcollection', 'my_new_collection');

	//add_action('reactionstopost', 'my_reactionstopost');

	//add_action('weeklyfeed_update', 'my_weeklyfeed_update');

	//add_action('fathers_nationalbourbonday', 'fathers_and_bourbonday');

	add_action('wp_ajax_nopriv_ajaxsubscribeunsubscribe', 'ajaxsubscribeunsubscribe');
	add_action('wp_ajax_ajaxsubscribeunsubscribe', 'ajaxsubscribeunsubscribe');

	add_action('wp_ajax_nopriv_ajaxsendingindexdata', 'ajaxsendingindexdata');
	add_action('wp_ajax_ajaxsendingindexdatae', 'ajaxsendingindexdata');

	//add_action('send_headers', 'allow_all_external_links');
	function allow_all_external_links() {
	    header("Content-Security-Policy: default-src * 'self' data: blob:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; img-src * 'self' data: blob:;");
	}

	function birthday_rewards_web(){
		global $wpdb;
		$user_id = get_current_user_id();
		$today = date('m-d');
		$current_year = date('Y');
		$sql = $wpdb->prepare(
			"SELECT user_id
	     FROM wp_usermeta
	     WHERE meta_key = 'date_of_birth'
	       AND DATE_FORMAT(meta_value, '%%m-%%d') <= DATE_FORMAT(CURDATE(), '%%m-%%d')
	       AND user_id = %d",
	    $user_id
		);
		$user = $wpdb->get_var($sql);
		if ($user) {
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

	//for single product page which is redirecting to blog post when theme is changed
	function so_43621049_template_include($template)
	{
		if (is_singular('product')) {
			$template = plugin_dir_path(__FILE__) . 'woocommerce/single-product.php';
		}
		return $template;
	}
	add_filter('template_include', 'so_43621049_template_include', 20);

	function my_new_collection($a)
	{
		global $wpdb;
		$id = $a;
		//print_r($id);
		$query = $wpdb->prepare("SELECT *  FROM wp_collections where collection_id=$id");
		$notifications_list = $wpdb->get_results($query);

		$str = str_replace(" ", "-", $notifications_list[0]->collection_orgname);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$args = array(
			'role' => 'Subscriber',
			'orderby' => 'user_nicename',
			'unsubscribe' => '0',
			'order' => 'ASC'
		);
		//$to='sumeeth@bottlecapps.com';
		$sub = 'Explore our new ' . $notifications_list[0]->collection_name;
		//wp_mail( $to, $sub, $html, $headers );
		$users = get_users($args);
		foreach ($users as $user) {
			if ($user->unsubscribe == '0') {
				$emm = $user->user_email;
				$em = base64_encode($emm);
				$html = '<!DOCTYPE html>
		<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
		<meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
		<meta name="color-scheme" content="light dark">
		<meta name="supported-color-schemes" content="light dark">
		<link rel="noopener" target="_blank" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
		<title>New Collection Available</title>
		  <style>
			:root {color-scheme: light dark; supported-color-schemes: light dark;} 
			body {-webkit-text-size-adjust:100%; margin:0;padding:0;width:100%;background-color:#ffffff;}
			#outlook a {padding:0;}
			a[x-apple-data-detectors] {color: inherit!important; text-decoration: none!important; font-size: inherit!important; font-family: inherit!important; font-weight:inherit!important; line-height:inherit!important;}
			.ExternalClass {width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
		  </style>
		</head>
	<body xml:lang="en" style="width: 100%; background-color: #eee; font-family:"Roboto", Verdana, sans-serif;">
	<div aria-roledescription="email" role="article">
	<table role="presentation" style="width: 100%; background-color: #eee;">
	<tr>
	<td>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; mso-table-lspace:0pt; mso-table-rspace:0pt;" role="presentation">
	<tr>
	<td align="center" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px; background-color: #753032; padding-top: 22px; padding-bottom: 22px;"> 
	 <img style="width: 150px;" src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/Sipn_logo.png" border="0" />	     
	</td>
	</tr>

	<tr>
	<td align="center" width="600" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="padding-top: 35px; padding-bottom: 35px; background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center;">
			  <table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
			   <tr>
			   <td align="center" style="text-align: center; line-height: 35px; font-size: 26px; font-weight: bold;">
				New Collection Available
			   </td>
			   </tr>
			   <tr>
			   <td align="center" style="text-align: center; color: #b8a66e; line-height: 30px; font-size: 26px; font-weight: bold;">
				 ' . $notifications_list[0]->collection_name . '
			   </td>
			   </tr>
			   </table>
	</td>
	</tr>
	<tr>
	<td width="600" align="center" style="text-align: center; width: 600px;">
			  <table width="540" align="center" style="text-align: center; width: 540px; padding-top: 10px; padding-left: 15px; padding-right: 15px;">
				 <tr>
				 <td align="center" width="540" style="text-align: center;">
				 <a href="' . site_url() . '/bourbon-collection/' . $str . '" target="_blank" style="text-decoration:none; color: #ffffff; -webkit-text-size-adjust:none;">  <img src="' . $notifications_list[0]->collection_image . '" border="0" style="width: 500px; border-radius: 50px;" /></a>
				 </td>
				 </tr>
			  </table>
	</td>
	</tr>
	<tr>
			<td align="center" width="600" style="text-align: center;">
			<table width="540" align="center">
			 <tr>
			 <td align="center" style="width: 540px; padding-top: 15px; padding-bottom: 30px; padding-left: 15px; padding-right: 15px; line-height: 20px; font-size: 16px; text-align: center;">
				 ' . nl2br(strip_tags($notifications_list[0]->collection_long_description)) . '
			 </td>
			 </tr>
			</table>
			</td>
	</tr>
	<tr>
			<td align="center" width="600" style="text-align: center;">	
			 <table align="center" width="300" border="0" cellpadding="0" cellspacing="0" style="width: 300px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #ffffff;">
			 <tr>
			 <td align="center" style="padding-bottom: 10px;">
			   <!--[if mso]>
	           <table>
	            <tr>
	            <td align="center" style="color:#ffffff; background-color: #b8a66e; padding: 5px 30px; border:1px solid #b8a66e; font-size:16px; font-weight:bold; text-align:center; width: 300px;">
	             <a href="https://sipn.page.link/collection" style="text-decoration:none; color: #ffffff; -webkit-text-size-adjust:none;">Explore Collection</a>
	            </td>
	            </tr>
	            </table>
	           <![endif]-->
			  <a href="https://sipn.page.link/collection" style="color:#ffffff; font-size:16px; font-weight:bold; text-align:center; text-decoration:none; padding: 5px 20px; display: block; background-color: #b8a66e; border:1px solid #b8a66e; border-radius:22px; -webkit-text-size-adjust:none;mso-hide:all;">Explore Collection</a>
			 
			 </td>
			 </tr>
			 <tr>
			 <td align="center">
			   <!--[if mso]>
	           <table>
	            <tr>
	            <td align="center" style="color:#333333; background-color: #ffffff; padding: 5px 30px; border:1px solid #b8a66e; font-size:16px; font-weight:bold; text-align:center; width: 300px;">
	             <a href="https://sipn.page.link/home" style="text-decoration:none; color: #333333; -webkit-text-size-adjust:none;">Go To SIPN</a>
	            </td>
	            </tr>
	            </table>
	           <![endif]-->
			  <a href="https://sipn.page.link/home" style="color:#333333; font-size:16px; font-weight:bold; text-align:center; text-decoration:none; padding: 5px 20px; display: block; background-color: #fffff; border:1px solid #b8a66e; border-radius:22px; -webkit-text-size-adjust:none;mso-hide:all;">Go To SIPN</a>
			 
			 </td>
			 </tr> 
			 </table>
			 </td>
	</tr>	
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>

	<tr>
			<td style="background-color: #212121; padding-top: 20px; padding-bottom: 20px;">
			<table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #212121;">
			 <tr>
			 <td align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
			  <table align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
			  <tr>
			 <td><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/fbsocial.png" border="0" width="29" height="30" style="width: 29px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			  <td><a href="https://instagram.com/sipnbourbon?igshid=OGRjNzg3M2Y=" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/igsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			  <td><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/twsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			 </tr>
			 </table>
			 </td>
			 </tr>
			 <tr>
			 <td align="center">
			 <table align="center" width="60" style="text-align: center; font-size: 0; width: 60px; padding-top: 12px;">
			 <tr>
			 <td align="center" width="60" style="text-align: center; font-size: 0; width: 60px;">
			  <img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/footer_sipn_logo.png" border="0" style="width: 60px;" />
			 </td>
			 </tr>
			 </table>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" style="color: #fff; font-size: 12px; text-align: center;">
			  <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-top: 16px;">
			  <tr>
			  <td align="center" style="text-align: center; color: #ffffff;">
			  <p style="margin: 0; color: #ffffff;">Copyright ' . date('Y') . ' SIPN Bourbon, All rights reserved.</p>
			  <p style="margin-top: 1px; margin-bottom: 0; color: #ffffff;">You can <a style="color: #ffffff; text-decoration: none;" href="https://sipnbourbon.com/wp-json/users/v2/unsubscribeemail?email=' . $em . '"  target="_blank">  <span style="
	    text-decoration: underline;
	">unsubscribe</span> </a> at any time.</p>
			  </td>
			  </tr>
			  </table>
			 </td>
			 </tr>
			</table>
			</td>
			</tr>
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</div>
	</body>
	</html>';
				$sub = 'Explore our new ' . $notifications_list[0]->collection_name;


				$to = $user->user_email;
				wp_mail($to, $sub, $html, $headers);
				$query = $wpdb->prepare("INSERT INTO `testing` (`email`) VALUES (%s)", $to);
				$res = $wpdb->query($query);
			}
		}
		//$to='sambasivarao@bottlecapps.com';
		//wp_mail( $to, 'New Collection Available', $html, $headers );
		// $users = get_users( $args );
		// foreach ( $users as $user ) {
		// $to= $user->user_email;
		//  wp_mail( $to, 'New Collection Available', $html, $headers );
		// }

		//  $m=['sambasivarao@bottlecapps.com','sumeeth@bottlecapps.com'];
		// foreach ($m as  $value) {
		//   	 wp_mail( $value, $sub, $html, $headers );
		//   }  
		//wp_mail( 'sambasivarao@bottlecapps.com', 'Collection Details', $no );
	}


	function my_reactionstopost()
	{
		global $wpdb;
		$q = $wpdb->prepare("SELECT * from wp_reply_likes
	       where created > now() - interval 24 hour and status ='0' group by reply_id");
		$users_post = $wpdb->get_results($q);
		//print_r($users_post);exit;
		foreach ($users_post as $value) {
			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $value->reply_id);
			$list = $wpdb->get_results($query);
			$likes = $list[0]->cnt;
			//echo $likes;
			$args = [
				'post_type' => 'reply',
				'post_status' => 'publish',
				'order' => 'DESC',
				'meta_query' => array(
					array(
						'key' => '_bbp_reply_to',
						'value' => $value->reply_id,
					)
				),
				'paged' => $page,

			];

			//print_r($args);
			$replies = get_posts($args);
			$repliescount = count($replies);
			$total = $likes + $repliescount;
			$querypos = $wpdb->prepare("SELECT * FROM `wp_posts` WHERE ID = $value->reply_id and post_parent ='35832' and post_type = 'reply' and post_status ='publish'");
			$pos_list = $wpdb->get_results($querypos);
			$content = $pos_list[0]->post_content;
			$emuser = $pos_list[0]->post_author;
			$con1 = strip_tags($content);
			$content1 = substr($con1, 0, 50);

			$queryus = $wpdb->prepare("SELECT user_email FROM `wp_users` WHERE ID = $emuser");
			$us_list = $wpdb->get_results($queryus);
			$em = $us_list[0]->user_email;

			$emmail = base64_encode($em);



			$html = '<!DOCTYPE html>';
			$html .= '<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
	<meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
	<meta name="color-scheme" content="light dark">
	<meta name="supported-color-schemes" content="light dark">
	<link rel="noopener" target="_blank" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<title>Weekly Feed Update</title>
	  <style>
		:root {color-scheme: light dark; supported-color-schemes: light dark;} 
		body {-webkit-text-size-adjust:100%; margin:0;padding:0;width:100%;background-color:#ffffff;}
		#outlook a {padding:0;}
		table {border-spacing: 0;}
		a[x-apple-data-detectors] {color: inherit!important; text-decoration: none!important; font-size: inherit!important; font-family: inherit!important; font-weight:inherit!important; line-height:inherit!important;}
		.ExternalClass {width:100%;}
		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
	  </style>
	</head>
	<body xml:lang="en" style="width: 100%; background-color: #eee; font-family: Google Sans,sans-serif,Verdana;">
	<div aria-roledescription="email" role="article">
	<table role="presentation" style="width: 100%; background-color: #eee;">
	<tr>
	<td>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; mso-table-lspace:0pt; mso-table-rspace:0pt;" role="presentation">
	<tr>
	<td style="font-size: 0; padding: 0;">&nbsp;&nbsp;</td>
	<td align="left" valign="top" align="center" width="600" style="text-align: center; width:600px">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px; background-color: #dbd9d8; padding-top: 10px;"> 
	<img style="width: 150px;" src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/Sipn_logo_new.png" border="0" />	     
	</td>
	</tr>
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 600px; background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td style="font-size: 0; padding: 0;">&nbsp;&nbsp;</td>
	<td align="center" width="540" style="text-align: center; width: 540px; padding-top: 35px; padding-bottom: 35px; ">
	<table align="center" width="540" border="0" cellpadding="0" cellspacing="0" style="width: 540px; background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" style="font-family: Google Sans,sans-serif,Verdana; color: #333333; line-height: 35px; font-size: 30px; font-weight: bold;">';
			$html .= "You've Got " . $total . " <br> New Notifications";
			$html .= '</td>
	</tr>
	<tr>
		 <td>
		  <table align="center" width="540" border="0" cellpadding="0" cellspacing="0" style="width: 540px; padding-top: 10px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-bottom: 20px; background-color: #ffffff;">    
		  <tbody><tr>
		  <td>  
		  <table align="center" width="250" border="0" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt; mso-table-rspace:0pt; width: 250px;">
		  <tbody><tr>
		  <td>
		  <table align="left" width="250" border="0" cellpadding="0" cellspacing="0" style="width: 250px; padding-top: 10px; padding-bottom: 10px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #ffffff;">
		  <tbody><tr>
		  <td width="35" height="28" style="vertical-align: middle; font-size: 0;">
		  <img width="25" style="width: 25px;" src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/likesnavy.png" border="0">
		  </td>
		  <td align="left" style="font-family: Google Sans,sans-serif,Verdana; vertical-align: middle; text-align: left; font-size: 18px; font-weight: bold; color: #44555c; padding-left: 6px;">
			' . $likes . ' Likes
		  </td>
		  </tr>
		  </tbody></table>   
		  </td>
		  </tr>
		  <tr>
		  <td>
		  <table align="left" width="250" border="0" cellpadding="0" cellspacing="0" style="width: 250px; padding-top: 10px; padding-bottom: 10px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #ffffff;">
		  <tbody><tr>
		  <td width="35" height="28" style="vertical-align: middle; font-size: 0;">
		  <img width="25" style="width: 25px;" src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/commentnavy.png" border="0">
		  </td>
		  <td align="left" style="font-family: Google Sans,sans-serif,Verdana; vertical-align: middle; text-align: left; font-size: 18px; font-weight: bold; color: #44555c; padding-left: 6px;">
			' . $repliescount . ' Replies
		  </td>
		  </tr>
		  </tbody></table>   
		  </td>
		  </tr>
		  
		  </tbody></table>
		  </td>
		  </tr>
		  </tbody></table>	 
		 </td>
	</tr>
	<tr>
		 <td align="center" width="600" style="text-align: center; width: 600px; background-color: #ffffff;"> 
		  <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-top: 0px; background-color: #ffffff;">    
		  <tr>
		  <td align="center" style="font-family: Google Sans,sans-serif,Verdana; line-height: 35px; font-size: 30px; font-weight: bold;">
		  <div><!--[if mso]>
		  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" strokecolor="#1e3650" fillcolor="#556270">
		  <w:anchorlock/>
		  <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">See More Notifications</center>
		  </v:roundrect>
		  <![endif]-->
		  <a href="' . site_url() . '" target="_blank" style="font-family: Google Sans,sans-serif,Verdana;background-color:#556270;border:1px solid #1e3650;border-radius:22px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:18px;font-weight:bold;text-align:center;text-decoration:none;padding:2px 10px;width:235px;-webkit-text-size-adjust:none;mso-hide:all;">See More Notifications</a></div>
		  </td>
		  </tr> 
		  </table>	     
		 </td>
		 </tr>

	</table>
	</td>
	<td style="font-size: 0; padding: 0;">&nbsp;&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>

	<tr>
		<td style="background-color: #212121; padding-top: 20px; padding-bottom: 20px;">
		<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #212121;">
		 <tr>
		 <td align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
		  <table align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
		  <tr>
		  <td><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/fbsocial.png" border="0" width="29" height="30" style="width: 29px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		  <td><a href="https://instagram.com/sipnbourbon?igshid=OGRjNzg3M2Y=" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/igsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		  <td><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/twsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		 </tr>
		 </table>
		 </td>
		 </tr>
		 <tr>
		 <td align="center">
		 <table align="center" width="60" style="text-align: center; font-size: 0; width: 60px; padding-top: 12px;">
		 <tr>
		 <td align="center" width="60" style="text-align: center; font-size: 0; width: 60px;">
		  <img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/footer_sipn_logo.png" border="0" style="width: 60px;" />
		 </td>
		 </tr>
		 </table>
		 </td>
		 </tr>
		 <tr>
		 <td align="center" style="color: #fff; font-size: 12px; text-align: center;">
		  <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-top: 16px;">
		  <tr>
		  <td align="center" style="text-align: center; color: #ffffff;">
		  <p style="margin: 0; color: #ffffff;">Copyright ' . date('Y') . ' SIPN Bourbon, All rights reserved.</p>
		   <p style="margin-top: 1px; margin-bottom: 0; color: #ffffff;">You can <a style="color: #ffffff; text-decoration: none;" href="https://sipnbourbon.com/wp-json/users/v2/unsubscribeemail?email=' . $emmail . '"  target="_blank">  <span style="
	    text-decoration: underline;
	">unsubscribe</span> </a> at any time.</p>

		  
		  </td>
		  </tr>
		  </table>
		 </td>
		 </tr>
		</table>
		</td>
		</tr>
	</table>
	</td>
	<td style="font-size: 0; padding: 0;">&nbsp;&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</div>
	</body>
	</html>';
			$content2 = "Your post " . $content1 . " got new likes";
			$headers = array('Content-Type: text/html; charset=UTF-8');
			// $m=['sambasivarao@bottlecapps.com','sumeeth@bottlecapps.com','durgaprasadk@bottlecapps.com','hari@bottlecapps.com','ragini@bottlecapps.com','darnish@techmaticsys.com','ani@bottlecapps.com','raghu@bottlecapps.com'];
			//foreach ($m as  $value) {
			// wp_mail($em, $content2, $html, $headers);
		}
	}


	function fathers_and_bourbonday()
	{
		global $wpdb;
		$sub = "Giveaway Alert! Enter Sipn's National Bourbon Day & Father's Day giveaway";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$args = array(
			'role' => 'Subscriber',
			'orderby' => 'user_nicename',
			'unsubscribe' => '0',
			'order' => 'ASC'
		);
		$users = get_users($args);
		foreach ($users as $user) {
			if ($user->validate_email == '0') {
				$emm = $user->user_email;
				$em = base64_encode($emm);
				$to = $user->user_email;

				$html = '<html>
				<style>
				@media only screen and (max-width: 767px) {
					table {width: 100%;}
				}
				</style>
	<body xml:lang="en" style="width: 100%; background-color: #eee; font-family: Google Sans,sans-serif,Verdana;">
	<div aria-roledescription="email" role="article">
		 
	<table role="presentation" style="width: 607px; background-color: #eee; margin-top: -5px; margin-left: -2px;">
	<tr><td><a href="https://sipnbourbon.com"><img style="width: 603px;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/notification/fathersdayemail.png"></a></td></tr>
	<tr>
		<td style="background-color: #674c40; padding-top: 20px; padding-bottom: 20px;">
		<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #674c40;">
		 <tr>
		 <td align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
		  <table align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
		  <tr>
		  <td><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/fbsocial.png" border="0" width="29" height="30" style="width: 29px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		  <td><a href="https://instagram.com/sipnbourbon?igshid=OGRjNzg3M2Y=" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/igsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		  <td><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/twsocial.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
		 </tr>
		 </table>
		 </td>
		 </tr>
		 <tr>
		 <td align="center">
		 <table align="center" width="60" style="text-align: center; font-size: 0; width: 60px; padding-top: 12px;">
		 <tr>
		 <td align="center" width="60" style="text-align: center; font-size: 0; width: 60px;">
		  <img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/footer_sipn_logo.png" border="0" style="width: 60px;" />
		 </td>
		 </tr>
		 </table>
		 </td>
		 </tr>
		 <tr>
		 <td align="center" style="color: #fff; font-size: 12px; text-align: center;">
		  <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-top: 16px;">
		  <tr>
		  <td align="center" style="text-align: center; color: #ffffff;">
		  <p style="margin: 0; color: #ffffff;">Copyright ' . date('Y') . ' SIPN Bourbon, All rights reserved.</p>
		   <p style="margin-top: 1px; margin-bottom: 0; color: #ffffff;">You can <a style="color: #ffffff; text-decoration: none;" href="https://sipnbourbon.com/wp-json/users/v2/unsubscribeemail?email=' . $em . '"  target="_blank">  <span style="
	    text-decoration: underline;
	">unsubscribe</span> </a> at any time.</p>

		  
		  </td>
		  </tr>
		  </table>
		 </td>
		 </tr>
		</table>
		</td>
		</tr>
	</table>
	</div>
	</body>
	</html>';
				wp_mail($to, $sub, $html, $headers);
				$query = $wpdb->prepare("INSERT INTO `testing` (`email`) VALUES (%s)", $to);
				$res = $wpdb->query($query);
			}
		}
	}

	function my_weeklyfeed_update()
	{
		global $wpdb;

		$args = array(
			'post_type' => 'events',
			'post_status' => 'publish',
			'posts_per_page' => 3,
			'meta_key' => 'event_start_date',
			'orderby' => 'meta_value_num',

			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'event_start_date',
					'value' => date("Ymd"), // date format error
					'compare' => '>='
				),
				array(
					'key' => 'event_end_date',
					'value' => date("Ymd"), // date format error
					'compare' => '>='
				)
			),
			'order' => 'ASC'
		);
		$events = get_posts($args);
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		$args1 = [
			'post_type' => 'product',
			'post_status' => 'publish',
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
			'posts_per_page' => 3
		];



		$products = get_posts($args1);
		//                                  $topic_posts = get_posts( array(
		// 'post_type' => 'topic',
		// 'post_status' => 'publish'
		// ) );
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
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$args = array(
			'role' => 'Subscriber',
			'orderby' => 'user_nicename',
			'order' => 'ASC'
		);
		//$to='sumeeth@bottlecapps.com';
		$sub = "Here is what's new on SIPN this week";
		//wp_mail( $to, $sub, $html, $headers );
		$users = get_users($args);
		foreach ($users as $user) {
			if ($user->unsubscribe == '0') {
				$emm = $user->user_email;
				$em = base64_encode($emm);



				$html = '<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
		<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
		<meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
		<meta name="color-scheme" content="light dark">
		<meta name="supported-color-schemes" content="light dark">
		<link rel="noopener" target="_blank" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
		<title>Weekly Feed Update</title>
		  <style>
			:root {color-scheme: light dark; supported-color-schemes: light dark;} 
			body {-webkit-text-size-adjust:100%; margin:0;padding:0;width:100%;background-color:#ffffff;}
			#outlook a {padding:0;}
			table {border-spacing: 0;}
			a[x-apple-data-detectors] {color: inherit!important; text-decoration: none!important; font-size: inherit!important; font-family: inherit!important; font-weight:inherit!important; line-height:inherit!important;}
			.ExternalClass {width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
		  </style>
		</head>
	<body style="width: 100%; background-color: #eee; font-family: Verdana, sans-serif;">
	<div aria-roledescription="email" role="article">
	<table role="presentation" style="width: 100%; background-color: #eee;">
	<tr>
	<td>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; mso-table-lspace:0pt; mso-table-rspace:0pt;" role="presentation">
	<tr>
	<td align="center" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px; background-color: #44555c; padding-top: 22px; padding-bottom: 22px;"> 
	 <img style="width: 150px;" src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/Sipn_logo.png" border="0" />	     
	</td>
	</tr>

	<tr>
	<td align="center" width="600" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">    
	<tr>
	<td align="center" width="600" style="text-align: center; padding-top: 30px; padding-bottom: 30px;">
	<table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;">
	<tr>
	<td>&nbsp;&nbsp;</td>
	<td align="center" width="540" style="width: 540px; color: #333333;">
			  <table align="center" width="540" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center; width: 540px; color: #333333;">    
			   <tr>
			   <td align="center" style="text-align: center; line-height: 30px; font-size: 26px; font-weight: bold; color: #333333;">
				This Week on SIPN
			   </td>
			   </tr>
			   <tr>
			   <td align="center" style="font-family: Google Sans, sans-serif, Verdana; line-height: 20px; font-size: 16px; color: #333333; text-align: center; padding-top: 10px;">
		        A bourbon community for the curious to the connoisseur. 
			   </td>
			   </tr>
			   </table>
	</td>
	<td>&nbsp;&nbsp;</td>
	</tr>
	</table>		   
	</td>
	</tr>
	<tr>
	<td width="600" align="center" style="text-align: center; width: 600px;">
			  <table width="600" align="center" style="text-align: center; width: 600px; border-spacing: 0;">
				 <tr>
				 <td align="center" width="600" style="text-align: center; padding: 0;">

			 <table align="center" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 600px; padding-top: 20px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-bottom: 15px; background-color: #44555c;">    
	         <tr>
			 <td>&nbsp;&nbsp;</td>
		     <td align="center" width="540" style="width: 540px;">
			  <table align="center" width="540" style="width: 540px; text-align: center;">
			  <tr>
			  <td align="center" style="font-family: Google Sans, sans-serif, Verdana; padding-bottom: 12px; line-height: 30px; font-size: 26px; font-weight: bold; text-align: center; color: #ffffff;">';
				$html .= "What's Buzzing";
				$html .= '</td>
			  </tr>';
				foreach ($replies2 as $value) {

					//print_r($aname);
					$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $value->ID);
					$cnt_list = $wpdb->get_results($query);
					$likes_count = $cnt_list[0]->cnt;
					if ($likes_count > 0) {
						//$m1[]=$value->ID;
						$m = $value->ID;
						$query = $wpdb->prepare("SELECT *  FROM `wp_posts` WHERE ID = '%d'", $value->ID);
						$repling = $wpdb->get_results($query);

						foreach ($repling as $value1) {
							$author_id = $value1->post_author;
							$author_details = get_user_by('id', $author_id);
							$aname = $author_details->data->display_name;
							$content = nl2br(strip_tags($value1->post_content));
							$query = array(
								'post_type' => 'reply',
								'post_status' => 'publish',
								'order' => 'ASC',
								'meta_query' => array(
									array(
										'key' => '_bbp_reply_to',
										'value' => $value1->ID,
									)
								),
							);

							$results = new WP_Query($query);
							$total_replies_count = $results->found_posts;
							$m1[] = $value->ID;
							$total1 = $likes_count + $total_replies_count;
							$total[] = $total1;
							$com = array_combine($m1, $total);
						}
					}
				}
				if ($com == '') {
					$com = array();
				}
				arsort($com);
				$keys = array_keys($com);
				for ($x = 0; $x < 3; $x++) {
					$ied = $keys[$x];
					$query34 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $ied);
					$cnt_list34 = $wpdb->get_results($query34);
					$likes_count34 = $cnt_list34[0]->cnt;
					$quer = $wpdb->prepare("SELECT *  FROM `wp_posts` WHERE ID = '%d'", $ied);
					$repli = $wpdb->get_results($quer);

					foreach ($repli as $value1) {
						$author_id = $value1->post_author;
						$author_details = get_user_by('id', $author_id);
						$aname = $author_details->data->display_name;
						$id = $value1->ID;
						$content = nl2br(strip_tags($value1->post_content));
						$count = strlen($content);
						if ($count > 160) {
							$showcontent = substr($content, 0, 150);
						} else {
							$showcontent = $content;
						}
						$query = array(
							'post_type' => 'reply',
							'post_status' => 'publish',
							'order' => 'ASC',
							'meta_query' => array(
								array(
									'key' => '_bbp_reply_to',
									'value' => $ied,
								)
							),
						);

						$results1 = new WP_Query($query);
						$total_replies_count1 = $results1->found_posts;
					}


					$html .= '<tr>
			  <td width="540" align="center" style="width: 540px; text-align: center; padding-bottom: 15px;"> 
			   <table width="540" align="center" style="width: 540px; text-align: center;">
			   <tr>
			   <td align="left" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 22px; font-size: 18px; color: #ffffff;">
			     <a style="color: #ffffff; text-decoration: none;" href="' . site_url() . '" target="_blank">' . $aname . '</a> 	   
			   </td>
			   </tr>
			   <tr>
			   <td align="left" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 16px; font-size: 13px; color: #ffffff;">
			   ' . $showcontent . '';

					if ($count > 160) {
						$html .= '....<a style="color: #b8a66e; text-decoration: none;" href="https://sipnbourbon.com/timeline/?q=' . $id . '">read more<a/>';
					}
					$html .= '</td>
			   </tr>
			   <tr>
			   <td align="left" width="540" style="width: 540px; padding-top: 4px; padding-bottom: 0; padding-left: 0; padding-right: 0;">
			    <table align="left" width="230" style="width: 230px; text-align: left;">
				<tr>
			    <td align="left" width="110" style="font-family: Google Sans, sans-serif, Verdana; padding-right: 12px; line-height: 16px; font-size: 13px; color: #ffffff; width: 100px; padding: 0;">
				 <table align="left" style="text-align: left;">
				 <tr>
			 	 <td style="vertical-align: middle; font-size: 0; line-height: 1; padding: 0;"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/likes.png" border="0" width="17" height="16" style="width: 17px; height: 16px;"></td>
				 <td style="vertical-align: middle; line-height: 1; padding-left: 6px; padding-right: 0; padding-top: 0; padding-bottom: 0; font-family: Google Sans, sans-serif, Verdana; line-height: 16px; font-size: 13px; color: #ffffff;">' . $likes_count34 . ' Likes</td>
				 </tr>
				 </table>
				</td>
				<td align="left" width="140" style="font-family: Google Sans, sans-serif, Verdana; line-height: 16px; font-size: 13px; color: #ffffff; width: 130px; padding: 0;">
			 	 <table align="left" style="text-align: left;">
				 <tr>
			 	 <td style="vertical-align: middle; font-size: 0; line-height: 1; padding: 0;"><img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/comment.png" border="0" width="16" height="15" style="width: 16px; height: 15px;"></td>
				 <td style="vertical-align: middle; line-height: 1; padding-left: 6px; padding-right: 0; padding-top: 0; padding-bottom: 0; font-family: Google Sans, sans-serif, Verdana; line-height: 16px; font-size: 13px; color: #ffffff;">' . $total_replies_count1 . ' Comments</td>
				 </tr>
				 </table>
				</td>
			    </tr>
	            </table>			
			   </td>
			   </tr>
			   </table>	  
			  </td>
			  </tr>';
				}

				$html .= '</table>
		     </td>
			 <td>&nbsp;&nbsp;</td>
		     </tr> 
	         </table>
			</td>
			</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px;">
	<table width="600" align="center" style="border-spacing: 0; text-align: center; width: 600px;">
	<tr>
	<td>&nbsp;&nbsp;</td>
			<td align="center" width="540" style="text-align: center; width: 540px;">
			<table width="540" align="center" style="border-spacing: 0; text-align: center; width: 540px;">
			 <tr>
			 <td align="center" width="540" style="width: 540px; padding-top: 20px; padding-bottom: 20px; padding-left: 0; padding-right: 0; text-align: center;">
			 <table align="center" width="540" border="0" cellpadding="0" cellspacing="0" style="width: 540px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #ffffff; border-spacing: 0;">    
	         <tr>
		     <td align="center" width="540" style="text-align: center; width: 540px;">
			  <table align="center" width="540" style="text-align: center; width: 540px;">
			  <tr>
			  <td width="540" align="center" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 30px; color: #b8a66e; font-size: 26px; font-weight: bold; text-align: center;">
		       Current Trending Bourbons
			  </td>
			  </tr>
			  <tr>
			  <td width="540" align="center" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 20px; font-size: 16px; text-align: center; padding-top: 10px; padding-bottom: 0; padding-left: 0; padding-right: 0;">
		       <table width="540" align="center" style="width: 540px; text-align: center;">
			   <tr>';
				foreach ($products as $product) {
					$prod_url = get_the_post_thumbnail_url($product->ID, 'full');
					$pname = $product->post_title;
					$link = get_permalink($product->ID);
					$html .= '<td width="180" align="center" style="text-align: center; width: 180px; vertical-align: top; color: #333333;">
			     <table width="180" align="center" style="width: 180px; text-align: center; border-spacing: 0; color: #333333;">
				 <tr>
			     <td align="center" width="180" height="160" style="text-align: center; height: 160px; width: 180px;">
				 <a href="' . $link . '" target="_blank"><img height="160" style="height: 160px;" src="' . $prod_url . '" /></a>
				 </td>
			     </tr>
				 <tr>
			     <td align="center" width="180" style="width: 180px; color: #333333; text-align: center; padding-top: 10px; font-family: Google Sans, sans-serif, Verdana; line-height: 18px; font-size: 15px; text-align: center;">
				 <a style="color: #333333; text-decoration: none;" href="' . $link . '" target="_blank"> ' . $pname . '</a>
				 </td>
			     </tr>
				 </table>
			   </td>';
				}
				$html .= '</tr>

			   </table>
			  </td>
			  </tr>
			  </table>
		     </td>
		     </tr> 
	         </table>
			 </td>
			 </tr>
			</table>
			</td>
	<td>&nbsp;&nbsp;</td>
	</tr>		
	</table>		
	</td>		
	</tr>
	<tr>
	<td align="center" width="600" style="text-align: center; width: 600px;">
	<table align="center" width="600" style="text-align: center; width: 600px; padding-top: 25px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-bottom: 10px; background-color: #44555c;">
	<tr>
	<td>&nbsp;&nbsp;</td>
			<td align="center" width="540" style="text-align: center; width: 540px;">	
			 <table align="center" width="540" border="0" cellpadding="0" cellspacing="0" style="text-align: center; width: 540px; mso-table-lspace:0pt; mso-table-rspace:0pt;">    
	         <tr>
		     <td align="center" width="540" style="text-align: center; width: 540px;">';
				if (isset($events) && !empty($events)) {
					$html .= '<table align="center" width="540" style="text-align: center; width: 540px;">
			  <tr><td align="center" width="540" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; padding-bottom: 10px; line-height: 30px; font-size: 26px; font-weight: bold; text-align: center; color: #ffffff;">
		       Upcoming Events
			  </td>
			  </tr>';
					foreach ($events as $event) {
						$startdate = date('jS M Y', strtotime(get_post_meta($event->ID, 'event_start_date', true)));
						$con = strip_tags($event->post_content);
						$contentss = substr($con, 0, 150);
						$eventname = strip_tags($event->post_name);
						$html .= '<tr>
						<td align="center" width="540" style="text-align: center; width: 540px; padding-bottom: 15px;">
						<table align="center" width="540" style="text-align: center; width: 540px;">
						<tr>
						<td align="left" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 22px; font-size: 18px; color: #ffffff;">
							' . $event->post_title . ' - ' . $startdate . '	   
						</td>
						</tr>
						<tr>
						<td align="left" style="width: 540px; font-family: Google Sans, sans-serif, Verdana; line-height: 16px; font-size: 13px; color: #ffffff;">

						Event Descriptions:' . $contentss . '.<a style="color: #b8a66e; text-decoration: none;" href="https://sipnbourbon.com/event/' . $eventname . '">....read more </a>
						</td>
						</tr>	   
						</table>';
					}
					$html .= '	  
							</td>
							</tr>';
				}
				$html .= '</table>
		     </td>
		     </tr> 
	         </table>
			 </td>
	<td>&nbsp;&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>	
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>

	<tr>
			<td style="background-color: #212121; padding-top: 20px; padding-bottom: 20px;">
			<table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #212121;">
			 <tr>
			 <td align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
			  <table align="center" width="90" style="text-align: center; font-size: 0; width: 90px;">
			  <tr>
			  <td><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" border="0" width="30" height="30" style="width: 29px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			  <td><a href="https://instagram.com/sipnbourbon?igshid=OGRjNzg3M2Y=" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			  <td><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" border="0" width="30" height="30" style="width: 30px; height: 30px; margin-left: 4px; margin-right: 4px;" /></a></td>
			 </tr>
			 </table>
			 </td>
			 </tr>
			 <tr>
			 <td align="center">
			 <table align="center" width="60" style="text-align: center; font-size: 0; width: 60px; padding-top: 12px;">
			 <tr>
			 <td align="center" width="60" style="text-align: center; font-size: 0; width: 60px;">
			  <img src="https://images.liquorapps.com/wst/EmailTemplates/sipn_mailer/footer_sipn_logo.png" border="0" style="width: 60px;" />
			 </td>
			 </tr>
			 </table>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" style="color: #fff; font-size: 12px; text-align: center;">
			  <table align="center" width="600px" border="0" cellpadding="0" cellspacing="0" style="width: 600px; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-top: 16px;">
			  <tr>
			  <td align="center" style="text-align: center; color: #ffffff;">
			  <p style="margin: 0; color: #ffffff;">Copyright ' . date('Y') . ' SIPN Bourbon, All rights reserved.</p>
			  <p style="margin-top: 1px; margin-bottom: 0; color: #ffffff;">You can <a style="color: #ffffff; text-decoration: none;" href="https://sipnbourbon.com/wp-json/users/v2/unsubscribeemail?email=' . $em . '"  target="_blank">  <span style="
	    text-decoration: underline;
	">unsubscribe</span> </a> at any time.</p>
			  </td>
			  </tr>
			  </table>
			 </td>
			 </tr>
			</table>
			</td>
			</tr>
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</div>
	</body>
	</html>';
				$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Sipn Bourbon <social@sipnbourbon.com>');
				$to = $user->user_email;
				wp_mail($to, $sub, $html, $headers);
			}
		}
	}
	// $m=['sumeethtechmatic80990@gmail.com','sumeeth@bottlecapps.com'];
	// foreach ($m as  $value) {
	//   	 wp_mail( $value, "Here is what's new on SIPN this week", $html, $headers );
	//   } 
	//     //wp_mail( 'sambasivarao@bottlecapps.com', 'Weekly Feed Updates', $html, $headers );
	// }

	// function ajaxtimelinecomments()
	// {
	// 	global $wpdb;
	// 	$item = $_POST;

	// 	$parent_id = $item['reply_id'];
	// 	$posts_per_page = -1;

	// 	if ($parent_id > 0) {
	// 		$args = [
	// 			'post_type' => 'reply',
	// 			'post_status' => 'publish',
	// 			'order' => 'DESC',
	// 			'meta_query' => array(
	// 				array(
	// 					'key' => '_bbp_reply_to',
	// 					'value' => $parent_id,
	// 				)
	// 			),
	// 			'numberposts' => $posts_per_page,
	// 		];

	// 		//print_r($args);
	// 		$replies = get_posts($args);
	// 		$ncommentcount = count($replies);
	// 		//print_r($replies);
	// 		$all_replies = array();
	// 		foreach ($replies as $reply) {
	// 			$author_id = $reply->post_author;
	// 			$author_details = get_user_by('id', $author_id);
	// 			$author_meta = get_user_meta($author_id);
	// 			$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');
	// 			if ($avatar == '') {
	// 				$avatar = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/chat/img-profile1.jpg';
	// 			}
	// 			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
	// 			$cnt_list = $wpdb->get_results($query);
	// 			$likes_count = $cnt_list[0]->cnt;

	// 			$reply_f_image = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'single-post-thumbnail');
	// 			if ($reply_f_image[0]) {
	// 				$reply_image_path = $reply_f_image[0];
	// 			} else {
	// 				$reply_image_path = '';
	// 			}
	// 			//$next_page = $page+1;
	// 			$reply_date = timeline_time_ago($reply->post_date);

	// 			$query = array(
	// 				'post_type' => 'reply',
	// 				'post_status' => 'publish',
	// 				'order' => 'ASC',
	// 				'meta_query' => array(
	// 					array(
	// 						'key' => '_bbp_reply_to',
	// 						'value' => $reply->ID,
	// 					)
	// 				),
	// 			);

	// 			$results = new WP_Query($query);
	// 			$total_replies_count = $results->found_posts; //// This is 0...
	// 			//echo $results->count_posts; //// This is 0...
	// 			wp_reset_postdata();

	// 			$replies = get_timeline_replies($reply->ID, 1);
	// 			$url = get_home_url() . "/timeline/?q=" . $reply->ID;

	// 			//if ( current_user_can( 'edit_reply', $reply->ID ) ) { by sumeeth
	// 			$cur_user_id = get_current_user_id();
	// 			if ($cur_user_id != 0) {   //for inner comments and sub comments bar link added by sumeeth
	// 				//$bid = bbp_get_user_profile_url($author_id);
	// 				$bid = get_home_url() . "/bar/user-".$author_id;
	// 			} else {
	// 				$bid = 0;
	// 			}
	// 			if ($cur_user_id == $author_id || $cur_user_id == 5) {
	// 				$edit_flag = 1;
	// 			} else {
	// 				$edit_flag = 0;
	// 			}
	// 			array_push($all_replies, array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'avatar' => $avatar, 'url' => $url, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'edit_flag' => $edit_flag, 'bid' => $bid, 'replies' => $replies, 'ncommentcount' => $ncommentcount));
	// 		}

	// 		//$all_replies['ncommentcount'] = $ncommentcount;

	// 		echo json_encode($all_replies);
	// 		exit;
	// 	} else {
	// 		return new WP_Error('rest_forbidden', 'Invalid Topic ID', array('status' => 403));
	// 	}
	// }

	function ajaxtimelinecomments()
	{
		global $wpdb;
		$item = $_POST;

		$parent_id = $item['reply_id'];
		$posts_per_page = -1;

		$cur_user_id = get_current_user_id();

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

		if ($parent_id > 0) {
			$args = [
				'post_type' => 'reply',
				'post_status' => 'publish',
				'order' => 'DESC',
				'author__not_in' => $mutually_blocked_users, // Exclude mutually blocked users
				'meta_query' => array(
					array(
						'key' => '_bbp_reply_to',
						'value' => $parent_id,
					)
				),
				'numberposts' => $posts_per_page,
			];

			$replies = get_posts($args);
			$ncommentcount = count($replies);
			$all_replies = array();

			foreach ($replies as $reply) {
				$author_id = $reply->post_author;
				$author_details = get_user_by('id', $author_id);
				$author_meta = get_user_meta($author_id);
				$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');
				if ($avatar == '') {
					$avatar = 'https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/chat/img-profile1.jpg';
				}

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $reply->ID);
				$cnt_list = $wpdb->get_results($query);
				$likes_count = $cnt_list[0]->cnt;

				$reply_f_image = wp_get_attachment_image_src(get_post_thumbnail_id($reply->ID), 'single-post-thumbnail');
				$reply_image_path = $reply_f_image[0] ?? '';

				$reply_date = timeline_time_ago($reply->post_date);

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

				$results = new WP_Query($query);
				$total_replies_count = $results->found_posts;
				wp_reset_postdata();

				$replies = get_timeline_replies($reply->ID, 1);
				$url = get_home_url() . "/timeline/?q=" . $reply->ID;

				$bid = ($cur_user_id != 0) ? get_home_url() . "/bar/user-" . $author_id : 0;
				$edit_flag = ($cur_user_id == $author_id || $cur_user_id == 5) ? 1 : 0;

				array_push($all_replies, array(
					'reply_id' => $reply->ID,
					'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)),
					'reply_image' => $reply_image_path,
					'reply_date' => $reply_date,
					'reply_gmt_date' => $reply->post_date,
					'total_replies_count' => $total_replies_count,
					'author' => $author_details->data->display_name,
					'author_id' => $author_id,
					'author_city' => $author_meta['city'][0],
					'author_state' => $author_meta['state'][0],
					'avatar' => $avatar,
					'url' => $url,
					'likes' => $likes_count,
					'is_liked' => get_like_flag($reply->ID),
					'edit_flag' => $edit_flag,
					'bid' => $bid,
					'replies' => $replies,
					'ncommentcount' => $ncommentcount
				));
			}

			echo json_encode($all_replies);
			exit;
		} else {
			return new WP_Error('rest_forbidden', 'Invalid Topic ID', array('status' => 403));
		}
	}

	function ajaxreportpost()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		if ($_POST['post_id'] && $_POST['post_url'] != '') {
			$to = 'social@sipnbourbon.com'; //social@sipnbourbon.com info@sipnbourbon.com

			$subject = 'Report post';
			$message = "Hello, <br>The following post is reported. please check the details below:<br>";
			$message .= "Post URL: " . $_POST['post_url'] . "<br>";
			$message .= "Reason: " . stripslashes($_POST['reason']) . "<br>";
			$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc: social@sipnbourbon.com');
			if (wp_mail($to, $subject, $message, $headers)) {
				echo json_encode(array('status' => true, 'message' => __('Reported Successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
			}
		}
		exit();
	}


	function ajaxdeletepost()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		$userid = $cur_user->data->ID;
		if ($_POST['reply_id'] != '') {

			$reply_id = $_POST['reply_id'];

			/* changes done by sumeeth
																		if ( ! current_user_can( 'edit_reply', $reply_id ) ) {
																				echo json_encode(array( 'message'=>'You do not have permission to delete that post.', 'status' => 0 ));
																		}
																		*/
			if (wp_trash_post($reply_id)) {
				$query = $wpdb->prepare("INSERT INTO `wp_users_activity` (`post_comment_id`, `post_type`, `deleted_by`) VALUES (%d, %s, %d)", $reply_id, 'post', $userid);
				$res = $wpdb->query($query);
				$query = $wpdb->prepare("UPDATE `notification_table` SET status='1', platform='Deletecommentfromweb' WHERE comment_id = '%d'", $reply_id);
				$res = $wpdb->query($query);
				reward_points("remove",(int)8,$userid, $reply_id);
				//for delete post
				$query = $wpdb->prepare("UPDATE `notification_table` SET status='1', platform='Deletepostfromweb' WHERE content = '%d'", $reply_id);
				$res = $wpdb->query($query);
				reward_points("remove",(int)6,$userid, $reply_id);
				echo json_encode(array("message" => "your post is deleted successfully.", "status" => 1));
			} else {
				echo json_encode(array('message' => 'Your post is not deleted.', 'status' => 0));
			}
		} else {
			echo json_encode(array('message' => 'Your post is not deleted. Please check the provided data.', 'status' => 0));
		}
		exit();
	}

	function ajaxaddposttotimeline()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$item['topic_id'] = 35832;

		if ($_POST['rid'] == '' || $_POST['rid'] <= 0) {
			$item['reply_to'] = 35832;
		} else {
			$item['reply_to'] = $_POST['rid'];
		}

		$item['reply'] = $_POST['reply'];
		$item['reply_img'] = $_POST['reply_img'];



		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		// if($item['reply'] != '' && $item['topic_id']>0 && $item['reply_to']>0){
		if ($item['topic_id'] > 0 && $item['reply_to'] > 0) { //added by sumeeth
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
			$from_device = "website";
			$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
			$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'from_device' => $from_device, 'reply_to' => $item['reply_to']);
			//get the topic IP so we can reset it later
			$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

			$new_reply_id = bbp_insert_reply($reply_data, $reply_meta);
			if ($new_reply_id) {



				$message = reward_points("add",(int)8,$cur_user->data->ID,  $new_reply_id);

				if(!empty($message)){
					$reward_message = $message;
				} else{
					$reward_message = 1;

				}

			
				
				$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $item['reply_to']);
				$lists = $wpdb->get_results($queryuid);
				$uid = $lists[0]->uid;

				$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $cur_user->data->ID);
				$listss = $wpdb->get_results($queryuname);
				$uname = $listss[0]->uname;

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
				$arrNotification["body"] = $uname . ' Commented on your Post';
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
				include_once 'fcm.php';
				$fcm = new FCM();
				if ($uid != $cur_user->data->ID) {
					$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`, `comment_id`) VALUES (%s, %d, %s, %d, %d, %s, %d)", 'Comment', $prep, $content_text, $cur_user->data->ID, $uid, 'Commentfromwebsite', $new_reply_id);
					$res = $wpdb->query($querystore);
					$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Commentfromwebsite");
				}


				if ($attachment_id) {
					add_post_meta($new_reply_id, '_thumbnail_id', $attachment_id);
				}
				
			
				// return array("new_reply_id" => $new_reply_id, 
				// 							"message" => "your post is submitted successfully.",
				// 						'reward_message' => $reward_message);

				echo json_encode([
					    "new_reply_id" => $new_reply_id,
					    "message" => "Your post is submitted successfully.",
					    "reward_message" => $reward_message
					]);
					wp_die();
			} else {
				return new WP_Error('rest_forbidden', 'Your post is not published.', array('status' => 403));
			}
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
		}

		exit();
	}

	function ajaxeditpost()
	{

		global $wpdb;

		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$item['topic_id'] = 35832;

		if ($_POST['rid'] == '' || $_POST['rid'] <= 0) {
			$item['reply_to'] = 35832;
		} else {
			$item['reply_to'] = $_POST['rid'];
		}
		if (isset($_POST['pid']) == '') {
			$pid = '';
		} else {
			$pid = $_POST['pid'];
		}
		if (isset($_POST['tagged_location']) == '') {
			$lid = 'null';
		} else {
			$lid = $_POST['tagged_location'];
		}

		if (isset($_POST['from_device']) == '') {
			$from_device = "website";
		} else {
			$from_device = "website";
		}
		$item['reply_id'] = $_POST['rid'];
		$item['reply'] = $_POST['reply'];

		$imgdatas = [];
		if (isset($_POST['img10']) != '') {
			$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img10']);
			$imgdatas[0] = $img;
		}
		if (isset($_POST['img11']) != '') {
			$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img11']);
			$imgdatas[1] = $img;
		}
		if (isset($_POST['img12']) != '') {
			$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img12']);
			$imgdatas[2] = $img;
		}
		$imgdatas = array_filter($imgdatas, fn($value) => !is_null($value) && !empty ($value));

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);

		if ($item['reply_id'] > 0) { //added by sumeeth
			if (isset($imgdatas) && !empty($imgdatas)) {

				foreach ($imgdatas as $key => $imgdata1) {

					$imgdata = base64_decode($imgdata1);

					$f = finfo_open();
					$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

					$type_file = explode('/', $mime_type);

					$avatar = time() . $key . '.' . 'webp';

					$uploaddir = wp_upload_dir();
					$myDirPath = $uploaddir["path"];
					$myDirUrl = $uploaddir["url"];




					file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

					$filename = $myDirUrl . '/' . basename($avatar);
					$wp_filetype = wp_check_filetype(basename($filename), null);

					$uploadfile = $uploaddir["path"] . '/' . basename($filename);
					$uploadfile1[] = $uploaddir["path"] . '/' . basename($filename);


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

						$attid[] = $attachment_id;
						$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
						wp_update_attachment_metadata($attachment_id, $attach_data);
					}
				}

			} else {
				$_POST['delete_image'] = 1;

			}


			$reply_id = $item['reply_id'];


			$reply_content = $item['reply'];
			$querypid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$pid' WHERE post_id =$reply_id AND meta_key='_bbp_product_id'");

			$res123 = $wpdb->query($querypid);

			$querylid = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$lid' WHERE post_id =$reply_id AND meta_key='_bbp_tagged_location'");

			$res1234 = $wpdb->query($querylid);
			$queryfrom = $wpdb->prepare("UPDATE wp_postmeta SET meta_value='$from_device' WHERE post_id =$reply_id AND meta_key='_bbp_from_device'");

			$res12345 = $wpdb->query($queryfrom);
			$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'product_id' => $pid, 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
			$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'reply_to' => $item['reply_to']);

			$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);


			$topic_id = bbp_get_reply_topic_id($reply_id);


			$reply_data = apply_filters('bbp_edit_reply_pre_insert', array(
				'ID' => $reply_id,
				'post_content' => $reply_content,
				'post_parent' => $topic_id,
				'post_author' => $reply_data['post_author'],
				'post_type' => 'reply'
			));

			$reply_id = wp_update_post($reply_data);
			if ($_POST['delete_image'] == 1) {

				update_post_meta($reply_id, '_thumbnail_id', '');
			}

			if (wp_update_post($reply_data)) {
				if ($attid) {
					$a = json_encode($attid);
					if (!empty($a)) {
						$b = trim($a, "[ ]");
					}
					//delete_post_thumbnail($new_reply_id);
					update_post_meta($reply_id, '_thumbnail_id', $b);
					//add_post_meta($reply_id, '_thumbnail_id', $b);
				}

				echo json_encode(array("reply_image" => $b, "message" => "your post is updated successfully."));
			} else {
				return new WP_Error('rest_forbidden', 'Your post is not updated.', array('status' => 403));
			}
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
		}
		exit();
	}

	function ajaxaddcommenttotimeline()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		if ($cur_user->data->user_email != '') {
			$item['topic_id'] = 35832;

			if ($_POST['rid'] == '' || $_POST['rid'] <= 0) {
				$item['reply_to'] = 35832;
			} else {
				$item['reply_to'] = $_POST['rid'];
			}
			if (isset($_POST['pid']) == '') {
				$pid = '';
			} else {
				$pid = $_POST['pid'];
			}
			//for location
			if (isset($_POST['tagged_location']) == '') {
				$lid = '';
			} else {
				$lid = $_POST['tagged_location'];
			}

			$item['reply'] = $_POST['reply'];
			$item['reply_img'] = $_POST['reply_img'];
			//print_r($_POST);exit;
			$imgdatas = [];
			if (isset($_POST['img0']) != '') {
				$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img0']);
				$imgdatas[0] = $img;
			}
			if (isset($_POST['img1']) != '') {
				$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img1']);
				$imgdatas[1] = $img;
			}
			if (isset($_POST['img2']) != '') {
				$img = preg_replace('#data:image/[^;]+;base64,#', '', $_POST['img2']);
				$imgdatas[2] = $img;
			}

			$imgdatas = array_filter($imgdatas, fn($value) => !is_null($value) && !empty ($value));
			//print_r($imgdatas);exit;


			$cur_user = wp_get_current_user();
			$user_details = get_user_meta($cur_user->data->ID);
			// if($item['reply'] != '' && $item['topic_id']>0 && $item['reply_to']>0){
			if ($item['topic_id'] > 0 && $item['reply_to'] > 0) { //added by sumeeth
				if (isset($imgdatas) && $imgdatas != '') {
					//for base64 multiimages
					//print_r($item['reply_img']);exit;
					//$img = preg_replace('#data:image/[^;]+;base64,#', '', $item['reply_img']);
					//print_r($img);exit;
					//$imgdatas = explode("img_url", $img);
					//array_pop($imgdatas);
					//print_r($imgdatas);exit;
					//$imgdata='';
					if (!empty($imgdatas)) {
						foreach ($imgdatas as $key => $imgdata1) {
							//$imgdata1[]=$imgdata1;
							//$imgdata = base64_decode($item["reply_img"]);
							$imgdata = base64_decode($imgdata1);
							//echo "hello";
							//print_r($imgdata);exit;
							$f = finfo_open();
							$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
							//$mime_type='image/jpeg';
							//print_r($mime_type);exit;
							$type_file = explode('/', $mime_type);
							//print_r($type_file);exit;
							$avatar = time() . $key . '.' . 'webp';

							$uploaddir = wp_upload_dir();
							$myDirPath = $uploaddir["path"];
							$myDirUrl = $uploaddir["url"];




							file_put_contents($uploaddir["path"] . '/' . $avatar, $imgdata);

							$filename = $myDirUrl . '/' . basename($avatar);
							$wp_filetype = wp_check_filetype(basename($filename), null);
							//print_r($wp_filetype);exit;
							$uploadfile = $uploaddir["path"] . '/' . basename($filename);
							$uploadfile1[] = $uploaddir["path"] . '/' . basename($filename);
							//print_r($uploadfile);exit;         

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
								//print_r($attachment_id);exit;
								$attid[] = $attachment_id;
								$attach_data = wp_generate_attachment_metadata($attachment_id, $uploadfile);
								wp_update_attachment_metadata($attachment_id, $attach_data);
							}
						}
					}
					//print_r($uploadfile1);exit;
					// print_r($imgdata1);exit;
				} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
					//update_user_meta($user_id, 'wp_user_avatar', '');
				}
				//from device
				$from_device = "website";
				$reply_data = array('reply_to' => $item['reply_to'], 'post_parent' => $item['topic_id'], 'post_content' => $item['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
				$reply_meta = array('forum_id' => '0', 'topic_id' => $item['topic_id'], 'product_id' => $pid, 'tagged_location' => $lid, 'from_device' => $from_device, 'reply_to' => $item['reply_to']);
				//get the topic IP so we can reset it later
				$ip = get_post_meta($item['topic_id'], '_bbp_author_ip', false);

				$new_reply_id = bbp_insert_reply($reply_data, $reply_meta);
				if ($new_reply_id) {
					if ($attid) {
						$a = json_encode($attid);
						if (!empty($a)) {
							$b = trim($a, "[ ]");
						}
						//delete_post_thumbnail($new_reply_id);
						add_post_meta($new_reply_id, '_thumbnail_id', $b);
					}
					if($item['reply_to'] == 35832){
					$reward_msg = reward_points("add",(int)6, $cur_user->data->ID, $new_reply_id);
					update_rewards();
				}else{
					$reward_msg = reward_points("add",(int)8,$cur_user->data->ID, $new_reply_id);
				}

				if (empty($reward_msg)) {
					$reward_msg = 1;
				} else {
					$reward_msg;
				}


					// if($attachment_id){
					// 	delete_post_thumbnail($new_reply_id);
					// 	add_post_meta($new_reply_id, '_thumbnail_id', $attachment_id);
					// }
					//$attach_url = wp_get_attachment_url($attachment_id);

					echo json_encode(array(	"new_reply_id" => $new_reply_id, 
																	"user_name" => $cur_user->data->display_name, 	
																	"message" => "your post is submitted successfully.",
																	"reward_message" => $reward_msg) );
					wp_die();

				} else {
					return new WP_Error('rest_forbidden', 'Your post is not published.', array('status' => 403));
				}
			} else {
				return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
			}
		} else {
			echo json_encode(array('status' => false, 'message' => __('Invalid user.')));
		}

		exit();
	}

	function ajaxloadtimeline()
	{
		global $wpdb;

		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Invalid request.')));
		}

		if (is_user_logged_in()) {
			$cur_user_id = get_current_user_id();
			$current_user_details = get_user_by('id', $cur_user_id);
			$current_user_meta = get_user_meta($cur_user_id);
			$cur_user_avatar = wp_get_attachment_image_url($current_user_meta['wp_user_avatar'][0], 'thumbnail');

			if (!$cur_user_avatar) {
				$cur_user_avatar = get_avatar_url($cur_user_id);
			}
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;

		if ($_POST['page']) {
			$page = filter_var($_POST['page'], FILTER_VALIDATE_INT);
			$timeline_res = get_timeline_list($page, 10);
			if ($page == 2) {
				$tmlcounte = 11;
			} else if ($page == 3) {
				$tmlcounte = 21;
			} else if ($page == 4) {
				$tmlcounte = 31;
			} else if ($page == 5) {
				$tmlcounte = 41;
			} else if ($page == 6) {
				$tmlcounte = 51;
			} else if ($page == 7) {
				$tmlcounte = 61;
			} else if ($page == 8) {
				$tmlcounte = 71;
			} else if ($page == 9) {
				$tmlcounte = 81;
			} else if ($page == 10) {
				$tmlcounte = 91;
			} else if ($page == 11) {
				$tmlcounte = 101;
			} else if ($page == 12) {
				$tmlcounte = 111;
			} else if ($page == 13) {
				$tmlcounte = 121;
			} else if ($page == 14) {
				$tmlcounte = 131;
			} else if ($page == 15) {
				$tmlcounte = 141;
			} else if ($page == 16) {
				$tmlcounte = 151;
			} else if ($page == 17) {
				$tmlcounte = 161;
			} else if ($page == 18) {
				$tmlcounte = 171;
			} else if ($page == 19) {
				$tmlcounte = 181;
			} else if ($page == 20) {
				$tmlcounte = 191;
			} else if ($page == 21) {
				$tmlcounte = 201;
			} else if ($page == 22) {
				$tmlcounte = 211;
			} else if ($page == 23) {
				$tmlcounte = 221;
			} else if ($page == 24) {
				$tmlcounte = 231;
			} else if ($page == 25) {
				$tmlcounte = 241;
			} else if ($page == 26) {
				$tmlcounte = 251;
			} else if ($page == 27) {
				$tmlcounte = 261;
			} else if ($page == 28) {
				$tmlcounte = 271;
			} else if ($page == 29) {
				$tmlcounte = 281;
			} else if ($page == 30) {
				$tmlcounte = 291;
			} else if ($page == 31) {
				$tmlcounte = 301;
			}



			$tmp1 = array();
			birthday_rewards_web();
			update_rewards();
			//echo"<pre>";print_r($timeline_res);exit;
			foreach ($timeline_res['replies'] as $reply) {
				?>
				<div class="inner-content" id="msg-<?php echo $reply['reply_id'] ?>">
					<div class="user-feed">
						<div class="user-profile">

							<div class="dropdown">
								<!-- <button class="dropbtn">...</button> -->
								<img class="threedots"
									src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/vertical-dots.png" height="144"
									width="39">
								<div class="dropdown-content">
									<?php $cur_user_id = get_current_user_id();
									$aid = $reply['author_id'];
									if ($cur_user_id == $aid || $cur_user_id == 5) { ?>
										<a href="javascript:void(0);" class="edit-tl-post" rptitle="<?php echo $reply['product_title']; ?>"
											rimage="<?php echo $reply['reply_image']; ?>" rid="<?php echo $reply['reply_id']; ?>"
											pid="<?php echo $reply['product_id']; ?>"
											locid="<?php echo $reply['tagged_location']; ?>"><span><i
													class="far fa-edit bar-edit"></i></span>Edit</a>
										<a href="javascript:void(0);" class="delete-tl-post"
											rid="<?php echo $reply['reply_id']; ?>"><span><i class="fa fa-trash"></i></span>Delete</a>
									<?php } ?>
									<!--   <a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $reply['reply_id']; ?>"post_url="<?php echo $reply['url']; ?>"><span><i class="fa fa-exclamation-circle"></i></span>Report</a> -->
									<!--    added by sumeeth -->
									<?php if (is_user_logged_in() && $cur_user_id != $aid) { ?>

										<a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $reply['reply_id']; ?>"
											post_url="<?php echo $reply['url']; ?>"><span><i
													class="fa fa-exclamation-circle"></i></span>Report</a>
									<?php } else if (!is_user_logged_in()) { ?>

											<a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" class="report-tl-post"
												rid="<?php echo $reply['reply_id']; ?>" post_url="<?php echo $reply['url']; ?>"><span><i
														class="fa fa-exclamation-circle"></i></span>Report</a>
									<?php } ?>
									<!--    added by sumeeth -->
								</div>
							</div>


							<div class="profile-in">
								<div class="profile-pic">

	    <a href="<?php if (is_user_logged_in()) {
	        echo bbp_get_user_profile_url($reply['author_id']);
	    } else {
	        echo "/login";
	    } ?>">
	        <img src="<?php echo $reply['avatar']; ?>" width="60" height="60">
	    </a>

	    <div class="user-name">

	        <?php if (is_user_logged_in()) { ?>
	            <a href="<?php echo bbp_get_user_profile_url($reply['author_id']); ?>">
	                <?php echo $reply['author']; ?>
	            </a>
	        <?php } else { ?>
	            <a href="/login"><?php echo $reply['author']; ?></a>
	        <?php } ?>

	        <br>

	        <?php
	        // ⭐ ADD THIS PRODUCT BLOCK
	        if (!empty($reply['product_id']) && get_post_type($reply['product_id']) == 'product') {

	            $the_product = wc_get_product($reply['product_id']);

	            if ($the_product) { ?>

	                <span class="sumss">
	                    <a href="<?php echo get_permalink($reply['product_id']); ?>">
	                        <?php echo esc_html($the_product->get_name()); ?>
	                    </a>
	                </span>
	                <br>

	        <?php }
	        }
	        ?>

	        <span class="sumloc">
	            <?php
	            if ($reply['tagged_location'] != '') {
	                echo $reply['reply_date'] . ', ' . $reply['tagged_location'];
	            } else {
	                echo $reply['reply_date'];
	            }
	            ?>
	        </span>

	    </div>

	</div>


							</div>
							<div class="user-msg">


								<?php $content = nl2br(strip_tags($reply['reply']));

								$count = strlen($content);
								if ($count > 160) {
									$showcontent = substr($content, 0, 150);
								} else {
									$showcontent = $content;
								}
								echo $showcontent;
								if ($count > 160) { ?><a class="read-more-show hide" href="#"
										id="<?php echo $reply['reply_id']; ?>">&nbsp;&nbsp;Read More</a><span
										class="read-more-content"><?php echo substr($content, 150); ?> <a class="read-more-hide hide"
											href="#" more-id="<?php echo $reply['reply_id']; ?>">&nbsp;&nbsp;Read Less</a></span>
								<?php }
								?>

							</div>
						</div>
					</div>
					<div class="upload-image  <?php if (($reply['reply_image'] == '' && $reply['product_image'] != '')) {
						echo "imgpro";
					} ?>" <?php if ($reply['product_image'] != '') { ?> oncontextmenu="return false;" <?php } ?>>


						<?php if ($reply['reply_image'] != '') { ?>
							<div class="section1<?php echo $page; ?>">
								<div class="slideshow-container">

									<?php if ($reply['reply_image']) {
										$a = $reply['reply_image'];
										$b = explode(',', $a);
										$coui = count($b);
										foreach ($b as $key => $value) { ?>

											<div class="mySlides1">
												<a href="javascript:void(0);"><img src="<?php echo $value; ?>" width="100%" alt=""> </a>
											</div> <?php } ?>

										<a class="prev" href="#." onclick="slide1[<?php echo $tmlcounte; ?>].plusSlides1(-1)" <?php if ($coui == 1) { ?> style="display: none;" <?php } ?>>❮</a>
										<a class="next" href="#." onclick="slide1[<?php echo $tmlcounte; ?>].plusSlides1(1)" <?php if ($coui == 1) { ?> style="display: none;" <?php } ?>>❯</a>
										<?php $tmp1[] = $tmlcounte; ?>

										<div class="dot-container" <?php if ($coui == 1) { ?> style="display: none;" <?php } ?>>
											<?php if ($reply['reply_image']) {
												$a = $reply['reply_image'];
												$b = explode(',', $a);
												$imgcnt = 1;
												foreach ($b as $key => $value) { ?>

													<span class="dot1"
														onclick="slide1[<?php echo $tmlcounte; ?>].currentSlide1(<?php echo $imgcnt; ?>)"></span>
													<?php $imgcnt++;
												}
											} ?>

										</div>

									<?php } ?>
								</div>
							</div> <?php } else if ($reply['reply_image'] != '' && $reply['product_image'] != '') { ?>
								<a href="javascript:void(0);"><img src="<?php echo $reply['product_image']; ?>" width="100%" alt=""></a>
						<?php } else if ($reply['product_image'] != '') { ?>
									<a href="javascript:void(0);"><img src="<?php echo $reply['product_image']; ?>" width="100%" alt=""></a>
						<?php } ?>


					</div>

					<div class="img-options post-lcs">
						<div class="options1 options <?php if ($reply['is_liked'] == '1') { ?>active<?php } ?>">
							<?php if (is_user_logged_in()) { ?>
								<a href="#." class="like_timeline" id="like" liked="<?php echo $reply['is_liked']; ?>"
									rid="<?php echo $reply['reply_id']; ?>"> <span class="likecomment"><span class="onlylike"><?php if (($reply['likes'] != '0')) {
										   echo $reply['likes']; ?></span><?php } else {
										   echo $reply['is_liked'] . "</span>";
									   } ?></a>
							<?php } else { ?>
								<a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="like"> <span class="likecomment"><span
											class="onlylike"><?php if (($reply['likes'] != '0')) {
												echo $reply['likes']; ?></span><?php } else {
												echo "</span>";
											} ?></a><?php } ?>
						</div>
						<div class="options2 options">
							<?php if (is_user_logged_in() && $cur_user->data->validate_email == '1') { ?>



								<a href="#." id="comment" data-toggle="modal" data-backdrop="static" data-target="#openpopup">
									<?php echo $reply['total_replies_count']; ?></a>




							<?php } else { ?>
								<?php if (is_user_logged_in()) { ?>
									<a href="#." id="comment" class="replies_list rlist_<?php echo $reply['reply_id']; ?>"
										rid="<?php echo $reply['reply_id']; ?>"> <?php echo $reply['total_replies_count']; ?></a>

								<?php } else { ?>
									<a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="comment">
										<?php echo $reply['total_replies_count']; ?></a>
								<?php } ?>

							<?php } ?>

						</div>
						<div class="options3 options">
							<!--    added by sumeeth -->
							<a href="#." class="copy-share-link" id="share"
								link="<?php echo site_url(); ?>/timeline/?q=<?php echo $reply['reply_id']; ?>"></a>
							<!--  <a href="#." class="copy-share-link" id="share" link="<?php echo site_url(); ?>/timeline/?q=<?php echo $reply['reply_id']; ?>"> Share</a> -->
						</div>
						<?php if (!empty($reply['product_id']) && get_post_type($reply['product_id']) == 'product') {

	    $the_product = wc_get_product($reply['product_id']);

	    if ($the_product) {

	        $sku = $the_product->get_sku();
	        $pid = $the_product->get_id();

	        if (!empty($sku)) { ?>

	            <div>
	                <a href="<?php echo esc_url(
	                    add_query_arg(
	                        array(
	                            'prod_id' => $sku,
	                            'prid' => $pid
	                        ),
	                        site_url('/buy-now/')
	                    )
	                ); ?>" class="buynow post-buynow">
	                    <button class="search">Buy Now</button>
	                </a>
	            </div>

	<?php
	        }
	    }
	}
	?>
					</div>
					<?php if (is_user_logged_in() && $cur_user->data->validate_email == '0') { ?>
						<div class="post-comments">
							<input type="text" placeholder="Post your Comment" class="pcommentindex_<?php echo $reply['reply_id'] ?>"
								id="commentindex_<?php echo $reply['reply_id'] ?>" />
							<button class="submitRepliesWrapperindex post_cmnt_new" rid="<?php echo $reply['reply_id'] ?>"><img
									src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
						</div>
					<?php } ?>
				</div>

				<?php $tmlcounte++;
			}
		} ?>
		<input type="hidden" id="totalcountim1<?php echo $page; ?>" class="totalcountim1"
			value="<?php echo implode(',', $tmp1); ?>">
		<?php

		echo '
	<script type="text/javascript">

	$(".read-more-content").addClass("hide");
	$(".read-more-show, .read-more-hide").removeClass("hide");

	$(".read-more-show").on("click", function(e) {
	  $(this).next(".read-more-content").removeClass("hide");
	  $(this).addClass("hide");
	  e.preventDefault();
	});

	$(".read-more-hide").on("click", function(e) {
	  $(this).parent(".read-more-content").addClass("hide");
	  var moreid=$(this).attr("more-id");
	  $(".read-more-show#"+moreid).removeClass("hide");
	  e.preventDefault();
	});


	var slide1=[]; 

	var strr=$("#totalcountim1' . $page . '").val();
	var str_arrayr = strr.split(",");
	//alert(str_arrayr);
	//alert(str_arrayr.length);
	for(var r = 0; r < str_arrayr.length; r++) {
	//alert(r);
	//alert(str_arrayr[r]);
	  slide1[str_arrayr[r]] = new CreateSlide' . $page . '(r);
	  
	   
	   
	}
	function CreateSlide' . $page . '(index) {
	    this.slideContainer = document.getElementsByClassName("section1' . $page . '")[index];
	    this.slideIndex = 1;
	    console.log(this.slideContainer);
	    this.plusSlides1 = function(n) {
	        this.showSlides(this.slideIndex += n);
	    };
	    this.currentSlide1 = function(n) {
	        this.showSlides(this.slideIndex = n);
	    };
	    this.showSlides = function(n) {
	        var i;
	        var slides1 = this.slideContainer.getElementsByClassName("mySlides1");
	       // alert(slides1.length);
	        var dots = this.slideContainer.getElementsByClassName("dot1");
	        if (n > slides1.length) {
	            this.slideIndex = 1
	        }
	        if (n < 1) {
	            this.slideIndex = slides1.length
	        }
	        for (i = 0; i < slides1.length; i++) {
	            slides1[i].style.display = "none";
	        }
	        for (i = 0; i < dots.length; i++) {
	            dots[i].className = dots[i].className.replace(" active", "");
	        }
	        slides1[this.slideIndex - 1].style.display = "block";
	        dots[this.slideIndex - 1].className += " active";
	    }
	    this.showSlides(1);
	}
	</script>';

		exit();
	}

	function ajaxproductdelete()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;

		if (get_user_bar_id($user_id) != $_POST['bar_id']) {
			echo json_encode(array('status' => false, 'message' => __('You are not authorized.')));
		} else {
			if ($_POST['product_id'] != '' && $_POST['shelf_id'] != '' && $_POST['bar_id'] != '') {
				$query = $wpdb->prepare("DELETE FROM `wp_bar_shelves_products` WHERE shelve_id = '%d' AND product_id = '%d'", $_POST["shelf_id"], $_POST["product_id"]);
				//print_r($query);exit;
				$res = $wpdb->query($query);
				echo json_encode(array('status' => true, 'message' => __('Product successfully removed from the your bar.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Invalid data.')));
			}
		}
		exit();
	}

	function ajaxproductsreorder()
	{
		global $wpdb;

		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;

		if (get_user_bar_id($user_id) != $_POST['bar_id']) {
			echo json_encode(array('status' => false, 'message' => __('You are not authorized.')));
		} else {
			if ($_POST['products_order'] != '' && $_POST['shelf_id'] != '' && $_POST['bar_id'] != '') {

				$p_cnt = 1;
				$s_cnt = 1;
				foreach ($_POST['products_order'] as $prod) {
					if ($_POST['originalshelfid'] == $_POST['shelf_id']) {
						$query = $wpdb->prepare("UPDATE `wp_bar_shelves_products` SET weight='%d' WHERE shelve_id = '%d' AND product_id = '%d'", $p_cnt, $_POST["shelf_id"], $prod);
						$res = $wpdb->query($query);
						$p_cnt++;
					} else {
						$query_delete = $wpdb->prepare("delete from `wp_bar_shelves_products` WHERE shelve_id = '%d' AND product_id = '%d'", $_POST["originalshelfid"], $prod);
						$response = $wpdb->query($query_delete);
						if ($response) {
							$query_insert = $wpdb->prepare("INSERT INTO `wp_bar_shelves_products` (weight, shelve_id, product_id) VALUES (%d, %d, %d)", $s_cnt, $_POST["shelf_id"], $prod);
							$res = $wpdb->query($query_insert);
							$s_cnt++;
						}
					}

				}

				echo json_encode(array('status' => true, 'message' => __('Bar updated successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Invalid data.')));
			}
		}
		exit();
	}

	function ajaxproductsreordercrossshelf()
	{
		global $wpdb;

		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
			exit;
		}

		$raw_payload = stripslashes($_POST['payload']);
		error_log('Raw Payload: ' . $raw_payload);
		$payload = json_decode($raw_payload, true);

		if ($payload === null) {
			echo json_encode(array('status' => false, 'message' => __('Failed to decode JSON payload.')));
			exit;
		}

		$cur_user = wp_get_current_user();
		$user_id = $cur_user->ID;

		if (get_user_bar_id($user_id) != $payload['bar_id']) {
			echo json_encode(array('status' => false, 'message' => __('You are not authorized.')));
			exit;
		}

		$success = true;

		$delete_shelf_products = $wpdb->prepare("DELETE FROM `wp_bar_shelves_products` WHERE shelve_id = %d", $payload["shelve_id"]);
		$res = $wpdb->query($delete_shelf_products);

		foreach ($payload['product'] as $product) {

			$query = $wpdb->prepare(
				"INSERT INTO `wp_bar_shelves_products` (shelve_id, product_id, weight) VALUES (%d, %d, %d)",
				$product['new_shelve_id'],
				$product['pid'],
				$product['order']
			);
			$res = $wpdb->query($query);

			if ($product['old_shelve_id'] !== $product['new_shelve_id']) {
				$delete_query = $wpdb->prepare(
					"DELETE FROM `wp_bar_shelves_products` WHERE shelve_id = %d AND product_id = %d",
					$product['old_shelve_id'],
					$product['pid']
				);
				$wpdb->query($delete_query);

				$update_query = $wpdb->prepare(
					"UPDATE `wp_bar_shelves_products` 
	                SET weight = weight - 1 
	                WHERE shelve_id = %d AND weight > %d",
					$product['old_shelve_id'],
					$product['order']
				);
				$wpdb->query($update_query);
			}
			if ($res === false) {
				$success = false;
				error_log('Database error: ' . $wpdb->last_error);
				break;
			}
		}

		if ($success) {
			echo json_encode(array('status' => true, 'message' => __('Bar updated successfully.')));
		} else {
			echo json_encode(array('status' => false, 'message' => __('An error occurred while updating the bar.')));
		}

		exit();
	}

	function ajaxsavebarchanges()
	{
		global $wpdb;
		$raw_payload = stripslashes($_POST['payload']);
		$payload = json_decode($raw_payload, true);

		$success = true;
		$namesuccess = true;
		$response = [];

		// Handle deleted bottles
		if (isset($payload['deleted_bottles'])) {
			foreach ($payload['deleted_bottles'] as $bottles) {
				$query = $wpdb->prepare("UPDATE `wp_bar_shelves_products` SET product_id = 0 WHERE shelve_id = %d AND weight = %d", $bottles['shelf_id'], $bottles['weight']);
				$res = $wpdb->query($query);

				if ($res === false) {
					$success = false;
					error_log('Database error: ' . $wpdb->last_error);
					$response['deleted_bottles'] = ['status' => false, 'message' => __('An error occurred while deleting bottles.')];
					break;
				}
			}
			if ($success) {
				$response['deleted_bottles'] = ['status' => true, 'message' => __('Deleted bottles updated successfully.')];
			}
		}



		// Handle shelf name updates
		if (isset($payload['shelfnames'])) {
			foreach ($payload['shelfnames'] as $names) {
				$query = $wpdb->prepare("UPDATE `wp_bar_shelves` SET `name` = %s WHERE id = %d", $names['shelf_name'], $names['shelf_id']);
				$res = $wpdb->query($query);

				if ($res === false) {
					$namesuccess = false;
					error_log('Database error: ' . $wpdb->last_error);
					$response['shelfnames'] = ['status' => false, 'message' => __('An error occurred while updating shelf names.')];
					break;
				}
			}
		}

		if ($namesuccess) {
			$response['shelfnames'] = ['status' => true, 'message' => __('Shelf names updated successfully.')];
		}

		// Combine responses
		wp_send_json($response);
	}

	function ajaxupdateshelfname()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;

		if (get_user_bar_id($user_id) != $_POST['bar_id']) {
			echo json_encode(array('status' => false, 'message' => __('You are not authorized.')));
		} else {
			if ($_POST['shelf_name'] != '' && $_POST['shelf_id'] != '' && $_POST['bar_id'] != '') {

				$query = $wpdb->prepare("UPDATE `wp_bar_shelves` SET `name` = '%s'  WHERE id = '%d' AND  bar_id = '%d'", $_POST["shelf_name"], $_POST["shelf_id"], $_POST["bar_id"]);
				$res = $wpdb->query($query);
				if ($res) {

					echo json_encode(array('status' => true, 'message' => __('Bar updated successfully.')));
				}

			} else {
				echo json_encode(array('status' => false, 'message' => __('Invalid data.')));
			}
		}
		exit();
	}

	function ajaxbarupdate()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;

		if (get_user_bar_id($user_id) != $_POST['bar_id']) {
			echo json_encode(array('status' => false, 'message' => __('You are not authorized.')));
		} else {
			if ($_POST['bar_id'] != '' && $_POST['is_public'] != '') {
				$bid = $_POST['bar_id']; //by sumeeth for shelf edit
				$shelf1 = $_POST['shelf1'];
				$shelf2 = $_POST['shelf2'];
				$shelf3 = $_POST['shelf3'];
				$s1 = $_POST['s1'];
				$s2 = $_POST['s2'];
				$s3 = $_POST['s3'];
				$sql1 = "UPDATE wp_bar_shelves SET name='$shelf1' WHERE id='$s1' AND bar_id='$bid'";
				$wpdb->query($sql1);
				$sql2 = "UPDATE wp_bar_shelves SET name='$shelf2' WHERE id='$s2' AND bar_id='$bid'";
				$wpdb->query($sql2);
				$sql3 = "UPDATE wp_bar_shelves SET name='$shelf3' WHERE id='$s3' AND bar_id='$bid'";
				$wpdb->query($sql3); //by sumeeth for shelf edit
				$query = $wpdb->prepare('UPDATE `wp_bar` SET name="%s", shared="%s" WHERE id = "%s"', $_POST["bar_name"], sanitize_text_field($_POST["is_public"]), sanitize_text_field($_POST["bar_id"]));
				$res = $wpdb->query($query);

				echo json_encode(array('status' => true, 'message' => __('Bar updated successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Invalid data.')));
			}
		}
		exit();
	}

	function ajaxchatedit()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_id = $cur_user->data->ID;
		$user_details = get_user_meta($cur_user->data->ID);
		if ($_POST['reply'] != '' && $_POST['reply_id'] > 0) {
			$reply_id = $_POST['reply_id'];
			$reply_content = $_POST['reply'];
			$reply_data = array('reply_to' => $_POST['reply_to'], 'post_parent' => $_POST['topic_id'], 'post_content' => $_POST['reply'], 'post_type' => 'reply', 'post_author' => $cur_user->data->ID);
			$reply_meta = array('forum_id' => '0', 'topic_id' => $_POST['topic_id'], 'reply_to' => $_POST['reply_to']);
			//get the topic IP so we can reset it later
			$ip = get_post_meta($_POST['topic_id'], '_bbp_author_ip', false);

			if (!current_user_can('edit_reply', $reply_id)) {
				//return new WP_Error( 'rest_forbidden', 'You do not have permission to edit that reply.', array( 'status' => 403 ) );
				echo json_encode(array('status' => false, 'message' => __('You do not have permission to edit that reply.')));
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
				echo json_encode(array('status' => true, 'message' => __('your post updated successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('your post update failed.')));
			}
		} else {
			echo json_encode(array('status' => false, 'message' => __('your post update failed.')));
		}
		exit();
	}



	function ajaxchatlike()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to record your like.')));
		}

		$cur_user = wp_get_current_user();

		$user_id = $cur_user->data->ID;

		if ($_POST['reply_id'] != '' && $_POST['like'] != '' && $user_id > 0) {
			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE user_id = '%d' AND reply_id = '%d' and status ='0'", $user_id, $_POST['reply_id']);
			$list = $wpdb->get_results($query);

			if ($list[0]->cnt >= 1) {
				if ($_POST['like'] == 0) {
					$query = $wpdb->prepare("UPDATE `wp_reply_likes` SET status='1' WHERE user_id = '%d' AND reply_id = '%d'", $user_id, $_POST['reply_id']);

					$res = $wpdb->query($query);

					$query = $wpdb->prepare("UPDATE `notification_table` SET status='1' WHERE notification_by = '%d' AND content = '%d'", $user_id, $_POST['reply_id']);
					$res = $wpdb->query($query);


					$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $_POST['reply_id']);
					$list = $wpdb->get_results($query);
					reward_points("remove",(int)7, $user_id, $_POST['reply_id']);
					echo '0';
				} else {
					echo '1';
				}
			} else {

				$query = $wpdb->prepare("INSERT INTO `wp_reply_likes` (`reply_id`, `user_id`) VALUES (%d, %d)", $_POST['reply_id'], $user_id);
				$res = $wpdb->query($query);
				$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $user_id);
				$listss = $wpdb->get_results($queryuname);
				$uname = $listss[0]->uname;
				$reward_message = reward_points("add",(int)7, $user_id, $_POST['reply_id']);
				$queryuid = $wpdb->prepare("SELECT post_author as uid FROM `wp_posts` WHERE ID = '%d'", $_POST['reply_id']);
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
				$arrNotification["targetID"] = $_POST['reply_id'];
				$arrNotification["targetType"] = "postdetail";
				$arrNotification["type"] = 1;
				$arrNotification["targetContent"]["targetID"] = $_POST['reply_id'];
				$arrNotification["targetContent"]["targetType"] = "postdetail";

				include_once 'fcm.php';
				$fcm = new FCM();

				if ($uid != $user_id) {

					$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`) VALUES (%s, %d, %s, %d, %d, %s)", 'Like', $_POST['reply_id'], $content_text, $user_id, $uid, 'Likefromwebsite');
					$res = $wpdb->query($querystore);
					$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Likefromwebsite");
				}
				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status ='0'", $_POST['reply_id']);
				$list = $wpdb->get_results($query);
				// return array("message" => "Record updated successfully.", "is_liked" => 1, "reward_message"=> $rewa);
				
				echo json_encode([
					'is_liked' => 1,
					'reward_message' => $reward_message ?? ''
				]);
				wp_die();
			
			}
		} else {
			echo '0';
		}
		exit();
	}

	function ajaxlikeprofile()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to record your like.')));
		}

		$cur_user = wp_get_current_user();

		$user_id = $cur_user->data->ID;

		if ($_POST['profile_id'] != '' && $_POST['like'] != '' && $user_id > 0) {
			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE user_id = '%d' AND profile_id = '%d'", $user_id, $_POST['profile_id']);
			$list = $wpdb->get_results($query);

			if ($list[0]->cnt >= 1) {
				if ($_POST['like'] == 0) {
					$query = $wpdb->prepare("DELETE FROM `wp_profile_likes` WHERE user_id = '%d' AND profile_id = '%d'", $user_id, $_POST['profile_id']);
					$res = $wpdb->query($query);

					$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $_POST['profile_id']);
					$list = $wpdb->get_results($query);
					reward_points('remove', (int)16, $_POST['profile_id'], null, $user_id);
					echo '0';
				} else {
					echo '-';
				}
			} else {

				$query = $wpdb->prepare("INSERT INTO `wp_profile_likes` (`profile_id`, `user_id`) VALUES (%d, %d)", $_POST['profile_id'], $user_id);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_profile_likes` WHERE profile_id = '%d'", $_POST['profile_id']);
				$list = $wpdb->get_results($query);

				$queryuname = $wpdb->prepare("SELECT display_name as uname FROM `wp_users` WHERE ID = '%d'", $user_id);
				reward_points('add', (int)16, $_POST['profile_id'], null, $user_id);
				$listss = $wpdb->get_results($queryuname);
				$uname = $listss[0]->uname;
				//print_r($uname);exit;

				//print_r($uid);exit;
				$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = '%d'", $_POST['profile_id']);
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
				$arrNotification["targetID"] = $_POST['profile_id'];
				$arrNotification["targetType"] = "bar";
				$arrNotification["type"] = 1;
				//$arrNotification["badge"] = 1;
				$arrNotification["targetContent"]["targetID"] = $_POST['profile_id'];
				$arrNotification["targetContent"]["targetType"] = "bar";
				//print_r($arrNotification);exit;
				// INCLUDE YOUR FCM FILE
				include_once 'fcm.php';
				$fcm = new FCM();
				if ($user_id != $_POST['profile_id']) {
					$querystore = $wpdb->prepare("INSERT INTO `notification_table` (`notification_type`, `content`, `content_text`, `notification_by`, `notification_to`, `platform`) VALUES (%s, %d, %s, %d, %d, %s)", 'Like', $_POST['profile_id'], $content_text, $user_id, $_POST['profile_id'], 'Barlikefromwebsite');
					$res = $wpdb->query($querystore);
					$result = $fcm->send_notification($andriod_device_ids, $arrNotification, "Barlikefromwebsite");
				}

				echo '1';
			}
		} else {
			echo '-';
		}
		exit();
	}

	function ajaxbprofileupdate()
	{
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to update your profile.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		//print_r($cur_user);exit;
		if ($cur_user->data->user_email != '') {
			$item = array();
			$item['name'] = $_POST['name'];
			$item['address'] = $_POST['address'];
			$item['aptsuitefloor'] = $_POST['aptsuitefloor'];
			$item['city'] = $_POST['city'];
			$item['state'] = $_POST['state'];
			$item['zipcode'] = $_POST['zip'];
			$item['phone_number'] = $_POST['phone'];
			$item['date_of_birth'] = $_POST['dob'];
			$item['avatar'] = $_POST['avatar'];
			$item['bio'] = $_POST['bio'];
			$profile_edited = 0;
			//we can give the reward point to user when all filled are commplete that time we can give the reward..
			if (
			    !empty($_POST['name']) &&
			    !empty($_POST['address']) &&
			    !empty($_POST['city']) &&
			    !empty($_POST['state']) &&
			    !empty($_POST['zip']) &&
			    !empty($_POST['phone']) &&
			    !empty($_POST['dob']) &&
			    !empty($_POST['bio'])
			){
				$profile_edited = 1;
			}


			global $wpdb;
			$user_id = $cur_user->data->ID;

			//$profile_edited = $wpdb->get_var($wpdb->prepare("SELECT profile_edited FROM {$wpdb->prefix}users WHERE ID = %d", $user_id));
		
			if ($profile_edited == 1) {
				$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $user_id, 1));
				if($list == 0){
					$return_msg = reward_points("add", (int)1, $user_id);	
				}
			}

			if(empty($return_msg)){
				$return_msg = 1;
			}else{
				$return_msg;
			}

			wp_update_user(array('ID' => $cur_user->data->ID, 'display_name' => $item['name'], 'phone_number' => $item['phone_number']));
			update_user_meta($user_id, 'phone_number', $item['phone_number']);
			update_user_meta($user_id, 'address', $item['address']);
			update_user_meta($user_id, 'date_of_birth', $item['date_of_birth']);

			update_user_meta($user_id, 'aptsuitefloor', $item['aptsuitefloor']);
			update_user_meta($user_id, 'city', $item['city']);
			update_user_meta($user_id, 'state', $item['state']);
			update_user_meta($user_id, 'zipcode', $item['zipcode']);
			update_user_meta($user_id, 'bio', $item['bio']);


			$newDOB = date("Y-m-d", strtotime($item['date_of_birth']));
			$from = new DateTime($newDOB);
			$to = new DateTime('today');
			$age = $from->diff($to)->y;
			if ($age >= 21 && $item['address'] != '' && $item['name'] != '' && $item['phone_number'] != '') {
				update_user_meta($user_id, 'is_verified', 1);
			} else {
				update_user_meta($user_id, 'is_verified', 0);
			}
			birthday_rewards_web();
			if (isset($item['avatar']) && $item['avatar'] != '') {
				$imgdata = base64_decode($item["avatar"]);
				//print_r($imgdata);
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
				//print_r($uploadfile);
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
					//exit;
				}
			}
			echo json_encode(array('status' => true, 
															'message' => __('Profile updated successfully.'), 
															'redirected_to' => bbp_get_user_profile_url($user_id),
															'reward_message' => $return_msg
														));

		} else {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to update your profile.'), 'redirected_to' => bbp_get_user_profile_url($user_id)));
		}
		exit();
	}

	function ajaxremoveprofileimage()
	{
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to update your profile.')));
		}

		$cur_user = wp_get_current_user();
		if ($cur_user->data->user_email != '') {
			$user_id = $cur_user->data->ID;
			$attachment_id = get_user_meta($user_id, 'wp_user_avatar', true);
			if ($attachment_id) {
				// wp_delete_attachment_metadata($attachment_id);
				delete_post_meta($attachment_id, '_wp_attachment_wp_user_avatar');

				wp_delete_attachment($attachment_id, true);

				delete_user_meta($user_id, 'wp_user_avatar');

				wp_send_json_success(array('message' => __('Profile image removed successfully.')));
			} else {
				wp_send_json_error(array('message' => __('No profile image found.')));
			}
		}
		exit;
	}

	function ajaxwishlist()
	{
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			die('Busted!');
		}

		$item = array();
		$item['product_id'] = $_POST['product_id'];
		$item['wishlist'] = $_POST['wishlist'];

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
				echo json_encode(array('status' => true, 'message' => __('Product added to wishlist')));
			} else {
				update_user_meta($cur_user->data->ID, 'wishlist', array($item['product_id']));
				echo json_encode(array('status' => true, 'message' => __('Product added to wishlist')));
			}
		} else {
			$existing_wishlist = $user_details['wishlist'][0];
			//print_r($existing_wishlist);
			if ($existing_wishlist) {
				$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
				if (($key = array_search($item['product_id'], $existing_wishlist_arr)) !== false) {
					unset($existing_wishlist_arr[$key]);
				} else {
					echo json_encode(array('status' => true, 'message' => __('Product does not exists in your wishlist')));
				}
				//print_r($existing_wishlist_arr);
				if (update_user_meta($cur_user->data->ID, 'wishlist', $existing_wishlist_arr)) {
					//return array_values($existing_wishlist_arr);
					echo json_encode(array('status' => true, 'message' => __('Product removed from wishlist')));
				}
			} else {
				echo json_encode(array('status' => true, 'message' => __('Product does not exists in your wishlist')));
			}
		}
		die();
	}


	function ajaxbarlist()
	{
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			die('Busted!');
		}
		session_start();
		global $wpdb;
		$cur_user = wp_get_current_user();
		if ($cur_user->data->user_email != '') {
			$item = array();
			$query = $wpdb->prepare("SELECT b.id as bar_id, b.name as bar_name, b.shared as shared, bs.name as shelf_name, bs.id as shelf_id, bs.weight as shelf_weight FROM wp_bar b LEFT JOIN wp_bar_shelves bs ON b.id = bs.bar_id WHERE owner_email = '%s' ORDER BY bs.weight ASC", $cur_user->data->user_email);
			$bar_info = $wpdb->get_results($query);

			if (!$bar_info[0]->bar_id) {
				$body1 = array('name' => '', 'owner_email' => $cur_user->data->user_email);
				$add_bar_res = web_bar_add($body1);
				$item['bar_id'] = $add_bar_res['bar_id'];
			} else {
				$item['bar_id'] = $bar_info[0]->bar_id;
			}




			$product_id = $_POST['product_id'];

			$querycheckduplicate = $wpdb->prepare("SELECT COUNT(1) FROM `wp_bar_shelves_products` WHERE `shelve_id` IN (
				SELECT `id` 
				FROM `wp_bar_shelves` 
				WHERE `bar_id` = %d
			) 
			AND `product_id` = %d", $item['bar_id'], $product_id);
			$duplicate = $wpdb->get_var($querycheckduplicate);

			if ($duplicate == 1) {
				echo json_encode(array('status' => false, 'message' => __('product is already in shelf.')));
				exit;
			}

			$shelf_id = isset($_SESSION['si']) ? $_SESSION['si'] : $_POST['shelf_id'];

			$countproductssql = $wpdb->prepare("SELECT COUNT(1) FROM `wp_bar_shelves_products` WHERE shelve_id = %d AND product_id <> 0", $shelf_id);
			$count_products = $wpdb->get_var($countproductssql);

			if ($count_products == 15) {
				echo json_encode(array('status' => false, 'message' => __('Shelf has exceed limit of 15.')));
				exit;
			}

			$weight = isset($_SESSION['w']) ? $_SESSION['w'] : $_POST['weight'];
			$countsql = $wpdb->prepare("SELECT COUNT(1) FROM `wp_bar_shelves_products` WHERE shelve_id = %d AND weight = %d", $shelf_id, $weight);
			$count = $wpdb->get_var($countsql);


			if ($count == 0) {
				$query = $wpdb->prepare("INSERT INTO `wp_bar_shelves_products` (shelve_id,product_id,weight) VALUES (%d,%d,%d)", $shelf_id, $product_id, $weight);
			} else {
				$query = $wpdb->prepare("UPDATE `wp_bar_shelves_products` set product_id = %d where shelve_id = %d AND weight = %d", $product_id, $shelf_id, $weight);
			}

			$res = $wpdb->query($query);
			if ($res) {
				if (isset($_SESSION['si'])) {
					unset($_SESSION['si']);
				} else {
					unset($_SESSION['w']);
				}
				$msg = 'Product added to Bar';

				$list = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM user_reward_history WHERE user_id = %d AND challenge_id = %d", $cur_user->data->ID, 15));
				if($list == 0){
					$reward_message = reward_points('add', (int)15, $cur_user->data->ID);
				}
	 
				if(empty($reward_message)){
					$reward_message = 1;
				} else{
					$reward_message;

				}

				echo json_encode(array('status' => true, 
																'message' => __($msg),
																'reward_message' => $reward_message
															));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Product already exists in bar shelf.')));
			}
		} else {
			echo json_encode(array('status' => false, 'message' => __('Invalid user.')));
		}
		die();
	}

	//add this within functions.php
	function ajax_login_init()
	{

		wp_register_script('ajax-login-script', get_stylesheet_directory_uri() . '/assets/js/ajax-login-script.js', array('jquery'));
		wp_enqueue_script('ajax-login-script');

		wp_localize_script('ajax-login-script', 'ajax_login_object', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'redirecturl' => home_url(),
			'loadingmessage' => __('Sending user info, please wait...')
		));

		// Enable the user with no privileges to run ajax_login() in AJAX
		add_action('wp_ajax_nopriv_ajaxlogin', 'ajax_login');
	}

	// Execute the action only if the user isn't logged in
	if (!is_user_logged_in()) {
		add_action('init', 'ajax_login_init');
	}

	function ajax_login()
	{

		// First check the nonce, if it fails the function will break
		check_ajax_referer('ajax-login-nonce', 'security');

		// Nonce is checked, get the POST data and sign user on
		$info = array();
		$info['user_login'] = $_POST['username'];
		$info['user_password'] = $_POST['password'];
		$info['remember'] = !empty($_POST['remember']) ? 1 : 0;

		$user_signon = wp_signon($info, false);
		if (is_wp_error($user_signon)) {
			echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.')));
		} else {
			if ($user_signon->data->validate_email == '1') {

				// echo json_encode(array('loggedin'=>false, 'message'=>__('Please verify email and login ...')));
				//wp_logout();
				$bar_path = bbp_get_user_profile_url($user_signon->data->ID);
				echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting... please verify your email.'), 'bar_path' => $bar_path, 'validateemail' => $user_signon->data->validate_email));
			} else {
				$bar_path = bbp_get_user_profile_url($user_signon->data->ID);
				echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...'), 'bar_path' => $bar_path));
			}
			if ($info['remember']) {
				$passkey = 'G)w9:3qga>:U#v(';
				$method = 'aes128';

				setcookie("remember", "1", array("secure" => true, "httponly" => true, "samesite" => "lax", "path" => '/login', "expires" => time() + (10 * 365 * 24 * 60 * 60)));
				setcookie("username", openssl_encrypt($info['user_login'], $method, $passkey), array("secure" => true, "httponly" => true, "samesite" => "lax", "path" => '/login', "expires" => time() + (10 * 365 * 24 * 60 * 60)));
				setcookie("userpassword", openssl_encrypt($info['user_password'], $method, $passkey), array("secure" => true, "httponly" => true, "samesite" => "lax", "path" => '/login', "expires" => time() + (10 * 365 * 24 * 60 * 60)));
			} else {
				unset($_COOKIE["username"]);
				unset($_COOKIE["userpassword"]);
				unset($_COOKIE["remember"]);
				setcookie("username", "", -1, '/login');
				setcookie("userpassword", "", -1, '/login');
				setcookie("remember", "", -1, '/login');
			}
		}


		die();
	}


	add_action('template_redirect', 'redirect_to_specific_page');

	function redirect_to_specific_page()
	{
		if (is_page('login') && is_user_logged_in()) {
			wp_redirect('/', 301);
			exit;
		}
		if (is_page('reset-password') && is_user_logged_in()) {
			wp_redirect('/', 301);
			exit;
		}
		if (is_shop() || is_page('cart')) {
			wp_redirect('/', 301);
			exit;
		}
	}

	function sipn_footer()
	{
		global $wpdb;
		$cur_user = wp_get_current_user();
		$curemail = $cur_user->data->user_email;
		echo '<footer>';
		// if(!is_page('login')){
		echo '<div class="container"><div class="page-loader" style="display:none;"><img src="' . get_stylesheet_directory_uri() . '/assets/images/ajax-loader.gif"></div>
	            <div class="powered-bc"><p>&copy; Sipn Bourbon 2021-' . date('Y') . '. All Rights Reserved.</p>
	           </div>
	        </div>
	        <p style="display:none;"><a href="https://sipnbourbon.com/sitemap" >sitemap</a></p>';
		//}
		echo '</footer></div></div>
	        </article>
	        
	      </main></div>';
		echo wp_footer();
		echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58L6H4C"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	';
		echo '<div class="modal modal-emailverification fade in" id="openpopup" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-emailverification modal-sm">
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <i class="fa fa-info-circle fa-info-circle-custom" aria-hidden="true"></i>
	    </div>
	    <div class="modal-body">
		 <div class="email-verification-text">Email not verified</div>
	      <div class="email-verification-content">We sent an email to you please verify your email to continue</div>
	      <div class="resendemail-main"><a href="javascript:void(0);" class="resendemail" data-id="' . $curemail . '" id="resendemail">Resend verification mail</a></div>
	      <div class="resendemail-main"><a href="/profile-info" class="email-verified-div">Already verified? Go to profile</a></div>
	    </div>  
	  </div>
	</div>
	</div>';
		//echo '<script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>';
		//echo '<script src="'.get_stylesheet_directory_uri().'/videojs-vimeo/lib/video.js"></script>';

		//echo '<script src="'.get_stylesheet_directory_uri().'/videojs-vimeo/vjs.vimeo.js"></script>';
		echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58L6H4C"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	';
		echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly" async></script>';

		echo '</body>

	</html>';
	}

	// function sipn_footer(){
	// 	echo '</main></div>';
	// 	if(!is_page('login')){
	// 		echo  '<footer><div class="container"><div class="page-loader" style="display:none;"><img src="'.get_stylesheet_directory_uri() . '/assets/images/ajax-loader.gif"></div>
	//             <p>&copy; Sipn App. 2021.</p>
	//             <small><a href="/terms-of-service">Terms of service</a></small>
	//         </div></footer>';
	// 	}
	//     echo '</div></div>
	//         </article>';
	//       echo wp_footer();
	//       //echo '<script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>';
	//       //echo '<script src="'.get_stylesheet_directory_uri().'/videojs-vimeo/lib/video.js"></script>';

	//       //echo '<script src="'.get_stylesheet_directory_uri().'/videojs-vimeo/vjs.vimeo.js"></script>';
	//       echo '</body>
	// </html>';
	// }

	/*add_action('after_setup_theme', 'custom_theme_scripts');

	function custom_theme_scripts() {
		wp_register_script('ajax-login', get_stylesheet_directory_uri().'/assets/js/ajax-login-script.js', true);
	   
		wp_enqueue_script('ajax-login');
	}
	*/

	function remove_unwanted_css()
	{
		wp_dequeue_style('storefront-style');
		wp_deregister_style('storefront-style');

		wp_dequeue_style('storefront-icons');
		wp_deregister_style('storefront-icons');

		wp_dequeue_style('storefront-gutenberg-blocks-inline');
		wp_deregister_style('storefront-gutenberg-blocks-inline');

		wp_dequeue_style('storefront-style-inline');
		wp_deregister_style('storefront-style-inline');

		wp_dequeue_style('storefront-gutenberg-blocks');
		wp_dequeue_style('wc-blocks-style');
		wp_dequeue_style('wc-blocks-vendors');
	}
	add_action('wp_enqueue_scripts', 'remove_unwanted_css', 20);

	add_action('after_setup_theme', 'remove_parent_theme_features', 10);

	function remove_parent_theme_features()
	{
		// our code here
		remove_action('init', 'storefront_footer');
		remove_action('init', 'before_storefront_footer');
		remove_action('init', 'after_storefront_footer');
	}


	add_action('woocommerce_after_single_product_summary', 'view_acf_field_for_single_product', 1);

	function view_acf_field_for_single_product()
	{
		echo "<div class='product_info pro-newinfo'>";
		if (function_exists('the_field')) {
			echo '<div class="proof">';
			display_pr_feild('Proof', 'proof');
			echo '</div>';
			echo '<div class="distillery">';
			display_pr_feild('Distillery', 'distillery');
			echo '</div>';
			echo '<div class="region">';
			display_pr_feild('Region', 'region');
			echo '</div>';
			echo '<div class="unitsize">';
			display_pr_feild('Size', 'unitsize');
			echo '</div>';
			echo '<div class="flavor">';
			display_pr_feild('Flavor', 'flavor');
			echo '</div>';
			echo '<div class="nose">';
			display_pr_feild('Nose', 'nose');
			echo '</div>';
			echo '<div class="finish">';
			display_pr_feild('Finish', 'finish');
			echo '</div>';
			//display_pr_feild('UPC', 'productupc');

		}
		echo "</div>";
	}

	function display_pr_feild($label, $feild)
	{
		if (get_field($feild)) {
			echo "<div class='pr-info-item'><label>" . $label . ": </label><span>";
			the_field($feild);
			echo "</span></div>";
		}
	}


	function wooc_extra_register_fields()
	{ ?>

		<p class="form-row form-row-first">
			<label for="reg_billing_first_name"><?php _e('First name', 'woocommerce'); ?><span class="required">*</span></label>
			<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if (!empty($_POST['billing_first_name']))
				esc_attr_e($_POST['billing_first_name']); ?>" />
		</p>
		<p class="form-row form-row-last">
			<label for="reg_billing_last_name"><?php _e('Last name', 'woocommerce'); ?><span class="required">*</span></label>
			<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if (!empty($_POST['billing_last_name']))
				esc_attr_e($_POST['billing_last_name']); ?>" />
		</p>
		<p class="form-row form-row-wide">
			<label for="reg_billing_phone"><?php _e('Phone', 'woocommerce'); ?></label>
			<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone"
				value="<?php esc_attr_e($_POST['billing_phone']); ?>" />
		</p>
		<div class="clear"></div>
		<?php
	}
	add_action('woocommerce_register_form_start', 'wooc_extra_register_fields');

	/**

	 * register fields Validating.

	 */

	function wooc_validate_extra_register_fields($username, $email, $validation_errors)
	{

		if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {

			$validation_errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'woocommerce'));
		}

		if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {

			$validation_errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'woocommerce'));
		}
		return $validation_errors;
	}

	add_action('woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3);


	/**
	 * Below code save extra fields.
	 */
	function wooc_save_extra_register_fields($customer_id)
	{
		if (isset($_POST['billing_phone'])) {
			// Phone input filed which is used in WooCommerce
			update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
		}
		if (isset($_POST['billing_first_name'])) {
			//First name field which is by default
			update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
			// First name field which is used in WooCommerce
			update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
		}
		if (isset($_POST['billing_last_name'])) {
			// Last name field which is by default
			update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
			// Last name field which is used in WooCommerce
			update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
		}
	}
	add_action('woocommerce_created_customer', 'wooc_save_extra_register_fields');



	add_action('admin_head-edit.php', 'addCustomImportButton');

	/**
	 * Adds "Import" button on module list page
	 */
	function addCustomImportButton()
	{
		global $current_screen;

		if ($_GET['update_rating'] == '1') {
			global $wpdb;
			$sql = "DELETE FROM wp_postmeta WHERE meta_key='_wc_average_rating' AND meta_value='0'";
			$wpdb->query($sql);
		}
		//print_r($current_screen->post_type);
		// Not our post type, exit earlier
		// You can remove this if condition if you don't have any specific post type to restrict to. 
		if ('product' == trim($current_screen->post_type)) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					jQuery(jQuery(".wrap h1")[0]).append(
						"<a href='/wp-admin/edit.php?post_type=product&update_rating=1' id='doc_popup' class='add-new-h2'>Update Ratings</a>"
					);
				});
			</script>
			<?php
		}
	}


	function wpse_product_update_upc($post_id)
	{
		$post_type = get_post_type($post_id);
		if ($post_type == 'product') {

			// start - update rating
			//echo "post-->".$post_id;
			$existing_rating = get_post_meta($post_id, '_wc_average_rating', true);
			delete_post_meta($post_id, '_wc_average_rating');
			//update_post_meta($post_id, '_wc_average_rating', $existing_rating);
			save_upc($post_id, '_wc_average_rating', $existing_rating);
			// end - update rating

			$product_upc = get_post_meta($post_id, 'productupc', true);

			$upc_len = strlen($product_upc);
			if ($upc_len == 12) {
				$product_upc_1 = substr($product_upc, 1);
				$product_upc_2 = substr($product_upc, 0, 11);
				$product_upc_3 = substr($product_upc_2, 1);

				save_upc($post_id, 'productupc_1', $product_upc_1);
				save_upc($post_id, 'productupc_2', $product_upc_2);
				save_upc($post_id, 'productupc_3', $product_upc_3);
			} else if ($upc_len == 11) {
				//if start is zero than add check digit at last
				if (substr($product_upc, 0, 1) == '0') {
					$check_digit = generate_upc_checkdigit($product_upc);
					$product_upc .= $check_digit;
					save_upc($post_id, 'productupc_4', $product_upc);

					$product_upc_1 = substr($product_upc, 1);
					$product_upc_2 = substr($product_upc, 0, 11);
					$product_upc_3 = substr($product_upc_2, 1);

					save_upc($post_id, 'productupc_1', $product_upc_1);
					save_upc($post_id, 'productupc_2', $product_upc_2);
					save_upc($post_id, 'productupc_3', $product_upc_3);
				} else {
					$product_upc_1 = substr($product_upc, 0, 10);
					save_upc($post_id, 'productupc_1', $product_upc_1);
				}
			} else if ($upc_len == 10) {
				$product_upc_1 = substr($product_upc, 1);
				$product_upc_2 = substr($product_upc, 0, 9);
				$product_upc_3 = substr($product_upc_2, 1);

				save_upc($post_id, 'productupc_1', $product_upc_1);
				save_upc($post_id, 'productupc_2', $product_upc_2);
				save_upc($post_id, 'productupc_3', $product_upc_3);
			}
		}
	}
	add_action('save_post', 'wpse_product_update_upc', 10, 3);

	function save_upc($post_id, $meta_key, $product_upc)
	{
		global $wpdb;
		$chk_sql = "SELECT * FROM wp_postmeta WHERE post_id='$post_id' AND meta_key='$meta_key'";
		$result = $wpdb->get_results($chk_sql);
		if ($wpdb->num_rows > 0) {
			$sql = "UPDATE wp_postmeta SET meta_value='$product_upc' WHERE post_id='$post_id' AND meta_key='$meta_key'";
			$wpdb->query($sql);
		} else {
			$sql = "INSERT INTO wp_postmeta(meta_id, post_id, meta_key, meta_value) values('', '$post_id', '$meta_key', '$product_upc')";
			$wpdb->query($sql);
		}
	}

	function generate_upc_checkdigit($upc_code)
	{
		$odd_total = 0;
		$even_total = 0;

		for ($i = 0; $i < 11; $i++) {
			if ((($i + 1) % 2) == 0) {
				/* Sum even digits */
				$even_total += $upc_code[$i];
			} else {
				/* Sum odd digits */
				$odd_total += $upc_code[$i];
			}
		}

		$sum = (3 * $odd_total) + $even_total;

		/* Get the remainder MOD 10*/
		$check_digit = $sum % 10;

		/* If the result is not zero, subtract the result from ten. */
		return ($check_digit != 0) ? 10 - $check_digit : $check_digit;
	}


	/* code to update products */
	// args to fetch all products
	/*
	$args = array(
	    'post_type' => 'product',
	    'posts_per_page' => -1
	);
	// create a custom query
	$products = new WP_Query( $args );
	// if products were returned...
	if ( $products->have_posts() ):
	    // loop over them....
	    while ( $products->have_posts() ):
	        $products->the_post();
	            
	       //update_post_meta( get_the_ID(), 'upc_test', '1' );
	       //delete_post_meta( get_the_ID(), 'upc_test');
	       wpse_product_update_upc(get_the_ID());
	       echo get_the_ID().' do it'.'<br>';
	            
	    endwhile;
	endif;
	*/

	add_filter('rest_request_after_callbacks', function ($response, array $handler, WP_REST_Request $request) {

		if ($request->get_route() == '/jwt-auth/v1/token' && isset($response->errors['[jwt_auth] incorrect_password'])) {
			$response->errors['[jwt_auth] incorrect_password'][0] = 'The password you entered is incorrect.';
		}
		if ($request->get_route() == '/jwt-auth/v1/token' && isset($response->errors['[jwt_auth] invalid_username'])) {
			$response->errors['[jwt_auth] invalid_username'][0] = 'The username is invalid.';
		}
		if ($request->get_route() == '/jwt-auth/v1/token' && isset($response->errors['[jwt_auth] empty_password'])) {
			$response->errors['[jwt_auth] empty_password'][0] = 'The password field is empty.';
		}
		if ($request->get_route() == '/jwt-auth/v1/token' && isset($response->errors['[jwt_auth] empty_username'])) {
			$response->errors['[jwt_auth] empty_username'][0] = 'The username field is empty.';
		}

		/*if ($request->get_route() == '/user/v1/reset-password' && !isset($response->errors['bad_email']) && !isset($response->errors['no_email'])) {
			$response["message"] = 'An OTP has been sent to your email address';
		}*/

		return $response;
	}, 10, 3);


	/*reset password namespace */
	add_filter('bdpwr_route_namespace', function ($route_namespace) {
		return 'user/v1';
	}, 10, 1);

	/*reset password - mail change */
	add_filter('bdpwr_code_email_text', function ($text, $email, $code, $expiry) {
		$message = "A password reset was requested for your account and your password reset code is " . $code . "\n" . "Please note that this code will expire in 15 mins.";
		return $message;
	}, 10, 4);
	function modify_url($params = [], $url = FALSE, $query_only = false)
	{
		// If $url wasn't passed in, use the current url
		if ($url == FALSE) {
			$scheme = $_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https';
			$url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		$request_arr = explode('/', $_SERVER['REQUEST_URI']);
		$length_of_uri = count($request_arr);
		if ($length_of_uri >= 3) {
			$last_part = $request_arr[$length_of_uri - 1];

			$last_part_arr = explode('?', $last_part);
			$request_arr[$length_of_uri - 1] = '?' . $last_part_arr[1];

			$url = $scheme . '://' . $_SERVER['HTTP_HOST'] . implode('/', $request_arr);
		}

		// Parse the url into pieces
		$url_array = parse_url($url);


		// The original URL had a query string, modify it.
		if (!empty($url_array['query'])) {
			parse_str($url_array['query'], $query_array);
			foreach ($params as $key => $value) {
				if ($value == null) {
					unset($query_array[$key]);
				} else {
					$query_array[$key] = $value;
				}
			}
		}


		// The original URL didn't have a query string, add it.
		else {
			$query_array = $params;
		}


		if ($query_only) {
			return '?' . http_build_query($query_array);
		}


		return $url_array['scheme'] . '://' . $url_array['host'] . $url_array['path'] . '?' . urldecode(http_build_query($query_array));
	}


	add_action('save_post', 'send_push_notifications', 10, 3);

	function send_push_notifications($post_id, $post, $update)
	{
		// Skip autosaves and revisions - these make save_post fire repeatedly
		if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
			return;
		}

		// Only set for post_type = push-notification
		if ('push-notification' !== $post->post_type) {
			return;
		}

		if ($post->post_status == 'publish') {

			// Send exactly once per notification. The block editor fires save_post
			// multiple times on an immediate publish, which caused duplicate sends
			// to every device. This flag makes the send idempotent.
			if (get_post_meta($post->ID, '_push_sent', true)) {
				return;
			}
			update_post_meta($post->ID, '_push_sent', current_time('mysql'));

			$regId = "c0LcCX_dQnaaFstxXMFatB:APA91bFYy7ItZm1jCt9B6LNKkP69d2XHOa7eFvZWGQ4DP8AhuAid4gOgT6FgAqLcyX6nFK1AXVVArtj8u-fVkPSKsB4WNDrR9MlV1w01x526mh26GhU1-zS0tmIB5V9gPPQmT5ckhgYX";
			global $wpdb;
			$query = $wpdb->prepare("SELECT DISTINCT device_id FROM `wp_devices` WHERE device_type = '%s'", "IOS");
			$ios_recipents = $wpdb->get_results($query, ARRAY_N);

			$ios_device_ids = array();
			foreach ($ios_recipents as $ios_recipent) {
				array_push($ios_device_ids, $ios_recipent[0]);
			}


			$query = $wpdb->prepare("SELECT DISTINCT device_id FROM `wp_devices` WHERE device_type = '%s'", "Android");
			$andriod_recipents = $wpdb->get_results($query, ARRAY_N);

			$andriod_device_ids = array();
			foreach ($andriod_recipents as $andriod_recipent) {
				array_push($andriod_device_ids, $andriod_recipent[0]);
			}

			// INCLUDE YOUR FCM FILE
			include_once 'fcm.php';

			$notification = array();
			$arrNotification = array();
			$arrData = array();
			$arrNotification["body"] = strip_tags($post->post_content); //for removing html tags
			$arrNotification["title"] = strip_tags($post->post_title);
			$arrNotification["sound"] = "default";
			$arrNotification["type"] = 1;

			if (get_post_meta($post->ID, 'notification_type', true)) {
				$arrNotification["targetContent"]["targetType"] = get_post_meta($post->ID, 'notification_type', true);


				$meta_key = 'select_content_' . strtolower(str_replace(' ', '', get_post_meta($post->ID, 'notification_type', true)));
				$arrNotification["targetContent"]["targetID"] = get_post_meta($post->ID, $meta_key, true);
				$arrNotification["targetID"] = get_post_meta($post->ID, $meta_key, true);
				$arrNotification["targetType"] = get_post_meta($post->ID, 'notification_type', true);
			}

			if (get_the_post_thumbnail_url($post->ID, 'medium')) {
				$arrNotification["image"] = get_the_post_thumbnail_url($post->ID, 'medium');
			}
			$fcm = new FCM();
			//print_r($arrNotification["body"]);
			//print_r($arrNotification["title"]);exit;
			//$ios_device_ids1 = array();
			//$ios_device_ids1[0]="dx6wz8aonUHtrpkrMMBHjA:APA91bHGim9oLQQ19l6vIu26lDUynfs1CBIOFfW85CnTURAyTr62KnzeEhlXGSWQB7odULTvxzH7emCfz9HbimjoKGHtZ_cKpr4YJlWwWvz-KYo5C2t9yNrn4bPQRSqw3iT38j3rj3BN"; //e1lrKcgcQr6OVgk7nKm8kC:APA91bEoAW26DGM8eqxweeT8FYcZzwhoelel0JMfx2zP4wASxRPNn7qD4jEAUXgsIpW_2u9U31J1GaF2UVNspdPaV_VZbJrEtZQLh9wo-SR4hadz8BMHj0RR0Tx0k-oydbp0ZlMWadFE durga garu for andoid 
			//$result = $fcm->send_notification($andriod_device_ids, $arrNotification,"Android");
			//$result2 = $fcm->send_notification($ios_device_ids, $arrNotification,"IOS");

			$i = 0;
			$k = 0;
			$arrfive = array();
			foreach ($andriod_device_ids as $value) {
				$i++;
				$k++;
				array_push($arrfive, $value);
				if ($i == 500 || $k == count($andriod_device_ids)) {
					$result = $fcm->send_notification($arrfive, $arrNotification, "Android", $post->ID);
					$arrfive = array();
					$i = 0;
				}
			}

			$l = 0;
			$m = 0;
			$arrinfive = array();
			foreach ($ios_device_ids as $value) {
				$l++;
				$m++;
				array_push($arrinfive, $value);
				if ($l == 500 || $m == count($ios_device_ids)) {
					$result2 = $fcm->send_notification($arrinfive, $arrNotification, "IOS", $post->ID);
					$arrinfive = array();
					$l = 0;
				}
			}
			if(is_array($result2)){
				$result2 = json_encode($result2);
			}
			$response_arr = json_decode($result2);
			// print_r($$result2);exit;
			if ($response_arr->success) {
				function sample_admin_notice__success()
				{
					?>
					<div class="notice notice-success is-dismissible">
						<p><?php _e('Done!', 'sample-text-domain'); ?></p>
					</div>
					<?php
				}
				add_action('admin_notices', 'sample_admin_notice__success');
			} else {
			}
		}
	}


	function display_nested_replies($replies, $parent_reply_id, $html = '', $nested)
	{

		//if($nested)
		//$html .= '<div class="collapse" id="reply-'.$parent_reply_id.'">';

		//$html .= '<ul>';
		foreach ($replies as $sub_reply) {
			$html .= '<li><div class="blog-review">';

			$html .= '<div id="post-' . $parent_reply_id . '" class="bbp-reply-header">';
			$html .= '<div class="bbp-meta">';
			$html .= '<span class="bbp-admin-links">';

			if (is_user_logged_in()) {
				if (current_user_can('edit_reply', $sub_reply['reply_id'])) {
					$html .= bbp_get_reply_edit_link(array('id' => $sub_reply['reply_id']));
					$html .= " | ";
				}
				$html .= bbp_get_reply_to_link(array('id' => $sub_reply['reply_id']));
			}

			$html .= '</span>';
			$html .= '</div>';
			$html .= '</div>';

			$html .= '<a href="' . bbp_get_user_profile_url($sub_reply['author_id']) . '">';
			if ($sub_reply['avatar']) {
				$html .= '<img src="' . $sub_reply["avatar"] . '">';
			} else {
				$html .= '<img src="https://sipnbourbon.com/wp-content/uploads/2021/09/img-profile1.jpg">';
			}
			$html .= '</a>';
			$html .= '<p><strong>' . $sub_reply["author"] . '</strong><br>';
			$html .= '<span>' . $sub_reply["city"] . '</span> </p>';
			$html .= '<div class="reply_content_' . $sub_reply['reply_id'] . '"><p>' . $sub_reply["reply"] . '</p></div>';
			$html .= '<div class="comments">';
			if ($reply['is_liked'])
				$html .= '<span class="icon-fav" liked="' . $sub_reply['is_liked'] . '" rid="' . $sub_reply['reply_id'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/images/chat/icon-fav.png">';
			else
				$html .= '<span class="icon-fav" liked="' . $sub_reply['is_liked'] . '" rid="' . $sub_reply['reply_id'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/images/chat/icon-fav-before.png">';

			//$html .= '<span class="round-circle">'.count($sub_reply["replies"]).'</span>';
			$html .= '</span>';
			/*if ( is_user_logged_in() && current_user_can( 'edit_reply', $sub_reply['reply_id'] ) ) {
																		$html .= '<span class="icon-comments" rid="'.$sub_reply['reply_id'].'"><img src="'.get_stylesheet_directory_uri().'/assets/images/chat/icon-comment.png">';
																		//$html .= '<span class="round-circle">'.$sub_reply["likes"].'</span>';
																		$html .= '</span>';
																		}*/
			//$html .= '<span class="icon-share"><img src="assets/images/chat/icon-share.png"></span>';
			$html .= '</div> ';
			$html .= '</div></li>';

			if (count($sub_reply['replies']) > 0) {
				$html = display_nested_replies($sub_reply['replies'], $sub_reply['reply_id'], $html, false);
			}
		}
		//$html .= '</ul>';

		//if($nested)
		//$html .= '</div>';
		return $html;
	}



	/*add_action( 'init', 'nicenames_to_display_name' );
	function nicenames_to_display_name() {
		foreach ( get_users() as $user ) {
			if ( $user->data->user_status == 0 && $user->data->user_nicename != $user->data->display_name ) {
				$user_ids[] = $user->ID;
			}
		}
		foreach( $user_ids as $uid ) {
			$info = get_userdata( $uid );
			$display_name = $info->data->display_name;
			
			if ($display_name) {
				$args = array(
					'ID'            => $uid,
					'user_nicename' => strtolower(str_replace(" ", "-", $display_name))
				);
				wp_update_user( $args );
			}
		}
	}
	*/

	function get_current_url()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "??") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	function remove_jquery_migrate($scripts)
	{

		if (!is_admin() && isset($scripts->registered['jquery'])) {

			$script = $scripts->registered['jquery'];

			if ($script->deps) {
				$script->deps = array_diff($script->deps, array('jquery-migrate'));
			}
		}
	}
	add_action('wp_default_scripts', 'remove_jquery_migrate');


	function web_user_bar($user_id)
	{
		global $wpdb;
		$item['user_id'] = $user_id;

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


					// foreach ($shelves as $shelf) {
					// 	$products_query = $wpdb->prepare("SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight  FROM wp_bar_shelves bs LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id LEFT JOIN wp_posts p ON bsp.product_id = p.id WHERE bsp.shelve_id = '%d' AND bsp.product_id <> 0 ORDER BY product_weight ASC", $shelf->shelf_id);
					// 	$prods = $wpdb->get_results($products_query);
					// 	$the_prods = array();
					// 	if (!empty($prods)) {
					// 		foreach ($prods as $prod) {
					// 			$product_image = get_the_post_thumbnail_url($prod->product_id, 'full');
					// 			$product_sm_image = get_the_post_thumbnail_url($prod->product_id, 'medium');
					// 			$prod_price = (float) get_post_meta($prod->product_id, '_price', true);

					// 			array_push($the_prods, array('product_id' => $prod->product_id, 'product_name' => $prod->product_name, 'product_weight' => $prod->product_weight, 'product_image' => $product_image, 'product_sm_image' => $product_sm_image, 'product_price' => $prod_price));
					// 		}
					// 	}
					// 	if (!empty($the_prods)) {
					// 		array_push($bar_output['shelves'], array(
					// 			'shelf_id' => $shelf->shelf_id,
					// 			'shelf_name' => $shelf->shelf_name,
					// 			'shelf_weight' => $shelf->shelf_weight,
					// 			'products' => $the_prods
					// 		));
					// 	}
					// }

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

						// Create an array to hold the products, indexed by their weights
						$product_map = array_fill(1, $max_weight, null);

						$default_image_url = get_stylesheet_directory_uri() . '/assets/images/icons/default-blank.png';
						$max_default_count = 3;

						// Collect products into an array
						$product_map = [];
						$has_non_zero_product = false;
						$first_product_weight = null;
						$last_non_zero_weight = 0;
						$default_count = 0;
						$all_ids_zero = true;

						// Process products
						if (!empty($prods)) {
							foreach ($prods as $prod) {
								// Check if product_id is non-zero
								if ($prod->product_id != 0) {
									$all_ids_zero = false;
									$has_non_zero_product = true;
									$last_non_zero_weight = max($last_non_zero_weight, (int) $prod->product_weight);
								}

								// Set the first product weight if it's the first product
								if ($first_product_weight === null) {
									$first_product_weight = $prod->product_weight;
								}

								// Store the product
								$product_map[(int) $prod->product_weight] = array(
									'product_id' => $prod->product_id,
									'product_name' => mb_strimwidth($prod->product_name, 0, 40, "..."),
									'product_weight' => $prod->product_weight,
									'product_image' => !empty($prod->product_id) ? get_the_post_thumbnail_url($prod->product_id, 'full') : '',
									'product_sm_image' => get_the_post_thumbnail_url($prod->product_id, 'medium'),
									'product_price' => (float) get_post_meta($prod->product_id, '_price', true)
								);
							}
						}

						// Prepare the output array based on conditions
						$processed_prods = [];

						// Case 1: All product_ids are zero
						if ($all_ids_zero) {
							$processed_prods = array_fill(0, $max_default_count, array(
								'product_id' => null,
								'product_name' => null,
								'product_weight' => null,
								'product_image' => $default_image_url,
								'product_sm_image' => $default_image_url,
								'product_price' => null
							));
						} else {
							// Case 2 & 3: Handle products based on conditions
							$current_weight = 1;
							$default_added = false;

							foreach ($product_map as $weight => $prod) {
								// Include products up to the last non-zero weight
								if ($weight <= $last_non_zero_weight || !$has_non_zero_product) {
									$processed_prods[] = $prod;

									// Add default products based on conditions
									if ($weight === $last_non_zero_weight && $default_count < $max_default_count) {
										if ($has_non_zero_product && !$default_added) {
											$processed_prods[] = array(
												'product_id' => null,
												'product_name' => null,
												'product_weight' => (string) $weight + 1,
												'product_image' => $default_image_url,
												'product_sm_image' => $default_image_url,
												'product_price' => null
											);
											$default_added = true;
											$default_count++;
										}
									}
								}
							}

							// Ensure at least three products
							if (count($processed_prods) < $max_default_count) {
								while (count($processed_prods) < $max_default_count) {
									$processed_prods[] = array(
										'product_id' => null,
										'product_name' => null,
										'product_weight' => null,
										'product_image' => $default_image_url,
										'product_sm_image' => $default_image_url,
										'product_price' => null
									);
								}
							}
						}

						// Convert the map back to a normal array for output
						$the_prods = $processed_prods;
						$all_products_null = true;
						foreach ($processed_prods as $prod) {
							if ($prod['product_id'] !== null || !empty($prod['product_id'])) {
								$all_products_null = false;
								break;
							}
						}

						// Only push shelf if not all product IDs are null
						if (!$all_products_null) {
							array_push($bar_output['shelves'], array(
								'shelf_id' => $shelf->shelf_id,
								'shelf_name' => $shelf->shelf_name,
								'shelf_weight' => $shelf->shelf_weight,
								'products' => $the_prods
							));
						}


					}

					$user_details = get_user_meta($user_dets->data->ID);
					//print_r($user_details);

					$my_profile = array();
					if ($user_dets->data->ID) {
						$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
						$my_profile['user_id'] = $user_dets->data->ID;
						$my_profile['user_email'] = $user_dets->data->user_email;
						$my_profile['name'] = $user_dets->data->display_name;
						$my_profile['phone_number'] = $user_details['phone_number'][0];
						$my_profile['address'] = $user_details['address'][0];
						$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
						$my_profile['city'] = $user_details['city'][0];
						$my_profile['state'] = $user_details['state'][0];
						$my_profile['zipcode'] = $user_details['zipcode'][0];
						$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
						$my_profile['is_verified'] = $user_details['is_verified'][0];
						$my_profile['avatar'] = $avatar;
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
				if ($shelves[0]->shared == '0')
					return array('message' => 'private bar', 'status' => false);
				else
					return array('message' => 'Bar doesnt exist', 'status' => false);
			}
		} else {
			return array('message' => 'Invalid user details.', 'status' => false);
		}
	}



	function web_get_my_bar()
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

				// Create an array to hold the products, indexed by their weights
				$product_map = array_fill(1, $max_weight, null);

				$default_image_url = get_stylesheet_directory_uri() . '/assets/images/icons/default.png';
				$max_default_count = 3;

				// Collect products into an array
				$product_map = [];
				$has_non_zero_product = false;
				$first_product_weight = null;
				$last_non_zero_weight = 0;
				$default_count = 0;
				$all_ids_zero = true;

				// Process products
				if (!empty($prods)) {
					foreach ($prods as $prod) {
						// Check if product_id is non-zero
						if ($prod->product_id != 0) {
							$all_ids_zero = false;
							$has_non_zero_product = true;
							$last_non_zero_weight = max($last_non_zero_weight, (int) $prod->product_weight);
						}

						// Set the first product weight if it's the first product
						if ($first_product_weight === null) {
							$first_product_weight = $prod->product_weight;
						}

						// Store the product
						$product_map[(int) $prod->product_weight] = array(
							'product_id' => $prod->product_id,
							'product_name' => mb_strimwidth($prod->product_name, 0, 40, "..."),
							'product_weight' => $prod->product_weight,
							'product_image' => !empty($prod->product_id) ? get_the_post_thumbnail_url($prod->product_id, 'full') : '',
							'product_sm_image' => get_the_post_thumbnail_url($prod->product_id, 'medium'),
							'product_price' => (float) get_post_meta($prod->product_id, '_price', true)
						);
					}
				}

				// Prepare the output array based on conditions
				$processed_prods = [];

				// Case 1: All product_ids are zero
				if ($all_ids_zero) {
					$processed_prods = array_fill(0, $max_default_count, array(
						'product_id' => null,
						'product_name' => null,
						'product_weight' => null,
						'product_image' => $default_image_url,
						'product_sm_image' => $default_image_url,
						'product_price' => null
					));
				} else {
					// Case 2 & 3: Handle products based on conditions
					$current_weight = 1;
					$default_added = false;

					foreach ($product_map as $weight => $prod) {
						// Include products up to the last non-zero weight
						if ($weight <= $last_non_zero_weight || !$has_non_zero_product) {
							$processed_prods[] = $prod;

							// Add default products based on conditions
							if ($weight === $last_non_zero_weight && $default_count < $max_default_count) {
								if ($has_non_zero_product && !$default_added) {
									$processed_prods[] = array(
										'product_id' => null,
										'product_name' => null,
										'product_weight' => (string) $weight + 1,
										'product_image' => $default_image_url,
										'product_sm_image' => $default_image_url,
										'product_price' => null
									);
									$default_added = true;
									$default_count++;
								}
							}
						}
					}

					// Ensure at least three products
					if (count($processed_prods) < $max_default_count) {
						while (count($processed_prods) < $max_default_count) {
							$processed_prods[] = array(
								'product_id' => null,
								'product_name' => null,
								'product_weight' => null,
								'product_image' => $default_image_url,
								'product_sm_image' => $default_image_url,
								'product_price' => null
							);
						}
					}
				}

				// Convert the map back to a normal array for output
				$the_prods = $processed_prods;

				array_push($bar_output['shelves'], array(
					'shelf_id' => $shelf->shelf_id,
					'shelf_name' => $shelf->shelf_name,
					'shelf_weight' => $shelf->shelf_weight,
					'products' => $the_prods
				));


			}

			$user_details = get_user_meta($cur_user->data->ID);
			//print_r($user_details);

			$my_profile = array();
			if ($cur_user->data->ID) {
				$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
				$my_profile['user_id'] = $cur_user->data->ID;
				$my_profile['user_email'] = $cur_user->data->user_email;
				$my_profile['name'] = $cur_user->data->display_name;
				$my_profile['phone_number'] = $user_details['phone_number'][0];
				$my_profile['address'] = $user_details['address'][0];
				$my_profile['aptsuitefloor'] = $user_details['aptsuitefloor'][0];
				$my_profile['city'] = $user_details['city'][0];
				$my_profile['state'] = $user_details['state'][0];
				$my_profile['zipcode'] = $user_details['zipcode'][0];
				$my_profile['date_of_birth'] = $user_details['date_of_birth'][0];
				$my_profile['is_verified'] = $user_details['is_verified'][0];
				$my_profile['avatar'] = $avatar;
				$my_profile['likes'] = get_likes_count($cur_user->data->ID);
				$bar_output['user_details'] = $my_profile;
			}

			return $bar_output;
		} else {
			return array('message' => 'Bar doesnt exist', 'status' => false);
		}
	}


	function web_bar_add($item)
	{
		global $wpdb;
		//$item = $request->get_json_params();

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

				$products_query = $wpdb->prepare("SELECT p.id as product_id, p.post_title as product_name, bsp.weight as product_weight  FROM wp_bar_shelves bs LEFT JOIN wp_bar_shelves_products bsp ON bs.id = bsp.shelve_id LEFT JOIN wp_posts p ON bsp.product_id = p.id WHERE bsp.shelve_id = '%d' ORDER BY bs.weight ASC", $shelf->shelf_id);
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


	function is_product_exists_bar($product_id, $user_id)
	{
		global $wpdb;
		$bar_id = get_user_bar_id($user_id);
		$query = $wpdb->prepare("SELECT COUNT(*) AS cnt FROM `wp_bar_shelves_products` p LEFT JOIN wp_bar_shelves s ON p.shelve_id=s.id LEFT JOIN wp_bar b ON b.id=s.bar_id WHERE b.id = '%s' AND p.product_id = '%s'", $bar_id, $product_id);
		$list = $wpdb->get_results($query);
		if ($list[0]->cnt > 0) {
			return true;
		} else {
			return false;
		}
	}

	function get_user_bar_id($user_id)
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

	function get_shelve_id($bar_id)
	{
		global $wpdb;

		$query = $wpdb->prepare("SELECT s.id as id, COUNT(p.product_id) AS products_count FROM  wp_bar_shelves_products p RIGHT JOIN wp_bar_shelves s ON p.shelve_id = s.id WHERE s.bar_id = '%s'  GROUP BY p.shelve_id ORDER BY products_count LIMIT 1", $bar_id);
		$list = $wpdb->get_results($query);

		if ($list[0]->id) {
			return array('shelve_id' => $list[0]->id, 'weight' => ((int) $list[0]->products_count + 1));
		} else {
			return 0;
		}
	}


	/** Disable Ajax Call from WooCommerce */
	add_action('wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
	function dequeue_woocommerce_cart_fragments()
	{
		wp_dequeue_script('wc-cart-fragments');
	}


	function sipn_social_share($title = '')
	{
		global $wp;
		global $post;
		$url = home_url($wp->request);

		if (!$title) {
			$title = $post->post_title;
		}

		$twitter_link = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;

		$facebook_link = 'https://www.facebook.com/sharer/sharer.php?text=' . $title . '&u=' . $url;

		$whatsapp_link = 'https://api.whatsapp.com/send?text=' . $url;

		$mail_link = 'mailto:subject=' . $title . '&body=' . $url;

		$html = '<div class="social-icons"><ul>';
		$html .= '<li><a href="' . $facebook_link . '" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>';
		$html .= '<li><a href="' . $twitter_link . '" target="_blank"><img src="/wp-content/themes/SIPN/assets/images/icon-twitter-white.png"></a></li>';
		$html .= '<li><a href="' . $whatsapp_link . '" data-action="share/whatsapp/share" target="_blank"><i class="fab fa-whatsapp"></i></a></li>';
	//	$html .= '<li><a href="' . $mail_link . '" target="_blank"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>';
		$html .= '<li><a class="copy-cls" href="javascript:void(0);" link="' . $url . '"><i class="fas fa-copy"></i></a></li>';
		$html .= '</ul></div>';

		return $html;
	}

	function my_acf_google_map_api($api)
	{
		$api['key'] = 'AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk';
		return $api;
	}
	add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

	add_filter('manage_product_posts_columns', 'smashing_filter_posts_columns');
	function smashing_filter_posts_columns($columns)
	{
		$columns['productupc'] = __('Product UPC');

		return $columns;
	}


	add_action('manage_product_posts_custom_column', 'smashing_realestate_column', 10, 2);
	function smashing_realestate_column($column, $post_id)
	{
		// Image column
		if ('productupc' === $column) {

			//  echo get_post_meta( $post_id, 'productupc', true );
			$str = get_post_meta($post_id, 'productupc', true);
			$str1 = substr($str, 1);
			echo $str1;
		}
	}





	function ajaxreportforum()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		if ($_POST['reason'] && $_POST['reason'] != '') {
			$to = 'social@sipnbourbon.com'; //social@sipnbourbon.com
			$subject = 'Report post';
			$message = "Hello, <br>The following forum is reported. please check the details below:<br>";
			$message .= "Forum Topic: " . $_POST['topic'] . "<br>";
			$message .= "Forum Reply: " . $_POST['reply'] . "<br>";
			$message .= "Forum Author: " . $_POST['author'] . "<br>";
			$message .= "Forum URL: " . $_POST['forum'] . "<br>";
			$message .= "Reason: " . stripslashes($_POST['reason']) . "<br>";
			$headers = array('Content-Type: text/html; charset=UTF-8', );
			if (wp_mail($to, $subject, $message, $headers)) {
				echo json_encode(array('status' => true, 'message' => __('Forum is reported successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
			}
		}
		exit();
	}



	function ajaxblockuser()
	{
		global $wpdb;
	}
	function ajaxreportbar()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		if ($_POST['reason'] && $_POST['reason'] != '') {
			$to = 'social@sipnbourbon.com'; //social@sipnbourbon.com
			$subject = 'Report post';
			$message = "Hello, <br>The following bar is reported. please check the details below:<br>";
			$message .= "Forum BarName: " . $_POST['bar_name'] . "<br>";
			$message .= "Forum Barlink: " . $_POST['barlink'] . "<br>";
			//$message .= "Forum Author: ".$_POST['author']. "<br>";
			//$message .= "Forum URL: ".$_POST['forum']. "<br>";
			$message .= "Reason: " . stripslashes($_POST['reason']) . "<br>";
			$headers = array('Content-Type: text/html; charset=UTF-8', );
			if (wp_mail($to, $subject, $message, $headers)) {
				echo json_encode(array('status' => true, 'message' => __('Profile reported successfully.')));
			} else {
				echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
			}
		}
		exit();
	}


	function ajaxdelprofile()
	{
		global $wpdb;
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		//  print_r($_POST['cpass']);exit;
		$cur_user = wp_get_current_user();
		$id = $cur_user->data->ID;
		$email = $cur_user->data->user_email;
		$info = array();
		$info['user_login'] = $email;
		$info['user_password'] = $_POST['cpass'];
		$info['remember'] = true;

		$user_signon = wp_signon($info, false);
		if (is_wp_error($user_signon)) {
			echo json_encode(array('status' => 0, 'message' => __('Wrong username or password.')));
		} else {
			$delete_user = $wpdb->query($wpdb->prepare("DELETE FROM `wp_users` WHERE ID = %d", $id));
			$delete_usermeta = $wpdb->query($wpdb->prepare("DELETE FROM `wp_usermeta` WHERE user_id = %d", $id));
			$delete_posts = $wpdb->query($wpdb->prepare("DELETE FROM `wp_posts` WHERE post_author = %d", $id));
			$delete_bar = $wpdb->query($wpdb->prepare("DELETE FROM `wp_bar` WHERE owner_email = %s", $email));
			$delete_reward = $wpdb->query($wpdb->prepare("DELETE FROM `users_rewards` WHERE user_id = %d", $id));
			$delete_reward_history = $wpdb->query($wpdb->prepare("DELETE FROM `user_reward_history` WHERE user_id = %d", $id));

			if ($wpdb->last_error) {
			    return new WP_Error('rest_forbidden', 'Profile not deleted: ' . $wpdb->last_error, array('status' => 403));
			} else {
			    return array("message" => "Profile deleted successfully.", "status" => 1);
			}
		}
		exit();
	}


	//join postmeta for search
	add_filter('posts_join', function ($join) {
		global $pagenow, $wpdb;
		if (is_admin() && 'edit.php' === $pagenow && 'product' === $_GET['post_type'] && !empty($_GET['s'])) {
			$join .= " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
		}
		return $join;
	});

	//search [your_postmeta_key] for search string
	add_filter('posts_where', function ($where) {
		global $pagenow, $wpdb;
		if (is_admin() && 'edit.php' === $pagenow && 'product' === $_GET['post_type'] && !empty($_GET['s'])) {
			$searchstring = '%' . $wpdb->esc_like($_GET['s']) . '%';
			//search [your_postmeta_key] as well
			$where .= $wpdb->prepare(" OR ($wpdb->postmeta.meta_key = 'productupc' AND $wpdb->postmeta.meta_value LIKE %s) ", $searchstring);
		}
		return $where;
	});

	//group by post ID
	add_filter('posts_groupby', function ($groupby, $query) {

		global $pagenow, $wpdb;
		if (is_admin() && 'edit.php' === $pagenow && 'product' === $_GET['post_type'] && !empty($_GET['s'])) {
			$groupby = "{$wpdb->posts}.ID";
		}
		return $groupby;
	}, 10, 2);

	function ajaxwhishlist()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to record your whish list.')));
		}
		if ($_POST['pid'] != '' && $_POST['uid'] != '' && $_POST['type'] != '') {
			if ($_POST['type'] == 'addw') {

				$query = $wpdb->prepare("INSERT INTO `wp_prod_wishlist` (`user_id`, `product_id`) VALUES (%d, %d)", $_POST['uid'], $_POST['pid']);
				$res = $wpdb->query($query);
				$result = 1;
			} else {
				$query = $wpdb->prepare("DELETE FROM `wp_prod_wishlist` WHERE user_id = '%d' AND product_id = '%d'", $_POST['uid'], $_POST['pid']);
				$res = $wpdb->query($query);
				$result = 2;
			}


		}
		echo $result;
		exit;
	}

	function ajaxsponslike()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to record your like.')));
		}

		$cur_user = wp_get_current_user();

		$user_id = $cur_user->data->ID;

		if ($_POST['spons_id'] != '' && $_POST['like'] != '' && $user_id > 0) {
			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $user_id, $_POST['spons_id']);
			$list = $wpdb->get_results($query);

			if ($list[0]->cnt >= 1) {
				if ($_POST['like'] == 0) {
					$query = $wpdb->prepare("DELETE FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $user_id, $_POST['spons_id']);
					$res = $wpdb->query($query);

					$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d'", $_POST['spons_id']);
					$list = $wpdb->get_results($query);
					reward_points("remove",(int)7, $user_id, $_POST['spons_id']);
					echo '0';
				} else {
					echo '1';
				}
			} else {

				$query = $wpdb->prepare("INSERT INTO `wp_sponsored_likes` (`spons_id`, `user_id`) VALUES (%d, %d)", $_POST['spons_id'], $user_id);
				$res = $wpdb->query($query);

				$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d'", $_POST['spons_id']);
				$list = $wpdb->get_results($query);
				reward_points("add",(int)7, $user_id, $_POST['spons_id']);
				echo '1';
			}
		} else {
			echo '0';
		}
		exit();
	}


	function ajaxaddsponscommenttotimeline()
	{
		global $wpdb;
		$item = $_POST;
		//print_r($item);exit;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		$user_id = $cur_user->data->ID;
		if (($item['reply'] != '' || $item['reply_img'] != '') && $item['rid'] > 0) {

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
					$img = $attach_url;
					$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';
					//$item['reply'] = "<img src='".$attach_url."' class='reply_attach'><br>".$item['reply'];
					//update_post_meta($attachment_id,'_wp_attachment_wp_user_avatar',$cur_user);
					//update_user_meta($user_id, 'wp_user_avatar', $attachment_id);
				}
			} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
				$img = '';
				//update_user_meta($user_id, 'wp_user_avatar', '');
			}
			$from_device = 'website';
			$query = $wpdb->prepare("INSERT INTO `wp_sponsored_comments` (`spons_id`, `comment`, `reply_img`, `user_id`, `from_device`) VALUES (%s, %s, %s, %d, %s)", $item['rid'], $item['reply'], $img, $user_id, $from_device);
			$res = $wpdb->query($query);
			$return_msg = reward_points("add",(int)8,$user_id, $item['rid']);
			update_rewards();
			echo json_encode(array("user_name" => $cur_user->data->display_name, "message" => "your post is submitted successfully.", "reward_message" => $return_msg));
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
		}

		exit();
	}


	function ajaxeditsponspost()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}


		$item = $_POST;
		//print_r($item);exit;
		$s = $_POST['rid'];
		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		$user_id = $cur_user->data->ID;
		if ($item['reply'] != '' && $item['rid'] > 0) {

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
					$img = $attach_url;
					$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
					$item['reply'] = '' . $item['reply'] . '<br><img src="' . $attach_url . '" class="reply_attach">';
				}
			} else if (isset($item['reply_img']) && $item['reply_img'] == '') {
				$item['reply'] = preg_replace("/<img[^>]+\>/i", " ", $item['reply']);
				$img = '';
			}

			$reply_id = $item['rid'];
			$reply_content = $item['reply'];
			$from_device = 'website';
			$query = $wpdb->prepare("UPDATE `wp_sponsored_comments` SET  comment='%s', reply_img='%s', user_id='%d', from_device='%s' WHERE comment_id = '%d'", $reply_content, $img, $user_id, $from_device, $reply_id);
			//print_r($query);exit;
			$res = $wpdb->query($query);
			return array("message" => "your post is updated successfully.");
		} else {
			return new WP_Error('rest_forbidden', 'Your post is not published. Please check the provided data.', array('status' => 403));
		}
		exit();
	}

	function ajaxdeletesponscomment()
	{
		global $wpdb;
		// First check the nonce, if it fails the function will break
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}

		$cur_user = wp_get_current_user();
		$user_details = get_user_meta($cur_user->data->ID);
		if ($_POST['reply_id'] != '') {

			$reply_id = $_POST['reply_id'];

			/* changes done by sumeeth
																		if ( ! current_user_can( 'edit_reply', $reply_id ) ) {
																				echo json_encode(array( 'message'=>'You do not have permission to delete that post.', 'status' => 0 ));
																		}
																		*/
			$query = $wpdb->prepare(
	    "SELECT spons_id FROM {$wpdb->prefix}sponsored_comments WHERE comment_id = %d",
			    $_POST["reply_id"]
			);

			$spons_id = $wpdb->get_var($query);
			$query = $wpdb->prepare("DELETE FROM `wp_sponsored_comments` WHERE comment_id = '%d'", $_POST["reply_id"]);
			$res = $wpdb->query($query);
			if ($res == 1) {
				//return array("message"=>"your post is deleted successfully.");
				//added by sumeeth
				reward_points("remove",(int)8, $cur_user->data->ID, $spons_id);
				update_rewards();
				echo json_encode(array("message" => "your post is deleted successfully.", "status" => 1));
			} else {

				echo json_encode(array('message' => 'Your post is not deleted.', 'status' => 0));
			}

			// if(wp_delete_post( $reply_id )){
			// 	echo json_encode(array("message"=>"your post is deleted successfully.", "status"=>1));
			// }
			// else{
			// 	echo json_encode(array( 'message'=>'Your post is not deleted.',  'status' => 0 ));
			// }
		} else {
			echo json_encode(array('message' => 'Your comment is not deleted. Please check the provided data.', 'status' => 0));
		}
		exit();
	}

	// function ajaxsponstimelinecomments()
	// {
	// 	global $wpdb;
	// 	$item = $_POST;

	// 	$parent_id = $item['reply_id'];
	// 	$posts_per_page = -1;

	// 	if ($parent_id > 0) {
	// 		$query = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d' order by created_at Desc", $parent_id);
	// 		$cnt_list = $wpdb->get_results($query);
	// 		$commentscount = count($cnt_list);
	// 		$replies = $cnt_list;
	// 		//print_r($replies);exit;
	// 		$all_replies = array();
	// 		foreach ($replies as $reply) {
	// 			$author_id = $reply->user_id;
	// 			$author_details = get_user_by('id', $author_id);
	// 			$author_meta = get_user_meta($author_id);
	// 			$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

	// 			if (!$avatar) {
	// 				$avatar = get_avatar_url($author_id);
	// 			}

	// 			$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE spons_id = '%d' and comment_id='%d'", $reply->spons_id, $reply->comment_id);
	// 			$cnt_list = $wpdb->get_results($query);
	// 			$likes_count = $cnt_list[0]->cnt;

	// 			$reply_f_image = $reply->reply_img;
	// 			if ($reply_f_image) {
	// 				$reply_image_path = $reply_f_image;
	// 			} else {
	// 				$reply_image_path = '';
	// 			}
	// 			$replies = get_timeline_sponsreplies($reply->comment_id, 1);
	// 			$reply_date = timeline_time_ago($reply->created_at);
	// 			$query1 = $wpdb->prepare("SELECT *  FROM `wp_sponsored_comments` WHERE spons_id = '%d'", $reply->comment_id);
	// 			$cnt_list1 = $wpdb->get_results($query1);
	// 			$total_replies_count = count($cnt_list1);
	// 			$url = get_home_url() . "/timeline_sponsads/?q=" . $reply->spons_id;

	// 			//if ( current_user_can( 'edit_reply', $reply->ID ) ) { by sumeeth
	// 			$cur_user_id = get_current_user_id();
	// 			if ($cur_user_id != 0) {   //for inner comments and sub comments bar link added by sumeeth
	// 				$bid = bbp_get_user_profile_url($author_id);
	// 			} else {
	// 				$bid = 0;
	// 			}
	// 			if ($cur_user_id == $author_id) {
	// 				$edit_flag = 1;
	// 			} else {
	// 				$edit_flag = 0;
	// 			}
	// 			array_push($all_replies, array('reply_id' => $reply->comment_id, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', strip_tags($reply->comment))), 'reply_image' => $reply_image_path, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->created_at, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'avatar' => $avatar, 'url' => $url, 'likes' => $likes_count, 'is_liked' => 0, 'edit_flag' => $edit_flag, 'bid' => $bid, 'replies' => $replies, 'commentscount' => $commentscount));
	// 		}

	// 		//$all_replies['commentscount'] = $commentscount;
	// 		echo json_encode($all_replies);
	// 		exit;
	// 	} else {
	// 		return new WP_Error('rest_forbidden', 'Invalid Topic ID', array('status' => 403));
	// 	}
	// }


	function ajaxsponstimelinecomments()
	{
		global $wpdb;
		$item = $_POST;

		$parent_id = isset($item['reply_id']) ? (int) $item['reply_id'] : 0;
		$cur_user_id = get_current_user_id(); // Current logged-in user

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

		// Proceed with comment fetching and processing
		if ($parent_id > 0) {
			$query = $wpdb->prepare("SELECT * FROM `wp_sponsored_comments` WHERE spons_id = %d ORDER BY created_at DESC", $parent_id);
			$replies = $wpdb->get_results($query);
			$commentscount = count($replies);

			$all_replies = [];

			foreach ($replies as $reply) {
				$author_id = (int) $reply->user_id;

				// Skip replies if the author is in the blocked list (either way)
				if (in_array($author_id, $mutually_blocked_users)) {
					continue;
				}

				$author_details = get_user_by('id', $author_id);
				$author_meta = get_user_meta($author_id);
				$avatar = isset($author_meta['wp_user_avatar'][0]) ? wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail') : get_avatar_url($author_id);

				// Count likes for the comment
				$likes_count = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM `wp_sponsored_likes` WHERE spons_id = %d AND comment_id = %d",
					$reply->spons_id,
					$reply->comment_id
				));

				// Determine reply image
				$reply_image_path = !empty($reply->reply_img) ? esc_url($reply->reply_img) : '';

				// Fetch nested replies (if any)
				$replies_data = get_timeline_sponsreplies($reply->comment_id, 1);

				// Get total reply count for nested replies
				$total_replies_count = $wpdb->get_var($wpdb->prepare(
					"SELECT COUNT(*) FROM `wp_sponsored_comments` WHERE spons_id = %d",
					$reply->comment_id
				));

				// Format reply date
				$reply_date = timeline_time_ago($reply->created_at);

				// URL for the reply
				$url = get_home_url() . "/timeline_sponsads/?q=" . $reply->spons_id;

				// User profile link
				$bid = ($cur_user_id != 0) ? get_home_url() . "/bar/user-" . $author_id : 0;

				// Determine if the current user can edit the reply
				$edit_flag = ($cur_user_id == $author_id) ? 1 : 0;

				$all_replies[] = [
					'reply_id' => (int) $reply->comment_id,
					'reply' => sanitize_text_field($reply->comment),
					'reply_image' => $reply_image_path,
					'reply_date' => $reply_date,
					'reply_gmt_date' => $reply->created_at,
					'total_replies_count' => (int) $total_replies_count,
					'author' => $author_details->data->display_name,
					'author_id' => $author_id,
					'author_city' => isset($author_meta['city'][0]) ? $author_meta['city'][0] : '',
					'author_state' => isset($author_meta['state'][0]) ? $author_meta['state'][0] : '',
					'avatar' => esc_url($avatar),
					'url' => esc_url($url),
					'likes' => (int) $likes_count,
					'is_liked' => 0,
					'edit_flag' => $edit_flag,
					'bid' => esc_url($bid),
					'replies' => $replies_data,
					'commentscount' => $commentscount
				];
			}

			echo json_encode($all_replies);
			exit;
		} else {
			wp_send_json_error(['message' => 'Invalid Topic ID'], 403);
		}
	}


	function ajaxsubscribeunsubscribe()
	{
		global $wpdb;
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => false, 'message' => __('Sorry, Not able to save your changes.')));
		}
		//  print_r($_POST['cpass']);exit;
		$cur_user = wp_get_current_user();
		$id = $cur_user->data->ID;
		$sval = $_POST['sval'];
		$queryupusid = $wpdb->prepare("UPDATE wp_users SET unsubscribe='$sval' WHERE ID =$id");
		//print_r($querybarid);exit;
		$res3 = $wpdb->query($queryupusid);
		//print_r($res3);exit;
		// if($res3==1){
		// 	echo json_encode(array('status'=>true, 'message'=>__('Profile updated successfully.')));
		// }else{
		// 	echo json_encode(array('status'=>false, 'message'=>__('Sorry, Not able to update your profile.'))); 
		// } 

	}

	function ajaxsendingindexdata()
	{
		global $wpdb;
		$item = $_POST;
		$searchtxt = $item['searchtxt'];

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
	                                        "product_title"
	                                    ],
	                                    "type": "phrase_prefix",
	                                    "boost": 5
	                                }
	                            },
	                            {
	                                "multi_match": {
	                                    "query": "' . $searchtxt . '",
	                                    "fields": [
	                                        "product_title"
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
	                                        "product_title"
	                                        
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
	                                        "product_title"
	                                       
	                                    ],
	                                    "type": "most_fields",
	                                    "fuzziness": "2",
	                                    "operator": "and"
	                                }
	                            },
	                            {
	                                "multi_match": {
	                                    "query": "' . $searchtxt . '",
	                                    "fields": [
	                                        "post_title"
	                                    ],
	                                    "type": "phrase_prefix",
	                                    "boost": 5
	                                }
	                            },
	                            {
	                                "multi_match": {
	                                    "query": "' . $searchtxt . '",
	                                    "fields": [
	                                        "post_title"
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
	                                        "post_title"
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
	                                        "post_title"
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
		$url = 'https://search-myfirstsearchdomain-w2gmbg37uqcpumn7u6inan7dii.us-east-1.es.amazonaws.com/sipnproduct,sipnpost/_search';
		// 	$options = array(
		//   'http' => array(
		//     'method'  => 'POST',
		//     'content' => $data,
		//     'header'=>  "Content-Type: application/json\r\n" .
		//                 "Accept: application/json\r\n"
		//     )
		// );


		// $context  = stream_context_create( $options );
		// $result = file_get_contents( $url, false, $context );
		// //$response = json_decode( $result );
		// print_r($result);exit;'
		//$now = new DateTime();
		//print_r($now);
		$data1 = wp_remote_post($url, array(
			'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
			'body' => $data,
			'method' => 'POST'
		));
		//$now = new DateTime();
		//print_r($now); echo "beforeresponse";
		$response = wp_remote_retrieve_body($data1);
		//$now = new DateTime();
		//print_r($now);
		print_r($response);
		exit;
	}


	function custom_rewrite_rule()
	{
		add_rewrite_rule(
			'^bar/user-([0-9]+)/?$',
			'index.php?pagename=bar&user_id=$matches[1]',
			'top'
		);
	}
	add_action('init', 'custom_rewrite_rule');

	function custom_query_vars($vars)
	{
		$vars[] = 'user_id';
		return $vars;
	}
	add_filter('query_vars', 'custom_query_vars');
	// Start the session on 'init'
	function start_session_on_init()
	{
		if (session_status() === PHP_SESSION_NONE) {
	    session_start();
	    session_write_close();
	}
	}
	add_action('init', 'start_session_on_init');

	// Store $_GET values in session
	function store_get_values_in_session()
	{
		if (isset($_GET['si'])) {
			$_SESSION['si'] = sanitize_text_field($_GET['si']);
		}

		if (isset($_GET['w'])) {
			$_SESSION['w'] = sanitize_text_field($_GET['w']);
		}
	}
	add_action('init', 'store_get_values_in_session');


	function custom_login_redirect($redirect_to, $request, $user)
	{
		if (isset($user->roles) && is_array($user->roles)) {
			if (in_array('administrator', $user->roles)) {
				return admin_url();
			}
			if (is_admin()) {
				return admin_url();
			}

			return site_url('/');
		}

		return $redirect_to;
	}
	add_filter('login_redirect', 'custom_login_redirect', 10, 3);


	function send_emails_for_user_signup()
	{
		global $wpdb;
		$users = get_users();

		foreach ($users as $user) {
			$signup_date = strtotime($user->data->user_registered);
			$current_date = current_time('timestamp', 1);
			$days_since_signup = round(($current_date - $signup_date) / (60 * 60 * 24)); // Calculate days since signup

			$validate_email = $wpdb->get_var($wpdb->prepare("SELECT validate_email FROM {$wpdb->users} WHERE ID = %d", $user->ID));
			$is_verified = ($validate_email == 0);

			$message1 = '
			<body style="font-family:Helvetica; padding: 0; margin: 0;">
				<div style="width: 599px; margin:0 auto;">
					<div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;">
						<div style="text-align: center; vertical-align:middle; padding-top:22px;">
							<img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png">
						</div>
					</div>
					<div style="background-color: #2c2c2c; text-align:center; padding-top: 10px; padding-bottom: 50px;">
						<p style="color: #fff; font-size:18px;">To finish signing up,<br> please confirm your email.</p>  
						<a href="https://sipnbourbon.com/wp-json/users/v2/verifyemail?email=' . $user->ID . '" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:16px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Verify Email</a>
					</div>
					<div style="background: #2d2d2c; width: 100%; text-align: center; padding-bottom: 20px;">
						<span style="color:white; font-size: 14px;">
							Please click on <a href="#" style="color: #bca665; text-decoration: none;">Unsubscribe</a> to stop receiving emails from SIPN.
						</span>
						<ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block;">
							<li style="display: inline-block; margin-right:2px;">
								<a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;">
								</a>
							</li>
							<li style="display: inline-block; margin-right:2px;">
								<a href="https://www.facebook.com/sipnbourbon" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;">
								</a>
							</li>
							<li style="display: inline-block;">
								<a href="https://twitter.com/sipnbourbon" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;">
								</a>
							</li>
						</ul>
						<ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
							<li style="display: inline-block; margin-right:3px;">
								<a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a>
							</li>
							<li style="display: inline-block;">
								<a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a>
							</li>
						</ul>
					</div>
				</div>
			</body>';


			$message2 = '
			<body style="font-family:Helvetica; padding: 0; margin: 0;">
				<div style="width: 599px; margin:0 auto;">
					<div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;">
						<div style="text-align: center; vertical-align:middle; padding-top:22px;">
							<img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png">
						</div>
					</div>
					<div style="background-color: #2c2c2c; text-align:center; padding-top: 10px; padding-bottom: 30px;">
						<p style="color: #fff; font-size:14px;"><span style="background-color: #2c2c2c; text-align:center; font-size:24px;">Make your first post today...</span></p>
						<br><br>
						<div style="display:flex; justify-content: center; text-align: left;">
							<ul style="color: #fff;">
								<li>Buy a bottle of bourbon lately?</li>
								<li>Sipn on a cocktail?</li>
								<li>Want to share your favorite brand?</li>
							</ul>
						</div>
						<br>
						<a href="https://sipnbourbon.com/" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:16px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Post Now</a>
					</div>
					<div style="background: #2d2d2c; width: 100%; text-align: center; padding-bottom: 20px;">
						<span style="color:white; font-size: 14px;">
							Please click on <a href="#" style="color: #bca665; text-decoration: none;">Unsubscribe</a> to stop receiving emails from SIPN.
						</span>
						<ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block;">
							<li style="display: inline-block; margin-right:2px;">
								<a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;">
								</a>
							</li>
							<li style="display: inline-block; margin-right:2px;">
								<a href="https://www.facebook.com/sipnbourbon" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;">
								</a>
							</li>
							<li style="display: inline-block;">
								<a href="https://twitter.com/sipnbourbon" target="_blank">
									<img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;">
								</a>
							</li>
						</ul>
						<ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
							<li style="display: inline-block; margin-right:3px;">
								<a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a>
							</li>
							<li style="display: inline-block;">
								<a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a>
							</li>
						</ul>
					</div>
				</div>
			</body>';




			$message4 = '
			        <body style="font-family:Helvetica; padding: 0; margin: 0;">
	            <div style="width: 599px; margin:0 auto;">
	                <div style="background: url(https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/bg-logo.jpg); background-repeat: no-repeat; background-position: 0; height: 110px;">
	                    <div style="text-align: center; vertical-align:middle; padding-top:22px;">
	                        <img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/welcome-email-images/logo-goldsmallnew.png">
	                    </div>
	                </div>
	                <div style="background-color: #2c2c2c; text-align:center; padding-top: 10px; padding-bottom: 30px;">
					<p style="color: #fff; font-size:20px;">Did you know you can make a virtual bar on SIPN?</p>
					</br></br>
					<img style="width: 31%;" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/SIPN-Bar-Screenshot.jpg">
					</br></br>
					<a href="https://sipnbourbon.com" style="background-color: #bca665; border-radius:25px; color:#fff; font-size:16px; font-weight:600; padding:5px 13px; margin-top:5px; text-decoration: none;">Add your first bottle today</a>

	                </div>
	                <div style="background: #2d2d2c; width: 100%; text-align: center; padding-bottom: 20px;">
	                    <span style="color:white; font-size: 14px;">
	                        Please click on <a href="#" style="color: #bca665; text-decoration: none;">Unsubscribe</a> to stop receiving emails from SIPN.
	                    </span>
	                    <ul style="padding: 0; margin:1% 0 0 25%; list-style: none; display: inline-block;">
	                        <li style="display: inline-block; margin-right:2px;">
	                            <a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank">
	                                <img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-instagram.png" style="max-width: 20px;">
	                            </a>
	                        </li>
	                        <li style="display: inline-block; margin-right:2px;">
	                            <a href="https://www.facebook.com/sipnbourbon" target="_blank">
	                                <img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-facebook.png" style="max-width: 20px;">
	                            </a>
	                        </li>
	                        <li style="display: inline-block;">
	                            <a href="https://twitter.com/sipnbourbon" target="_blank">
	                                <img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-twitter.png" style="max-width: 20px;">
	                            </a>
	                        </li>
	                    </ul>
	                    <ul style="padding: 0; margin:2% 25% 0 0; list-style: none; display: inline-block; float: right; font-size: 11px;">
	                        <li style="display: inline-block; margin-right:3px;">
	                            <a style="color: #bca665;" href="https://sipnbourbon.com/terms">Terms</a>
	                        </li>
	                        <li style="display: inline-block;">
	                                                      <a style="color: #bca665;" href="https://sipnbourbon.com/privacy-policy">Privacy Policy</a>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	        </body>';


			// Send 3rd-day reminder if not verified
			if ($days_since_signup == 3 && !$is_verified && !get_user_meta($user->ID, 'email_sent_day_3', true)) {
				wp_mail($user->user_email, 'SIPN is waiting… please verify your account today!', $message1, ['Content-Type: text/html; charset=UTF-8']);
				update_user_meta($user->ID, 'email_sent_day_3', true);
			}

			// Send 4th-day email reminder to make a post
			if ($days_since_signup == 4 && !get_user_meta($user->ID, 'email_sent_day_4', true)) {
				wp_mail($user->user_email, 'The bourbon community is waiting...', $message2, ['Content-Type: text/html; charset=UTF-8']);
				update_user_meta($user->ID, 'email_sent_day_4', true);
			}

			// Send 7th-day push notification
			if ($days_since_signup == 7 && !get_user_meta($user->ID, 'email_sent_day_7', true)) {
				$query = $wpdb->prepare("SELECT device_id FROM `wp_devices` WHERE user_id = %d", $user->ID);
				$android_recipient = $wpdb->get_results($query, ARRAY_N);


				$android_device_ids = array_column($android_recipient, 0);

				require_once 'fcm.php';
				$fcm = new FCM();
				$arrNotification["title"] = 'SIPN';
				$arrNotification["body"] = 'Share what you’re SIPN’ on today';

				$result = $fcm->send_notification($android_device_ids, $arrNotification, "Likefromapp");
			}

			// Send 10th-day email
			if ($days_since_signup == 10 && !get_user_meta($user->ID, 'email_sent_day_10', true)) {
				wp_mail($user->user_email, 'Build your virtual bar today', $message4, ['Content-Type: text/html; charset=UTF-8']);
				update_user_meta($user->ID, 'email_sent_day_10', true);
			}
		}
	}



	function schedule_user_signup_cron_job()
	{
		if (!wp_next_scheduled('check_user_signup_cron')) {
			wp_schedule_event(time(), 'daily', 'check_user_signup_cron');
		}
	}
	add_action('wp', 'schedule_user_signup_cron_job');

	add_action('check_user_signup_cron', 'send_emails_for_user_signup');

	function schedule_indexer_cron_job() {
	    if ( ! wp_next_scheduled( 'run_indexer_cron' ) ) {
	        wp_schedule_event( time(), 'daily', 'run_indexer_cron' ); // change 'daily' to 'hourly' if needed
	    }
	}
	add_action( 'wp', 'schedule_indexer_cron_job' );

	// Hook to your indexer function
	add_action( 'run_indexer_cron', 'my_custom_indexer' );

	function my_custom_indexer() {
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
	}

	function schedule_indexer_cron_job_product() {
	    if ( ! wp_next_scheduled( 'run_indexer_cron_product' ) ) {
	        wp_schedule_event( time(), 'daily', 'run_indexer_cron_product' ); // change 'daily' to 'hourly' if needed
	    }
	}
	add_action( 'wp', 'schedule_indexer_cron_job_product' );

	// Hook to your indexer function
	add_action( 'run_indexer_cron_product', 'my_custom_indexer_products' );

	function my_custom_indexer_products() {
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
	}

	add_action('wp_ajax_track_event_click','track_event_click');
	add_action('wp_ajax_nopriv_track_event_click','track_event_click');

	function track_event_click(){

	    $id = intval($_GET['id']);

	    $clicks = (int) get_post_meta($id,'event_clicks',true);
	    $clicks++;

	    update_post_meta($id,'event_clicks',$clicks);

	    wp_die();
	}
	?>