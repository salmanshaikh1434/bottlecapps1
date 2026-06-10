<?php
/**
 * Template Name: SIPN Reset Passwords
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
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrapnew.min.css"
        type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <title>Sipn Login</title>
    <style>
        /* Fonts */
        @font-face {
            font-family: "Sen", sans-serif;
            src: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/Sen-VariableFont_wght.ttf') format('truetype');
        }

        :root {
            padding: 0;
            margin: 0;
        }

        body {
            background-color: #151515;
            padding: 0;
            margin: 0;
            color: #fff;
        }

        img,
        button {
            border: none;
            outline: none;
            padding: 0;
            margin: 0;
        }

        p a {
            color: #fff;
        }

        .wrapper-top {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/BG-Asset-top.png);
            background-repeat: no-repeat;
            background-position: 0 0;
            background-size: 48%;
        }

        .wrapper-bottom {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/BG-Asset-bottom.png);
            background-repeat: no-repeat;
            background-position: left bottom;
            background-size: 48%;
        }

        .bg-form {
            background-color: #282828;
            border-top-left-radius: 3em;
            border-bottom-left-radius: 3em;
            font-family: "Sen", sans-serif;
            color: #fff;
            padding: 80px 40px 80px 40px;
            min-height: 100vh;
        }

        .bg-form h1 {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 0px 0 15px 0;
        }

        .bg-form p {
            font-weight: 400;
        }

        .bg-form .form-group {
            margin-bottom: 20px;
        }

        .bg-form .form-control {
            padding: 5px 12px !important;
        }

        .bg-form label {
            margin-bottom: 10px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .bg-form p.status {
            text-align: center;
        }

        .gold,
        .gold a,
        .gold:hover,
        .gold:focus {
            color: #BDA766;
        }

        .inline-block {
            width: 100%;
            float: left;
        }

        .inline-block span {
            display: inline-block;
        }

        .bg-form input::-webkit-input-placeholder {
            color: #7e7779;
            line-height: 22px;
        }

        input.pass_log_id::placeholder {
            position: relative;
            top: 2px;
            font-size: 12px;
            color: #7e7779;
            opacity: 1;
        }

        input.pass_log_id::-ms-input-placeholder {
            color: #7e7779;
        }

        .rememberme {
            width: 50%;
            float: left;
        }

        .rememberme input {
            float: left;
            width: auto;
            margin: 3px 8px 0 0;
        }

        .rememberme input[type="checkbox"] {
            border: solid 2px #E3EBF2 !important;
            border-radius: 0 !important;
        }

        .forgotpass {
            width: 50%;
            text-align: right;
        }

        .new-reset {
            background-color: #baa86d;
            border-radius: 6px;
            color: #fff;
            padding: 13px 0px;
            margin: 10px 0 20px 0;
            text-transform: uppercase;
            font-weight: 600;
            width: 150px;
            white-space: nowrap;
        }

        .w-60 {
            width: 60%;
            margin: 0 auto;
        }

        .text-right {
            float: right;
        }

        .mt-30 {
            margin-top: 10px;
        }

        .mb-30 {
            margin-bottom: 10px;
        }

        .login-logo {
            vertical-align: middle;
            text-align: center;
            margin-top: 50%;
        }

        .login-logo img {
            max-width: 100%;
            width: 40%;
        }

        .mobile-view {
            display: none;
        }

        .pass-icon {
            position: relative;
        }

        .pass-icon span {
            position: absolute;
            right: 10px;
            color: #A0A5BA;
            font-size: 17px;
        }

        .login-form {
            width: 60%;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .dont-accnt span.gold.text-right {
            text-transform: uppercase;
            font-weight: bold;
        }

        .dont-accnt {
            margin-bottom: 20px;
        }

        .dont-accnt .gold a {
            font-weight: bold;
        }

        /* Custom-checkbox */
        .rememberme1 {
            width: 50%;
            float: left;
        }

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

        .rememberme1 .control:hover input~.control__indicator,
        .rememberme1 .control input:focus~.control__indicator {
            background: #fff;
        }

        .rememberme1 .control input:checked~.control__indicator {
            background: #BDA766;
        }

        .rememberme1 .control:hover input:not([disabled]):checked~.control__indicator,
        .rememberme1 .control input:checked:focus~.control__indicator {
            background: #BDA766;
        }

        .rememberme1 .control input:disabled~.control__indicator {
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

        .rememberme1 .control input:checked~.control__indicator:after {
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

        .rememberme1 .control--checkbox input:disabled~.control__indicator:after {
            border-color: #BDA766;
        }

        @media screen and (max-width: 767px) {
            .mobile-view {
                display: block;
                font-size: 12px;
            }

            .dtop-view {
                display: none;
            }

            .wrapper-top {
                background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/BG-Asset-mobile.png);
                background-repeat: no-repeat;
                background-position: 0 0;
                background-size: 100%;
                background-position-y: -20px;
            }

            .login-logo img {
                width: 25%;
            }

            .login-logo {
                margin-top: 16%;
                padding-bottom: 50px;
                min-height: auto;
            }

            .bg-form {
                border-top-left-radius: 1em;
                border-top-right-radius: 1em;
                border-bottom-left-radius: 0;
                padding: 35px;
                min-height: auto;
            }

            .signin {
                width: 210px;
                padding: 13px 0px;
            }

            .container-height,
            .login-logo {
                height: auto;
            }

            .rememberme input {
                float: none;
            }

            .rememberme1 {
                display: flex;
                justify-content: center;
                width: 100% !important;
            }

            .inline-block span,
            p.w-60,
            p.w-60 span {
                text-align: center;
                width: 100% !important;
                margin: 0;
                padding: 0;
            }

            p {
                display: inline-block;
                width: 100%;
            }

            .login-h1 {
                font-size: 22px;
                font-weight: bold;
            }
        }

        @media screen and (max-width: 1199px) and (min-width: 992px) {
            .bg-form {
                padding: 55px 20px 20px 20px;
            }

            .login-form {
                width: 85%;
            }
        }

        @media screen and (max-width: 991px) and (min-width: 768px) {
            .bg-form {
                padding: 50px 15px 20px 15px;
                border-top-left-radius: 3em;
                border-top-right-radius: 3em;
                border-bottom-left-radius: 0;
                width: 98%;
            }

            .container {
                padding: 0 10px;
            }

            .w-60 {
                width: 100%;
            }

            .login-logo img {
                width: 24%;
            }

            .wrapper-top {
                background-size: 100%;
            }

            .login-logo {
                margin-top: 153px;
                padding-bottom: 60px;
            }

            .login-form {
                top: 27%;
                width: 66%;
            }

        }

        @media screen and (max-width: 1440px) and (min-width: 1200px) {
            .bg-form {
                padding: 75px 15px 35px 15px;
            }

            .login-form {
                width: 60%;
            }
        }

        @media screen and (min-width: 1441px) {
            .dont-accnt .w-60 {
                width: 50%;
            }
        }

        @media not all and (min-resolution:.001dpcm) {
            @media {
                .dont-accnt .gold a {
                    font-family: 'Sen', ui-monospace !important;
                    font-size: 14px;
                    font-weight: 500;
                }
            }
        }

        .form-group {
            position: relative;
        }

        .field_icon {
            position: absolute;
            right: 10px;
            top: 60%;
            cursor: pointer;
            color: #A0A5BA;
        }

        input[type="password"] {
            padding-right: 30px;
        }

        @media screen and (max-width: 480px) {
            .new-reset {
                margin-top: 0;
                margin-bottom: 10px;
                padding: 10px 0;
            }

        }
    </style>

    <?php wp_head(); ?>
</head>

<body>
    <div class="wrapper-top dtop-view">
        <div class="wrapper-bottom">
            <form class="animate login" id="reset_form" method="post">
                <div class="container-height">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="login-logo">
                                        <a href="/"><img
                                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                    </div>
                                </div>
                                <div class="col-md-7" style="padding-right: 0;">
                                    <div class="bg-form container-height">
                                        <div class="container login-form">
                                            <h1>Forgot Password</h1>
                                            <p class="status"></p>
                                            <div class="msg-container" style="display:none; padding:5px;">
                                                <span class="termspp"></span>
                                            </div>
                                            <div class="form-login">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input placeholder="example@gmail.com" class="input-sm form-control"
                                                        type="email" id="reset_email" name="email" required />
                                                </div>
                                                <div class="form-group" style="display: none;" id="otp">
                                                    <label>OTP</label>
                                                    <input class="input-sm form-control" type="text" placeholder="OTP"
                                                        id="reset_otp" name="otp" required disabled />
                                                </div>
                                                <div class="form-group pass-icon" style="display: none;" id="pass">
                                                    <label>Password</label>
                                                    <input class="input-sm form-control pass_log_id" type="password"
                                                        placeholder="Password" id="reset_pass" name="pass"
                                                        autocomplete="off" disabled />
                                                    <span toggle="#reset_pass"
                                                        class="fa fa-fw field_icon fa-eye toggle-password"></span>
                                                </div>
                                                <div class="row">
                                                    <div class="text-center">
                                                        <button id="reset_form_btn" class="new-reset">Clear</button>
                                                        <button id="reset_pass_btn" class="new-reset">Request
                                                            OTP</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="dont-accnt">
                                                            <p class="w-60"><span class="text-left">Don't have an
                                                                    account?</span> <span class="gold text-right"><a
                                                                        href="/sign-up">Sign Up</a></span> </p>
                                                        </div>
                                                    </div>
                                                </div>
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
            <form id="reset_form_mobile" method="post">
                <div class="container-height">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="login-logo">
                                        <a href="/"><img
                                                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                                    </div>
                                    <div class="container text-center">
                                        <h1 class="login-h1">Forgot Password</h1>
                                        <p class="status"></p>
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="bg-form container-height">
                                        <div class="form-login">
                                            <div class="form-group">
                                                <label>Email</label>

                                                <input placeholder="example@gmail.com" class="input-sm form-control"
                                                    type="email" id="reset_email_mobile" name="email" required />

                                            </div>
                                            <div class="form-group" style="display: none;" id="otp_mob">
                                                <label>OTP</label>

                                                <input class="input-sm form-control" type="text" placeholder="OTP"
                                                    id="reset_otp_mobile" name="otp" required disabled />
                                            </div>

                                            <div class="form-group pass-icon" style="display: none;" id="pass_mob">
                                                <label>Password</label>

                                                <input class="input-sm pass_log_id form-control" type="password"
                                                    placeholder="Password" id="reset_password_mobile" name="pass"
                                                    autocomplete="off" disabled />
                                                <span toggle="#password-field"
                                                    class="fa fa-fw field_icon fa-eye  toggle-password"></span>

                                            </div>
                                            <div class="row">
                                                <div class=" text-center">
                                                    <button id="reset_form_btn" class="new-reset">Clear</button>
                                                    <button type="submit" id="reset_pass_mobile"
                                                        class="new-reset">Request OTP</button>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dont-accnt">
                                                        <p class="w-60"><span class="text-left">Don't have an
                                                                account?</span> <span class="gold text-right"><a
                                                                    href="/sign-up">Sign Up</a></span> </p>
                                                    </div>
                                                </div>
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
    <script>

        document.getElementById('reset_form_btn').addEventListener('click', function () {

            document.getElementById('reset_email').value = '';
            document.getElementById('reset_otp').value = '';
            document.getElementById('reset_pass').value = '';

            var otp = document.getElementById('otp');
            var pass = document.getElementById('pass');
            otp.style.display = "none";
            pass.style.display = "none";
            document.getElementById('reset_pass_btn').classList.remove('reset_pwd');;
            document.getElementById('reset_otp').disabled = true;
            document.getElementById('reset_pass').disabled = true;
        });



        $(document).ready(function () {

            $(document).on('click', '.toggle-password', function () {
                var input = $('#pass_reset');
                var input2 = $('#reset_password_mobile');

                // Toggle input types
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
                input2.attr('type', input2.attr('type') === 'password' ? 'text' : 'password');

                // Toggle eye icon class based on input field type
                if (input.attr('type') === 'password' || input2.attr('type') === 'password') {
                    $(this).addClass("fa-eye-slash");
                } else {
                    $(this).removeClass("fa-eye-slash");
                }
            });



            // Reset password functionality
            $('#reset_pass_btn').on('click', function (e) {
                e.preventDefault();
                var isResetPwd = $(this).hasClass('reset_pwd');
                var sendInfo = {
                    'email': $('#reset_email').val(),
                    'code': isResetPwd ? $('#reset_otp').val() : undefined,
                    'password': isResetPwd ? $('#reset_pass').val() : undefined
                };

                // Basic validation before sending AJAX request
                if (!validateForm(sendInfo, isResetPwd)) {
                    displayMessage('Please fill in all required fields correctly.', 'error');
                    return;
                }

                var ajaxUrl = isResetPwd ? '/wp-json/user/v1/set-password' : '/wp-json/user/v1/reset-password';

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    contentType: "application/json",
                    url: ajaxUrl,
                    data: JSON.stringify(sendInfo),
                    success: function (data) {
                        handleSuccess(data, isResetPwd);
                    },
                    error: function (results) {
                        handleError(results);
                    }
                });
            });

            function handleSuccess(data, isResetPwd) {
                displayMessage(data.message);
                if (!isResetPwd) {
                    // Show OTP and Password inputs for next step
                    $('#reset_otp, #reset_pass').prop('disabled', false).closest('.form-group').show();
                    $('#reset_pass_btn').addClass('reset_pwd').text('RESET PASSWORD');
                } else {
                    // Redirect after successful password reset
                    displayMessage('Password changed successfully.', 'success');
                    setTimeout(function () {
                        window.location.href = "/login";
                    }, 3000);
                }
            }

            function handleError(results) {
                var message = results.responseJSON && results.responseJSON.message ? results.responseJSON.message : 'An error occurred. Please try again.';
                displayMessage(message, 'error');
            }

            function displayMessage(message, type = 'success') {
                $('.msg-container .termspp').text(message).toggleClass('error', type === 'error');
                $('.msg-container').show();
            }

            function validateForm(info, isResetPwd) {
                // Basic email validation and ensure required fields are filled
                return info.email && (isResetPwd ? (info.code && info.password) : true);
            }
        });


        $(document).ready(function () {
            // Toggle password visibility
            $(document).on('click', '.toggle-password', function () {
                $(this).toggleClass("fa-eye-slash");
                var input = $(this).siblings('input');
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
            });

            // Handle form submission
            $('#reset_form_mobile').on('submit', function (e) {
                e.preventDefault();

                // Check if the button has the class 'reset_pwd'
                var isResetPwdmobile = $('#reset_pass_mobile').hasClass('reset_pwd_mobile');
                var sendInfo = {
                    'email': $('#reset_email_mobile').val(),
                    'code': isResetPwd ? $('#reset_otp_mobile').val() : undefined,
                    'password': isResetPwd ? $('#reset_pass_mobile').val() : undefined
                };
                console.log(sendInfo);
                // Basic validation before sending AJAX request
                if (!validateForm(sendInfo, isResetPwd)) {
                    displayMessage('Please fill in all required fields correctly.', 'error');
                    return;
                }


                var ajaxUrl = isResetPwd ? '/wp-json/users/v1/set-password' : '/wp-json/user/v1/reset-password';

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    contentType: "application/json",
                    url: ajaxUrl,
                    data: JSON.stringify(sendInfo),
                    success: function (data) {
                        handleSuccess(data, isResetPwd);
                    },
                    error: function (results) {
                        handleError(results);
                    }
                });
            });

            function handleSuccess(data, isResetPwd) {
                displayMessage(data.message);
                if (!isResetPwdmobile) {
                    // Show OTP and Password inputs for next step
                    $('#reset_otp_mobile, #reset_pass_mobile').prop('disabled', false).closest('.form-group').show();
                    $('#reset_pass_mobile').addClass('reset_pwd_mobile').text('RESET PASSWORD');
                } else {
                    // Redirect after successful password reset
                    setTimeout(function () {
                        window.location.href = "/login";
                    }, 2000);
                }
            }

            function handleError(results) {
                var message = results.responseJSON && results.responseJSON.message ? results.responseJSON.message : 'An error occurred. Please try again.';
                displayMessage(message, 'error');
            }

            function displayMessage(message, type = 'success') {
                console.log(message);
                $('.msg-container .termspp').text(message).toggleClass('error', type === 'error');
                $('.msg-container').show();
            }

            function validateForm(info, isResetPwd) {
                // Basic email validation and ensure required fields are filled
                return info.email && (isResetPwd ? (info.code && info.password) : true);
            }
        });


    </script>

</body>

</html>