var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
jQuery(document).ready(function ($) {
	showAgeVerification();
    $('.post[rid="0"]').prop('disabled', true);
  
    $('body').on('click', '.invite_friends_click', function (e) {
		
				$.ajax({
				            type: 'GET',
				            dataType: 'json',
							contentType: "application/json;",
				            url: '/wp-json/users/v2/invite_friends',
				            data: JSON.stringify(''),
				            success: function(data){
								
				            },
							error: function(results) {
					
				            }
				        });
	});

	$('body').on('click', '.replies_list', function (e) {

		$("#repliesModal .result-replies").html('');
		var reply_id = $(this).attr('rid');
		$("#repliesModal .result-replies").attr("rid", reply_id);

		$("#repliesModal .comment").attr("id", "comment_" + reply_id);
		//$("#replyModal .comment").val($(this).closest('.user-feed').find('.user-msg').html().trim());
		$("#repliesModal .commentInput").attr("rid", reply_id);
		$("#repliesModal .comments_input").addClass("pcomment_" + reply_id);
		$("#repliesModal .commentImg").attr("id", "comment_img_" + reply_id);
		$("#repliesModal .submitRepliesWrapper").attr("rid", reply_id);

		var modal = document.getElementById("repliesModal");
		modal.style.display = "block";

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxtimelinecomments', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': reply_id,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {

				var html = '';
				//console.log(data.length);
				if (data.length > 1) {
					html += '<div class="child-container extracomment" style="padding-left: 5rem; padding-top: 1rem;">';
					//html += '<div class="line-div"></div>';
				} else {
					html += '<div class="child-container" style="padding-left: 5rem; padding-top: 1rem;">';
				}
				$.each(data, function (index, itemData) {
					//console.log(itemData);
					$('.ncommnents').text('');
					$('.ncommnents').text('Comments (' + itemData.ncommentcount + ')');

					html += '<div class="reply-msg user-feed" id="msg-' + itemData.reply_id + '"><div class="get-msg"><div class="profile-pic">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">';
					} else {
						html += '<a href="/login">';
					}
					html += '<img class="img-circle" src="' + itemData.avatar + '" alt="wines-bottle" width="60" height="60"></a><div class="user-name sender-chat sender-chat-alt-div">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">' + itemData.author + '</a> <small>' + itemData.reply_date + '</small>';
					} else {

						html += '<a href="/login">' + itemData.author + '</a> <small>' + itemData.reply_date + '</small>';
					}
					html += '<br><span class="user-msg rl-msg-text">';

					$content = itemData.reply.replace(/\n/g, "<br>");

					$count = $content.length;
					if ($count > 160) {
						$showcontent = $content.substring(0, 150);
					} else {
						$showcontent = $content;
					}

					html += '' + $showcontent + ''; if ($count > 160) {
						$shcontent = $content.substring(150);
						html += '<a class="read-more-show hide" href="javascript:void(0);" id="' + itemData.reply_id + '">&nbsp;&nbsp;Read More</a><span class="read-more-content">' + $shcontent + ' <a class="read-more-hide hide" href="javascript:void(0);" more-id="' + itemData.reply_id + '">&nbsp;&nbsp;Read Less</a></span>';
					}




					html += '</span>';

					if (itemData.reply_image) {
						html += '<span class="upload-image upload-image-alt-div"><img src="' + itemData.reply_image + '" width="100%" alt=""></span>';
					}
					html += '</div></div>';
					html += '</div>'
					html += '<div class="msg-opt"><ul class="list-inline">';

					//by sumeeth

					if (itemData.replies.length) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list1 rlist_' + itemData.reply_id + '"  rid="' + itemData.reply_id + '"><span class="icon-replay"><img class="re" src="/wp-content/themes/SIPN/assets/images/icon-reply.png"></span>' + itemData.total_replies_count + ' Reply</a>.&nbsp;</li>';
					} else {
						html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list1 rlist_' + itemData.reply_id + '" rid="' + itemData.reply_id + '"><span class="icon-replay"><img src="/wp-content/themes/SIPN/assets/images/icon-reply.png"></span>' + itemData.total_replies_count + ' Reply</a>.&nbsp;</li>'; //by sumeeth

					}

					if (itemData.edit_flag == 0) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="' + itemData.reply_id + '" post_url="' + itemData.url + '">Report</a>.&nbsp;</li>';
					}

					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="edit-tl-comment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '">Edit</a>';
					}

					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="delete-tl-post" rid="' + itemData.reply_id + '">Delete</a>';
					}



					html += '</ul></div></div>';

				});
				if (data.length) {
					html += '</div>';
				}
				//var html_element = '.sub_replies_'+reply_id;
				$("#repliesModal .result-replies").html(html);


				$('.read-more-content').addClass('hide')
				$('.read-more-show, .read-more-hide').removeClass('hide')

				// Set up the toggle effect:
				$('.read-more-show').on('click', function (e) {
					$(this).next('.read-more-content').removeClass('hide');
					$(this).addClass('hide');
					e.preventDefault();
				});

				$('.read-more-hide').on('click', function (e) {
					$(this).parent('.read-more-content').addClass('hide');
					var moreid = $(this).attr("more-id");
					$('.read-more-show#' + moreid).removeClass('hide');
					e.preventDefault();
				});



			}
		});


	});

	$('body').on('click', '.replies_list1', function (e) {
		$("#repliesModal1 .result-replies").html('');
		var reply_id = $(this).attr('rid');
		$("#repliesModal1 .result-replies").attr("rid", reply_id);

		$("#repliesModal1 .comment").attr("id", "comment_" + reply_id);
		//$("#replyModal .comment").val($(this).closest('.user-feed').find('.user-msg').html().trim());
		$("#repliesModal1 .commentInput").attr("rid", reply_id);
		$("#repliesModal1 .commentInput").addClass("pcomment_" + reply_id);
		$("#repliesModal1 .commentImg").attr("id", "comment_img_" + reply_id);
		$("#repliesModal1 .submitRepliesWrapper").attr("rid", reply_id);

		var modal = document.getElementById("repliesModal1");
		modal.style.display = "block";


		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxtimelinecomments', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': reply_id,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var html = '';
				//console.log(data.length);
				if (data.length > 1) {
					html += '<div class="child-container extracomment" style="padding-left: 5rem; padding-top: 1rem;">';
					//html += '<div class="line-div"></div>';
				} else {
					html += '<div class="child-container" style="padding-left: 5rem; padding-top: 1rem;">';
				}
				$.each(data, function (index, itemData) {
					//console.log(itemData);
					$('.headd').text('');
					$('.headd').text('Comments (' + itemData.ncommentcount + ')');
					html += '<div class="reply-msg user-feed" id="msg-' + itemData.reply_id + '"><div class="get-msg"><div class="profile-pic">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">';
					} else {
						html += '<a href="/login">';
					}
					html += '<img class="img-circle" src="' + itemData.avatar + '" alt="wine-bottle" width="60" height="60"></a><div class="user-name sender-chat sender-chat-alt-div">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">' + itemData.author + '</a> ' + itemData.reply_date;
					} else {
						html += '<a href="/login">' + itemData.author + '</a> ' + itemData.reply_date;
					}


					html += '<br><span class="user-msg rl-msg-text">';

					$content = itemData.reply.replace(/\n/g, "<br>");

					$count = $content.length;
					if ($count > 160) {
						$showcontent = $content.substring(0, 150);
					} else {
						$showcontent = $content;
					}

					html += '' + $showcontent + ''; if ($count > 160) {
						$shcontent = $content.substring(150);
						html += '<a class="read-more-show hide" href="javascript:void(0);" id="' + itemData.reply_id + '">&nbsp;&nbsp;Read More</a><span class="read-more-content">' + $shcontent + ' <a class="read-more-hide hide" href="javascript:void(0);" more-id="' + itemData.reply_id + '">&nbsp;&nbsp;Read Less</a></span>';
					}




					html += '</span>';




					if (itemData.reply_image) {
						html += '<span class="upload-image"><img src="' + itemData.reply_image + '" width="100%" alt=""></span>';
					}
					html += '</div></div>';

					//for design
					/*html += '<div class="dropdown">';
					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="dropbtn"><span class="fa fa-ellipsis-v more-icon"></span></a>';
					}*/
					//html += '<div class="dropdown-content">';




					// if (itemData.edit_flag==0) {
					// html += '<a href="javascript:void(0);" class="report-tl-post" rid="'+itemData.reply_id+'" post_url="'+itemData.url+'"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>';
					// }


					html += '</div>'/*
					html += '</div></div></div>'*/




					html += '<div class="msg-opt"><ul class="list-inline">';

					//by sumeeth
					if (itemData.edit_flag == 0) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="' + itemData.reply_id + '" post_url="' + itemData.url + '">Report</a>.&nbsp;</li>';
					}


					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="edit-tl-comment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '">Edit</a>';
						html += '<a href="javascript:void(0);" class="delete-tl-post" rid="' + itemData.reply_id + '">Delete</a>';
					}

					/*html += '<li class="list-item">Commented ' + itemData.reply_date + '</li>';*/

					html += '</ul></div></div>';

				});
				if (data.length) {
					html += '</div>';
				}
				//var html_element = '.sub_replies_'+reply_id;
				$("#repliesModal1 .result-replies").html(html);

				//$("#repliesModal .result-replies").html(html);


				$('.read-more-content').addClass('hide')
				$('.read-more-show, .read-more-hide').removeClass('hide')

				// Set up the toggle effect:
				$('.read-more-show').on('click', function (e) {
					$(this).next('.read-more-content').removeClass('hide');
					$(this).addClass('hide');
					e.preventDefault();
				});

				$('.read-more-hide').on('click', function (e) {
					$(this).parent('.read-more-content').addClass('hide');
					var moreid = $(this).attr("more-id");
					$('.read-more-show#' + moreid).removeClass('hide');
					e.preventDefault();
				});

			}
		});


	});

	//connented and added by salman on 19/08/2024
	/*$(document).on('click','#shelfedit1, #shelfedit2, #shelfedit3', function(e){
		e.preventDefault();
		if($(this).text() == "Save"){
			$('.slider.'+$(this).attr('id')+'-section').find('.delete-product').hide();
			$(this).children().first().text('Edit');
		}else{
			$('.slider.'+$(this).attr('id')+'-section').find('.delete-product').show();
			$(this).children().first().text('Save');
		}
	});*/

	$(document).on('click', '#shelfedit1, #shelfedit2, #shelfedit3', function (e) {
		e.preventDefault();

		var button = $(this);
		var buttonText = button.children().first().text().trim();
		var inputElement = button.closest('.shelf-container').find('input.shelfedit');
		var ssid = inputElement.attr('ssid');
		var bar_id = inputElement.attr('bar-id');
		var shelfName = inputElement.val().trim();

		if (buttonText === "Save") {
			$('.slider.' + $(this).attr('id') + '-section').find('.delete-product').hide();

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxupdateshelfname',
					'shelf_id': ssid,
					'shelf_name': shelfName,
					'bar_id': bar_id,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					console.log('Shelf name updated successfully.');
				},
				error: function (error) {
					console.log('Error updating shelf name:', error);
				}
			});

			$(this).children().first().text('Edit');
		} else if (buttonText === "Edit") {
			$('.slider.' + $(this).attr('id') + '-section').find('.delete-product').show();
			$(this).children().first().text('Save');
		}
	});

	$('body').on('click', '.submitRepliesWrapper', function (e) {
		//console.log("wishlist");
		$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		if (comment.length <= 0) {
			return false;
		}
		var cur_ele = $(this);
		cur_ele.html('<i class="fa fa-spinner fa-spin"></i>');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddposttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var trigger_selector = ".rlist_" + rid;
				//console.log(trigger_selector);
				$(".more-user-info textarea").val('');
				//if(!cur_ele.parent().hasClass("post-comments-up")){
				$(trigger_selector).trigger('click');
				//}
				//alert(trigger_selector.text());
				var count = parseInt($(trigger_selector).text()) + 1;
				$(trigger_selector).text(count);
				$(".page-loader").hide();
				cur_ele.html('<img src="' + window.location.origin + '/wp-content/themes/SIPN/assets/images/icon-send.png">');
				
				$('.comments_input').val('');
				$(comment_id).val('');

				if (data.reward_message != 1) {
						let timerInterval;
						Swal.fire({
						  title: data.reward_message,
						  timer: 2000,
						  timerProgressBar: true,
						  didOpen: () => {
						    Swal.showLoading();
						    const timer = Swal.getPopup().querySelector("b");
						    timerInterval = setInterval(() => {
						      timer.textContent = `${Swal.getTimerLeft()}`;
						    }, 2000);
						  },
						  willClose: () => {
						    clearInterval(timerInterval);
						  }
						});
					}

			}
		});

	});

	$('body').on('click', '.submitRepliesWrapperindex', function (e) {
		//console.log("wishlist");
		//$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#commentindex_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		if (comment.length <= 0) {
			return false;
		}
		var cur_ele = $(this);
		cur_ele.html('<i class="fa fa-spinner fa-spin"></i>');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddposttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var trigger_selector = ".rlist_" + rid;
				//console.log(trigger_selector);
				$(".more-user-info textarea").val('');
				//if(!cur_ele.parent().hasClass("post-comments-up")){
				$(trigger_selector).trigger('click');
				//}
				//alert(trigger_selector.text());
				var count = parseInt($(trigger_selector).text()) + 1;
				$(trigger_selector).text(count);
				//$(".page-loader").hide();
				cur_ele.html('<img src="' + window.location.origin + '/wp-content/themes/SIPN/assets/images/icon-send.png">');
				//location.reload();
				$('.pcommentindex_' + rid).val('');
				$(comment_id).val('');


			}
		});

	});

	$('body').on('click', '.list-tl-replies', function (e) {
		var reply_id = $(this).attr('rid');

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxtimelinecomments', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': reply_id,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var html = '';
				//console.log(data.length);
				if (data.length) {
					html += '<div class="child-container" style="padding-left: 5rem; padding-top: 1rem;">';
					html += '<div class="line-div"></div>';
				}
				$.each(data, function (index, itemData) {
					//console.log(itemData);
					html += '<div class="reply-msg"><div class="get-msg"><div class="profile-pic"><a href="javascript:void(0);"><img class="img-circle" src="' + itemData.avatar + '" alt="wine-bottle" width="60" height="60"></a><div class="user-name sender-chat sender-chat-alt-div">' + itemData.author + '<br><span>' + itemData.reply + '</span></div></div></div>';

					html += '<div class="msg-opt"><ul class="list-inline">';

					if (itemData.replies.length) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="list-tl-replies sub_replies_' + itemData.reply_id + '" rid="' + itemData.reply_id + '"><span></span>Replies (' + itemData.replies.length + ')</a>.&nbsp;</li>';
					}

					html += '<li class="list-item"><a href="javascript:void(0);" class="reply-tl-post" rid="' + itemData.reply_id + '"><span></span>Reply</a>.&nbsp;</li>';

					html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="' + itemData.reply_id + '" post_url="' + itemData.url + '"><span></span>Report</a>.</li>';
					html += '<li class="list-item">Posted ' + itemData.reply_date + '</li>';

					html += '</ul></div></div>';

				});
				if (data.length) {
					html += '</div>';
				}
				var html_element = '.sub_replies_' + reply_id;
				$(html_element).closest('.main-post').after(html);
			}
		});
	});


	$('body').on('click', '.reply-tl-post', function (e) {
		var reply_id = $(this).attr('rid');

		$("#replyModal .comment").attr("id", "comment_" + reply_id);
		//$("#replyModal .comment").val($(this).closest('.user-feed').find('.user-msg').html().trim());
		$("#replyModal .commentInput").attr("rid", reply_id);
		$("#replyModal .commentImg").attr("id", "comment_img_" + reply_id);
		$("#replyModal .submitReplyWrapper").attr("rid", reply_id);

		var modal = document.getElementById("replyModal");
		modal.style.display = "block";
	});

	$('body').on('click', '.submitReplyWrapper', function (e) {
		//console.log("wishlist");
		$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		//console.log(comment_image);
		var cur_ele = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddcommenttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log(data);
				var msg = "#msg-" + rid + " .user-msg";
				var msg_img = "#msg-" + rid + " .upload-image img";
				$(msg).html(comment);
				if (data.reply_image) {
					$(msg_img).attr("src", data.reply_image);
				}
				$(cur_ele).closest('textarea').val('');
				$(cur_ele).closest('.cimg').val('');
				var modal3 = document.getElementById("replyModal");
				modal3.style.display = "none";
				$(".page-loader").hide();
				location.reload(); //added by sumeeth

			}
		});

	});

	$('body').on('click', '.closecropper', function (e) {
		var modald = document.getElementById("cropperModal");
		// Clean up previous cropper instance if exists
		$('#search_input_head').val('');
		if (window.cropper) {
			window.cropper.destroy(); // Destroy the previous cropper instance
		}
		modald.style.display = "none";
		$('body').css('overflow', ''); // Resets overflow to the default value
	});
	// $('body').on('click', '.edit-tl-post', function (e) {
	// 	var reply_id = $(this).attr('rid');
	// 	var prod_id = $(this).attr('pid');
	// 	var locid = $(this).attr('locid');
	// 	var ptitle = $(this).attr('rptitle');
	// 	if (prod_id != '') {
	// 		$('#tagproduct1').show();
	// 		$('#tagproduct1').prop('disabled', true);
	// 	} else {
	// 		$('#tagproduct1').show();
	// 		$('#tagproduct1').prop('disabled', false);
	// 	}

	// 	if (locid != '') {
	// 		$('#tageditlocpost').prop('disabled', true); //for tag a location
	// 		$('#tageditlocpost').addClass('colorbttn'); //for tag a location
	// 		$('.closeloc').show();
	// 		$('.tagloceditpostsearch').show();
	// 	} else {
	// 		$('.closeloc').hide();
	// 		$('#tageditlocpost').prop('disabled', false); //for tag a location
	// 		$('#tageditlocpost').removeClass('colorbttn'); //for tag a location
	// 		$('.tagloceditpostsearch').hide();
	// 	}


	// 	//alert('hi');

	// 	if (ptitle == 'Sipn Bourbon - Home is where ...') {
	// 		ptitle = '';
	// 		$("#headerpostsearch1").hide();
	// 		$('.closeproduct').hide();
	// 		$("#editpostoutputimage").hide();
	// 		$('.edicloseimage').hide();
	// 	}

	// 	if (ptitle != '') {

	// 		$("#headerpostsearch1").show();
	// 		$('#tagproduct1').prop('disabled', true); //for tag a product
	// 		$('#tagproduct1').addClass('colorbttn'); //for tag a product
	// 	}
	// 	var reply_image = $(this).attr('rimage'); //added by sumeeth
	// 	if (reply_image == '') {
	// 		$('.view-gallery').hide();
	// 	} else {
	// 		$('.view-gallery').show();
	// 	}
	// 	var strArray = reply_image.split(",");
	// 	//alert(strArray);
	// 	$(".viewonly").empty();
	// 	for (var i = 0; i < strArray.length; i++) {
	// 		$(".viewonly").append(
	// 			'<li> <img id="blah" src="' + strArray[i] + '" alt="your image" style="width: 100%; height: 100%;"></li>'
	// 		);

	// 	}
	// 	$(".viewonly").append('<span class="fa fa-times deletepimages"></span>');
	// 	$("#editModal .sumee").val(reply_image);
	// 	$("#editModal .comment").attr("id", "comment_" + reply_id);

	// 	var msg = $(this).closest('.user-feed').find('.user-msg').html();
	// 	if (msg == '' || typeof (msg) == "undefined") {
	// 		var msg = $(this).closest('.main-post').find('.rl-msg-text').html();
	// 	}
	// 	var str = msg.toString();
	// 	msg = str.replace(/(<([^>]+)>)/ig, '');
	// 	msg = msg.replace('&nbsp;&nbsp;Read More', '');
	// 	msg = msg.replace('&nbsp;&nbsp;Read Less', '');

	// 	$("#editModal #fpid1").val(prod_id); //for product id edit
	// 	$("#editModal .tagloceditpostsearch").val(locid); //for product location id edit
	// 	$("#editModal .comment").val(msg.trim());
	// 	$("#editModal .commentInput").attr("rid", reply_id);
	// 	$("#editModal #headerpostsearch1").val(ptitle);

	// 	$("#editModal #output").attr("src", reply_image);
	// 	if (reply_image != '') {
	// 		$('#output').show();
	// 		$('#editpostoutputimage').show();
	// 	}
	// 	$("#editModal .commentImg").attr("id", "comment_img_" + reply_id);
	// 	$("#editModal .submitEditWrapper").attr("rid", reply_id);

	// 	var modal = document.getElementById("editModal");
	// 	modal.style.display = "block";
	// 	var addcommenttext = msg;
	// 	$a = reply_image;
	// 	if (addcommenttext.length > 0 || $a != '') {
	// 		$('.post').prop('disabled', false);
	// 		$('.post').removeClass('colorbttn');


	// 	} else if (addcommenttext.length <= 0 && $a != '') {

	// 		$('.post').prop('disabled', false);
	// 		$('.post').removeClass('colorbttn');

	// 	} else if (addcommenttext.length <= 0 && $a == '') {

	// 		$('.post').prop('disabled', true);
	// 		$('.post').addClass('colorbttn');

	// 	}
	// 	else {
	// 		$('.post').prop('disabled', true);
	// 		$('.post').addClass('colorbttn');

	// 	}
	// });

	// Event listener for the main edit button

	// $('body').on('click', '.edit-tl-post', function (e) {
	// 	var reply_id = $(this).attr('rid');
	// 	var prod_id = $(this).attr('pid');
	// 	var locid = $(this).attr('locid');
	// 	var ptitle = $(this).attr('rptitle');

	// 	// Handling product and location related UI
	// 	if (prod_id != '') {
	// 		$('#tagproduct1').show().prop('disabled', true);
	// 	} else {
	// 		$('#tagproduct1').show().prop('disabled', false);
	// 	}

	// 	if (locid != '') {
	// 		$('#tageditlocpost').prop('disabled', true).addClass('colorbttn');
	// 		$('.closeloc').show();
	// 		$('.tagloceditpostsearch').show();
	// 	} else {
	// 		$('.closeloc').hide();
	// 		$('#tageditlocpost').prop('disabled', false).removeClass('colorbttn');
	// 		$('.tagloceditpostsearch').hide();
	// 	}

	// 	// Manage product title and related UI
	// 	if (ptitle == 'Sipn Bourbon - Home is where ...') {
	// 		ptitle = '';
	// 		$("#headerpostsearch1").hide();
	// 		$('.closeproduct').hide();
	// 		$("#editpostoutputimage").hide();
	// 		$('.edicloseimage').hide();
	// 	}

	// 	if (ptitle != '') {
	// 		$("#headerpostsearch1").show();
	// 		$('#tagproduct1').prop('disabled', true).addClass('colorbttn');
	// 	}

	// 	// Manage images in the modal
	// 	var reply_image = $(this).attr('rimage');
	// 	if (reply_image == '') {
	// 		$('.view-gallery').hide();
	// 	} else {
	// 		$('.view-gallery').show();
	// 	}

	// 	var strArray = reply_image.split(",");
	// 	$(".viewonly").empty();
	// 	for (var i = 0; i < strArray.length; i++) {
	// 		$(".viewonly").append(
	// 			'<li> <img src="' + strArray[i] + '" alt="your image" style="width: 100%; height: 100%;">' +
	// 			'<span class="createclose edicloseimage2" data-index="' + i + '">×</span>' + // Added close button
	// 			'</li>'
	// 		);
	// 	}

	// 	// Other modal setup
	// 	$("#editModal .sumee").val(reply_image);
	// 	$("#editModal .comment").attr("id", "comment_" + reply_id);

	// 	// Handling message display
	// 	var msg = $(this).closest('.user-feed').find('.user-msg').html() ||
	// 		$(this).closest('.main-post').find('.rl-msg-text').html() || '';
	// 	msg = msg.replace(/(<([^>]+)>)/ig, '').replace('&nbsp;&nbsp;Read More', '').replace('&nbsp;&nbsp;Read Less', '');
	// 	$("#editModal #fpid1").val(prod_id);
	// 	$("#editModal .tagloceditpostsearch").val(locid);
	// 	$("#editModal .comment").val(msg.trim());
	// 	$("#editModal .commentInput").attr("rid", reply_id);
	// 	$("#editModal #headerpostsearch1").val(ptitle);
	// 	$("#editModal #output").attr("src", reply_image);

	// 	// Show output if an image exists
	// 	if (reply_image != '') {
	// 		$('#output').show();
	// 		$('#editpostoutputimage').show();
	// 	}
	// 	$("#editModal .commentImg").attr("id", "comment_img_" + reply_id);
	// 	$("#editModal .submitEditWrapper").attr("rid", reply_id);

	// 	// Show modal
	// 	var modal = document.getElementById("editModal");
	// 	modal.style.display = "block";

	// 	// Enable/disable post button based on inputs
	// 	if (msg.length > 0 || reply_image != '') {
	// 		$('.post').prop('disabled', false).removeClass('colorbttn');
	// 	} else {
	// 		$('.post').prop('disabled', true).addClass('colorbttn');
	// 	}
	// });


	$('body').on('click', '.edit-tl-post', function (e) {
		var reply_id = $(this).attr('rid');
		var prod_id = $(this).attr('pid');
		var locid = $(this).attr('locid');
		var ptitle = $(this).attr('rptitle');

		// Handling product and location related UI
		if (prod_id != '') {
			$('#tagproduct1').show().prop('disabled', true);
		} else {
			$('#tagproduct1').show().prop('disabled', false);
		}

		if (locid != '') {
			$('#tageditlocpost').prop('disabled', true).addClass('colorbttn');
			$('.closeloc').show();
			$('.tagloceditpostsearch').show();
		} else {
			$('.closeloc').hide();
			$('#tageditlocpost').prop('disabled', false).removeClass('colorbttn');
			$('.tagloceditpostsearch').hide();
		}

		// Manage product title and related UI
		if (ptitle == 'Sipn Bourbon - Home is where ...') {
			ptitle = '';
			$("#headerpostsearch1").hide();
			$('.closeproduct').hide();
			$("#editpostoutputimage").hide();
			$('.edicloseimage').hide();
		}

		if (ptitle != '') {
			$("#headerpostsearch1").show();
			$('#tagproduct1').prop('disabled', true).addClass('colorbttn');
		}

		// Manage images in the modal
		var reply_image = $(this).attr('rimage');
		if (reply_image == '') {
			$('.view-gallery').hide();
		} else {
			$('.view-gallery').show();
		}

		var strArray = reply_image.split(",");
		$(".viewonly").empty();
		// for (var i = 0; i < strArray.length; i++) {
		// 	$(".viewonly").append(
		// 		'<li> <img src="' + strArray[i] + '" alt="your image" style="width: 100%; height: 100%;">' +
		// 		'<span class="createclose edicloseimage2" data-index="' + i + '">×</span>' + // Added close button
		// 		'</li>'
		// 	);
		// }

		for (var i = 0; i < strArray.length; i++) {
			(function (index) {  // Create a closure to capture the current value of i
				var imageUrl = strArray[index];  // This is the current image URL

				// Fetch the image from the URL and convert it to base64
				fetch(imageUrl)
					.then(response => response.blob())  // Convert the image to a blob
					.then(blob => {
						var reader = new FileReader();
						reader.onloadend = function () {
							var base64String = reader.result;  // This is the base64 string

							// Create list item with base64 image and delete button
							var listItem = document.createElement("li");
							listItem.innerHTML =
								"<img src='" + base64String + "' style='height:30%; width:30%;' />" +
								"<span class='createclose edicloseimage3' style='z-index:1000;'  data-index='" + index + "'>×</span>";

							// Append the base64 image to the hidden input with the correct id
							$('#mulimg1').append("<input type='hidden' id='img1" + index + "' value='" + base64String + "' >");

							// Append the list item to the preview container
							$(".viewonly").append(listItem);
						};
						reader.readAsDataURL(blob);  // Convert blob to base64
					})
					.catch(error => console.log('Error fetching and converting image: ', error));
			})(i);  // Immediately invoke the closure with the current value of i
		}


		// Other modal setup
		$("#editModal .sumee").val(reply_image);
		$("#editModal .comment").attr("id", "comment_" + reply_id);

		// Handling message display
		var msg = $(this).closest('.user-feed').find('.user-msg').html() ||
			$(this).closest('.main-post').find('.rl-msg-text').html() || '';
		msg = msg.replace(/(<([^>]+)>)/ig, '').replace('&nbsp;&nbsp;Read More', '').replace('&nbsp;&nbsp;Read Less', '');
		$("#editModal #fpid1").val(prod_id);
		$("#editModal .tagloceditpostsearch").val(locid);
		$("#editModal .comment").val(msg.trim());
		$("#editModal .commentInput").attr("rid", reply_id);
		$("#editModal #headerpostsearch1").val(ptitle);
		$("#editModal #output").attr("src", reply_image);

		// Show output if an image exists
		if (reply_image != '') {
			$('#output').show();
			$('#editpostoutputimage').show();
		}
		$("#editModal .commentImg").attr("id", "comment_img_" + reply_id);
		$("#editModal .submitEditWrapper").attr("rid", reply_id);

		// Show modal
		var modal = document.getElementById("editModal");
		modal.style.display = "block";

		// Enable/disable post button based on inputs
		if (msg.length > 0 || reply_image != '') {
			$('.post').prop('disabled', false).removeClass('colorbttn');
		} else {
			$('.post').prop('disabled', true).addClass('colorbttn');
		}
	});



	//added by sumeeth
	$('body').on('click', '.edit-tl-comment', function (e) {
		var reply_id = $(this).attr('rid');
		var reply_image = $(this).attr('rimagecomment');
		//alert(reply_image);
		//console.log('-->'+$(this).closest('.user-feed').find('.user-msg').html());
		$("#editModal1 .comment").attr("id", "comment_" + reply_id);

		var msg = $(this).closest('.user-feed').find('.user-msg').html();
		//console.log('test----'+msg);
		if (msg == '' || typeof (msg) == "undefined") {
			//console.log('test');
			var msg = $(this).closest('.main-post').find('.rl-msg-text').html();
		}
		var str = msg.toString();

		// Regular expression to identify HTML tags in
		// the input string. Replacing the identified
		// HTML tag with a null string.
		msg = str.replace(/(<([^>]+)>)/ig, '');
		msg = msg.replace('&nbsp;&nbsp;Read More', '');
		msg = msg.replace('&nbsp;&nbsp;Read Less', '');
		$("#editModal1 .comment").val(msg.trim());
		$("#editModal1 .commentInput").attr("rid", reply_id);
		$("#editModal1 #output1").attr("src", reply_image);
		if (reply_image != '') {
			$('#output1').show();
			$('#editpostoutputimage1').show();
			$('.edicloseimage1').show();
		}
		$("#editModal1 .commentImg").attr("id", "comment_img_" + reply_id);
		$("#editModal1 .submitEditWrapper1").attr("rid", reply_id);

		var modal6 = document.getElementById("editModal1");
		modal6.style.display = "block";
		var modal7 = document.getElementById("repliesModal1");
		modal7.style.display = "none";
	});

	$('body').on('click', '.delete-tl-post', function () {
		var reply_id = $(this).attr('rid');
		$('#confirmdelmodel').find('#delete-tl-post').attr('rid', reply_id); // Set reply_id
		$('#confirmdelmodel').modal('show');
	});




	$(document).on('click', '#delete-tl-post', function (e) {
		e.preventDefault();
		var reply_id = $(this).attr('rid');
		if (reply_id > 0) {
			var input_data = { "reply_id": reply_id };

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxdeletepost',
					'reply_id': reply_id,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					if (data.status) {
						location.reload();
					} else {
						alert('Failed to delete post. Please try again.');
					}
				},
				error: function () {
					alert('An error occurred. Please try again.');
				}
			});
		}
	});

	$('body').on('click', '.report_post', function (e) {
		var reply_id = $("#reportModal").attr('reply_id');
		var post_url = $("#reportModal").attr('post_url');
		var reason = $(this).attr('rep');
		if (reply_id > 0) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxreportpost', //calls wp_ajax_nopriv_ajaxlogin
					'post_id': reply_id,
					'post_url': post_url,
					'reason': reason,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					var modal = document.getElementById("reportModal");
					modal.style.display = "none";
					var modalr = document.getElementById("repliesModal");
					modalr.style.display = "none";
					if (data.message == 'Reported Successfully.') {
						$('#common_alert').modal('show');
						var modal1 = document.getElementById("common_alert");
						var alertMsg = document.getElementById("alert-msg");
						alertMsg.innerText = data.message;
						setTimeout(function () {
							modal1.style.display = "none";
							location.reload();
						}, 3000);
					}
				}
			});
		}
	});

	$('body').on('click', '.report-tl-post', function (e) {

		var modal = document.getElementById("reportModal");
		modal.style.display = "block";

		var reply_id = $(this).attr('rid');
		var post_url = $(this).attr('post_url');
		$("#reportModal").attr("reply_id", reply_id);
		$("#reportModal").attr("post_url", post_url);
	});


	//$('.submitWrapper').on('click', function(e){
	$('body').on('click', '.submitWrapper', function (e) {
		$('.post').prop('disabled', true);
		$('.post').addClass('colorbttn');
		
		$(this).prop('disabled', true)
        .html('POST  <i class="fa fa-spinner fa-spin"></i>'); 


		var tlp = $('.taglocpostsearch').val();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		var pid = $('#fpid').val();
		var img0 = $('#img10').val();
		var img1 = $('#img11').val();
		var img2 = $('#img12').val();

		//console.log(comment);
		//console.log(comment_image);
		var cur_ele = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddcommenttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'img0': img0,
				'img1': img1,
				'img2': img2,
				'pid': pid,
				'tagged_location': tlp,
				'nonce': site_script_object.nonce,

			},
			success: function (data) {
				//console.log('sss');
				

				if (data.reward_message != 1) {
						let timerInterval;
						Swal.fire({
						  title: data.reward_message,
						  timer: 2000,
						  timerProgressBar: true,
						  didOpen: () => {
						    Swal.showLoading();
						    const timer = Swal.getPopup().querySelector("b");
						    timerInterval = setInterval(() => {
						      timer.textContent = `${Swal.getTimerLeft()}`;
						    }, 2000);
						  },
						  willClose: () => {
						    clearInterval(timerInterval);
						  }
						}).then((result) => {
						  if (result.dismiss === Swal.DismissReason.timer) {
							// no use
						  }
						});
					}

				if (rid == '0') {

					/*var new_post = '<div class="inner-content" id="msg-'+data.new_reply_id+'">';
					new_post += '<div class="user-feed"><div class="user-profile">';
					
					var user_feed = $(".profile-in").html();
					new_post += '<div class="profile-in">'+user_feed+'</div>';
					new_post += '<div class="user-msg">'+comment+'</div>';
					
					new_post += '</div></div>';
					
					if(comment_image){
					new_post += '<div class="upload-image"><a href="#"><img src="'+comment_image+'" width="100%" alt=""></a></div>';
					}
					
					new_post += '<div class="img-options"><div class="options1 options "><a href="javascript:void(0);" class="like_timeline" id="like" liked="0" rid="'+data.new_reply_id+'"> Like</a></div><div class="options2 options"><a href="#comment-'+data.new_reply_id+'" id="comment"> Comment</a></div><div class="options3 options"> <a href="javascript:void(0);" class="copy-share-link" id="share" link="https://sipndev.wpengine.com/timeline/?q='+data.new_reply_id+'"> Share</a></div></div>';
					
					
					new_post += '</div>';
					
					$(".inner-content-feeds").after(new_post);*/
					//	location.reload();  
					// $('.post').prop('disabled', true);
					//             $('.post').addClass('colorbttn'); 
					window.location.href = '/';
					$(".page-loader").hide();
				}
				else {
					//console.log(data);
					var new_post = '<div class="get-msg">';
					new_post += '<div class="profile-pic">';

					new_post += $(".msg-opt-in .profile-pic").html();

					new_post += '<div class="user-name sender-chat sender-chat-alt-div">' + data.user_name + '<br><span>' + comment + '</span></div>';

					if (data.reply_imge) {
						new_post += '<div class="upload-image"><a href="javascript:void(0);"><img src="' + data.reply_imge + '" width="100%" alt=""></a></div>';
					}

					new_post += '</div>';
					new_post += '</div>';

					new_post += '<div class="msg-opt"><ul class="list-inline"><li class="list-item"><a href="javascript:void(0);">Like</a>.</li><li class="list-item"><a href="javascript:void(0);">Reply</a>.</li><li class="list-item"><a href="javascript:void(0);">Share</a>.</li><li class="list-item">2 minutes ago</li></ul></div>';

					$(new_post).prependTo($(cur_ele).closest(".user-chat"));

					$(cur_ele).closest('textarea').val('');
					$(cur_ele).closest('.cimg').val('');

					$(".page-loader").hide();
					location.reload();
				}
			}
		});

	});



	$('body').on('click', '.submitEditWrapper', function (e) {

		// $('.btn-postnow').html('<button class="post colorbttn submitWrapper post_spinner">POST NOW <i class="fa fa-spinner fa-spin"></i></button');

		$(this).prop('disabled', true)
        .html('POST <i class="fa fa-spinner fa-spin"></i>'); 

		var rid = $(this).attr('rid');
		var elid = $('.tagloceditpostsearch').val();
		var delete_image = $('#delete_image').val();
		var comment_id = "#comment_" + rid;
		var comment = $(comment_id).val();
		var delimage = $('#output').attr('src');
		var img10 = $('#img10').val();
		var img11 = $('#img11').val();
		var img12 = $('#img12').val();
		var pid = $('#fpid1').val();

		var cur_ele = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxeditpost', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'pid': pid,
				'tagged_location': elid,
				'delete_image': delete_image,
				'reply': comment,
				'delimage': delimage,
				'img10': img10,
				'img11': img11,
				'img12': img12,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var msg = "#msg-" + rid + " .user-msg";
				var msg_img = "#msg-" + rid + ".upload-image img";
				$(msg).html(comment);
				if (data.reply_image) {
					$(msg_img).attr("src", data.reply_image);
				}
				$(cur_ele).closest('textarea').val('');
				$(cur_ele).closest('.cimg').val('');
				var modal2 = document.getElementById("editModal");
				modal2.style.display = "none";
				//$(".page-loader").hide();
				location.reload();

			}
		});

	});

	//added by sumeeth

	$('body').on('click', '.submitEditWrapper1', function (e) {
		//console.log("wishlist");
		$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		//console.log(comment_image);
		var cur_ele = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxeditpost', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log(data);
				var msg = "#msg-" + rid + " .user-msg";
				var msg_img = "#msg-" + rid + " .upload-image img";
				$(msg).html(comment);
				if (data.reply_image) {
					$(msg_img).attr("src", data.reply_image);
					$(msg_img).removeAttr('style');
				}
				$(cur_ele).closest('textarea').val('');
				$(cur_ele).closest('.cimg').val('');
				var modal6 = document.getElementById("editModal1");
				modal6.style.display = "none";
				$(".page-loader").hide();
				location.reload();

			}
		});

	});


	$('#add_to_wishlist').on('click', function (e) {
		//console.log("wishlist");
		$(".page-loader").show();
		var pid = $(this).attr('pid');
		var wish = $(this).attr('wish');
		var sec = $(this).attr('sec');

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxwishlist', //calls wp_ajax_nopriv_ajaxlogin
				'product_id': pid,
				'wishlist': wish,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log('sss');
				if (wish == '1') {
					$('#add_to_wishlist').attr("wish", 0);
					$(".page-loader").hide();
					location.reload();
				}
				else {
					$('#add_to_wishlist').attr("wish", 1);
					$(".page-loader").hide();
					location.reload();
				}
			}
		});




	});

	$('body').on('click', '.remove_to_wishlist', function () {
		//console.log("wishlist");

		$(".page-loader").show();
		var pid = $(this).attr('pid');
		var wish = $(this).attr('wish');
		//var sec = $(this).attr('sec');
		// alert(pid);
		// alert(wish);

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxwishlist', //calls wp_ajax_nopriv_ajaxlogin
				'product_id': pid,
				'wishlist': wish,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log('sss');
				if (wish == '1') {
					//console.log("wish 1");
					//$('#add_to_wishlist span').text("Remove from wishlist");
					$('#add_to_wishlist').attr("wish", 0);
					$(".page-loader").hide();
					location.reload();
				}
				else {
					//console.log("wish 0");
					$('#add_to_wishlist span').text("Add to wishlist");
					$('#add_to_wishlist').attr("wish", 1);
					$(".page-loader").hide();
					location.reload();
				}
			}
		});

	});


	$('.remove_from_wishlist').on('click', function (e) {
		//console.log("wishlist");
		$(".page-loader").show();
		var pid = $(this).attr('pid');
		var wish = $(this).attr('wish');
		var parent_div = $(this).closest('.wishlist_prod');

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxwishlist', //calls wp_ajax_nopriv_ajaxlogin
				'product_id': pid,
				'wishlist': wish,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				$(parent_div).remove();
				$(".page-loader").hide();
			}
		});

	});


	$('#add_to_bar').on('click', function (e) {

		$('.btn-add-to-bar').html('Add to Bar <i class="fa fa-spinner fa-spin"></i>')
		var pid = $(this).attr('pid');
		var wish = $(this).attr('bar');
		var redirect_link = $(this).attr('link');
		if (!$(this).hasClass('nologinaction')) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxbarlist', //calls wp_ajax_nopriv_ajaxlogin
					'product_id': pid,
					'bar': wish,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {

					if (wish == '1') {
						$('#add_to_bar').attr("wish", 0);
						location.reload();
						if (data.reward_message != 1) {
							let timerInterval;
							Swal.fire({
							  title: data.reward_message,
							  timer: 2000,
							  timerProgressBar: true,
							  didOpen: () => {
							    Swal.showLoading();
							    const timer = Swal.getPopup().querySelector("b");
							    timerInterval = setInterval(() => {
							      timer.textContent = `${Swal.getTimerLeft()}`;
							    }, 2000);
							  },
							  willClose: () => {
							    clearInterval(timerInterval);
							  }
							});
						}

					}
					else {
						$('#add_to_bar span').text("Added to Bar");

					}

				}
			});
		}
	});



	// $("#pImage").on("change", function (event) {
	// 	var ext = $('#pImage').val().split('.').pop().toLowerCase();
	// 	if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
	// 		alert('Invalid image type');
	// 		return false;
	// 	}
	// 	var reader = new FileReader();
	// 	reader.onload = function () {
	// 		var output = document.getElementById("profile_imge_output");
	// 		output.src = reader.result;
	// 		$("#profile_imge_output").show();
	// 		var img_data = reader.result;
	// 		img_data = img_data.substring(img_data.indexOf(",") + 1);
	// 		//console.log(img_data);
	// 		$("#profile_pic").val(img_data);
	// 	};
	// 	reader.readAsDataURL(event.target.files[0]);
	// });


	//$(".commentInput").on("change", function (event) {
	$('body').on('change', '.commentInput', function (event) {
		var reader = new FileReader();
		var id = $(this).attr('rid');
		reader.onload = function () {
			var output_val_element = "#comment_img_" + id;
			//var output = document.getElementById(output_val_element);
			//output.src = reader.result;
			//$("#profile_imge_output").show();
			var img_data = reader.result;
			img_data = img_data.substring(img_data.indexOf(",") + 1);
			//console.log(img_data);
			$(output_val_element).val(img_data);
		};
		reader.readAsDataURL(event.target.files[0]);
	});

	//edit profile 
	$('.edit-profile-setting').on('click', function (e) {
		$(".view-only").hide();
		$(".edit-pro-form").show();
		$(".report-tl-forum").show();
		$(".inputWrapper").show();
		$(".edit-pro-form input").removeAttr("disabled");
		$(".edit-pro-form .signin").removeClass("hide-btn");
	});

	$('.newsignup .signin').on('click', function (e) {

		var error_flag = 0;
		var error_msg = '';
		if ($("input[name='username']").val() == '') {

			error_flag = 1;
			$(".usernameflash").remove();
			$(".username-flash-message").append(
				'<span class="usernameflash error-message"><p>Name is required</p></span>'
			);
		}

		var dobValue = $('#dob').val();
		var inputDate = new Date(dobValue);

		if (!dobValue || isNaN(inputDate.getTime())) {
			$(".dob-flash-message").append(
				'<span class="error-message"><p>Please enter a valid date of birth.</p></span>'
			);
			$('#dob').addClass('is-invalid'); // Mark as invalid
			return;
		}
		/*if ($("input[name='bio']").val() == '') {

			error_flag = 1;
			$(".biflash").remove();
			$(".bio-flash-message").append(
				'<span class="biflash error-message"><p>Bio is required</p></span>'
			);
		}*/
		/*if ($("input[name='city']").val() == '') {
			error_flag = 1;
			$(".cityflash").remove();
			$(".city-flash-message").append(
				'<span class="cityflash error-message"><p>City is required</p></span>'
			);
		}*/
		/*if ($("input[name='state']").val() == '') {
			error_flag = 1;
			$(".stateflash").remove();
			$(".state-flash-message").append(
				'<span class="stateflash error-message"><p>State is required</p></span>'
			);
		}
		if ($("input[name='zip']").val() == '') {
			error_flag = 1;
			$(".zipflash").remove();
			$(".zip-flash-message").append(
				'<span class="zipflash error-message"><p>Zip Code is required</p></span>'
			);
		}
		if ($("input[name='phone']").val() == '') {
			error_flag = 1;
			$(".phoneflash").remove();
			$(".phone-flash-message").append(
				'<span class="phoneflash error-message"><p>Phone Number is required</p></span>'
			);
		}*/


		if (error_flag) {
			return false;
		} else {
			let $this = $(this);
			$this.html('<i class="fa fa-spinner fa-spin"></i> Save Changes');
			$.ajax({
				type: "POST",
				dataType: "json",
				url: site_script_object.ajaxurl,
				data: {
					action: "ajaxbprofileupdate", //calls wp_ajax_nopriv_ajaxlogin
					name: $("input[name='username']").val(),
					bio: $("input[name='bio']").val(),
					address: $("input[name='address']").val(),
					aptsuitefloor: $("input[name='aptsuitefloor']").val(),
					city: $("input[name='city']").val(),
					state: $("input[name='state']").val(),
					zip: $("input[name='zip']").val(),
					phone: $("input[name='phone']").val(),
					dob: $("input[name='dob']").val(),
					avatar: $("#profile_pic").val(),
					nonce: site_script_object.nonce,
				},
				success: function (data) {
				    if (data.reward_message != 1) {
						localStorage.setItem('rewardMessageText', data.reward_message);
					}
				    window.location.href = data.redirected_to;
				    },
			});
		}
	});

	  // On new page load, check flag and show the SweetAlert
	 $(document).ready(function () {
		  const rewardMessage = localStorage.getItem('rewardMessageText');
		  if (rewardMessage) {
		    let timerInterval;
		    Swal.fire({
		      title: rewardMessage,
		      timer: 2000,
		      timerProgressBar: true,
		      didOpen: () => {
		        Swal.showLoading();
		        const timer = Swal.getPopup().querySelector("b");
		        timerInterval = setInterval(() => {
		          if (timer) {
		            timer.textContent = `${Swal.getTimerLeft()}`;
		          }
		        }, 100);
		      },
		      willClose: () => {
		        clearInterval(timerInterval);
		      },
		    });
		    localStorage.removeItem('rewardMessageText');
		  }
		});



	$(document).on('click', '.remove-profile-img', function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: site_script_object.ajaxurl,
			data: {
				action: "ajaxremoveprofileimage", //calls wp_ajax_nopriv_ajaxlogin
				nonce: site_script_object.nonce,
			},
			success: function () {
				location.reload();
			},
		});
	});

	$("#search").keydown(function (e) {
		var searchbox = $(this);
		$(this).find('li').removeClass('selected');
		if (e.which !== 40) { return; }
		var i = $(this).closest('.input-search').find('.result-sec');

		var v = i.find('li a').first();
		v.focus();

	});

	$('#search').bind('input', function () {
		var searchtxt = $(this).val();
		var opdropdown = 'product';
		if (searchtxt.trim() == '') {
			$(".result-sec").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": opdropdown };
			var requesting;

			/* if request is in-process, kill it */
			if (requesting) {
				requesting.abort();
			};

			requesting = $.ajax({
				type: 'POST',
				async: true,
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
			}).done(function (data) {
				var result = '';
				$.each(data.hits.hits, function (index, prod) {
					//console.log(prod);
					//console.log(prod._source.product_image);
					if (prod._index == 'sipnproduct_prod') {

						if (prod._source.product_image) {
							var pimg = prod._source.product_image;
						}
						else {
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}
						result += '<li class="middle-post-search"><a class="middle-post-anchor"  href="' + prod._source.product_link + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
						if (prod._source.product_flavor) {
							result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
						}

						result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
					}
					if (prod._index == 'sipnpost_prod') {



						var pimg1 = '/wp-content/themes/SIPN/assets/images/no-image-available.png';
						var posttitle = prod._source.post_title.substring(0, 120);

						result += '<li class="middle-post-search"><a  href="' + prod._source.post_url + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" class="post-img-search" style="vertical-align:middle;width:36px !important;" src="' + pimg1 + '"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + posttitle + '</small></span><br>';
						if (prod._source.tagged_product) {
							result += '<span><small class="catg" style="font-size: 14px;">Tagged Product: ' + prod._source.tagged_product + '</small></span>';






						}

						result += '</span></td></tr></tbody></table></a></li>';
					}



				});
				//console.log(result);
				$(".result-sec").html("<ul>" + result + "</ul>");
				if (data.hits.hits != '0') {
					if ($("#search").val().trim() != '') {
						$(".result-sec").attr("style", "display: block !important");

						$(".result-sec").find('a').keydown(function (e) {

							switch (e.which) {
								case 40:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().next().find('a').first().focus();
									break;
								case 38:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().prev().find('a').first().focus();
									break;
							}
						});
						$(".result-sec").find('a').on("focusin", function (e) {
							var searchbox = $(this);
							$(this).parent().addClass("selected");

						});

						$(".result-sec").find('a').on("focusout", function (e) {
							var searchbox = $(this);
							$(this).parent().removeClass("selected");
						});

					}


					// const div = document.getElementsByClassName('header-result-sec');
					const scrollToTop = () => {
						// get the div element by its id
						const div = document.getElementById("result-sec");
						// smooth scroll to the top of the div
						div.scrollTo({
							top: 0,
							behavior: 'smooth'
						});
					}
					scrollToTop();

					// smooth scroll to the top of the div
					//      $('.header-result-sec').animate({
					//     scrollTop: $(".middle-post-search").offset().bottom
					// }, 2000);


				} else {
					$(".result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
					$(".result-sec").attr("style", "display: block !important");
				}
				/* process response */

				/* response received, reset variable */
				requesting = null;
			});









		} else {
			$(".result-sec").html("");
			$(".sec").attr("style", "display: none !important");
		}


	});

	// $("#header-search-sec").on("focusout", function(){
	// 	$(".header-result-sec").attr("style", "display: none !important");
	// });
	$(".hidden-sms").on("click", function () {


		$(".header-result-sec").attr("style", "display: none !important");
		//$(".opdropdown").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});

	$(".side-content").on("click", function () {
		$(".header-result-sec").attr("style", "display: none !important");
		//$(".opdropdown").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});

	$(".inner-content-feeds").on("click", function () {
		$(".result-sec").attr("style", "display: none !important");
	});

	$(".inner-content").on("click", function () {
		$(".header-result-sec").attr("style", "display: none !important");
		//$(".opdropdown").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});

	$(".mchat").on("click", function () {
		$(".header-result-sec").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});
	$(".mtrending").on("click", function () {
		$(".header-result-sec").attr("style", "display: none !important");
		//$(".opdropdown").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});
	$(".col-md-10").on("click", function () {
		$(".header-result-sec").attr("style", "display: none !important");
		//$(".opdropdown").attr("style", "display: none !important");
		$(".result-sec").attr("style", "display: none !important");
		$(".result-sec1").attr("style", "display: none !important");
		//$(".form-control").removeClass("active");
	});

	$("#header-search").on("focusin", function () {
		if ($(".header-result-sec").html() != '') {
			$(".header-result-sec").attr("style", "display: block !important");
		}
	});

	$("#mob_header_search").on("focusin", function () {
		if ($(".result-sec1").html() != '') {
			$(".result-sec1").attr("style", "display: block !important");
		}
	});


	// 	$('#header-search').bind('input', function(){
	//   console.log("search!");
	// });
	// $('#header-result-sec li:first').addClass('selected');
	$("#header-search").keydown(function (e) {
		var searchbox = $(this);
		$(this).find('li').removeClass('selected');
		if (e.which !== 40) { return; }
		var i = $(this).closest('.search-bar').find('.header-result-sec');

		var v = i.find('li a').first();
		v.focus();

	});



	$('#header-search').bind('input', function () {
		var searchtxt = $(this).val();
		console.log('search text',searchtxt);
		var opdropdown = $('.opdropdown').val();
		if (searchtxt.trim() == '') {
			$(".header-result-sec").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": opdropdown };
			var requesting;

			/* if request is in-process, kill it */
			if (requesting) {
				requesting.abort();
			};

			requesting = $.ajax({
				type: 'POST',
				async: true,
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
			}).done(function (data) {
				console.log('data',data);
				var result = '';
				$.each(data.hits.hits, function (index, prod) {
					//console.log(prod);
					//console.log(prod._source.product_image);
					if (prod._index == 'sipnproduct_prod') {

						if (prod._source.product_image) {
							var pimg = prod._source.product_image;
						}
						else {
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}
						result += '<li class="middle-post-search"><a class="middle-post-anchor"  href="' + prod._source.product_link + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk">' + prod._source.product_title + '</small></span></div>';
						if (prod._source.product_flavor) {
							result += '<div class="pro-desc-main"><span><small class="catg">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
						}

						result += '</div><div class="pro-price-main"><small class="price-dollar"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
					}
					if (prod._index == 'sipnpost_prod') {



						var pimg1 = '/wp-content/themes/SIPN/assets/images/no-image-available.png';
						var posttitle = prod._source.post_title.substring(0, 120);

						result += '<li class="middle-post-search"><a  href="' + prod._source.post_url + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" class="post-img-search" style="vertical-align:middle;width:36px !important;" src="' + pimg1 + '"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + posttitle + '</small></span><br>';
						if (prod._source.tagged_product) {
							result += '<span><small class="catg" style="font-size: 14px;">Tagged Product: ' + prod._source.tagged_product + '</small></span>';






						}

						result += '</span></td></tr></tbody></table></a></li>';
					}



				});
				//console.log(result);
				$(".header-result-sec").html("<ul>" + result + "</ul>");
				if (data.hits.hits != '0') {
					if ($("#header-search").val().trim() != '') {
						$(".header-result-sec").attr("style", "display: block !important");

						$(".header-result-sec").find('a').keydown(function (e) {

							switch (e.which) {
								case 40:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().next().find('a').first().focus();
									break;
								case 38:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().prev().find('a').first().focus();
									break;
							}
						});
						$(".header-result-sec").find('a').on("focusin", function (e) {
							var searchbox = $(this);
							$(this).parent().addClass("selected");

						});

						$(".header-result-sec").find('a').on("focusout", function (e) {
							var searchbox = $(this);
							$(this).parent().removeClass("selected");
						});

					}


					// const div = document.getElementsByClassName('header-result-sec');
					const scrollToTop = () => {
						// get the div element by its id
						const div = document.getElementById("header-result-sec");
						// smooth scroll to the top of the div
						div.scrollTo({
							top: 0,
							behavior: 'smooth'
						});
					}
					scrollToTop();

					// smooth scroll to the top of the div
					//      $('.header-result-sec').animate({
					//     scrollTop: $(".middle-post-search").offset().bottom
					// }, 2000);


				} else {
					$(".header-result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
					$(".header-result-sec").attr("style", "display: block !important");
				}
				/* process response */

				/* response received, reset variable */
				requesting = null;
			}).fail(function (xhr, status, error) {
            console.log('AJAX ERROR');
            console.log('status:', status);
            console.log('error:', error);
            console.log('response:', xhr.responseText);
        });









		} else {
			$(".header-result-sec").html("");
			$(".header-result-sec").attr("style", "display: none !important");
		}
	});

	//for tag product

	$("#headerpostsearch").on("focusin", function () {
		if ($(".headerpost-result-sec").html() != '') {
			$(".headerpost-result-sec").attr("style", "display: block !important");
		}
	});

	$("#headerpostsearch").keydown(function (e) {
		var searchbox = $(this);
		$(this).find('li').removeClass('selected');
		if (e.which !== 40) { return; }
		var i = $(this).closest('.write-block').find('.headerpost-result-sec');

		var v = i.find('li a').first();
		v.focus();

	});


	$('#headerpostsearch').bind('input', function () {
		var searchtxt = $(this).val();
		if (searchtxt.trim() == '') {
			$(".headerpost-result-sec").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth


		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": 'product' };
			var requesting;

			/* if request is in-process, kill it */
			if (requesting) {
				requesting.abort();
			};

			requesting = $.ajax({
				type: 'POST',
				async: true,
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
			}).done(function (data) {
				var result = '';
				$.each(data.hits.hits, function (index, prod) {
					//console.log(prod);
					//console.log(prod._source.product_image);
					if (prod._index == 'sipnproduct_prod') {

						if (prod._source.product_image) {
							var pimg = prod._source.product_image;
						}
						else {
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}

						result += '<li class="middle-post-search"><a href="javascript:void(0);"  data-id="' + prod._id + '" class="middle-post-anchor getpid" link="' + prod._source.product_title + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
						if (prod._source.product_flavor) {
							result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
						}

						result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
					}




				});
				//console.log(result);
				$(".headerpost-result-sec").html("<ul>" + result + "</ul>");
				if (data.hits.hits != '0') {
					if ($("#headerpostsearch").val().trim() != '') {
						$(".headerpost-result-sec").attr("style", "display: block !important");
						$(".closeproduct1").show();

						$(".headerpost-result-sec").find('a').keydown(function (e) {

							switch (e.which) {
								case 40:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().next().find('a').first().focus();
									break;
								case 38:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().prev().find('a').first().focus();
									break;
							}
						});
						$(".headerpost-result-sec").find('a').on("focusin", function (e) {
							var searchbox = $(this);
							$(this).parent().addClass("selected");

						});

						$(".headerpost-result-sec").find('a').on("focusout", function (e) {
							var searchbox = $(this);
							$(this).parent().removeClass("selected");
						});

					}


					// const div = document.getElementsByClassName('header-result-sec');
					const scrollToTop = () => {
						// get the div element by its id
						const div = document.getElementById("headerpost-result-sec");
						// smooth scroll to the top of the div
						div.scrollTo({
							top: 0,
							behavior: 'smooth'
						});
					}
					scrollToTop();

					// smooth scroll to the top of the div
					//      $('.header-result-sec').animate({
					//     scrollTop: $(".middle-post-search").offset().bottom
					// }, 2000);


				} else {
					$(".headerpost-result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
					$(".headerpost-result-sec").attr("style", "display: block !important");
					$(".closeproduct1").hide();

				}
				/* process response */

				/* response received, reset variable */
				requesting = null;
			});









		} else {
			$(".headerpost-result-sec").html("");
			$(".headerpost-result-sec").attr("style", "display: none !important");
		}

	});

	$("#headerpostsearch1").on("focusin", function () {
		if ($(".headerpost-result-sec").html() != '') {
			$(".headerpost-result-sec").attr("style", "display: block !important");
		}
	});

	$("#headerpostsearch1").keydown(function (e) {
		var searchbox = $(this);
		$(this).find('li').removeClass('selected');
		if (e.which !== 40) { return; }
		var i = $(this).closest('.write-block').find('.headerpost-result-sec');

		var v = i.find('li a').first();
		v.focus();

	});

	$("#headerpostsearch1").on("keyup", function () {
		//alert('hi');
		var searchtxt = $(this).val();
		if (searchtxt.trim() == '') {
			$(".headerpost-result-sec").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": 'product' };
			var requesting;

			/* if request is in-process, kill it */
			if (requesting) {
				requesting.abort();
			};

			requesting = $.ajax({
				type: 'POST',
				async: true,
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
			}).done(function (data) {
				var result = '';
				$.each(data.hits.hits, function (index, prod) {
					//console.log(prod);
					//console.log(prod._source.product_image);
					if (prod._index == 'sipnproduct_prod') {

						if (prod._source.product_image) {
							var pimg = prod._source.product_image;
						}
						else {
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}

						result += '<li class="middle-post-search"><a href="javascript:void(0);"  data-id="' + prod._id + '" class="middle-post-anchor getpid1" link="' + prod._source.product_title + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
						if (prod._source.product_flavor) {
							result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
						}

						result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
					}




				});
				//console.log(result);
				$(".headerpost-result-sec").html("<ul>" + result + "</ul>");
				if (data.hits.hits != '0') {
					if ($("#headerpostsearch1").val().trim() != '') {
						$(".headerpost-result-sec").attr("style", "display: block !important");
						$(".closeproduct").show();

						$(".headerpost-result-sec").find('a').keydown(function (e) {

							switch (e.which) {
								case 40:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().next().find('a').first().focus();
									break;
								case 38:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().prev().find('a').first().focus();
									break;
							}
						});
						$(".headerpost-result-sec").find('a').on("focusin", function (e) {
							var searchbox = $(this);
							$(this).parent().addClass("selected");

						});

						$(".headerpost-result-sec").find('a').on("focusout", function (e) {
							var searchbox = $(this);
							$(this).parent().removeClass("selected");
						});

					}


					// const div = document.getElementsByClassName('header-result-sec');
					const scrollToTop = () => {
						// get the div element by its id
						const div = document.getElementById("headerpost-result-sec");
						// smooth scroll to the top of the div
						div.scrollTo({
							top: 0,
							behavior: 'smooth'
						});
					}
					scrollToTop();

					// smooth scroll to the top of the div
					//      $('.header-result-sec').animate({
					//     scrollTop: $(".middle-post-search").offset().bottom
					// }, 2000);


				} else {
					$(".headerpost-result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
					$(".headerpost-result-sec").attr("style", "display: block !important");
					$(".closeproduct").hide();

				}
				/* process response */

				/* response received, reset variable */
				requesting = null;
			});









		} else {
			$(".headerpost-result-sec").html("");
			$(".headerpost-result-sec").attr("style", "display: none !important");
		}

	});

	$(document).ready(function () {
		$('body').on('click', '.getpid', function () {
			var copied_url = $(this).attr('link')
			if (copied_url.length > 30) {
				var text = copied_url.substring(0, 30)//cuts to 1024
				var last = text.lastIndexOf(" ")//gets last space (to avoid cutting the middle of a word)
				var text = text.substring(0, last)//cuts from last space (to avoid cutting the middle of a word)
				var text = text + `...`//adds (...) at the end to show that it's cut
			} else {
				var text = copied_url;
			}
			//alert(text);
			var ppid = $(this).attr('data-id')
			$('#fpid').val(ppid);
			// alert(ppid);
			$('#headerpostsearch').val(text);
			$(".headerpost-result-sec").attr("style", "display: none !important");


		});
	});
	$(document).ready(function () {
		$('body').on('click', '.getpid1', function () {
			var copied_url = $(this).attr('link')

			if (copied_url.length > 30) {
				var text = copied_url.substring(0, 30)//cuts to 1024
				var last = text.lastIndexOf(" ")//gets last space (to avoid cutting the middle of a word)
				var text = text.substring(0, last)//cuts from last space (to avoid cutting the middle of a word)
				var text = text + `...`//adds (...) at the end to show that it's cut
			} else {
				var text = copied_url;
			}
			var ppid = $(this).attr('data-id')
			$('#fpid1').val(ppid);
			// alert(ppid);
			$('#headerpostsearch1').val(text);
			$(".headerpost-result-sec").attr("style", "display: none !important");


		});
	});
	//for mobile view
	$("#mob_header_search").on("focusin", function () {
		if ($(".result-sec1").html() != '') {
			$(".result-sec1").attr("style", "display: block !important");
		}
	});

	$("#mob_header_search").keydown(function (e) {
		var searchbox = $(this);
		$(this).find('li').removeClass('selected');
		if (e.which !== 40) { return; }
		var i = $(this).closest('.search-bar').find('.result-sec1');

		var v = i.find('li a').first();
		v.focus();

	});


	$('#mob_header_search').bind('input', function () {
		var searchtxt = $(this).val();
		var opdropdown = $('#opdropdown-mobile').val();
		if (searchtxt.trim() == '') {
			$(".result-sec1").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": opdropdown };
			var requesting;

			/* if request is in-process, kill it */
			if (requesting) {
				requesting.abort();
			};

			requesting = $.ajax({
				type: 'POST',
				async: true,
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
			}).done(function (data) {
				var result = '';
				$.each(data.hits.hits, function (index, prod) {
					//console.log(prod);
					//console.log(prod._source.product_image);
					if (prod._index == 'sipnproduct_prod') {

						if (prod._source.product_image) {
							var pimg = prod._source.product_image;
						}
						else {
							var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
						}
						result += '<li class="middle-post-search"><a class="middle-post-anchor"  href="' + prod._source.product_link + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
						if (prod._source.product_flavor) {
							result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
						}

						result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
					}
					if (prod._index == 'sipnpost_prod') {



						var pimg1 = '/wp-content/themes/SIPN/assets/images/no-image-available.png';
						var posttitle = prod._source.post_title.substring(0, 120);

						result += '<li class="middle-post-search"><a  href="' + prod._source.post_url + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" class="post-img-search" style="vertical-align:middle;width:36px !important;" src="' + pimg1 + '"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + posttitle + '</small></span><br>';
						if (prod._source.tagged_product) {
							result += '<span><small class="catg" style="font-size: 14px;">Tagged Product: ' + prod._source.tagged_product + '</small></span>';






						}

						result += '</span></td></tr></tbody></table></a></li>';
					}



				});
				//console.log(result);
				$(".result-sec1").html("<ul>" + result + "</ul>");
				if (data.hits.hits != '0') {
					if ($("#mob_header_search").val().trim() != '') {
						$(".result-sec1").attr("style", "display: block !important");

						$(".result-sec1").find('a').keydown(function (e) {

							switch (e.which) {
								case 40:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().next().find('a').first().focus();
									break;
								case 38:
									e.preventDefault(); // prevent moving the cursor
									$(this).parent().prev().find('a').first().focus();
									break;
							}
						});
						$(".result-sec1").find('a').on("focusin", function (e) {
							var searchbox = $(this);
							$(this).parent().addClass("selected");

						});

						$(".result-sec1").find('a').on("focusout", function (e) {
							var searchbox = $(this);
							$(this).parent().removeClass("selected");
						});

					}


					// const div = document.getElementsByClassName('header-result-sec');
					const scrollToTop = () => {
						// get the div element by its id
						const div = document.getElementById("result-sec1");
						// smooth scroll to the top of the div
						div.scrollTo({
							top: 0,
							behavior: 'smooth'
						});
					}
					scrollToTop();

					// smooth scroll to the top of the div
					//      $('.header-result-sec').animate({
					//     scrollTop: $(".middle-post-search").offset().bottom
					// }, 2000);


				} else {
					$(".result-sec1").html("<ul><center style='color:red';>No results found</center></ul>");
					$(".result-sec1").attr("style", "display: block !important");
				}
				/* process response */

				/* response received, reset variable */
				requesting = null;
			});









		} else {
			$(".result-sec1").html("");
			$(".result-sec1").attr("style", "display: none !important");
		}

	});


	$(".filter-check").on("change", function () {
		$("#search_form").submit();
	});


	// $('.icon-fav').on('click', function (e) {
	// 	e.preventDefault();

	// 	var rid = $(this).attr('rid');
	// 	var liked = $(this).attr('liked');

	// 	// If already liked, return early to avoid further action
	// 	if (liked == 1) {
	// 		return;
	// 	}

	// 	var cur_ele = $(this);
	// 	var cur_ele_img = $(this).children('img');

	// 	// Disable the click event until AJAX completes
	// 	cur_ele.off('click');

	// 	var like = (liked == '0') ? '1' : '0';

	// 	$.ajax({
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		url: site_script_object.ajaxurl,
	// 		data: {
	// 			'action': 'ajaxchatlike',
	// 			'reply_id': rid,
	// 			'like': like,
	// 			'nonce': site_script_object.nonce,
	// 		},
	// 		success: function (data) {
	// 			if (data == '1') {
	// 				cur_ele.attr("liked", '1');
	// 				cur_ele_img.attr("src", "/wp-content/themes/SIPN/assets/images/chat/icon-fav.png");
	// 			} else {
	// 				cur_ele.attr("liked", '0');
	// 				cur_ele_img.attr("src", "/wp-content/themes/SIPN/assets/images/chat/icon-fav-before.png");
	// 			}
	// 		},
	// 		complete: function () {
	// 			// Re-enable the click event after the AJAX request is complete
	// 			cur_ele.on('click', function (e) { /* re-attach the click handler here if needed */ });
	// 		}
	// 	});
	// });


	$('.icon-fav').on('click', function (e) {
		e.preventDefault();

		var rid = $(this).attr('rid');
		var liked = $(this).attr('liked');

		// If already liked, return early to avoid further action
		if (liked == 1) {
			return;
		}

		var cur_ele = $(this);
		var cur_ele_img = $(this).children('img');

		// Check if the button is already disabled (processing AJAX)
		if (cur_ele.data('processing') === true) {
			return;
		}

		// Disable the button by setting a flag
		cur_ele.data('processing', true);

		var like = (liked == '0') ? '1' : '0';

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxchatlike',
				'reply_id': rid,
				'like': like,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {

				if (data.status == 1) {
					cur_ele.attr("liked", '1');
					cur_ele_img.attr("src", "/wp-content/themes/SIPN/assets/images/chat/icon-fav.png");
				} else {
					cur_ele.attr("liked", '0');
					cur_ele_img.attr("src", "/wp-content/themes/SIPN/assets/images/chat/icon-fav-before.png");
				}
			},
			complete: function () {

				setTimeout(function () {
					// Re-enable the button after the delay
					cur_ele.data('processing', false);
				}, 2000);
			},
			error: function () {

				setTimeout(function () {
					cur_ele.data('processing', false);
				}, 2000); // Delay for 2 seconds
			}
		});
	});



	$(".comments .icon-comments").on("click", function () {
		//console.log('test..');
		$('.edit_sec').remove();
		var reply_content_selector = '.reply_content_' + $(this).attr('rid') + ' p';
		var reply_content = $(reply_content_selector).html();
		$(this).closest('.comments').append("<div class='edit_sec'><textarea name='edit_comment' id='edit_comment' class='edit_comment edit_comment_" + $(this).attr('rid') + "'>" + reply_content + "</textarea><input class='edit_submit' type='button' value='Submit' rid='" + $(this).attr('rid') + "'></div>");
	});


	$('body').on('click', '.edit_submit', function () {
		var rid = $(this).attr('rid');
		var reply_content_selector = '.edit_comment_' + rid;
		var reply_content = $(reply_content_selector).val();
		var reply_content_html_selector = '.reply_content_' + rid + ' p';
		//console.log(rid+'____'+reply_content);


		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxchatedit', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': rid,
				'reply': reply_content,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log(data);
				if (data.status) {
					//console.log("success");
					$('.edit_sec').remove();
					$(reply_content_html_selector).html(reply_content);
				}
				else {
					//console.log("failure");
					$('.edit_sec').remove();
					//cur_ele.attr("liked", '0');
					//cur_ele_img.attr("src", "/wp-content/themes/SIPN/assets/images/chat/icon-fav-before.png");
				}
			}
		});
	});


	$('body').on('click', '.like_profile', function () {
		var is_liked = $(this).attr('liked');
		if (is_liked.trim() == '') {
			is_liked = 0;
		} else {
			if (is_liked == 1) {
				is_liked = 0;
			}
			else {
				is_liked = 1;
			}
		}

		var profile_id = $(this).attr('pid');

		//if(is_liked != ''){
		var like_data = { "profile_id": profile_id, "like": is_liked };

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxlikeprofile', //calls wp_ajax_nopriv_ajaxlogin
				'profile_id': profile_id,
				'like': is_liked,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log(data);
				if (data == '1') {
					//console.log("wish 1");
					$('.like').removeClass("inactive");
					var cnt = parseInt($('.profile_likes_count').html()) + 1;
					$('.profile_likes_count').html(cnt);
					$('.like_profile').attr('liked', '1');
				}
				else if (data == '0') {
					//console.log("wish 0");
					$('.like').addClass("inactive");
					var cnt = parseInt($('.profile_likes_count').html()) - 1;
					$('.profile_likes_count').html(cnt);
					$('.like_profile').attr('liked', '0');

				}
			}
		});
		//}else{
		//$(".result-sec").html("");
		//$(".result-sec").attr("style", "display: none !important");
		//}
	});

	$('body').on('click', '.barpop_yes', function () {
		window.location.href = $(".bar-create").attr('link');
	});


	$('body').on('click', '#save_bar', function () {
		var bar_id = $(this).attr('bid');
		var bar_name = $("#bar_name").val();
		var shelf1 = $("#shelfedit1").val();
		var s1 = $("#shelfedit1").attr('ssid');
		var shelf2 = $("#shelfedit2").val();
		var s2 = $("#shelfedit2").attr('ssid');
		var shelf3 = $("#shelfedit3").val();
		var s3 = $("#shelfedit3").attr('ssid'); //added by sumeeth for shelf edit
		if ($("#bar_state").is(':checked')) {
			var bar_state = '1';
		} else {
			var bar_state = '0';
		}

		if (bar_id != '' && bar_state != '') {
			//var bar_data = { "bar_id": bar_id, "bar_name": bar_name, "is_public": bar_state};

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxbarupdate', //calls wp_ajax_nopriv_ajaxlogin
					'bar_id': bar_id,
					'bar_name': bar_name,
					'is_public': bar_state,
					'shelf1': shelf1,
					'shelf2': shelf2,
					'shelf3': shelf3,
					's1': s1,
					's2': s2,
					's3': s3, //added by sumeeth for shelf edit
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					$(".edit-bar-sec").hide();
					$("#bar_name").val(bar_name);
					$(".bar-title span").html(bar_name);
					$('.shelfedit').prop('readonly', true); //added by sumeeth for shelf edit
				}
			});
		}

	});


	$('body').on('click', '.show-bar-edit', function () {
		$('.edit-bar-sec').toggle();
		$('.shelfedit').prop('readonly', false); //added by sumeeth for shelf edit
	});



	$('body').on('click', '.delete-product', function () {
		if (confirm('Confirm remove product from bar?')) {
			var prod_id = $(this).closest('li').attr('pid');
			var bar_id = $(this).closest('ul').attr('brid');
			var shelf_id = $(this).closest('ul').attr('slid');
			//console.log('delete'+prod_id+'--'+bar_id+'--'+shelf_id);
			var ele = $(this).closest('li');
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxproductdelete',
					'bar_id': bar_id,
					'shelf_id': shelf_id,
					'product_id': prod_id,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					ele.remove();
					location.reload();
				}
			});
		} else {
			console.log('cancel')
		}
	});



	$('body').on('click', '.like_timeline', function (e) {
		e.preventDefault();

		var rid = $(this).attr('rid');
		var liked = $(this).attr('liked');
		var cur_ele = $(this);


		if (cur_ele.data('processing') === true) {
			return;
		}


		cur_ele.data('processing', true);

		var like = (liked == '0') ? '1' : '0'; // Toggle like/unlike

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxchatlike',
				'reply_id': rid,
				'like': like,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var innerContent = cur_ele.closest('.inner-content');
				var onlyLike = innerContent.find('.onlylike');
				var onlyCountLikes = innerContent.find('.onlycountlikes');
				var currentLikes = parseInt(onlyLike.html()) || 0;


				if (data.is_liked == '1') {

									
					cur_ele.attr("liked", '1');
					cur_ele.parent().addClass('active');


					var newLikes = currentLikes + 1;
					onlyLike.html(newLikes);
					onlyCountLikes.html('&nbsp;' + (newLikes == 1 ? 'Like' : 'Likes'));
	
					if (data.reward_message != '') {
						let timerInterval;
						Swal.fire({
						  title: data.reward_message,
						  timer: 2000,
						  timerProgressBar: true,
						  didOpen: () => {
						    Swal.showLoading();
						    const timer = Swal.getPopup().querySelector("b");
						    timerInterval = setInterval(() => {
						      timer.textContent = `${Swal.getTimerLeft()}`;
						    }, 2000);
						  },
						  willClose: () => {
						    clearInterval(timerInterval);
						  }
						}).then((result) => {
						  if (result.dismiss === Swal.DismissReason.timer) {
						    //console.log("I was closed by the timer");
						  }
						});
					}

						
				}

				else {
					cur_ele.attr("liked", '0');
					cur_ele.parent().removeClass('active');

					var newLikes = Math.max(currentLikes - 1, 0);
					onlyLike.html(newLikes);
					onlyCountLikes.html('&nbsp;' + (newLikes == 1 ? 'Like' : 'Likes'));
				}
			},
			complete: function () {
				cur_ele.data('processing', false);
			},
			error: function () {
				cur_ele.data('processing', false);
			}
		});
	});


	// if($('.homepage-video-section').length){
	// var slideIndex = 0;
	//        showSlides();

	//        function showSlides() {
	//          var i;
	//          var slides = document.getElementsByClassName("videoSlide");
	//          var dots = document.getElementsByClassName("dot");
	//          for (i = 0; i < slides.length; i++) {
	//            slides[i].style.display = "none";  
	//          }
	//          slideIndex++;
	//          if (slideIndex > slides.length) {slideIndex = 1}    
	//          for (i = 0; i < dots.length; i++) {
	//            dots[i].className = dots[i].className.replace(" active", "");
	//          }
	//          slides[slideIndex-1].style.display = "block";  
	//          dots[slideIndex-1].className += " active";
	//          setTimeout(showSlides, 5000); // Change image every 3 seconds
	//        }
	// }


	$("input[name='phone'], input[name='your-phone'], input[name='aContactNo']").keyup(function () {
		/*$(this).val(
		  $(this)
			.val()
			.replace(/^(\d{3})(\d{3})(\d+)$/, "($1)-$2-$3")
		);*/
		let newVal = $(this).val().replace(/\D/g, "");

		if (newVal.length === 0) {
			newVal = "";
		} else if (newVal.length <= 3) {
			newVal = newVal.replace(/^(\d{0,3})/, "($1)");
		} else if (newVal.length <= 6) {
			newVal = newVal.replace(/^(\d{0,3})(\d{0,3})/, "($1)-$2");
		} else if (newVal.length <= 10) {
			newVal = newVal.replace(/^(\d{0,3})(\d{0,3})(\d{0,4})/, "($1)-$2-$3");
		} else {
			newVal = newVal.substring(0, 9);
			newVal = newVal.replace(/^(\d{0,3})(\d{0,3})(\d{0,4})/, "($1)-$2-$3");
		}
		$(this).val(newVal);
	});

});


