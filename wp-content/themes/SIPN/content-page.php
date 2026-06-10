<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 */

?>


	<?php if ( is_page('about-us') || is_page('terms-of-service') ) : ?>
		<div class="col-md-12 mtop100">
                        <h1><?php echo get_the_title();?></h1>
                    </div>
	<?php endif; ?>
	<?php
	$url = get_current_url(); 
	if (strpos($url, '/bar/') !== false) {?>
	<?php the_content(); ?>
	<?php } else{ ?>
	<div class="col-md-12">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	<?php } ?>

