<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

                <div class="container">
                    <div class="col-md-5">
                        <div class="img-chatdetail">
						<?php
						$featured =  get_post_meta($post->ID, 'featured_image', true);
						if($featured)
						$f_image = wp_get_attachment_image_url($featured, 'full');
						else
						$f_image = get_stylesheet_directory_uri()."/assets/images/chat/img-chatdetail.png";
						
						?>
                            <img alt="<?php bbp_topic_title(); ?>" src="<?php echo $f_image;?>"> 
                      </div>
                      <h1><?php bbp_topic_title(); ?></h1>
                      <hr class="half-rulehalf"></hr>
                      <p class="strong"><?php echo get_post_meta($post->ID, 'short_description_top', true);?> </p>
					    <div class="forum-social">
					   <?php echo sipn_social_share(); ?>
					</div>
                    </div>
                    <div class="col-md-7">
                    	<!-- added by sumeeth -->
				<div class="menu3 another_menu">
				<?php
				$topic_posts = get_posts( array(
					'post_type' => 'topic',
					'post_status' => 'publish'
					) );
				//print_r($post->ID);exit;
					//echo"<pre>";print_r($topic_posts);exit;
					?>
                    <ul>
					<?php foreach($topic_posts as $topic_post){ ?>
					<?php if($topic_post->ID != '35832' && $topic_post->ID != $post->ID ){ ?>
                        <li><a href="<?php echo get_permalink($topic_post->ID);?>"><?php echo $topic_post->post_title;?> </a></li>
                    	<?php } ?> 
			<?php } ?> 
                    </ul>
                </div>
                <!-- added by sumeeth -->
                        <div class="chat-detail">
							
							<p><?php echo the_content();?></p>
                            <?php if ( bbp_has_replies() ) : ?>
							<?php 
							$topic = get_post($post->ID);
							$all_topics = array();

						
							if($topic->post_type == 'topic' && $topic->post_status == 'publish'){
								
								$all_topics['topic_id'] = $topic->ID;
								$all_topics['topic_title'] = $topic->post_title;
								
								$thumb_image_id = get_post_meta($topic->ID, 'thumb_image', true);
								$thumb_featured_image_id = get_post_meta($topic->ID, 'featured_image', true);
								
								$featured_img = wp_get_attachment_image_src( $thumb_featured_image_id, 'full' );
								$thumb_img = wp_get_attachment_image_src( $thumb_image_id, 'full' );
								
								$all_topics['topic_featured_image'] = $featured_img[0];
								$all_topics['topic_thumb_image'] = $thumb_img[0];
								$all_topics['topic_desc'] = str_replace(']]>', '', str_replace('<![CDATA[', '', $topic->post_content));
								$all_topics['topic_short_desc_top'] = get_post_meta($topic->ID, 'short_description_top', true);
								$all_topics['topic_short_desc_bottom'] = get_post_meta($topic->ID, 'short_description_bottom', true);
								
								
								$args = [
								  'post_type' => 'reply',
								  'post_status' => 'publish',
								  'order' => 'ASC', //by sumeeth for showing latest comments
								  'post_parent' => $topic->ID,
								  'numberposts' => -1
								];

								$replies = get_posts($args);
								$all_topics['replies'] = array();
								//print_r($replies);
								foreach($replies as $reply){
									$parent_info = get_post_meta($reply->ID, '_bbp_reply_to');
									//print_r($parent_info);echo "<br><hr><br>";
									if(!$parent_info){
										$author_id = $reply->post_author;
										$author_details = get_user_by('id', $author_id);
										$author_meta = get_user_meta($author_id);	
										$avatar = wp_get_attachment_image_url($author_meta['wp_user_avatar'][0], 'thumbnail');
										
										$query = $wpdb->prepare("SELECT count(*) as cnt FROM `wp_reply_likes` WHERE reply_id = '%d'", $reply->ID);
										$cnt_list = $wpdb->get_results($query);
										$likes_count = $cnt_list[0]->cnt;
										
										$replies = get_replies($reply->ID);
										//$replies = bbp_get_reply_ancestors($reply->ID)
										//print_r($replies);
										//print_r($author_details);
										array_push($all_topics['replies'], array('reply_id'=>$reply->ID, 'reply'=>str_replace(']]>', '', str_replace('<![CDATA[', '', $reply->post_content)), 'author'=>$author_details->data->display_name, 'author_id'=>$author_id, 'author_city'=>$author_meta['city'][0], 'author_state'=>$author_meta['state'][0], 'avatar'=>$avatar, 'likes'=>$likes_count, 'is_liked'=>get_like_flag($reply->ID), 'replies'=>$replies));
									}
								}
							}
							//print_r($all_topics);
							?>
								<!-- for edit modal report -->
						<div id="reportModal" class="modal">
							<div class="modal-content">
                                            <header>
                                                <h2>Report</h2>
                                          <span class="close">×</span>
                                        </header>
                                        <div class="report">
                                            <p><strong>Why are you reporting this post?</strong></p>
                                            <p>Your report is confidential, this will keep the SIPN community cleaner for all the users. </p>
                                            <ul>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate Speech<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Prohibited Content">Prohibited Content<span><i class="fa fa-chevron-right"></i></span></a></li>

                                            </ul>
                                           
                                        </div>
                            </div>
                        </div> 
							<?php $cur_user = wp_get_current_user();
										$pid=$cur_user->data->ID;
										$pemail=$cur_user->data->user_email; ?>
                            		<!-- for edit modal report -->
