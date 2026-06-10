<?php 
get_header();
$post_id = get_the_ID();

$views = (int) get_post_meta($post_id, 'event_views', true);
$views++;

update_post_meta($post_id, 'event_views', $views);

?>
<article class="col-md-10">
            <div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container">
                    <div class="col-md-5">
                        <div class="img-chatdetail">
							<?php
							$event_image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
							if($event_image_url){
							?>
							<img src="<?php echo $event_image_url;?>"> 
							<?php } else{ ?>
                            <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-event1.png"> 
							<?php } ?>
                      </div>
                      <h1><?php the_title(); ?></h1>
                      <hr class="half-rulehalf"></hr>
                      <!-- <p class="strong"><?php the_excerpt();?> </p> -->
		      <div class ="events-social"><?php echo sipn_social_share(); ?></div>
                    </div>
                    <div class="col-md-7">
                        <div class="chat-detail">
                          <h2 class="evnt-h2">
						  Date: <?php echo date('jS M Y',strtotime(get_post_meta($post->ID, 'event_start_date', true))); echo ' - '; echo date('jS M Y',strtotime(get_post_meta($post->ID, 'event_end_date', true)));?><br>
						  Time: <?php if(get_post_meta($post->ID, 'all_day_event', true)){ echo 'All day';}else if(get_post_meta($post->ID, 'event_start_time', true)){ echo the_field('event_start_time'); echo ' to '; echo the_field('event_end_time');} ?><br>
						  <?php
						  $location = get_post_meta($post->ID, 'event_venue', true); 
						  if($location['address']){?>Location: <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo $location['lat'];?>,<?php echo $location['lng'];?>&zoom=<?php echo $location['zoom'];?>"><?php echo $location['address'];?></a><?php } ?>
						  <br><?php $price = get_post_meta($post->ID, 'event_price', true); ?>
						  Price: <?php  $p=$price; if ($p=='' || $p=='0') {
                            $p='Free';
                         }else{
                            echo "$";
                         } echo $p;?>  
						  </h2>
                          <div class="event-desc">
			    <p><?php the_content();?></p>

<?php 
$location = get_field('event_venue');
if( $location ): ?>
    <div class="acf-map" data-zoom="16">
        <div class="marker" data-lat="<?php echo esc_attr($location['lat']); ?>" data-lng="<?php echo esc_attr($location['lng']); ?>"></div>
    </div>
<?php endif; ?>
                            </div>
 			    
			     <div class="evnt-calender-block">
<?php 
/*$location = get_field('event_venue');
if( $location ): ?>
    <div class="acf-map" data-zoom="16">
        <div class="marker" data-lat="<?php echo esc_attr($location['lat']); ?>" data-lng="<?php echo esc_attr($location['lng']); ?>"></div>
    </div>
<?php endif;*/ ?>
<?php //echo the_field('event_venue');?>
                              <!--<div class="evnt-calender"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-add.png"><span>Add to Calendar</span></div>-->
                              <?php 
$booking = get_field('book_now'); // ACF field

if($booking){
?>
<div class="event-cta-wrapper">

    <div class="event-offer-text">
        Apply code <span>SIPN</span> at checkout for <b>10% OFF</b>
    </div>

    <a href="<?php echo esc_url($booking); ?>" 
       class="book-now-btn"
       target="_blank"
       rel="noopener noreferrer nofollow sponsored"
       data-event="<?php echo get_the_ID();?>">
       Book Now
    </a>

</div>
<?php } ?>
                             </div>
                        </div>
                    </div>
		</div>




<style type="text/css">
    .event-cta-wrapper{
    text-align:center;
    margin-top:25px;
}

.event-offer-text{
    background:#0d6efd;
    color:#fff;
    display:inline-block;
    padding:10px 18px;
    border-radius:6px;
    font-size:15px;
    font-weight:500;
    margin-bottom:18px;
}

.event-offer-text span{
    font-weight:700;
    letter-spacing:1px;
}

.book-now-btn{
    display:inline-block;
    background:linear-gradient(135deg,#c8a45b,#a8843f);
    color:#fff !important;
    padding:14px 34px;
    font-size:17px;
    font-weight:600;
    border-radius:8px;
    text-decoration:none;
    transition:all .3s ease;
    box-shadow:0 8px 20px rgba(0,0,0,.25);
}

.book-now-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 28px rgba(0,0,0,.35);
    background:linear-gradient(135deg,#d4b06b,#b3914a);
}

.acf-map {
    width: 100%;
    height: 400px;
    border: #ccc solid 1px;
    margin: 20px 0;
}

// Fixes potential theme css conflict.
.acf-map img {
   max-width: inherit !important;
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk"></script>
<script type="text/javascript">
(function( $ ) {

/**
 * initMap
 *
 * Renders a Google Map onto the selected jQuery element
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @return  object The map instance.
 */
function initMap( $el ) {

    // Find marker elements within map.
    var $markers = $el.find('.marker');

    // Create gerenic map.
    var mapArgs = {
        zoom        : $el.data('zoom') || 16,
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    // Add markers.
    map.markers = [];
    $markers.each(function(){
        initMarker( $(this), map );
    });

    // Center map based on markers.
    centerMap( map );

    // Return map instance.
    return map;
}

/**
 * initMarker
 *
 * Creates a marker for the given jQuery element and map.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @param   object The map instance.
 * @return  object The marker instance.
 */
function initMarker( $marker, map ) {

    // Get position from marker.
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');
    var latLng = {
        lat: parseFloat( lat ),
        lng: parseFloat( lng )
    };

    // Create marker instance.
    var marker = new google.maps.Marker({
        position : latLng,
        map: map
    });

    // Append to reference for later use.
    map.markers.push( marker );

    // If marker contains HTML, add it to an infoWindow.
    if( $marker.html() ){

        // Create info window.
        var infowindow = new google.maps.InfoWindow({
            content: $marker.html()
        });

        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open( map, marker );
        });
    }
}

/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
function centerMap( map ) {

    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });

    // Case: Single marker.
    if( map.markers.length == 1 ){
        map.setCenter( bounds.getCenter() );

    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}

// Render maps on page load.
$(document).ready(function(){
    $('.acf-map').each(function(){
        var map = initMap( $(this) );
    });
});

})(jQuery);
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    document.querySelectorAll(".book-now-btn").forEach(function(btn){

        btn.addEventListener("click", function(){

            var eventId = this.dataset.event;

            fetch("/wp-admin/admin-ajax.php?action=track_event_click&id="+eventId,{
                method: "GET",
                keepalive: true
            });

        });

    });

});
</script>
<?php sipn_footer();?>
