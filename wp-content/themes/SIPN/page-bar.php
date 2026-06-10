<?php
/**
 * Template Name: SIPN Bar
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
get_header();

global $current_user;
$cur_user = wp_get_current_user();

// $bar_output = web_get_my_bar();
// echo '<pre>';
// print_r($bar_output);
// exit;

$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$path = parse_url($current_url, PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));
$last_part = end($parts);

$profile_id = str_replace('user-', '', $last_part);

if ($profile_id != $current_user->data->ID) {
    $userdata = get_user_by('ID', $profile_id);
    $user_details = get_user_meta($profile_id);
    $bar_output = web_user_bar($profile_id);
} else {
    $bar_output = web_get_my_bar();
    $user_details = get_user_meta($current_user->data->ID);
}
$totalReward = get_total_rewards($current_user->data->ID);
$totalPrice = 0;

foreach ($bar_output['shelves'] as $shelf) {
    foreach ($shelf['products'] as $product) {
        $price = isset($product['product_price']) ? (float) $product['product_price'] : 0;
        $totalPrice += $price;
    }
}



$email = $current_user->data->user_email;
$unsubscribe = $current_user->data->unsubscribe;
$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
$blink = $bar_output['bar_link'];
?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bar.css" type="text/css" defer>
<article class="col-md-10">
    <div class="wrapper-top">
        <div class="wrapper-bottom">

            <div class="container">
                <?php
                if ($profile_id == $current_user->data->ID || ($bar_output->message != 'Bar doesnt exist' || $bar_output['message'] != 'Bar doesnt exist')) {
                    // First bar creation
                    if ($profile_id == $current_user->data->ID && ($bar_output->message == 'Bar doesnt exist' || $bar_output['message'] == 'Bar doesnt exist')) {
                        $url1 = site_url() . '/wp-json/bar/v1/add';
                        $body1 = array('name' => '', 'owner_email' => $current_user->data->user_email);
                        $add_bar_res = web_bar_add($body1);
                        // Redirect to the user's bar link after creation
                        $bar_link = bbp_get_user_profile_url($current_user->data->ID);
                        header("Location: $bar_link");
                    } ?>
                    <input type="hidden" id="lemail" value="<?php echo $email; ?>">
                    <input type="hidden" id="barlink" value="<?php echo $blink; ?>">
                    <div class="row wd-100">
                        <div class="newprofile-block">
                            <div class="col-md-4">
                                <div class="newprofile-img">
                                    <?php if ($avatar) { ?>
                                        <img src="<?php echo $avatar; ?>" alt="">
                                    <?php } else { ?>
                                        <img
                                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/img-profile1.jpg">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if ($profile_id == $current_user->data->ID) { ?>
                                    <ul class="newprofile-info">
                                        <li class="p">
                                            <p>
                                                <?php if (strpos($current_user->data->display_name, 'user-') === false) {
                                                    echo $current_user->data->display_name;
                                                } ?>
                                            </p>
                                        </li>
                                        <li class="btn">
                                            <a href="/profile-edit"><button type="button">Edit Profile</button></a>
                                        </li>
                                        <div class="btn-profile-rewards">
                                        <?php if($userdata->data->validate_email == 0){ ?>
                                        <li class="btn">
                                            <a href="/rewards"><button type="button" class="rewards-btn"><span><?php echo $totalReward ? $totalReward : 0; ?> pts</span>Rewards</button></a>
                                        </li>
                                        <?php } ?>
                                        </div>
                                    </ul>
                                <?php } else { ?>
                                    <ul class="newprofile-info">
                                        <li class="p">
                                            <p>
                                                <?php if (strpos($userdata->data->display_name, 'user-') === false) {
                                                    echo $userdata->data->display_name;
                                                } ?>
                                            </p>
                                        </li>
                                        <!-- <li class="p">
                                            <span class="company_verified"><img src="https://staging.sipnbourbon.com/wp-content/themes/SIPN/assets/images/badge-gold.png" width="23"></span>
                                        </li> -->
                                    </ul>
                                <?php } ?>
                                <p class="profile-name"><?php echo $user_details['bio'][0]; ?></p>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <?php if ($bar_output['message'] == 'private bar' && $profile_id != $current_user->data->ID) { ?>
                            <div class="private-bar">
                                <img class="no-bar-exists"
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bar-is-private.png">
                            </div>
                        <?php } else if ($profile_id != $current_user->data->ID && ($bar_output['message'] == 'Bar doesnt exist' || $bar_output['message'] == 'Bar doesnt exist')) {
                            if ($profile_id != $current_user->data->ID) { ?>
                                    <div class="private-bar">
                                        <img class="private-bar no-bar-exists"
                                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bar-is-private.png">
                                    </div>
                            <?php }
                        } else if (is_user_logged_in() && $current_user->data->validate_email == '0') { ?>
                                    <div class="shelf-container <?= ($profile_id != $current_user->data->ID) ? '' : 'show-default' ?>"
                                        bar_id='<?= $bar_output['bar_id'] ?>'>
                                        <div id="ajax-success-message" class="custom-alert alert alert-success text-center"
                                            role="alert" style="display: none;">
                                        </div>
                                <?php if ($profile_id == $current_user->data->ID) { ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex"
                                                        style="display: flex; justify-content: end; align-items: center;">
                                                        <!-- <h1><?= $bar_output['bar_name'] ?></h1> -->
                                                        <button class="btn btn-default bar-edit" type="button">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                    <?php
                                }
                                if (isset($bar_output['shelves'])) {
                                    foreach ($bar_output['shelves'] as $shelves) { ?>
                                                <input type="text" class="shelfedit" maxlength="30" ssid="<?= $shelves['shelf_id']; ?>"
                                                    style="text-align: center;color: #bda766; font-size: 17px; font-family: 'montserratbold'; border: transparent; text-align: left; width: 90%;"
                                                    value="<?= $shelves['shelf_name'] ?>" readonly="readonly">
                                                <div class="shelf">
                                                    <button class="carousel-button left" onclick="scrollCarousel(this, 'left')"><span
                                                            class="circle-icon">&lt;</span></button>
                                                    <div class="carousel" shelf_id="<?= $shelves['shelf_id']; ?>">
                                                    <?php
                                                    $i = 1;
                                                    foreach ($shelves['products'] as $bottle) {
                                                        $product_id = isset($bottle['product_id']) ? (int) $bottle['product_id'] : 0;
                                                        if ($product_id === 0) {
                                                            $nondragable = 'non-draggable';
                                                        } else {
                                                            $nondragable = '';
                                                        }

                                                        ?>
                                                            <div class="bottle <?= $nondragable ?>" data-id="<?= $i ?>"
                                                                pid="<?= isset($bottle['product_id']) ? $bottle['product_id'] : 0 ?>">
                                                        <?php if (isset($bottle['product_id']) && ($bottle['product_id'] != 0)) { ?>
                                                                    <a href="<?php echo get_permalink($bottle['product_id']); ?>"><img
                                                                            src="<?= isset($bottle['product_image']) ? $bottle['product_image'] : get_stylesheet_directory_uri() . '/assets/images/icons/product.jpg' ?>"
                                                                            alt="Bottle 1"
                                                                            onerror="this.onerror=null; this.src='<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/product.jpg';"></a>
                                                        <?php } else if ($bottle['product_image'] == get_stylesheet_directory_uri() . '/assets/images/icons/default-blank.png') { ?>
                                                                        <img src="<?= isset($bottle['product_image']) ? $bottle['product_image'] : get_stylesheet_directory_uri() . '/assets/images/icons/default.png'; ?>"
                                                                            alt="Bottle 1"
                                                                            onerror="this.onerror=null; this.src='<?= get_stylesheet_directory_uri() . '/assets/images/icons/default.png'; ?>';">

                                                        <?php } else { ?>
                                                                        <a href="/?s=&si=<?= $shelves['shelf_id']; ?>&w=<?php echo $i; ?>">
                                                                            <img src="<?= isset($bottle['product_image']) ? $bottle['product_image'] : get_stylesheet_directory_uri() . '/assets/images/icons/default.png'; ?>"
                                                                                alt="Bottle 1"
                                                                                onerror="this.onerror=null; this.src='<?= get_stylesheet_directory_uri() . '/assets/images/icons/default.png'; ?>';">

                                                                        </a>
                                                        <?php }
                                                        if (isset($bottle['product_id']) && ($bottle['product_id'] != 0)) { ?>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-delete-product.png"
                                                                        class="delete-bottle" dpid="<?= $bottle['product_id'] ?>">
                                                        <?php } else { ?>
                                                                    <span class="delete-bottle"> ... </span>
                                                        <?php } ?>
                                                                <div class="bottle-title">
                                                            <?= isset($bottle['product_name']) ? $bottle['product_name'] : "..." ?>
                                                                </div>
                                                            </div>
                                                        <?php

                                                        $i++;
                                                    } ?>
                                                    </div>
                                                    <!-- <a class="next" href="javascript:void(0);" onclick="scrollCarousel(this, 'right')">❯</a> -->
                                                    <button class="carousel-button right" onclick="scrollCarousel(this, 'right')"><span
                                                            class="circle-icon">&gt;</span></button>
                                                </div>

                                        <?php
                                    }
                                } else {
                                    echo '<h1 class="text-center">No products in the Bar</h1>';
                                } ?>
                                    </div>
                        <?php } else if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>
                                        <div class="col-md-6">
                                            <div class="chat-detail">
                                                <div class="my-bar">
                                                    <p>Email not verified</p>
                                                    <p>We sent an email to you, please verify your email to continue
                                                    </p>
                                                    <div class="resendemail-main">
                                                        <a href="#" class="resendemail" data-id="<?php echo $curemail; ?>"
                                                            id="resendemail">Resend
                                                            verification mail</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        <?php } else {
                            echo '<script> window.location.href = "/login" </script>';
                        } ?>
                    </div>
                    <div class="col-md-6">
                        <?php if ($profile_id == $current_user->data->ID) { ?>
                            <div class="chat-detail-profile-page">
                                <div class="my-bar view-only">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h4 class="subscribeunsubscribe">Subscribe Email Notifications:</h4>
                                            <label class="switch1">
                                                <input type="checkbox" class="check" <?php if ($unsubscribe == 0) {
                                                    echo "checked";
                                                } else {
                                                } ?>>
                                                <span class="slider round"></span>
                                                <input type="hidden" name="checkval" id="checkval"
                                                    value="<?php echo $unsubscribe; ?>">
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
                                            <input type="text" pattern="[A-Za-z]{1,32}" placeholder="Name*" name="name"
                                                required value="<?php if (strpos($cur_user->data->display_name, 'user-') !== false) {
                                                    echo '';
                                                } else {
                                                    echo $cur_user->data->display_name;
                                                } ?>" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label><?php echo $cur_user->data->user_email; ?></label>
                                        </div>
                                    </div>
                                    <input type="text" placeholder="Address" name="address" id="ship-address" required
                                        value="<?php echo $user_details['address'][0]; ?>" disabled="disabled"
                                        autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Apt/Suite/Floor" name="aptsuitefloor" required
                                                value="<?php echo $user_details['aptsuitefloor'][0]; ?>"
                                                disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="City" name="city" id="locality" required
                                                value="<?php echo $user_details['city'][0]; ?>" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" placeholder="State" name="state" id="state" required
                                                value="<?php echo $user_details['state'][0]; ?>" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Zip" name="zip" id="postcode" required
                                                value="<?php echo $user_details['zipcode'][0]; ?>" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Phone Number" name="phone" required
                                                value="<?php echo $user_details['phone_number'][0]; ?>" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="Date Of Birth" name="dob" required
                                                value="<?php echo $user_details['date_of_birth'][0]; ?>"
                                                disabled="disabled">
                                        </div>
                                    </div>

                                    <button type="submit" class="signin hide-btn">Save</button>

                                </div>




                            </div>
                            <div class="prof-details">
                                <?php
                                $liked_flag = get_profile_like_flag($profile_id);
                                if ($liked_flag) {
                                    $like_class = "";
                                } else {
                                    $like_class = "inactive";
                                }
                                ?>
                                <div class="like <?php echo $like_class; ?>"><a liked="<?php echo $liked_flag; ?>"
                                        pid="<?php echo $profile_id; ?>" class="<?php if (is_user_logged_in()) {
                                               echo 'like_profile';
                                           } else { ?> nologinaction <?php } ?>" href="javascript:void(0);"><i
                                            class="fa fa-thumbs-up" aria-hidden="true"></i> <span
                                            class="profile_likes_count"><?php if ($profile_id == $current_user->data->ID) {
                                                echo $bar_output['user_details']['likes'];
                                            } else {
                                                echo $bar_output['user_details']['likes'];
                                            } ?></span></a>
                                </div>

                                <!-- <div class="location"><a href=""><i class="fa fa-map-marker" aria-hidden="true"></i> FL, USA</a></div> -->
                                <small>Est. value: $<span class='bar-value'>
                                        <?php
                                        $num = number_format((float) $totalPrice, 2, '.', '');
                                        if ($profile_id == $current_user->data->ID) {
                                            echo $num;
                                        } else {
                                            echo $num;
                                        } ?></span></small>
                                <div style="height:2vh;"></div>
                            </div>

                            <div class="wishlist-icons">
                                <?php echo sipn_social_share(stripslashes($bar_output['bar_name'])); ?>
                            </div>

                            <div class="bar-container">
                                <h1 class="profile bar-title">
                                    <span><?php echo stripslashes($bar_output['bar_name']); ?></span> <i
                                        class="far fa-edit bar-edit show-bar-edit"></i>
                                </h1>
                                <div class="edit-bar-sec" style="display:none;">
                                    <input type="text" name="bar_name" id="bar_name"
                                        value="<?php echo stripslashes($bar_output['bar_name']); ?>">
                                    <p class="edibar" style="color:red;display:none;">This field is required </p>
                                    <div class="onoff">
                                        <span style="margin-right: 10px; line-height: 22px;">Profile (Private/Public)</span>
                                        <label class="switch">
                                            <input type="checkbox" name="bar_state" id="bar_state" <?php if ($bar_output['is_public']) {
                                                echo "checked=checked";
                                            } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <input type="button" class="<?php if ($bar_output['bar_name'] == '') {
                                        echo 'colorbttn';
                                    } ?>" id="save_bar" bid="<?php echo $bar_output['bar_id']; ?>" value="Save" <?php if ($bar_output['bar_name'] == '') {
                                            echo 'disabled="disabled"';
                                        } ?>>
                                </div>
                            </div>

                        <?php } else { ?>
                            <div class="prof-details">
                                <?php
                                $liked_flag = get_profile_like_flag($profile_id);
                                if ($liked_flag) {
                                    $like_class = "";
                                } else {
                                    $like_class = "inactive";
                                }
                                ?>
                                <div class="like <?php echo $like_class; ?>"><a liked="<?php echo $liked_flag; ?>"
                                        pid="<?php echo $profile_id; ?>" class="<?php if (is_user_logged_in()) {
                                               echo 'like_profile';
                                           } else { ?> nologinaction <?php } ?>" href="javascript:void(0);"><i
                                            class="fa fa-thumbs-up" aria-hidden="true"></i> <span
                                            class="profile_likes_count"><?php if ($profile_id == $current_user->data->ID) {
                                                echo $bar_output['user_details']['likes'];
                                            } else {
                                                echo $bar_output['user_details']['likes'];
                                            } ?></span></a>
                                </div>

                                <!-- <div class="location"><a href=""><i class="fa fa-map-marker" aria-hidden="true"></i> FL, USA</a></div> -->
                                <small>Est. value: $<span class='bar-value'>
                                        <?php
                                        $num = number_format((float) $totalPrice, 2, '.', '');
                                        if ($profile_id == $current_user->data->ID) {
                                            echo $num;
                                        } else {
                                            echo $num;
                                        } ?></span></small>
                                <div style="height:2vh;"></div>
                            </div>
                            <div class="wishlist-icons">
                                <?php echo sipn_social_share(stripslashes($bar_output['bar_name'])); ?>
                            </div>
                            <div style="height:2vh;"></div>
                            <div class="userreportbar"><a href="javascript:void(0);" class="report-bar">Report</a></div>
                            <div style="height:2vh;"></div>
                            <div class="userreportbar"> <a href="javascript:void(0);" class="block-profile"
                                    data-blockuserid="<?php echo $profile_id; ?>"
                                    data-curuserid="<?php echo $current_user->data->ID; ?>">Block</a>
                            </div>
                        <?php } ?>


                    </div>
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
                        <p><strong>Why are you reporting this profile?</strong></p>
                        <p>Your report is confidential, this will keep the SIPN community cleaner for all the users.
                        </p>
                        <ul>
                            <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i
                                            class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate Speech<span><i
                                            class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's
                                    inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                            <li><a class="report_post" href="javascript:void(0);" rep="Prohibited Content">Prohibited
                                    Content<span><i class="fa fa-chevron-right"></i></span></a></li>

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
                                <a href="javascript:void(0);" class="block-cancel"><button
                                        class="btn btn-profile-cancel">Cancel</button></a>
                                <a href="javascript:void(0);" class="block-user-profile"><button
                                        class="btn btn-profile-save">Proceed</button></a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div id="common_alert" class="modal" style="z-index:10200;">
                <div class="modal-content">
                    <div class="report">
                        <p class="content_delete">Alert!</p>
                        <p class="content_delete" id="alert-msg"></p>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
            <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bar.js"></script>
            <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly"
                async></script>
            <script>

                $(document).ready(function () {
                    $('.shelfedit').on('input', function () {
                        const value = $(this).val();
                        if (value.length > 30) {
                            $(this).val(value.slice(0, 30));
                        }
                    });
                    $('body').on('click', '.report-bar', function (e) {

                        var modal = document.getElementById("reportModal");
                        modal.style.display = "block";

                    });
                    $('body').on('click', '.block-profile', function (e) {

                        var modal = document.getElementById("blockuserModal");
                        modal.style.display = "block";

                    });
                    $('body').on('click', '.block-cancel', function (e) {

                        var modal = document.getElementById("blockuserModal");
                        modal.style.display = "none";

                    });
                    $('body').on('click', '.close', function (e) {

                        var modal = document.getElementById("reportModal");
                        modal.style.display = "none";

                    });
                    $('body').on('click', '.block-user-profile,.blocked-profile', function (e) {


                        var blockuserid = $(".block-profile").attr('data-blockuserid');
                        var curuserid = $(".block-profile").attr('data-curuserid');
                        var search_data = { "user_id": curuserid, "block_user_id": blockuserid, "type": 'block' };
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
                            url: '/wp-json/users/v2/userblock',
                            data: JSON.stringify(search_data),
                        }).done(function (data) {
                            var modal = document.getElementById("blockuserModal");
                            modal.style.display = "none";
                            $(".block-profile").html('Un Block');
                            window.location.href = "/blocked-users";
                            $(".block-profile").addClass('blocked-profile');
                            $(".block-profile").removeClass('block-profile');
                        });
                    });
                    $('body').on('click', '.report_post', function (e) {

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
                            success: function (data) {
                                var modal = document.getElementById("reportModal");
                                modal.style.display = "none";
                                if (data.message == 'Profile reported successfully.') {
                                    $('#common_alert').modal('show');
                                    var modal1 = document.getElementById("common_alert");
                                    var alertMsg = document.getElementById("alert-msg");
                                    alertMsg.innerText = data.message;
                                    setTimeout(function () {
                                        modal1.style.display = "none";
                                        location.reload();
                                    }, 3000);
                                }
                            }
                        });
                    });

                });
                jQuery(document).ready(function ($) {
                    $('.check').on('change', function () {
                        // Get the checked status of the checkbox
                        let unsubscribeFlag = $(this).is(':checked') ? 0 : 1;

                        // Send the data using AJAX
                        $.ajax({
                            url: '<?php echo get_site_url(); ?>/wp-json/timeline/v2/unsubscribe',
                            method: 'POST',
                            data: JSON.stringify({ "unsubscribe_flag": unsubscribeFlag }),
                            contentType: 'application/json; charset=utf-8',
                            dataType: 'json',
                            success: function (response) {
                                // Handle success (optional)
                                console.log('Success:', response);
                            },
                            error: function (xhr, status, error) {
                                // Handle error (optional)
                                console.log('Error:', xhr.responseText);
                            }
                        });
                    });
                });
            </script>

            <script id="rendered-js">
                $(".bar-value").html(<?php echo $bar_value; ?>);
            </script>