function showAgeVerification(){
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
}
function showAlertMessage(msg){
	$('.reward_message').html();
	$('#reward_alert').modal('show');
}

function checkInputLength(input) {
	if (input.value.length > 60) {
		input.value = input.value.slice(0, 60);
		// alert("You can only enter up to 100 characters.");
	}
}
function copyFormatted(html) {
	// Create an iframe (isolated container) for the HTML
	var container = document.createElement("div");
	container.innerHTML = html;

	// Hide element
	container.style.position = "fixed";
	container.style.pointerEvents = "none";
	container.style.opacity = 0;

	// Detect all style sheets of the page
	var activeSheets = Array.prototype.slice
		.call(document.styleSheets)
		.filter(function (sheet) {
			return !sheet.disabled;
		});

	// Mount the iframe to the DOM to make `contentWindow` available
	document.body.appendChild(container);

	// Copy to clipboard
	window.getSelection().removeAllRanges();

	var range = document.createRange();
	range.selectNode(container);
	window.getSelection().addRange(range);

	document.execCommand("copy");
	for (var i = 0; i < activeSheets.length; i++) activeSheets[i].disabled = true;
	document.execCommand("copy");
	for (var i = 0; i < activeSheets.length; i++)
		activeSheets[i].disabled = false;

	// Remove the iframe
	document.body.removeChild(container);
}






