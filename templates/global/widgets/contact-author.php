<?php

/**
 * The template for displaying the listing contact author is shown in the Single Listing Sidebar
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/widgets/contact-author.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// template part $args
extract($args);

$sidebar_id = $w_args['id'] ?? '';
if ($sidebar_id !== 'adqs_single_listing') {
	return '';
}

$post_id = get_the_ID();
$author_id = get_the_author_meta('ID');
$post_type = get_post_type();
$author_posts_url = adqs_listing_author_url($author_id, $post_type);
$only_for_login_user   = $w_instance['only_for_login_user'] ?? '';

?>
<div class="connect-agents" id="adqs_connectAgents">
	<?php
	if (isset($w_instance['title']) && !empty($w_instance['title'])) {
		echo wp_kses_post($w_args['before_title'] . apply_filters('widget_title', $w_instance['title']) . $w_args['after_title']);
	}
	?>

	<div class="connect-agents-inner">
		<div class="adqs-agent-avater">
			<a class="connect-agents-inner-thumb" href="<?php echo esc_url($author_posts_url); ?>">
				<?php echo get_avatar($author_id, 80); ?>
			</a>
			<?php do_action('adqs_after_author', $author_id); ?>
		</div>

		<a class="connect-agents-inner-txt" href="<?php echo esc_url($author_posts_url); ?>">

			<h4 class="connect-agents-txt"><?php the_author(); ?></h4>
			<p class="connect-agents-sub"><?php the_author_meta('adqs_address_info', $author_id); ?></p>

		</a>
	</div>

	<form>
		<div class="connect-agents-form">
			<input type="text" class="connect-agents-input" placeholder="<?php echo esc_attr__('Name', 'adirectory'); ?>" name="adqs_ca_name" required>
		</div>
		<div class="connect-agents-form">
			<input type="email" class="connect-agents-input" placeholder="<?php echo esc_attr__('Email', 'adirectory'); ?>" name="adqs_ca_email" required>
		</div>
		<div class="connect-agents-form">
			<input type="tel" class="connect-agents-input" placeholder="<?php echo esc_attr__('Phone', 'adirectory'); ?>" name="adqs_ca_phone" required>
		</div>
		<div class="connect-agents-form">
			<textarea class="connect-agents-input" name="adqs_ca_msg" rows="5"
				placeholder="<?php echo esc_attr__('Type message...', 'adirectory'); ?>" required></textarea>
		</div>
		<input type="hidden" name="adqs_listing_id" value="<?php echo esc_attr($post_id); ?>">
		<input type="hidden" name="adqs_co" value="<?php echo esc_attr($author_id); ?>">
		<input type="hidden" name="only_login_user" value="<?php echo esc_attr($only_for_login_user); ?>">
		<button class="connect-agents-btn"><?php echo esc_html__('Submit Message', 'adirectory'); ?></button>
	</form>
</div>