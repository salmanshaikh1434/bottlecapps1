<?php

/**
 * Template Name: SIPN Profile
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 * 
 * @package bbPress
 * @subpackage Theme
 */
 get_header(); 
global $wpdb;
if (!is_user_logged_in()) {
    wp_redirect("/");
    exit;
}
$cur_user = wp_get_current_user();
$user_details = get_user_meta($cur_user->data->ID);
$curemail = $cur_user->data->user_email;
$unsubscribe = $cur_user->data->unsubscribe;
$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');

$bar_res = web_get_my_bar();

$email = $cur_user->data->user_email;
$blink = $bar_res['bar_link'];
?>
<style>
    .logo.logo-left.logout .profile_name{ font-family: "Nunito", sans-serif !important;}
    a > span.profile_name{font-family: "Nunito", sans-serif !important;}
</style>
<article class="col-md-10">
    <div class="wrapper-top">
        <div class="wrapper-bottom">
            <div class="container">
                <div class="col-md-7 newprofile">
                    <div class="row newprofile-block">
                        <div class="col-md-4">
                            <div class="newprofile-img">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/profile1.png">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <ul class="newprofile-info">
                                <li class="p">
                                    <p><?php if (strpos($cur_user->data->display_name, 'user-') !== false) {
                                            echo '';
                                        } else {
                                            echo $cur_user->data->display_name;
                                        } ?></p>
                                </li>
                                <li class="btn">
                                    <a href="/profile-edit"><button type="button">Edit Profile</button></a>
                                </li>
                                <li class="btn">
                                    <div>
                                        <a href="/rewards"><button type="button">Rewards</button></a>
                                    </div>
                                </li>
                                <!--
                                <li class="img"><img
                                        src="<?php // echo get_stylesheet_directory_uri(); 
                                                ?>/assets/images/icon-settings.png">
                                </li>
