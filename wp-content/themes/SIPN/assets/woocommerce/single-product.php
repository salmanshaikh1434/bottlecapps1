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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( ); ?>
<article>
	<div class="container">
                    <div class="col-md-4  text-center">
                        <div class="img-prodetail">
							<?php
							global $post;
							$the_product = wc_get_product();
							$prod_url = get_the_post_thumbnail_url( $post->ID, 'full' );
							//print_r($the_product);
							//update_post_meta( $the_product->get_id(), '_wc_average_rating', 3.00 );
							?>
                            <img src="<?php if($prod_url){echo $prod_url;}else{ echo get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';}?>" alt="<?php echo $the_product->name;?>">
							
                      </div>
					  <div class="col-md-12 text-center">
                        <div><a href="<?php echo esc_url( add_query_arg( array('prod_id' => $the_product->sku, 'prid' => $the_product->id), site_url( '/buy-now/' ) ) )?>" class="buynow"><button class="search">Buy Now</button></a></div>
						<?php echo sipn_social_share(); ?>
						
                    </div>
                    </div>
                    <div class="col-md-8">
                        <!--<div class="col-md-12">
                            <div class="text-center prod-video">
                                 <img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/img-video.png">
                            </div>
                        </div>-->
                      <div class="chat-detail mtop60">
                        <div class="col-md-5"><h1><?php echo $the_product->name;?></h1>
                            <div class="price">$<?php echo $the_product->price;?></div>
                            <div class="rating"><ul>
								<?php for($i=1; $i<=round($the_product->average_rating);$i++){ ?>
                                <li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-after.png"></li>
								<?php } ?>
								<?php for($j=1; $j<=5-round($the_product->average_rating);$j++){ ?>
								<li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-before.png"></li>
								<?php } ?>
                            </ul></div>
                        </div>
                        <div class="col-md-7">
                            <!--<h3>Region Proof Age Tasting Notes</h3>-->
							<?php view_acf_field_for_single_product();?>
				<div class="product_info">
                            	<?php if($post->post_content){?><div class="desription"><div class="pr-info-item desc"><label>Descriptions123: </label><span><?php echo $post->post_content;?></span></div></div><?php } ?>
				<div class="upc-details"><?php echo display_pr_feild('UPC', 'productupc');?></div>
				</div>
                        </div>
                     </div>
                     <div class="col-md-12">
						<?php
						$add_to_bar = 1;
						$cur_user = wp_get_current_user();
						if(is_product_exists_bar($post->ID, $current_user->data->ID)){
							$add_to_bar = 0;
						}
						?>
                        <div class="add-bar"><a <?php if(!is_user_logged_in()){ ?>class="nologinaction" <?php } ?> link="<?php echo bbp_get_user_profile_url($current_user->data->ID);?>" href="javascript:void(0);" id="add_to_bar" pid="<?php echo $post->ID;?>" <?php if($add_to_bar){ ?>bar="1"<?php }else{ ?>bar="0"<?php }?>><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-add.png"><span><?php if($add_to_bar){ ?>Add to Bar<?php }else{ ?>Remove from Bar<?php } ?></span></a></div>
						<?php
						$add_to_wishlist = 1;
						$cur_user = wp_get_current_user();
						$user_details = get_user_meta($cur_user->data->ID);
						$existing_wishlist = $user_details['wishlist'][0];
						//print_r($existing_wishlist);
						if($existing_wishlist){
							$existing_wishlist_arr = maybe_unserialize($existing_wishlist);
							if(in_array($post->ID, $existing_wishlist_arr)){
								$add_to_wishlist = 0;
							}
						}
						 ?>
						 
                         <div class="add-wishlist"><a <?php if(!is_user_logged_in()){ ?>class="nologinaction" <?php } ?> href="javascript:void(0);" id="add_to_wishlist" pid="<?php echo $post->ID;?>" <?php if($add_to_wishlist){ ?>wish="1"<?php }else{ ?>wish="0"<?php }?>><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-wishlist.png"><span><?php if($add_to_wishlist){ ?>Add to Wishlist<?php }else{ ?>Remove from wishlist<?php } ?></span></a></div>
						 
                     </div>
                    </div>
                </div>



<?php
sipn_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
