<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!session_id()) {
    session_start();
}

// Now access the session variable

get_header();
global $post;
global $wpdb;
$the_product = wc_get_product();
$produpch = get_post_meta($the_product->id, 'productupc', true);
$product_avg_rating = get_post_meta($the_product->id, '_product_average_rating', true);
$external_url = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT external_url FROM {$wpdb->posts} WHERE ID = %d",
        $the_product->id
    )
);
$produpc = str_replace('#', '', $produpch)
?>

<style>
    .page-loader {
        position: absolute;
        z-index: 9;
        text-align: center;
        width: 80px !important;
        bottom: 300px !important;
        margin: 0 auto !important;
        left: 40% !important;

    }

    .hide {
        display: none;
    }
    .modal {
        padding-top:10px!important;
    }
    .modal-body {
            width: 100%!important;
            float: left!important;
        }
        .modal-content{
            border: none!important;
            width:100%;
            padding:0px!important;
        }
</style>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bar.css" type="text/css" defer>

<article class="col-md-10 mtop80">
    <div class="container">
        <div class="col-md-4 col-sm-12 text-center">
            <div class="product-back">
                <a href="javascript:void(0);" class="productpage_back"><img
                        src="/wp-content/themes/SIPN/assets/images/icon-menu-slider.png"></a>
                <p class="prodet-heading"><?php echo $the_product->name; ?></p>
            </div>








            <div class="img-prodetail product-bg-div">


            <div class="col-md-12">
                <?php
                $add_to_bar = 1;
                $cur_user = wp_get_current_user();
                if (is_product_exists_bar($post->ID, $current_user->data->ID)) {
                    $add_to_bar = 0;
                }
                ?>
            
                <?php
                $add_to_wishlist = 1;
                $cur_user = wp_get_current_user();
                $user_details = get_user_meta($cur_user->data->ID);
                $existing_wishlist = $user_details['wishlist'][0];
                if ($existing_wishlist) {
                    $existing_wishlist_arr = maybe_unserialize($existing_wishlist);
                    if (in_array($post->ID, $existing_wishlist_arr)) {
                        $add_to_wishlist = 0;
                    }
                }
                ?>

                <div class="add-wishlist"><a <?php if (!is_user_logged_in()) { ?>class="" href="/login" <?php } else { ?>
                            href="javascript:void(0);" <?php }
                if (is_user_logged_in()) { ?> id="add_to_wishlist" <?php } ?>
                        pid="<?php echo $post->ID; ?>" <?php if ($add_to_wishlist) { ?> data-actiontype = "fav" wish="1" <?php } else { ?> wish="0" data-actiontype = "fav_removed"
                        <?php } ?>
                        data-prurl="0"   data-rtype = "1" data-epage = "product_detail" data-pupc="<?php echo $produpc;?>" class="recordprodevent">
                       <?php if ($add_to_wishlist) { ?><img
                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/icon-like.png"><?php } else { ?><img
                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/icon-like-active.png"><?php } ?>

                        <span><?php if ($add_to_wishlist) { ?><?php } else { ?><?php } ?></span></a></div>

            </div>




                <div class="btn-new-div btn-pro-btn">
                    <?php
                    $add_to_bar = 1;
                    $cur_user = wp_get_current_user();
                    if (is_product_exists_bar($post->ID, $current_user->data->ID)) {
                        $add_to_bar = 0;
                    }
                    ?>
                  
                </div>
                <?php

                $prod_url = get_the_post_thumbnail_url($post->ID, 'full');
                ?>
                <img src="<?php if ($prod_url) {
                    echo $prod_url;
                } else {
                    echo get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
                } ?>"
                    alt="<?php echo $the_product->name; ?>">

            </div>
            <div class="col-md-12 text-center">
                <?php echo sipn_social_share(); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="product-dtl-div prodetail-new mtop60">

                <h1 class="prodet-heading"><a href="javascript:void(0);" class="copyurl"><img
                            src="<?php echo get_stylesheet_directory_uri() . '/assets/images/icon-share-link.png'; ?>"
                            alt="<?php echo $the_product->name; ?>"></a><?php echo $the_product->name; ?></h1>

                <div class="rating ratings-new">
                    <ul>
                        <?php $the_product->average_rating = $product_avg_rating;?>
                        <?php 
                        // 1. Get raw float value (e.g. 3.5)
                        $rating = (float) $product_avg_rating;
                        
                        // 2. Calculate Full Stars (e.g. 3)
                        $full_stars = floor($rating);
                        
                        // 3. Check for Half Star (e.g. is decimal >= 0.5?)
                        $has_half_star = ($rating - $full_stars) >= 0.5;
                        
                        // 4. Calculate Empty Stars
                        // Total 5 - Full - (1 if half exists, else 0)
                        $empty_stars = 5 - $full_stars - ($has_half_star ? 1 : 0);
                        ?>

                        <?php for ($i = 0; $i < $full_stars; $i++) { ?>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-after.png"></li>
                        <?php } ?>
                        <?php if ($has_half_star) { ?>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-half.png">
                            </li>
                        <?php } ?>
                        <?php for ($j = 0; $j < $empty_stars; $j++) { ?>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-before.png">
                            </li>
                        <?php } ?>
                        
                    </ul>
                </div>
                <div class="price-new-pro">
                    <div class="price">$<?php echo $the_product->price; ?></div>
                    <span>Inclusive of all taxes</span>
                </div>
                <!--<h3>Region Proof Age Tasting Notes</h3>-->
                <div class="product_info pro-newdesc">
                    <?php if ($post->post_content) { ?>
                        <div class="description-detail">
                            <div class="pr-info-item"><label>Description: </label><span>
                                    <?php $content = nl2br(strip_tags($post->post_content));

                                    $count = strlen($content);
                                    if ($count > 160) {
                                        $showcontent = substr($content, 0, 150);

                                    } else {
                                        $showcontent = $content;
                                    }
                                    echo $showcontent;
                                    if ($count > 160) { ?>
                                        <a class="read-more-show hide" href="#" id="<?php echo $post->ID; ?>">Read More</a>
                                        <span class="read-more-content"><?php echo substr($content, 150); ?> <a
                                                class="read-more-hide hide" href="#" more-id="<?php echo $post->ID; ?>">Read
                                                Less</a></span>
                                    <?php }
                                    ?>
                                </span></div>
                        </div><?php } ?>
                </div>
                <?php view_acf_field_for_single_product(); ?>
                <div class="product_info pro-newsku">
                    <div class="pr-info-item"><span class="upc-detail"><label>SKU:
                            </label><span><?php echo $post->ID; ?></span></span></div>
                </div>
                <div class="btn-new-div">
                    <?php
                    $add_to_bar = 1;
                    $action = "";// added by salman very crucial for bar preview
                    $cur_user = wp_get_current_user();
                    if (is_product_exists_bar($post->ID, $current_user->data->ID)) {
                        $add_to_bar = 0;
                    }
                    if(!isset($_SESSION['si']) && $add_to_bar == 1){
                        $action = 'data-toggle="modal" data-target="#bar-preview"';
                    }

                    if($post->ID){
                        $_SESSION['product_id'] =  $post->ID; // added by salman very crucial for bar preview
                    }
                    ?>
                    <div class="add-bar-new">
                        <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>
                            <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static"
                                data-target="#openpopup" <?php if ($add_to_bar) { ?> data-actiontype="bar"  data-prurl="0" data-rtype="1" data-epage="product_detail" data-pupc="<?php echo $produpc;?>" class="recordprodevent" <?php } else { ?><?php } ?>>
                                <button class="btn btn-add-to-bar"><?php if ($add_to_bar) { ?>Add to Bar<?php } else { ?>Added
                                        to Bar<?php } ?></button></a>
                        <?php } else if (is_user_logged_in() && $current_user->data->validate_email == '0') { ?>

                                <a <?php if (!is_user_logged_in()) { ?>class="nologinaction" <?php } ?>
                                    link="<?php echo '/bar/user-'.$current_user->data->ID; ?>"
                                    href="javascript:void(0);" <?php if (is_user_logged_in()) { ?>  <?php } ?>
                                    pid="<?php echo $post->ID; ?>" <?php if ($add_to_bar) { ?> data-actiontype="bar"  data-prurl="0" data-rtype="1" data-epage="product_detail" data-pupc="<?php echo $produpc;?>" class="recordprodevent" bar="1" <?php } else { ?>bar="0" <?php } ?>
                                    <?= !empty($action) ? $action : (($add_to_bar == 1) ? 'id="add_to_bar"' :'') ?>>

                                <?php if ($add_to_bar) { ?>    <?php } else { ?>    <?php } ?>

                                    <button class="btn btn-add-to-bar"><?php if ($add_to_bar) { ?>Add to Bar<?php } else { ?>Added
                                            to Bar<?php } ?></button></a>

                        <?php } else { ?>
                                <a <?php if (!is_user_logged_in()) { ?>class="" <?php } ?>
                                    link="<?php echo '/bar/user-'.$current_user->data->ID; ?>" href="/login" <?php if (is_user_logged_in()) { ?> id="add_to_bar" <?php } ?> pid="<?php echo $post->ID; ?>" <?php if ($add_to_bar) { ?> data-actiontype="bar"  data-prurl="0" data-rtype="1" data-epage="product_detail" data-pupc="<?php echo $produpc;?>" class="recordprodevent" bar="1" <?php } else { ?>bar="0" <?php } ?>>
                                <?php if ($add_to_bar) { ?>    <?php } else { ?>    <?php } ?>

                                    <button class="btn btn-add-to-bar"><?php if ($add_to_bar) { ?>Add to Bar<?php } else { ?>Added
                                            to Bar<?php } ?></button></a>

                        <?php } ?>
                    </div>
                   <div class="buy-now-new-div test">
