<?php
/**
 * Template Name: SIPN Timeline Post
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php get_header(); ?>
<style>
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

    </style>
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

    //if($topic->post_type == 'topic' && $topic->post_status == 'publish'){

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

    $args = [
        'include' => array($post_id),
        'post_type' => 'reply',
        'post_status' => 'publish',
        'author__not_in' => $mutually_blocked_users,
        'order' => 'DESC'
    ];

    $cur_user = wp_get_current_user();
    $cur_user_id = $cur_user->data->ID;
    if (!$cur_user_id) {
        $cur_user_id = '0';
    }

    $replies = get_posts($args);
    $all_topics['replies'] = array();
    foreach ($replies as $reply) {
        $parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
       
        $author_id = $reply->post_author;
        $author_details = get_user_by('id', $author_id);
        $author_meta = get_user_meta($author_id);
        $avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');

        if (!$avatar) {
            $avatar = get_avatar_url($author_id);
        }

        $query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d' and status='0'", $reply->ID);
        $cnt_list = $wpdb->get_results($query);
        $likes_count = $cnt_list[0]->cnt;

       
        $thumbnail_id = get_post_meta($reply->ID, '_thumbnail_id');
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
        $external_url = $productlis->external_url; 
        
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
            $product_rating = '';
        }
        $product_price = $the_product->price;
        if ($product_price == '') {
            $product_price = '';
        }
        $results = new WP_Query($query);
        $total_replies_count = $results->found_posts; //// This is 0...

        wp_reset_postdata();

        $replies = get_timeline_replies($reply->ID, 1);

        $reply_date = timeline_time_ago($reply->post_date);
        $url = get_home_url() . "/timeline#" . $reply->ID;
       

        array_push($all_topics['replies'], array('reply_id' => $reply->ID, 'reply' => str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'reply_image' => $reply_image_path, 'reply_imagearray' => $array1, 'reply_date' => $reply_date, 'reply_gmt_date' => $reply->post_date, 'total_replies_count' => $total_replies_count, 'author' => $author_details->data->display_name, 'author_id' => $author_id, 'author_city' => $author_meta['city'][0], 'author_state' => $author_meta['state'][0], 'url' => $url, 'avatar' => $avatar, 'likes' => $likes_count, 'is_liked' => get_like_flag($reply->ID), 'product_id' => $pid, 'product_title' => $product_title, 'product_image' => $product_image, 'product_upc' => $product_upc, 'product_rating' => $product_rating, 'product_price' => $product_price, 'tagged_location' => $lid, 'replies' => $replies));
    }
    

    return $all_topics;

}
?>
<article class="col-sm-12 col-xs-12 article sharetimeline">
    <div class="main-section">
        <div class="main-content">
            <div class="inner-content-feeds1">

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
                              
                                <input type="text" placeholder="Post your Comment"
                                    class="text-area comment replsum comments_input" id="comment_0">
                                <button type="button" class="post submitRepliesWrapper post_cmnt_new" rid="0" value=""
                                    disabled="disabled"><img
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>   
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
                            
                                <input type="text" class="comments_input comment" placeholder="Post your Comment"
                                    id="comment_0" />
                                <button class="submitRepliesWrapper post_cmnt_new" rid="0"><img
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-send.png" /></button>
                            </div>
                        
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

                <?php
                //get timeline messages
                //print_r($_GET);
                if ($_GET['q']) {
                    $reply_id =  $_GET['q'];

                    $query1 = $wpdb->prepare("SELECT meta_value as pid FROM wp_postmeta WHERE post_id = $reply_id AND meta_key ='_bbp_product_id'");
                    $p_list = $wpdb->get_results($query1);
                    $pid = $p_list[0]->pid;
                    $productlis = get_post($pid);
                    $external_url = $productlis->external_url; 
                    
                    $timeline_res = get_timeline_post(filter_var($_GET['q'], FILTER_VALIDATE_INT));
                    //print_r($timeline_res); exit;
                    $tmlcount = 1;
                    $tmp = array();
                    foreach ($timeline_res['replies'] as $reply) {
                        //print_r( $reply['reply_id']); exit;
                        ?>
                        <div class="inner-content" id="msg-<?php echo $reply['reply_id'] ?>">
                            <div class="user-feed">
                                <div class="user-profile">



                                    <div class="profile-in">
                                        <div class="profile-pic">
                                            <a href="<?php if (is_user_logged_in()) {
                                                echo bbp_get_user_profile_url($reply['author_id']);
                                            } else {
                                                echo "/login";
                                            } ?>"><img loading="lazy" src="<?php echo $reply['avatar']; ?>"
                                                    alt="profile_pic" width="60" height="60"></a>
                                            <!--    added by sumeeth -->
                                            <div class="user-name">
                                                <?php if (is_user_logged_in()) { ?>
                                                    <a href="<?php echo bbp_get_user_profile_url($reply['author_id']); ?>">
                                                        <?php echo $reply['author']; ?></a>
                                                <?php } else { ?>
                                                    <a href="/login"><?php echo $reply['author'];
                                                } ?></a>
                                                <span class="company_verified"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/badge-gold.png" width="23"></span>
                                                <br>
                                                <?php if ($reply['product_title'] != "Sipn Bourbon - Home is where ...") {
                                                  
                                                    $the_product = wc_get_product($reply['product_id']); 
                                                    if($reply['product_title'] !== 'Timeline'){
                                                    ?>
                                                    <span class="sumss"><a href="<?php echo get_permalink($reply['product_id']); ?>"
                                                            title="<?php echo $reply['product_title']; ?>">
                                                           
                                                            <?=   $reply['product_title'] ?></a></span><br>
                                                <?php }} else { ?>

                                                <?php } ?>
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
                            } ?>" <?php if ($reply['product_image'] != '') { ?>
                                    oncontextmenu="return false;" <?php } ?>>

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
                                                        onclick="slide[<?php echo $tmlcount; ?>].plusSlides(-1)" <?php if ($coui == 1) { ?> style="display: none;" <?php } ?>>❮</a>
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
                                                class="likecomment"><span
                                                    class="onlylike"><?php if (($reply['likes'] != '0')) {
                                                        echo $reply['likes']; ?></span><?php } else {
                                                        echo $reply['is_liked'] . "</span>";
                                                    } ?>
                                        </a>
                                    <?php } else { ?>
                                        <a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="like"> <span
                                                class="likecomment"><span
                                                    class="onlylike"><?php if (($reply['likes'] != '0')) {
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
                                            <a href="#." id="comment" class="replies_list rlist_<?php echo $reply['reply_id']; ?>"
                                                rid="<?php echo $reply['reply_id']; ?>">
                                                <?php echo $reply['total_replies_count']; ?></a>

                                        <?php } else { ?>
                                            <a href="/login?redirect_to=msg-<?php echo $reply['reply_id'] ?>" id="comment">
                                                <?php echo $reply['total_replies_count']; ?></a>
                                        <?php } ?>

                                    <?php } ?>

                                </div>
                                <div class="options3 options">
                                   
                                    <?php   
                                  
                                    if (isset($external_url) && !empty($external_url)) {  ?>
                                  
                                    <a href="javascript:void(0);" class="copy-share-link" id="share"
                                        link="<?php echo esc_url($external_url); ?>"></a>

                                    <?php } else{?>

                                        <a href="javascript:void(0);" class="copy-share-link" id="share"
                                        link="<?php echo site_url(); ?>/timeline/?q=<?php echo $reply['reply_id']; ?>"></a>

                                    <?php }?>
                                 
                                </div>

                            
                                <?php if ($reply['product_title'] != "Sipn Bourbon - Home is where ..." && !empty($reply['product_id'])) {

                                    $the_product = wc_get_product($reply['product_id']);
                                 
                                if (isset($external_url) && !empty($external_url)) { 
                                ?>
                                    <div>
                                        <a href="<?php echo esc_url($external_url); ?>" class="buynow post-buynow" target="_blank">
                                            <button class="search">Buy Now</button>
                                        </a>
                                    </div>
                                <?php 
                                } else { 
                                ?>
                                    <div>
                                        <a href="<?php echo esc_url(add_query_arg(array('prod_id' => $the_product->sku, 'prid' => $the_product->id), site_url('/buy-now/'))); ?>" 
                                        class="buynow post-buynow">
                                            <button class="search">Buy Now</button>
                                        </a>
                                    </div>
                                <?php 
                                } } else { ?>

                                <?php } ?>
                            </div>
                          
                        </div>

                        <?php $tmlcount++;
                    }
                } ?>

            </div>



        </div>
        <br>


    </div>
    <input type="hidden" name="totalcountim[]" id="totalcountim" value="<?php echo implode(',', $tmp); ?>">

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script type="text/javascript">

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

        var slide = [];

        var str = $('#totalcountim').val();

        var str_array = str.split(',');
       
        for (var s = 0; s < str_array.length; s++) {   
            slide[str_array[s]] = new CreateSlide(s);   
        }
        


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