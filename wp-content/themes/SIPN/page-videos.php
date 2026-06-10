<?php
/**
 * Template Name: SIPN Videos
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
global $wpdb;
$cur_user = wp_get_current_user();

$url = site_url().'/wp-json/home/v2/videos';
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

$videos_res = json_decode( wp_remote_retrieve_body( $response ) );

$total_videos = $videos_res->total_videos;
$no_of_pages = ceil($total_videos/$videos_per_page);

 ?>
 <article class="col-md-10">
			<div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container pg-videos">
                    <div class="col-md-12 mp-0">
                        <h1 class="heading-main-events">Videos</h1>
					</div>
					
					<?php 
					
						if(count($videos_res->videos)>0){
					?>
					<div class="videos-total-div">
					    <div class="col-md-12 mp-0">
					<?php
					foreach($videos_res->videos as $video){

					$prod_url = $video->thumb;
					
					if(!$prod_url)
					$prod_url = get_stylesheet_directory_uri().'/assets/images/default-bottle.jpg';
					?>
					
                      <div class="col-md-4">
                          <div class="videolisting">
                              <a class="wp-colorbox-iframe cboxElement" href="<?php echo $video->url.'?autoplay=1';?>">
                                  <div class="video-img-div">
                                      <img src="<?php echo $prod_url;?>">
                                  </div>
                                  <div class="video-content-div">
                                      <h2><?php echo $video->title;?></h2>
                                      <p><?php echo $video->description;?></p>
                                  </div>
                              </a> 
                          </div>
                      </div>
					  
					
					<?php } ?>
					</div>
					</div>
					<div class="col-md-12">
					<?php if($no_of_pages>1){?>
					<div class="paginate">
						<?php /*for($p=1; $p<=$no_of_pages; $p++){ 
							$page_path = modify_url(array('page'=>$p));
						?>
							<a href="<?php echo $page_path;?>"><?php echo $p;?></a>
						<?php }*/ ?>
					</div>
					<?php 
					$end = $page*$per_page;
					$start = $end-$per_page+1;
					?>
					
					  <div  class="col-md-12 col-sm-8">
					  <div  class="page-navigation">
						<?php if($no_of_pages>1){?>
						<ul class="pagination">
						  <li class="page-item <?php if($page <= 1){?>disabled<?php } ?>">
							<a aria-label="First" class="page-link prev_next" href="<?php echo '/videos/page/1';?>" tabindex="-1" page="1">
							  <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
							</a>
						  </li>
						  <li class="page-item <?php if($page <= 1){?>disabled<?php } ?>">
							<a aria-label="Previous" class="page-link prev_next" href="<?php echo '/videos/page/'.($page-1);?>" tabindex="-1" page="<?php echo $page-1; ?>">
							  <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
							</a>
						  </li>
						  <?php ?>
						  <li class="page-item <?php if($page == 1){echo 'active';}?>">
							<a class="page-link" href="<?php echo '/videos/page/1';?>"> 1 
							  <span class="sr-only">(current)</span>
							</a>
						  </li>
						  <?php if($page>=5){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>

						  <?php for($i=$page-2; $i<=$page-1;$i++){ if($i>1){?>
							<li class="page-item <?php if($page == $i){echo 'active';}?>">
							<a class="page-link <?php echo $i;?>" href="<?php echo '/videos/page/'.$i;?>" > <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>
						  <?php for($i=$page; $i<=$page+2 && $i<$no_of_pages;$i++){ if($i>1){?>
							<li class="page-item <?php if($page == $i){echo 'active';}?>">
							<a class="page-link" href="<?php echo '/videos/page/'.$i;?>"> <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>

						  <?php if($page<$no_of_pages-3){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>
						  <li class="page-item <?php if($page == $no_of_pages){echo 'active';}?>">
							<a class="page-link" href="<?php echo '/videos/page/'.$no_of_pages;?>"> <?php echo $no_of_pages;?> </a>
						  </li>
						  <li class="page-item  <?php if($page >= $no_of_pages){?>disabled<?php } ?>">
							<a aria-label="Next" class="page-link prev_next" href="<?php echo '/videos/page/'.$page+1;?>" page="<?php echo $page+1; ?>">
							  <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</a>
						  </li>
						  <li class="page-item <?php if($page >= $no_of_pages){?>disabled<?php } ?>">
							<a aria-label="Last" class="page-link prev_next" href="<?php echo '/videos/page/'.$no_of_pages;?>"  page="<?php echo $no_of_pages; ?>">
							  <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
							</a>
						  </li>
						</ul>
						<?php } ?>
					  </div>
					  </div>
  
					<?php } ?>
					</div>
					
					<?php }else { ?>
					<div>No Products Match search criteria.</div>
					<?php } ?>
                </div>
				
				
				
<?php sipn_footer();?>
