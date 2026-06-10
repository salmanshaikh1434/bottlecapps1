<?php
    $passkey = 'G)w9:3qga>:U#v(';
    $method = 'aes128';
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrapnew.min.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <title>Sipn Login</title>
    <style>
        /* Fonts */
        @font-face {font-family: "Sen", sans-serif; src: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/Sen-VariableFont_wght.ttf') format('truetype');}
        :root {padding: 0;margin: 0;}
        body {background-color: #151515;padding: 0;margin: 0;color: #fff;}
        img,button {border: none;outline: none;padding: 0;margin: 0;}
        p a {color: #fff;}
        .wrapper-top {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-top.png);background-repeat: no-repeat;background-position: 0 0;background-size: 48%;}
        .wrapper-bottom {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-bottom.png);background-repeat: no-repeat;background-position: left bottom;background-size: 48%;}
        .bg-form {background-color: #282828;border-top-left-radius: 3em;border-bottom-left-radius: 3em;font-family: "Sen", sans-serif;color: #fff;padding: 80px 40px 80px 40px;min-height: 100vh;}
        .bg-form h1 {text-align: center;font-size: 16px;font-weight: 700;margin: 0px 0 15px 0;}
        .bg-form p {font-weight: 400;}
        .bg-form input {border: none;width: 94%;outline: none;background: none;height: auto;padding: 0;margin: 0; line-height: normal !important; position: relative;}
        .bg-form .form-group {margin-bottom: 20px;}
        .bg-form .form-control{ padding: 5px 12px !important;}
        .bg-form label {margin-bottom: 10px;font-weight: 400; text-transform: uppercase;}
        .bg-form p.status {text-align: center;}
        .gold,.gold a {color: #BDA766;}
        .gold:hover {color: #BDA766;}
        .gold:focus {color: #BDA766;}
        .inline-block {width: 100%;float: left;}
        .inline-block span {display: inline-block;}
        .bg-form input::-webkit-input-placeholder{ color: #7e7779; line-height: 22px;}
        input.pass_log_id::placeholder {position: relative;top:2px;font-size:12px;
            color: #7e7779;
            opacity: 1; /* Firefox */
         }

        input.pass_log_id::-ms-input-placeholder { /* Edge 12-18 */
            color: #7e7779;
        }
        .rememberme {width: 50%;float: left;}
        .rememberme input {float: left;width: auto;margin: 3px 8px 0 0;}
        .rememberme input[type="checkbox"]{ border:solid 2px #E3EBF2 !important; border-radius: 0 !important;}
        .forgotpass {width: 50%;text-align: right;}
        .signin {background-color: #baa86d;border-radius: 6px;color: #fff;padding: 13px 100px;margin: 10px 0 20px 0; text-transform: uppercase; font-weight: 600;}
        .w-60 {width: 60%;margin: 0 auto;}
        .text-right {float: right;}
        .mt-30 {margin-top: 10px;}
        .mb-30 {margin-bottom: 10px;}
        .login-logo {vertical-align: middle;text-align: center;margin-top: 50%;}
        .login-logo img {max-width: 100%;width: 40%;}
        .mobile-view {display: none;}
        .pass-icon {position: relative;}
        .pass-icon span {position: absolute;right: 10px;top: 7px;color: #A0A5BA;font-size: 17px;}
        .login-form {width: 60%; margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);}
        .dont-accnt span.gold.text-right {text-transform: uppercase; font-weight: bold;}
        .dont-accnt {margin-bottom: 20px;}
        .dont-accnt .gold a{ font-weight: bold;}

        /* Custom-checkbox */
        .rememberme1{ width: 50%; float: left;}
        .rememberme1 .control {
              display: block;
              position: relative;
              padding-left: 30px;
              cursor: pointer;
              font-size: 14px;
              float: left;
              font-family: "Sen", sans-serif;
            }
            .rememberme1 .control input {
              position: absolute;
              z-index: -1;
              opacity: 0;
              left: 0;
              width: auto;
            }
            .rememberme1 .control__indicator {
              position: absolute;
              top: 2px;
              left: 0;
              height: 15px;
              width: 15px;
              background: #fff;
              border: solid 1px #E3EBF2;
              border-radius: 4px;

            }
            .rememberme1 .control:hover input ~ .control__indicator,
            .rememberme1 .control input:focus ~ .control__indicator {
              background: #fff;
            }
            .rememberme1 .control input:checked ~ .control__indicator {
              background: #BDA766;
            }
            .rememberme1 .control:hover input:not([disabled]):checked ~ .control__indicator,
            .rememberme1 .control input:checked:focus ~ .control__indicator {
              background: #BDA766;
            }
            .rememberme1 .control input:disabled ~ .control__indicator {
              background: #fff;
              border: solid 1px #E3EBF2;
              opacity: 0.6;
              pointer-events: none;
            }
            .rememberme1 .control__indicator:after {
              content: '';
              position: absolute;
              display: none;
            }
            .rememberme1 .control input:checked ~ .control__indicator:after {
              display: block;
            }
            .rememberme1 .control--checkbox .control__indicator:after {
              left: 6px;
              top: 2px;
              width: 3px;
              height: 8px;
              border: solid #fff;
              border-width: 0 2px 2px 0;
              transform: rotate(45deg);
            }
            .rememberme1 .control--checkbox input:disabled ~ .control__indicator:after {
              border-color: #BDA766;
            }
        /* Age Verification Start*/
        .age-overlay {position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(6px); display: flex; justify-content: center; align-items: center; z-index: 9;}
        .age-verify { background: #000; color: #fff; width: 56%; padding: 0px; text-align: center;}
        .age-body{ padding: 0 20px;}
        .age-bg{color: #fff; padding: 0 0 30px 0; text-align: center; margin: 0 auto;}
        .age-bg h2{ font-family: 'Sen'; font-weight: bold; font-size: 40px; color: #BDA766; padding: 0; margin: 30px 0 0 0;}
        .age-bg p{ font-family: 'Sen'; font-weight: bold; font-size: 20px; }
        .age-bg p a{color: #BDA766;}
        .age-logo { border-bottom: solid 1px #4C4C4C; padding: 30px 0;}
        .age-logo img{ max-width: 23%;}
        .age-under, .age-over{ font-family: 'Sen'; font-size: 12px; text-transform: uppercase; color: #fff; border-radius: 25px; padding: 10px 25px; width: 135px; font-weight: bold; margin: 20px 0;}
        .age-under{ background-color: #BDA766; border: solid 1px #BDA766; }
        .age-over{  border:solid 1px #BDA766; background-color: #000; margin-right: 15px; }
        /* age verification End */
        @media screen and (max-width: 767px) {
            .mobile-view {display: block;font-size: 12px;}
            .dtop-view {display: none;}
            .wrapper-top {background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-mobile.png);background-repeat: no-repeat;background-position: 0 0;background-size: 100%;background-position-y: -20px;}
            .login-logo img {width: 25%;}
            .login-logo {margin-top: 16%;padding-bottom: 50px;min-height: auto;}
            .bg-form {border-top-left-radius: 1em;border-top-right-radius: 1em;border-bottom-left-radius: 0;padding: 35px;min-height: auto;}
            .signin {width: 210px; padding: 13px 0px;}
            .container-height, .login-logo {height: auto;}
            .rememberme input {float: none;}
            .rememberme1{ display: flex; justify-content: center; width: 100% !important;}
            .inline-block span, p.w-60, p.w-60 span {text-align: center; width: 100% !important; margin: 0; padding: 0;}
            p {display: inline-block; width: 100%;}
            .login-h1 {font-size: 22px; font-weight: bold;}
            .bg-form{ border-top-right-radius: 3em; border-bottom-left-radius:0; width: 98%;}
            .age-under{ margin: 5px 0 0 0;}
            .age-body{ padding-right: 20px; padding-left: 20px;}
            .age-logo img{ max-width: 150px;}
            .age-bg h2{ font-size: 20px;}
            .age-bg p{ font-size: 13px;}
        }
        @media screen and (max-width: 1199px) and (min-width: 992px) {
            .bg-form {padding: 55px 20px 20px 20px;}
            .login-form {width: 85%;}
        }
        @media screen and (max-width: 991px) and (min-width: 768px) {
            .bg-form {padding: 50px 15px 20px 15px; border-top-left-radius: 3em; border-top-right-radius: 3em; border-bottom-left-radius: 0;}
            .container {padding: 0 10px;}
            .w-60 {width: 100%;}
            .wrapper-top {background-size: 100%;}
            .login-logo img{ width: 24%;}
            .login-logo{ margin-top: 151px; margin-bottom: 60px;}
            .login-form{ top: 275px;}
            .signup-width-div{ margin-top: 0px;}
            .bg-form{ border-top-right-radius: 3em; border-bottom-left-radius:0; width: 98%;}
        }
        @media screen and (max-width: 1440px) and (min-width: 1200px) {
            .bg-form {padding: 75px 15px 35px 15px;}
            .login-form {width: 60%;}
        }
        @media screen and (min-width: 1441px) {
            .dont-accnt .w-60 {width: 50%;}
        }
        @media not all and (min-resolution:.001dpcm) { @media {

            .dont-accnt .gold a{ font-family: 'Sen', ui-monospace !important; font-size: 14px; font-weight: 500;}
        }}
    </style>

    <?php wp_head(); ?>
</head>

<body>
    <div class="wrapper-top dtop-view">
        <div class="wrapper-bottom">
            <form class="animate login" method="post">
                <div class="container-height">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="login-logo">
                                        <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                    </div>
                                </div>
                                <div class="col-md-7" style="padding-right: 0;">
                                    <div class="bg-form container-height">
                                        <div class="container login-form">
                                            <h1>Sign In</h1>
                                            <p class="text-center">Please sign in to your existing account</p>
                                            <p class="status"></p>
                                            <div class="form-login">
                                                <div class="form-group">

                                                    <label>Email</label>
                                                    <div class="form-control">
                                                        <input placeholder="example@gmail.com" class="input-sm" type="email" name="username" id="username" value="<?php echo (!empty($_COOKIE["username"]))? openssl_decrypt($_COOKIE["username"], $method, $passkey):'';?>" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <div class="form-control  pass-icon">
                                                        <input class="input-sm pass_log_id" type="password" placeholder="Password" name="password" id="password" value="<?php echo (!empty($_COOKIE["userpassword"]))? openssl_decrypt($_COOKIE["userpassword"], $method, $passkey):'';?>" />

                                                        <span toggle="#password-field" class="fa fa-fw field_icon fa-eye  toggle-password"></span>

                                                        <?php if (isset($_GET['redirect_to'])) { ?>
                                                            <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url() . "/" . $_GET['redirect_to']; ?>">
                                                        <?php } else { ?>
                                                            <input type="hidden" name="redirecturl" id="redirecturl" value="">
                                                        <?php } ?>
                                                        <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url(); ?>">
                                                        <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to']; ?>">
                                                    </div>
                                                </div>

                                                

                                                <div class="inline-block">
                                                    <!-- <span class="rememberme">
                                                        <input type="checkbox" class="checkbox-round" id="" name="remember" value="1">
                                                        <label> Remember me </label></span> -->

                                                        <div class="rememberme1">
                                                            <label class="control control--checkbox">
                                                                Remember me 
                                                                <input type="checkbox"  id="" name="remember" value="1" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    <span class="gold forgotpass"><a href="/reset-password">Forgot Password</a>
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <div class=" text-center">
                                                        <button type="submit" class="signin" id="signin">Sign In</button>
                                                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                   <div class="dont-accnt">
                                                        <p class="w-60"><span class="text-left">Don't have an account?</span> <span class="gold text-right"><a href="/sign-up">Sign Up</a></span> </p>
                                                    </div>
                                                </div>
                                                <p class="text-center mt-30 mb-30">By signing in you agree to <a href="/terms" class="gold">terms of service</a> and <br> <a href="/privacy-policy" class="gold">privacy policy</a> and represent you are 21 or older</p>
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
    <!-- Mobile -->
    <div class="mobile-view">
        <div class="wrapper-top">
            <form class="animate login" method="post">
                <div class="container-height">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="login-logo">
                                        <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                    </div>
                                    <div class="container text-center">
                                        <h1 class="login-h1">Sign In</h1>
                                        <p class="text-center">Please sign in to your existing account</p>
                                        <p class="status"></p>
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="bg-form container-height">
                                        <div class="form-login">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <div class="form-control">
                                                    <input placeholder="example@gmail.com" class="input-sm" type="email" name="username" id="username" value="<?php echo (!empty($_COOKIE["username"]))? openssl_decrypt($_COOKIE["username"], $method, $passkey):'';?>" required />
                                                </div>
                                            </div>
                                            <div class="form-group"> 
                                                    <label>Password</label>
                                                    <div class="form-control  pass-icon">
                                                        <input class="input-sm pass_log_id" type="password" placeholder="Password" name="password" id="password" value="<?php echo (!empty($_COOKIE["userpassword"]))? openssl_decrypt($_COOKIE["userpassword"], $method, $passkey):'';?>" />

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
                                            <div class="inline-block">
                                                <!-- <span class="rememberme">
                                                    <input type="checkbox" id="" name="remember" value="1">
                                                    <label> Remember me </label></span> -->
                                                    <div class="rememberme1">
                                                            <label class="control control--checkbox">
                                                                Remember me 
                                                                <input type="checkbox"  id="" name="remember" value="1" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                <span class="gold forgotpass"><a href="/reset-password">Forgot Password</a>
                                                </span>
                                            </div>
                                            <div class="row">

                                                <?php if (isset($_GET['redirect_to'])) { ?>
                                                <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url() . "/" . $_GET['redirect_to']; ?>">
                                                <?php } else { ?>
                                                <input type="hidden" name="redirecturl" id="redirecturl" value="">
                                                <?php } ?>
                                                <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url(); ?>">
                                                <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to']; ?>">

                                                <div class=" text-center"><button type="submit" class="signin" id="signin">Sign In</button></div>
                                                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                            </div>
                                            <div class="col-md-12">
                                               <div class="dont-accnt">
                                                    <p class="w-60"><span class="text-left">Don't have an account?</span>
                                                        <span class="gold text-right"><a href="/sign-up">Sign Up</a></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="text-center mt-30 mb-30">By signing in you agree to <a href="/terms" class="gold">terms of service</a> and <br> <a href="/privacy-policy" class="gold">privacy policy</a> and represent you are 21 or older</p>
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





    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye-slash");

            var input = $(".pass_log_id");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });


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
