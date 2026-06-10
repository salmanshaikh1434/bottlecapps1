<?php
/**
 * Template Name: SIPN Contact
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
		<div class="col-md-12 mtop100">
			<h1>Questions?<br>FEEDBACK?</h1>
		</div>
		<div class="col-md-12">
		  <p class="strong"> Let us know below!</p>
		  <small><!--Proin sollicitudin id enim non dapibus. Nulla dapibus mauris vel massa sollicitudin, non sagittis dui malesuada.--></small>
		</div>
	
		<div class="col-md-12">
			<div class="chat-detail mtop30">
			 <?php get_template_part( 'content', 'page' ); ?>
			</div>

		 </div>
	</div>
					
<?php sipn_footer();?>