<?php

/**
 * The template for displaying listing content in the shortcode template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/content-taxonomy.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $wp;

// template part $args
extract($args);

do_action('adqs_before_main_content');
$isSlick = ($pagination_type === 'carousel') ? true : false;

$active_type = $_GET['directory_type'] ?? '';

$all_listing_type = adqs_get_directory_types();
$directory_type_id = !empty($active_type) ? get_term_by('slug', $active_type, 'adqs_listing_types')->term_id : 0;
$active_type_terms = array();
foreach ($get_category_terms as $single_term) :
	$directory_belongs = !empty(get_term_meta($single_term->term_id, 'listing_types', true)) ? get_term_meta($single_term->term_id, 'listing_types', true) : [];
	$directory_belongs = !empty($active_type) ? in_array($directory_type_id, $directory_belongs) : true;
	if ($directory_belongs) :
		$term_obj             = get_term($single_term->term_id, $tax_name);
		$inner_array          = array();
		$inner_array['id']    = $single_term->term_id;
		$inner_array['name']  = $single_term->name;
		$inner_array['slug']  = $single_term->slug;

		$inner_array['image'] = get_term_meta($single_term->term_id, 'adqs_location_image_id', true);

		$inner_array['count'] = $term_obj->count;
		array_push($active_type_terms, $inner_array);
	endif;
endforeach; ?>

<div class="qsd-select-category adqs-location-area">
	<div class="adqs-admin-container">
		<?php if (!empty($top_bar_show) && $all_listing_type && count($all_listing_type) > 1) : ?>
			<ul class="qsd-catagory-list-btn">
				<li><a href="<?php echo esc_url(adqs_get_base_page_url(home_url($wp->request))); ?>" class="<?php echo empty($active_type) ? esc_attr('active') : '' ?>"><?php echo esc_html__('All', 'adirectory'); ?></a></li>
				<?php
				$item_count = 1;
				foreach ($all_listing_type as $listing_type) :
					if ($item_count == apply_filters('adqs_loc_dirlist_dropdown', 4)) {
						echo '<li class="has-next-all"><span>+</span><ul>';
					}
				?>
					<li><a href="<?php echo esc_url(add_query_arg('directory_type', $listing_type->slug,  adqs_get_base_page_url(home_url($wp->request)))); ?>" class="<?php echo ($listing_type->slug === $active_type) ? esc_attr('active') : '' ?>"><?php echo esc_html($listing_type->name); ?></a></li>
				<?php
					// Close the inner <ul> tag at the end of the loop if it was opened
					if ($item_count == count($all_listing_type)) {
						echo '</ul></li>';
					}
					$item_count++;
				endforeach; ?>
			</ul>
		<?php endif;

		?>
		<div class="<?php echo $isSlick ? 'qsd-has-slick' : ''; ?> qsd-select-category-grid">
			<div class="<?php echo $isSlick ? 'qsd-slick-wrapper' : ''; ?> qsd-select-category-grid-item" <?php if ($isSlick) : ?> data-settings='<?php echo esc_attr($carousel_settings); ?>' ; <?php endif; ?>>
				<?php
				foreach ($active_type_terms as $active_type_term) :
				?>
					<div class="qsd-tax-grid-single">

						<div class="qsd-select-category-grid-thumb">
							<img src="<?php echo esc_url(wp_get_attachment_image_url($active_type_term['image'] ?? 0, apply_filters('adqs_locations_image_size', 'post-thumbnails'))); ?>" alt="#" />
							<div class="qsd-select-category-grid-thumb-over">
								<a href="<?php echo esc_url(get_category_link($active_type_term['id'] ?? 0)); ?>">
									<div class="qsd-select-category-grid-thumb-over-txt">
										<p><?php echo esc_html($active_type_term['count'] ?? ''); ?> <?php echo esc_html__('Listing', 'adirectory'); ?></p>
										<h2><?php echo esc_html($active_type_term['name'] ?? ''); ?></h2>
										<span class="qsd-select-category-grid-thumb-over-icon">
											<svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1.35553 1.3335L5.88886 6.62238L1.35553 11.9113" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
											</svg>
										</span>
									</div>
								</a>
							</div>
						</div>

					</div>
				<?php
				endforeach;

				if (empty($active_type_terms)) {
					adqs_get_template_part('content', 'none');
				}

				?>

			</div>
		</div>
		<?php if ($isSlick) : ?>
			<div class="adqs-buttons">
				<button class="adqs-global-slick-button-prev adqs-slick-prev-<?php echo esc_attr($uniqId); ?>" type="button">
					<span>
						<svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.25 9L1.5 5.25M1.5 5.25L5.25 1.5M1.5 5.25L11.5 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</button>
				<div class="adqs-global-slick-pagination adqs-slick-pagination-<?php echo esc_attr($uniqId); ?>"></div>
				<button class="adqs-global-slick-button-next adqs-slick-next-<?php echo esc_attr($uniqId); ?>" type="button">
					<span>
						<svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.75 9L11.5 5.25M11.5 5.25L7.75 1.5M11.5 5.25L1.5 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>

					</span>
				</button>
			</div>
		<?php endif; ?>

		<?php if (!empty($pagination_args)) : ?>
			<div class='adqs_pagination'>
				<?php echo wp_kses_post(paginate_links($pagination_args) ?? ''); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
do_action('adqs_after_main_content');
