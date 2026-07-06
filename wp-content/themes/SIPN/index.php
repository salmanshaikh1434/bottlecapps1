<?php

/**
 * Template Name: SIPN Sipn Bourbon - Home is where Bourbon is
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php get_header(); ?>
<?php
global $current_user;
wp_get_current_user();
if (is_user_logged_in()) {
    $cur_user_id = get_current_user_id();
    $current_user_details = get_user_by('id', $cur_user_id);
    $curemail = $current_user_details->user_email;
    $current_user_meta = get_user_meta($cur_user_id);
    $cur_user_avatar = wp_get_attachment_image_url($current_user_meta['wp_user_avatar'][0], 'thumbnail');

    if (!$cur_user_avatar) {
        $cur_user_avatar = get_avatar_url($cur_user_id);
    }
    if($current_user_details->validate_email == 0){
        birthday_rewards_web();
        update_rewards();
    }
}
?>
<style type="text/css">
    .mcarousel-inner .active.left {
        left: -33%;
    }

    .mcarousel-inner .active.right {
        left: 33%;
    }

    .mcarousel-inner .next {
        left: 33%
    }

    .mcarousel-inner .prev {
        left: -33%
    }

    .mcarousel-control.left {
        background-image: none;
    }

    .mcarousel-control.right {
        background-image: none;
    }

    .mcarousel-inner .item {
        background: white;
    }

    .mySlides {
        display: none;
        padding: 0px;
        text-align: center;
    }

    .mySlides a {
        display: block;
    }

    .mySlides1 {
        display: none;
        padding: 0px;
        text-align: center;
    }

    .mySlides1 a {
        display: block;
    }

    /* Next & previous buttons */
    .prev,
    .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        margin-top: -30px;
        padding: 16px;
        color: #888;
        font-weight: bold;
        font-size: 20px;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        position: absolute;
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* The dot/bullet/indicator container */
    .dot-container {
        text-align: center;
        padding: 5px 0;
        background: transparent !important;
        width: 100%;
        float: left;
    }

    .dot-container1 {
        text-align: center;
        padding: 5px 0;
        background: #2d2d2d !important;
        width: 100%;
        float: left;
    }

    /* The dots/bullets/indicators */
    .dot,
    .dot1 {
        cursor: pointer;
        height: 10px;
        width: 10px;
        margin: 0 2px;
        background-color: #2d2d2d;
        border: solid 1px #b7a968;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    /* Add a background color to the active dot/circle */
    .dot-container span.active,
    span .dot:hover,
    span .dot:active,
    span .dot1:active,
    span .dot1:hover {
        background-color: #b7a968;
        border-color: #b7a968;
    }

    /* Add an italic font style to all quotes */
    .hide {
        display: none;
    }

    .carousel-inner>.item {
        height: auto;
        margin: inherit;
    }
</style>

<!-- Async script executes immediately and must be after any SDOM elements used in callback. -->
<!-- 
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/buy-now-location2.js"></script>
 -->
<div id="popup" class="install-app" style="display:none;">
    <div>
        <div id="popup-close">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-app.png" alt="logo" width="80"
                height="80">
        </div>

        <p>For Better Experience, <br>Download The <b>SIPN BOURBON</b> App.</p>


        <a href="javascript:void(0);" class="closeinstall"> Not Now! </a>
        <a href="http://onelink.to/sipnbourbon" class="addinstall"> Switch to App. </a>

    </div>
</div>
<h1 style="display:none;">SIPN</h1>
<h2 style="display:none;">Buy bourbon online</h2>
<section class="page-sec col-xs-12 col-sm-10 home-page">
    <div class="main-section row">
        <div class="main-content col-sm-7">

            <div class="clearfix"></div>
            <?php
            $product_visibility_term_ids1 = wc_get_product_visibility_term_ids();
            $args1 = [
                'post_type' => 'product',
                'post_status' => 'publish',
                'order' => 'DESC',
                'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'term_taxonomy_id',
                        'terms' => array($product_visibility_term_ids1['featured']),
                    ),
                    array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'term_taxonomy_id',
                        'terms' => array($product_visibility_term_ids1['exclude-from-catalog']),
                        'operator' => 'NOT IN',
                    ),
                ),
                'posts_per_page' => -1
            ];



            $products2 = get_posts($args1);
            $products = array_chunk($products2, 3);
            ?>

            <div class="mtrending">
                <div class="trending">
                    <h2>Featured Products</h2>
                    <span class="view-all">
                        <a href="/featured-products">View all</a>
                    </span>
                </div>

                <div id="myCarousel" class="mcarousel slide">

                    <div class="mcarousel-inner">
                        <?php
                        if (count($products) > 0) {
                            $cnt = 0;
                            foreach ($products as $key => $product) {

                                ?>
                                <div class="item <?php if ($key == 0) {
                                    echo "active";
                                } ?>">
                                    <?php foreach ($product as $key => $product1) {
                                        $the_product = wc_get_product($product1->ID);
                                        $prod_url = get_the_post_thumbnail_url($product1->ID, 'full'); ?>

                                        <div class="col-xs-4  col-xl-4 grow" style="padding-left: 0;padding-right: 8px;">

                                            <div class="tcb-product-item">
                                                <div class="tcb-product-info">
                                                    <div class="tcb-product-title">
                                                        <h4><a href="<?php echo get_permalink($product1->ID); ?>"
                                                                title="<?php echo $product1->post_title; ?>"><?php echo $product1->post_title; ?></a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tcb-product-photo">
                                                <a href="<?php echo get_permalink($product1->ID); ?>"><img loading="lazy"
                                                        src="<?php echo $prod_url; ?>" alt="<?php echo $product1->post_title; ?>"
                                                        width="100" height="100" class="img-responsive"></a>

                                            </div>

                                        </div>
                                    <?php } ?>

                                </div>

                                <?php $cnt++;
                            }
                        } else {
                            echo "No products found.";
                        } ?>

                    </div>

                    <!-- Controls -->
                    <a class="left mcarousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right mcarousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

            </div>

            <!-- Sample Slider -->

            <div class="add-your-feeds">
                <!-- end of modal -->


                <div id="editModal" class="modal edit_newpost">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Edit Post</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="">

                            <div class="user-chat">
                                <div class="msg-opt-in">
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar; ?>"
                                                alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">
                                        <div class="write-block">
                                            <div class="loc-search">
                                                <div class="col-md-6">
                                                    <input type="button" class="" value="Tag Product" id="tagproduct1"
                                                        disabled="true">
                                                    <input class="form-control headerpostsearch1" name="s" required
                                                        type="text" for="search" placeholder="Search Bourbons"
                                                        id="headerpostsearch1" autocomplete="off"
                                                        style="color: #000; "><span class="closeproduct">×</span>
                                                </div>
                                                <div class="headerpost-result-sec" style="display:none;"></div>
                                                <input type="hidden" id="fpid1" value="" />
                                                <div class="col-md-6">
                                                    <input type="button" class="tageditlocpost" value="Tag Location"
                                                        id="tageditlocpost" disabled="true"><span class="closeloc"
                                                        style="position: absolute;right: 30px;color: #baa86d;top: 82px;font-size: 25px;cursor: pointer;display: none;">×</span>
                                                    <input id="search_input"
                                                        class="form-control pac-target-input bn_address2 tagloceditpostsearch"
                                                        placeholder="Enter Address" type="text" autocomplete="off">
                                                    <input type="hidden" id="lat">
                                                    <input type="hidden" id="lng">
                                                    <input type="hidden" id="administrative_area_level_1" value="">
                                                </div>
                                            </div>

                                            <!-- <input type="text" placeholder="Write a public comment..."> -->
                                            <textarea placeholder="Edit your post" class="text-area comment"
                                                id="comment_01"></textarea>
                                            <!-- <div id="addeimage" class="viewonly1 editaddprev" style="display:none;"></div> -->

                                        </div>
                                        <div class="editimageholder">
                                            <div class="editimg-reupload">
                                                <ul id="addeimage" class="viewonly1 editaddprev" style="display:none;">
                                                </ul>
                                                <span class=""
                                                    style="display:none;">&times;</span><!-- //cancelclose edicloseimage -->
                                            </div>
                                            <div class="imageholder">
                                                <div class="view-gallery">
                                                    <ul class="viewonly edit_img_posts" id="edit_img_posts"></ul>
                                                    <!--  <span class="fa fa-times deletepimages"></span> -->
                                                    <input type="hidden" name="delete_image" id="delete_image"
                                                        value="0">

                                                </div>

                                                <div class="emojis">
                                                    <div class="inputWrapper1">
                                                        <!-- added by sumeeth -->
                                                        <input accept="image/*" onchange="readURL2(this.files);"
                                                            class="fileInput commentInput" rid="0" name="pImage"
                                                            id="profile-pic1" type="file" multiple="">
                                                        <label for="profile-pic1"><img
                                                                src="/wp-content/themes/SIPN/assets/images/icon-pin.png"></span></label>
                                                        <div id="mulimg1"></div>
                                                        <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="fa fa-camera"></span> -->
                                                        <input type="hidden" class="commentImg" id="comment_img_0"
                                                            value="">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-postnow">
                                    <button type="button" class="post colorbttn submitEditWrapper post_spinner" rid="0">POST
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end of modal -->






                <div id="replyModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Reply Post</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="">

                            <div class="user-chat">
                                <div class="msg-opt-in">
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar; ?>"
                                                alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">
                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                        <textarea placeholder="Add your post" class="text-area comment "
                                            id="comment_0"></textarea>
                                        <div class="emojis">
                                            <div class="inputWrapper1">
                                                <input accept="image/*" class="fileInput commentInput" rid="0"
                                                    name="pImage" type="file"><img
                                                    src="/wp-content/themes/SIPN/assets/images/icon-pin.png">
                                                <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"><a href="javascript:void(0);" class="post submitReplyWrapper"
                                        rid="0" type="button">Post</a></div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end of modal -->


                <div id="repliesModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2 class="ncommnents">Comments</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="replies-body">
                            <div class="result-replies"></div>
                            <div class="more-user-info">
                                <!-- <input type="text" placeholder="Write a public comment..."> -->
                                <input type="text" placeholder="Post your Comment"
                                    class="text-area comment replsum comments_input" id="comment_0">
                                <button type="button" class="post submitRepliesWrapper post_cmnt_new" rid="0" value=""
                                    disabled="disabled"><img
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>

                                <!--
                                                        <div class="emojis repliesholder">
                                                            <div class="inputWrapper1">
                                                                <input accept="image/*" onchange="readURL1(this);"  class="fileInput commentInput" rid="0" name="pImage" type="file">
                                                                <p id="editimage" style="display:none;"> <img id="blah1" src="#" alt="your image" style="display:none;" /></p>
                                                                <span class="commentcloseimage" style="display:none;">&times;</span>-->
                                <!--
                                                                 <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="fa fa-paperclip"></span>
                                                                <input type="hidden" class="commentImg replsum" id="comment_img_0" value="">
                                                            </div>

                                                        </div>-->
                            </div>
                            <!-- <div class="col-md-8 btn-postnow">
                                
                            </div> -->
                        </div>
                    </div>

                </div> <!-- end of modal -->

                <div id="reportModal" class="modal" style="z-index: 9999;">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Report</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="report">
                            <p><strong>Why are you reporting?</strong></p>
                            <p>Your report is confidential, this will keep the SIPN community cleaner for all the users.
                            </p>
                            <ul>
                                <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i
                                                class="fa fa-chevron-right"></i></span></a></li>
                                <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate
                                        Speech<span><i class="fa fa-chevron-right"></i></span></a></li>
                                <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's
                                        inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                                <li><a class="report_post" href="javascript:void(0);"
                                        rep="Prohibited Content">Prohibited Content<span><i
                                                class="fa fa-chevron-right"></i></span></a></li>

                            </ul>

                        </div>
                    </div>

                </div>

                <div id="editModal1" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Edit Comment</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="">

                            <div class="user-chat">
                                <div class="msg-opt-in">
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar; ?>"
                                                alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">
                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                        <input type="text" placeholder="Post your Comment"
                                            class="text-area comment replsum comments_input" id="comment_0">
                                        <button type="button" class="post submitEditWrapper1 post_cmnt_new" rid="0"
                                            value="" disabled="disabled"><img
                                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                                    </div>
                                </div>
                                <div class="col-md-8">

                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end of modal -->

                <div id="repliesModal1" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2 class="headd"> Comments</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="replies-body">
                            <div class="result-replies"></div>
                            <div class="more-user-info">
                                <!-- 
                                <textarea placeholder="Post your Comment" class="text-area comment" id="comment_0"></textarea>
                                 <div class="emojis">
                                    <div class="inputWrapper1">
                                        
                                        <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                    </div>

                                </div> -->
                                <input type="text" class="comments_input comment" placeholder="Post your Comment"
                                    id="comment_0" />
                                <button class="submitRepliesWrapper post_cmnt_new" rid="0"><img
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                            </div>
                            <!-- <div class="col-md-8">
                                <input type="button" class="post submitRepliesWrapper colorbttn" rid="0" value="Post">
                            </div> -->
                        </div>
                    </div>

                </div> <!-- end of modal -->
                <div id="sponsoredModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2 class="spcommentscount">Sponsored Comments</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="replies-body">
                            <div class="result-replies"></div>
                            <div class="more-user-info">
                                <!-- <input type="text" placeholder="Write a public comment..."> -->
                                <div class="post-comments sponsored-comments-new">
                                    <input type="text" class="comments_input comment pcomment_"
                                        placeholder="Post your Comment" class="pcomment" id="comment" />
                                    <button class="submitsponsRepliesWrapper post_cmnt_new"><img
                                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                                </div>

                            </div>

                        </div>
                    </div>

                </div> <!-- end of modal -->
                <div id="editsponsModal1" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Edit Comment</h2>
                            <span class="close">&times;</span>
                        </header>
                        <div class="">

                            <div class="user-chat">
                                <div class="msg-opt-in">
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar; ?>"
                                                alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">

                                        <input type="text" class="comments_input comment"
                                            placeholder="Edit your comment" id="comment_0" />
                                        <button class="submitsponsEditWrapper1  post_cmnt_new" rid="0"><img
                                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>


                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                        <!-- <textarea placeholder="Edit your comment" class="text-area comment" id="comment_0"></textarea>
                                         --><!-- <div class="emojis">
                                            <div class="inputWrapper1">
                                               
                                                <input accept="image/*" onchange="loadFile1spons(event)" class="fileInput commentInput" rid="0" name="pImage" id="profile-pic2" type="file">
                                                <label for="profile-pic2"><img src="/wp-content/themes/SIPN/assets/images/icon-pin.png"></label>
                                                <p id="editpostoutputimage123" style="display:none;"><img id="output123" src="" height="100" width="100" style="display:none;" /></p>
                                                <span class="edicloseimage1" style="display:none;">&times;</span>
                                                
                                                <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                            </div>

                                        </div> -->
                                    </div>
                                </div>
                                <!-- <div class="col-md-12">

                                    <input type="button" class="post submitsponsEditWrapper1" rid="0" value="Post">
                                </div> -->
                            </div>
                        </div>
                    </div>

                </div> <!-- end of modal -->

                <div id="repliesModal2" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2 id="headd"> Comments</h2>
                            <span class="close subclose">&times;</span>
                        </header>
                        <div class="replies-body">
                            <div class="result-replies"></div>
                            <div class="more-user-info">
                                <!-- <input type="text" placeholder="Write a public comment..."> -->
                                <!-- <textarea placeholder="Post your Comment" class="text-area comment" id="comment_0"></textarea>
                                <div class="emojis">
                                    <div class="inputWrapper1">

                                    </div>

                                </div> -->
                                <input type="text" class="comments_input comment" placeholder="Post your Comment"
                                    id="comment_0" />
                                <button class="submitsponsRepliesWrapper post_cmnt_new" rid="0"><img
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                            </div>
                            <!-- <div class="col-md-12">

                                <input type="button" class="post submitsponsRepliesWrapper" rid="0" value="Post">

                            </div> -->
                        </div>
                    </div>

                </div> <!-- end of modal -->

                <div id="editsponsModal2" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <header>
                            <h2>Edit Comment</h2>
                            <span class="close edscmnt">&times;</span>
                        </header>
                        <div class="">

                            <div class="user-chat">
                                <div class="msg-opt-in">
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar; ?>"
                                                alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">
                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                        <input type="text" class="comments_input comment"
                                            placeholder="Edit your comment" id="comment_0" />
                                        <button class="submitsponsEditWrapper1  post_cmnt_new" rid="0"><img
                                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12">

                                    <input type="button" class="post submitsponsEditWrapper1" rid="0" value="Post">
                                </div> -->
                            </div>
                        </div>
                    </div>

                </div> <!-- end of modal -->


            </div>


            <?php
            $timeline_res_sponsored = get_timeline_list('1', '10');
            //   echo "<pre>";print_r($timeline_res_sponsored);exit;
            foreach ($timeline_res_sponsored['sponsored_ads'] as $spons) {
                // If no click-through link is set, fall back to the linked product's page.
                if (empty($spons['link']) && !empty($spons['product_id']) && (int) $spons['product_id'] > 0) {
                    $spons_fallback = get_permalink((int) $spons['product_id']);
                    if ($spons_fallback) {
                        $spons['link'] = $spons_fallback;
                    }
                }
                ?>
                <div class="inner-content" id="sponsmsg-<?php echo $spons['spons_id']; ?>">
                    <div class="user-feed">
                        <div class="user-profile">
                            <div class="dropdown">
                                <img class="threedots" alt="sipnbourbon"
                                    src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/vertical-dots.png"
                                    height="144" width="39">
                                <div class="dropdown-content">
                                    <!--    added by sumeeth -->
                                    <?php if (is_user_logged_in()) {
                                        $lin = "#.";
                                    } else if (is_user_logged_in() && $current_user->data->validate_email == '0') {
                                        $lin = "/login?redirect_to=sponsmsg-" . $spons['spons_id'] . "";
                                    } else {
                                        $lin = "/login";
                                    } ?>
                                    <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>



                                        <a href="javascript:void(0);" class="recordaddclick" data-actiontype="Report"
                                            data-id="<?php echo $spons['spons_id']; ?>" data-from="website" data-toggle="modal"
                                            data-backdrop="static" data-target="#openpopup"><span><i
                                                    class="fa fa-exclamation-circle"></i></span> Report</a>


                                    <?php } else { ?>
                                        <a href="<?php echo $lin; ?>" class="report-tl-post recordaddclick"
                                            data-actiontype="Report" data-id="<?php echo $spons['spons_id']; ?>"
                                            data-from="website" rid="<?php echo $spons['spons_id']; ?>"
                                            post_url="https://sipnbourbon.com/timeline_sponsads/?q=<?php echo $spons['spons_id']; ?>"><span><i
                                                    class="fa fa-exclamation-circle"></i></span>Report</a>
                                    <?php } ?>

                                    <!--    added by sumeeth -->
                                </div>
                            </div>
                            <div class="profile-in">
                                <div class="profile-pic">
                                    <a class="profile-spons-add" href="javascript:void(0);"><img
                                            src="<?php echo $spons['company_logo']; ?>" alt="sponsored_logo" width="60"
                                            height="60"></a>
                                    <!--    added by sumeeth -->
                                    <div class="user-name">
                                        <a href="javascript:void(0);"><?php echo $spons['company_name']; ?></a>
                                        <span class="company_verified"><img alt="company_logo" width="23" height="23"
                                                src="<?php echo $spons['spons_verified']; ?>"></span> 
                                        <?php if ($spons['product_title'] != '') { ?>
                                            <span class="sumss recordaddclick" data-actiontype="ProductLink"
                                                data-id="<?php echo $spons['spons_id']; ?>" data-from="website">
                                                <a
                                                    href="<?php echo $spons['link']; ?>"
                                                    title="<?php echo $spons['product_title']; ?>" target="_blank"><?php echo $spons['product_title']; ?>
                                                       </a></span>
                                            <br>
                                            <small class=""><?php echo $spons['spons_date']; ?>, Sponsored</small>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="user-msg">
                                <?php $spdesc = strip_tags($spons['description']);
                                echo nl2br($spdesc); ?>
                            </div>
                        </div>
                    </div>
                    <div class="upload-image spn_post_div" oncontextmenu="return false;">
                        <div class="sectionss">
                            <div class="slideshow-container1234">

                                <?php if ($spons['is_bar'] == 1) {
                                    if (is_user_logged_in()) {
                                        $spons_link = '/bar/user-' . $current_user->data->ID;
                                    } else {
                                        $spons_link = "/login?redirect_to=bar";
                                    }
                                } else {
                                    $spons_link = $spons['link'];
                                }
                                if (is_user_logged_in() && $current_user->data->validate_email == '0') {
                                    ?>
                                    <a class="recordaddclick" data-actiontype="View" data-id="<?php echo $spons['spons_id']; ?>"
                                        data-from="website" href="<?php echo $spons_link; ?>" target="_blank">
                                        <?php //if(is_user_logged_in()){echo bbp_get_user_profile_url($current_user->data->ID);} else{ echo "/login?redirect_to=bar"; }
                                                ?>
                                        <!--  <?php //echo $spons['link'];
                                                ?> -->
                                        <?php if ($spons['image'] != '') { ?>
                                            <img src="<?php echo $spons['image']; ?>" alt="sponsored_image" width="100%">

                                        <?php } else if ($spons['image'] != '' && $spons['product_image'] != '') { ?>
                                                <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } else if ($spons['product_image'] != '') { ?>
                                                    <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } ?>
                                    </a>

                                <?php } else if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>

                                        <a class="recordaddclick" data-actiontype="View" data-id="<?php echo $spons['spons_id']; ?>"
                                            data-from="website" href="javascript:void(0);" data-toggle="modal"
                                            data-backdrop="static" data-target="#openpopup" target="_blank">
                                            <!--  <?php //echo $spons['link'];
                                                    ?> -->
                                        <?php if ($spons['image'] != '') { ?>
                                                <img src="<?php echo $spons['image']; ?>" alt="sponsored_image" width="100%">

                                        <?php } else if ($spons['image'] != '' && $spons['product_image'] != '') { ?>
                                                    <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } else if ($spons['product_image'] != '') { ?>
                                                        <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } ?>
                                        </a>

                                <?php } else { ?>
                                        <a class="recordaddclick" data-actiontype="View" data-id="<?php echo $spons['spons_id']; ?>"
                                            data-from="website" href="<?php echo $spons_link; ?>" target="_blank">
                                            <!--  <?php //echo $spons['link'];
                                                    ?> -->
                                        <?php if ($spons['image'] != '') { ?>
                                                <img src="<?php echo $spons['image']; ?>" alt="sponsored_image" width="100%">

                                        <?php } else if ($spons['image'] != '' && $spons['product_image'] != '') { ?>
                                                    <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } else if ($spons['product_image'] != '') { ?>
                                                        <img src="<?php echo $spons['product_image']; ?>" alt="sponsored_image" width="100%">
                                        <?php } ?>
                                        </a>

                                <?php } ?>







                            </div>
                        </div>
                    </div>
                    <div class="img-options spon-lcs" id="comment-<?php echo $spons['spons_id']; ?>">
                        <div class="options1 options <?php if ($spons['is_liked'] == '1') { ?>active<?php } ?>">
                            <?php if (is_user_logged_in()) { ?>
                                <a href="javascript:void(0);" class="spons_like_timeline recordaddclick" data-actiontype="Like"
                                    data-id="<?php echo $spons['spons_id']; ?>" data-from="website" id="like"
                                    liked="<?php echo $spons['is_liked']; ?>" rid="<?php echo $spons['spons_id']; ?>"> <span
                                        class="onlysponslike">
                                        <?php if (($spons['likes_count'] != '0')) {
                                            echo $spons['likes_count']; ?></span><?php } else {
                                            echo "</span>";
                                        } ?></a>
                            <?php } else { ?>
                                <a href="/login?redirect_to=sponsmsg-<?php echo $spons['spons_id'] ?>" data-actiontype="Like"
                                    class="recordaddclick" data-id="<?php echo $spons['spons_id']; ?>" data-from="website"
                                    id="like"> <span class="onlysponslike">
                                        <?php if (($spons['likes_count'] != '0')) {
                                            echo $spons['likes_count']; ?></span><?php } else {
                                            echo "</span>";
                                        } ?></a>
                            <?php } ?>
                        </div>
                        <div class="options2 options">
                            <?php if (is_user_logged_in()) {
                                $lin = "javascript:void(0);";
                            } else {
                                $lin = "/login?redirect_to=sponsmsg-" . $spons['spons_id'] . "";
                            } ?>
                            <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>

                                <a href="javascript:void(0);" class="recordaddclick" data-actiontype="Comment" id="comment"
                                    data-id="<?php echo $spons['spons_id']; ?>" data-from="website" data-toggle="modal"
                                    data-backdrop="static" data-target="#openpopup">
                                    <?php echo $spons['total_replies_count']; ?></a>

                            <?php } else { ?>
                                <a href="<?php echo $lin; ?>" data-id="<?php echo $spons['spons_id']; ?>"
                                    data-actiontype="Comment" data-from="website"
                                    class="replies_sponslist rlist_<?php echo $spons['spons_id']; ?> recordaddclick"
                                    rid="<?php echo $spons['spons_id']; ?>" id="comment">
                                    <?php echo $spons['total_replies_count']; ?></a>
                            <?php } ?>
                        </div>
                        <div class="options3 options">
                            <!--    added by sumeeth -->
                            <a href="javascript:void(0);" class="copy-share-link recordaddclick" data-actiontype="Share"
                                data-id="<?php echo $spons['spons_id']; ?>" data-from="website" id="share"
                                link="https://sipnbourbon.com/timeline_sponsads/?q=<?php echo $spons['spons_id']; ?>">
                                &nbsp;</a>
                            <!--  <a href="#." class="copy-share-link" id="share" link="https://sipnbourbon.com/timeline/?q=62958"> Share</a> -->
                        </div>
                        <div class="options4 options">
    <?php
    // Buy Now link is taken dynamically from the ad's own link (wp_sponsored_ads.link).
    $spons_buy_upc = (!empty($spons['product_id']) && (int) $spons['product_id'] > 0)
        ? str_replace('#', '', (string) get_post_meta((int) $spons['product_id'], 'productupc', true))
        : '';
    if ($spons['link'] != '') { ?>
        <a class="buynow post-buynow recordaddclick"
           href="<?php echo $spons['link']; ?>"
           target="_blank"
           data-actiontype="buy_now"
           data-id="<?php echo $spons['spons_id']; ?>"
           data-from="website"
           data-rtype="1"
           data-pupc="<?php echo esc_attr($spons_buy_upc); ?>">
            <button class="search"><?php echo !empty($spons['buynow_text']) ? esc_html($spons['buynow_text']) : 'Buy Now'; ?></button>
        </a>
    <?php } ?>