// Create nice animation on copy button click
jQuery(document).ready(function ($) {
	$('body').on('click', '.copy-cls', function () {
		var copied_url = $(this).attr('link')
		copyFormatted(copied_url);
		$(this).append('<span class="copied">Link copied to clipboard.</span>');

		setTimeout(function () {
			$(".copied").remove();
		}, 2000);
	});

	$('body').on('click', '.copy-cls_coll', function () {
		var copied_url = $(this).attr('link')
		copyFormatted(copied_url);
		$(this).append('<span class="copied_coll">Link copied to clipboard.</span>');

		setTimeout(function () {
			$(".copied_coll").remove();
		}, 2000);
	});



});

jQuery(document).ready(function ($) {
	$('body').on('click', '#copy-cls-home', function () {
		var copied_url = $(this).attr('link')
		copyFormatted(copied_url);
		$(".home-copytext").remove();
		$(this).append('<p class="copied home-copytext">Link copied to clipboard.</p>');

		setTimeout(function () {
			$(".home-copytext").remove();
		}, 2000);
	});
});







//scroll smoothly
// Define selector for selecting
// anchor links with the hash
/*let anchorSelector = 'a[href^="#"]';
 
$(anchorSelector).on('click', function (e) {
 
	// Prevent scrolling if the
	// hash value is blank
	e.preventDefault();
 
	// Get the destination to scroll to
	// using the hash property
	let destination = $(this.hash);
 
	// Get the position of the destination
	// using the coordinates returned by
	// offset() method and subtracting 50px
	// from it.
	let scrollPosition = destination.offset().top - 50;
 
	// Specify animation duration
	let animationDuration = 500;
 
	// Animate the html/body with
	// the scrollTop() method
	$('html, body').animate({
		scrollTop: scrollPosition
	}, animationDuration);
});*/


