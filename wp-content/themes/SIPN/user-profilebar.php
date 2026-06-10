<?php

/**
 * User Profile Bar
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
global $current_user; wp_get_current_user();
$profile_id = bbp_get_displayed_user_id();
if($profile_id != $current_user->data->ID){
	$bar_res = web_user_bar($profile_id);
}else{
	$bar_res = web_get_my_bar();
}
 echo "<pre>";print_r($bar_res);exit;
$email=$current_user->data->user_email;
$blink=$bar_res['bar_link'];
?>
<article  class="col-md-10">
	<div class="container">
	<?php
	if($bar_res['message'] == 'private bar' && $profile_id != $current_user->data->ID){ ?>
		<div class="private-bar"><img class="no-bar-exists" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/bar-is-private.png"> </div>
	<?php }
	else{
		
		if($profile_id != $current_user->data->ID && ($bar_res->message == 'Bar doesnt exist' ||$bar_res['message'] == 'Bar doesnt exist')){
			
			if($profile_id != $current_user->data->ID){
			?>
				<div class="private-bar"><img class="private-bar no-bar-exists" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/bar-is-private.png"> </div>
			<?php
			}
		}else if($profile_id == $current_user->data->ID || ($bar_res->message != 'Bar doesnt exist' || $bar_res['message'] != 'Bar doesnt exist')){
			//first bar create
			if($profile_id == $current_user->data->ID && ($bar_res->message == 'Bar doesnt exist' || $bar_res['message'] == 'Bar doesnt exist')){
					$url1 = site_url().'/wp-json/bar/v1/add'; 
					$body1 = array('name'=>'', 'owner_email'=>$current_user->data->user_email);
					$add_bar_res = web_bar_add($body1);
					//print_r($add_bar_res);
					$bar_link = bbp_get_user_profile_url($current_user->data->ID);
					header("Location: $bar_link");
			}
		?>
		<input type="hidden" id="lemail" value="<?php echo $email; ?>" >
		<input type="hidden" id="barlink" value="<?php echo $blink ?> ">
		<div class="col-md-6 bar-container">
			
			<div class="img-chatdetail bar-img-height">
			<img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-bar.png"> 
			</div>
			
			<?php if($profile_id == $current_user->data->ID){ ?>
			<h1 class="profile bar-title"><span><?php echo stripslashes($bar_res['bar_name']);?></span> <i class="far fa-edit bar-edit"></i></h1>
			<div class="edit-bar-sec" style="display:none;">
				<input type="text" name="bar_name" id="bar_name" value="<?php echo stripslashes($bar_res['bar_name']);?>">
				<p class="edibar" style="color:red;display:none;">This field is required </p>
				<div class="onoff">
				<span style="margin-right: 10px; line-height: 22px;">Profile (Private/Public)</span>
				<label class="switch">
				<input type="checkbox" name="bar_state" id="bar_state" <?php if($bar_res['is_public']){echo "checked=checked";}?>>
				<span class="slider round"></span>
				</label>
				</div>
				<input type="button" class="<?php if($bar_res['bar_name']=='') { echo 'colorbttn'; } ?>" id="save_bar" bid="<?php echo $bar_res['bar_id'];?>" value="Save" <?php if($bar_res['bar_name']=='') { echo 'disabled="disabled"'; } ?>>
			</div>
			<?php }else{ ?>
				
			<h1 class="profile bar-title"><?php echo stripslashes($bar_res['bar_name']);?></h1>
			<?php } ?>
			<?php //print_r($bar_res);?>
			<div class="prof-details">
			<?php
			$liked_flag = get_profile_like_flag($profile_id);
			if($liked_flag){
				$like_class =  "";
			}else{
				$like_class =  "inactive";
			}
			?>
			<div class="like <?php echo $like_class;?>"><a liked="<?php echo $liked_flag; ?>"  pid="<?php echo $profile_id; ?>" class="<?php if ( is_user_logged_in() ){ echo 'like_profiles';}else{?> nologinaction <?php } ?>" href="javascript:void(0);"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="profile_likes_count"><?php if($profile_id == $current_user->data->ID){echo $bar_res['user_details']['likes'];}else{ echo $bar_res['user_details']['likes']; }?></span></a></div>
			<!-- <div class="location"><a href=""><i class="fa fa-map-marker" aria-hidden="true"></i> FL, USA</a></div> -->
			<small>Est.Value $<span class='bar-value'><?php $num = number_format((float)$bar_res['user_details']['bar_value'], 2, '.', '');  if($profile_id == $current_user->data->ID){echo  $num;}else{echo $num;}?></span></small>
			</div>
			<div class="wishlist-icons">
			<?php echo sipn_social_share(stripslashes($bar_res['bar_name'])); ?>
			</div>
			<?php if($profile_id == $current_user->data->ID){ }else{?>
			<div class="userreportbar"><a href="javascript:void(0);" class="report-bar">Report</a></div>
			<?php } ?>
		</div>
		
		<?php if($profile_id == $current_user->data->ID){ ?>
		<div class="col-md-6">
                      <div class="chat-detail">
                        <div class="my-bar">
						<?php
						$cnt = 1;
						$total_products_cnt = 0;
						$bar_value = 0;
						
						foreach($bar_res['shelves'] as $shelf){
							 ?>
                        <!--   <h2><?php //echo $shelf['shelf_name'];?></h2> -->
                         <input type="text" class="shelfedit" ssid="<?php echo $shelf['shelf_id']; ?>" id="shelfedit<?php echo $cnt; ?>" style="text-align: center;color: #bda766; font-size: 17px; font-family: 'montserratbold';margin: 0 auto 25px auto; padding-top: 15px;border: transparent;" value="<?php echo $shelf['shelf_name'];?>" readonly="readonly"> <!-- by sumeeth -->
                          <!-- Flickity HTML init -->
                          <section class="slider">
                            <ul slid="<?php echo $shelf['shelf_id'];?>" brid="<?php echo $bar_res['bar_id'];?>" class="slides ui-sortable cs-hidden"  id="autoWidth<?php echo $cnt;?>">
							<?php 
							$p=1;
							foreach($shelf['products'] as $product){

					if(!$product['product_image'])
                                        $product['product_image'] = get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';
                                    if($product['product_id']!=''){

?>
                              <li pid="<?php echo $product['product_id'];?>" class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
                                <div class="box">
								
                                  <div class="slide-img">
								 
                                     <img alt="<?php echo $product['product_name'];?>" src="<?php echo $product['product_image'];?>">
								
                                  </div>
				<div class="slide-title"><?php echo $product['product_name'];?></div>
				<div class="delete-product" dpid="<?php echo $product['product_id'];?>"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-delete-product.png"></div>				
                                </div>	
                                 
                              </li>
							  
							<?php }
							$p++; 
							$total_products_cnt++; 
							$price = (float)get_post_meta( $product['product_id'], '_price', true );
							$bar_value += $price;} ?>
                            
							<?php if(count($shelf['products'])>=3){?>
							<li class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
							 <a href="/?s=&si=<?php echo $shelf['shelf_id'];?>&w=<?php echo $p;?>">
							<div class="box">
							  <div class="slide-img">
								 <img alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy-lightplus.png">
							  </div>
							 </div>
							</a>
							 <div class="slide-title">...</div>
							  <div class="delete-product"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-delete-product.png"></div>
						  </li>
						  <?php } else{ 
							  for($k=count($shelf['products'])+1; $k<=3; $k++){?>
							  <li class="slide slide<?php echo $k;?> ui-sortable-handle item-a">
							  <a href="/?s=&si=<?php echo $shelf['shelf_id'];?>&w=<?php echo $k;?>">
							  <div class="box">
							  <div class="slide-img">
								 <img alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy-lightplus.png">
							  </div>
							 </div>
							 </a>
							 <div class="slide-title">...</div>
							  
						  </li>
							  <?php } } ?>
							  
                            </ul>
                             
                          </section>
							<?php 
							$cnt++;
						} 
							?>
						</div>
                     </div>
                     <!-- drag drop HTML init -->
                    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
                    <script id="rendered-js">
					$(".bar-value").html(<?php echo $bar_value;?>);
					<?php if($profile_id == $current_user->data->ID){?>
					$( document ).on('mouseleave','.slide-img',function(){
						$('.slides').on('mouseover mouseenter mouseleave mouseup mousedown', function() {
							return false
						});
					});
                    $(".slides").sortable({
                      placeholder: 'slide-placeholder',
                      axis: "x",
                      revert: 150,
                      start: function (e, ui) {
						
                        placeholderHeight = ui.item.outerHeight();
                        ui.placeholder.height(placeholderHeight + 15);
                        $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                    
                      },
                      change: function (event, ui) {
						
                        ui.placeholder.stop().height(0).animate({
                          height: ui.item.outerHeight() + 15 },
                        300);
                    
                        placeholderAnimatorHeight = parseInt($(".slide-placeholder-animator").attr("data-height"));
                    
                        $(".slide-placeholder-animator").stop().height(placeholderAnimatorHeight + 15).animate({
                          height: 0 },
                        300, function () {
                          $(this).remove();
                          placeholderHeight = ui.item.outerHeight();
                          $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                        });
                    
                      },
                      stop: function (e, ui) {
                        $(".slide-placeholder-animator").remove();
						var products_order = [];
						var shelf_id = $(this).attr('slid');
						var bar_id = $(this).attr('brid');
						var cnt = 1;
						$(this).find('li.slide').not(".clone").each(function(){
							if($(this).attr('pid')){
								//console.log($(this).attr('pid'));
								products_order.push($(this).attr('pid'));
								cnt++;
								//shelf_id = $(this).attr('slid');
							}
						});
						
						//console.log(products_order);
						//console.log(shelf_id);
						
						$.ajax({
							type: 'POST',
							dataType: 'json',
							url: site_script_object.ajaxurl,
							data: { 
								'action': 'ajaxproductsreorder', //calls wp_ajax_nopriv_ajaxlogin
								'products_order': products_order,
								'shelf_id': shelf_id,
								'bar_id': bar_id,
								'nonce': site_script_object.nonce,
								},
							success: function(data){
								
							}
						});
							
						//e.preventDefault();
						//e.stopPropagation();
                      } });
					<?php } ?>
                    </script>
                    
                      
                    </div>
		<?php } else { ?>
		<div class="col-md-6">
                      <div class="chat-detail">
                        <div class="my-bar">
						<?php
						$cnt = 1;
						$total_products_cnt = 0;
						$bar_value = 0;
						//print_r($bar_res->shelves);
						if(count($bar_res['shelves'])>0){
						foreach($bar_res['shelves'] as $shelf){
							if(count($shelf['products'])>0){ ?>
                          <h2><?php echo $shelf['shelf_name'];?></h2>
                          <!-- Flickity HTML init -->
                          <section class="slider">
                            <ul class="slides ui-sortable cs-hidden"  id="autoWidth<?php echo $cnt;?>">
							<?php 
							$p=1;
							foreach($shelf['products'] as $product){
					if(!$product['product_image'])
                                        $product['product_image'] = get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';
?>
                              <li class="slide slide<?php echo $p;?> ui-sortable-handle item-a">
                                <div class="box">
								
                                  <div class="slide-img">
								  <a href="<?php echo get_permalink($product['product_id']);?>">
                                     <img alt="<?php echo $product['product_name'];?>" src="<?php echo $product['product_image'];?>">
									</a>
                                  </div>
								  <div class="slide-title"><?php echo $product['product_name'];?></div>
								
                                </div>	
                                 
                              </li>
							  
								<?php 
								$p++; 
								$total_products_cnt++; 
								$price = (float)get_post_meta( $product['product_id'], '_price', true );
								$bar_value += $price;
							} ?>
                           
							  <?php if(count($shelf['products'])<3){ 
							  for($k=count($shelf['products'])+1; $k<=3; $k++){?>
							  <li class="slide slide<?php echo $k;?> ui-sortable-handle item-a">
							  
							  <div class="box">
							  <div class="slide-img">
								 <img class="empty-bottle" alt="" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-dummy.png">
							  </div>
							 </div>
							 
							 <div class="slide-title">...</div>
							  
						  </li>
							  <?php } } ?>
                            </ul>
                             
                          </section>
							<?php }
							$cnt++;
						}} if($total_products_cnt<=0){ echo '<h2>No products in the Bar</h2>';}
							?>
						</div>
                     </div>
                     <!-- for edit modal report -->
						<div id="reportModal" class="modal">
							<div class="modal-content">
                                            <header>
                                                <h2>Report</h2>
                                          <span class="close">×</span>
                                        </header>
                                        <div class="report">
                                            <p><strong>Why are you reporting this post?</strong></p>
                                            <p>Your report is confidential, this will keep the SIPN community cleaner for all the users. </p>
                                            <ul>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate Speech<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Prohibited Content">Prohibited Content<span><i class="fa fa-chevron-right"></i></span></a></li>

                                            </ul>
                                           
                                        </div>
                            </div>
                        </div> 
                     <!-- drag drop HTML init -->

                    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
                    <script id="rendered-js">
					$(".bar-value").html(<?php echo $bar_value;?>);
					/*$( document ).on('mouseleave','.slide-img',function(){
						$('.slides').on('mouseover mouseenter mouseleave mouseup mousedown', function() {
							return false
						});
					});
                    $(".slides").sortable({
                      placeholder: 'slide-placeholder',
                      axis: "x",
                      revert: 150,
                      start: function (e, ui) {
                    
                        placeholderHeight = ui.item.outerHeight();
                        ui.placeholder.height(placeholderHeight + 15);
                        $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                    
                      },
                      change: function (event, ui) {
                    
                        ui.placeholder.stop().height(0).animate({
                          height: ui.item.outerHeight() + 15 },
                        300);
                    
                        placeholderAnimatorHeight = parseInt($(".slide-placeholder-animator").attr("data-height"));
                    
                        $(".slide-placeholder-animator").stop().height(placeholderAnimatorHeight + 15).animate({
                          height: 0 },
                        300, function () {
                          $(this).remove();
                          placeholderHeight = ui.item.outerHeight();
                          $('<div class="slide-placeholder-animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
                        });
                    
                      },
                      stop: function (e, ui) {
                    
                        $(".slide-placeholder-animator").remove();
                    
                      } });*/
                    </script>
                    
                      
                    </div>
		<?php } ?>			
		<?php } } ?>		
	</div>			
	<script>
	$("#bar_name").on("keyup", function(){
		//alert('hi');
		var barname = $(this).val();
		if(barname.trim().length>0==''){
			$('#save_bar').prop("disabled", true);
			$('#save_bar').addClass('colorbttn');
			$('.edibar').show();
		}else{
			$('#save_bar').removeAttr("disabled");
			$('#save_bar').removeClass('colorbttn');
			$('.edibar').hide();
		}
	});
	$(document).ready(function(){
	$('body').on('click', '.report-bar', function (e) {
		
		var modal = document.getElementById("reportModal");
		modal.style.display = "block";
		
	});
	$('body').on('click', '.close', function (e) {
		
		var modal = document.getElementById("reportModal");
		modal.style.display = "none";
		
	});
	$('body').on('click', '.report_post', function (e) {

		var barname = $("#bar_name").val();
		var barlink = $("#barlink").val();
		var lemail = $("#lemail").val();
		var reason = $(this).attr('rep');
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: { 
					'action': 'ajaxreportbar', //calls wp_ajax_nopriv_ajaxlogin
					'barname': barname,
					'barlink': barlink,
					'lemail': lemail,
					'reason': reason,
					'nonce': site_script_object.nonce,
					},
				success: function(data){
					//alert(data.message);
					//console.log(data.status);
					//if(data.status){
					//var sec = "#msg-"+reply_id;
					//$(sec).hide();
					//}
					var modal = document.getElementById("reportModal");
					modal.style.display = "none";
					if(data.message=='Bar is reported successfully.'){
						alert('Thanks For Reporting');
					}
				}
			});
	});

	});
	</script>				