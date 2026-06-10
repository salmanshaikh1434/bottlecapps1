<?php
/**
 * Template Name: SIPN Events
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
$search_key = sanitize_text_field($_POST['es']);
?>
 <article class="col-md-10">
            <div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container pg-events">
                    <div class="col-md-12">
                        <h1>Events</h1>
                    </div>
                    <div class="col-md-12">
                      <div class="search-upc mtopminus">
							<form method="POST">
                            <div class="input-search">
                                 <input type="text" placeholder="Search" name="es" value="<?php echo $search_key;?>">
                                 <i class="fa fa-search icon-search" aria-hidden="true"></i>
                             </div>
                             <div class="btn-upc">
                                 <button type="submit" class="btn-search">Search</button>
                             </div>
							</form>
                      </div>
                      <!--<div class="search-sort mtopminus">
                        <div class="input-search">
                             <input type="text" placeholder="Sort By LOCATION" name="uname" required>
                             <i class="fa fa-angle-up icon-up" aria-hidden="true"></i>
                         </div>
                         <div class="btn-sort">
                              <button type="submit" class="btn-price">Location
                                <i class="fa fa-check" aria-hidden="true"></i>
                              </button>
                              <button type="submit" class="btn-style">Date</button>
                        </div>
                      </div>-->
				</div>
                
                    <div class="col-md-12">
					<?php
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					$args = array(
						'post_type' => 'events',
					    'post_status' => 'publish',
						'posts_per_page' => 6,
						'meta_key' => 'event_start_date',
						'orderby' => 'meta_value_num',
						'order' => 'ASC',
						'meta_query' => array(
						'relation' => 'OR', 
						array(
							'key' => 'event_start_date',
							'value' => date("Ymd"), // date format error
							'compare' => '>='
						) ,
						array(
							'key' => 'event_end_date',
							'value' => date("Ymd"), // date format error
							'compare' => '>='
						)
						),
						'paged' => $paged
					);
					
					if($search_key){
						$args['s'] = $search_key;
					}
					
					$query = new WP_Query( $args );

					?>
					<?php if ( $query->have_posts() ) : ?>

					<!-- begin loop -->
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="col-md-4">
						<?php
						$event_image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
						?>
                        <div class="evnt-img" <?php if($event_image_url){?>style="background-image:url('<?php echo $event_image_url;?>');"<?php } ?>>
                         <!--<div class="evnt-calender">
							<img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-add.png">
							<span>Add to Calendar</span>
						 </div>-->
                        </div>
                        <div class="evnt-content">
                          <h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                          <ul>
                            <li><a href="#">Date: <?php echo date('jS M Y',strtotime(get_post_meta($post->ID, 'event_start_date', true))); echo ' - '; echo date('jS M Y',strtotime(get_post_meta($post->ID, 'event_end_date', true)));?></a></li>
<?php $location = get_post_meta($post->ID, 'event_venue', true); if($location['address']){?> <br> <li><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo $location['lat'];?>,<?php echo $location['lng'];?>&zoom=<?php echo $location['zoom'];?>">Location: <?php echo $location['city'].", ".$location['state_short'].", ".$location['country'];?></a></li><?php } ?>
                          </ul>
                        </div>
                      </div>
					<?php endwhile; ?>
					<!-- end loop -->


					<div class="pagination">
					<?php 
						echo paginate_links( array(
							'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
							'total'        => $query->max_num_pages,
							'current'      => max( 1, get_query_var( 'paged' ) ),
							'format'       => '?paged=%#%',
							'show_all'     => false,
							'type'         => 'plain',
							'end_size'     => 2,
							'mid_size'     => 1,
							'prev_next'    => true,
							'prev_text'    => sprintf( '<i></i> %1$s', __( '<<', 'text-domain' ) ),
							'next_text'    => sprintf( '%1$s <i></i>', __( '>>', 'text-domain' ) ),
							'add_args'     => false,
							'add_fragment' => '',
						) );
					?>
					</div>


					<?php wp_reset_postdata(); ?>

					<?php else : ?>
						<p><?php _e( 'Sorry, currently we are not organizing any events.' ); ?></p>
					<?php endif; ?>
					
						</div>
                       </div>
                    </div>
<?php sipn_footer();?>
