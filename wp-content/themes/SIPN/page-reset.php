<?php
/**
 * Template Name: SIPN Reset Password
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php get_header(); ?>
<article class="col-md-10">
  <div class="wrapper-top">
    <div class="reset-class">
      <div class="wrapper-middle">
        <div class="animate" id="resetpwd">
          <div class="imgcontainer">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-gold.png" alt=""
              class="logo-gold">
          </div>

          <div class="login-container">
            <p class="resetpwd">Reset Password</p>

            <input type="email" placeholder="Email" id="reset_email" name="email" required>
            <input type="text" placeholder="OTP" id="reset_otp" name="otp">
            <input type="password" placeholder="Password" id="reset_pass" name="pass" autocomplete="off">

            <div class="reset">
              <input type="button" id="reset_pass_btn" class="signin reset" value="Reset">
              <!-- Add the reset button -->
              <input type="reset" id="reset_form_btn" class="reset" value="Clear Form">
            </div>
          </div>

          <div class="terms-container msg-container">
            <small class="termspp">Reset password link has been sent to your email</small>
          </div>
        </div>
      </div>

      <script>
        document.getElementById('reset_form_btn').addEventListener('click', function () {
          document.getElementById('reset_email').value = '';
          document.getElementById('reset_otp').value = '';
          document.getElementById('reset_pass').value = '';
        });
      </script>
      <?php sipn_footer(); ?>