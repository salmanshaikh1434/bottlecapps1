<?php
/**
 * Template Name: SIPN Rating
 */
get_header();

// --- 1. PREPARE DATA ---
$user_id = get_current_user_id();
$edit_pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
$view_pid = isset($_GET['view_pid']) ? intval($_GET['view_pid']) : 0;

$view_mode = 'empty'; // Default
$product_data = null;
$rating_data = null;
$my_history = [];

global $wpdb;
$table_ratings = $wpdb->prefix . 'ratings';
$table_posts = $wpdb->prefix . 'posts';

// SCENARIO A: EDIT/SUBMIT MODE (?pid=123)
if ($edit_pid > 0) {
    $product_data = get_post($edit_pid);
    $rating_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ratings WHERE user_id = %d AND product_id = %d", $user_id, $edit_pid));
    if ($product_data && $product_data->post_type === 'product') {
        $view_mode = 'form';
        // $rating_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ratings WHERE user_id = %d AND product_id = %d", $user_id, $edit_pid));
        // echo json_encode($rating_data);exit;
    }
    if ($edit_pid > 0 && $rating_data) {
        $view_mode = 'form';
    }

}
// SCENARIO B: DETAIL VIEW MODE (?view_pid=123)
elseif ($view_pid > 0) {
    $product_data = get_post($view_pid);
    $rating_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ratings WHERE user_id = %d AND product_id = %d", $user_id, $view_pid));
    
    if ($product_data && $rating_data) {
        $view_mode = 'detail';
    }
}
// SCENARIO C: HISTORY LIST (Default)
else {
    $my_history = $wpdb->get_results($wpdb->prepare(
        "SELECT r.*, p.post_title, p.ID as post_id 
         FROM $table_ratings r
         JOIN $table_posts p ON r.product_id = p.ID
         WHERE r.user_id = %d
         ORDER BY r.updated_at DESC",
        $user_id
    ));

    if (!empty($my_history)) {
        $view_mode = 'history';
    }
}
    // $rating_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ratings WHERE user_id = %d AND product_id = %d", $user_id, $edit_pid));
    // if ($edit_pid > 0 && $rating_data) {    
    //     $my_history = $wpdb->get_results($wpdb->prepare(
    //         "SELECT r.*, p.post_title, p.ID as post_id 
    //         FROM $table_ratings r
    //         JOIN $table_posts p ON r.product_id = p.ID
    //         WHERE r.user_id = %d
    //         ORDER BY r.updated_at DESC",
    //         $user_id
    //     ));
    //     $view_mode = 'history';
    // }
?>

