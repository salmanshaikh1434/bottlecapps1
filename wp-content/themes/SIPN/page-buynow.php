<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 ?>

        <?php
/**
 * Template Name: SIPN BuyNow
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
get_header();
 if($_GET['prod_id'] == '' || $_GET['prod_id'] <= 0){
	wp_redirect( '/featured-products', 301 ); 
	exit;
 }else{
   echo "<script>var bn_prod_id = ".sanitize_text_field($_GET['prod_id'])."</script>";
   
   $prd_id = sanitize_text_field($_GET['prid']);
   $prod_id = sanitize_text_field($_GET['prod_id']);
   $product_upc = get_post_meta( $prd_id, 'productupc', true );
    $nproduct_upc = str_replace("#","",$product_upc); //for changed upc by sumeeth for #
   echo "<script>var bn_upc = ".$nproduct_upc."
   var prod_id = ".$prod_id."</script>";
 }
 
global $wpdb;

$product_visibility_term_ids = wc_get_product_visibility_term_ids();
$args = [
	  'post_type' => 'product',
	  'post_status' => 'publish',
	  'order' => 'DESC',
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
	  'posts_per_page' => -1 //by sumeeth for showing all trending products
	];

$products = get_posts($args);


  $the_product = wc_get_product( $prd_id );
  ?>

 <article class="col-md-10">
            <div class="wrapper-top">
            	<div class="wrapper-bottom">
                	<div class="container">
                    	<div class="col-md-12">
                        <h1 class="heading-main-events">Buy Now</h1>
						</div>
                
        <div class="col-md-6 where-purchase">
								<div class="purchase-btn-main">
									<div class="purchase-btn-sub">
									<div class="double-btn">
										<div class="double-btn-inner" id="purchase" data-value="P">
										<span>Where to Purchase</span>
										</div>
									</div>
										<!-- for product details -->

				<div class="chat-detail">
                        <div class="col-md-2">
						<div class="buy-prodetail">
							<?php
							global $post;
							
							$prod_url = get_the_post_thumbnail_url( $prd_id, 'full' );
							//print_r($the_product);
							//update_post_meta( $the_product->get_id(), '_wc_average_rating', 3.00 );
							?>
                            <img src="<?php if($prod_url){echo $prod_url;}else{ echo get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';}?>" alt="<?php echo $the_product->name;?>">
							
                      </div>
					
                        </div>
                        <div class="col-md-10">
							  <h3><?php echo $the_product->name;?></h3>
                            <div class="rating"><ul>
								<?php $rating=$the_product->average_rating;
                if ($rating=='') {
                 $rating=0;
                }

                 for($i=1; $i<=round($rating);$i++){ ?>
                                <li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-after.png"></li>
								<?php } ?>
								<?php for($j=1; $j<=5-round($rating);$j++){ ?>
								<li><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/rating-before.png"></li>
								<?php } ?>
                            </ul></div>
							<div class="price">$<?php echo $the_product->price;?></div>
                        </div>
                </div>
               
                      <!-- for product details -->
                  <span class="ihs-latitude" style="display:none;"></span>
									<span class="ihs-longitude" style="display:none;"></span>
									<span class="ihs-address" style="display:none;"></span>
									<div class="form-group form-group-storelocator">
									<img class="current-loc" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/gps.png">
										<input id="search_input" class="form-control pac-target-input bn_address2" placeholder="Enter Address" type="text" autocomplete="off" list="suggestions">
										<!-- added by salman on 20th Aug 2024 to show recent search -->
										<datalist id="suggestions"></datalist>
										<input type="hidden" id="lat">
										<input type="hidden" id="lng">
										<input type="hidden" id="administrative_area_level_1" value="">
									</div>
										
									<div class="thrible-btn">
										<div class="btn-upc text-center">
										<button class="btn-search buy-now-btn">Search</button>
										</div>
										<div class="loader" style="display: none;"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Loading...</div>
										<div class="stores-main-div">
										<div class="stores-sub-div" id="demo">
										</div>
										</div>
									</div>

									
							</div>
								</div>
        </div>
					 
				<div class="col-md-6">
							<div class="chat-detail find">
									<div class="my-bar">
									<h2 class="trending">Best Sellers</h2>
									<!--Slider-->
									<div id="slider">  
									<div id="dots-con">
										<!--Controlling arrows-->
										<span class="controls" onclick="prevSlide(-1)" id="left-arrow"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
										<?php
										if(count($products)>0){
										for($i=1;$i<=ceil(count($products)/3); $i++){ ?>
										<span class="dot"></span>
										<?php }}?>
										<span class="controls" id="right-arrow" onclick="nextSlide(1)"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
									</div>
										
										
								<?php
								if(count($products)>0){
								$cnt = 0;
								foreach($products as $product){
								$the_product = wc_get_product( $product->ID );
								$prod_url = get_the_post_thumbnail_url( $product->ID, 'medium' );
								?>
								<?php if($cnt != 0 && $cnt%3 == 0){ ?>
								</ul>
								</div>
								<?php } ?>
								<?php if($cnt == 0 || $cnt%3 == 0){ ?>
								<div class="slide">
								<ul>
								<?php } ?>
								<?php if($cnt == count($products)){ ?>
								</ul>
								</div>
								<?php } ?>
								<li><a href="<?php echo get_permalink($product->ID);?>" title="<?php echo $product->post_title;?>"><img alt="<?php echo $product->post_title;?>" src="<?php echo $prod_url;?>"><span class="prod-title"><?php echo $product->post_title;?></span></a></li>
								
								<?php $cnt++;}}else{ echo "No products found on your wishlist.";} ?>
								</div>
								</div>
				</div>

						
							


							
                    </div>
								</div>
					

<!-- Async script executes immediately and must be after any DOM elements used in callback. -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly" async ></script>
<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/buy-now-location.js"></script>
<script>
// $(document).ready(function () {
// 	setTimeout(function(){
// 		$v1=$('.ihs-address').html();
// 		$("#search_input").val($v1);
// 		$l=$('.ihs-latitude').html();
//     	$lo=$('.ihs-longitude').html();
// 		$("#lat").val($l);
//  	 	$("#lng").val($lo);
// 		}, 5000);


// }); 
$(document).ready(function () {
$(".current-loc").click(function(){
    	$v=$('.ihs-address').html();
    	$l=$('.ihs-latitude').html();
    	$lo=$('.ihs-longitude').html();
    	//alert($v);
    	$("#search_input").val($v);
    	$("#lat").val($l);
 	 	$("#lng").val($lo);
    
    });  


});

				var slides = document.querySelectorAll(".slide");
				var dots = document.querySelectorAll(".dot");
				var index = 0;


				function prevSlide(n){
				index+=n;
				console.log("prevSlide is called");
				changeSlide();
				}

				function nextSlide(n){
				index+=n;
				changeSlide();
				}

				changeSlide();

				function changeSlide(){

				if(index>slides.length-1)
				index=0;

				if(index<0)
				index=slides.length-1;



				for(let i=0;i<slides.length;i++){
				slides[i].style.display = "none";

				dots[i].classList.remove("active");


				}

				slides[index].style.display = "block";
				dots[index].classList.add("active");



				}

</script>
<!-- added by salman on 20th Aug 2024 to show recent search -->
<script>
		
			function setCookie(name, value, days) {
				let expires = "";
				if (days) {
					const date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = "; expires=" + date.toUTCString();
				}
				document.cookie = name + "=" + (value || "") + expires + "; path=/";
			}

			
			function getCookie(name) {
				const nameEQ = name + "=";
				const cookies = document.cookie.split(';');
				for (let i = 0; i < cookies.length; i++) {
					let cookie = cookies[i].trim();
					if (cookie.indexOf(nameEQ) === 0) {
						return cookie.substring(nameEQ.length, cookie.length);
					}
				}
				return null;
			}

			
			function storeRecentValue(name, newValue) {
				const existingValues = JSON.parse(getCookie(name) || "[]");

			
				if (newValue && !existingValues.includes(newValue)) {
					existingValues.unshift(newValue);
				}

			
				if (existingValues.length > 1) {
					existingValues.pop(); 
				}

			
				setCookie(name, JSON.stringify(existingValues), 7);
			}

			
			function populateSuggestions(name) {
				const dataList = document.getElementById('suggestions');
				dataList.innerHTML = ''; 

				const storedValues = JSON.parse(getCookie(name) || "[]");

			
				storedValues.forEach(value => {
					const option = document.createElement('option');
					option.value = value;
					dataList.appendChild(option);
				});
			}

			
			document.getElementById('search_input').addEventListener('focusout', function () {
				const cookieName = 'search_input_values';
				storeRecentValue(cookieName, this.value);
			});

			
			document.getElementById('search_input').addEventListener('focusin', function () {
				if (this.value.trim() === "") { // Only populate suggestions if the input is empty
					const cookieName = 'search_input_values';
					populateSuggestions(cookieName);
				}
			});
				
			document.getElementById('search_input').addEventListener('input', function () {
				if (this.value.trim() !== "") { 
					const dataList = document.getElementById('suggestions');
					dataList.innerHTML = ''; 
				}
			});

		</script>
    <div class="buy-now-mtop">
        <?php sipn_footer();?>
		</div>