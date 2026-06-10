<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/bootstrapnew.min.css" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
 
        <title>Sipn Login</title>
        <style>
             /* Fonts */
             @font-face {
                font-family: 'Sen';
                src: url('<?php echo get_stylesheet_directory_uri();?>/assets/fonts/Sen-VariableFont_wght.ttf') format('truetype');
            }
            :root{padding: 0; margin: 0;}
            body{ background-color: #000; padding: 0; margin: 0; color: #fff;}
            img, button{ border: none; outline: none; padding: 0; margin: 0;}
            p a{color: #fff;}
            .wrapper-top{ background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-top.png); background-repeat: no-repeat; background-position: 0 0; background-size: 48%;}
            .wrapper-bottom{ background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-bottom.png); background-repeat: no-repeat; background-position: left bottom; background-size: 48%;}
            .bg-form{background-color: #282828; border-top-left-radius: 3em; border-bottom-left-radius: 3em; font-family: 'Sen'; color: #fff; padding: 80px 40px 80px 40px; min-height: 100vh;}
            .bg-form h1{ text-align: center; font-size: 16px; font-weight: 700; margin: 0px 0 15px 0;}
            .bg-form p{ font-weight: 400;}
            .bg-form input{ border: none; width: 100%; outline: none; background: none; height: auto; padding: 0; margin: 0;}
            .bg-form .form-group{ margin-bottom: 15px;}
            .bg-form label{margin-bottom: 10px; text-transform: uppercase; font-weight: 400;}
            .gold, a.gold{ color: #BDA766;}
            .inline-block{width: 100%; float: left;}
            .inline-block span{ display: inline-block;}
            .rememberme{ width: 50%; float: left;}
            .rememberme input{ float: left; width: auto; margin:6px 8px 0 0;}
            .forgotpass{ width: 50%; text-align: right;}
            .signin{background-color: #baa86d;border-radius: 12px; color: #fff; padding: 13px 121px; margin: 10px 0 10px 0;}
            .w-60{ width: 60%; margin: 0 auto;}
            .text-right{ float: right;}
            .mt-30{margin-top: 10px;}
            .mb-30{margin-bottom: 10px;}
            .login-logo{ vertical-align: middle; text-align: center; margin-top: 50%;}
            .login-logo img{ max-width: 100%; width: 40%;}
            .mobile-view{ display: none;}
            .pass-icon{ position: relative;}
            .pass-icon span{position: absolute; right: 10px; top: 10px;}
            @media screen and (max-width: 767px) {
                .mobile-view{display: block; font-size: 12px;}
                .dtop-view{display: none;}
                .wrapper-top{ background-image: url(<?php echo get_stylesheet_directory_uri();?>/assets/images/BG-Asset-mobile.png); background-repeat: no-repeat; background-position: 0 0; background-size: 100%; background-position-y: -20px;}
                .login-logo img{width: 25%;}
                .login-logo{ margin-top: 23%; padding-bottom: 50px; min-height: auto;}
                .bg-form{ border-top-left-radius: 3em; border-top-right-radius: 3em; border-bottom-left-radius: 0; padding: 20px; min-height: auto;  }
                .signin{ width: 100%; padding: 13px 0px;}
                .container-height, .login-logo{ height: auto;}
                .rememberme input{ float: none;}
                .inline-block span, p.w-60, p.w-60 span{ text-align: center; width: 100% !important; margin:0; padding: 0;}
            }
            @media screen and (max-width: 1199px) and (min-width: 992px) {
                .bg-form{padding: 55px 20px 20px 20px;}
            }
            @media screen and (max-width: 991px) and (min-width: 768px) {
                .bg-form{padding: 50px 15px 20px 15px;}
                .container{ padding: 0 10px;}
                .w-60{ width: 100%;}
            }
            @media screen and (max-width: 1440px) and (min-width: 1200px) {
                .bg-form{padding: 75px 15px 35px 15px;}
            }

        </style>

<?php wp_head(); ?>
    </head>
    <body>
            <div class="wrapper-top dtop-view">
                <div class="wrapper-bottom">
                    <form class="animate" id="login" method="post">
                        
                    <div class="container-height">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="login-logo">
                                            <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/logo-goldsmall.png" />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="bg-form container-height">
                                            <div class="container">
                                                <h1>Log In</h1>
                                                <p class="text-center">Please sign in to your existing account</p>
                                                <p class="status"></p>
                                                <div class="form-login">
                                                    <div class="form-group">
                                                          
                                                            <label>Email</label>
                                                            <div class="form-control">
                                                            <input placeholder="example@gmail.com" class="input-sm" type="email"  name="username" id="username" required/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <div class="form-control  pass-icon">
                                                            <input class="input-sm pass_log_id" type="password" placeholder="* * * * * * *" name="password" id="password"   />
                                                         
                                                            <span toggle="#password-field" class="fa fa-fw field_icon fa-eye fa-eye-slash toggle-password"></span>


                                                                                            <?php if(isset($_GET['redirect_to'])){?>
                                              <input type="hidden" name="redirecturl" id="redirecturl" value="<?php echo site_url()."/".$_GET['redirect_to'];?>">
                                              <?php } else {?>
                                              <input type="hidden" name="redirecturl" id="redirecturl" value="">
                                              <?php } ?>
                                               <input type="hidden" name="redirectsite" id="redirectsite" value="<?php echo site_url();?>">
                                              <input type="hidden" name="redirectpart" id="redirectpart" value="<?php echo $_GET['redirect_to'];?>">
                                                        </div>
                                                    </div>

                                                    <div class="inline-block">
                                                        <span class="rememberme">
                                                            <input type="checkbox" id="" name="" value="">
                                                            <label> Remember me </label></span>
                                                            <span class="gold forgotpass"><a href="/reset-password">Forgot Password</a>
                                                            </span>
                                                    </div>
                                                    <div class="row">
                                                        <div class=" text-center">
                                                            <button type="submit" class="signin" id="signin">Log In</button>
                                                             <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12"><p class="w-60"><span class="text-left">Dont't have an account?</span> <span class="gold text-right"><a href="/sign-up">Sign Up</a></span> </p></div>
                                                    <p class="text-center mt-30 mb-30">By signing in you agree to <a href="/terms" class="gold">terms of service and <br>
                                                        privacy policy</a> and represent you are 21 or older</p>
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
            </div>
            <script>
                $(document).on('click', '.toggle-password', function() {
                    $(this).toggleClass("fa-eye-slash");

                    var input = $(".pass_log_id");
                    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
                    });
            </script>
    </body>
</html>