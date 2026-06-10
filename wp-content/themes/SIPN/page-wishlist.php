<?php
/**
 * Template Name: SIPN Wishlist
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php
if (!is_user_logged_in()) {
  wp_redirect('/login');
  exit;
}
get_header(); ?>
<?php

global $wpdb;
$cur_user = wp_get_current_user();
$user_details = get_user_meta($cur_user->data->ID);

$existing_wishlist = $user_details['wishlist'][0];
//print_r($existing_wishlist); echo 'list';
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
      $all_products[$key]['product_rating'] = $the_product->average_rating;
      $all_products[$key]['product_rating_count'] = $the_product->review_count;

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
    $total_products = count($all_products);
    //print_r($all_products);
  }
}
?>
<article class="col-md-10">
  <div class="wrapper-top">
    <div class="wrapper-bottom">
      <div class="container wishlist-prod">
        <?php

        if ($total_products > 0) {
          ?>
          <div class="col-md-12">
            <?php
            foreach ($all_products as $product) {
              $prod_url = get_the_post_thumbnail_url($product['product_id'], 'medium');

              if (!$prod_url)
                $prod_url = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
              ?>
              <a href="<?php echo get_permalink($product['product_id']); ?>">
                <div class="col-md-4">
                  <div class="sr-img">
                    <div class="col-md-5">
                      <img src="<?php echo $prod_url; ?>">
                    </div>
                    <div class="col-md-7">
                      <h3><?php echo $product['product_title']; ?></h3>
                      <div class="rating">
                        <ul>
                          <?php for ($i = 1; $i <= round((int) $product['product_rating']); $i++) { ?>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-after.png"></li>
                          <?php } ?>
                          <?php for ($j = 1; $j <= 5 - round((int) $product['product_rating']); $j++) { ?>
                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-before.png"></li>
                          <?php } ?>
                        </ul>
                      </div>
                      <a href="javascript:void(0);" class="remove_to_wishlist" pid="<?php echo $product['product_id']; ?>"
                        wish="0"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-close.png"></a>
                      <div class="price">$<?php echo $product['product_price']; ?></div>
                      <p><?php $md = substr($product['product_desc'], 0, 60);
                      echo strip_tags($md);
                      if (strlen($product['product_desc']) > 60) {
                        echo "...";
                      } ?></p>
                    </div>
                  </div>
                </div>
              </a>

            <?php } ?>
          </div>
          <div class="col-md-12">
            <?php if ($no_of_pages > 1) { ?>
              <div class="paginate">
                <?php /*for($p=1; $p<=$no_of_pages; $p++){ 
                           $page_path = modify_url(array('page'=>$p));
                       ?>
                           <a href="<?php echo $page_path;?>"><?php echo $p;?></a>
                       <?php }*/ ?>
              </div>
              <?php
              $end = $page * $per_page;
              $start = $end - $per_page + 1;
              ?>

              <div class="col-md-12 col-sm-12 search-list-items">
                <div class="page-navigation">
                  <?php if ($no_of_pages > 1) { ?>
                    <ul class="pagination">
                      <!-- <li class="page-item <?php if ($page <= 1) { ?>disabled<?php } ?>">
                            <a aria-label="First" class="page-link prev_next" href="<?php echo modify_url(array('page' => 1)); ?>" tabindex="-1" page="1">
                              <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                            </a>
                          </li> -->
                      <?php if ($page > 1) { ?>
                        <li class="page-item <?php if ($page <= 1) { ?>disabled<?php } ?>">
                          <a aria-label="Previous" class="page-link prev_next"
                            href="<?php if ($page <= 1) {
                              echo 'javascript:void(0);';
                            } else {
                              echo modify_url(array('page' => $page - 1));
                            } ?>"
                            tabindex="-1" page="<?php echo $page - 1; ?>">
                            <span aria-hidden="true"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> <span
                                class="prev">Previous </span></span>
                          </a>
                        </li>

                      <?php } ?>
                      <li class="page-item <?php if ($page == 1) {
                        echo 'active';
                      } ?>">
                        <a class="page-link" href="<?php echo modify_url(array('page' => 1)); ?>"> 1
                          <span class="sr-only">(current)</span>
                        </a>
                      </li>
                      <?php if ($page >= 5) { ?>
                        <li class="page-item disabled">
                          <a class="page-link">...</a>
                        </li>
                      <?php } ?>

                      <?php for ($i = $page - 2; $i <= $page - 1; $i++) {
                        if ($i > 1) { ?>
                          <li class="page-item <?php if ($page == $i) {
                            echo 'active';
                          } ?>">
                            <a class="page-link <?php echo $i; ?>" href="<?php echo modify_url(array('page' => $i)); ?>">
                              <?php echo $i; ?> </a>
                          </li>
                        <?php }
                      } ?>
                      <?php for ($i = $page; $i <= $page + 2 && $i < $no_of_pages; $i++) {
                        if ($i > 1) { ?>
                          <li class="page-item <?php if ($page == $i) {
                            echo 'active';
                          } ?>">
                            <a class="page-link" href="<?php echo modify_url(array('page' => $i)); ?>"> <?php echo $i; ?> </a>
                          </li>
                        <?php }
                      } ?>

                      <?php if ($page < $no_of_pages - 3) { ?>
                        <li class="page-item disabled">
                          <a class="page-link">...</a>
                        </li>
                      <?php } ?>
                      <li class="page-item <?php if ($page == $no_of_pages) {
                        echo 'active';
                      } ?>">
                        <a class="page-link" href="<?php echo modify_url(array('page' => $no_of_pages)); ?>">
                          <?php echo $no_of_pages; ?> </a>
                      </li>
                      <li class="page-item  <?php if ($page >= $no_of_pages) { ?>disabled<?php } ?>">
                        <a aria-label="Next" class="page-link prev_next"
                          href=" <?php if ($page >= $no_of_pages) {
                            $a = $no_of_pages;
                            echo 'javascript:void(0);';
                          } else {
                            $a = $page + 1;
                            echo modify_url(array('page' => $a));
                          } ?>"
                          page="<?php echo $page + 1; ?>">
                          <span class="next"> Next </span><span aria-hidden="true"><i class="glyphicon glyphicon-arrow-right"
                              aria-hidden="true"></i></span>
                        </a>
                      </li>
                      <!--  <li class="page-item <?php if ($page >= $no_of_pages) { ?>disabled<?php } ?>">
                            <a aria-label="Last" class="page-link prev_next" href="<?php echo modify_url(array('page' => $no_of_pages)); ?>"  page="<?php echo $no_of_pages; ?>">
                              <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                            </a>
                          </li> -->
                    </ul>
                  <?php } ?>
                </div>
              </div>

            <?php } ?>
          </div>

        <?php } else { ?>
          <div>No products are in your wish list.</div>
        <?php } ?>
      </div>

      <?php sipn_footer(); ?>