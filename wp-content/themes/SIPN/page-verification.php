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

get_header(); ?>
	
<article class="col-md-10">
	<div class="wrapper-top">
	<div class="wrapper-bottom">
		<div class="wrapper-middle">
		  <form class="animate" id="login" method="post">
			<div class="imgcontainer">
			  <a href=""><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/logo-gold.png" alt="" class="logo-gold"></a>
			</div>
			
			<div class="login-container">
				
			  <p class="status">Please verify your email using the link sent to your email.</p>
			  <input type="email" placeholder="Email" name="username" id="username" required>
			  <input type="password" placeholder="Password" name="password" id="password" required>
			  <?php if(isset($_GET['redirect_to'])){?>
			  <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url()."/".$_GET['redirect_to'];?>">
			  <?php } else {?>
			  <input type="hidden" name="redirecturl" id="redirecturl" value="">
			  <?php } ?>
			   <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url();?>">
			  <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to'];?>">
			  <!--<input class="submit_button signin" type="submit" value="Sign In" name="submit">-->
			  <button type="submit" class="signin" id="signin">Sign In</button>
			  <button type="button" id="signup" class="signup">Sign Up</button>
			   <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
			</div>
			
			<div class="terms-container">
			 <small class="termspp">By signing in you agree to <a href="/terms-of-service">terms of service and privacy policy</a> and represent you are 21 or older</small>
			  <small><span class="psw"><a href="/reset-password">Forgot your password?</a></span></small>
			</div>
		  </form>
</div>

        
<?php
sipn_footer();
?>