</div>

                    </div>
                    <?php if (is_user_logged_in() && $current_user_details->validate_email == '0') { ?>
                        <div class="post-comments post-comments-up">
                            <input type="text" placeholder="Post your Comment"
                                class="pcommentindex_<?php echo $spons['spons_id']; ?>"
                                id="commentindex_<?php echo $spons['spons_id']; ?>" />
                            <button class="submitsponsRepliesWrapperindex post_cmnt_new"
                                rid="<?php echo $spons['spons_id']; ?>"><img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                        </div>
                    <?php } ?>

                </div>
            <?php } ?>
            <?php
            //get timeline messages
            
            $timeline_res = get_timeline_list('1', '10');
            //  echo "<pre>";print_r($timeline_res);exit;
            $tmlcount = 1;
            $tmp = array();
            foreach ($timeline_res['replies'] as $reply) {
                ?>
                <div class="inner-content" id="msg-<?php echo $reply['reply_id'] ?>">
                    <div class="user-feed">
                        <div class="user-profile">

                            <div class="dropdown">
                                <!-- <button class="dropbtn">...</button> -->
                                <img class="threedots normal-report" alt="sipnbourbon"
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/vertical-dots.png"
                                    height="144" width="39">
                                <div class="dropdown-content">
                                    <?php if (current_user_can('edit_reply', $reply['reply_id'])) { ?>
                                        <a href="javascript:void(0);" class="edit-tl-post"
                                            rptitle="<?php echo $reply['product_title']; ?>"
                                            rimage="<?php echo $reply['reply_image']; ?>"
                                            rid="<?php echo $reply['reply_id']; ?>" pid="<?php echo $reply['product_id']; ?>"
                                            locid="<?php echo $reply['tagged_location']; ?>"><span><i
                                                    class="far fa-edit bar-edit"></i></span>Edit</a>
                                        <a href="javascript:void(0);" class="delete-tl-post"
                                            rid="<?php echo $reply['reply_id']; ?>"><span><i
                                                    class="fa fa-trash"></i></span>Delete</a>
                                    <?php } ?>
                                    <!--   <a href="#." class="report-tl-post" rid="<?php echo $reply['reply_id']; ?>"post_url="<?php echo $reply['url']; ?>"><span><i class="fa fa-exclamation-circle"></i></span> Report</a> -->
                                    <!--    added by sumeeth -->
                                    <?php if (is_user_logged_in() && !current_user_can('edit_reply', $reply['reply_id'])) { ?>


                                        <?php if (is_user_logged_in() && $current_user_details->validate_email == '1') { ?>



                                            <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static"
                                                data-target="#openpopup"><span><i
                                                        class="fa fa-exclamation-circle"></i></span>Report</a>


                                        <?php } else { ?>
                                            <a href="javascript:void(0);" class="report-tl-post"
                                                rid="<?php echo $reply['reply_id']; ?>"
                                                post_url="<?php echo $reply['url']; ?>"><span><i
                                                        class="fa fa-exclamation-circle"></i></span>Report</a>
                                        <?php } ?>








                                    <?php } else if (!is_user_logged_in()) { ?>

                                        <?php if (is_user_logged_in() && $current_user_details->validate_email == '1') { ?>



                                                <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static"
                                                    data-target="#openpopup"><span><i
                                                            class="fa fa-exclamation-circle"></i></span>Report</a>


                                        <?php } else { ?>
                                                <a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" class="report-tl-post"
                                                    rid="<?php echo $reply['reply_id']; ?>"
                                                    post_url="<?php echo $reply['url']; ?>"><span><i
                                                            class="fa fa-exclamation-circle"></i></span>Report</a>
                                        <?php } ?>



                                    <?php } ?>
                                    <!--    added by sumeeth -->
                                </div>
                            </div>

                            <?php $badge = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/badge-gold.png"; ?>
                            <div class="profile-in">
                                <div class="profile-pic">
                                    <a href="<?php if (is_user_logged_in()) {
                                        echo '/bar/user-' . $reply['author_id'];
                                    } else {
                                        echo "/login";
                                    } ?>"><img loading="lazy" src="<?php echo $reply['avatar']; ?>" alt="profile_pic"
                                            width="60" height="60"></a>
                                    <!--    added by sumeeth -->
                                    <div class="user-name">
                                        <?php if (is_user_logged_in()) { ?>
                                            <a href="<?php echo '/bar/user-' . $reply['author_id']; ?>">
                                                <?php echo $reply['author']; ?></a>
                                        <?php } else { ?>
                                            <a href="/login"><?php echo $reply['author'];
                                        } ?></a>
                                        <?php if(!$badge){ ?>
                                        <span class="company_verified"><img src="<?php echo $badge; ?>" width="23"></span>
                                        <?php } ?>
                                        <br>

                                        <?php
                                        if (!empty($reply['product_id'])) {

    $the_product = wc_get_product($reply['product_id']);

    if ($the_product) { ?>

        <span class="sumss">
            <a href="<?php echo get_permalink($reply['product_id']); ?>"
               title="<?php echo esc_attr($the_product->get_name()); ?>">
               <?php echo esc_html($the_product->get_name()); ?>
            </a>
        </span><br>

<?php
    }
}
?>
                                        <span class="sumloc">
                                            <?php
                                            if ($reply['tagged_location'] != '') {
                                                echo $reply['reply_date'] . ', ' . $reply['tagged_location'];
                                            } else {
                                                echo $reply['reply_date'];
                                            } ?>
                                        </span>
                                    </div>
                                    <!-- <div class="user-name"> <?php //echo $reply['author'];
                                        ?><br><span><?php //echo $reply['reply_date'];
                                            ?></span></div> -->
                                </div>


                            </div>
                            <div class="user-msg post_user-msg">


                                <?php $content = nl2br(strip_tags($reply['reply']));

                                $count = strlen($content);
                                if ($count > 160) {
                                    $showcontent = substr($content, 0, 150);
                                } else {
                                    $showcontent = $content;
                                }
                                echo $showcontent;
                                if ($count > 160) { ?><a class="read-more-show hide" href="#"
                                        id="<?php echo $reply['reply_id'] ?>">&nbsp;&nbsp;Read More</a><span
                                        class="read-more-content"><?php echo substr($content, 150); ?> <a
                                            class="read-more-hide hide" href="#"
                                            more-id="<?php echo $reply['reply_id'] ?>">&nbsp;&nbsp;Read Less</a></span>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="upload-image post_img_new  <?php if (($reply['reply_image'] == '' && $reply['product_image'] != '')) {
                        echo "imgpro";
                    } ?>" <?php if ($reply['product_image'] != '') { ?> oncontextmenu="return false;" <?php } ?>>

                        <?php if ($reply['reply_image'] != '') { ?>
                            <div class="section">
                                <div class="slideshow-container">

                                    <?php if ($reply['reply_image']) {
                                        $a = $reply['reply_image'];
                                        $b = explode(',', $a);
                                        $coui = count($b);
                                        foreach ($b as $key => $value) { ?>

                                            <div class="mySlides">
                                                <a href="javascript:void(0);"><img src="<?php echo $value; ?>" loading="lazy"
                                                        width="100%" alt="post_image"> </a>
                                            </div> <?php } ?>

                                        <div class="next_prev">
                                            <a class="prev" href="javascript:void(0);"
                                                onclick="slide[<?php echo $tmlcount; ?>].plusSlides(-1)" <?php if ($coui == 1) { ?>
                                                    style="display: none;" <?php } ?>>❮</a>
                                            <a class="next" href="javascript:void(0);"
                                                onclick="slide[<?php echo $tmlcount; ?>].plusSlides(1)" <?php if ($coui == 1) { ?>
                                                    style="display: none;" <?php } ?>>❯</a>
                                            <?php $tmp[] = $tmlcount; ?>
                                        </div>

                                        <div class="dot-container" <?php if ($coui == 1) { ?> style="display: none;" <?php } ?>>
                                            <?php if ($reply['reply_image']) {
                                                $a = $reply['reply_image'];
                                                $b = explode(',', $a);
                                                $imgcnt = 1;
                                                foreach ($b as $key => $value) { ?>

                                                    <span class="dot"
                                                        onclick="slide[<?php echo $tmlcount; ?>].currentSlide(<?php echo $imgcnt; ?>)"></span>
                                                    <?php $imgcnt++;
                                                }
                                            } ?>

                                        </div>





                                    <?php } ?>
                                </div>
                            </div> <?php } else if ($reply['reply_image'] != '' && $reply['product_image'] != '') { ?>
                                <a href="javascript:void(0);"><img loading="lazy" src="<?php echo $reply['product_image']; ?>"
                                        width="100%" alt="tagged_product_image"></a>
                        <?php } else if ($reply['product_image'] != '') { ?>
                                    <a href="javascript:void(0);"><img loading="lazy" src="<?php echo $reply['product_image']; ?>"
                                            width="100%" alt="tagged_product_image"></a>
                        <?php } ?>


                    </div>

                    <div class="img-options post-lcs">
                        <div class="options1 options <?php if ($reply['is_liked'] == '1') { ?>active<?php } ?>">
                            <?php if (is_user_logged_in()) { ?>
                                <a href="javascript:void(0);" class="like_timeline" id="like"
                                    liked="<?php echo $reply['is_liked']; ?>" rid="<?php echo $reply['reply_id']; ?>"> <span
                                        class="likecomment"><span class="onlylike"><?php if (($reply['likes'] != '0')) {
                                            echo $reply['likes']; ?></span><?php } else {
                                            echo $reply['is_liked'] . "</span>";
                                        } ?>
                                </a>
                            <?php } else { ?>
                                <a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="like"> <span
                                        class="likecomment"><span class="onlylike"><?php if (($reply['likes'] != '0')) {
                                            echo $reply['likes']; ?></span><?php } else {
                                            echo "</span>";
                                        } ?></a>
                            <?php } ?>
                        </div>
                        <div class="options2 options">
                            <?php if (is_user_logged_in() && $current_user_details->validate_email == '1') { ?>



                                <a href="javascript:void(0);" id="comment" data-toggle="modal" data-backdrop="static"
                                    data-target="#openpopup">
                                    <?php echo $reply['total_replies_count']; ?></a>




                            <?php } else { ?>
                                <?php if (is_user_logged_in()) { ?>
                                    <a href="javascript:void(0)" id="comment" class="replies_list rlist_<?php echo $reply['reply_id']; ?>"
                                        rid="<?php echo $reply['reply_id']; ?>"> <?php echo $reply['total_replies_count']; ?></a>

                                <?php } else { ?>
                                    <a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="comment">
                                        <?php echo $reply['total_replies_count']; ?></a>
                                <?php } ?>

                            <?php } ?>

                        </div>
                        <div class="options3 options">
                            <!--    added by sumeeth -->
                            <a href="javascript:void(0);" class="copy-share-link" id="share"
                                link="<?php echo site_url(); ?>/timeline/?q=<?php echo $reply['reply_id']; ?>"></a>
                            <!--  <a href="#." class="copy-share-link" id="share" link="<?php echo site_url(); ?>/timeline/?q=<?php echo $reply['reply_id']; ?>"> Share</a> -->
                        </div>
                       <?php

