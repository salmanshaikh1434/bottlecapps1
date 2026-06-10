<?php
/**
 * Template Name: SIPN Collection
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
 </style>
 
<?php
global $wpdb;
$cur_user = wp_get_current_user();

$url = site_url().'/wp-json/home/v2/collections';
if($paged){$page=$paged;}else{$page=1;} 
$videos_per_page = $per_page = 9;

$body = array('page'=>$page, 'videos_per_page' => $videos_per_page );
$body = wp_json_encode( $body );
$response = wp_remote_post( $url, array(
'body'    => $body,
'headers'     => [
'Content-Type' => 'application/json',
],
) );
//print_r($response);exit;
$videos_res = json_decode( wp_remote_retrieve_body( $response ) );
//print_r($response);exit;
//$total_videos = $videos_res->total_videos;
//$no_of_pages = ceil($total_videos/$videos_per_page);

 ?>


				


 <article class="col-md-10">
			<div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container pg-collections">
                    <div class="col-md-12">
                        <h1 class="heading-main-events">Bourbon Collection</h1>
                        <h2 style="display: none;">Best Bourbon collection</h2>
					</div>
					
    				<?php 
					
						if(count($videos_res)>0){
					?>
					<div class="col-md-12" style="margin-top: 14px;">
							<div class="row">
					<?php
					foreach($videos_res as $video){
				
					?>
					
                  
					  
					<div class="col-md-4">
					    <div class="collections-block">
					        <a href="<?php echo site_url();?>/bourbon-collection/<?php $str = str_replace(" ", "-", $video->collection_orgname);echo $str; ?>" class="collections-link">
					            <?php
						?>
					            <div class="evnt-img collections-topimg" <?php if($video->collection_image){?>style="background-image:url('<?php echo $video->collection_image; ?>');" <?php } ?>>

					            </div>
					            <div class="evnt-content text-left collections">
					                <h2><?php echo $video->collection_name; ?></h2>
<!--
					                <ul>
					                    <li class="text-right author">By: <?php echo $video->author; ?></li>

					                </ul>
-->
					            </div>
					        </a>
					    </div>
					</div>

					<?php } ?>
						</div>
					</div>
					
					
					<?php }else { ?>
					<div>No Products Match search criteria.</div>
					<?php } ?>
                </div>
				
				
				
<?php sipn_footer();?>
