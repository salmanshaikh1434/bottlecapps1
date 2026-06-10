<?php
/**
 * Template Name: SIPN elasticsearch
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
 ?>
 <?php get_header();?>
 <style type="text/css">
 	body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0
}
.form-control:focus{
	color: #000;
	}
	.proposdetail{
		color: black;
	}
 </style>

 
<?php
global $wpdb;
// $cur_user = wp_get_current_user();

// $url = site_url().'/wp-json/home/v2/collections';
// if($paged){$page=$paged;}else{$page=1;} 
// $videos_per_page = $per_page = 9;

// $body = array('page'=>$page, 'videos_per_page' => $videos_per_page );
// $body = wp_json_encode( $body );
// $response = wp_remote_post( $url, array(
// 'body'    => $body,
// 'headers'     => [
// 'Content-Type' => 'application/json',
// ],
// ) );
// //print_r($response);exit;
// $videos_res = json_decode( wp_remote_retrieve_body( $response ) );
//print_r($response);exit;
//$total_videos = $videos_res->total_videos;
//$no_of_pages = ceil($total_videos/$videos_per_page);

 ?>
<div class="searchimtiyaz">
<input class="form-control" name="s" required type="search" for="search" placeholder="E.g. Maker's Mark" id="header-search1234" autocomplete="off" style="width: 50%;position: relative;top: 0;left: 0;">
			    <div class="header-result-sec" style="width: 50%;position: absolute;top: 20%;left: 16.5%;"></div>
			</div>

			    <script type="text/javascript">
			    	$("#header-search1234").on("keyup", function(){
			    		var currentRequest = null;
		var searchtxt = $(this).val();
		if (searchtxt.trim() == '') {
		  $(".header-result-sec").attr("style", "width: 50%;position: absolute;top: 20%;left: 16.5%");
		}
		//$(".search-load").remove(); by sumeeth
		if(searchtxt.length>2){
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

		
			currentRequest =	$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: { 
					'action': 'ajaxsendingindexdata', //calls wp_ajax_nopriv_ajaxlogin
					'searchtxt': searchtxt,
					'nonce': site_script_object.nonce,
					},
					beforeSend : function()    {
					if(currentRequest != null) {
						currentRequest.abort();
					}
					
				},
				success: function(data){
					//alert(data);
					//console.log(data);
					var result = '';
					$.each(data.hits.hits, function(index, prod) {
						//console.log(prod);
						//console.log(prod._source.product_image);
						if(prod._index=='sipnproduct'){
							result +='<span class="proposdetail">Product</span>'
							if(prod._source.product_image){
							var pimg = prod._source.product_image;
						}
						else{
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}
						result += '<li><a href=""><table class="pdd" width="98%"><tbody><tr><td class="text-center td15"><img aria-hidden="" height="20" style="vertical-align:middle;" src="'+pimg+'"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;">'+prod._source.product_title+'</small></span><br>';
						if(prod._source.product_flavor){
						result += '<span><small class="catg">Flavor: '+prod._source.product_flavor+'</small></span><br>';
						}
						
						result += '<small class="red text-right"><!----><span><strong>$'+prod._source.product_price+'</strong></span><!----></small></span></td></tr></tbody></table></a></li>';
						}
						if(prod._index=='sipnpost'){
							result +='<span class="proposdetail">Post</span>'
							if(prod._source.post_image){
							var pimg1 = prod._source.post_image;
						}
						else{
							var pimg1 = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}
						result += '<li><a href=""><table class="pdd" width="98%"><tbody><tr><td class="text-center td15"><img aria-hidden="" height="20" style="vertical-align:middle;" src="'+pimg1+'"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;">'+prod._source.post_title+'</small></span><br>';
						if(prod._source.tagged_product){
						result += '<span><small class="catg">Flavor: '+prod._source.tagged_product+'</small></span><br>';
						}
						
						result += '</span></td></tr></tbody></table></a></li>';
						}

						
						
					});
					//console.log(result);
					$(".header-result-sec").html("<ul>"+result+"</ul>");
					if(data.hits.hits!='0'){
						if($("#header-search").val().trim() != ''){
						$(".header-result-sec").attr("style", "display: block !important");
						}
					}else{
						$(".header-result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
						$(".header-result-sec").attr("style", "display: block !important");
					}
					
					
				}
			})



			
		}else{
			// $(".header-result-sec").html("");
			// $(".header-result-sec").attr("style", "display: none !important");
		}
	});
			    </script>

				


 
				
				
				
<?php sipn_footer();?>
