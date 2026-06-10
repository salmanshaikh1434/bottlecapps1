<?php
/**
 * Template Name: SIPN Featured
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
 ?>
 <?php get_header();?>
 
<?php
global $wpdb;
$product_visibility_term_ids = wc_get_product_visibility_term_ids();
$args = [
	  'post_type' => 'product',
	  'post_status' => 'publish',
	  'order' => 'ASC',
	  'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
          'relation' => 'AND',
          array(
            'taxonomy' => 'product_visibility',
            'field'    => 'term_taxonomy_id',
            'terms'    => array( $product_visibility_term_ids['featured'] ),
          ),
          array(
            'taxonomy' => 'product_visibility',
            'field'    => 'term_taxonomy_id',
            'terms'    => array( $product_visibility_term_ids['exclude-from-catalog'] ),
            'operator' => 'NOT IN',
          ),
        ),
	];

$products = get_posts($args);
//print_r($products);
 ?>
 <article class="col-md-10">
            <div class="wrapper-bottom">
                <div class="container">
                    <div class="col-md-12">
                        <h1>Page you are looking for does not exists.</h1>
					</div>
                
                    <div class="col-md-12">
					<?php
					if(count($products)>0){
					foreach($products as $product){
					$the_product = wc_get_product( $product->ID );
					$prod_url = get_the_post_thumbnail_url( $product->ID, 'full' );
					?>
					<a href="<?php echo get_permalink($product->ID);?>">
                      <div class="col-md-4">
                        <div class="sr-img">
                            <div class="col-md-5">
                               <img src="<?php echo $prod_url;?>">
                            </div>
                            <div class="col-md-7">
                                <h3><?php echo $product->post_title;?></h3>
                                <div class="rating">
                                    <ul>
                                     <?php for($i=1; $i<=round((float)$product->average_rating);$i++){ ?>
									<li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-after.png"></li>
									<?php } ?>
									<?php for($j=1; $j<=5-round((float)$product->average_rating);$j++){ ?>
									<li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-before.png"></li>
									<?php } ?>
                                    </ul>
                                </div>
                                <div class="price">$<?php echo $the_product->price;?></div>
                                   <p><?php echo substr($the_product->description, 0, 60); if(strlen($the_product->description)>60){ echo "...";} ?></p>
                            </div>
                         </div>
                      </div>
					  </a>
					<?php }}else{ echo "No products found on your wishlist.";} ?>
                     
                    </div>
                </div>
				</div>
<?php sipn_footer();?>