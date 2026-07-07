<?php
/**
 * Template Name: SIPN Eventss
 *
 * The events listing page — bourbon tasting events only, USA only,
 * grouped by month, sorted by date ascending.
 *
 * @package Neve
 */

get_header();

/* ------------------------------------------------------------------
 * Inputs
 * ------------------------------------------------------------------ */
$search_key = isset( $_POST['es'] ) ? sanitize_text_field( $_POST['es'] ) : '';
$only_free  = ( isset( $_GET['myVar'] ) && $_GET['myVar'] === 'free' );
$today      = current_time( 'Y-m-d' );

/* ------------------------------------------------------------------
 * Configuration
 * ------------------------------------------------------------------ */
$us_states = [
	'AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA',
	'KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY',
	'NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY',
];

/* ------------------------------------------------------------------
 * Query: pull all published events, then filter in PHP
 * (avoids raw SQL and date-format mismatches)
 * ------------------------------------------------------------------ */
$args = [
	'post_type'      => 'events',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'no_found_rows'  => true,
];
if ( $search_key ) {
	$args['s'] = $search_key;
}
$query = new WP_Query( $args );

$tasting_events = [];

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();
		$title   = get_the_title();

		// Show all bourbon events: title mentions "bourbon", OR the event was
		// imported by the SIPN Bourbon Events plugin (Eventbrite sync).
		$is_imported = (bool) get_post_meta( $post_id, '_sipn_be_source', true );
		if ( ! $is_imported && stripos( $title, 'bourbon' ) === false ) {
			continue;
		}

		$start     = get_post_meta( $post_id, 'event_start_date', true );
		$end       = get_post_meta( $post_id, 'event_end_date',   true );
		$venue_raw = get_post_meta( $post_id, 'event_venue',      true );
		$state_raw = (string) get_post_meta( $post_id, 'event_state', true );
		$price     = get_post_meta( $post_id, 'event_price',      true );
		$book      = get_post_meta( $post_id, 'book_now',         true );

		// Venue can be either an ACF Google Map array OR a plain string
		$venue_state_short = '';
		if ( is_array( $venue_raw ) ) {
			$venue = $venue_raw['address']
				?? ( $venue_raw['name']
				?? trim( ( $venue_raw['city'] ?? '' ) . ', ' . ( $venue_raw['state_short'] ?? '' ), ', ' ) );
			$venue_state_short = $venue_raw['state_short'] ?? '';
		} else {
			$venue = (string) $venue_raw;
		}

		// Normalize state — handles "MA", "MA 02210, USA", "KY 40601", etc.
		$state = '';
		$candidates = [ $state_raw, $venue_state_short ];
		foreach ( $candidates as $candidate ) {
			$candidate = strtoupper( trim( $candidate ) );
			if ( $candidate === '' ) {
				continue;
			}
			// Try to extract leading 2-letter code (e.g. "MA 02210, USA" -> "MA")
			if ( preg_match( '/^([A-Z]{2})\b/', $candidate, $m ) && in_array( $m[1], $us_states, true ) ) {
				$state = $m[1];
				break;
			}
			// Try to find any US state code as a whole word
			foreach ( $us_states as $code ) {
				if ( preg_match( '/\b' . $code . '\b/', $candidate ) ) {
					$state = $code;
					break 2;
				}
			}
		}

		// USA only
		if ( ! in_array( $state, $us_states, true ) ) {
			continue;
		}

		// Future events only (use event_end_date if present, else event_start_date)
		$compare_date = $end ?: $start;
		if ( ! $compare_date ) {
			continue;
		}
		$compare_ts = strtotime( $compare_date );
		if ( ! $compare_ts || $compare_ts < strtotime( $today ) ) {
			continue;
		}

		// Free-events filter (price empty)
		if ( $only_free && trim( (string) $price ) !== '' && stripos( (string) $price, 'free' ) === false ) {
			continue;
		}

		$start_ts = $start ? strtotime( $start ) : 0;
		if ( ! $start_ts ) {
			continue;
		}

		// Image: featured image if set, else the remote (Eventbrite) image URL.
		$image = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( ! $image ) {
			$image = get_post_meta( $post_id, '_sipn_be_image_url', true );
		}

		$tasting_events[] = [
			'id'          => $post_id,
			'title'       => $title,
			'slug'        => get_post_field( 'post_name', $post_id ),
			'image'       => $image,
			'start'       => $start,
			'end'         => $end,
			'start_ts'    => $start_ts,
			'venue'       => $venue,
			'state'       => $state,
			'price'       => $price,
			'book'        => $book,
			'is_imported' => (bool) get_post_meta( $post_id, '_sipn_be_source', true ),
		];
	}
	wp_reset_postdata();
}