-->
                            </ul>
                            <p>Bio....... Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="chat-detail">
                            <div class="my-bar">
                                <?php
                                $cnt = 1;
                                $total_products_cnt = 0;
                                $bar_value = 0;

                                foreach ($bar_res['shelves'] as $shelf) {
                                ?>
                                    <!--   <h2><?php //echo $shelf['shelf_name'];
                                                ?></h2> -->
                                    <input type="text" class="shelfedit" ssid="<?php echo $shelf['shelf_id']; ?>" id="shelfedit<?php echo $cnt; ?>" style="text-align: center;color: #bda766; font-size: 17px; font-family: 'montserratbold';margin: 0 auto 25px auto; padding-top: 15px;border: transparent;" value="<?php echo $shelf['shelf_name']; ?>" readonly="readonly">
                                    <!-- by sumeeth -->
                                    <!-- Flickity HTML init -->
                                    <section class="slider">
                                        <ul slid="<?php echo $shelf['shelf_id']; ?>" brid="<?php echo $bar_res['bar_id']; ?>" class="slides ui-sortable cs-hidden" id="autoWidth<?php echo $cnt; ?>">
                                            <?php
                                            $p = 1;
                                            foreach ($shelf['products'] as $product) {

                                                if (!$product['product_image'])
                                                    $product['product_image'] = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
                                                if ($product['product_id'] != '') {

                                            ?>
                                                    <li pid="<?php echo $product['product_id']; ?>" class="slide slide<?php echo $p; ?> ui-sortable-handle item-a">
                                                        <div class="box">

                                                            <div class="slide-img">

                                                                <img alt="<?php echo $product['product_name']; ?>" src="<?php echo $product['product_image']; ?>">

                                                            </div>
                                                            <div class="slide-title"><?php echo $product['product_name']; ?>
                                                            </div>
                                                            <div class="delete-product" dpid="<?php echo $product['product_id']; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-delete-product.png">
                                                            </div>
                                                        </div>

                                                    </li>

                                            <?php }
                                                $p++;
                                                $total_products_cnt++;
                                                $price = (float)get_post_meta($product['product_id'], '_price', true);
                                                $bar_value += $price;
                                            } ?>

                                            <?php if (count($shelf['products']) >= 3) { ?>
                                                <li class="slide slide<?php echo $p; ?> ui-sortable-handle item-a">
                                                    <a href="/?s=&si=<?php echo $shelf['shelf_id']; ?>&w=<?php echo $p; ?>">
                                                        <div class="box">
                                                            <div class="slide-img">
                                                                <img alt="" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/img-dummy-lightplus.png">
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="slide-title">...</div>
                                                    <div class="delete-product"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-delete-product.png">
                                                    </div>
                                                </li>
                                                <?php } else {
                                                for ($k = count($shelf['products']) + 1; $k <= 3; $k++) { ?>
                                                    <li class="slide slide<?php echo $k; ?> ui-sortable-handle item-a">
                                                        <a href="/?s=&si=<?php echo $shelf['shelf_id']; ?>&w=<?php echo $k; ?>">
                                                            <div class="box">
                                                                <div class="slide-img">
                                                                    <img alt="" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/img-dummy-lightplus.png">
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="slide-title">...</div>

                                                    </li>
                                            <?php }
                                            } ?>

                                        </ul>

                                    </section>
                                <?php
                                    $cnt++;
                                }
                                ?>
                            </div>
                        </div>
                        <!-- drag drop HTML init -->
                        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-ui.min.js">
                        </script>
                        <script id="rendered-js">
                            $(".bar-value").html(<?php echo $bar_value; ?>);
                            <?php if ($profile_id == $current_user->data->ID) { ?>
                                $(document).on('mouseleave', '.slide-img', function() {
                                    $('.slides').on('mouseover mouseenter mouseleave mouseup mousedown',
                                        function() {
                                            return false
                                        });
                                });
                                $(".slides").sortable({
                                    placeholder: 'slide-placeholder',
                                    axis: "x",
                                    revert: 150,
                                    start: function(e, ui) {

                                        placeholderHeight = ui.item.outerHeight();
                                        ui.placeholder.height(placeholderHeight + 15);
                                        $('<div class="slide-placeholder-animator" data-height="' +
                                            placeholderHeight +
                                            '"></div>').insertAfter(ui.placeholder);

                                    },
                                    change: function(event, ui) {

                                        ui.placeholder.stop().height(0).animate({
                                                height: ui.item.outerHeight() + 15
                                            },
                                            300);

                                        placeholderAnimatorHeight = parseInt($(".slide-placeholder-animator")
                                            .attr(
                                                "data-height"));

                                        $(".slide-placeholder-animator").stop().height(
                                            placeholderAnimatorHeight + 15).animate({
                                                height: 0
                                            },
                                            300,
                                            function() {
                                                $(this).remove();
                                                placeholderHeight = ui.item.outerHeight();
                                                $('<div class="slide-placeholder-animator" data-height="' +
                                                    placeholderHeight + '"></div>').insertAfter(ui
                                                    .placeholder);
                                            });

                                    },
                                    stop: function(e, ui) {
                                        $(".slide-placeholder-animator").remove();
                                        var products_order = [];
                                        var shelf_id = $(this).attr('slid');
                                        var bar_id = $(this).attr('brid');
                                        var cnt = 1;
                                        $(this).find('li.slide').not(".clone").each(function() {
                                            if ($(this).attr('pid')) {
                                                //console.log($(this).attr('pid'));
                                                products_order.push($(this).attr('pid'));
                                                cnt++;
                                                //shelf_id = $(this).attr('slid');
                                            }
                                        });

                                        //console.log(products_order);
                                        //console.log(shelf_id);

                                        $.ajax({
                                            type: 'POST',
                                            dataType: 'json',
                                            url: site_script_object.ajaxurl,
                                            data: {
                                                'action': 'ajaxproductsreorder', //calls wp_ajax_nopriv_ajaxlogin
                                                'products_order': products_order,
                                                'shelf_id': shelf_id,
                                                'bar_id': bar_id,
                                                'nonce': site_script_object.nonce,
                                            },
                                            success: function(data) {

                                            }
                                        });

                                        //e.preventDefault();
                                        //e.stopPropagation();
                                    }
                                });
                            <?php } ?>
                        </script>
                    </div>
                </div>
                <div class="col-md-5">
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
                                    <h5 class="view-name"><?php if (strpos($cur_user->data->display_name, 'user-') !== false) {
                                                                echo '';
                                                            } else {
                                                                echo $cur_user->data->display_name;
                                                            } ?></h5>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <h4>Email Address:</h4>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="view-name"><?php echo $cur_user->data->user_email; ?></h5>
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
                                    <h4>Apt/Suite/Floor:</h4>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="view-apt"><?php echo $user_details['aptsuitefloor'][0]; ?></h5>
                                </div>
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
                                <div class="col-md-6" style="clear-left">
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
                                <div class="col-md-6" style="clear-left">
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
                                    <input type="text" pattern="[A-Za-z]{1,32}" placeholder="Name*" name="name" required value="<?php if (strpos($cur_user->data->display_name, 'user-') !== false) {
                                                                                                                                    echo '';
                                                                                                                                } else {
                                                                                                                                    echo $cur_user->data->display_name;
                                                                                                                                } ?>" disabled="disabled">
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
                </div>

            </div>
            <!-- for edit modal report -->
            <div id="reportModal" class="modal">
                <div class="modal-content">
                    <header>
                        <h2>Profile Delete Confirmation</h2>
                        <span class="close">×</span>
                    </header>
                    <div class="report">
                        <p><strong>Please enter the password to proceed!</strong></p>
                        <input type="password" id="cpass" placeholder="Enter your password" required="required" />

                        <ul>
                            <li><a class="report_post" href="javascript:void(0);" rep="0">Yes<span><i class="fa fa-chevron-right"></i></span></a>

                                <a class="report_post" href="javascript:void(0);" rep="1">No<span><i class="fa fa-chevron-right"></i></span></a>
                            </li>



                        </ul>

                    </div>
                </div>
            </div>

            <div class="modal modal-emailverification fade in" id="openpopup1" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-emailverification modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <i class="fa fa-info-circle fa-info-circle-custom" aria-hidden="true"></i>
                        </div>
                        <div class="modal-body">
                            <div class="email-verification-text">Email not verified</div>
                            <div class="email-verification-content">We sent an email to you please verify your email to
                                continue</div>
                            <div class="resendemail-main"><a href="javascript:void(0);" class="resendemail" data-id="<?php echo $curemail; ?>" id="resendemail">Resend verification mail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/location.js"></script>
            <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly" async></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function() {

                    $('body').on('click', '#emailverifiedprofile', function() {
                        alert('Your email is verified');
                    });

                    $('body').on('click', '.report-tl-forum', function(e) {

                        var modal = document.getElementById("reportModal");
                        modal.style.display = "block";

                    });

                    $('body').on('click', '.close', function(e) {

                        var modal = document.getElementById("reportModal");
                        modal.style.display = "none";

                    });

                    $('body').on('click', '.report_post', function(e) {
                        var reason = $(this).attr('rep');
                        if (reason == '1') {
                            var modal = document.getElementById("reportModal");
                            modal.style.display = "none";
                            location.reload();
                        } else {
                            var cpass = $('#cpass').val();
                            if (cpass == '') {
                                alert('Enter password');
                            } else {

                                $.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: site_script_object.ajaxurl,
                                    data: {
                                        'action': 'ajaxdelprofile', //calls wp_ajax_nopriv_ajaxlogin
                                        'reason': reason,
                                        'cpass': cpass,
                                        'nonce': site_script_object.nonce,
                                    },
                                    success: function(data) {
                                        //alert(data.message);
                                        //console.log(data.status);
                                        if (data.status == 0) {
                                            //	$('#cpass').val('');
                                            alert('Wrong password.');

                                        } else {
                                            alert('Profile deleted successfully');
                                            location.reload();
                                        }
                                        // var modal = document.getElementById("reportModal");
                                        // modal.style.display = "none";
                                        //if(data.message=='Forum is reported successfully.'){
                                        //alert('Thanks For Reporting');
                                        //}
                                    }
                                });

                            }
                        }
                    });


                    $('body').on('click', '.check', function() {
                        var checkStatus = this.checked ? 'ON' : 'OFF';
                        if (checkStatus == 'ON') {
                            $('#checkval').val('0');
                            //$('.checkbox').text('Unsubscribe');
                        } else {
                            $('#checkval').val('1');
                            //$('.subscribeunsubscribe').text('Subscribe');
                        }
                        $sval = $('#checkval').val();
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: site_script_object.ajaxurl,
                            data: {
                                'action': 'ajaxsubscribeunsubscribe', //calls 
                                'sval': $sval,
                                'nonce': site_script_object.nonce,
                            },
                            success: function(data) {
                                //alert(data);
                                //console.log(data.status);
                                if (data == '0') {
                                    alert('Profile updated successfully');

                                    // $(".op").append(
                                    //            '<span class="flash-message">' + data.message + "</span>"
                                    //          );
                                } else {

                                }
                                //$(".op").show();
                                // var modal = document.getElementById("reportModal");
                                // modal.style.display = "none";
                                //if(data.message=='Forum is reported successfully.'){
                                //alert('Thanks For Reporting');
                                //}
                            }
                        });



                    });


                });
            </script>
            <?php sipn_footer(); ?>