jQuery(document).ready(function($) {

    // Show the login dialog box on click
    $('a#show_login').on('click', function(e){
        $('body').prepend('<div class="login_overlay"></div>');
        $('form.login').fadeIn(500);
        $('div.login_overlay, form.login a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('form.login').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form.login').on('submit', function(e){
     // alert('0');
        $('form.login p.status').show().text(ajax_login_object.loadingmessage);
		let remember = 0;
		if($(this).find('input[name=remember]').is(':checked')){
			remember = 1;
		}
		//alert('1');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $(this).find('input[name=username]').val(), 
                'password': $(this).find('input[name=password]').val(),
                'remember': remember,
                'security': $(this).find('#security').val() },
            success: function(data){
            	//alert(data.validateemail);
                $('form.login p.status').text(data.message);
                if (data.loggedin == true){
                    
					if($('form.login #redirecturl').val() != ''){
						//alert('2');
						var rurl = $('form.login #redirectpart').val();
						//alert(rurl.indexOf("msg-"));
							if(rurl.indexOf("msg-") != -1){
								if(data.validateemail==1){
									var redirect="/";
								}else{
								var redirect = $('form.login #redirectsite').val()+"?#"+$('form.login #redirectpart').val();
								}
								
							} else {
								if(data.validateemail==1){
									var redirect="/";
								}else{
									var redirect = $('form.login #redirecturl').val();
								}
								
							}
					}
					else{
						var redirect = ajax_login_object.redirecturl;
					}
					//console.log(redirect);
					if(redirect.indexOf('bar') !== -1){
						//console.log('bar');
						document.location.href = data.bar_path;
					}else{
						//console.log('no bar');
						document.location.href = redirect;
					}
                }
            }

        });
        //alert('3');
        e.preventDefault();
    });
	
	$('.signup').on('submit', function(e){
		e.preventDefault();
        var error_flag = 0;
		let formdata = new FormData(this);

		if (formdata.get('uname') == '') {
			
			error_flag = 1;
			$(".usernameflash").remove();
        $(".nameflash").append(
          '<span class="usernameflash error-message"><p>Name is required</p></span>'
        );
		}else{
			$(".usernameflash").remove();
		}
		if (formdata.get('username') == '') {
			error_flag = 1;
			$(".email-flash").remove();
        $(".emailflash").append(
          '<span class="email-flash error-message"><p>Email is required</p></span>'
        );
		}else{
			$(".email-flash").remove();
		}

		if (formdata.get('password') == '') {
			error_flag = 1;
			$(".password-flash").remove();
        $(".passwordflash").append(
          '<span class="password-flash error-message"><p>Password is required</p></span>'
        );
		}else{
			$(".password-flash").remove();
		}
		if (formdata.get('re_password') == '') {
			error_flag = 1;
			$(".repassword-flash ").remove();
        $(".repasswordflash").append(
          '<span class="repassword-flash error-message"><p>Re-type Password is required</p></span>'
        );
		}else{
			$(".repassword-flash ").remove();
		}

		if (formdata.get('re_password') === formdata.get('password')) {
			
			$(".repassword-flash1 ").remove();
		}else{
			error_flag = 1;
			$(".repassword-flash1 ").remove();
        $(".repasswordflash").append(
          '<span class="repassword-flash1 error-message"><p>Passwords do NOT match!</p></span>'
        );
		}
		$('form.signup p.status').show().text(ajax_login_object.loadingmessage);
		if (error_flag) {
        return false;
      } else {
        var sendInfo = { 
                'uname': formdata.get('uname'),
                'email': formdata.get('username'),
                'password': formdata.get('password')
			};
			
        $.ajax({
            type: 'POST',
            dataType: 'json',
			contentType: "application/json;",
            url: '/wp-json/users/v2/register',
            data: JSON.stringify(sendInfo),
            success: function(data){
				$('form.login p.status').text("Please verify your email using the link sent to your email.");
				document.location.href = "/";
            },
			error: function(results) {
				//console.log(results);
				$('form.signup p.status').text(results.responseJSON.message);
            }
        });
    }
    });
	
	
	
	/*$('#reset_pass_btn').on('click', function(e){
        //$('form#login p.status').show().text(ajax_login_object.loadingmessage);
		if(!$("#reset_pass_btn").hasClass('reset_pwd')){
			var sendInfo = {
					'email': $('#reset_email').val()
				};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v1/reset-password',
				data: JSON.stringify(sendInfo),
				success: function(data){
					$('.msg-container .termspp').text(data.message);
					$('.msg-container').show();
					$("#reset_otp").show();
					$("#reset_pass").val('');
					$("#reset_pass").show();
					$("#reset_pass_btn").addClass('reset_pwd');
				},
				error: function(results) { 
					$('.msg-container .termspp').text(results.responseJSON.message);
					$('.msg-container').show();
					$("#reset_otp").hide();
					$("#reset_pass").hide();
					$("#reset_pass_btn").removeClass('reset_pwd');
				}
			});
		}else {
			var sendInfo = {
					'email': $('#reset_email').val(),
					'code': $('#reset_otp').val(),
					'password': $('#reset_pass').val()
				};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/user/v1/set-password',
				data: JSON.stringify(sendInfo),
				success: function(data){
					$('.msg-container .termspp').text(data.message + " Redirecting to login page..");
					$('.msg-container').show();
					$("#reset_otp").show();
					$("#reset_pass").show();
					//$("#reset_pass_btn").addClass('reset_pwd');
					//document.location.href = "/login";
				},
				error: function(results) {
					$('.msg-container .termspp').text(results.responseJSON.message);
					$('.msg-container').show();
					$("#reset_otp").show();
					$("#reset_pass").show();
					//$("#reset_pass_btn").removeClass('reset_pwd');
				}
			});
		}
        e.preventDefault();
    });*/

 });