//When reached end of home page
jQuery(document).ready(function ($) {
	if ($('.home-page').length) {
		var flag = 0;
		//alert(flag);
		$(window).on('scroll', function () {
			if ($(window).scrollTop() >= $('.main-content').offset().top + $('.main-content').outerHeight() - window.innerHeight - 100) {
				//alert(flag);
				if (!flag) {
					var page = parseInt(localStorage.getItem('timeline_page')) + 1;
					//console.log('You reached the end of the DIV');
					//alert(flag);
					flag = 1;
					$.ajax({
						type: 'POST',
						dataType: 'html',
						url: site_script_object.ajaxurl,
						data: {
							'action': 'ajaxloadtimeline',
							'page': page,
							'nonce': site_script_object.nonce,
						},
						success: function (data) {
							//console.log("data-->"+data);
							$(".main-content").append(data);
							flag = 0;
							localStorage.setItem('timeline_page', page);
						}
					});
				}
			}
		});
	}

	//added by sumeeth
	var icon = document.querySelector(".fa-search");
	var search = document.querySelector('#header-search');

	var form = document.querySelector('.form');
	if (icon) icon.onclick = function () {
		$('#header-search').focus();
		//$('#opdropdown').toggle();
		//   search.classList.toggle('active')
		// form.classList.toggle('active')

	}



	// var mob_icon = document.querySelector(".fa-mob");
	// var mob_search = document.querySelector('#mob_header_search');
	// var mob_form = document.querySelector('.mob_form'); 
	//  mob_icon.onclick = function() {
	// 	//$('#mob_header_search').addClass('active');
	// 	//$('.mob_form').addClass('active');
	// 	// $('#mob_header_search').focus();
	//  //    mob_search.classList.toggle('active')
	//  //    mob_form.classList.toggle('active')

	// }

	$(document).ready(function () {
		var button = document.querySelector('#nav-icon4');
		if (button) button.addEventListener('click', function () {
			var slide = document.querySelector('.left-slide-bar');
			if (slide) slide.classList.toggle('open-menu');
			button.classList.toggle('open');

		});

	});

	$(".edicloseimage").click(function () {
		//	alert('fdsf');
		$('#output').attr('src', '');
		$('#output').hide();
		$('.commentImg').val('');
		$('#editpostoutputimage').hide();
		$('.edicloseimage').hide();
		$('.sumee').val('');
		$a = $("#editModal .submitEditWrapper").attr("rid");
		var addcommenttext = $('#comment_' + $a).val();
		//alert(addcommenttext.trim().length);
		if (addcommenttext.trim().length > 0) {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
			//$('#tagproduct1').prop('disabled', false); //for tag a product
			//$('#tagproduct1').removeClass('colorbttn'); //for tag a product

		} else {


			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');

		}


	});
	$(".edicloseimage1").click(function () {
		$('#output1').attr('src', '');
		$('#output1').hide();
		$('.commentImg').val('');
		$('#editpostoutputimage1').hide();
		$('.edicloseimage1').hide();
		var addcommenttext = $('.text-area comment').val();
		if (addcommenttext == 'undefined') {
			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');

		} else {
			$a = $('.commentImg').val();
			if (addcommenttext.length > 0 || $a != '') {
				$('.post').prop('disabled', false);
				$('.post').removeClass('inactive');
			} else {
				$('.post').prop('disabled', true);
				$('.post').addClass('inactive');
			}
		}


	});

	$(".edicloseimage2").click(function () {
		$('#blah').attr('src', '');
		$('#blah').hide();
		$('#comment_img_0').val('');
		$('#addimage').hide();
		$('.edicloseimage2').hide();
		var addcommenttext = $('.text-area').val();
		$a = $('#comment_img_0').val();
		if (addcommenttext.length > 0 || $a != '') {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
		} else {
			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');
		}

	});

	$(".commentcloseimage").click(function () {
		$('#blah1').attr('src', '');
		$('#blah1').hide();
		$('.commentImg').val('');
		$('#editimage').hide();
		$('.commentcloseimage').hide();
		var addcommenttext = $('.text-area comment replsum').val();
		$a = $('.commentImg').val();
		if (addcommenttext.length > 0 || $a != '') {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
		} else {
			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');
		}

	});


	$(".replsum").on("keyup", function () {
		//   alert('hi');
		var addcommenttext = $(this).val();
		$a = $('.commentImg replsum').val();
		//alert($a);
		//alert(addcommenttext.trim().length);
		if (addcommenttext.trim().length > 0 || $a != '') {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
			$('#tagproduct').prop('disabled', false); //for tag a product
			$('#tagproduct').removeClass('colorbttn'); //for tag a product

		} else if (addcommenttext.length == 0 && $a == '') {
			//alert('hi');
			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');
			$('#tagproduct').prop('disabled', true); //for tag a product
			$('#tagproduct').addClass('colorbttn');
			$('#headerpostsearch').hide();
			$('.closeproduct1').hide();
		} else {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
			$('#tagproduct').prop('disabled', true); //for tag a product
			$('#tagproduct').addClass('colorbttn'); //for tag a product
			$('#headerpostsearch').hide();
		}

	});

	$(".invite_friends").click(function () {
		$('#social-icons1').toggle();

	});

	//for collection share button
	$(".sharecoll").click(function () {
		$('#share_collections_icons').toggle();

	});
});

