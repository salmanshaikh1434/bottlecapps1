<?php
/**
 * Single event template.
 *
 * Handles both formats of event_venue:
 *  - ACF Google Map array (existing manually-entered events)
 *  - Plain string (events imported from Eventbrite via SBB sync)
 *
 * @package Neve / SIPN custom theme
 */

get_header();
?>
<article class="col-md-10">
	<div class="wrapper-top">
		<div class="wrapper-bottom">
			<div class="container">

				<?php
				$post_id = $post->ID;

				$event_image_url = get_the_post_thumbnail_url( $post_id, 'full' );
				$start_raw       = get_post_meta( $post_id, 'event_start_date', true );
				$end_raw         = get_post_meta( $post_id, 'event_end_date',   true );
				$start_time      = get_post_meta( $post_id, 'event_start_time', true );
				$end_time        = get_post_meta( $post_id, 'event_end_time',   true );
				$all_day         = get_post_meta( $post_id, 'all_day_event',    true );
				$venue_raw       = get_post_meta( $post_id, 'event_venue',      true );
				$state_raw       = (string) get_post_meta( $post_id, 'event_state', true );

				// Extract a clean 2-letter state code from values like "MA 02210, USA"
				$state = '';
				$us_state_codes = [
					'AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA',
					'KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY',
					'NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY',
				];
				if ( $state_raw ) {
					$candidate = strtoupper( trim( $state_raw ) );
					if ( preg_match( '/^([A-Z]{2})\b/', $candidate, $m ) && in_array( $m[1], $us_state_codes, true ) ) {
						$state = $m[1];
					} else {
						foreach ( $us_state_codes as $code ) {
							if ( preg_match( '/\b' . $code . '\b/', $candidate ) ) {
								$state = $code;
								break;
							}
						}
					}
				}
				$price_raw       = get_post_meta( $post_id, 'event_price',      true );
				$book_url        = get_post_meta( $post_id, 'book_now',         true );

				$start_disp = $start_raw ? date( 'jS M Y', strtotime( $start_raw ) ) : '';
				$end_disp   = $end_raw   ? date( 'jS M Y', strtotime( $end_raw   ) ) : '';

				// Normalize venue: handle both ACF map array and plain string
				$venue_address = '';
				$venue_lat     = '';
				$venue_lng     = '';
				$venue_zoom    = 16;
				$venue_state   = '';

				if ( is_array( $venue_raw ) ) {
					$venue_address = $venue_raw['address'] ?? ( $venue_raw['name'] ?? '' );
					$venue_lat     = $venue_raw['lat']     ?? '';
					$venue_lng     = $venue_raw['lng']     ?? '';
					$venue_zoom    = $venue_raw['zoom']    ?? 16;
					$venue_state   = $venue_raw['state_short'] ?? '';
				} else {
					$venue_address = (string) $venue_raw;
				}

				if ( ! $state && $venue_state ) {
					$state = $venue_state;
				}

				// Normalize price (template should not double-prepend $)
				$price_str = trim( (string) $price_raw );
				if ( $price_str === '' || $price_str === '0' || strcasecmp( $price_str, 'free' ) === 0 ) {
					$price_display = 'Free';
				} elseif ( strpos( $price_str, '$' ) !== false ) {
					// Already has currency symbol (e.g. from Eventbrite sync)
					$price_display = $price_str;
				} else {
					$price_display = '$' . $price_str;
				}

				$has_map = ( $venue_lat !== '' && $venue_lng !== '' );
				?>

				<div class="col-md-5">
					<div class="img-chatdetail">
						<?php if ( $event_image_url ) : ?>
							<img src="<?php echo esc_url( $event_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php else : ?>
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/img-event1.png' ); ?>" alt="">
						<?php endif; ?>
					</div>
					<h1><?php the_title(); ?></h1>
					<hr class="half-rulehalf">
					<div class="events-social"><?php echo sipn_social_share(); ?></div>
				</div>

				<div class="col-md-7">
					<div class="chat-detail">
						<h2 class="evnt-h2">
							Date:
							<?php echo esc_html( $start_disp ); ?>
							<?php if ( $end_disp && $end_disp !== $start_disp ) : ?>
								- <?php echo esc_html( $end_disp ); ?>
							<?php endif; ?>
							<br>
							Time:
							<?php
							if ( $all_day ) {
								echo 'All day';
							} elseif ( $start_time ) {
								echo esc_html( $start_time );
								if ( $end_time ) {
									echo ' to ' . esc_html( $end_time );
								}
							}
							?>
							<br>
							<?php if ( $venue_address ) : ?>
								Location:
								<?php if ( $has_map ) : ?>
									<a target="_blank" rel="noopener"
										href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr( $venue_lat ); ?>,<?php echo esc_attr( $venue_lng ); ?>">
										<?php echo esc_html( $venue_address ); ?>
									</a>
								<?php else : ?>
									<a target="_blank" rel="noopener"
										href="https://www.google.com/maps/search/?api=1&query=<?php echo rawurlencode( $venue_address . ( $state ? ', ' . $state : '' ) ); ?>">
										<?php echo esc_html( $venue_address ); ?><?php echo $state ? ', ' . esc_html( $state ) : ''; ?>
									</a>
								<?php endif; ?>
								<br>
							<?php endif; ?>
							Price: <?php echo esc_html( $price_display ); ?>
							<?php if ( $book_url ) : ?>
								<br>
								<a class="btn-booknow" target="_blank" rel="noopener" href="<?php echo esc_url( $book_url ); ?>">Book Now</a>
							<?php endif; ?>
						</h2>

						<div class="event-desc">
							<p><?php the_content(); ?></p>

							<?php if ( $has_map ) : ?>
								<div class="acf-map" data-zoom="<?php echo esc_attr( $venue_zoom ); ?>">
									<div class="marker"
										data-lat="<?php echo esc_attr( $venue_lat ); ?>"
										data-lng="<?php echo esc_attr( $venue_lng ); ?>"></div>
								</div>
							<?php endif; ?>
						</div>

						<div class="evnt-calender-block"></div>
					</div>
				</div>
			</div>

			<?php if ( $has_map ) : ?>
				<style type="text/css">
					.acf-map {
						width: 100%;
						height: 400px;
						border: #ccc solid 1px;
						margin: 20px 0;
					}
					.acf-map img {
						max-width: inherit !important;
					}
				</style>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk"></script>
				<script type="text/javascript">
					( function ( $ ) {
						function initMap( $el ) {
							var $markers = $el.find( '.marker' );
							var map = new google.maps.Map( $el[ 0 ], {
								zoom: $el.data( 'zoom' ) || 16,
								mapTypeId: google.maps.MapTypeId.ROADMAP
							} );
							map.markers = [];
							$markers.each( function () { initMarker( $( this ), map ); } );
							centerMap( map );
							return map;
						}

						function initMarker( $marker, map ) {
							var lat = parseFloat( $marker.data( 'lat' ) );
							var lng = parseFloat( $marker.data( 'lng' ) );
							if ( isNaN( lat ) || isNaN( lng ) ) { return; }
							var marker = new google.maps.Marker( {
								position: { lat: lat, lng: lng },
								map: map
							} );
							map.markers.push( marker );
							if ( $marker.html() ) {
								var iw = new google.maps.InfoWindow( { content: $marker.html() } );
								google.maps.event.addListener( marker, 'click', function () {
									iw.open( map, marker );
								} );
							}
						}

						function centerMap( map ) {
							var bounds = new google.maps.LatLngBounds();
							map.markers.forEach( function ( marker ) {
								bounds.extend( {
									lat: marker.position.lat(),
									lng: marker.position.lng()
								} );
							} );
							if ( map.markers.length === 1 ) {
								map.setCenter( bounds.getCenter() );
							} else {
								map.fitBounds( bounds );
							}
						}

						$( document ).ready( function () {
							$( '.acf-map' ).each( function () { initMap( $( this ) ); } );
						} );
					} )( jQuery );
				</script>
			<?php endif; ?>

			<?php sipn_footer(); ?>