// Sort by start date ascending
usort( $tasting_events, function ( $a, $b ) {
	return $a['start_ts'] <=> $b['start_ts'];
} );

// Group by Year-Month
$by_month = [];
foreach ( $tasting_events as $ev ) {
	$key = date( 'Y-m', $ev['start_ts'] );
	$by_month[ $key ][] = $ev;
}
?>

<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container pg-events">
				<div class="col-md-12">
					<h1 class="heading-main-events">Events</h1>
					<h2 style="display:none;">Bourbon tasting Events</h2>
					<h2 style="display:none;">Bourbon events</h2>
				</div>

				<div class="col-md-12">
					<div class="search-upc mtopminus events-results">
						<form method="POST">
							<div class="input-search">
								<input type="text" class="events_search"
									placeholder="Search by event name or city or state"
									id="eventsearch" name="es"
									value="<?php echo esc_attr( $search_key ); ?>">
								<i class="fa fa-search icon-search" aria-hidden="true"></i>
								<div class="result-sec events-results-dropdown"></div>
								<div class="result-zero" style="display:none;"> 0 Results.</div>
							</div>
						</form>
					</div>
					<div class="search-sort mtopminus">
						<div class="free-switch">
							<div class="onoff">
								<span>Free Events</span>
								<label class="switch">
									<input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No"
										class="check" <?php echo $only_free ? 'checked' : ''; ?>>
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

						<?php if ( empty( $by_month ) ) : ?>
							<p>Sorry, currently we are not organizing any Bourbon Tasting events.</p>
						<?php else : ?>

							<?php foreach ( $by_month as $month_key => $events ) :
								$month_label = date( 'F Y', strtotime( $month_key . '-01' ) );
							?>
								<div class="col-md-12">
									<h2 class="events-month-heading" style="margin-top:30px;border-bottom:1px solid #c9a64a;padding-bottom:8px;">
										<?php echo esc_html( strtoupper( $month_label ) ); ?>
									</h2>
								</div>

								<?php foreach ( $events as $ev ) :
									$start_disp = date( 'jS M Y', $ev['start_ts'] );
									$end_disp   = $ev['end'] ? date( 'jS M Y', strtotime( $ev['end'] ) ) : '';
									$location   = trim( $ev['venue'] . ( $ev['state'] ? ', ' . $ev['state'] : '' ), ', ' );

									// Imported (Eventbrite) events link to the source page in a new tab;
									// curated events keep their internal single-event page.
									if ( $ev['is_imported'] && ! empty( $ev['book'] ) ) {
										$card_href   = $ev['book'];
										$card_target = ' target="_blank" rel="noopener noreferrer"';
									} else {
										$card_href   = home_url( '/event/' . $ev['slug'] );
										$card_target = '';
									}
								?>
									<div class="col-md-4">
										<div class="events-block">
											<a href="<?php echo esc_url( $card_href ); ?>"<?php echo $card_target; ?>>
												<div class="evnt-img"
													<?php if ( $ev['image'] ) : ?>
													style="background-image:url('<?php echo esc_url( $ev['image'] ); ?>');"
													<?php endif; ?>>
												</div>
												<div class="evnt-content">
													<h2 style="color:#ffffff;"><?php echo esc_html( $ev['title'] ); ?></h2>
													<ul>
														<li style="color:#ffffff">
															Date: <?php echo esc_html( $start_disp ); ?>
															<?php if ( $end_disp && $end_disp !== $start_disp ) : ?>
																- <?php echo esc_html( $end_disp ); ?>
															<?php endif; ?>
														</li>
														<?php if ( $location ) : ?>
															<li>Location: <?php echo esc_html( $location ); ?></li>
														<?php endif; ?>
														<?php if ( $ev['price'] ) : ?>
															<li>Price: <?php echo esc_html( $ev['price'] ); ?></li>
														<?php endif; ?>
													</ul>
												</div>
											</a>
										</div>
									</div>
								<?php endforeach; ?>

								<div class="clearfix"></div>
							<?php endforeach; ?>

						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			jQuery( function ( $ ) {
				$( 'body' ).on( 'click', '.check', function () {
					var on  = this.checked;
					var url = "<?php echo esc_url( home_url( '/events/' ) ); ?>";
					window.location.href = on ? ( url + '?myVar=free' ) : url;
				} );
			} );
		</script>

		<?php sipn_footer(); ?>
