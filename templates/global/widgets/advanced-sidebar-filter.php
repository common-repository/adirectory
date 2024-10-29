<?php

/**
 * The template for displaying the archived Listing Sidebar
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/widgets/advanced-sidebar-filter.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// template part $args
extract($args);

$adqs_cat_terms = adqs_get_terms('adqs_category');
$adqs_location_terms = adqs_get_terms('adqs_location');
$adqs_tags = adqs_get_terms('adqs_tags', array('hierarchical' => false));

$sidebar_id = $w_args['id'] ?? '';
if (($sidebar_id !== 'adqs_advanced_sidebar_filter') && !(isset($w_instance['listing_url']) && !empty($w_instance['listing_url']))) {
	return '';
}


?>

<div class="qsd-prodcut-grid-with-side-bar">
	<?php
	if (isset($w_instance['title']) && !empty($w_instance['title'])) {
		echo wp_kses_post($w_args['before_title'] . apply_filters('widget_title', $w_instance['title']) . $w_args['after_title']);
	}
	?>
	<form class="qsd-form-main" action="<?php echo esc_url($w_instance['listing_url']); ?>" method="get">

		<!-- Serach Filter -->
		<div class="qsd-form-item adqs-ajax-search">
			<input type="text" class="qsd-form-input" name="ls" placeholder="<?php echo esc_attr__('Type your Keyword...', 'adirectory'); ?>" />
			<?php do_action('adqs_ajax_search'); ?>
		</div>

		<!-- Category Filter -->
		<?php if (!empty($adqs_cat_terms)) : ?>

			<div class="qsd-form-item">
				<select class="qsd-form-select" name="category">
					<option value=""><?php echo esc_html__('Select Category', 'adirectory'); ?></option>
					<?php
					foreach ($adqs_cat_terms as $adqs_cat_term) : ?>
						<option value="<?php echo esc_attr($adqs_cat_term->slug) ?>">
							<?php echo esc_html($adqs_cat_term->name); ?>
						</option>
					<?php endforeach;
					?>
				</select>
			</div>
		<?php endif; ?>

		<?php if (!empty($adqs_location_terms)) : ?>
			<!-- Location Filter -->
			<div class="qsd-form-item">
				<select class="qsd-form-select" name="location">
					<option value=""><?php echo esc_html__('Select Location', 'adirectory'); ?></option>
					<?php
					foreach ($adqs_location_terms as $adqs_loc_term) : ?>
						<option value="<?php echo esc_attr($adqs_loc_term->slug) ?>">
							<?php echo esc_html($adqs_loc_term->name); ?>
						</option>
					<?php endforeach;
					?>
				</select>
			</div>
		<?php endif; ?>

		<div class="qsd-prodcut-grid-with-side-bar-pricing mt-36px">
			<h3 class="qsd-prodcut-grid-with-side-bar-titel">
				<?php echo esc_html__('Pricing Range', 'adirectory'); ?>
			</h3>
			<div class="qsd-prodcut-grid-with-side-bar-pricing-item">
				<div class="qsd-pricing-filter-wrap">
					<div class="qsd-form-item">
						<input class="qsd-form-input" type="number" placeholder="<?php echo esc_html__('Min', 'adirectory'); ?>" name="minPrice" value="" id="min-price-field" min="0">
					</div>
					<div class="qsd-form-item">
						<input class="qsd-form-input" type="number" name="maxPrice" value="" placeholder="<?php echo esc_html__('Max', 'adirectory'); ?>" id="max-price-field" min="1">
					</div>
				</div>
			</div>
		</div>

		<!-- Reviews  -->
		<div class="qsd-prodcut-grid-with-side-bar-reviews ">
			<h3 class="qsd-prodcut-grid-with-side-bar-titel">
				<?php esc_html_e('Rating Up / Equel', 'adirectory'); ?>
			</h3>
			<div class="qsd-prodcut-grid-with-side-bar-reviews-item">

				<div class="qsd-prodcut-grid-reviews-inner">
					<input type="radio" name="rating" class="reviews-inner-check" value="5" id="rating_5" />
					<label for="rating_5" class="reviews-inner-label">
						<span class="qsd-five-star">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</span>
					</label>
				</div>
				<div class="qsd-prodcut-grid-reviews-inner">
					<input type="radio" name="rating" class="reviews-inner-check" value="4" id="rating_4" />
					<label for="rating_4" class="reviews-inner-label">
						<span class="qsd-four-star">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</span>
					</label>
				</div>

				<div class="qsd-prodcut-grid-reviews-inner">
					<input type="radio" name="rating" class="reviews-inner-check" value="3" id="rating_3" />
					<label for="rating_3" class="reviews-inner-label">
						<span class="qsd-three-star">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</span>
					</label>
				</div>

				<div class="qsd-prodcut-grid-reviews-inner">
					<input type="radio" name="rating" class="reviews-inner-check" value="2" id="rating_2" />
					<label for="rating_2" class="reviews-inner-label">
						<span class="qsd-two-star">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</span>
					</label>
				</div>

				<div class="qsd-prodcut-grid-reviews-inner">
					<input type="radio" name="rating" class="reviews-inner-check" value="1" id="rating_1" />
					<label for="rating_1" class="reviews-inner-label">
						<span class="qsd-one-star">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</span>
					</label>
				</div>



			</div>
		</div>

		<!-- Tags  -->
		<?php if (!empty($adqs_tags)) : ?>
			<div class="qsd-prodcut-grid-with-side-bar-Tags mt-36px">
				<h3 class="qsd-prodcut-grid-with-side-bar-titel">
					<?php esc_html_e("Tags", "adirectory"); ?>
				</h3>
				<div class="qsd-prodcut-grid-with-side-bar-reviews-item">

					<?php
					$key = 0;
					$display_number = 3;
					foreach ($adqs_tags as $key => $adqs_tag) :
					?>
						<div class="qsd-prodcut-grid-reviews-inner adqs_tags-wrapper <?php echo ($key + 1) > $display_number ? 'tags-hidden' : ''; ?>">
							<input type="checkbox" value="<?php echo esc_attr($adqs_tag->term_id); ?>" class="tags-inner-check" id="tags_<?php echo esc_attr($adqs_tag->term_id); ?>" />
							<label for="tags_<?php echo esc_attr($adqs_tag->term_id); ?>" class="reviews-inner-label-txt">
								<?php echo esc_html($adqs_tag->name); ?>
							</label>
						</div>
					<?php endforeach;
					if (!empty($key) && ($key > $display_number)) :
					?>
						<div class="tag-btn seemore-tag">
							<?php esc_html_e("See More", "adirectory") ?>
							<span>
								<i class="fa-solid fa-angle-right"></i>
							</span>
						</div>
					<?php endif; ?>
				</div>
				<input type="hidden" value="" name="tags" id="tags_field" />
			</div>
		<?php endif; ?>

		<!-- Submit Button  -->
		<div class="qsd-prodcut-grid-btn">
			<button class="reset-btn" type="reset">
				<?php echo esc_html_e('Reset Search', 'adirectory') ?>
			</button>
			<button type="submit" class="qsd-main-btn">
				<span>
					<i class="fa-solid fa-magnifying-glass"></i>
				</span>
				<?php echo esc_html_e('Find Listing', 'adirectory') ?>
			</button>
		</div>

		<?php
		if (isset($_GET['view_type'])) : ?>
			<input type="hidden" name="view_type" value="<?php echo esc_attr($_GET['view_type']); ?>">
		<?php endif;
		?>
		<?php
		if (isset($_GET['directory_type'])) : ?>
			<input type="hidden" name="directory_type" value="<?php echo esc_attr($_GET['directory_type']); ?>">
		<?php endif;
		?>
	</form>
</div>