$the_product = null;

if (!empty($reply['product_id']) && get_post_type($reply['product_id']) == 'product') {
    $the_product = wc_get_product($reply['product_id']);
}

if ($the_product) {

    $pid = $the_product->get_id();
    $sku = $the_product->get_sku();

    $produpch = get_post_meta($pid, 'productupc', true);
    $produpc = !empty($produpch) ? str_replace('#', '', $produpch) : '';

    // Buy Now URL resolved dynamically from the product's own settings (no hardcoded IDs).
    $sipn_buy = sipn_get_product_buy_now($the_product);

?>
<div>

<?php if (!empty($sipn_buy['url'])) { ?>

    <a href="javascript:void(0);"
       class="buynow post-buynow recordprodevent"
       data-prurl="<?php echo esc_url($sipn_buy['url']); ?>"
       data-actiontype="buy_now"
       data-rtype="<?php echo (int) $sipn_buy['rtype']; ?>"
       data-pupc="<?php echo esc_attr($produpc); ?>">
        <button class="search">Buy Now</button>
    </a>

<?php } ?>

</div>

<?php } ?>
                    </div>
                    <?php if (is_user_logged_in() && $current_user_details->validate_email == '0') { ?>
                        <div class="post-comments post-comments-up">
                            <input type="text" placeholder="Post your Comment"
                                class="pcommentindex_<?php echo $reply['reply_id'] ?>"
                                id="commentindex_<?php echo $reply['reply_id'] ?>" />
                            <button class="submitRepliesWrapperindex post_cmnt_new" rid="<?php echo $reply['reply_id'] ?>"><img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                        </div>
                    <?php } ?>
                </div>

                <?php $tmlcount++;
            } ?>

        </div>
        <input type="hidden" name="totalcountim[]" id="totalcountim" value="<?php echo implode(',', $tmp); ?>">
        <div class="col-sm-5 right-slider">
            <div class="side-content  right-side-bar">
                <div class="inner-right-slider">


                    <?php
                    $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                    $args = [
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
                        'posts_per_page' => -1
                    ];



                    $products = get_posts($args);
                    ?>
                    <div class="trending">
                        <h2>Featured Products</h2>
                        <span class="view-all">
                            <a href="/featured-products">View all</a>
                        </span>
                    </div>
                    <div class="trending-bg-div">
                        <div class="tcb-product-slider">
                            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    <?php
                                    if (count($products) > 0) {
                                        $cnt = 0;
                                        foreach ($products as $product) {
                                            $the_product = wc_get_product($product->ID);
                                            $prod_url = get_the_post_thumbnail_url($product->ID, 'medium');
                                            ?>
                                            <?php if ($cnt != 0 && $cnt % 3 == 0) { ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($cnt == 0 || $cnt % 3 == 0) { ?>
                                        <div class="item <?php if ($cnt == 0) {
                                            echo 'active';
                                        } ?>">
                                            <div class="row" style="margin:0;">
                                            <?php } ?>
                                            <div class="col-xs-4  col-xl-4 grow" style="padding-left: 0;padding-right: 8px;">
                                                <div class="tcb-product-item">
                                                    <div class="tcb-product-info">
                                                        <div class="tcb-product-title">
                                                            <h4><a href="<?php echo get_permalink($product->ID); ?>"
                                                                    class="featured-product-link" data-product-id="<?php echo $product->ID; ?>"
                                                                    title="<?php echo $product->post_title; ?>"><?php echo $product->post_title; ?></a>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tcb-product-photo">
                                                    <a href="<?php echo get_permalink($product->ID); ?>"
                                                        class="featured-product-link" data-product-id="<?php echo $product->ID; ?>"><img loading="lazy"
                                                            src="<?php echo $prod_url; ?>"
                                                            alt="<?php echo $product->post_title; ?>" width="100" height="100"
                                                            class="img-responsive"></a>
                                                    <!-- added by sumeeth -->
                                                    <!--  <div class="price">$<?php //echo $the_product->price;
                                                            ?></div>
                                                        <div class="rating"><ul>
                                                        <?php //for($i=1; $i<=round($the_product->average_rating);$i++){
                                                                ?>
                                                            <li><img src="<?php //echo get_stylesheet_directory_uri();
                                                                    ?>/assets/images/rating-after.png"></li>
                                                            <?php //}
                                                                    ?>
                                                            <?php //for($j=1; $j<=5-round($the_product->average_rating);$j++){
                                                                    ?>
                                                            <li><img src="<?php //echo get_stylesheet_directory_uri();
                                                                    ?>/assets/images/rating-before.png"></li>
                                                            <? php // }
                                                                        ?>
                                                            </ul>
                                                        </div> -->
                                                    <!-- added by sumeeth -->
                                                </div>

                                            </div>

                                            <?php $cnt++;
                                        }
                                    } else {
                                        echo "No products found.";
                                    } ?>
                                </div>
                            </div>


                        </div>
                    </div>


                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                    <script>
                        // Track featured-product clicks (web). Non-blocking so navigation is unaffected.
                        (function () {
                            var endpoint = '<?php echo esc_url_raw(rest_url('products/v2/featured-click')); ?>';
                            document.addEventListener('click', function (e) {
                                var link = e.target.closest ? e.target.closest('.featured-product-link') : null;
                                if (!link) return;
                                var productId = link.getAttribute('data-product-id');
                                if (!productId) return;
                                var payload = JSON.stringify({ product_id: parseInt(productId, 10), source: 'web' });
                                try {
                                    if (navigator.sendBeacon) {
                                        navigator.sendBeacon(endpoint, new Blob([payload], { type: 'application/json' }));
                                    } else {
                                        fetch(endpoint, {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json' },
                                            body: payload,
                                            keepalive: true
                                        });
                                    }
                                } catch (err) { /* tracking must never block navigation */ }
                            }, true);
                        })();
                    </script>

                    <script>
                        // Track featured-product VIEWS (impressions). A product is recorded the first
                        // time its carousel slide becomes visible. Deduped per page load.
                        (function () {
                            var endpoint = '<?php echo esc_url_raw(rest_url('products/v2/featured-view')); ?>';
                            var seen = {};

                            function record(id) {
                                id = parseInt(id, 10);
                                if (!id || seen[id]) return;
                                seen[id] = true;
                                var payload = JSON.stringify({ product_id: id, source: 'web' });
                                try {
                                    if (navigator.sendBeacon) {
                                        navigator.sendBeacon(endpoint, new Blob([payload], { type: 'application/json' }));
                                    } else {
                                        fetch(endpoint, {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json' },
                                            body: payload,
                                            keepalive: true
                                        });
                                    }
                                } catch (err) { /* tracking must never block UX */ }
                            }

                            function recordVisible() {
                                var links = document.querySelectorAll('.featured-product-link[data-product-id]');
                                var vw = window.innerWidth || document.documentElement.clientWidth;
                                var vh = window.innerHeight || document.documentElement.clientHeight;
                                Array.prototype.forEach.call(links, function (l) {
                                    if (l.offsetParent === null) return; // display:none (inactive slide)
                                    var r = l.getBoundingClientRect();
                                    if (r.width === 0 || r.height === 0) return;
                                    var inView = r.top < vh && r.bottom > 0 && r.left < vw && r.right > 0;
                                    if (inView) record(l.getAttribute('data-product-id'));
                                });
                            }

                            function start() {
                                if ('IntersectionObserver' in window) {
                                    var io = new IntersectionObserver(function (entries) {
                                        entries.forEach(function (en) {
                                            if (en.isIntersecting && en.intersectionRatio > 0) {
                                                record(en.target.getAttribute('data-product-id'));
                                            }
                                        });
                                    }, { threshold: 0.3 });
                                    Array.prototype.forEach.call(
                                        document.querySelectorAll('.featured-product-link[data-product-id]'),
                                        function (l) { io.observe(l); }
                                    );
                                } else {
                                    recordVisible();
                                    window.addEventListener('scroll', recordVisible, { passive: true });
                                }
                                // Belt-and-suspenders: when the Bootstrap carousel advances, re-check visibility.
                                if (window.jQuery) {
                                    jQuery('#carousel-example-generic').on('slid.bs.carousel', function () {
                                        setTimeout(recordVisible, 60);
                                    });
                                }
                            }

                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', start);
                            } else {
                                start();
                            }
                        })();
                    </script>

                </div>
                <div class="clearfix"></div>
                <?php
                global $wpdb;
                $pageID = get_option('page_on_front');
                $home_videos = get_post_meta($pageID, 'videos');
                //print_r($home_videos);exit;
                $home_videos_arr = array_filter(explode("\n", str_replace("\r", "", $home_videos[0])));
                ?>


            </div>
            <div class="clearfix"></div>
            <!-- Videos Events Copyright -->

            <div class=" bar-block video homepage-video-section">
                <div class="row">
                    <!--  <div class="slideshow-container"> -->
                    <div id="myCarousel1" class="carousel slide">
                        <div class="carousel-inner">
                            <?php

                            $video_dets = explode('|', $home_videos_arr[0]);
                            ?>
                            <div class="<?php if ($v_cnt == 0) {
                                echo 'active';
                            } ?> item">
                            <!-- <img src="https://sipnbourbon.com/wp-content/uploads/2025/09/img-promotion.png" class="promotion" > -->
                                <iframe loading="lazy" src="<?php echo $video_dets[0] . '&autopause=0&mute=1'; ?>"
                                    allow="autoplay" allowfullscreen width="420" height="280">

                                </iframe>
                            </div>

                        </div>
                        <!-- <a class="carousel-control left" href="#myCarousel1" onclick="callPlayer('current','pauseVideo')" data-slide="prev">&lsaquo;</a>
                                                                <a class="carousel-control right" href="#myCarousel1" onclick="callPlayer('current','pauseVideo')" data-slide="next">&rsaquo;</a> -->
                        <!--    <ul class="carousel-indicators video-dot">
                                                                  <?php
                                                                  //$v_cnt1 = 0;
                                                                  //foreach($home_videos_arr as $home_video){ if($home_video != ''){
                                                                  ?>
                                                                <li data-target="#myCarousel1" data-slide-to="<?php //echo $v_cnt1;
                                                                ?>" class="<?php //if($v_cnt1==0){ echo 'active';}
                                                                ?>"></li>
                                                                <?php //$v_cnt1++;}}
                                                                ?>

                                                              </ul> -->
                    </div>
                    <!-- </div> -->


                    <br>


                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 events">
                <a href="/sipn-bourbon-events"><img loading="lazy"
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/img-events.png" alt="events"
                        height="100%" width="100%"></a>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            <input type="hidden"
                value="sipn,sipn bourbon,bourbons,blanton's bourbon,bulleit bourbon,weller bourbon,eagle rear bourbon,buy bourbon,liquor store,buy liquor,best bourbon,buy bourbon online,rare bourbon,bourbon store,bourbon liquor,best bourbon whiskey">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="mobile-apps-links">
                    <h3>AVAILABLE ON</h3>
                    <div class="mobile-links-img">
                        <a href="https://play.google.com/store/apps/details?id=com.cta.sipn&hl=en" target="_blank"><img
                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/play-store.png"
                                alt="google play"></a>
                        <a href="https://apps.apple.com/in/app/sipn-bourbon/id1597312660" target="_blank"><img
                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/app-store.png"
                                alt="app store"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 allrights">
                &copy; Sipn Bourbon 2021-<?php echo date('Y'); ?>. All Rights Reserved.
                <div class="clearfix"></div>
            </div>
            <p style="display: none;"><a href="https://sipnbourbon.com/sitemap">sitemap</a></p>





            <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrapnew.min.css"
                type="text/css">
            <script type="text/javascript"
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrapnew.min.js"></script>
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>

            <!--   <script src="https://player.vimeo.com/api/player.js"></script> -->
            <script type="text/javascript">
                $('.read-more-content').addClass('hide')
                $('.read-more-show, .read-more-hide').removeClass('hide')

                // Set up the toggle effect:
                $('.read-more-show').on('click', function (e) {
                    $(this).next('.read-more-content').removeClass('hide');
                    $(this).addClass('hide');
                    e.preventDefault();
                });

                $('.read-more-hide').on('click', function (e) {
                    $(this).parent('.read-more-content').addClass('hide');
                    var moreid = $(this).attr("more-id");
                    $('.read-more-show#' + moreid).removeClass('hide');
                    e.preventDefault();
                });



                var slide = [];

                var str = $('#totalcountim').val();

                var str_array = str.split(',');
                //alert(str_array);
                //alert(str_array.length);
                for (var s = 0; s < str_array.length; s++) {
                    // alert(s);
                    // alert(str_array[s]);
                    // Trim the excess whitespace.

                    // Add additional code here, such as:
                    slide[str_array[s]] = new CreateSlide(s);
                    // console.log( 'var slide'+[str_array[s]]+'=new CreateSlide('+s+')');


                }
                // for (var i = 0; i < 11; i++) {

                // var j=i+1;
                //  slide[j] = new CreateSlide(i);

                // }

                // var slide1 = new CreateSlide(0);
                //  var slide2 = new CreateSlide(1);
                // var slide3 = new CreateSlide(2);
                // var slide4 = new CreateSlide(3);
                // var slide5 = new CreateSlide(4);
                // var slide6 = new CreateSlide(5);
                // var slide7 = new CreateSlide(6);
                // var slide8 = new CreateSlide(7);
                // var slide9 = new CreateSlide(8);
                //  var slide10 = new CreateSlide(9);


                function CreateSlide(index) {
                    this.slideContainer = document.getElementsByClassName("section")[index];
                    this.slideIndex = 1;
                    //  console.log(this.slideContainer);
                    this.plusSlides = function (n) {
                        this.showSlides(this.slideIndex += n);
                    };
                    this.currentSlide = function (n) {
                        this.showSlides(this.slideIndex = n);
                    };
                    this.showSlides = function (n) {
                        var i;
                        var slides = this.slideContainer.getElementsByClassName("mySlides");
                        // alert(slides.length);
                        var dots = this.slideContainer.getElementsByClassName("dot");
                        if (n > slides.length) {
                            this.slideIndex = 1
                        }
                        if (n < 1) {
                            this.slideIndex = slides.length
                        }
                        for (i = 0; i < slides.length; i++) {
                            slides[i].style.display = "none";
                        }
                        for (i = 0; i < dots.length; i++) {
                            dots[i].className = dots[i].className.replace(" active", "");
                        }
                        slides[this.slideIndex - 1].style.display = "block";
                        dots[this.slideIndex - 1].className += " active";
                    }
                    this.showSlides(1);
                }
            </script>
            <script>
                /* AUTOPLAY NAV HIGHLIGHT */

                // bind 'slid' function
                $('#myCarousel1').bind('slid', function () {

                    // remove active class
                    $('.carousel-linked-nav .active').removeClass('active');

                    // get index of currently active item
                    var idx = $('#myCarousel1 .item.active').index();

                    // select currently active item and add active class
                    $('.carousel-linked-nav li:eq(' + idx + ')').addClass('active');

                });

                //Youtube
                $(function ($) {
                    $('div.carousel-inner div.active').attr('id', 'current');
                });

                function callPlayer(frame_id, func, args = '') {
                    //alert(func);
                    if (window.$ && frame_id instanceof $) frame_id = frame_id.get(0).id;
                    //alert(frame_id);
                    var iframe = document.getElementById(frame_id);

                    if (iframe && iframe.tagName.toUpperCase() != 'IFRAME') {

                        iframe = iframe.getElementsByTagName('iframe')[0];

                        iframe = '[object HTMLIFrameElement]';
                    }
                    //alert(iframe);
                    if (iframe) {
                        //alert(func);
                        iframe.contentWindow.postMessage(JSON.stringify({
                            "event": "command",
                            "func": func,
                            "args": args || [],
                            "id": frame_id
                        }), "*");
                    }

                    jQuery(function ($) {
                        $('div.carousel-inner div.item').attr('id', '');
                        $('div.carousel-inner div.active').attr('id', 'current');
                    });
                }

                localStorage.setItem('timeline_page', 1);
                jQuery('.videoSlide a').colorbox({
                    "allow": "autoplay",
                    onLoad: function () {
                        var iframe = document.querySelector('iframe');
                        //iframe.setAttributeNode("allow","autoplay");
                    },
                    onComplete: function () {
                        var iframe = document.querySelector('iframe');
                        //iframe.setAttributeNode("allow","autoplay");
                        var player = new Vimeo.Player(iframe);
                        document.querySelector('body').click();
                        player.play();
                        player.play().then(function () {
                            // The video is playing
                        }).catch(function (error) {
                            switch (error.name) {
                                case 'PasswordError':
                                    // The video is password-protected
                                    break;

                                case 'PrivacyError':
                                    // The video is private
                                    break;

                                default:
                                    // Some other error occurred
                                    break;
                            }
                        });
                        player.on('play', function () {
                            console.log('Played the video');
                        });
                    }
                });
            </script>

            </script>
            <script>
                // var icon = document.querySelector(".fa-search");
                //         var search = document.querySelector('#header-search');
                //         var form = document.querySelector('.form');
                //         icon.onclick = function() {
                //             search.classList.toggle('active')
                //             form.classList.toggle('active')

                //         }

                //         var mob_icon = document.querySelector(".fa-mob");
                //         var mob_search = document.querySelector('#mob_header_search');
                //         var mob_form = document.querySelector('.mob_form');
                //         mob_icon.onclick = function() {
                //             mob_search.classList.toggle('active')
                //             mob_form.classList.toggle('active')

                //         }


                //     var button = document.querySelector('#nav-icon4');
                //     button.addEventListener('click', function(){
                //         document.querySelector('.left-slide-bar').classList.toggle('open-menu');
                //         button.classList.toggle('open');

                //     });
                // added by sumeeth
                $(document).ready(function () {
                    $('body').on('click', '.copy-share-link', function () {
                        var copied_url = $(this).attr('link')
                        copyFormatted(copied_url);
                        $(this).append('<span class="copiedee">Link copied to clipboard.</span>');

                        setTimeout(function () {
                            $(".copiedee").remove();
                        }, 2000);
                    });

                    if (localStorage.getItem('popState') == 'shown') {
                        $("#popup").attr('style', 'display:none !important');

                    }
                    $('body').on('click', '#popup', function () {

                        $("#popup").attr('style', 'display:none !important'); // Now the pop up is hidden.
                    });
                    $('body').on('click', '.closeinstall', function () {

                        //alert('hi');
                        localStorage.setItem('popState', 'shown')
                        $("#popup").attr('style', 'display:none !important'); // Now the pop up is hidden.
                    });

                });
                // added by sumeeth
                //   close.addEventListener('click', function(){
                //      document.querySelector('.open').classList.remove('active');
                //       bar2.style.display = "none";

                //   })
            </script>

            <script>
                // Get the modal
                var modal = document.getElementById("myModal");
                var modal2 = document.getElementById("");
                var modal3 = document.getElementById("replyModal");
                var modal4 = document.getElementById("");
                var modal5 = document.getElementById("reportModal");
                var modal6 = document.getElementById("editModal1");
                var modal7 = document.getElementById("");
                var modal8 = document.getElementById("sponsoredModal");
                var modal9 = document.getElementById("editsponsModal1");
                var modal10 = document.getElementById("repliesModal2");
                var modal11 = document.getElementById("editsponsModal2");
                //var modal12 = document.getElementById("openpopup");

                // Get the button that opens the modal
                var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];
                var span2 = document.getElementsByClassName("close")[1];
                var span3 = document.getElementsByClassName("close")[2];
                var span4 = document.getElementsByClassName("close")[3];
                var span5 = document.getElementsByClassName("close")[4];
                var span6 = document.getElementsByClassName("close")[5];
                var span7 = document.getElementsByClassName("close")[6];
                var span8 = document.getElementsByClassName("close")[7];
                var span9 = document.getElementsByClassName("close")[8];
                var span10 = document.getElementsByClassName("close")[9];
                var span11 = document.getElementsByClassName("close")[10];
                // var span12 = document.getElementsByClassName("close")[11];

                // When the user clicks the button, open the modal
                // btn.onclick = function() {
                // modal.style.display = "block";
                // }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function () {
                    if (modal) {
                        $('#addimage').html('');
                        $('#mulimg').html('');
                    }
                    modal.style.display = "none";
                }
                span2.onclick = function () {
                    modal2.style.display = "none";
                }
                span3.onclick = function () {
                    modal3.style.display = "none";
                }
                span4.onclick = function () {
                    modal4.style.display = "none";
                }
                span5.onclick = function () {
                    modal5.style.display = "none";
                }
                span6.onclick = function () {
                    modal6.style.display = "none";
                }
                span7.onclick = function () {
                    modal7.style.display = "none";
                }
                span8.onclick = function () {
                    modal8.style.display = "none";
                }
                span9.onclick = function () {
                    modal9.style.display = "none";
                }
                span10.onclick = function () {
                    modal10.style.display = "none";
                }
                span11.onclick = function () {
                    modal11.style.display = "none";
                }
                // span12.onclick = function() {
                //   modal12.style.display = "none";
                // }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function (event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        modal2.style.display = "none";
                        modal3.style.display = "none";
                        modal4.style.display = "none";
                        modal5.style.display = "none";
                        modal6.style.display = "none";
                        modal7.style.display = "none";
                        modal8.style.display = "none";
                        modal9.style.display = "none";
                        modal10.style.display = "none";
                        modal11.style.display = "none";
                        // modal12.style.display = "none";
                    }
                }


                //added by sumeeth
                // function readURL(input) {
                // if (input.files && input.files[0]) {
                //     var reader = new FileReader();

                //     reader.onload = function (e) {
                //     //  alert(e.target.result);
                //     $('#blah').attr('src', e.target.result).width(100).height(100);
                //     };

                //     reader.readAsDataURL(input.files[0]);
                //    $('#blah').show();
                //    $('.edicloseimage2').show();
                //    $('#addimage').show();
                //    $('.post').prop('disabled', false);
                //    $('.post').removeClass('colorbttn');
                //    $('#tagproduct').prop('disabled', false); //for tag a product
                //     $('#tagproduct').removeClass('colorbttn'); //for tag a product
                // }
                // }




                /*function readURL(input) {
                    $('#addimage').html('');
                    $('#mulimg').html('');
                    if (input.length > 3) {
                        alert("please upload only 3 images");
                        $('.post').prop('disabled', true);
                        $('.post').addClass('colorbttn');
                        window.location.href = 'https://sipnbourbon.com/login/?redirect_to=msg-12345';
                    } else {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        // var reader = new FileReader();



                        var preview = document.getElementById("addimage");
                        var fileInput = document.querySelector("input[type=file]");
                        $j = 0;
                        for (var i = 0; i < fileInput.files.length; i++) {
                            var reader = new FileReader();
                            reader.onload = function(readerEvent) {
                                var listItem = document.createElement("li");
                                //alert(readerEvent.target.result);
                                listItem.innerHTML = "<img src='" + readerEvent.target.result +
                                    "'  style='height:100px;width:120px;' />";

                                $('#mulimg').append("<input type='hidden' id='img" + $j + "' value='" + readerEvent
                                    .target.result + "' >");
                                $j++;
                                preview.append(listItem);
                            }
                            reader.readAsDataURL(fileInput.files[i]);
                        }

                        $('.edicloseimage2').show();
                        $('#addimage').show();
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct').prop('disabled', false); //for tag a product
                        $('#tagproduct').removeClass('colorbttn'); //for tag a product

                        $('.taglocpost').prop('disabled', false); //for tag a location
                        $('.taglocpost').removeClass('colorbttn'); //for tag a location

                    }

                }*/

                function readURL2(input) {
                    var existingImages = $('#edit_img_posts li').length;

                    // Calculate total images after the new input
                    var totalImages = existingImages + input.length;

                    if (totalImages > 3) {
                        alert("Only 3 images allowed");
                        $('.post').prop('disabled', true);
                        $('.post').addClass('colorbttn');
                        return; // Exit the function if over limit
                    } else {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        var preview1 = document.getElementById("edit_img_posts");
                        var $j = existingImages; // Start from existing image count
                        var filesArray = Array.from(input); // Convert input to an array

                        // Process the first image and crop it
                        processImage(filesArray[0], 0);

                        function processImage(file, index) {
                            var reader = new FileReader();
                            reader.onload = function (readerEvent) {
                                // Show the modal and display the image in Cropper
                                var modal = document.getElementById("cropperModal");
                                var image = document.getElementById("image");
                                modal.style.display = "block";
                                image.src = readerEvent.target.result;

                                // Clean up previous cropper instance if exists
                                if (window.cropper) {
                                    window.cropper.destroy(); // Destroy the previous cropper instance
                                }

                                // Initialize Cropper.js with 1:1 aspect ratio
                                window.cropper = new Cropper(image, {
                                    aspectRatio: 1,
                                    viewMode: 1
                                });

                                // Clear any previous event handlers for the cropButton
                                $('#cropButton').off('click').on('click', function cropHandler() {
                                    var croppedCanvas = window.cropper.getCroppedCanvas({
                                        width: 300,
                                        height: 300
                                    });
                                    var croppedImage = croppedCanvas.toDataURL('image/png');

                                    // Add cropped image to preview
                                    var listItem = document.createElement("li");
                                    listItem.innerHTML = "<img src='" + croppedImage + "' style='height:30%;width:30%;' />" +
                                        "<span class='createclose edicloseimage3' style='z-index:1000;' data-index='" + $j + "'>×</span>";

                                    // Append cropped image and hidden input to the DOM
                                    $('#mulimg1').append("<input type='hidden' id='img1" + $j + "' value='" + croppedImage + "' >");
                                    preview1.append(listItem);
                                    $j++;

                                    // Close the modal and destroy the cropper instance
                                    modal.style.display = "none";
                                    window.cropper.destroy();

                                    // Move to the next image if there are more
                                    var nextIndex = index + 1;
                                    if (nextIndex < filesArray.length) {
                                        processImage(filesArray[nextIndex], nextIndex); // Process the next image
                                    }
                                });
                            };
                            reader.readAsDataURL(file);
                        }

                        // Ensure .edicloseimage3 buttons are visible
                        $('.edicloseimage3').show();
                        $('.viewonly').show();
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                    }
                }






                function readURL1(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            //  alert(e.target.result);
                            $('#blah1').attr('src', e.target.result).width(100).height(100);
                        };

                        reader.readAsDataURL(input.files[0]);
                        $('#blah1').show();
                        $('#editimage').show();
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct1').prop('disabled', false); //for tag a product
                        $('#tagproduct1').removeClass('colorbttn'); //for tag a product
                        $('.commentcloseimage').show();

                    }
                }

                function readURL3(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            //  alert(e.target.result);
                            $('#blah2').attr('src', e.target.result).width(100).height(100);
                        };

                        reader.readAsDataURL(input.files[0]);
                        $('#blah2').show();
                        $('#editimage2').show();
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        // $('#tagproduct1').prop('disabled', false); //for tag a product
                        // $('#tagproduct1').removeClass('colorbttn'); //for tag a product
                        $('.commentcloseimage2').show();

                    }
                }



                //  var loadFile = function(event) {
                //  // $epid=$(this).data("rid");
                //  //alert($epid);
                //  var image = document.getElementById('output');
                //  image.src = URL.createObjectURL(event.target.files[0]);
                //  $('#output').show();
                //  $('#editpostoutputimage').show();
                // // $('.edicloseimage').show();



                //  $('.post').prop('disabled', false);
                //     $('.post').removeClass('colorbttn');
                //     $('#tagproduct1').prop('disabled', false); //for tag a product
                //      $('#tagproduct1').removeClass('colorbttn'); //for tag a product


                //  };
                var loadFile1 = function (event) {
                    // $epid=$(this).data("rid");
                    //alert($epid);
                    var image = document.getElementById('output1');
                    image.src = URL.createObjectURL(event.target.files[0]);
                    $('#output1').show();
                    $('#editpostoutputimage1').show();
                    $('.edicloseimage1').show();
                };
                var loadFile1spons = function (event) {
                    // $epid=$(this).data("rid");
                    //alert($epid);
                    var image = document.getElementById('output123');
                    image.src = URL.createObjectURL(event.target.files[0]);
                    $('#editsponsModal1 #output123').show();
                    $('#editsponsModal1 #editpostoutputimage123').show();
                    $('#editsponsModal1 .edicloseimage1').show();
                };
                $(document).ready(function () {
                    if (window.location.href === 'https://sipnbourbon.com/?#msg-12345') {
                        $('#myModal').show();
                        //$('.post').prop('disabled', false);
                        //$('.post').removeClass('colorbttn');
                        //window.location.href='';
                    }

                });

                $("#comment_0").on("keyup", function () {
                    //  alert('hi');
                    var addcommenttext = $(this).val();
                    $a = $('#comment_img_0').val();
                    if (addcommenttext.trim().length > 0 || $a != '') {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct').prop('disabled', false); //for tag a product
                        $('#tagproduct').removeClass('colorbttn'); //for tag a product
                        $('.taglocpost').prop('disabled', false); //for tag a location
                        $('.taglocpost').removeClass('colorbttn'); //for tag a location


                    } else if (addcommenttext.length <= 0 && $a == '') {
                        //alert('hi');
                        $('.post').prop('disabled', true);
                        $('.post').addClass('colorbttn');
                        $('#tagproduct').prop('disabled', true); //for tag a product
                        $('#tagproduct').addClass('colorbttn');

                        $('.taglocpost').prop('disabled', true); //for tag a location
                        $('.taglocpost').addClass('colorbttn');


                        $('#headerpostsearch').hide();
                        $('.closeproduct1').hide();

                        $('.taglocpostsearch').hide();
                        $('.closeloc1').hide();



                    } else {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct').prop('disabled', true); //for tag a product
                        $('#tagproduct').addClass('colorbttn'); //for tag a product

                        $('.taglocpost').prop('disabled', true); //for tag a location
                        $('.taglocpost').addClass('colorbttn'); //for tag a location


                        $('#headerpostsearch').hide();
                        $('.closeproduct1').hide();

                        $('.taglocpostsearch').hide();
                        $('.closeloc1').hide();
                    }

                });
                //26-07





                $(document).ready(function () {
                    $('body').on('click', '.close', function (e) {
                        var modal = document.getElementById("repliesModal");
                        var modal1 = document.getElementById("repliesModal1");
                        var modal2 = document.getElementById("editModal");

                        if (modal2) {
                            $('#edit_img_posts').html('');
                            $('#mulimg1').html('');
                        }

                        modal.style.display = "none";
                        modal1.style.display = "none";
                        modal2.style.display = "none";
                        $('body').css('overflow', ''); // Resets overflow to the default value
                    });

                    $('body').on('click', '.closecropper', function (e) {
                        var modald = document.getElementById("cropperModal");
                        if (window.cropper) {
                            window.cropper.destroy(); // Destroy the previous cropper instance
                        }

                        modald.style.display = "none";
                        $('body').css('overflow', ''); // Resets overflow to the default value
                    });
                    $(".text-area add").on("keyup", function () {
                        var addcommenttext = $(this).val();

                        // alert(addcommenttext.trim().length);
                        $a = $('#comment_img_0').val();
                        // alert($a);
                        if (addcommenttext.trim().length > 0 || $a != '') {
                            $('.post').prop('disabled', false);
                            $('.post').removeClass('colorbttn');
                            $('#tagproduct').prop('disabled', false); //for tag a product
                            $('#tagproduct').removeClass('colorbttn'); //for tag a product
                            $('.taglocpost').prop('disabled', false); //for tag a location
                            $('.taglocpost').removeClass('colorbttn'); //for tag a location


                            $('#headerpostsearch').show();
                            $('.taglocpostsearch').show();
                            $('.closeloc1').show();
                        } else if (addcommenttext.length <= 0 && $a == '') {
                            //alert('hi');
                            $('.post').prop('disabled', true);
                            $('.post').addClass('colorbttn');
                            $('#tagproduct').prop('disabled', true); //for tag a product
                            $('#tagproduct').addClass('colorbttn');

                            $('.taglocpost').prop('disabled', true); //for tag a location
                            $('.taglocpost').addClass('colorbttn');


                        } else {
                            $('.post').prop('disabled', false);
                            $('.post').removeClass('colorbttn');
                            $('#tagproduct').prop('disabled', true); //for tag a product
                            $('#tagproduct').addClass('colorbttn'); //for tag a product

                            $('.taglocpost').prop('disabled', true); //for tag a location
                            $('.taglocpost').addClass('colorbttn'); //for tag a location



                        }

                    });
                });

                $("#comment_01").on("keyup", function () {
                    var addcommenttext = $(this).val();
                    $a = $('.commentImg').val();
                    $b = $('.sumee').val();
                    //  alert($a);
                    //  alert($b);
                    if (addcommenttext.trim().length > 0 || $a != '' || $b != '') {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct1').prop('disabled', false); //for tag a product
                        $('#tagproduct1').removeClass('colorbttn'); //for tag a product

                    } else if (addcommenttext.length <= 0 && $a == '' || $b == '') {
                        //alert('hi');
                        $('.post').prop('disabled', true);
                        $('.post').addClass('colorbttn');
                        $('#tagproduct1').prop('disabled', true); //for tag a product
                        $('#tagproduct1').addClass('colorbttn');
                        $('#headerpostsearch1').hide();
                        $('.closeproduct').hide();
                        $('.taglocpostsearch').hide();
                        $('.closeloc1').hide();
                    } else {
                        $('.post').prop('disabled', false);
                        $('.post').removeClass('colorbttn');
                        $('#tagproduct1').prop('disabled', true); //for tag a product
                        $('#tagproduct1').addClass('colorbttn'); //for tag a product
                        $('#headerpostsearch1').hide();
                        $('.taglocpostsearch').hide();
                        $('.closeloc1').hide();
                    }

                });
                // submitEditWrapper

                // Slider mchat
                // var container = document.getElementById('container')
                // var slider = document.getElementById('slider');
                // var slides = document.getElementsByClassName('slide').length;
                // var buttons = document.getElementsByClassName('btn');


                // var currentPosition = 0;
                // var currentMargin = 0;
                // var slidesPerPage = 0;
                // var slidesCount = slides - slidesPerPage;
                // var containerWidth = container.offsetWidth;
                // var prevKeyActive = false;
                // var nextKeyActive = true;

                // window.addEventListener("resize", checkWidth);

                // function checkWidth() {
                //     containerWidth = container.offsetWidth;
                //     setParams(containerWidth);
                // }


                // function setParams(w) {
                //     if (w < 551) {
                //         slidesPerPage = 1;
                //     } else {
                //         if (w < 901) {
                //             slidesPerPage = 2;
                //         } else {
                //             if (w < 1101) {
                //                 slidesPerPage = 3;
                //             } else {
                //                 slidesPerPage = 4;
                //             }
                //         }
                //     }
                //     slidesCount = slides - slidesPerPage;
                //     if (currentPosition > slidesCount) {
                //         currentPosition -= slidesPerPage;
                //     };
                //     currentMargin = - currentPosition * (100 / slidesPerPage);
                //     slider.style.marginLeft = currentMargin + '%';
                //     if (currentPosition > 0) {
                //         buttons[0].classList.remove('inactive');
                //     }
                //     if (currentPosition < slidesCount) {
                //         buttons[1].classList.remove('inactive');
                //     }
                //     if (currentPosition >= slidesCount) {
                //         buttons[1].classList.add('inactive');
                //     }
                // }

                // setParams();

                // function slideRight() {
                //     if (currentPosition != 0) {
                //         slider.style.marginLeft = currentMargin + (100 / slidesPerPage) + '%';
                //         currentMargin += (100 / slidesPerPage);
                //         currentPosition--;
                //     };
                //     if (currentPosition === 0) {
                //         buttons[0].classList.add('inactive');
                //     }
                //     if (currentPosition < slidesCount) {
                //         buttons[1].classList.remove('inactive');
                //     }
                // };

                // function slideLeft() {
                //     if (currentPosition != slidesCount) {
                //         slider.style.marginLeft = currentMargin - (100 / slidesPerPage) + '%';
                //         currentMargin -= (100 / slidesPerPage);
                //         currentPosition++;
                //     };
                //     if (currentPosition == slidesCount) {
                //         buttons[1].classList.add('inactive');
                //     }
                //     if (currentPosition > 0) {
                //         buttons[0].classList.remove('inactive');
                //     }
                // };
                //mchat

                $(document).ready(function () {


                    $(window).load(function () {
                        $(".mcarousel .item").each(function () {
                            var i = $(this).next();
                            i.length || (i = $(this).siblings(":first")),
                                i.children(":first-child").clone().appendTo($(this));

                            for (var n = 0; n < 4; n++)(i = i.next()).length ||
                                (i = $(this).siblings(":first")),
                                i.children(":first-child").clone().appendTo($(this))
                        })
                    });
                    $('#myCarousel').carousel({
                        interval: 10000
                    })
                });
                //for tag product

                $(document).ready(function () {
                    $('body').on('click', '#tagproduct1', function () {

                        $("#headerpostsearch1").toggle();
                        $(".closeproduct").toggle();
                        //$('.tagloceditpostsearch').hide();
                        // $('.closeloc').hide();
                        //$('.headerpost-result-sec').hide();


                        //$(".closeproduct").toggle();
                        // $('#tagproduct1').prop('disabled', true); //for tag a product
                        // $('#tagproduct1').addClass('colorbttn'); //for tag a product


                    });
                });



                $(document).ready(function () {
                    $('body').on('click', '.closeproduct', function () {


                        //$(".headerpost-result-sec").hide();
                        $("#fpid1").val('');
                        $("#headerpostsearch1").val('');





                    });
                    $('body').on('click', '.closeloc', function () {
                        //  alert('ded');

                        $(".tagloceditpostsearch").val('');

                        //  $("#fpid1").val('');
                        // $("#headerpostsearch1").val('');
                        //$('.closeproduct').hide();




                    });

                    $('body').on('click', '.closeloc1', function () {
                        //  alert('ded');

                        $(".taglocpostsearch").val('');
                        $('.taglocpostsearch').hide();
                        $('.closeloc1').hide();


                        //  $("#fpid1").val('');
                        // $("#headerpostsearch1").val('');
                        //$('.closeproduct').hide();




                    });

                    $('body').on('click', '.openpost', function () {
                        window.history.pushState('', '', 'https://sipnbourbon.com');
                        //location.replace("https://sipnbourbon.com")


                    });
                });


                //for tag location on post
                $(document).ready(function () {



                    $('body').on('click', '#tageditlocpost', function () {


                        $(".tagloceditpostsearch").toggle();
                        $(".closeloc").toggle();
                        //$('.headerpostsearch1').hide();
                        //$('.closeproduct').hide();
                        //$('.closeloc').toggle();
                        $('.headerpost-result-sec').hide();
                        // $('#taglocpost').prop('disabled', true); //for tag a product
                        //$('#taglocpost').addClass('colorbttn'); //for tag a product





                    });

                    $('body').on('click', '.deletepimages', function () {

                        $("#addeimage").empty();
                        $("#delete_image").val(1);
                        $('.view-gallery').hide();
                        //$('#taglocpost').addClass('colorbttn'); //for tag a product
                    });

                    ///for add post x mark


                    ///for edit post x mark
                    $('body').on('click', '.edicloseimage', function () {
                        $('input[type=file]').val('');
                        $("#img10").val('');
                        $("#img11").val('');
                        $("#img12").val('');
                        $('#addeimage').hide();
                        //$('#taglocpost').addClass('colorbttn'); //for tag a product


                    });










                });
            </script>
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58L6H4C" height="0" width="0"
                    style="display:none;visibility:hidden"></iframe></noscript>


        </div>
    </div>
    </div>
    </div>
    <!--     added by sumeeth -->


