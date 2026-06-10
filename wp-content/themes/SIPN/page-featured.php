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
<?php get_header(); ?>

<?php
global $wpdb;
$products_per_page = 9;
$page = get_query_var('paged') ? get_query_var('paged') : 1;
$product_visibility_term_ids = wc_get_product_visibility_term_ids();
$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'order' => 'DESC',
	'paged' => $page,
	'numberposts' => $products_per_page,
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
];

$total_args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'order' => 'DESC',
	'numberposts' => -1,
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
];
$total_products_list = get_posts($total_args);

$products = get_posts($args);

$total_products = count($total_products_list);
$no_of_pages = ceil($total_products / $products_per_page);
?>
<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container custom-container">
				<div class="col-md-12">
					<h1>Best Sellers</h1>
				</div>

				<div class="col-md-12">
					<?php
					if (count($products) > 0) {

						foreach ($products as $product) {
							$the_product = wc_get_product($product->ID);
							$prod_url = get_the_post_thumbnail_url($product->ID, 'medium');
							if (!$prod_url)
								$prod_url = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
							?>

							<div class="col-md-4">
								<a href="<?php echo get_permalink($product->ID); ?>">
									<div class="sr-img">
										<div class="col-md-5">
											<img src="<?php echo $prod_url; ?>">
										</div>
										<div class="col-md-7">
											<h3><?php echo $product->post_title; ?></h3>
											<div class="rating">
												<ul>
													<?php for ($i = 1; $i <= round($the_product->average_rating); $i++) { ?>
														<li><img
																src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-after.png">
														</li>
													<?php } ?>
													<?php for ($j = 1; $j <= 5 - round($the_product->average_rating); $j++) { ?>
														<li><img
																src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-before.png">
														</li>
													<?php } ?>
												</ul>
											</div>
											<div class="price">$<?php echo $the_product->price; ?></div>
											<p><?php $de = strip_tags($the_product->description);
											echo substr($de, 0, 60);
											if (strlen($the_product->description) > 60) {
												echo "...";
											} ?>
											</p>
										</div>
									</div>
								</a>
							</div>

						<?php }
					} else {
						echo "No products found on your wishlist.";
					} ?>

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

						<div class="col-md-12 col-sm-8">
							<div class="page-navigation">
							<?php if($no_of_pages>1){?>
						<ul class="pagination">
						  <!-- <li class="page-item <?php if($page <= 1){?>disabled<?php } ?>">
							<a aria-label="First" class="page-link prev_next" href="<?php echo "/featured-products/page/1";?>" tabindex="-1" page="1">
							  <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
							</a>
						  </li> -->
						  <?php if($page > 1){ ?>
						  <li class="page-item <?php if($page <= 1){?>disabled<?php } ?>">
							<a aria-label="Previous" class="page-link prev_next" href="<?php echo "/featured-products/page/".($page-1);?>" tabindex="-1" page="<?php echo $page-1; ?>">
							  <span aria-hidden="true"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i><span class="prev"> Previous</span></span>
							</a>
						  </li>
						  <?php } ?>
						  <li class="page-item <?php if($page == 1){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/featured-products/page/1/";?>"> 1 
							  <span class="sr-only">(current)</span>
							</a>
						  </li>
						  <?php if($page>=5){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>

						  <?php for($i=$page-2; $i<=$page-1;$i++){ if($i>1){?>
							<li class="page-item <?php if($page == $i){echo 'active';}?>">
							<a class="page-link <?php echo $i;?>" href="<?php echo "/featured-products/page/".$i."/";?>"> <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>
						  <?php for($i=$page; $i<=$page+2 && $i<$no_of_pages;$i++){ if($i>1){?>
							<li class="page-item <?php if($page == $i){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/featured-products/page/".$i."/";?>"> <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>

						  <?php if($page<$no_of_pages-3){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>
						  <li class="page-item <?php if($page == $no_of_pages){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/featured-products/page/".$no_of_pages."/";?>"> <?php echo $no_of_pages;?> </a>
						  </li>
						  <li class="page-item  <?php if($page >= $no_of_pages){?>disabled<?php } ?>">
							<a aria-label="Next" class="page-link prev_next" href="<?php if($page >= $no_of_pages){$a=$no_of_pages; } else {$a=$page+1;} echo "/featured-products/page/".($a)."/";?>" page="<?php echo $page+1; ?>">
							  <span aria-hidden="true"><span class="next">Next </span><i class="glyphicon glyphicon-arrow-right" aria-hidden="true"></i></span>
							</a>
						  </li>
						</ul>
						<?php } ?>
							</div>
						</div>

					<?php } ?>
				</div>


			</div>
		</div>
		<?php sipn_footer(); ?>


