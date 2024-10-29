<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 *  Filter Listings
 */
$view_type = isset($_GET['view_type']) ? $_GET['view_type'] : '';
if ($view_type === 'map') {
	return '';
}


global $wp;

$adqs_cat_terms = adqs_get_terms('adqs_category');
$adqs_location_terms = adqs_get_terms('adqs_location');
$adqs_tags = adqs_get_terms('adqs_tags', array('hierarchical' => false));
$current_url = $wp->request;

// all request data
$search = isset($_GET['ls']) ? $_GET['ls'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$directory_type = isset($_GET['directory_type']) ? $_GET['directory_type'] : '';
$tags = (isset($_GET['tags']) && !empty($_GET['tags'])) ? explode(",", $_GET['tags']) : [];
$minprice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
$maxprice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : '';

?>
<section class="qsd-prodcut">
	<form action="<?php echo esc_url(home_url($wp->request)); ?>" method="get">
		<?php
		if (isset($_GET['directory_type'])) : ?>
			<input type="hidden" name="directory_type" value="<?php echo esc_attr($_GET['directory_type']); ?>">
		<?php endif;
		?>
		<div class="adqs-admin-container">
			<div class="qsd-prodcut-main-box qsd-advancedTop_filter">
				<h3 class="qsd-prodcut-grid-with-side-bar-titel">
					<?php echo esc_html__('Search', 'adirectory'); ?>
				</h3>
				<div class="qsd-prodcut-main">
					<div class="qsd-prodcut-main-left">
						<div class="qsd-form-main">
							<div class="qsd-form-item adqs-ajax-search">
								<input type="text" class="qsd-form-input" name="ls" placeholder="<?php echo esc_attr__('Type your Keyword...', 'adirectory'); ?>" value="<?php echo esc_attr($search); ?>" />
								<?php do_action('adqs_ajax_search'); ?>
							</div>
							<?php if (!empty($adqs_cat_terms)): ?>
								<!-- Category Filter -->
								<div class="qsd-form-item">
									<select class="qsd-form-select" name="category">
										<option value=""><?php echo esc_html__('Select Category', 'adirectory'); ?></option>
										<?php
										foreach ($adqs_cat_terms as $adqs_cat_term) : ?>
											<option value="<?php echo esc_attr($adqs_cat_term->slug) ?>" <?php selected($category, $adqs_cat_term->slug); ?>>
												<?php echo esc_html($adqs_cat_term->name); ?>
											</option>
										<?php endforeach;
										?>
									</select>
								</div>
							<?php endif; ?>

							<?php if (!empty($adqs_location_terms)): ?>
								<!-- Location Filter -->
								<div class="qsd-form-item">
									<select class="qsd-form-select" name="location">
										<option value=""><?php echo esc_html__('Select Location', 'adirectory'); ?></option>
										<?php
										foreach ($adqs_location_terms as $adqs_loc_term) : ?>
											<option value="<?php echo esc_attr($adqs_loc_term->slug) ?>" <?php selected($location, $adqs_loc_term->slug); ?>>
												<?php echo esc_html($adqs_loc_term->name); ?>
											</option>
										<?php endforeach;
										?>
									</select>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="qsd-prodcut-main-right">

						<a href="<?php echo esc_url(home_url($wp->request)); ?>" class="qsd-prodcut-filter-btn" id="adqs_advtf_btn">
							<img class="img-svg" src="<?php echo esc_url(ADQS_DIRECTORY_ASSETS_URL . '/frontend/img/filter.svg'); ?>" alt="#">

							<span id="adqs_advtf_text"><?php echo esc_html__('Filter', 'adirectory'); ?></span>
						</a>

						<button type="submit" class="qsd-main-btn">

							<img class="img-svg" src="<?php echo esc_url(ADQS_DIRECTORY_ASSETS_URL . '/frontend/img/Search.svg'); ?>" alt="#">

							<?php echo esc_html__('Find Listing', 'adirectory'); ?>
						</button>

					</div>

				</div>
			</div>

			<?php
			$isActiveMoreHidden = true;
			if (!empty($minprice) || !empty($maxprice) || !empty($maxprice) || !empty($rating) || !empty(array_filter($tags))) {
				$isActiveMoreHidden = false;
			}

			?>
			<div class="qsd-prodcut-grid-with-side-bar-item  <?php echo esc_attr($isActiveMoreHidden ? 'hidden' : ''); ?>" id="adqs_advtFilter_more">
				<div class="qsd-prodcut-grid-with-side-bar">
					<!-- Pricing  -->
					<div class="qsd-prodcut-grid-with-side-bar-pricing">
						<h3 class="qsd-prodcut-grid-with-side-bar-titel">
							<?php echo esc_html__('Pricing Range', 'adirectory'); ?>
						</h3>
						<div class="qsd-prodcut-grid-with-side-bar-pricing-item">
							<div class="qsd-pricing-filter-wrap">
								<div class="qsd-form-item">
									<input class="qsd-form-input" type="number" placeholder="<?php echo esc_html__('Min', 'adirectory'); ?>" name="minPrice" value="<?php echo esc_attr($minprice); ?>" id="min-price-field" min="0">
								</div>
								<div class="qsd-form-item">
									<input class="qsd-form-input" type="number" name="maxPrice" value="<?php echo esc_attr($maxprice); ?>" placeholder="<?php echo esc_html__('Max', 'adirectory'); ?>" id="max-price-field" min="1">
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
								<input type="radio" name="rating" class="reviews-inner-check" value="5" id="rating_5" <?php checked($rating, 5); ?> />
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
								<input type="radio" name="rating" class="reviews-inner-check" value="4" id="rating_4" <?php checked($rating, 4); ?> />
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
								<input type="radio" name="rating" class="reviews-inner-check" value="3" id="rating_3" <?php checked($rating, 3); ?> />
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
								<input type="radio" name="rating" class="reviews-inner-check" value="2" id="rating_2" <?php checked($rating, 2); ?> />
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
								<input type="radio" name="rating" class="reviews-inner-check" value="1" id="rating_1" <?php checked($rating, 1); ?> />
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
					<?php if (!empty($adqs_tags)): ?>
						<div class="qsd-prodcut-grid-with-side-bar-Tags mt-36px">
							<h3 class="qsd-prodcut-grid-with-side-bar-titel">
								<?php esc_html_e("Tags", "adirectory"); ?>
							</h3>
							<div class="qsd-prodcut-grid-with-side-bar-reviews-item">

								<?php
								$key = 0;
								$display_number = 5;
								foreach ($adqs_tags as $key => $adqs_tag) :
								?>
									<div class="qsd-prodcut-grid-reviews-inner qsd-tags-wrapper <?php echo ($key + 1) > $display_number ? 'tags-hidden' : ''; ?>">
										<input type="checkbox" value="<?php echo esc_attr($adqs_tag->term_id); ?>" class="tags-inner-check" id="tags_<?php echo esc_attr($adqs_tag->term_id); ?>" <?php echo in_array($adqs_tag->term_id, $tags) ? 'checked' : ''; ?> />
										<label for="tags_<?php echo esc_attr($adqs_tag->term_id); ?>" class="reviews-inner-label-txt">
											<?php echo esc_html($adqs_tag->name); ?>
										</label>
									</div>
								<?php endforeach;
								if (!empty($key) && ($key > $display_number)):
								?>
									<div class="tag-btn seemore-tag">
										<?php esc_html_e("See More", "adirectory") ?>
										<span>
											<i class="fa-solid fa-angle-right"></i>
										</span>
									</div>
								<?php endif; ?>
							</div>
							<input type="hidden" value="<?php echo esc_attr(join(',', array_filter($tags))); ?>" name="tags" id="tags_field" />
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</section>