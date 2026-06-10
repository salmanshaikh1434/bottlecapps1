<?php

/**
 * Single User Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>


		<?php //bbp_get_template_part( 'user', 'details' ); ?>

		
			<?php //if ( bbp_is_favorites()               ) bbp_get_template_part( 'user', 'favorites'       ); ?>
			<?php //if ( bbp_is_subscriptions()           ) bbp_get_template_part( 'user', 'subscriptions'   ); ?>
			<?php //if ( bbp_is_single_user_engagements() ) bbp_get_template_part( 'user', 'engagements'     ); ?>
			<?php //if ( bbp_is_single_user_topics()      ) bbp_get_template_part( 'user', 'topics-created'  ); ?>
			<?php //if ( bbp_is_single_user_replies()     ) bbp_get_template_part( 'user', 'replies-created' ); ?>
			<?php //if ( bbp_is_single_user_edit()        ) bbp_get_template_part( 'form', 'user-edit'       ); ?>
			<?php if ( bbp_is_single_user_profile()     ) bbp_get_template_part( 'user', 'profile'         ); ?>
	
	

	<?php //do_action( 'bbp_template_after_user_wrapper' ); ?>

