<?php

/**
 * Template Name: SIPN Edit Profile
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
?>
<?php get_header(); ?>

<?php
global $wpdb;
if (!is_user_logged_in()) {
    wp_redirect("/");
    exit;
}
$cur_user = wp_get_current_user();
$user_details = get_user_meta($cur_user->data->ID);
$curloginname = $cur_user->data->display_name;
$curemail = $cur_user->data->user_email;
$unsubscribe = $cur_user->data->unsubscribe;
$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');
?>
<article class="col-md-10">
    <div class="wrapper-top">
        <div class="wrapper-bottom">
            <div class="container">
                <div class="profile-edit-page newsignup">
                    <div class="col-md-2 col-sm-12 wdf-100">
                        <div class="profile-img-edit">

                            <div class="profile-pic-edit-page">
                                <?php if ($avatar) { ?>
                                    <img id="profile_imge_output" src="<?php echo $avatar; ?>" alt="">
                                    <a href="javascript:void(0);" class="remove-photo-edit remove-profile-img">Remove
                                        Photo</a>
                                <?php } else { ?>
                                    <img id="profile_imge_output"
                                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/img-profile1.jpg">
                                    <a class="remove-photo-edit"></a>
                                <?php } ?>

                                <br>
                                <div class="change-img-div ">
                                    <span
                                        class="btn btn-change-img"><?php echo !empty($avatar) ? "Change Photo" : "Choose Image"; ?></span>
                                    <input accept="image/*" capture="camera" class="fileInput upoad_file_edit"
                                        id="pImage" name="pImage" type="file">
                                    <input type="hidden" id="profile_pic" name="profile_pic" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12 wdf-100">
                        <div class="row">
                            <div class="edit-fields-div">
                                <div class="form-group username-flash-message">
                                    <label for="usr">Name</label>
                                    <input type="text" class="form-control" value="<?php echo $curloginname; ?>"
                                        name="username" id="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="usr">Email Address </label>
                                    <input type="text" class="form-control" id="email" value="<?php echo $curemail; ?>"
                                        readonly>
                                </div>
                                <div class="form-group bio-flash-message">
                                    <label for="usr">Bio </label>
                                    <input type="text" class="form-control" name="bio"
                                        value="<?php echo $user_details['bio'][0]; ?>" id="bio">
                                </div>
                                <div class="form-group dob-flash-message">
                                    <label for="usr">Date of Birth</label>
                                    <?php
                                    $stored_dob = $user_details['date_of_birth'][0];
                                    $dob = date('Y-m-d', strtotime($stored_dob));

                                    // Calculate the maximum allowable date (21 years ago)
                                    $max_dob = date('Y-m-d', strtotime('-21 years'));
                                    ?>
                                    <input type="date" class="form-control" name="dob" value="<?php echo $dob; ?>"
                                        id="dob" max="<?php echo $max_dob; ?>" required>
                                </div>


                                <div class="form-group ">
                                    <label for="usr">Address</label>
                                    <input type="text" class="form-control" name="address" id="ship-address"
                                        value="<?php echo $user_details['address'][0]; ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group city-flash-message">
                                            <label for="usr">City </label>
                                            <input type="text" class="form-control"
                                                value="<?php echo $user_details['city'][0]; ?>" name="city"
                                                id="locality">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group state-flash-message">
                                            <label for="usr">State </label>
                                            <input type="text" class="form-control"
                                                value="<?php echo $user_details['state'][0]; ?>" name="state"
                                                id="state">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group zip-flash-message">
                                    <label for="usr">Zip Code</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo $user_details['zipcode'][0]; ?>" name="zip" id="postcode"
                                        pattern="[0-9]{5}" title="Five digit zip code" inputmode="numeric" maxlength="5"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>


                                <div class="form-group phone-flash-message">
                                    <label for="usr">Phone Number </label>
                                    <input type="text" class="form-control"
                                        value="<?php echo $user_details['phone_number'][0]; ?>" id="phone" name="phone">
                                </div>
                                <div class="row">
                                    <div class="delete-account">
                                        <a href="javascript:void(0);" class="delete-span" data-toggle="modal"
                                            data-target="#reportModal"><i class="far fa-trash-alt"></i> Delete
                                            Account</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="btns-save-cancel">
                                        <a href="<?php echo bbp_get_user_profile_url($cur_user->data->ID); ?>"
                                            class="btn btn-profile-cancel">Cancel</a>
                                        <button type="submit" class="btn btn-profile-save signin">Save Changes</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<!-- for edit modal report -->
<div id="reportModal" class="modal">
    <div class="modal-content">
        <div class="report">
            <p><strong>Please enter password</strong></p>
            <input type="password" class="password_field_delete" id="cpass" placeholder="Enter your password"
                required="required" />
            <p class="content_delete">Are you sure, you want to delete your SIPN account?</p>
            <div class="row">
                <div class="btns-cancel-proceed">
                    <a href="javascript:void(0);" rep="1" class="report_post"><button
                            class="btn btn-profile-cancel">Cancel</button></a>
                    <a href="javascript:void(0);" rep="0" class="report_post"><button
                            class="btn btn-profile-save">Proceed</button></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-emailverification fade in" id="openpopup1" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-emailverification modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <i class="fa fa-info-circle fa-info-circle-custom" aria-hidden="true"></i>
            </div>
            <div class="modal-body">
                <div class="email-verification-text">Email not verified</div>
                <div class="email-verification-content">We sent an email to you please verify your email to continue
                </div>
                <div class="resendemail-main"><a href="javascript:void(0);" class="resendemail"
                        data-id="<?php echo $curemail; ?>" id="resendemail">Resend verification mail</a></div>
            </div>
        </div>
    </div>
</div>
<div id="common_alert" class="modal" style="z-index:10200;">
    <div class="modal-content">
        <div class="report">
            <p class="content_delete" id="alert-msg"></p>
        </div>
    </div>
</div>
<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    // $(document).ready(function () {
    //     $('#dob').on('keyup change', function () {
    //         var maxDate = new Date();
    //         maxDate.setFullYear(maxDate.getFullYear() - 21); // Maximum date (21 years ago)
    //         var minDate = new Date();
    //         minDate.setFullYear(minDate.getFullYear() - 100); // Minimum date (100 years ago)

    //         var inputDate = new Date($(this).val());
    //         $(this).removeClass('is-invalid');

    //         // Check if the date entered is beyond the max date or older than 100 years
    //         if ($(this).val() && (inputDate > maxDate || inputDate < minDate)) {
    //             // Clear the invalid input date
    //             $(this).val('');

    //             // Optionally mark the field as invalid
    //             $(this).addClass('is-invalid');

    //             // Set the alert message
    //             $('#alert-msg').text("You are under restricted age group");

    //             // Show the alert modal
    //             $('#common_alert').show().delay(3000).fadeOut(); // Show for 3 seconds

    //             return false; // Prevent further actions for now
    //         }
    //     });
    // });

    $(document).ready(function () {
        $('#dob').on('blur', function () {
            var maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - 21); // Maximum date (21 years ago)
            var minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 100); // Minimum date (100 years ago)

            var inputDate = new Date($(this).val());
            $(this).removeClass('is-invalid');

            // Check if the date entered is beyond the max date or older than 100 years
            if ($(this).val() && (inputDate > maxDate || inputDate < minDate || isNaN(inputDate.getTime()))) {
                // Clear the invalid input date
                $(this).val('');

                // Optionally mark the field as invalid
                $(this).addClass('is-invalid');

                // Set the alert message
                $('#alert-msg').text("You are under restricted age group");

                // Show the alert modal
                $('#common_alert').show().delay(3000).fadeOut(); // Show for 3 seconds

                return false; // Prevent further actions for now
            }
        });
    });



    $(document).ready(function () {


        $('body').on('click', '#emailverifiedprofile', function () {
            alert('Your email is verified');
        });

        $('body').on('click', '.report-tl-forum', function (e) {

            var modal = document.getElementById("reportModal");
            modal.style.display = "block";

        });

        $('body').on('click', '.close', function (e) {

            var modal = document.getElementById("reportModal");
            modal.style.display = "none";

        });

        $('body').on('click', '.btn-profile-cancel', function (e) {

            var modal = document.getElementById("common-confirm");
            modal.style.display = "none";
            location.reload();
        });

        $('body').on('click', '.report_post', function (e) {
            var reason = $(this).attr('rep');
            if (reason == '1') {
                var modal1 = document.getElementById("reportModal");
                modal1.style.display = "none";
                location.reload();
            } else {
                var cpass = $('#cpass').val();
                if (cpass == '') {
                    // Show the modal with the message "Enter password"
                    $('.content_delete').text('Enter password');
                    var modal = document.getElementById("common-confirm");
                    modal.style.display = "block";
                } else {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: site_script_object.ajaxurl,
                        data: {
                            'action': 'ajaxdelprofile', //calls wp_ajax_nopriv_ajaxlogin
                            'reason': reason,
                            'cpass': cpass,
                            'nonce': site_script_object.nonce,
                        },
                        success: function (data) {
                            if (data.status == 0) {
                                var modal1 = document.getElementById("reportModal");
                                modal1.style.display = "none";
                                $('.content_delete').text('Wrong password.');
                                var modal = document.getElementById("common-confirm");
                                modal.style.display = "block";
                            } else {
                                var modal1 = document.getElementById("reportModal");
                                modal1.style.display = "none";

                                $('.content_delete').text('Profile deleted successfully');
                                var modal = document.getElementById("common-confirm");
                                modal.style.display = "block";
                                // Optional: Reload after a delay
                                setTimeout(function () {
                                    location.reload();
                                }, 2000); // Wait 2 seconds before reloading
                            }
                        }
                    });
                }
            }
        });

        $('body').on('click', '.check', function () {
            var checkStatus = this.checked ? 'ON' : 'OFF';
            if (checkStatus == 'ON') {
                $('#checkval').val('0');
                //$('.checkbox').text('Unsubscribe');
            } else {
                $('#checkval').val('1');
                //$('.subscribeunsubscribe').text('Subscribe');
            }
            $sval = $('#checkval').val();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: site_script_object.ajaxurl,
                data: {
                    'action': 'ajaxsubscribeunsubscribe', //calls
                    'sval': $sval,
                    'nonce': site_script_object.nonce,
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data.status);
                    if (data == '0') {
                        alert('Profile updated successfully');

                        // $(".op").append(
                        //            '<span class="flash-message">' + data.message + "</span>"
                        //          );
                    } else {

                    }
                    //$(".op").show();
                    // var modal = document.getElementById("reportModal");
                    // modal.style.display = "none";
                    //if(data.message=='Forum is reported successfully.'){
                    //alert('Thanks For Reporting');
                    //}
                }
            });



        });


        var cropper;

        // Image file validation and preview
        $("#pImage").on("change", function (event) {
            var ext = $('#pImage').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert('Invalid image type');
                return false;
            }

            var files = event.target.files;
            if (files && files.length > 0) {
                var file = files[0];
                var reader = new FileReader();

                reader.onload = function (e) {
                    // Set the image preview before cropping
                    $('#profile_imge_output').attr('src', e.target.result);
                    $("#profile_imge_output").show();

                    // Open Cropper.js in the modal
                    $('#image').attr('src', e.target.result);
                    $('#cropperModal').show();

                    // Initialize Cropper.js
                    if (cropper) {
                        cropper.destroy(); // Destroy any previous cropper instance
                    }
                    cropper = new Cropper(document.getElementById('image'), {
                        aspectRatio: 1, // Square crop
                        viewMode: 1
                    });
                };

                reader.readAsDataURL(file);
            }
        });

        // Handle cropping and saving the image
        $('#cropButton').on('click', function () {
            if (cropper) {
                var canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });

                var croppedImage = canvas.toDataURL('image/png');

                // Set the cropped image as the profile picture
                $('#profile_imge_output').attr('src', croppedImage);

                // Store the cropped image data (base64 without the data prefix) in a hidden input
                var img_data = croppedImage.split(',')[1]; // Remove the base64 prefix
                $('#profile_pic').val(img_data);

                // Close the modal and destroy the cropper instance
                $('#cropperModal').hide();
                cropper.destroy();
            }
        });

        // Close the cropper modal
        $('.closecropper').on('click', function () {
            $('#cropperModal').hide();
            if (cropper) {
                cropper.destroy(); // Destroy cropper instance
            }
            location.reload();
        });

        // Remove the profile image
        $('.remove-profile-img').on('click', function () {
            $('#profile_imge_output').attr('src', "<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chat/img-profile1.jpg");
            $('#profile_pic').val(''); // Clear the hidden input
        });




    });
</script>
<?php sipn_footer(); ?>