</section>
</main>
<?php echo wp_footer(); ?>
</div>

<style>
    div#popup {
        display: none !important;
    }

    /* use a media query to filter small devices */
    @media only screen and (max-device-width:480px) {

        /* show the popup */
        div#popup {
            display: block !important;
        }
    }

    /*mchat slider css*/
    #container {
        height: 40vh;
        width: 94vw;
        margin: 0;
        padding: 0;
        /* background: teal; */
        display: grid;
        place-items: center
    }

    #slider-container {
        height: 205px;
        width: 89vw;
        max-width: 1400px;
        /* background: #54d5e4;
    box-shadow: 5px 5px 8px gray inset;*/
        position: relative;
        overflow: hidden;
        padding: 20px;
        float: left;
    }

    #slider-container .btn {
        /* position: absolute;
    top: calc(50% - 30px);
    height: 30px;
    width: 30px;
    border-left: 8px solid #b7a968;
    border-top: 8px solid #b7a968; */
    }

    #slider-container .btn:hover {
        /* transform: scale(1.2); */
    }

    #slider-container .btn.inactive {
        /* border-color: rgb(183 169 104); */
    }

    #slider-container .btn:first-of-type {
        /* transform: rotate(-45deg);
    left: 10px;
    z-index: 1;
    height:31px; */
    }

    #slider-container .btn:last-of-type {
        /* transform: rotate(135deg);
    right: 10px; */
    }

    #slider-container #slider {
        display: flex;
        width: 1000%;
        height: 100%;
        transition: all .5s;
    }

    #slider-container #slider .slide {
        height: 90%;
        margin: auto 10px;
        /* background-color: #a847a4;
    box-shadow: 2px 2px 4px 2px white, -2px -2px 4px 2px white; */
        display: grid;
        place-items: center;
    }

    #slider-container #slider .slide span {
        color: white;
        font-size: 150px;
    }

    @media only screen and (min-width: 1100px) {

        #slider-container #slider .slide {
            width: calc(2.5% - 20px);
        }

    }

    @media only screen and (max-width: 1100px) {

        #slider-container #slider .slide {
            width: calc(3.3333333% - 20px);
        }

    }

    @media only screen and (max-width: 900px) {

        #slider-container #slider .slide {
            width: calc(5% - 109px);
            margin: 0 5px 0 0;
        }

        #slider-container {
            padding: 10px;
            width: 96vw;
            height: 220px;
            float: left !important;
        }

        #container {
            height: 42vh;
        }

        #slider-container .btn {
            /*top: calc(48% - 0px);/*}
    /* #slider-container #slider{ width:688%;} */
        }
    }

    @media only screen and (max-width: 550px) {
        #container {
            height: 31vh;
        }

        #slider-container .btn {
            /*top:calc(40% - 2px);*/
        }

        #slider-container {
            padding: 0px;
            width: 92vw;
            float: left;
        }

        #slider-container #slider .slide {
            width: calc(6% - 54px);
            margin: 0 0 5px 0;
        }

        /* #slider-container #slider{ width: 485%;} */
    }

    /*mchat slider css*/
