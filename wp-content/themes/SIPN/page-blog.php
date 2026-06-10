<?php
/**
 * Template Name: SIPN Blog
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
 ?>
 <?php get_header();?>
 <article class="col-md-10">
            <div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container">
                    <div class="col-md-12">
                        <h1 class="heading-main-events">Blog</h1>
                    </div>
                    <div class="chat-container">
					<?php
					$posts_per_page = 9;
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					$page = $paged;
					$query = new WP_Query( array(
						'post_type' => 'post',
					    'post_status' => 'publish',
						'posts_per_page' => 9,
						'order' => 'DESC',
						'paged' => $paged
					) );
					//print_r($query);
					
					?>
					<?php if ( $query->have_posts() ) : ?>

					<!-- begin loop -->
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					$post_img = get_the_post_thumbnail_url( $post->ID, 'full' );
					if(!$post_img){
						$post_img = get_stylesheet_directory_uri().'/assets/images/chat/img-chat1.png';
					}
					?>
						<div class="col-md-4">
							<div class="chat-block blog-block">
							<a href="<?php echo get_the_permalink();?>">
								<div class="chat-img">
									<img src="<?php echo $post_img;?>"  alt="<?php echo $post->post_title;?>">
								</div>
								<div class="chat-content">
<!--									<div class="icon-blog"><img src="<?php // echo get_stylesheet_directory_uri();?>/assets/images/icon-blog-gold.png"></div>-->
									<!-- <h1 style="display:none;"><?php echo $post->post_title;?></h1> -->
									<h2><?php echo $post->post_title;?></h2>
									<!-- <h4><?php //echo $post->post_title;?></h4> -->
									<hr class="half-rule"></hr>
									<div class="blog-p">
									<?php $excerpt = substr($post->post_content, 0, 200);
									$result = substr($excerpt, 0, strrpos($excerpt, ' '));
									echo $result. '..';
									?>
									</div>
								</div>
							</a>
							</div>
						</div>
					<?php endwhile; ?>
					
					<div class="col-md-12 float-left">
					<?php 
					$no_of_pages = $query->max_num_pages;
					if($no_of_pages>1){?>
					<div class="paginate">
						<?php /*for($p=1; $p<=$no_of_pages; $p++){ 
							$page_path = modify_url(array('page'=>$p));
						?>
							<a href="<?php echo $page_path;?>"><?php echo $p;?></a>
						<?php }*/ ?>
					</div>
					<?php 
					$end = $paged*$per_page;
					$start = $end-$per_page+1;
					?>
					
					  <div  class="col-md-12 col-sm-12 d-flex justify-conent">
					  <div  class="page-navigation">
						<?php if($no_of_pages>1){?>
						<ul class="pagination">
						  <!-- <li class="page-item <?php if($paged <= 1){?>disabled<?php } ?>">
							<a aria-label="First" class="page-link prev_next" href="<?php echo "/blogs/page/1";?>" tabindex="-1" page="1">
							  <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
							</a>
						  </li> -->
						  <?php if($paged > 1){ ?>
						  <li class="page-item <?php if($paged <= 1){?>disabled<?php } ?>">
							<a aria-label="Previous" class="page-link prev_next" href="<?php echo "/blogs/page/".($paged-1);?>" tabindex="-1" page="<?php echo $paged-1; ?>">
							  <span aria-hidden="true"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i><span class="prev"> Previous</span></span>
							</a>
						  </li> 
						  <?php } ?>
						  <li class="page-item <?php if($paged == 1){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/blogs/page/1/";?>"> 1 
							  <span class="sr-only">(current)</span>
							</a>
						  </li>
						  <?php if($paged>=5){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>

						  <?php for($i=$paged-2; $i<=$paged-1;$i++){ if($i>1){?>
							<li class="page-item <?php if($paged == $i){echo 'active';}?>">
							<a class="page-link <?php echo $i;?>" href="<?php echo "/blogs/page/".$i."/";?>"> <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>
						  <?php for($i=$paged; $i<=$paged+2 && $i<$no_of_pages;$i++){ if($i>1){?>
							<li class="page-item <?php if($paged == $i){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/blogs/page/".$i."/";?>"> <?php echo $i;?> </a>
						  </li>
						  <?php }} ?>

						  <?php if($paged<$no_of_pages-3){ ?>
						  <li class="page-item disabled">
							<a class="page-link">...</a>
						  </li>
						  <?php } ?>
						  <li class="page-item <?php if($paged == $no_of_pages){echo 'active';}?>">
							<a class="page-link" href="<?php echo "/blogs/page/".$no_of_pages."/";?>"> <?php echo $no_of_pages;?> </a>
						  </li>
						  <li class="page-item  <?php if($paged >= $no_of_pages){?>disabled<?php } ?>">
							<a aria-label="Next" class="page-link prev_next" href="<?php if($paged >= $no_of_pages){$a=$no_of_pages; } else {$a=$paged+1;} echo "/blogs/page/".($a)."/";?>" page="<?php echo $paged+1; ?>">
							  <span aria-hidden="true"><span class="next">Next </span><i class="glyphicon glyphicon-arrow-right" aria-hidden="true"></i></span>
							</a>
						  </li>
						</ul>
						<?php } ?>
					  </div>
					  </div>
  
					<?php } ?>
					</div>
					
					


					<?php wp_reset_postdata(); ?>

					<?php else : ?>
						<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
					<?php endif; ?>
					
					</div>
					</div>
                   
<?php sipn_footer();?>