<input type="hidden" id="forum_url" value="<?php echo $all_topics['forum_url']; ?>" >
<input type="hidden" id="topic" value="<?php echo $all_topics['topic_title']; ?>" >
<input type="hidden" id="author" value="<?php echo $pemail; ?>" >
							<div class="chat-main">
							<?php foreach($all_topics['replies'] as $reply){ ?>
							<div class="blog-review">
								<div id="post-<?php echo $reply['reply_id']; ?>" class='bbp-reply-header'>
									<div class="bbp-meta">
										<span class="bbp-admin-links">
										<?php
										if ( is_user_logged_in() ) {


											 if($cur_user->data->validate_email=='1'){ ?>

											 	<?php if ( current_user_can( 'edit_reply', $reply['reply_id'] ) ) { ?>
											 	<a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span></span>Edit</a>
											 	<?php 	echo " | ";
													} ?>

											 	<a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span></span>Reply</a>
                                   


                                   <?php  }else{ 

                                    	if ( current_user_can( 'edit_reply', $reply['reply_id'] ) ) {
	echo bbp_get_reply_edit_link(array('id' => $reply['reply_id']));
	echo " | ";
}

	echo bbp_get_reply_to_link(array('id' => $reply['reply_id'], 'reply_text' => 'Reply' )); 
                                    }  ?>





	<?php if ($pid != $reply['author_id']) { ?>


		<?php if($cur_user->data->validate_email=='1'){ ?>

                                 

                                   
                                <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#openpopup"> | Report</a>	


                                  <?php  }else{ ?>
                                    <a href="javascript:void(0);" class="report-tl-forum"  reply="<?php echo $reply['reply'];?>"> | Report</a>	
                                   <?php } ?>





					 <?php }
										} ?>
										
										</span>
									</div>
								</div>
								
								<div <?php bbp_reply_class(); ?>>
									<a href="<?php echo bbp_get_user_profile_url($reply['author_id']);?>"><?php if($reply['avatar']){ ?>
									<img class="profile_avatar_img" src="<?php echo $reply['avatar']; ?>">
									<?php } else{ ?>
									<!-- <img class="profile_avatar_img" src="/wp-content/uploads/2021/09/img-profile1.jpg"> -->
									<i class="fas fa-user-circle"></i>
									<?php } ?></a>
									 <p><strong><?php echo $reply['author']; ?></strong><br>
									 <span><?php echo $reply['city']; ?></span> </p>
									 <div class='reply_content_<?php echo $reply['reply_id']; ?>'><p><?php echo $reply['reply']; ?></p></div>
									 <div class="comments">
										 <span class="icon-fav" liked="<?php echo $reply['is_liked'];?>" rid="<?php echo $reply['reply_id'];?>">
										 <?php if($reply['is_liked']){ ?>
										 <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/chat/icon-fav.png">
										 <?php } else{ ?>
										 <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/chat/icon-fav-before.png">
										 <?php } ?>
										 <span class="round-circle"><?php echo $reply["likes"];?></span>
										 </span>
										 <?php
										 /*if ( current_user_can( 'edit_reply', $reply['reply_id'] ) ) {
										 ?>
										 <span class="icon-comments" rid="<?php echo $reply['reply_id'];?>"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/chat/icon-comment.png">
											<!--<span class="round-circle"><?php //echo count($reply["replies"]);?></span>-->
										</span>
										 <?php }*/ ?>
									 </div>
									 
									<?php
									//print_r($reply);
									if(count($reply['replies'])>0){
										echo '<div class="col-md-12"><a type="button" class="more-chat" data-toggle="collapse" data-target="#reply-'.$reply['reply_id'].'" href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a>';
										echo '<div class="collapse nested-replies-sec" id="reply-'.$reply['reply_id'].'"><ul>';
										echo display_nested_replies($reply['replies'], $reply['reply_id'], '', true);
										echo '</ul></div>';
										echo '</div>';
									}
									?>
								</div>
								
								
                            </div>
							<?php } ?>
							</div>
							
							
							<?php /*bbp_get_template_part( 'pagination', 'replies' ); ?>

							<?php bbp_get_template_part( 'loop',       'replies' ); ?>

							<?php bbp_get_template_part( 'pagination', 'replies' );*/ ?>

							<?php endif; ?>
							<?php if ( is_user_logged_in() && $cur_user->data->validate_email=='0' ) { ?>
							<div class="add-comment">
							<?php bbp_get_template_part( 'form', 'reply' ); ?>
							</div>
							<?php }else{ ?>
								<div class="bbp-template-notice">
								<ul>
								<li>You must be logged in and verify email to reply to this topic. <!--<a href="/login?redirect_to=<?php //echo get_permalink();?>">click here</a> to login--></li>
								</ul>
								</div>
							<?php } ?>
							
                        </div>
              
				
 <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>            
