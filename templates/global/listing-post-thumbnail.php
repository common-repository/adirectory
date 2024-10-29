<?php

/**
 * The template for displaying listing price in the globaly template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/meta.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$author_id = get_the_author_meta('ID');
$author_posts_url = adqs_listing_author_url($author_id, get_post_type());


$default_preview_image = apply_filters('adqs_listing_default_img', AD()->Helper->get_setting('default_preview_image'));

?>

<div class="qsd-prodcut-grid-list-thumb">




	<?php if (!empty(has_post_thumbnail())) :


	?>
		<div class="qsd-prodcut-grid-list-thumb">
			<div class="qsd-thubm-top-bar">
				<div class="qsd-top-left-badges-group">
					<?php do_action('adqs_grid_badges_btn_group', get_the_ID()); ?>
				</div>

				<div class="qsd-top-right-btn-group">
					<?php do_action('adqs_grid_thumnail_btn_group', get_the_ID()); ?>
				</div>
			</div>

			<a href="<?php the_permalink(); ?>">
				<?php echo get_the_post_thumbnail(); ?>
			</a>
		</div>
	<?php elseif (!empty($default_preview_image)) :

	?>
		<div class="qsd-thubm-top-bar">
			<div class="qsd-top-left-badges-group">
				<?php do_action('adqs_grid_badges_btn_group', get_the_ID()); ?>
			</div>

			<div class="qsd-top-right-btn-group">
				<?php do_action('adqs_grid_thumnail_btn_group', get_the_ID()); ?>
			</div>
		</div>
		<a href="<?php the_permalink(); ?>">
			<img src="<?php echo esc_url($default_preview_image); ?>" alt="<?php the_title(); ?>">
		</a>

	<?php endif; ?>
	<!-- author	-->
	<div class="grid-list-inner-btm">
		<a href="<?php echo esc_url($author_posts_url); ?>" class="grid-list-inner-btm-btn">
			<span class="adqs-listing-auth">
				<span>
					<?php echo get_avatar($author_id, 80); ?>
				</span>
				<?php do_action('adqs_after_author', $author_id); ?>
			</span>
			<?php the_author(); ?>
		</a>
	</div>
</div>