//for event predictive search by sumeeth
jQuery(document).ready(function ($) {
	$("#eventsearch").on("keyup", function () {
		var myVar = $('#op').val();
		if (myVar == 'free') {
			myVar = 0;
		}
		var searchtxt1 = $(this).val();
		if (searchtxt1.trim() == '') {
			$(".result-sec").attr("style", "display: none !important");
		}
		$(".search-load").remove();
		if (searchtxt1.length > 2) {

			$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "keyword": searchtxt1, "value": myVar, "products_per_page": "100", "rating_limit": "1" };
			$.ajax(
				{
					url: "/wp-json/events/v2/list/",
					type: 'post',
					data: JSON.stringify(search_data),
					dataType: 'json',
					contentType: 'application/json',
					success: function (data) {
						var result = '';

						$.each(data.events, function (index, prod) {
							//alert(prod.event_title);
							result += '<li><a href="https://sipnbourbon.com/event/' + prod.post_name + '"><table class="pdd" width="98%"><tbody><tr><td class="text-center td15"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;">' + prod.event_title + '</small></span><br>';
							if (prod.event_venue) {
								result += '<span><small class="catg">Location: ' + prod.event_venue.address + '</small></span>';
							}
							if (prod.event_start_date != '') {
								result += '<span><small class="catg">Date : ' + prod.event_start_date + ' - ' + prod.event_end_date + ' </small>';
							} else {
								result += '<span><small class="catg"></small>';
							}
							if (prod.event_price != '') {
								result += '<small class="red text-right"><!----><span><strong>$' + prod.event_price + '</strong></span><!----></small></span></td></tr></tbody></table></a></li>';
							} else {
								result += '<small class="red text-right"><!----><span><strong>Free</strong></span><!----></small></span></td></tr></tbody></table></a></li>';
							}
							//result += '<li><a href="'+prod.product_link+'">'+prod.product_title+'</li>';
						});
						//console.log(result);
						$(".result-sec").html("<ul>" + result + "</ul>");
						if (data.total_events != '0') {
							if ($("#eventsearch").val().trim() != '') {
								$(".result-sec").attr("style", "display: block !important");
								$(".result-zero").attr("style", "display: none !important");
							}
						} else {
							$(".result-zero").attr("style", "display: block !important");
							$(".result-sec").attr("style", "display: none !important");
						}
						$(".search-load").remove();
					}
				}
			);
		} else {
			$(".result-sec").html("");
			$(".result-sec").attr("style", "display: none !important");
		}
	});


	$('body').on('click', '.replies_sponslist', function (e) {
		$("#sponsoredModal .result-replies").html('');
		var reply_id = $(this).attr('rid');
		$("#sponsoredModal .result-replies").attr("rid", reply_id);

		$("#sponsoredModal .comment").addClass("pcomment_" + reply_id);
		$("#sponsoredModal .comment").attr("id", "comment_" + reply_id);
		$("#sponsoredModal .commentInput").attr("rid", reply_id);
		$("#sponsoredModal .commentImg").attr("id", "comment_img_" + reply_id);
		$("#sponsoredModal .submitsponsRepliesWrapper").attr("rid", reply_id);

		var modal = document.getElementById("sponsoredModal");
		modal.style.display = "block";

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxsponstimelinecomments', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': reply_id,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {


				var html = '';
				if (data.length) {
					html += '<div class="child-container" style="padding-left: 5rem; padding-top: 1rem;">';
				}
				$.each(data, function (index, itemData) {
					console.log(itemData);
					$('.spcommentscount').text('');
					$('.spcommentscount').text('Sponsored Comments (' + itemData.commentscount + ')');
					$('.rlist_' + reply_id).html(itemData.commentscount);
					html += '<div class="reply-msg user-feed" id="msg-' + itemData.reply_id + '"><div class="get-msg"><div class="profile-pic">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">';
					} else {
						html += '<a href="/login">';
					}
					html += '<img class="img-circle" src="' + itemData.avatar + '" alt="wine-bottle" width="60" height="60"></a><div class="user-name sender-chat sender-chat-alt-div">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">' + itemData.author + '</a> ' + itemData.reply_date;
					} else {

						html += '<a href="/login">' + itemData.author + '</a> ' + itemData.reply_date;
					}
					html += '<br><span class="user-msg rl-msg-text">' + itemData.reply.replace(/\n/g, "<br>") + '</span>';

					if (itemData.reply_image) {
						html += '<span class="upload-image"><img src="' + itemData.reply_image + '" width="100%" alt=""></span>';
					}
					html += '</div></div>';

					//for design
					/* if (itemData.edit_flag != 0) {
					 html += '<div class="dropdown"><a href="#" class="dropbtn"><span class="fa fa-ellipsis-v more-icon"></span></a><div class="dropdown-content">';
 
 
 
 
					 // if (itemData.edit_flag==0) {
					 // html += '<a href="javascript:void(0);" class="report-tl-post" rid="'+itemData.reply_id+'" post_url="'+itemData.url+'"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>';
					 // }
 
				 	
						 html += '<a href="javascript:void(0);" class="edit-tl-sponscomment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '"><span><i class="far fa-edit bar-edit"></i></span>Edit</a>';
						 html += '<a href="javascript:void(0);" class="delete-tl-sponcomment" rid="' + itemData.reply_id + '"><span><i class="fa fa-trash"></i></span>Delete</a>';
				 	
					 html += '</div></div>';
						 }*/
					html += '</div>'




					html += '<div class="msg-opt"><ul class="list-inline">';

					//by sumeeth
					if (itemData.total_replies_count) {
						/*html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list2 rlist_' + itemData.reply_id + '"  rid="' + itemData.reply_id + '"><span class="reply-cmnt"><i class="fa fa-reply-all"></i></span>Replies (' + itemData.total_replies_count + ')</a></li>';
					*/
						html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list2 rlist_' + itemData.reply_id + '" rid="' + itemData.reply_id + '"><span><span class="icon-replay"><img src="/wp-content/themes/SIPN/assets/images/icon-reply.png"></span></span>' + itemData.total_replies_count + ' Reply</a></li>';
					} else {
						html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list2 rlist_' + itemData.reply_id + '" rid="' + itemData.reply_id + '"><span><span class="icon-replay"><img src="/wp-content/themes/SIPN/assets/images/icon-reply.png"></span></span>Reply</a></li>';
					}

					if (itemData.edit_flag == 0) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="' + itemData.reply_id + '" post_url="' + itemData.url + '">Report</a></li>';
					}
					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="edit-tl-sponscomment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '">Edit</a>';
					}
					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="delete-tl-sponcomment" rid="' + itemData.reply_id + '">Delete</a>';
					}
					// if (itemData.edit_flag==0) {
					// html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="'+itemData.reply_id+'" post_url="'+itemData.url+'"><span></span>Report</a>.</li>';
					// }

					// if (itemData.edit_flag) {
					// 	html += '<li class="list-item"><a href="javascript:void(0);"  class="edit-tl-post" rid="'+itemData.reply_id+'"><span></span>Edit</a>.</li>';
					// 	html += '<li class="list-item"><a href="javascript:void(0);"  class="delete-tl-post" rid="'+itemData.reply_id+'"><span></span>Delete</a>.</li>';
					// }

					/*html += '<li class="list-item">Commented ' + itemData.reply_date + '</li>';
*/
					html += '</ul></div></div>';

				});
				if (data.length) {
					html += '</div>';
				}
				//var html_element = '.sub_replies_'+reply_id;
				$("#sponsoredModal .result-replies").html(html);

			}
		});

	});


});





