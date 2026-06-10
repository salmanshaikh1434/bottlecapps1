<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */
//exit; 
get_header(); ?>

<?php $url = get_current_url();

if (strpos($url, '/bar/') !== false) {
	get_template_part( 'content', 'page' );
	sipn_footer();
}else if (strpos($url, '/about-us/') !== false) {
?><article class="col-md-10">
            <div class="wrapper-top">
            <div class="">
                <div class="container">
                	<?php	
	get_template_part( 'content', 'page' );
	sipn_footer();
	?>
</div>
	<?php
}else{
	
?>
 <article class="col-md-10">
            <div class="wrapper-top">
            <div class="wrapper-bottom">
                <div class="container m-height">
                    
<?php
while ( have_posts() ) :
	the_post();

	get_template_part( 'content', 'page' );


endwhile; // End of the loop.
?>

</div>
<?php
sipn_footer();
} ?>