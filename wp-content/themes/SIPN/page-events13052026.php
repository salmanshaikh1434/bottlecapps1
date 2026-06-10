<?php
$val = $_GET['myVar'];
/**
 * Template Name: SIPN Eventss
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
$search_key = sanitize_text_field($_POST['es']);
?>
<article class="col-md-10"> 
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container pg-events">
				<div class="col-md-12">
					<h1 class="heading-main-events">Events</h1>
					<h2 style="display: none;">Bourbon tasting Events</h2>
					<h2 style="display: none;">Bourbon events</h2>
				</div>
				<div class="col-md-12">
					<div class="search-upc mtopminus events-results">
						<form method="POST">
							<div class="input-search">
								<input type="text" class="events_search"
									placeholder="Search by event name or city or state" id="eventsearch" name="es"
									value="<?php echo $search_key; ?>">
								<i class="fa fa-search icon-search" aria-hidden="true"></i>
								<div class="result-sec events-results-dropdown"></div>
								<div class="result-zero" style="display:none;"> 0 Results.</div>
							</div>
							<!--
							 <div class="btn-upc">
								 <button type="submit" class="btn-search">Search</button>
							 </div>
-->
						</form>
					</div>
					<div class="search-sort mtopminus">
						<div class="free-switch">
							<div class="onoff">
								<span>Free Events</span>
								<label class="switch">
									<input type="hidden" id="op" value="<?php echo $val; ?>">
									<input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No"
										class="check">
									<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
				<div class="events-total-div">
					<div class="col-md-12">
						<?php
						// $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						// $args = array(
						// 	'post_type' => 'events',
						//     'post_status' => 'publish',
						// 	'posts_per_page' => 6,
						// 	'meta_key' => 'event_start_date',
						// 	'orderby' => 'meta_value_num',
						// 	'order' => 'ASC',
						// 	'meta_query' => array(
						// 	'relation' => 'OR', 
						// 	array(
						// 		'key' => 'event_start_date',
						// 		'value' => date("Ymd"), // date format error
						// 		'compare' => '>='
						// 	) ,
						// 	array(
						// 		'key' => 'event_end_date',
						// 		'value' => date("Ymd"), // date format error
						// 		'compare' => '>='
						// 	)
						// 	),
						// 	'paged' => $paged
						// );
						
						// if($search_key){
						// 	$args['s'] = $search_key;
						// }
						
						// $query = new WP_Query( $args );
						$keyword = $search_key;
						$keyword = esc_sql(sanitize_text_field($keyword));
						$query_cond = "";
						if ($keyword) {

							$query_cond .= " OR (";
							$query_cond .= " pm.meta_key='event_venue' AND pm.meta_value LIKE '%$keyword%' ";
							$query_cond .= ") ";
						}
						if ($val == 'free') {
							$query_cond11 = "AND ( pm.meta_key = 'event_price' AND pm.meta_value ='' )";
						}
						$day = date('Ymd');
						$query = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  ((p.post_title LIKE '%$keyword%' OR REPLACE(post_title, \"'\", '') LIKE '%$keyword%') $query_cond )  AND p.post_type='events' AND p.post_status = 'publish' )";
						$products = $wpdb->get_results($query);
						foreach ($products as $key => $event) {
							$arr[] = $event->ID;
						}
						$query1 = "(SELECT DISTINCT p.ID FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE  (( pm.meta_key = 'event_start_date' AND pm.meta_value >=$day ) OR ( pm.meta_key = 'event_end_date' AND pm.meta_value >=$day ) )  AND p.post_type='events' AND p.post_status = 'publish')";
						$products1 = $wpdb->get_results($query1);
						foreach ($products1 as $key => $event1) {
							$arr1[] = $event1->ID;
						}
						if ($arr1 == '') {
							$arr1 = array();
						}
						if ($arr == '') {
							$arr = array();
						}
						//echo "<pre>";print_r($arr1);exit;
						$result = array_intersect($arr, $arr1);
						//print_r($result);exit;
						$total = count($result);

						if ($total):
							foreach ($result as $event3) {
								$products_query = $wpdb->prepare("SELECT DISTINCT p.*,pm.* FROM wp_posts p LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE p.ID=$event3 $query_cond11 GROUP BY post_title");
								//print_r($products_query);exit;
								$events1 = $wpdb->get_results($products_query);
								$c = count($events1);
								$aas[] = $c;
								//print_r($c);
						
								foreach ($events1 as $key => $event) {
									?>

									<div class="col-md-4">
										<div class="events-block">
											<?php

											$event_image_url = get_the_post_thumbnail_url($event->ID, 'full');
											?>
											<a href="https://sipnbourbon.com/event/<?php echo $event->post_name; ?>">
												<div class="evnt-img" <?php if ($event_image_url) { ?>style="background-image:url('<?php echo $event_image_url; ?>');" <?php } ?>>

												</div>
												<div class="evnt-content">
													<h2><?php echo $event->post_title; ?></h2>

													<ul>
														<li style="color:#ffffff">Date:
															<?php echo date('jS M Y', strtotime(get_post_meta($event->ID, 'event_start_date', true)));
															echo ' - ';
															echo date('jS M Y', strtotime(get_post_meta($event->ID, 'event_end_date', true))); ?>
														</li>
														<?php $location = get_post_meta($event->ID, 'event_venue', true);
														if ($location['address']) { ?>
															<br>
															<li><a target="_blank"
																	href="https://www.google.com/maps/search/?api=1&query=<?php echo $location['lat']; ?>,<?php echo $location['lng']; ?>&zoom=<?php echo $location['zoom']; ?>">Location:
																	<?php echo $location['city'] . ", " . $location['state_short'] . ", " . $location['country']; ?></a>
															</li><?php } ?>
													</ul>
												</div>
											</a>
										</div>
									</div>
								<?php }
							}
							$asas = array_sum($aas);
							if ($asas == 0 || $asas == '') {
								echo "Currently we are not organizing any events";
							} ?>
							<!-- end loop -->


							<div class="pagination">
								<?php
								echo paginate_links(array(
									'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
									'total' => $query->max_num_pages,
									'current' => max(1, get_query_var('paged')),
									'format' => '?paged=%#%',
									'show_all' => false,
									'type' => 'plain',
									'end_size' => 2,
									'mid_size' => 1,
									'prev_next' => true,
									'prev_text' => sprintf('<i></i> %1$s', __('<<', 'text-domain')),
									'next_text' => sprintf('%1$s <i></i>', __('>>', 'text-domain')),
									'add_args' => false,
									'add_fragment' => '',
								));
								?>
							</div>


							<?php wp_reset_postdata(); ?>

						<?php else: ?>
							<p><?php _e('Sorry, currently we are not organizing any events.'); ?></p>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">

			$(document).ready(function () {
				var v = $('#op').val();

				//alert(v);
				if (v == 'free') {
					$(".check").prop("checked", true);
				} else {
					$(".check").prop("checked", false);
				}


				$('body').on('click', '.check', function () {
					var checkStatus = this.checked ? 'ON' : 'OFF';
					if (checkStatus == 'ON') {
						$myVar = 'free';
					} else {

						$myVar = '';
					}
					window.location.href = "https://sipnbourbon.com/events/?myVar=" + $myVar;

				});
			});

		</script>
		<?php sipn_footer(); ?>