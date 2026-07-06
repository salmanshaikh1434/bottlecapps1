<?php
/**
 * Template Name: SIPN Timeline Sponsored Post
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
if (is_user_logged_in()) {
    $cur_user_id = get_current_user_id();
    $current_user_details = get_user_by('id', $cur_user_id);
    $current_user_meta = get_user_meta($cur_user_id);
    $cur_user_avatar = wp_get_attachment_image_url($current_user_meta['wp_user_avatar'][0], 'thumbnail');

    if (!$cur_user_avatar) {
        $cur_user_avatar = get_avatar_url($cur_user_id);
    }
}
$a = $_GET['key'];
$c = base64_decode($a);
function get_timeline_post($post_id)
{
    global $wpdb;
    $all_topics = array();
    $all_topics['sponsored_ads'] = array();
    $day = date('Ymd');
    $query = $wpdb->prepare("SELECT *  FROM wp_sponsored_ads WHERE  id=$post_id");
    $notifications_list = $wpdb->get_results($query);

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


    $k = 0;
    $sponsored_verify = "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-sponsored.png";
    foreach ($notifications_list as $not) {
        $query = $wpdb->prepare("SELECT count(*) as cnt FROM wp_sponsored_likes WHERE  spons_id =$not->id");
        $cnt_list = $wpdb->get_results($query);
        $likes_count = $cnt_list[0]->cnt;
        $cur_user = wp_get_current_user();
        $userid = $cur_user->data->ID;
        if ($userid > 0) {
            $query1 = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_sponsored_likes` WHERE user_id = '%d' AND spons_id = '%d'", $userid, $not->id);
            $list = $wpdb->get_results($query1);
            if ($list[0]->cnt >= 1) {
                $is_liked = "1";
            } else {
                $is_liked = "0";
            }
        } else {
            $is_liked = "0";
        }
        $productlis = get_post($not->product_id);

        $product_title = $productlis->post_title;
        if ($product_title == '') {
            $product_title = '';
        }
        $pid = $not->product_id;
        if ($pid == '') {
            $pid = '';
        }
        $product_image = get_the_post_thumbnail_url($pid, 'full');
        if ($product_image == '') {
            $product_image = '';
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
        $all_topics['sponsored_ads'][$k]['replies'] = $spons_replies;
        $all_topics['sponsored_ads'][$k]['buynow_text'] = $not->ad_type;
        $k++;
    }
    return $all_topics;

}
?>
<article class="col-sm-12 col-xs-12 article sharetimeline">
    <div class="main-section">
        <div class="main-content">
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

            <?php
            if ($_GET['q']) {
                $timeline_res = get_timeline_post(filter_var($_GET['q'], FILTER_VALIDATE_INT));

                foreach ($timeline_res['sponsored_ads'] as $spons) {
                    ?>

                    <div class="inner-content" id="sponsmsg-<?php echo $spons['spons_id']; ?>">
                        <div class="user-feed">
                            <div class="user-profile">
                                <div class="profile-in">
                                    <div class="profile-pic">
                                        <a class="profile-spons-add" href="javascript:void(0);"><img
                                                src="<?php echo $spons['company_logo']; ?>" alt="sponsored_logo" width="60"
                                                height="60"></a>
                                        <!--    added by sumeeth -->
                                        <div class="user-name">
                                            <a href="javascript:void(0);"><?php echo $spons['company_name']; ?></a>
                                            <span class="company_verified"><img alt="company_logo" width="23" height="23"
                                                    src="<?php echo $spons['spons_verified']; ?>"></span> <br>
                                            <?php if ($spons['product_title'] != '') {
                                                $the_product = wc_get_product($spons['product_id']); ?>
                                                <?php if ($spons['product_title'] !== 'Timeline Sponsored') { ?>
                                                    <span class="sumss recordaddclick" data-actiontype="ProductLink"
                                                        data-id="<?php echo $spons['spons_id']; ?>" data-from="website"><a
                                                            href="<?php echo get_permalink($spons['product_id']); ?>"
                                                            title="<?php echo $spons['product_title']; ?>"><?php $p = $spons['product_title'];
                                                               if ($p == "Sipn Bourbon - Home is where Bourbon is") {
                                                                   $spons['product_title'] = '';
                                                               }
                                                               echo $spons['product_title']; ?></a></span>
                                                    <br>
                                                <?php } ?>
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
                                            $spons_link = bbp_get_user_profile_url($current_user->data->ID);
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
                                <?php if ($spons['product_title'] != '') { ?>
                                    <div><a href="<?php echo $spons['link']; ?>" target = "_blank"
                                             class="buynow post-buynow recordaddclick" data-actiontype="BuyNow"
                                             data-id="<?php echo $spons['spons_id']; ?>" data-from="website"><button
                                                 class="search"><?php echo !empty($spons['buynow_text']) ? esc_html($spons['buynow_text']) : 'Buy Now'; ?></button></a></div>
                                <?php } ?>
                            </div>
                        </div>
                        

                    </div>

                <?php }
            } ?>
        </div>
    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script>

        $('.close').click(function () {
            $('.modal').css('display', 'none'); // This sets display to none
        });


        $('body').on('click', '.copy-share-link', function () {
            var copied_url = $(this).attr('link')
            copyFormatted(copied_url);
            $(this).append('<span class="copiedee">Link copied to clipboard.</span>');

            setTimeout(function () {
                $(".copiedee").remove();
            }, 2000);
        });
        var icon = document.querySelector(".fa-search");
        var search = document.querySelector('#header-search');
        var form = document.querySelector('.form');
        icon.onclick = function () {
            search.classList.toggle('active')
            form.classList.toggle('active')
        }
    </script>
    <script>
        var button = document.querySelector('#nav-icon4');
        button.addEventListener('click', function () {
            document.querySelector('.sec-nav-bar').classList.toggle('open-menu');
            button.classList.toggle('open');
        });
    </script>



    <?php sipn_footer(); ?>