jQuery(document).ready(function ($) {
	$('body').on('click', '.submitsponsRepliesWrapper', function (e) {
		//console.log("wishlist");
		//$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		//console.log(comment_image);

		if (comment.length <= 0) {
			return false;
		}
		var cur_ele = $(this);
		cur_ele.html("<i class=\"fa fa-spinner fa-spin\"></i>")
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddsponscommenttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var trigger_selector = ".rlist_" + rid;
				//console.log(trigger_selector);
				$(".more-user-info textarea").val('');
				//if(!cur_ele.parent().hasClass("post-comments-up")){
				$(trigger_selector).trigger('click');
				//}
				//$(".page-loader").hide();
				$(".pcomment_" + rid).val('');
				cur_ele.html("<img src='/wp-content/themes/SIPN/assets/images/icon-send.png'>");
				//location.reload();

			}
		});

	});
	$('body').on('click', '.submitsponsRepliesWrapperindex', function (e) {
		//console.log("wishlist");
		//$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#commentindex_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		//console.log(comment_image);

		if (comment.length <= 0) {
			return false;
		}
		var cur_ele = $(this);
		cur_ele.html("<i class=\"fa fa-spinner fa-spin\"></i>")
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxaddsponscommenttotimeline', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var trigger_selector = ".rlist_" + rid;
				//console.log(trigger_selector);
				$(".more-user-info textarea").val('');
				//if(!cur_ele.parent().hasClass("post-comments-up")){
				$(trigger_selector).trigger('click');
				//}
				//$(".page-loader").hide();
				$(".pcommentindex_" + rid).val('');
				cur_ele.html("<img src='/wp-content/themes/SIPN/assets/images/icon-send.png'>");
				//location.reload();

			}
		});

	});




	$('body').on('click', '.edit-tl-sponscomment', function (e) {
		var reply_id = $(this).attr('rid');
		var reply_image = $(this).attr('rimagecomment');
		//alert(reply_image);
		//console.log('-->'+$(this).closest('.user-feed').find('.user-msg').html());
		$("#editsponsModal1 .comment").attr("id", "comment_" + reply_id);

		var msg = $(this).closest('.user-feed').find('.user-msg').html();

		//console.log('test----'+msg);
		if (msg == '' || typeof (msg) == "undefined") {
			//console.log('test');
			var msg = $(this).closest('.main-post').find('.rl-msg-text').html();

		}
		var msg = msg.replace(/<img[^>]*>/g, "");
		msg = msg.replace("<br>", "");
		$("#editsponsModal1 .comment").val(msg.trim());
		$("#editsponsModal1 .commentInput").attr("rid", reply_id);
		$("#editsponsModal1 #output123").attr("src", reply_image);
		if (reply_image != '') {
			$('#editsponsModal1  #output123').show();
			$('#editsponsModal1  #editpostoutputimage123').show();
			$('#editsponsModal1  .edicloseimage1').show();
		}
		$("#editsponsModal1 .commentImg").attr("id", "comment_img_" + reply_id);
		$("#editsponsModal1 .submitsponsEditWrapper1").attr("rid", reply_id);

		var modal16 = document.getElementById("editsponsModal1");
		modal16.style.display = "block";
		var modal17 = document.getElementById("repliesModal1");
		modal17.style.display = "none";
	});


	$('body').on('click', '.submitsponsEditWrapper1', function (e) {
		//console.log("wishlist");
		//$(".page-loader").show();
		var rid = $(this).attr('rid');
		var comment_id = "#comment_" + rid;
		var image_id = "#comment_img_" + rid;
		var comment = $(comment_id).val();
		var comment_image = $(image_id).val();
		//console.log(comment);
		//console.log(comment_image);
		var cur_ele = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxeditsponspost', //calls wp_ajax_nopriv_ajaxlogin
				'rid': rid,
				'reply': comment,
				'reply_img': comment_image,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				//console.log(data);
				var msg = "#msg-" + rid + " .user-msg";
				var msg_img = "#msg-" + rid + " .upload-image img";
				$(msg).html(comment);
				if (data.reply_image) {
					$(msg_img).attr("src", data.reply_image);
					$(msg_img).removeAttr('style');
				}
				$(cur_ele).closest('textarea').val('');
				$(cur_ele).closest('.cimg').val('');
				var modal6 = document.getElementById("editsponsModal1");
				modal6.style.display = "none";
				//$(".page-loader").hide();
				location.reload();

			}
		});

	});

	$('body').on('click', '.replies_list2', function (e) {
		$("#repliesModal2 .result-replies").html('');
		var reply_id = $(this).attr('rid');
		$("#repliesModal2 .result-replies").attr("rid", reply_id);

		$("#repliesModal2 .comment").attr("id", "comment_" + reply_id);
		$("#repliesModal2 .comment").addClass('pcomment_' + reply_id);
		//$("#replyModal .comment").val($(this).closest('.user-feed').find('.user-msg').html().trim());
		$("#repliesModal2 .commentInput").attr("rid", reply_id);
		$("#repliesModal2 .commentImg").attr("id", "comment_img_" + reply_id);
		$("#repliesModal2 .submitsponsRepliesWrapper").attr("rid", reply_id);

		var modal = document.getElementById("repliesModal2");
		modal.style.display = "block";

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxsponstimelinecomments', //calls wp_ajax_nopriv_ajaxlogin
				'reply_id': reply_id,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var html = '';
				//console.log(data.length);
				if (data.length) {
					html += '<div class="child-container" style="padding-left: 5rem; padding-top: 1rem;">';
					//html += '<div class="line-div"></div>';
				}
				$.each(data, function (index, itemData) {
					//console.log(itemData);


					html += '<div class="reply-msg user-feed" id="msg-' + itemData.reply_id + '"><div class="get-msg"><div class="profile-pic">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">';
					} else {
						html += '<a href="/login">';
					}
					html += '<img class="img-circle" src="' + itemData.avatar + '" alt="wine-bottle" width="60" height="60"></a><div class="user-name sender-chat sender-chat-alt-div">';
					if (itemData.bid != 0) {
						html += '<a href="' + itemData.bid + '">' + itemData.author + '</a>' + itemData.reply_date;
					} else {

						html += '<a href="/login">' + itemData.author + '</a>' + itemData.reply_date;
					}
					html += '<br><span class="user-msg rl-msg-text rlmsgtext_' + itemData.reply_id + '">' + itemData.reply.replace(/\n/g, "<br>") + '</span>';

					if (itemData.reply_image) {
						html += '<span class="upload-image"><img src="' + itemData.reply_image + '" width="100%" alt=""></span>';
					}
					html += '</div></div>';





					// if (itemData.edit_flag==0) {
					// html += '<a href="javascript:void(0);" class="report-tl-post" rid="'+itemData.reply_id+'" post_url="'+itemData.url+'"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>';
					// }

					/*if (itemData.edit_flag != 0) {
						//for design
					html += '<div class="dropdown"><a href="#" class="dropbtn"><span class="fa fa-ellipsis-v more-icon"></span></a><div class="dropdown-content">';

						html += '<a href="javascript:void(0);" class="edit-tl-sponsadcomment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '"><span><i class="far fa-edit bar-edit"></i></span>Edit</a>';
						html += '<a href="javascript:void(0);" class="delete-tl-sponcomment" rid="' + itemData.reply_id + '"><span><i class="fa fa-trash"></i></span>Delete</a>';
					}*/
					html += '</div></div>'
					html += '</div>'




					html += '<div class="msg-opt"><ul class="list-inline">';

					//by sumeeth
					if (itemData.edit_flag == 0) {
						html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="' + itemData.reply_id + '" post_url="' + itemData.url + '"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>.</li>';
					}
					if (itemData.edit_flag != 0) {
						html += '<a href="javascript:void(0);" class="edit-tl-sponsadcomment" rimagecomment="' + itemData.reply_image + '"  rid="' + itemData.reply_id + '"><span><i class="far fa-edit bar-edit"></i></span>Edit</a>';
						html += '<a href="javascript:void(0);" class="delete-tl-sponcomment" rid="' + itemData.reply_id + '"><span><i class="fa fa-trash"></i></span>Delete</a>';
					}
					// if(itemData.replies.length){
					// 	html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list1 rlist_'+itemData.reply_id+'"  rid="'+itemData.reply_id+'"><span></span>Replies ('+itemData.replies.length+')</a>.</li>';
					// }


					//	html += '<li class="list-item"><a href="javascript:void(0);" class="replies_list2 rlist_'+itemData.reply_id+'" rid="'+itemData.reply_id+'"><span></span>Reply</a>.</li>'; //by sumeeth



					// if (itemData.edit_flag==0) {
					// html += '<li class="list-item"><a href="javascript:void(0);" class="report-tl-post" rid="'+itemData.reply_id+'" post_url="'+itemData.url+'"><span></span>Report</a>.</li>';
					// }

					// if (itemData.edit_flag) {
					// 	html += '<li class="list-item"><a href="javascript:void(0);"  class="edit-tl-post" rid="'+itemData.reply_id+'"><span></span>Edit</a>.</li>';
					// 	html += '<li class="list-item"><a href="javascript:void(0);"  class="delete-tl-post" rid="'+itemData.reply_id+'"><span></span>Delete</a>.</li>';
					// }

					//html += '<li class="list-item">' + itemData.reply_date + '</li>';

					html += '</ul></div></div>';

				});
				if (data.length) {
					html += '</div>';
				}
				//var html_element = '.sub_replies_'+reply_id;
				$("#repliesModal2 .result-replies").html(html);

			}


		});

	});

	$('body').on('click', '.edit-tl-sponsadcomment', function (e) {
		var reply_id = $(this).attr('rid');
		var reply_image = $(this).attr('rimagecomment');
		//alert(reply_image);
		//console.log('-->'+$(this).closest('.user-feed').find('.user-msg').html());
		$("#editsponsModal2 .comment").attr("id", "comment_" + reply_id);

		var msg = $('.rlmsgtext_' + reply_id).html();
		//alert(msg);
		//console.log('test----'+msg);
		if (msg == '' || typeof (msg) == "undefined") {
			//console.log('test');
			var msg = $(this).closest('.main-post').find('.rl-msg-text').html();

		}
		/*var msg = msg.replace(/<img[^>]*>/g, "");*/
		//alert(msg);
		$("#editsponsModal2 .comment").val(msg);
		$("#editsponsModal2 .commentInput").attr("rid", reply_id);

		$("#editsponsModal2 .submitsponsEditWrapper1").attr("rid", reply_id);

		var modal6 = document.getElementById("editsponsModal2");
		modal6.style.display = "block";
		var modal7 = document.getElementById("repliesModal1");
		modal7.style.display = "none";
		var modal8 = document.getElementById("repliesModal2");
		modal8.style.display = "none";
	});

	$('body').on('click', '.delete-tl-sponcomment', function () {
		var reply_id = $(this).attr('rid');
		$('#confirmdelmodelspons').find('#delete-tl-sponcomment').attr('rid', reply_id); // Set reply_id
		$('#confirmdelmodelspons').modal('show');
	});

	$('body').on('click', '#delete-tl-sponcomment', function (e) {

		var reply_id = $(this).attr('rid');
		if (reply_id > 0) {
			var input_data = { "reply_id": reply_id };


			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: {
					'action': 'ajaxdeletesponscomment', //calls wp_ajax_nopriv_ajaxlogin
					'reply_id': reply_id,
					'nonce': site_script_object.nonce,
				},
				success: function (data) {
					//console.log(data);
					if (data.status) {
						var sec = "#msg-" + reply_id;
						$(sec).hide();
					}
					location.reload();
				}
			});


		}
	});



	$('body').on('click', '.spons_like_timeline', function (e) {
		e.preventDefault();

		var rid = $(this).attr('rid');
		var liked = $(this).attr('liked');
		var cur_ele = $(this);

		// Prevent multiple clicks while AJAX is processing
		if (cur_ele.data('processing') === true) {
			return;
		}

		// Mark as processing
		cur_ele.data('processing', true);

		var like = (liked == '0') ? '1' : '0'; // Toggle like/unlike

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxsponslike',
				'spons_id': rid,
				'like': like,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {
				var innerContent = cur_ele.closest('.inner-content');
				var onlySponsLike = innerContent.find('.onlysponslike');
				var onlySponsCountLikes = innerContent.find('.onlysponscountlikes');
				var currentLikes = parseInt(onlySponsLike.html()) || 0;

				if (data == '1') {
					cur_ele.attr('liked', '1').parent().addClass('active');

					// Increment like count
					var newLikes = currentLikes + 1;
					onlySponsLike.html(newLikes);
					onlySponsCountLikes.html('&nbsp;' + (newLikes === 1 ? 'Like' : 'Likes'));
				} else {
					cur_ele.attr('liked', '0').parent().removeClass('active');

					// Decrement like count, ensuring it doesn't go below 0
					var newLikes = Math.max(currentLikes - 1, 0);
					onlySponsLike.html(newLikes);
					onlySponsCountLikes.html('&nbsp;' + (newLikes === 1 ? 'Like' : 'Likes'));
				}
			},
			complete: function () {
				// Reset processing flag
				cur_ele.data('processing', false);
			},
			error: function () {
				// Reset processing flag in case of an error
				cur_ele.data('processing', false);
			}
		});






		/*$.post( ajax_login_object.ajaxurl, { 'product_id': pid, 'wishlist': wish  }, function( data ) {
			$('#add_to_wishlist').text("Remove from wishlist");
			$('#add_to_wishlist').attr("wish", 0);
		}, "json");
		*/



	});



});