<?php
// Buy Now URL resolved dynamically (per-product field -> external URL -> default flow).
if (function_exists('sipn_get_product_buy_now')) {
    $buy = sipn_get_product_buy_now($the_product);
    $buy_url = $buy['url'];
    $rtype   = $buy['rtype'];
} elseif (!empty($external_url)) {
    $buy_url = $external_url;
    $rtype   = 1;
} else {
    $buy_url = add_query_arg(
        array('prod_id' => $the_product->sku, 'prid' => $the_product->id),
        site_url('/buy-now/')
    );
    $rtype = 0;
}
?>

<a href="javascript:void(0);"
   class="recordprodevent"
   data-prurl="<?php echo esc_url($buy_url); ?>"
   data-actiontype="buy_now"
   data-rtype="<?php echo $rtype; ?>"
   data-pupc="<?php echo esc_attr($produpc); ?>">
    <button class="btn btn-buynow">Buy Now</button>
</a>
</div>

    <?php if ( is_user_logged_in() ) {
        $rating_page_url = site_url( '/the-rating-club/' );
    }else{
         $rating_page_url = site_url( '/login/' );
    }
        $rate_link = add_query_arg( 'pid', $the_product->id, $rating_page_url );
        
        // --- NEW LOGIC: Check if user has already rated ---
        $rate_button_text = 'Rate This Bottle'; // Default text
        if ( is_user_logged_in() ) {
            global $wpdb;
            $ratings_table = $wpdb->prefix . 'ratings';
            $current_user_id = get_current_user_id();
            
            // Check if a row exists for this user and product 
            $existing_rating = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM $ratings_table WHERE user_id = %d AND product_id = %d",
                $current_user_id, 
                $the_product->id
            ));
            
            if ( $existing_rating ) {
                $rate_button_text = 'Edit Rating';
            }
        }
        // --- NEW LOGIC END ---

    ?>
    <a href="<?php echo esc_url( $rate_link ); ?>" class="btn-sipn-rate">
      <i class="fa fa-star"></i> <?php echo $rate_button_text; ?> <?php //echo $the_product->product_rating; ?>
    </a>

                </div>
            </div>

            
        </div>
    </div>

    <?php echo '<script type="application/ld+json">
{
  "@context": "https://schema.org/", 
  "@type": "Product", 
  "name": "' . $the_product->name . '",
  "image":"' . $prod_url . '",
  "description": "' . $showcontent . '",
  "brand": {
    "@type": "Brand",
    "name": "Bourbon"
  },
  "sku": "' . $post->ID . '",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "1.5",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "5"
  }
}
</script>'; ?>

    <?php
    sipn_footer();

    /* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
    ?>
    <script>
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
        $(document).ready(function () {
            // on click
            $('.copyurl').click(function (e) {
                //console.log(window.location.href);
                copyFormatted(window.location.href);
                $(".home-copytext").remove();
                $(this).append('<p class="copied home-copytext">Link copied to clipboard.</p>');

                setTimeout(function () {
                    $(".home-copytext").remove();
                }, 2000);
            });
            $('.productpage_back').click(function (e) {
                // prevent default behavior
                e.preventDefault();
                // Go back 1 page 
                window.history.back();
                // can also use 
                // window.history.go(-1);
            });
        });

        


    </script>



 <!-- Bootstrap JS and dependencies -->

 <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
        
        function scrollCarousel(button, direction) {
            const shelf = button.closest('.shelf');
            const carousel = shelf.querySelector('.carousel');
            if (!carousel) return;

            const bottle = carousel.querySelector('.bottle');
            const bottleWidth = bottle ? bottle.offsetWidth : 0;
            const margin = 20;
            const scrollAmount = bottleWidth + margin;

            if (direction === 'left') {
                carousel.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                carousel.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        }
    </script>

<?php $bar_output = web_get_my_bar(); 
// echo '<pre>';
// print_r($bar_output);

?>
<!-- Modal Structure -->
<div class="modal" id="bar-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="shelf-container">
                <?php

                    foreach ($bar_output['shelves'] as $shelves) { ?>
                        <input type="text" class="shelfedit" ssid="<?= $shelves['shelf_id']; ?>"
                            style="text-align: center;color: #bda766; font-size: 17px; font-family: 'montserratbold'; border: transparent; text-align: left; width: 90%;"
                            value="<?= $shelves['shelf_name'] ?>" readonly="readonly">
                        <div class="shelf">
                            <button class="carousel-button left" onclick="scrollCarousel(this, 'left')"><img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/left.png" /></button>
                            <div class="carousel" shelf_id="<?= $shelves['shelf_id']; ?>">
                                <?php
                                $i = 1;
                                foreach ($shelves['products'] as $bottle) {
                                  

                                    ?>
                                    <div class="bottle" data-id="<?= $i ?>"
                                        pid="<?= isset($bottle['product_id']) ? $bottle['product_id'] : 0 ?>">
                                        <?php if (isset($bottle['product_id']) && ($bottle['product_id'] != 0)) { ?>
                                            <img src="<?= isset($bottle['product_image']) ? $bottle['product_image'] : get_stylesheet_directory_uri() . '/assets/images/icons/product.jpg' ?>"
                                                alt="Bottle 1"
                                                onerror="this.onerror=null; this.src='<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/product.jpg';">
                                        <?php } else { ?>
                                            <a href="javascript:void(0);" shelf_id ="<?= $shelves['shelf_id']; ?>" weight="<?php echo $i; ?>" id="add_to_shelf">
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

                            <button class="carousel-button right" onclick="scrollCarousel(this, 'right')"><img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/right.png" /></button>
                        </div>

                        <?php
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<script>
$(document).on('click', '#add_to_shelf', function (e) {
    e.preventDefault(); // Prevent the default action of the link
    e.stopPropagation(); // Stop event propagation

    $('.btn-add-to-bar').html('Add to Bar <i class="fa fa-spinner fa-spin"></i>');

    var pid = <?= $_SESSION['product_id'] ?>;
    var shelf_id = $(this).attr('shelf_id');
    var weight = $(this).attr('weight');

    if (!$(this).hasClass('nologinaction')) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: site_script_object.ajaxurl,
            data: {
                'action': 'ajaxbarlist',
                'product_id': pid,
                'weight': weight,
                'shelf_id': shelf_id,
                'nonce': site_script_object.nonce,
            },
            success: function (data) {
                $('.btn-add-to-bar').html('Added to Bar');

                // Manually hide the modal and remove it from DOM
                $('#bar-preview').fadeOut(300, function() {
                    $(this).removeClass('in').hide(); // Remove 'in' class and hide
                    $('body').removeClass('modal-open'); // Remove modal-open class from body
                    $('.modal-backdrop').remove(); // Remove the backdrop
                    $('#bar-preview').remove(); // Completely remove the modal from DOM
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
});
</script>

