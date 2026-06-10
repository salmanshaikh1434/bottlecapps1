<?php get_header();?>
<article class="col-md-10">
            <div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container">
					<h2>Blog</h2>
					<div class="chat-container">
						<div class="col-md-12">
							<?php
							$post_img = get_the_post_thumbnail_url( $post->ID, 'full' );
							if(!$post_img){
								$post_img = get_stylesheet_directory_uri().'/assets/images/img-banner.jpg';
							}
							?>
							<div class="blog-banner" style="background:url('<?php echo $post_img; ?>')  no-repeat left top;background-size:cover;"></div>
						</div>
						<div class="col-md-12">
						<div class="blog-container">
							<div class="blog-left blog-details-sec">
								<a href="/sipn-bourbon-blogs"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-menu-slider.png"></a>
								<h1 style="color: black;"><?php echo $post->post_title;?></h1>
								<?php echo nl2br(get_the_content());?>
								<br><br> <?php echo sipn_social_share(); ?>
							</div>
							<div class="blog-right">
								<?php
								$args = array('post_type'=>'post', 'orderby'=>'rand', 'numberposts'=>3, 'post__not_in'=>array($post->ID));
								$r_posts = get_posts($args);
								foreach($r_posts as $blogpost){
								?>
								<div class="blog-topic">
									<!-- <h3>Blog Topic</h3> -->
									<p class="strong"><?php echo $blogpost->post_title;?></p>
									<a href="<?php echo get_the_permalink($blogpost->ID);?>">
									<img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/icon-blog-grey.png">
									</a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
                    
                </div>
<?php sipn_footer();?>