<style>
    /* --- General Layout --- */
    .section-view { display: none; }
    .section-view.active { display: block; }
    
    /* --- Star Inputs --- */
    .star-rating-input .star { font-size: 32px; cursor: pointer; color: #000; padding: 0 2px; line-height: 1; }
    .star-rating-input .star.active { color: #c5a059; }
    
    /* --- History Grid --- */
    .product-listing { 
        background: #fff; border-radius: 15px; padding: 20px; 
        text-align: center; margin-bottom: 30px; cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.2s;
    }
    .product-listing:hover { transform: translateY(-5px); }
    .rating-prod img { max-height: 200px; width: auto; margin-bottom: 15px; }
    .circle {
        width: 50px; height: 50px; line-height: 50px;
        border-radius: 50%; background: #c5a059; color: #fff;
        text-align: center; font-weight: bold; font-size: 18px;
        margin: 10px auto;
    }

</style>

<article class="col-md-10">
    <div class="wrapper-top">
        <div class="wrapper-bottom">
            <div class="container">
                <div class="rating-logo">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-rating.png" alt="rating">
                </div>
                
                <section class="bg-rating">

                    <div class="section-view <?php echo ($view_mode === 'empty') ? 'active' : ''; ?>">
                        <div style="text-align:center;">
                            <h3>You don’t have any bottle to rate</h3>
                            <h4>Try searching one or click on “<span>Rate</span>” in the product page</h4>
                        </div>
                    </div>

                    <div class="section-view <?php echo ($view_mode === 'form') ? 'active' : ''; ?>">
                        <?php if ($product_data): 
                            $img_url = get_the_post_thumbnail_url($product_data->ID, 'medium') ?: get_stylesheet_directory_uri().'/assets/images/img-bottle-rating.png';
                            $nose = $rating_data ? $rating_data->rating_nose : 0;
                            $palate = $rating_data ? $rating_data->rating_palate : 0;
                            $finish = $rating_data ? $rating_data->rating_finish : 0;
                            $value = $rating_data ? $rating_data->rating_value : 0;
                            // $experience = $rating_data ? $rating_data->rating_experience : 0;
                            $notes = $rating_data ? $rating_data->tasting_notes : '';
                        ?>
                        <div class="rating-form">
                            <h2><?php echo esc_html($product_data->post_title); ?></h2>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="rating-border">
                                        <div class="rating-prod">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="bottle">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <div class="d-flex">
                                            <div class="rate-product">
                                                <p><strong>Nose</strong> Aroma & Bouquet</p>
                                                <div class="star-rating-input">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <span class="star <?php echo $i <= $nose ? 'active' : ''; ?>" data-val="<?php echo $i; ?>">★</span>
                                                    <?php endfor; ?>
                                                    <input type="hidden" id="input_nose" value="<?php echo $nose; ?>">
                                                </div>
                                            </div>
                                            <div class="rate-product">
                                                <p><strong>Palate</strong> Taste & Mouthfeel</p>
                                                <div class="star-rating-input">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <span class="star <?php echo $i <= $palate ? 'active' : ''; ?>" data-val="<?php echo $i; ?>">★</span>
                                                    <?php endfor; ?>
                                                    <input type="hidden" id="input_palate" value="<?php echo $palate; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rate-product">
                                                <p><strong>Finish</strong> Aftertaste</p>
                                                <div class="star-rating-input">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <span class="star <?php echo $i <= $finish ? 'active' : ''; ?>" data-val="<?php echo $i; ?>">★</span>
                                                    <?php endfor; ?>
                                                    <input type="hidden" id="input_finish" value="<?php echo $finish; ?>">
                                                </div>
                                            </div>
                                            <div class="rate-product">
                                                <p><strong>Value</strong> Price vs. Quality</p>
                                                <div class="star-rating-input" id="finish-stars">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <span class="star <?php echo $i <= $value ? 'active' : ''; ?>" data-val="<?php echo $i; ?>">★</span>
                                                    <?php endfor; ?>
                                                    <input type="hidden" id="input_value" value="<?php echo $value; ?>">
                                                </div>
                                            </div>  
                                        </div>

                                        <!-- <input type="hidden" id="input_experience" value="3">
                                        <input type="hidden" id="input_value" value="3"> -->
                                    </div>
                                    <div class="form-group">
                                        <textarea id="input_notes" type="text" placeholder="Share your thoughts on this Bourbon...."><?php echo esc_textarea($notes); ?></textarea>
                                    </div>
                                    
                                    <button type="button" id="btn-submit-rating" data-pid="<?php echo $product_data->ID; ?>">Submit Rating</button>
                                    <div id="api-message" style="margin-top:10px; font-weight:bold;"></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="section-view <?php echo ($view_mode === 'detail') ? 'active' : ''; ?>">
                        <?php if ($product_data && $rating_data): 
                            $d_img = get_the_post_thumbnail_url($product_data->ID, 'medium') ?: get_stylesheet_directory_uri().'/assets/images/img-bottle-rating.png';
                        ?>
                        <div class="rating-review">
                            <a href="<?php echo esc_url(get_permalink()); ?>" style="display:inline-block; margin-bottom:20px; color:#999; text-decoration:none;">&larr; Back to My Ratings</a>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="product-listing" style="cursor:default;">
                                        <div class="rating-border">
                                            <div class="rating-prod">
                                                <img src="<?php echo esc_url($d_img); ?>" alt="rating">
                                            </div>
                                        </div>
                                        <p><strong><?php echo esc_html($product_data->post_title); ?></strong></p>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="review-card">
                                        <div class="review-box">
                                            
                                            <div class="rating-revs">
                                                <div class="circle"><?php echo esc_html($rating_data->rating_nose); ?></div>
                                                <div class="rev-cat">Nose</div>
                                            </div>
                                            <div class="rating-revs">
                                                <div class="circle"><?php echo esc_html($rating_data->rating_palate); ?></div>
                                                <div class="rev-cat">Palate</div>
                                            </div>
                                            <div class="rating-revs">
                                                <div class="circle"><?php echo esc_html($rating_data->rating_finish); ?></div>
                                                <div class="rev-cat">Finish</div>
                                            </div>
                                            <div class="rating-revs">
                                                <div class="circle"><?php echo esc_html($rating_data->rating_value); ?></div>
                                                <div class="rev-cat">Value</div>
                                            </div>
                                            
                                        </div>
                                         <div class="review-desc-block">
                                                <div class="review-desc">
                                                    <p><?php echo nl2br(esc_html($rating_data->tasting_notes)); ?></p>
                                                    
                                                    <p>
                                                        Overall Rating: <?php echo esc_html($rating_data->rating_overall); ?> / 5
                                                    </p>
                                                </div>

                                                <div>
                                                    <a href="?pid=<?php echo $product_data->ID; ?>" class="btn-edit-rating">Edit Rating</a>
                                                </div>
                                            </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="section-view <?php echo ($view_mode === 'history') ? 'active' : ''; ?>">
                        <div class="rating-review">
                            <h3 style="margin-bottom:20px; text-align:center;">My Ratings</h3>
                            <div class="row">
                                <?php foreach ($my_history as $item): 
                                    $h_img = get_the_post_thumbnail_url($item->post_id, 'medium') ?: get_stylesheet_directory_uri().'/assets/images/img-bottle-rating.png';
                                    $global_count = (int) get_post_meta($item->post_id, '_product_rating_count', true);
                                ?>
                                <div class="col-md-4">
                                    <div class="product-listing" onclick="window.location.href='?view_pid=<?php echo $item->post_id; ?>'">
                                        <div class="rating-border">
                                            <div class="rating-prod">
                                                <img src="<?php echo esc_url($h_img); ?>" alt="rating">
                                                <p>
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/icon-info.png" alt="info" style="width:12px; height:12px; display:inline; margin:0;"> 
                                                    <?php echo $global_count; ?> users rated
                                                </p>
                                            </div>
                                        </div>
                                        <div class="circle">
                                            <?php echo esc_html($item->rating_overall); ?>
                                        </div>
                                        <p><?php echo esc_html($item->post_title); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
</article>

<div class="buy-now-mtop">
    <?php sipn_footer();?>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    
    // 1. Star Selection
    $('.star-rating-input .star').on('click', function() {
        var value = $(this).data('val');
        var parent = $(this).parent();
        parent.find('input').val(value);
        parent.find('.star').each(function() {
            $(this).toggleClass('active', $(this).data('val') <= value);
        });
    });

    // 2. API Submission
    $('#btn-submit-rating').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        var pid = btn.data('pid');
        var msgDiv = $('#api-message');

        var payload = {
            product_id: pid,
            nose: $('#input_nose').val(),
            palate: $('#input_palate').val(),
            finish: $('#input_finish').val(),
            // experience: $('#input_experience').val(),
            value: $('#input_value').val(),
            notes: $('#input_notes').val()
        };

        if (payload.nose == 0 || payload.palate == 0 || payload.finish == 0) {
            msgDiv.css('color', 'red').text('Please rate Nose, Palate, and Finish.');
            return;
        }

        btn.text('Submitting...').prop('disabled', true);

        $.ajax({
            url: '/wp-json/ratings/v1/submit',
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce("wp_rest"); ?>');
            },
            data: JSON.stringify(payload),
            contentType: 'application/json',
            success: function(response) {
                //msgDiv.css('color', 'green').text('Rating submitted successfully!');
                btn.text('Saved');
                // Redirect to Detail View of this product
                setTimeout(function() {
                    window.location.href = '?view_pid=' + pid; 
                }, 500);
            },
            error: function(err) {
                btn.text('Submit Rating').prop('disabled', false);
                var msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : "Error submitting.";
                //msgDiv.css('color', 'red').text(msg);
            }
        });
    });
});
</script>