function readURL(input) {
	// Get the number of existing images
	var existingImagesCount = $('#addimage li').length;

	// Calculate total images (existing + newly selected files)
	var totalFilesToUpload = input.length + existingImagesCount;

	// Check if total exceeds 3
	if (totalFilesToUpload > 3) {
		alert("You can only upload up to 3 images in total.");
		return; // Stop further execution if the limit is exceeded
	}

	$('.post').prop('disabled', false);
	$('.post').removeClass('colorbttn');

	var preview = document.getElementById("addimage");
	var fileInput = document.querySelector("input[type=file]");
	var $j = existingImagesCount; // Start index after the existing images
	var filesArray = Array.from(fileInput.files); // Store all files
	var currentFileIndex = 0; // Start with the first file

	// Process the first image and crop it
	processImage(filesArray[currentFileIndex]);

	function processImage(file) {
		var fileType = file.type;
		if (fileType === 'image/png' || fileType === 'image/jpeg' || fileType === 'image/jpg' || fileType === 'image/webp') {
			var reader = new FileReader();
			reader.onload = function (event) {
				// Show the modal and display the image in Cropper
				var modal = document.getElementById("cropperModal");
				var image = document.getElementById("image");
				modal.style.display = "block";
				image.src = event.target.result;

				// Clean up previous cropper instance if exists
				if (window.cropper) {
					window.cropper.destroy(); // Destroy the previous cropper instance
				}

				// Initialize Cropper.js with 1:1 aspect ratio
				window.cropper = new Cropper(image, {
					aspectRatio: 1,
					viewMode: 1
				});

				// Handle cropping when "Crop" button is clicked
				document.getElementById("cropButton").addEventListener('click', function cropHandler() {
					var croppedCanvas = window.cropper.getCroppedCanvas({
						width: 300,
						height: 300
					});
					var croppedImage = croppedCanvas.toDataURL('image/png');

					// Add cropped image to preview
					var listItem = document.createElement("li");
					listItem.innerHTML = "<img src='" + croppedImage + "' style='height:100px;width:120px;' />";

					// Add a close button for each image
					var span = document.createElement("span");
					span.className = "createclose edicloseimage2";
					span.innerHTML = '&times;';
					span.setAttribute('data-index', $j); // Associate the close button with the image index
					listItem.append(span);

					// Append to preview
					preview.appendChild(listItem);

					// Create hidden input for image data
					$('#mulimg').append("<input type='hidden' id='img1" + $j + "' value='" + croppedImage + "' >");



					// Close the modal and destroy cropper instance
					modal.style.display = "none";
					window.cropper.destroy();

					// Remove the event listener for the crop button to prevent multiple triggers
					document.getElementById("cropButton").removeEventListener('click', cropHandler);

					// Increment image index
					$j++;

					// Move to the next image
					currentFileIndex++;
					if (currentFileIndex < filesArray.length) {
						processImage(filesArray[currentFileIndex]); // Process the next image
					}
				});
			};
			reader.readAsDataURL(file);
		} else {
			alert('Invalid image type');
		}
	}

	function resetModalState() {
		// Clear Cropper modal state
		var modal = document.getElementById("cropperModal");
		modal.style.display = "none";
		$('#image').attr('src', '');
	}

	$('#addimage').show();
	$('.post').prop('disabled', false);
	$('.post').removeClass('colorbttn');
	$('#tagproduct').prop('disabled', false); // Enable tag product button
	$('#tagproduct').removeClass('colorbttn'); // Remove disabled style
	$('.taglocpost').prop('disabled', false); // Enable tag location button
	$('.taglocpost').removeClass('colorbttn'); // Remove disabled style
}

