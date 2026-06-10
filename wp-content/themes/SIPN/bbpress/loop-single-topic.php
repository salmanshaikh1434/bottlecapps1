<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<?php if($post->ID != '35832'){ ?>
<div class="col-md-4">
  <div class="chat-block new-forums-div">
	<div class="chat-img">
		<?php
		$featured =  get_post_meta($post->ID, 'thumb_image', true);
		if($featured)
		$f_image = wp_get_attachment_image_url($featured, 'full');
		else
		$f_image = get_stylesheet_directory_uri()."/assets/images/chat/img-chat1.png";
		
		?>
		<img alt="<?php bbp_topic_title(); ?>" src="<?php echo $f_image;?>"> 
	  
	 </div>
	<div class="chat-content">
	  <!--<h3><i class="fa fa-plus-circle" aria-hidden="true"></i>
		<?php /*if(is_user_logged_in()){ bbp_topic_subscription_link( array( 'before' => '', 'subscribe' => 'Join', 'unsubscribe' => 'Leave' ) );}else{*/ ?><span><span><a href="/login?redirect_to=topics">Join</a> </span></span><?php //} ?>
	  </h3>-->
	  
	  <?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>
		<h2><a class="bbp-topic-permalink" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a></h2>
		<p class="strong"><?php echo get_post_meta($post->ID, 'short_description_top', true);?></p>
		<hr class="half-rule"></hr>
		<p><?php echo get_post_meta($post->ID, 'short_description_bottom', true);?></p>

	</div>
  </div>
</div>
<?php } ?>
					
<?php bbp_topic_pagination(); ?>
<!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