<script>
	$(document).ready(function(){

	$('body').on('click', '.report-tl-forum', function (e) {
		
		var modal = document.getElementById("reportModal");
		modal.style.display = "block";
		
		var reply = $(this).attr('reply');
		//var reply = $(this).attr('author');
		//var post_url = $(this).attr('post_url');
		$("#reportModal").attr("reply", reply);
		//$("#reportModal").attr("post_url", post_url);
	});

	$('body').on('click', '.close', function (e) {
		
		var modal = document.getElementById("reportModal");
		modal.style.display = "none";
		
	});
	
	$('body').on('click', '.report_post', function (e) {

		var reply = $("#reportModal").attr('reply');
		var forum = $("#forum_url").val();
		var topic = $("#topic").val();
		var author = $("#author").val();
		var reason = $(this).attr('rep');
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: site_script_object.ajaxurl,
				data: { 
					'action': 'ajaxreportforum', //calls wp_ajax_nopriv_ajaxlogin
					'reply': reply,
					'forum': forum,
					'topic': topic,
					'reason': reason,
					'author': author,
					'nonce': site_script_object.nonce,
					},
				success: function(data){
					//alert(data.message);
					//console.log(data.status);
					//if(data.status){
					//var sec = "#msg-"+reply_id;
					//$(sec).hide();
					//}
					var modal = document.getElementById("reportModal");
					modal.style.display = "none";
					if(data.message=='Forum is reported successfully.'){
						alert('Thanks For Reporting');
					}
				}
			});
	});

				

	});
</script>				
