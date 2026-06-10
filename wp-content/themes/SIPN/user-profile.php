<?php

/**
 * User Profile Bar
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
global $current_user;
wp_get_current_user();

// Get the URL path and extract the user ID
$url_path = $_SERVER['REQUEST_URI'];
$matches = [];
preg_match('/\/user-(\d+)\//', $url_path, $matches);

if (isset($matches[1])) {
    // Use the extracted user ID from the URL
    $user_id = $matches[1];
} else {
    // Fallback to the current user's ID
    $user_id = $current_user->data->ID;
}

// Redirect to the appropriate URL
header('Location: /bar/user-' . $user_id);
exit;


$userdata = '';
$curemail = $current_user->data->user_email;
$unsubscribe = $current_user->data->unsubscribe;
$profile_id = bbp_get_displayed_user_id();
if($profile_id != $current_user->data->ID){

$userdata = get_user_by('ID',$profile_id);
$user_details = get_user_meta($profile_id);
$bar_res = web_user_bar($profile_id);
}else{
	$bar_res = web_get_my_bar();

$user_details = get_user_meta($current_user->data->ID);
}
// echo "<pre>";print_r($bar_res);exit;
$email=$current_user->data->user_email;
$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
$blink=$bar_res['bar_link'];
?>
<article class="col-md-10">
   <div class="wrapper-top-new">
        <div class="container">
        <?php
	if($bar_res['message'] == 'private bar' && $profile_id != $current_user->data->ID){ ?>
        <div class="private-bar"><img class="no-bar-exists" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/bar-is-private.png"> </div>
        <?php }
	else{
		
		if($profile_id != $current_user->data->ID && ($bar_res->message == 'Bar doesnt exist' ||$bar_res['message'] == 'Bar doesnt exist')){
			
			if($profile_id != $current_user->data->ID){
			?>
        <div class="private-bar"><img class="private-bar no-bar-exists" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/bar-is-private.png"> </div>
        <?php
			}
		}else if($profile_id == $current_user->data->ID || ($bar_res->message != 'Bar doesnt exist' || $bar_res['message'] != 'Bar doesnt exist')){
			//first bar create
			if($profile_id == $current_user->data->ID && ($bar_res->message == 'Bar doesnt exist' || $bar_res['message'] == 'Bar doesnt exist')){
					$url1 = site_url().'/wp-json/bar/v1/add'; 
					$body1 = array('name'=>'', 'owner_email'=>$current_user->data->user_email);
					$add_bar_res = web_bar_add($body1);
					//print_r($add_bar_res);
					$bar_link = bbp_get_user_profile_url($current_user->data->ID);
					header("Location: $bar_link");
			}
		?>
        <input type="hidden" id="lemail" value="<?php echo $email; ?>">
        <input type="hidden" id="barlink" value="<?php echo $blink ?> ">
        <div class="row wd-100">
            <div class="newprofile-block">
                <div class="col-md-4">
                    <div class="newprofile-img">
                        <?php if ($avatar) { ?>
                                    <img  src="<?php echo $avatar; ?>" alt="">
                                <?php } else { ?>
                                    <img  src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/img-profile1.jpg">
                     <?php } ?>
                    </div>
                    

                </div>
                <div class="col-md-8">
          <?php if($profile_id == $current_user->data->ID){ ?>
                    <ul class="newprofile-info">
                        <li class="p">
                            <p>
        
                                <?php if (strpos($current_user->data->display_name, 'user-') !== false) {
                                            echo '';
                                        } else {
                                            echo $current_user->data->display_name;
                                        } ?></p>
                        </li>
                        <li class="btn">
                            <a href="/profile-edit"><button type="button">Edit Profile</button></a>
                        </li>
                    </ul>
                <?php }else{ ?>
 <ul class="newprofile-info">
                        <li class="p">
                            <p>
        
                                <?php if (strpos($userdata->data->display_name, 'user-') !== false) {
                                            echo '';
                                        } else {
                                            echo $userdata->data->display_name;
                                        } ?></p>
                        </li>
                    </ul>
                <?php }?>
                    <p class="profile-name"><?php echo $user_details['bio'][0]?></p>
                </div>
            </div>
        </div>
        <?php if(is_user_logged_in() && $current_user->data->validate_email == '0'){ ?>
        <?php if($profile_id == $current_user->data->ID){ ?>
        <div class="col-md-6">
           <h2 class="bar-heading">Bar</h2>
            <div class="chat-detail new-bar-div">
                <div class="my-bar">
                    <?php
						$cnt = 1;
						$total_products_cnt = 0;
						$bar_value = 0;
						
						foreach($bar_res['shelves'] as $shelf){
							 ?>
                   <!--START Added  bar-id and a div by salman on 19-08-2024 to resolve edit issue -->
				   
				    <div class="shelf-container">
                                                    <input type="text" class="shelfedit" ssid="<?php echo $shelf['shelf_id']; ?>"
                                                        id="shelfedit<?php echo $cnt; ?>"
                                                        style="text-align: center;color: #bda766; font-size: 17px; font-family: 'montserratbold';margin: 6px auto 25px auto; padding-top: 6px;border: transparent; text-align: left; width: 90%;"
                                                        value="<?php echo $shelf['shelf_name']; ?>" bar-id="<?php echo $bar_res['bar_id']; ?>">
                                                    <a href="#" id="shelfedit<?php echo $cnt; ?>">
                                                        <h6 class="bar-edit-text">Edit</h6>
                                                    </a>
                                                </div>
					<!-- END -->							
                    <section class="slider shelfedit<?php echo $cnt; ?>-section">
                        <ul slid="<?php echo $shelf['shelf_id'];?>" brid="<?php echo $bar_res['bar_id'];?>" class="slides ui-sortable cs-hidden" id="autoWidth<?php echo $cnt;?>">
                            <?php 
							$p=1;
							foreach($shelf['products'] as $product){

					if(!$product['product_image'])
                                        $product['product_image'] = get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';
                                    if($product['product_id']!=''){

?>
                            <li pid="<?php echo $product['product_id'];?>" class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
                                <div class="box">

                                    <div class="slide-img">

                                        <img alt="<?php echo $product['product_name'];?>" src="<?php echo $product['product_image'];?>">

                                    </div>
                                    <div class="slide-title"><?php echo $product['product_name'];?></div>
                                    <div class="delete-product" dpid="<?php echo $product['product_id'];?>"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-delete-product.png"></div>
                                </div>

                            </li>

                            <?php }
							$p++; 
							$total_products_cnt++; 
							$price = (float)get_post_meta( $product['product_id'], '_price', true );
							$bar_value += $price;} ?>

                            <?php if(count($shelf['products'])>=3){?>
                            <li class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
                                <a href="/?s=&si=<?php echo $shelf['shelf_id'];?>&w=<?php echo $p;?>">
                                    <div class="box">
                                        <div class="slide-img">
                                            <img alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy-lightplus.png">
                                        </div>
                                    </div>
                                </a>
                                <div class="slide-title">...</div>
                                <div class="delete-product"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-delete-product.png"></div>
                            </li>
                            <?php } else{ 
							  for($k=count($shelf['products'])+1; $k<=3; $k++){?>
                            <li class="slide slide<?php echo $k;?> ui-sortable-handle item-a">
                                <a href="/?s=&si=<?php echo $shelf['shelf_id'];?>&w=<?php echo $k;?>">
                                    <div class="box">
                                        <div class="slide-img">
                                            <img alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy-lightplus.png">
                                        </div>
                                    </div>
                                </a>
                                <div class="slide-title">...</div>

                            </li>
                            <?php } } ?>

                        </ul>

                    </section>
                    <?php 
							$cnt++;
						} 
							?>
                </div>
            </div>
            <!-- drag drop HTML init -->
            <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
            <script id="rendered-js">
                                        $(".bar-value").html(<?php echo $bar_value; ?>);
                                    <?php if ($profile_id == $current_user->data->ID) { ?>
                                            var originalShelfID;
                                            $(document).on('mouseleave', '.slide-img', function () {
                                                $('.slides').on('mouseover mouseenter mouseleave mouseup mousedown', function () {
                                                    return false
                                                });
                                            });

                                            const EditBtnText = document.querySelector('.bar-edit-text');

                                            $(".slides").sortable({
                                                placeholder: 'slide-placeholder',
                                                axis: "xy",
                                                revert: 150,
                                                // added by salman 
                                                connectWith: ".slides",
                                                helper: "original",

                                                start: function (e, ui) {
                                                    // Added by Salman to Capture the original shelf ID
                                                    originalShelfID = ui.item.parent('ul.slides').attr('slid');

                                                    placeholderHeight = ui.item.outerHeight();
                                                    ui.placeholder.height(placeholderHeight + 15);
                                                    $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                                                },

                                                change: function (event, ui) {
                                                    ui.placeholder.stop().height(0).animate({
                                                        height: ui.item.outerHeight() + 15
                                                    }, 300);

                                                    placeholderAnimatorHeight = parseInt($(".slide-placeholder-animator").attr("data-height"));

                                                    $(".slide-placeholder-animator").stop().height(placeholderAnimatorHeight + 15).animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        $(this).remove();
                                                        placeholderHeight = ui.item.outerHeight();
                                                        $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                                                    });
                                                },

                                                stop: function (e, ui) {
                                                    $(".slide-placeholder-animator").remove();

                                                    var newShelfID = ui.item.parent('ul.slides').attr('slid'); // New shelf ID
                                                    var bar_id = ui.item.parent('ul.slides').attr('brid');

                                                    var products_order = [];
                                                    var cnt = 1;

                                                    // Added by Salman to Remove the product from the original shelf if it has changed
                                                    if (originalShelfID !== newShelfID) {
                                                        $('ul.slides[slid="' + originalShelfID + '"]').find('li[pid="' + ui.item.attr('pid') + '"]').remove();
                                                    }


                                                    ui.item.parent('ul.slides').find('li.slide').not(".clone").each(function () {
                                                        if ($(this).attr('pid')) {
                                                            products_order.push($(this).attr('pid'));
                                                            cnt++;
                                                        }
                                                    });




                                                    $.ajax({
                                                        type: 'POST',
                                                        dataType: 'json',
                                                        url: site_script_object.ajaxurl,
                                                        data: {
                                                            'action': 'ajaxproductsreorder',
                                                            'products_order': products_order,
                                                            'shelf_id': newShelfID,
                                                            'originalshelfid': originalShelfID,
                                                            'bar_id': bar_id,
                                                            'nonce': site_script_object.nonce,
                                                        },
                                                        success: function (data) {
                                                            // Handle success (optional)
                                                        }
                                                    });
                                                }
                                            });

                                    <?php } ?>
                                    </script>


        </div>
        <?php } else { ?>
        <div class="col-md-6">
            <div class="chat-detail">
                <div class="my-bar">
                    <?php
						$cnt = 1;
						$total_products_cnt = 0;
						$bar_value = 0;
						//print_r($bar_res->shelves);
						if(count($bar_res['shelves'])>0){
						foreach($bar_res['shelves'] as $shelf){
							if(count($shelf['products'])>0){ ?>
                    <h2><?php echo $shelf['shelf_name'];?></h2>
                    <!-- Flickity HTML init -->
                    <section class="slider">
                        <ul class="slides ui-sortable cs-hidden" id="autoWidth<?php echo $cnt;?>">
                            <?php 
							$p=1;
							foreach($shelf['products'] as $product){
					if(!$product['product_image'])
                                        $product['product_image'] = get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';
?>
                            <li class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
                                <div class="box">

                                    <div class="slide-img">
                                        <a href="<?php echo get_permalink($product['product_id']);?>">
                                            <img alt="<?php echo $product['product_name'];?>" src="<?php echo $product['product_image'];?>">
                                        </a>
                                    </div>
                                    <div class="slide-title"><?php echo $product['product_name'];?></div>

                                </div>

                            </li>

                            <?php 
								$p++; 
								$total_products_cnt++; 
								$price = (float)get_post_meta( $product['product_id'], '_price', true );
								$bar_value += $price;
							} ?>

                            <?php if(count($shelf['products'])<3){ 
							  for($k=count($shelf['products'])+1; $k<=3; $k++){?>
                            <li class="slide slide<?php echo $k;?> ui-sortable-handle item-a">

                                <div class="box">
                                    <div class="slide-img">
                                        <img class="empty-bottle" alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy.png">
                                    </div>
                                </div>

                                <div class="slide-title">...</div>

                            </li>
                            <?php } } ?>
                        </ul>

                    </section>
                    <?php }
							$cnt++;
						}} if($total_products_cnt<=0){ echo '<h2>No products in the Bar</h2>';}
							?>
                </div>
            </div>
            <!-- for edit modal report -->
            <div id="reportModal" class="modal">
                <div class="modal-content">
                    <header>
                        <h2>Report</h2>
                        <span class="close">×</span>
                    </header>
                    <div class="report">
                        <p><strong>Why are you reporting this post?</strong></p>
                        <p>Your report is confidential, this will keep the SIPN community cleaner for all the users. </p>
                        <ul>
                            <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate Speech<span><i class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="Prohibited Content">Prohibited Content<span><i class="fa fa-chevron-right"></i></span></a></li>

                        </ul>

                    </div>
                </div>
            </div>
          <div id="blockuserModal" class="modal">
    <div class="modal-content">
        <div class="report">
            <h2>Alert !</h2>
           <p class="content_delete">Are you sure you want to block this profile?</p>
             <div class="row">
                <div class="btns-cancel-proceed">
                    <a href="javascript:void(0);" class="block-cancel"><button class="btn btn-profile-cancel">Cancel</button></a>
                    <a href="javascript:void(0);" class="block-user-profile" ><button class="btn btn-profile-save">Proceed</button></a>
                </div>
            </div>

            
        </div>
    </div>
</div>

           

        </div>
        <?php } }else if(is_user_logged_in() && $current_user->data->validate_email == '1'){ ?>
            <div class="col-md-6">
            <div class="chat-detail">
                <div class="my-bar">
                   <p >Email not verified</p>
                <p>We sent an email to you, please verify your email to continue
                </p>
                <div class="resendemail-main">
                    <a href="#" class="resendemail" data-id="<?php echo $curemail; ?>" id="resendemail">Resend verification mail</a>
                </div>
                </div>
            </div>
           </div>
        <?php }else{
        echo '<script> window.location.href = "/login" </script>';
        } ?>

        <!-- added by raghu !-->
        <div class="col-md-6 bar-container">

            <!-- <div class="img-chatdetail bar-img-height">
			<img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-bar.png"> 
			</div> -->

            <?php if($profile_id == $current_user->data->ID){ ?>
            <div class="chat-detail-profile-page">
                <div class="my-bar view-only">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <h4 class="subscribeunsubscribe">Subscribe Email Notifications:</h4>
                            <label class="switch1">
                                <input type="checkbox" class="check" <?php if ($unsubscribe == 0) {
                                                                                    echo "checked";
                                                                                } else {
                                                                                }  ?>>
                                <span class="slider round"></span>
                                <input type="hidden" name="checkval" id="checkval" value="<?php echo $unsubscribe; ?>">
                            </label>
                            <div class="op"></div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Name:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-name"><?php if (strpos($current_user->data->display_name, 'user-') !== false) {
                                                                echo '';
                                                            } else {
                                                                echo $current_user->data->display_name;
                                                            } ?></h5>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Email Address:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-name"><?php echo $current_user->data->user_email; ?></h5>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Address:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-addr"><?php echo $user_details['address'][0]; ?></h5>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>City:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-city"><?php echo $user_details['city'][0]; ?></h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>State:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-state"><?php echo $user_details['state'][0]; ?></h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Zip:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-zip"><?php echo $user_details['zipcode'][0]; ?></h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Phone Number:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-phone"><?php echo $user_details['phone_number'][0]; ?></h5>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h4>Date Of Birth:</h4>
                        </div>
                        <div class="col-md-6">
                            <h5 class="view-dob"><?php echo $user_details['date_of_birth'][0]; ?></h5>
                        </div>
                    </div>

                    <!-- Flickity HTML init -->
                </div>

                <div class="edit-pro-form" style="display:none;">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" pattern="[A-Za-z]{1,32}" placeholder="Name*" name="name" required value="<?php if (strpos($cur_user->data->display_name, 'user-') !== false) { echo '';} else {echo $cur_user->data->display_name;} ?>" disabled="disabled">
                        </div>
                        <div class="col-md-6">
                            <label><?php echo $cur_user->data->user_email; ?></label>
                        </div>
                    </div>
                    <input type="text" placeholder="Address" name="address" id="ship-address" required value="<?php echo $user_details['address'][0]; ?>" disabled="disabled" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" placeholder="Apt/Suite/Floor" name="aptsuitefloor" required value="<?php echo $user_details['aptsuitefloor'][0]; ?>" disabled="disabled">
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="City" name="city" id="locality" required value="<?php echo $user_details['city'][0]; ?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" placeholder="State" name="state" id="state" required value="<?php echo $user_details['state'][0]; ?>" disabled="disabled">
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="Zip" name="zip" id="postcode" required value="<?php echo $user_details['zipcode'][0]; ?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" placeholder="Phone Number" name="phone" required value="<?php echo $user_details['phone_number'][0]; ?>" disabled="disabled">
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="Date Of Birth" name="dob" required value="<?php echo $user_details['date_of_birth'][0]; ?>" disabled="disabled">
                        </div>
                    </div>

                    <button type="submit" class="signin hide-btn">Save</button>

                </div>
            </div>


            <h1 class="profile bar-title"><span><?php echo stripslashes($bar_res['bar_name']);?></span> <i class="far fa-edit bar-edit"></i></h1>
            <div class="edit-bar-sec" style="display:none;">
                <input type="text" name="bar_name" id="bar_name" value="<?php echo stripslashes($bar_res['bar_name']);?>">
                <p class="edibar" style="color:red;display:none;">This field is required </p>
                <div class="onoff">
                    <span style="margin-right: 10px; line-height: 22px;">Profile (Private/Public)</span>
                    <label class="switch">
                        <input type="checkbox" name="bar_state" id="bar_state" <?php if($bar_res['is_public']){echo "checked=checked";}?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <input type="button" class="<?php if($bar_res['bar_name']=='') { echo 'colorbttn'; } ?>" id="save_bar" bid="<?php echo $bar_res['bar_id'];?>" value="Save" <?php if($bar_res['bar_name']=='') { echo 'disabled="disabled"'; } ?>>
            </div>
            <?php }?>
            
            
            
            <?php if($profile_id == $current_user->data->ID){ }else{?>
                <div class="prof-details">
                <?php
            $liked_flag = get_profile_like_flag($profile_id);
            if($liked_flag){
                $like_class =  "";
            }else{
                $like_class =  "inactive";
            }
            ?>
                <div class="like <?php echo $like_class;?>"><a liked="<?php echo $liked_flag; ?>" pid="<?php echo $profile_id; ?>" class="<?php if ( is_user_logged_in() ){ echo 'like_profiles';}else{?> nologinaction <?php } ?>" href="javascript:void(0);"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="profile_likes_count"><?php if($profile_id == $current_user->data->ID){echo $bar_res['user_details']['likes'];}else{ echo $bar_res['user_details']['likes']; }?></span></a></div>

                <!-- <div class="location"><a href=""><i class="fa fa-map-marker" aria-hidden="true"></i> FL, USA</a></div> -->
                <small>Est. value: $<span class='bar-value'>
                    <?php 
                $num= number_format((float)$bar_res['user_details']['bar_value'], 2, '.', '');  
                if($profile_id == $current_user->data->ID){
                    echo  $num;
                }else{
                    echo $num;}?></span></small>
            </div>
                <div class="wishlist-icons">
                <?php echo sipn_social_share(stripslashes($bar_res['bar_name'])); ?>
            </div>
            <div class="userreportbar"><a href="javascript:void(0);" class="report-bar">Report</a></div>
            <div class="userreportbar"> <a href="javascript:void(0);" class="block-profile" data-blockuserid="<?php echo $profile_id;?>"data-curuserid="<?php echo $current_user->data->ID;?>">Block</a></div>
            <?php } ?>
        </div>
        <!-- added by raghu i-->
        <?php } } ?>
    </div>
    </div>
      <!-- drag drop HTML init -->

            <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
            <script id="rendered-js">
                $(".bar-value").html(<?php echo $bar_value;?>);
            </script>
    <script>
        $("#bar_name").on("keyup", function() {
            //alert('hi');
            var barname = $(this).val();
            if (barname.trim().length > 0 == '') {
                $('#save_bar').prop("disabled", true);
                $('#save_bar').addClass('colorbttn');
                $('.edibar').show();
            } else {
                $('#save_bar').removeAttr("disabled");
                $('#save_bar').removeClass('colorbttn');
                $('.edibar').hide();
            }
        });
        $(document).ready(function() {
            $('body').on('click', '.report-bar', function(e) {

                var modal = document.getElementById("reportModal");
                modal.style.display = "block";

            });
            $('body').on('click', '.block-profile', function(e) {

                var modal = document.getElementById("blockuserModal");
                modal.style.display = "block";

            });
            $('body').on('click', '.block-cancel', function(e) {

                var modal = document.getElementById("blockuserModal");
                modal.style.display = "none";

            });
            $('body').on('click', '.close', function(e) {

                var modal = document.getElementById("reportModal");
                modal.style.display = "none";

            });
            $('body').on('click', '.block-user-profile,.blocked-profile', function(e) {  
               

                var blockuserid = $(".block-profile").attr('data-blockuserid');
                var curuserid = $(".block-profile").attr('data-curuserid');
                var search_data = { "user-id": curuserid,"block_user_id": blockuserid, "type": 'block' };
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
                 var modal = document.getElementById("blockuserModal");
                modal.style.display = "none";
                $(".block-profile").html('Un Block');
                $(".block-profile").addClass('blocked-profile');
                $(".block-profile").removeClass('block-profile');
                /*alert('3');
                console.log(data);*/
            });
            });
            $('body').on('click', '.report_post', function(e) {

                var barname = $("#bar_name").val();
                var barlink = $("#barlink").val();
                var lemail = $("#lemail").val();
                var reason = $(this).attr('rep');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: site_script_object.ajaxurl,
                    data: {
                        'action': 'ajaxreportbar', //calls wp_ajax_nopriv_ajaxlogin
                        'barname': barname,
                        'barlink': barlink,
                        'lemail': lemail,
                        'reason': reason,
                        'nonce': site_script_object.nonce,
                    },
                    success: function(data) {
                        //alert(data.message);
                        //console.log(data.status);
                        //if(data.status){
                        //var sec = "#msg-"+reply_id;
                        //$(sec).hide();
                        //}
                        var modal = document.getElementById("reportModal");
                        modal.style.display = "none";
                        if (data.message == 'Bar is reported successfully.') {
                            alert('Thanks For Reporting');
                        }
                    }
                });
            });

        });
    </script>
      <!-- drag drop HTML init -->

            <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
            <script id="rendered-js">
                $(".bar-value").html(<?php echo $bar_value;?>);
            </script>
    