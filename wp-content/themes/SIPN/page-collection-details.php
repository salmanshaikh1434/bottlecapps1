<?php
/**
 * Template Name: SIPN Collection Details
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */

global $wpdb;

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'price';
$type = isset($_GET['sort_type']) ? $_GET['sort_type'] : 'ASC';

// Get the current URL path
$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_segments = explode('/', trim($url_path, '/'));
$val = end($path_segments);
$str = str_replace("-", " ", $val);
$query = $wpdb->prepare("SELECT * FROM wp_collections WHERE collection_orgname = %s", $str);

$masterdata = $wpdb->get_results($query);
$name = $masterdata[0]->collection_name;




?>
<?php get_header(); ?>

<style type="text/css">
	body {font-family: Arial, Helvetica, sans-serif; margin: 0;}
	.copied_coll {display: block;position: absolute;top: -16px;right: -24px;width: 89px;background: white;color: #19191b;font-size: 13px;}
	.ptop25 {padding-top: 25px;}
	.spr-dropbtn {background-color: transparent;padding: 0px;border: none;cursor: pointer;}
	.sort-price-rating {position: relative;	display: block;	text-align: right; padding-right: 65px; width: 100%;}
	.spr-content {display: none; position: absolute; right: 62px; min-width: 123px;	text-align: left;box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);z-index: 1;border-radius: 8px;	background-color: #262626;}
	.spr-content a {color: #fff;padding: 12px 16px;	text-decoration: none;display: block;}
	.spr-content a:hover {background-color: #3C3C3C;}
	.spr-content a:hover:first-child {	border-radius: 8px 8px 0 0;	}
	.spr-content a:hover:last-child {border-radius: 0px 0px 8px 8px;}
	.sort-price-rating:hover .spr-content {	display: block;	}
	.sort-price-rating:hover .spr-dropbtn {	background-color: transparent;}
	.sort-icon {font-size: 12px;margin-left: 5px;}
	.asc::after {
		content: '▲';
		/* Up arrow */
	}
	.desc::after {
		content: '▼';
		/* Down arrow */
	}

	@media screen and (max-width: 1440px) and (min-width: 1200px) {
		.sort-price-rating {padding-right: 45px;}
		.spr-content {right: 42px;}
	}

	@media screen and (max-width: 1199px) and (min-width: 992px) {
		.sort-price-rating {padding-right: 45px;}
		.spr-content {right: 42px;}
	}

	@media screen and (max-width: 991px) and (min-width: 768px) {
		.sort-price-rating {padding-right: 75px;}
		.spr-content {right: 72px;}
	}

	@media screen and (max-width: 767px) {
		.sort-price-rating {padding-right: 10%;}
		.spr-content {right: 35px;}
	}
	​@media screen and (max-width: 480px) {
		.sort-price-rating {padding-right: 10% !important;}
		.spr-content {right: 29px;}
	}
</style>

<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom ptop25">
			<div class="container">
				<div class="col-md-12">
					<span class="collections-back">
						<a href="/bourbon-collection"><img
								src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-menu-slider.png"></a>
					</span>

					<h1 class="collections-heading"><?php echo $name; ?> <span class="collections-author">By:
							<?php echo $masterdata[0]->author; ?></span> </h1>
					<h2 style="display: none;">Must-Have Bourbon Whiskeys for Thanksgiving Celebration</h2>




					<div class="share_collections">
						<span class="sharecoll"><i class="fas fa-share-alt"></i></span>
						<div class="share_collections_icons" id="share_collections_icons" style="display:none;">
							<ul>
								<li>
									<a href="https://www.facebook.com/sharer/sharer.php?text=Hey, checkout this Collection&amp;u=<?php echo site_url(); ?>/bourbon-collection/<?php echo $val; ?>"
										target="_blank">
										<i class="fa-brands fa-facebook-f" style="font-style: normal;"></i></a>
								</li>
								<li><a href="https://twitter.com/messages/compose?text=Hey, checkout this Collection <?php echo site_url(); ?>/bourbon-collection/<?php echo $val; ?>"
										target="_blank">
										<!-- <i class="fab fa-twitter"></i> -->
										<img src="/wp-content/themes/SIPN/assets/images/icon-twitter-gold.png">
									</a>
								</li>
								
								<li><a href="https://api.whatsapp.com/send?text=Hey, checkout this Collection <?php echo site_url(); ?>/bourbon-collection/<?php echo $val; ?>" data-action="share/whatsapp/share" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
								<li><a class="copy-cls_coll" href="javascript:void(0);"
										link="<?php echo site_url(); ?>/bourbon-collection/<?php echo $val; ?>"><i
											class="fas fa-copy"></i></a></li>
								<!-- <li><a class="copy-cls" href="javascript:void(0);" link="<?php //echo site_url(); ?>/bourbon-collections/<?php //echo $val; ?>"><i class="fas fa-share-alt"></i></a></li> -->

							</ul>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="col-md-8 colle">

						<div class="sort-price-rating">
							<button class="spr-dropbtn"><svg width="115" height="22" viewBox="0 0 115 22" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M114.143 11H103.857C103.343 11 103 10.6 103 10C103 9.4 103.343 9 103.857 9H114.143C114.657 9 115 9.4 115 10C115 10.6 114.657 11 114.143 11Z"
										fill="#BDA766" />
									<path
										d="M114.125 16H108.875C108.35 16 108 15.6 108 15C108 14.4 108.35 14 108.875 14H114.125C114.65 14 115 14.4 115 15C115 15.6 114.65 16 114.125 16Z"
										fill="#BDA766" />
									<path
										d="M114.15 6H98.85C98.34 6 98 5.6 98 5C98 4.4 98.34 4 98.85 4H114.15C114.66 4 115 4.4 115 5C115 5.6 114.66 6 114.15 6Z"
										fill="#BDA766" />
								</svg>
							</button>
							<div class="spr-content">
								<a href="#" id="sort-price">Price <span id="price-icon">⬍</span></a>
								<a href="#" id="sort-rating">Rating <span id="rating-icon">⬍</span></a>
							</div>
						</div>

						<?php
						$pv = $masterdata[0]->collection_products;

						if (!empty($pv) && $pv !== 'NULL') {
							// Get product IDs
							$p = array_filter(array_map('trim', explode(',', $pv)), 'is_numeric');
							
							// Ensure we have valid product IDs
							if (!empty($p)) {
								// Create a custom query
								$query_args = array(
									'post_type' => 'product',
									'order'=>$type,
									'post__in' => $p, // Only include specified products
									'orderby' => 'post__in', // Preserve the order of IDs
									'meta_query' => array(),
									'posts_per_page' => -1,
								);

								// Apply sorting based on the filter
								if ($sort_by === 'price') {
									$query_args['orderby'] = 'meta_value_num';
									$query_args['meta_key'] = '_price';
									$query_args['meta_query'][] = array(
										'key' => '_price',
										'value' => 0,
										'compare' => '>=',
										'type' => 'NUMERIC',
									);
								} elseif ($sort_by === 'rating') { 
									    $query_args['meta_key'] = '_wc_average_rating';
									    $query_args['orderby'] = 'meta_value_num';
									    $query_args['order'] = $type; // 'asc' or 'desc' from URL param

									    $query_args['meta_query'][] = array(
									        'key' => '_wc_average_rating',
									        'compare' => 'EXISTS',
									    );
								}
								

								// Custom query
								$custom_query = new WP_Query($query_args);

								// Display the products
								if ($custom_query->have_posts()) {
									echo '<ul>';
									while ($custom_query->have_posts()) {
										$custom_query->the_post();
										global $product; // Access the global product object
										$img = get_the_post_thumbnail_url($product->get_id(), 'medium');
										if (!$img) {
											$img = get_stylesheet_directory_uri() . "/assets/images/default-bottle.jpg";
										}
										?>
										<!-- $average_rating = $product->get_average_rating(); -->
										<li>
											<a href="<?php echo get_permalink($product->get_id()); ?>"
												title="<?php echo esc_attr($product->get_name()); ?>">
												<img alt="<?php echo esc_attr($product->get_name()); ?>"
													src="<?php echo esc_url($img); ?>">
												<span class="prod-title"><?php echo esc_html($product->get_name()); ?></span>
												<div class="rating">
													<ul>
														<?php for ($i = 1; $i <= round((int) $product->get_average_rating()); $i++) { ?>
															<li><img
																	src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-after.png">
															</li>
														<?php } ?>
														<?php for ($j = 1; $j <= 5 - round((int) $product->get_average_rating()); $j++) { ?>
															<li><img
																	src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/rating-before.png">
															</li>
														<?php } ?>
													</ul>
												</div>
												<p>$<?php echo esc_html($product->get_price()); ?></p>
											</a>
										</li>
										<?php
									}
									echo '</ul>';
									wp_reset_postdata(); // Reset the global post object
								} else {
									echo 'No Products';
								}
							} else {
								echo 'No valid product IDs found.';
							}
						} else {
							echo 'No Products';
						}
						?>

					</div>

					<div class="col-md-4">



						<div class="collection-room"><img src="<?php echo $masterdata[0]->collection_image; ?>"
								alt="Old Fashioned Cocktail"></div>
						<div class="about-collection">

							<p> <?php echo $masterdata[0]->collection_long_description; ?> </p>
						</div>

					</div>



				</div>

				<script>
					function handleSortClick(event, sortBy) {
						event.preventDefault();

						var currentUrl = new URL(window.location.href);

						var currentSortBy = currentUrl.searchParams.get('sort_by');
						var currentSortType = currentUrl.searchParams.get('sort_type');

						var newSortType;
						if (currentSortBy === sortBy) {
							newSortType = (currentSortType === 'asc') ? 'desc' : 'asc';
						} else {
							newSortType = 'asc';
						}


						currentUrl.searchParams.set('sort_by', sortBy);
						currentUrl.searchParams.set('sort_type', newSortType);


						updateSortIcons(sortBy, newSortType);

						window.location.href = currentUrl.href;
					}


					function updateSortIcons(sortBy, sortType) {

						document.getElementById('price-icon').textContent = '⬍';
						document.getElementById('rating-icon').textContent = '⬍';


						if (sortBy === 'price') {
							document.getElementById('price-icon').textContent = (sortType === 'asc') ? '▲' : '▼';
						} else if (sortBy === 'rating') {
							document.getElementById('rating-icon').textContent = (sortType === 'asc') ? '▲' : '▼';
						}
					}


					document.getElementById("sort-price").addEventListener("click", function (event) {
						handleSortClick(event, 'price');
					});

					document.getElementById("sort-rating").addEventListener("click", function (event) {
						handleSortClick(event, 'rating');
					});


					window.onload = function () {
						var currentUrl = new URL(window.location.href);
						var currentSortBy = currentUrl.searchParams.get('sort_by');
						var currentSortType = currentUrl.searchParams.get('sort_type');
						if (currentSortBy && currentSortType) {
							updateSortIcons(currentSortBy, currentSortType);
						}
					};
				</script>


				<?php sipn_footer(); ?>