$(document).ready(function () {
	$('body').on('click', '.edicloseimage2', function () {
		var imageIndex = $(this).attr('data-index');
		// Clean up previous cropper instance if exists
		if (window.cropper) {
			window.cropper.destroy(); // Destroy the previous cropper instance
		}
		$(this).parent('li').remove(); // Remove the image from #addimage

		// Find and remove the corresponding hidden input from #mulimg
		$('#mulimg').find('#img1' + imageIndex).remove();

		// Adjust data-index and ID for remaining images
		$('#addimage li').each(function (index) {
			var newIndex = index; // Use the current index for the new data-index
			$(this).find('.edicloseimage2').attr('data-index', newIndex); // Update data-index on the close button
		});

		// Update IDs for all hidden inputs in #mulimg
		$('#mulimg input[type="hidden"]').each(function (index) {
			$(this).attr('id', 'img1' + index); // Set new ID based on current index
		});
		// Hide #addimage if there are no images left
		if ($('#addimage li').length === 0) {
			$('#addimage').hide();
			// Clear file input to reset state
			document.querySelector("input[type=file]").value = "";
			checkPostButton();
		}
	});




	$('body').on('click', '.edicloseimage3', function () {
		var imageIndex = $(this).attr('data-index');
		// Clean up previous cropper instance if exists
		if (window.cropper) {
			window.cropper.destroy(); // Destroy the previous cropper instance
		}
		$(this).parent('li').remove();
		$('#mulimg1').find('#img1' + imageIndex).remove();

		$('.edit_img_posts li').each(function (index) {
			$(this).find('.edicloseimage3').attr('data-index', index);
			$('#mulimg1').find('#img1' + (index + 1)).attr('id', 'img1' + index);
		});

		// If all images are removed, hide the container
		if ($('.edit_img_posts li').length === 0) {
			$('.edit_img_posts').hide();

			$('#profile-pic1').val('');
		}
	});


	$("#comment_0").on("keyup", function () {
		//  alert('hi');
		var addcommenttext = $(this).val();
		$a = $('#comment_img_0').val();
		if (addcommenttext.trim().length > 0 || $a != '') {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
			$('#tagproduct').prop('disabled', false); //for tag a product
			$('#tagproduct').removeClass('colorbttn'); //for tag a product
			$('.taglocpost').prop('disabled', false); //for tag a location
			$('.taglocpost').removeClass('colorbttn'); //for tag a location


		} else if (addcommenttext.length <= 0 && $a == '') {
			//alert('hi');
			$('.post').prop('disabled', true);
			$('.post').addClass('colorbttn');
			$('#tagproduct').prop('disabled', true); //for tag a product
			$('#tagproduct').addClass('colorbttn');

			$('.taglocpost').prop('disabled', true); //for tag a location
			$('.taglocpost').addClass('colorbttn');


			$('#headerpostsearch').hide();
			$('.closeproduct1').hide();

			$('.taglocpostsearch').hide();
			$('.closeloc1').hide();



		} else {
			$('.post').prop('disabled', false);
			$('.post').removeClass('colorbttn');
			$('#tagproduct').prop('disabled', true); //for tag a product
			$('#tagproduct').addClass('colorbttn'); //for tag a product

			$('.taglocpost').prop('disabled', true); //for tag a location
			$('.taglocpost').addClass('colorbttn'); //for tag a location


			$('#headerpostsearch').hide();
			$('.closeproduct1').hide();

			$('.taglocpostsearch').hide();
			$('.closeloc1').hide();
		}
		checkPostButton();
	});
	$('body').on('click', '.closeloc1', function () {
		$(".taglocpostsearch").val('');
		$('.taglocpostsearch').hide();
		$('.closeloc1').hide();

	});
	$('body').on('click', '.closeproduct1', function () {
		$(".headerpost-result-sec").hide();
		$("#fpid").val('');
		$("#headerpostsearch").val('');
		$('.closeproduct1').hide();
		$('.headerpostsearch').hide();

	});
	$('body').on('click', '#taglocpost', function () {
		$(".taglocpostsearch").toggle();
		//$('.headerpostsearch').hide();
		$('.closeloc1').toggle();
		//$('.closeproduct1').hide();
		$('.headerpost-result-sec').hide();
	});
	$('body').on('click', '#tagproduct', function () {
		$("#headerpostsearch").toggle();
		$(".closeproduct1").toggle();
	});

	$('body').on('click', '#myBtn', function (e) {
		var modal = document.getElementById("myModal");
		modal.style.display = "block";
		$('body').css('overflow', 'hidden'); // Disables scrolling
	});

	$('body').on('click', '.close-modal', function (e) {
		var modal = document.getElementById("myModal");
		if (modal) {
			$('#addimage').html('');
			$('#mulimg').html('');
		}
		modal.style.display = "none";

		$('body').css('overflow', ''); // Resets overflow to the default value
	});

	$('body').on('click', '.openpost', function (e) {
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
		$('body').css('overflow', ''); // Resets overflow to the default value
	});
});


// Function to check if either textarea or file input has content
    function checkPostButton() {
        var text = $('#comment_0').val().trim();
        var files = $('#profile-pic')[0].files.length;
        // Enable the post button if either the textarea has content or files are selected
        if (text.length === 0 && $('#addimage li').length === 0) {
    	$('#btnPost').prop('disabled', true);
		$('#btnPost').addClass('colorbttn');
		} else {
    		$('#btnPost').prop('disabled', false);
		}
    }


jQuery(document).ready(function ($) {
	$(".opdropdown").on("change", function (event) {

		var opdropdown = $(this).val();
		var searchtxt = $('#header-search').val();
		if (searchtxt.trim() == '') {
			$(".header-result-sec").attr("style", "display: none !important");
		}
		if (opdropdown == 'All') {
			$('#header-search').attr('placeholder', 'Search SIPN');
		} else if (opdropdown == 'post') {
			$('#header-search').attr('placeholder', 'Search Posts');
		} else if (opdropdown == 'product') {
			$('#header-search').attr('placeholder', 'Search Bourbons');
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": opdropdown };
			$.ajax({


				type: 'POST',
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
				success: function (data) {
					//alert(data);
					//console.log(data);
					var result = '';
					$.each(data.hits.hits, function (index, prod) {
						//console.log(prod);
						//console.log(prod._source.product_image);
						if (prod._index == 'sipnproduct_prod') {

							if (prod._source.product_image) {
								var pimg = prod._source.product_image;
							}
							else {
								var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
							}
							result += '<li class="middle-post-search"><a class="middle-post-anchor"  href="' + prod._source.product_link + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
							if (prod._source.product_flavor) {
								result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
							}

							result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
						}
						if (prod._index == 'sipnpost_prod') {


							var pimg1 = '/wp-content/themes/SIPN/assets/images/no-image-available.png';
							var posttitle = prod._source.post_title.substring(0, 120);
							result += '<li class="middle-post-search"><a  href="' + prod._source.post_url + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img class="post-img-search" aria-hidden="" style="vertical-align:middle;width:36px !important;" src="' + pimg1 + '"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + posttitle + '</small></span><br>';
							if (prod._source.tagged_product) {
								result += '<span><small class="catg">Tagged Product: ' + prod._source.tagged_product + '</small></span>';
							}

							result += '</span></td></tr></tbody></table></a></li>';
						}



					});
					//console.log(result);
					$(".header-result-sec").html("<ul>" + result + "</ul>");
					if (data.hits.hits != '0') {
						if ($("#header-search").val().trim() != '') {
							$(".header-result-sec").attr("style", "display: block !important");
							$(".header-result-sec").find('a').keydown(function (e) {

								switch (e.which) {
									case 40:
										e.preventDefault(); // prevent moving the cursor
										$(this).parent().next().find('a').first().focus();
										break;
									case 38:
										e.preventDefault(); // prevent moving the cursor
										$(this).parent().prev().find('a').first().focus();
										break;
								}
							});
							$(".header-result-sec").find('a').on("focusin", function (e) {
								var searchbox = $(this);
								$(this).parent().addClass("selected");

							});

							$(".header-result-sec").find('a').on("focusout", function (e) {
								var searchbox = $(this);
								$(this).parent().removeClass("selected");
							});
						}

						const scrollToTop = () => {
							// get the div element by its id
							const div = document.getElementById("header-result-sec");
							// smooth scroll to the top of the div
							div.scrollTo({
								top: 0,
								behavior: 'smooth'
							});
						}
						scrollToTop();
					} else {
						$(".header-result-sec").html("<ul><center style='color:red';>No results found</center></ul>");
						$(".header-result-sec").attr("style", "display: block !important");
					}


				}
			});
		} else {
			$(".header-result-sec").html("");
			$(".header-result-sec").attr("style", "display: none !important");
		}

		$('#header-search').focus();
	});
});


jQuery(document).ready(function ($) {
	$("#opdropdown-mobile").on("change", function (event) {

		var opdropdown = $(this).val();
		var searchtxt = $('#mob_header_search').val();
		if (opdropdown == 'All') {
			$('#mob_header_search').attr('placeholder', 'Search SIPN');
		} else if (opdropdown == 'post') {
			$('#mob_header_search').attr('placeholder', 'Search Posts');
		} else if (opdropdown == 'product') {
			$('#mob_header_search').attr('placeholder', 'Search Bourbons');
		}
		if (searchtxt.trim() == '') {
			$(".result-sec1").attr("style", "display: none !important");
		}
		//$(".search-load").remove(); by sumeeth
		if (searchtxt.length > 2) {
			//done by sumeeth
			//$('<div class="search-load"><img src="/wp-content/themes/SIPN/assets/images/loader1.gif"> Searching...</div>').insertAfter(".icon-search");

			var search_data = { "searchtxt": searchtxt, "option": opdropdown };
			$.ajax({


				type: 'POST',
				dataType: 'json',
				contentType: "application/json;",
				url: '/wp-json/users/v2/ajaxsendingindexdata/',
				data: JSON.stringify(search_data),
				success: function (data) {
					//alert(data);
					//console.log(data);
					var result = '';
					$.each(data.hits.hits, function (index, prod) {
						//console.log(prod);
						//console.log(prod._source.product_image);
						if (prod._index == 'sipnproduct_prod') {

							if (prod._source.product_image) {
								var pimg = prod._source.product_image;
							}
							else {
								var pimg = '/wp-content/themes/SIPN/assets/images/default-bottle.jpg';
							}
							result += '<li class="middle-post-search"><a class="middle-post-anchor"  href="' + prod._source.product_link + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img aria-hidden="" style="vertical-align:middle;" src="' + pimg + '"></td><td class="td85"><div class="pro-title-desc-main"><div class="pro-title-main"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + prod._source.product_title + '</small></span></div>';
							if (prod._source.product_flavor) {
								result += '<div class="pro-desc-main"><span><small class="catg" style="font-size: 14px;">Flavor: ' + prod._source.product_flavor + '</small></span></div>';
							}

							result += '</div><div class="pro-price-main"><small class="red text-right" style="font-size: 15px;"><!----><span><strong>$' + prod._source.product_price.toFixed(2) + '</strong></span><!----></small></div></td></tr></tbody></table></a></li>';
						}
						if (prod._index == 'sipnpost_prod') {


							var pimg1 = '/wp-content/themes/SIPN/assets/images/no-image-available.png';
							var posttitle = prod._source.post_title.substring(0, 120);
							result += '<li class="middle-post-search"><a  href="' + prod._source.post_url + '"><table class="pdd" width="98%"><tbody><tr class="search-post-img-enhance"><td class="text-center td15"><img class="post-img-search" aria-hidden="" style="vertical-align:middle;width:36px !important;" src="' + pimg1 + '"></td><td class="td85"><span class="title"><small class="titlebrk" style="font-weight: bold;font-size: 14px;margin-bottom: 5px; width: 100%; float: left;">' + posttitle + '</small></span><br>';
							if (prod._source.tagged_product) {
								result += '<span><small class="catg">Tagged Product: ' + prod._source.tagged_product + '</small></span>';
							}

							result += '</span></td></tr></tbody></table></a></li>';
						}



					});
					//console.log(result);
					$(".result-sec1").html("<ul>" + result + "</ul>");
					if (data.hits.hits != '0') {
						if ($("#mob_header_search").val().trim() != '') {
							$(".result-sec1").attr("style", "display: block !important");
							$(".result-sec1").find('a').keydown(function (e) {

								switch (e.which) {
									case 40:
										e.preventDefault(); // prevent moving the cursor
										$(this).parent().next().find('a').first().focus();
										break;
									case 38:
										e.preventDefault(); // prevent moving the cursor
										$(this).parent().prev().find('a').first().focus();
										break;
								}
							});
							$(".result-sec1").find('a').on("focusin", function (e) {
								var searchbox = $(this);
								$(this).parent().addClass("selected");

							});

							$(".result-sec1").find('a').on("focusout", function (e) {
								var searchbox = $(this);
								$(this).parent().removeClass("selected");
							});
						}

						const scrollToTop = () => {
							// get the div element by its id
							const div = document.getElementById("result-sec1");
							// smooth scroll to the top of the div
							div.scrollTo({
								top: 0,
								behavior: 'smooth'
							});
						}
						scrollToTop();
					} else {
						$(".result-sec1").html("<ul><center style='color:red';>No results found</center></ul>");
						$(".result-sec1").attr("style", "display: block !important");
					}


				}
			});
		} else {
			$(".result-sec1").html("");
			$(".result-sec1").attr("style", "display: none !important");
		}
		$('#mob_header_search').focus();
	});
});

$(document).ready(function () {
	$('body').on('click', '#resendemail', function () {
		$v = $(this).attr("data-id");
		//alert($v);
		var search_data = { "email": $v };
		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json;",
			url: '/wp-json/users/v2/resendverificationemail/',
			data: JSON.stringify(search_data),
			success: function (data) {
				console.log(data.message);
				if (data.message == 'Verification Email sent successfully.') {

					alert('Email sent successfully');

				}
				else {
					location.reload();
				}


			}
		});
	});
});

$(document).ready(function () {
	var id = $('.recordprodevent').attr("data-pupc");
		var epage = $('.recordprodevent').attr("data-epage");
		if(epage == "product_detail"){
		var search_data = { "upc": id, "device_type": "web", "action": "view" };
		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json;",
			url: '/wp-json/tracking/v2/events/',
			data: JSON.stringify(search_data),
			success: function (data) {

			}
		});
	}

	$('body').on('click', '.recordaddclick', function () {
		$id = $(this).attr("data-id");
		$actiontype = $(this).attr("data-actiontype");
		$from = $(this).attr("data-from");
		//alert($v);
		var search_data = { "id": $id, "from": $from, "actiontype": $actiontype };
		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json;",
			url: '/wp-json/users/v2/recordsponsoredaddclick/',
			data: JSON.stringify(search_data),
			success: function (data) {

				//location.reload();

			}
		});
	});

	$('body').on('click', '.recordprodevent', function () {
		var id = $(this).attr("data-pupc");
		var actiontype = $(this).attr("data-actiontype");
		var url = $(this).attr("data-prurl");
		var rtype = $(this).attr("data-rtype");
		var epage = $(this).attr("data-epage");
		//alert($v);
		var search_data = { "upc": id, "device_type": "web", "action": actiontype };
		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json;",
			url: '/wp-json/tracking/v2/events/',
			data: JSON.stringify(search_data),
			success: function (data) {
       if(epage == "product_detail" && actiontype == "fav" || actiontype == "fav_removed"){
         location.reload();
       }
       if(url !== "0" && url !== "undefined"){
		if (rtype == 1 && actiontype == "buy_now") {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    }

			}
		});
	});
});

var span = document.getElementsByClassName("close")[0];
if (span) span.onclick = function () {
	modal.style.display = "none";
}
//mycode raghu

$(document).ready(function () {
	$('body').on('click', '.like_profiles', function () {
		var prid = $(this).attr("pid");
		var likes = parseInt($(this).attr("liked"));
		//$(this).attr("liked",lcount);


		if ($(this).hasClass('nologinaction')) {
			window.location.href = '/login'
		}
		//  
		var sdata = { "like": 0, "profile_id": prid };

		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json;",
			url: '/wp-json/user/v2/likeprofile/',
			data: JSON.stringify(sdata),
			success: function (data) {
				if (!$('.like').hasClass('inactive')) {
					//alert('1');
					$('.like').addClass("inactive");
				} else {
					//alert('2');
					$('.like').removeClass("inactive");
				}
				$('.profile_likes_count').text(data.likes);

			}
		});
	});

	$('body').on('click', '.addtowishlist', function () {
		var pid = $(this).attr('data-pid');
		var uid = $(this).attr('data-uid');
		if ($(this).hasClass('removewhishlist')) {
			$(this).html('Add to wish list');
			var type = $(this).removeClass('removewhishlist');
			var wtype = "removew";
		} else {

			var type = $(this).addClass('removewhishlist');
			$(this).html('Remove from whish list');
			var wtype = "addw";
		}

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: site_script_object.ajaxurl,
			data: {
				'action': 'ajaxwhishlist', //calls wp_ajax_nopriv_ajaxlogin
				'pid': pid,
				'uid': uid,
				'type': wtype,
				'nonce': site_script_object.nonce,
			},
			success: function (data) {


			}

		});

	});
});

