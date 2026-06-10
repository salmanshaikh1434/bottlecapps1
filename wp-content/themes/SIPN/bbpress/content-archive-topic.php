<?php

/**
 * Archive Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>

<div class="">
                <h1 class="heading-main-events">FORUMS</h1>
                <!--<div class="search">
                    <input class="form-control input-md" type="text" value="Search" placeholder="Search">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </div>-->
                <div class="menu3">
				<?php
				$topic_posts = get_posts( array(
					'post_type' => 'topic',
					'post_status' => 'publish'
					) );
					//print_r($topic_posts);
					?>
                    <ul>
					<?php foreach($topic_posts as $topic_post){ ?>
					<?php if($topic_post->ID != '35832'){ ?>
                        <li><a href="<?php echo get_permalink($topic_post->ID);?>"><?php echo $topic_post->post_title;?> </a></li>
                    	<?php } ?> 
			<?php } ?> 
                    </ul>
                </div>
                <div class="chat-container">
					<div class="row">
					<?php //do_action( 'bbp_template_before_topics_index' ); ?>

					<?php if ( bbp_has_topics() ) : ?>

						<?php //bbp_get_template_part( 'pagination', 'topics'    ); ?>

						<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

						<?php //bbp_get_template_part( 'pagination', 'topics'    ); ?>

					<?php else : ?>

						<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

					<?php endif; ?>

					<?php //do_action( 'bbp_template_after_topics_index' ); ?>
	
					
                    
                    </div>
                  
                </div>


            </div>
			
		 <?php //sipn_footer();?>	
	