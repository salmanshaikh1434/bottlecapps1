<?php

/**
 * Template Name: SIPN Sign up
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrapnew.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/style-new-designs.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <title>Sipn Up</title>
    <style>
        /* Fonts */
        @font-face {font-family: 'Sen';src: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/Sen-VariableFont_wght.ttf') format('truetype');}
        :root {padding: 0;margin: 0;}
        body {background-color: #151515;padding: 0;margin: 0;color: #fff;}
        img,button {border: none;outline: none;padding: 0;margin: 0;}
        p a {color: #fff;}
        .wrapper-top {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-top.png);background-repeat: no-repeat;background-position: 0 0;background-size: 48%;}
        .wrapper-bottom {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-bottom.png);background-repeat: no-repeat;background-position: left bottom;background-size: 48%;}
        .bg-form {background-color: #282828;border-top-left-radius: 3em;border-bottom-left-radius: 3em;font-family: 'Sen';color: #fff;padding: 80px 40px 80px 40px;min-height: 100vh;}
        .bg-form h1 {text-align: center;font-size: 16px;font-weight: 700;margin: 0px 0 15px 0;}
        .bg-form p {font-weight: 400;}
        .bg-form input {border: none;width: 94%;outline: none;background: none;height: auto;padding: 0;margin: 0;}
        .bg-form .form-group {margin-bottom: 15px;}
        .bg-form label {margin-bottom: 10px;text-transform: uppercase;font-weight: 100;}
        .bg-form p.status {text-align: center;}
        .signup-width-div {width: 60%; margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);}
        .gold, a.gold, .gold a {color: #BDA766;}
        .dont-accnt .gold a{ text-transform: uppercase; font-weight: 600;}
        .inline-block {width: 100%;float: left;}
        .inline-block span {display: inline-block;}
        .rememberme {width: 50%;float: left;}
        .rememberme input {float: left;width: auto;margin: 6px 8px 0 0;}
        .forgotpass {width: 50%;text-align: right;}
        .signin {background-color: #baa86d;border-radius: 12px;color: #fff;padding: 13px 121px;margin: 10px 0 10px 0; text-transform: uppercase; font-weight: 600;}
        .w-60 {width: 60%;margin: 0 auto;}
        .text-right {float: right;}
        .mt-30 {margin-top: 10px;}
        .mb-30 {margin-bottom: 10px;}
        .login-logo {vertical-align: middle;text-align: center;margin-top: 50%;}
        .login-logo img {max-width: 100%;width: 40%;}
        .mobile-view {display: none;}
        .pass-icon {position: relative;}
        .pass-icon span {position: absolute;right: 10px;top: 7px;color: #A0A5BA;font-size: 17px;}
        
        @media screen and (max-width: 767px) {
            .mobile-view {display: block;font-size: 12px;}
            .dtop-view {display: none;}
            .wrapper-top {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-mobile.png);background-repeat: no-repeat;background-position: 0 0;background-size: 100%;background-position-y: -20px;}
            .login-logo img{ width: 153px;}
            .login-logo {margin-top: 98px; padding-bottom: 50px;min-height: auto;  margin-bottom: 0px;}
            .bg-form {border-top-left-radius: 1em;border-top-right-radius: 1em;border-bottom-left-radius: 0;padding: 35px;min-height: auto; margin-top: 18px;}
            .signin {width: 210px;padding: 13px 0px;}
            .container-height,.login-logo {height: auto;}
            .rememberme input {float: none;}
            .inline-block span,p.w-60,p.w-60 span {text-align: center;width: 100% !important;margin: 0;padding: 0;}
            .signup-h1 {font-size: 22px; font-weight: bold;}
            .bg-form{ border-top-right-radius: 3em; border-bottom-left-radius:0; width: 98%;}
        }

        @media screen and (max-width: 1199px) and (min-width: 992px) {
            .bg-form {padding: 55px 20px 20px 20px;}
            .signup-width-div{ width: 61%;}
        }

        @media screen and (max-width: 991px) and (min-width: 768px) {
            .bg-form {padding: 50px 15px 20px 15px;}
            .container {padding: 0 10px;}
            .w-60 {width: 100%;}
            .login-logo{ margin-top: 152px; margin-bottom: 60px;}
            .login-logo img{ width: 24%;}
            .bg-form{ border-top-right-radius: 3em; border-bottom-left-radius:0; width: 98%;}
            .wrapper-top{ background-size: 100%;}
            .signup-width-div{ width: 61%; margin-top: 0;}
        }

        @media screen and (max-width: 1440px) and (min-width: 1200px) {
            .bg-form {padding: 75px 15px 35px 15px;}
        }
        @media screen and (max-width: 480px) {
            .login-logo img{ width: 90px;}
            .login-logo{ margin-top: 58px;}
        }
    </style>

    <?php wp_head(); ?>
</head>

<body>
    <!-- Age Confirmation Modal -->
    <?php if (!isset($_COOKIE['age_verified'])): ?>
      <div class="age-overlay">
          <div id="ageModal" class="age-verify">
              <div class="age-bg">
                  <div class="age-logo">
                      <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                  </div>
                  <div class="age-body">
                      <h2>Are you at least 21 years old?</h2>
                      <p>By entering this site you are agreeing to the 
                          <a target="_blank" href="/terms">Terms of Use</a> and 
                          <a target="_blank" href="/privacy-policy">Privacy Policy</a>
                      </p>
                      
                      <button id="under21" class="age-over">No, Exit</button>
                      <button id="over21" class="age-under">I am Over 21</button>
                  </div>
              </div>
          </div> 
      </div>
  <?php endif; ?>

    <div class="wrapper-top dtop-view">
        <div class="wrapper-bottom">
            <form class="animate signup" method="post">

                <div class="container-height">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <a href="/">
                                        <div class="login-logo">
                                            <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-7" style="padding-right: 0;">
                                    <div class="bg-form container-height">
                                        <div class="container signup-width-div">
                                            <h1>Sign Up</h1>
                                            <p class="text-center">Please sign up to get started</p>
                                            <p class="status"></p>
                                            <div class="form-login">
                                                <div class="form-group nameflash">
                                                    <label>NAME</label>
                                                    <div class="form-control">
                                                        <input placeholder="John doe" class="input-sm" type="uname" name="uname" id="uname" required />
                                                    </div>
                                                </div>
                                                <div class="form-group emailflash">
                                                    <label>EMAIL</label>
                                                    <div class="form-control">
                                                        <input placeholder="example@gmail.com" class="input-sm" type="email" name="username" id="username" required />
                                                    </div>
                                                </div>
                                                <div class="form-group passwordflash">
                                                    <label>Password</label>
                                                    <div class="form-control pass-icon">
                                                        <input class="input-sm pass_log_id" type="password" placeholder="Password" name="password" id="password" />
                                                        <span toggle="#password-field" class="fa fa-fw field_icon fa-eye toggle-password"></span>
                                                        <?php if (isset($_GET['redirect_to'])) { ?>
                                                            <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url() . "/" . $_GET['redirect_to']; ?>">
                                                        <?php } else { ?>
                                                            <input type="hidden" name="redirecturl" id="redirecturl" value="">
                                                        <?php } ?>
                                                        <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url(); ?>">
                                                        <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group repasswordflash">
                                                    <label>RE-TYPE Password</label>
                                                    <div class="form-control pass-icon">
                                                        <input class="input-sm pass_log_id1" type="password" placeholder="Password" name="re_password" id="re_password" />
                                                        <span toggle="#re_password-field" class="fa fa-fw field_icon fa-eye toggle-password1"></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class=" text-center">
                                                        <button type="submit" class="signin" id="signup">SIGN
                                                            UP</button>
                                                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                                    </div>
                                                </div>
                                                <div class="dont-accnt">
                                                        <p class="w-60"><span class="text-left">Already have an account?</span> <span class="gold text-right"><a href="/login">Sign In</a></span> </p>
                                                    </div>
                                                <p class="text-center mt-30 mb-30">By signing up you agree to <a href="/terms" class="gold">terms of service</a> and <br> <a href="/privacy-policy" class="gold">privacy policy</a> and represent you are 21 or older</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="mobile-view">
        <div class="wrapper-top">
            <div class="container-height">
                <form class="animate signup" method="post">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="login-logo">
                                        <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                    </div>
                                    <div class="container text-center">
                                        <h1 class="signup-h1">Sign Up</h1>
                                        <p class="text-center">Please sign up to get started</p>
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="bg-form container-height">
                                        <div class="form-login">
                                            <div class="form-group nameflash">
                                                <label>NAME</label>
                                                <div class="form-control">
                                                    <input placeholder="John doe" class="input-sm" type="uname" name="uname" id="uname" required />
                                                </div>
                                            </div>
                                            <div class="form-group emailflash">
                                                <label>EMAIL</label>
                                                <div class="form-control">
                                                    <input placeholder="example@gmail.com" class="input-sm" type="email" name="username" id="username" required />
                                                </div>
                                            </div>
                                            <div class="form-group passwordflash">
                                                <label>Password</label>
                                                <div class="form-control pass-icon">
                                                    <input class="input-sm pass_log_id" type="password" placeholder="Password" name="password" id="password" />
                                                    <span toggle="#password-field" class="fa fa-fw field_icon fa-eye toggle-password"></span>
                                                    <?php if (isset($_GET['redirect_to'])) { ?>
                                                        <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url() . "/" . $_GET['redirect_to']; ?>">
                                                    <?php } else { ?>
                                                        <input type="hidden" name="redirecturl" id="redirecturl" value="">
                                                    <?php } ?>
                                                    <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url(); ?>">
                                                    <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group repasswordflash">
                                                <label>RE-TYPE Password</label>
                                                <div class="form-control pass-icon">
                                                    <input class="input-sm pass_log_id1" type="password" placeholder="Password" name="re_password" id="re_password" />
                                                    <span toggle="#password-field" class="fa fa-fw field_icon fa-eye toggle-password1"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class=" text-center">
                                                    <button type="submit" class="signin" >SIGN UP</button>
                                                    <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                                </div>
                                            </div>
                                            <div class="dont-accnt">
                                                        <p class="w-60"><span class="text-left">Already have an account?</span> <span class="gold text-right"><a href="/login">Sign In</a></span> </p>
                                                    </div>
                                            <p class="text-center mt-30 mb-30">By signing up you agree to <a href="/terms" class="gold">terms of service</a> and <br> <a href="/privacy-policy" class="gold">privacy policy</a> and represent you are 21 or older</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye-slash");

            var input = $(".pass_log_id");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });
         $(document).on('click', '.toggle-password1', function() {
            $(this).toggleClass("fa-eye-slash");

            var input = $(".pass_log_id1");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });

        //add the age modal code
        $(document).ready(function () {
            const currentPath = window.location.pathname.toLowerCase().replace(/\/$/, '');
            const excludedPages = ['/terms', '/privacy-policy'];

            // Check if user is verified via cookie
            const isVerified = document.cookie.includes('age_verified=true');

            // If page is excluded, skip showing modal
            if (excludedPages.includes(currentPath)) {
                $('.age-overlay').hide();
                $('body').removeClass('age-locked');
                return;
            }

            // Show modal and lock page if not verified
            if (!isVerified) {
                $('body').addClass('age-locked'); // lock page
                $('.age-overlay').show(); // show modal
            }

            const over21Btn = document.getElementById('over21');
            const under21Btn = document.getElementById('under21');
            const modal = document.getElementById('ageModal');

            if (over21Btn) {
                over21Btn.addEventListener('click', function () {
                    // Set verification cookie
                    document.cookie = "age_verified=true; path=/; max-age=" + 60 * 60 * 24 * 30;

                    if (modal) modal.style.display = 'none';

                    // Unlock page
                    $('body').removeClass('age-locked');

                    // Remove overlay
                    const overlayElement = document.querySelector('.age-overlay');
                    if (overlayElement) {
                        overlayElement.classList.remove('age-overlay');
                    }
                });
            }

            if (under21Btn) {
                under21Btn.addEventListener('click', function () {
                    window.location.href = "https://www.google.com";
                });
            }
        });

    </script>

</body>

</html>