</style>

<div class="modal modal-emailverification fade" id="openpopup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-emailverification modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <i class="fa fa-info-circle fa-info-circle-custom" aria-hidden="true"></i>
            </div>
            <div class="modal-body">
                <div class="email-verification-text">Email not verified</div>
                <div class="email-verification-content">We sent an email to you please verify your email to continue
                </div>
                <div class="resendemail-main"><a href="#" class="resendemail" data-id="<?php echo $curemail; ?>"
                        id="resendemail">Resend verification mail</a></div>
                <div class="resendemail-main"><a href="<?php if (is_user_logged_in()) {
                    echo '/bar/user-' . $current_user->data->ID;
                } else {
                    echo '/login';
                } ?>" class="email-verified-div">Already verified? Go to
                        profile</a></div>
            </div>
        </div>
    </div>
</div>
<div id="confirmdelmodel" class="modal" style="z-index:10100;">
    <div class="modal-content">
        <div class="report">
            <p class="content_delete">Are you sure, you want to delete?</p>
            <div class="row">
                <div class="btns-cancel-proceed">
                    <button class="btn btn-profile-cancel" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-profile-save" id="delete-tl-post">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="confirmdelmodelspons" class="modal" style="z-index:10100;">
    <div class="modal-content">
        <div class="report">
            <p class="content_delete">Are you sure, you want to delete?</p>
            <div class="row">
                <div class="btns-cancel-proceed">
                    <button class="btn btn-profile-cancel" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-profile-save" id="delete-tl-sponcomment">Ok</button>
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
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly"
    async></script>
</body>

</html>
<!--     added by sumeeth -->
<?php //sipn_footer();
?>