<?php
/**
 * Template Name: SIPN Search
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
$cur_user = wp_get_current_user();
if ($_POST['si'] != '') {
	update_user_meta($cur_user->data->ID, 'shelve_id', sanitize_text_field($_GET['si']));
	update_user_meta($cur_user->data->ID, 'weight', sanitize_text_field($_GET['w']));
} else {
	update_user_meta($cur_user->data->ID, 'shelve_id', sanitize_text_field($_GET['si']));
	update_user_meta($cur_user->data->ID, 'weight', sanitize_text_field($_GET['w']));
}

$product_visibility_term_ids = wc_get_product_visibility_term_ids();
$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'order' => 'ASC',
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
//print_r($products);

if (sanitize_text_field($_GET['s']) != '') {
	$per_page = 9;
	$page = sanitize_text_field($_GET['page']) ? sanitize_text_field($_GET['page']) : 1;

	$request_body = array(
		"keyword" => sanitize_text_field($_GET["s"]),
		'products_per_page' => $per_page,
		'page' => $page,
		'sort_type' => sanitize_text_field($_GET['sort_type']),
		'sort_by' => sanitize_text_field($_GET['sort_by'])
	);



	if ($_GET['sort_by'] == 'rating') {
		if ($_GET['rating_limit'] != '') {
			$request_body['rating_limit'] = sanitize_text_field($_GET['rating_limit']);
		}
	}

	if ($_GET['sort_by'] == 'price') {
		if ($_GET['price_limit'] != '') {
			$request_body['price_limit'] = sanitize_text_field($_GET['price_limit']);
		}
	}

	$search_args = array(
		"headers" => array("Content-Type" => "application/json"),
		"body" => json_encode($request_body)
	);
	//print_r($search_args);
	$url_endpoint = get_site_url() . "/wp-json/products/v2/list/";
	$res = wp_remote_post($url_endpoint, $search_args);
	$search_results = wp_remote_retrieve_body($res);
	$search_products = json_decode($search_results);


	$total_products = $search_products->total_products;
	$no_of_pages = ceil($total_products / $per_page);
}
?>
<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container">
				<div class="col-md-12">
					<!-- <h1 style="display: none;">Find bourbon - You love</h1> -->
					<h1 class="search-head">Search</h1>
					<h2 style="display: none;">Buy bourbon online</h2>
				</div>
				<?php if (sanitize_text_field($_GET['s']) != '') { ?>
					<div class="col-md-12">
						<form id="search_form" action="/" autocomplete='off'>
							<div class="search-upc">

								<div class="input-search">
									<input type="text" placeholder="Search Bourbons" id="search" name="s" required
										value="<?php echo stripslashes(sanitize_text_field($_GET['s'])); ?>"
										autocomplete="off">
									<i class="fa fa-search icon-search" aria-hidden="true"></i>
									<div class="result-sec"></div>
								</div>
								<div class="btn-upc">
									<button type="submit" class="btn-search">Search</button>
								</div>

							</div>
							<div class="search-sort">
								<div class="input-search">
									<select name="sort_by" id="sort_by" class="sort_by filter-check" autocomplete="off">
										<option value=""> Sort by </option>
										<option value="price" <?php if ($_GET['sort_by'] == 'price') {
											echo "selected='selected'";
										} ?>>Price</option>
										<option value="rating" <?php if ($_GET['sort_by'] == 'rating') {
											echo "selected='selected'";
										} ?>>Rating</option>
									</select>
									<i class="glyphicon glyphicon-menu-down icon-up" aria-hidden="true"></i>
								</div>
								<div class="btn-sort">
									<?php if ($_GET['sort_by'] == 'price') { ?>
										<div class="price-holder sort-type">
											<select name="sort_type" id="sort_type" class="btn-price filter-check">
												<option value=""> Sort Order </option>
												<option value="ASC" <?php if ($_GET['sort_type'] == 'ASC') {
													echo "selected='selected'";
												} ?>>Low to High</option>
												<option value="DESC" <?php if ($_GET['sort_type'] == 'DESC') {
													echo "selected='selected'";
												} ?>>High to Low</option>
											</select>
											<i class="fa fa-check" aria-hidden="true"></i>
										</div>
										<div class="price-holder price-limit">
											<select name="price_limit" id="price_limit" class="btn-price filter-check">
												<option value=""> Price Filter </option>
												<option value="0-25" <?php if ($_GET['price_limit'] == '0-25') {
													echo "selected='selected'";
												} ?>>$0 - $25</option>
												<option value="25-50" <?php if ($_GET['price_limit'] == '25-50') {
													echo "selected='selected'";
												} ?>>$25 - $50</option>
												<option value="50-75" <?php if ($_GET['price_limit'] == '50-75') {
													echo "selected='selected'";
												} ?>>$50 - $75</option>
												<option value="75-100" <?php if ($_GET['price_limit'] == '75-100') {
													echo "selected='selected'";
												} ?>>$75 - $100</option>
												<option value="100-" <?php if ($_GET['price_limit'] == '100-') {
													echo "selected='selected'";
												} ?>>$100 & above</option>
											</select>
											<i class="fa fa-check" aria-hidden="true"></i>
										</div>
									<?php } ?>
									<?php if ($_GET['sort_by'] == 'rating') { ?>
										<div class="rating-holder">
											<select name="rating_limit" id="rating_limit" class="btn-style filter-check">
												<option value=""> Rating Filter </option>
												<option value="1" <?php if ($_GET['rating_limit'] == '1') {
													echo "selected='selected'";
												} ?>>1 & above</option>
												<option value="2" <?php if ($_GET['rating_limit'] == '2') {
													echo "selected='selected'";
												} ?>>2 & above</option>
												<option value="3" <?php if ($_GET['rating_limit'] == '3') {
													echo "selected='selected'";
												} ?>>3 & above</option>
												<option value="4" <?php if ($_GET['rating_limit'] == '4') {
													echo "selected='selected'";
												} ?>>4 & above</option>
												<option value="5" <?php if ($_GET['rating_limit'] == '5') {
													echo "selected='selected'";
												} ?>>5</option>
											</select>
											<i class="fa fa-check" aria-hidden="true"></i>
										</div>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
				<?php } else { ?>
					<div class="col-md-6">
						<div class="search-container">
							<form action="/">
								<div class="input-search">
									<input type="text" placeholder="Search Bourbons" id="search" name="s"
										value="<?php echo stripslashes(sanitize_text_field($_GET['s'])); ?>"
										autocomplete="off" required>
									<i class="fa fa-search icon-search" aria-hidden="true"></i>
									<div class="result-sec"></div>
								</div>
								<button type="submit" class="search">Search</button>
							</form>
						</div>
					</div>
				<?php } ?>


				<?php

				if (sanitize_text_field($_GET['s']) != '') {

					if ($total_products > 0) {
						?>
						<div class="col-md-12">
							<?php
							foreach ($search_products->products as $product) {
								$prod_url = get_the_post_thumbnail_url($product->product_id, 'medium');

								if (!$prod_url)
									$prod_url = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';
								?>
								<div class="col-md-4">
									<a href="<?php echo get_permalink($product->product_id); ?>">
										<div class="sr-img">
											<div class="col-md-5">
												<img src="<?php echo $prod_url; ?>">
											</div>
											<div class="col-md-7">
												<h3><?php echo $product->product_title; ?></h3>
												<div class="rating">
													<ul>
														<?php for ($i = 1; $i <= round((int) $product->product_rating); $i++) { ?>
															<li><img
																	src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-after.png">
															</li>
														<?php } ?>
														<?php for ($j = 1; $j <= 5 - round((int) $product->product_rating); $j++) { ?>
															<li><img
																	src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-before.png">
															</li>
														<?php } ?>
													</ul>
												</div>
												<div class="price">$<?php echo $product->product_price; ?></div>
												<p><?php $md = substr($product->product_desc, 0, 60);
												echo strip_tags($md);
												if (strlen($product->product_desc) > 60) {
													echo "...";
												} ?>
												</p>
											</div>
										</div>
									</a>
								</div>

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
														<a aria-label="Previous" class="page-link prev_next" href="<?php if ($page <= 1) {
															echo 'javascript:void(0);';
														} else {
															echo modify_url(array('page' => $page - 1));
														} ?>" tabindex="-1" page="<?php echo $page - 1; ?>">
															<span aria-hidden="true"><i class="glyphicon glyphicon-arrow-left"
																	aria-hidden="true"></i> <span class="prev">Previous </span></span>
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
															<a class="page-link <?php echo $i; ?>"
																href="<?php echo modify_url(array('page' => $i)); ?>"> <?php echo $i; ?> </a>
														</li>
													<?php }
												} ?>
												<?php for ($i = $page; $i <= $page + 2 && $i < $no_of_pages; $i++) {
													if ($i > 1) { ?>
														<li class="page-item <?php if ($page == $i) {
															echo 'active';
														} ?>">
															<a class="page-link" href="<?php echo modify_url(array('page' => $i)); ?>">
																<?php echo $i; ?> </a>
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
													<a class="page-link"
														href="<?php echo modify_url(array('page' => $no_of_pages)); ?>">
														<?php echo $no_of_pages; ?> </a>
												</li>
												<li class="page-item  <?php if ($page >= $no_of_pages) { ?>disabled<?php } ?>">
													<a aria-label="Next" class="page-link prev_next" href=" <?php if ($page >= $no_of_pages) {
														$a = $no_of_pages;
														echo 'javascript:void(0);';
													} else {
														$a = $page + 1;
														echo modify_url(array('page' => $a));
													} ?>" page="<?php echo $page + 1; ?>">
														<span class="next"> Next </span><span aria-hidden="true"><i
																class="glyphicon glyphicon-arrow-right" aria-hidden="true"></i></span>
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
						<div>No products match the search criteria.</div>
					<?php }
				} else { ?>
					<div class="col-md-6" style="margin-bottom:200px;">
						<div class="find chat-detail">
							<div class="my-bar">
								<h2 class="trending">Trending</h2>
								<!--Slider-->
								<div id="slider">
									<div id="dots-con">
										<!--Controlling arrows-->
										<span class="controls" onclick="prevSlide(-1)" id="left-arrow"><i
												class="fa fa-angle-left" aria-hidden="true"></i></span>
										<?php
										if (count($products) > 0) {
											for ($i = 1; $i <= ceil(count($products) / 3); $i++) { ?>
												<span class="dot"></span>
											<?php }
										} ?>
										<span class="controls" id="right-arrow" onclick="nextSlide(1)"><i
												class="fa fa-angle-right" aria-hidden="true"></i></span>
									</div>


									<?php
									if (count($products) > 0) {
										$cnt = 0;
										foreach ($products as $product) {
											$the_product = wc_get_product($product->ID);
											$prod_url = get_the_post_thumbnail_url($product->ID, 'medium');
											if (!$prod_url)
												$prod_url = get_stylesheet_directory_uri() . '/assets/images/default-bottle.jpg';

											?>
											<?php if ($cnt != 0 && $cnt % 3 == 0) { ?>
												</ul>
											</div>
										<?php } ?>
										<?php if ($cnt == 0 || $cnt % 3 == 0) { ?>
											<div class="slide">
												<ul>
												<?php } ?>
												<?php if ($cnt == count($products)) { ?>
												</ul>
											</div>
										<?php } ?>
										<li><a href="<?php echo get_permalink($product->ID); ?>"
												title="<?php echo $product->post_title; ?>"><img
													alt="<?php echo $product->post_title; ?>" src="<?php echo $prod_url; ?>"><span
													class="prod-title"><?php echo $product->post_title; ?></span></a></li>

										<?php $cnt++;
										}
									} else {
										echo "No products found on your wishlist.";
									} ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<?php if (sanitize_text_field($_GET['s']) == '') { ?>
			<script>
				var slides = document.querySelectorAll(".slide");
				var dots = document.querySelectorAll(".dot");
				var index = 0;


				function prevSlide(n) {
					index += n;
					console.log("prevSlide is called");
					changeSlide();
				}

				function nextSlide(n) {
					index += n;
					changeSlide();
				}

				changeSlide();

				function changeSlide() {

					if (index > slides.length - 1)
						index = 0;

					if (index < 0)
						index = slides.length - 1;



					for (let i = 0; i < slides.length; i++) {
						slides[i].style.display = "none";

						dots[i].classList.remove("active");


					}

					slides[index].style.display = "block";
					dots[index].classList.add("active");



				}

			</script>
		<?php } ?>
		<?php sipn_footer(); ?>