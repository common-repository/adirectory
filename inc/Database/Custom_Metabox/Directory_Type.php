<?php

namespace ADQS_Directory\Database\Custom_Metabox;

use ADQS_Directory\Database\Base\Custom_Metabox;

// Traits
use ADQS_Directory\Database\Traits\Metabox_Fields\Preset_Fields;
use ADQS_Directory\Database\Traits\Metabox_Fields\Custom_Fields;
use ADQS_Directory\Database\Traits\Common\Render_Data;


/**
 * Metabox Class
 */
class Directory_Type extends Custom_Metabox
{


	use Preset_Fields, Custom_Fields, Render_Data;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		parent::__construct();

		add_action('wp_ajax_adqs_change_directory_type', array($this, 'directory_type_change'));
		add_action('admin_enqueue_scripts', array($this, 'assetsloading'));
	}




	/**
	 * Method register_custom_metabox
	 *
	 * @return void
	 */
	public function register_custom_metabox()
	{
		add_meta_box('adqs_directory_type_selection', esc_html__('Directory Type Selection', 'adirectory'), array($this, 'metabox_view'), 'adqs_directory', 'advanced', 'high');
		add_meta_box('adqs_images_slider_sidebar', esc_html__('Slider Images', 'adirectory'), array($this, 'slider_metabox_view'), 'adqs_directory', 'side');
		add_action('post_submitbox_misc_actions', [$this, 'add_misc_actions']);
	}



	/**
	 * Method add_misc_actions
	 *
	 * @param $post $post 
	 *
	 * @return void
	 */
	public function add_misc_actions($post)
	{
		if (($post->post_type ?? '') !== 'adqs_directory') {
			return;
		}

		wp_nonce_field(basename(__FILE__), 'adqs_misc_actions_metabox');

		$isFeatured = AD()->Helper->meta_val($post->ID, '_is_featured', '');



?>
		<div class="misc-pub-section misc-pub-adpqs-featured-listing">
			<div id="adqs-fl-wrap">
				<label>
					<input value="yes" type="checkbox" name="_is_featured" id="_is_featured" <?php checked($isFeatured, 'yes'); ?>>
					<span><strong><?php echo esc_html__('Featured', 'adirectory'); ?></strong> :
						<?php echo esc_html__(' This Listing Item', 'adirectory'); ?></span>
				</label>
			</div>
		</div>
		<?php
		$value = AD()->Helper->meta_val($post->ID, '_expiry_date', '');

		$expireDates = !empty(adqs_get_setting_option('listing_expiry_date')) ? adqs_get_setting_option('listing_expiry_date') : 70;
		$expireDates = wp_date('n_d_Y_h_i', strtotime("+{$expireDates} days"));

		if (!empty($value)) {
			$expireDates = $value;
		}
		list($mm, $jj, $aa, $hh, $mn) = explode('_', $expireDates);
		?>
		<div class="misc-pub-section misc-pub-adpqs-expiration-time">
			<span id="adqs-timestamp">
				<strong><?php echo esc_html__('Expiration', 'adirectory'); ?></strong> :
				<?php echo esc_html__('Date & Time', 'adirectory'); ?>
			</span>
			<div class="adqs-never-expire" style="margin-top: 10px;">
				<?php
				$expiryNever = AD()->Helper->meta_val($post->ID, '_expiry_never', '');
				?>
				<label>
					<input value="yes" type="checkbox" name="_expiry_never" id="_expiry_never" <?php checked($expiryNever, 'yes'); ?>>
					<span><?php echo esc_html__('Never Expire', 'adirectory'); ?></span>
				</label>
			</div>
			<div id="adqs-timestamp-wrap" class="<?php echo esc_attr($expiryNever === 'yes' ? 'hidden' : ''); ?>">
				<label>

					<select id="adqs_mm" name="_expiry_date[mm]">
						<option value="1" <?php selected($mm ?? '', '1'); ?>><?php echo esc_html__('Jan', 'adirectory'); ?>
						</option>
						<option value="2" <?php selected($mm ?? '', '2'); ?>><?php echo esc_html__('Feb', 'adirectory'); ?>
						</option>
						<option value="3" <?php selected($mm ?? '', '3'); ?>><?php echo esc_html__('Mar', 'adirectory'); ?>
						</option>
						<option value="4" <?php selected($mm ?? '', '4'); ?>><?php echo esc_html__('Apr', 'adirectory'); ?>
						</option>
						<option value="5" <?php selected($mm ?? '', '5'); ?>><?php echo esc_html__('May', 'adirectory'); ?>
						</option>
						<option value="6" <?php selected($mm ?? '', '6'); ?>><?php echo esc_html__('Jun', 'adirectory'); ?>
						</option>
						<option value="7" <?php selected($mm ?? '', '7'); ?>><?php echo esc_html__('Jul', 'adirectory'); ?>
						</option>
						<option value="8" <?php selected($mm ?? '', '8'); ?>><?php echo esc_html__('Aug', 'adirectory'); ?>
						</option>
						<option value="9" <?php selected($mm ?? '', '9'); ?>><?php echo esc_html__('Sep', 'adirectory'); ?>
						</option>
						<option value="10" <?php selected($mm ?? '', '10'); ?>><?php echo esc_html__('Oct', 'adirectory'); ?>
						</option>
						<option value="11" <?php selected($mm ?? '', '11'); ?>><?php echo esc_html__('Nov', 'adirectory'); ?>
						</option>
						<option value="12" <?php selected($mm ?? '', '12'); ?>><?php echo esc_html__('Dec', 'adirectory'); ?>
						</option>
					</select>
				</label>
				<label>
					<input type="text" id="adqs_jj" placeholder="day" name="_expiry_date[jj]" value="<?php echo esc_attr($jj ?? ''); ?>" size="2" maxlength="2">
				</label>
				<label>
					<input type="text" id="adqs_aa" placeholder="year" name="_expiry_date[aa]" value="<?php echo esc_attr($aa ?? ''); ?>" size="4" maxlength="4">
				</label>@ <label>
					<input type="text" id="adqs_hh" placeholder="hour" name="_expiry_date[hh]" value="<?php echo esc_attr($hh ?? ''); ?>" size="2" maxlength="2">
				</label> : <label>
					<input type="text" id="adqs_mn" placeholder="min" name="_expiry_date[mn]" value="<?php echo esc_attr($mn ?? ''); ?>" size="2" maxlength="2">
				</label>
			</div>


		</div>

	<?php

	}




	/**
	 * Method slider_metabox_view
	 *
	 * @param $post $post 
	 *
	 * @return void
	 */
	public function slider_metabox_view($post)
	{
		wp_nonce_field(basename(__FILE__), 'adqs_slider_metabox');
		$name  = '_images';
		$value = AD()->Helper->meta_val($post->ID, $name, []);
	?>
		<div class="qsd-form-group qsd-slider-images-fields qsd-slider-metabox">

			<div class="qsd-form-wrap qsd-slider-images" data-name="<?php echo esc_attr($name); ?>">
				<div class="qsd-add-sliders"><i class="dashicons dashicons-images-alt2"></i> </div>
				<!-- <div class="qsd-slider-image-preview">
		</div> -->
				<?php
				if (!empty($value) && is_array($value)) :
					foreach ($value as $image_id) :
						$attachment_image = wp_get_attachment_url($image_id);
				?>
						<div class="qsd-slider-image-preview" style="background-image:url(<?php echo esc_url($attachment_image); ?>)">
							<input type="hidden" name="_images[]" value="<?php echo absint($image_id); ?>"><i class="dashicons dashicons-remove"></i>
						</div>
				<?php
					endforeach;
				endif;
				?>
				<div class="qsd-remove-sliders">
					<i class="dashicons dashicons-trash"></i>
				</div>
			</div>
		</div>
	<?php
	}


	/**
	 * Method metabox_view
	 *
	 * @param $post $post 
	 *
	 * @return void
	 */
	public function metabox_view($post)
	{
		$post_id         = !empty($post->ID) ? absint($post->ID) : 0;
		$directory_types = adqs_get_directory_types();

		wp_nonce_field('directory_type', 'adqs_select_directory');

		$getDirTypeID = get_post_meta($post_id, 'adqs_directory_type', true);
		$getDirTypeID = !empty($getDirTypeID) ? absint($getDirTypeID) : 0;

	?>
		<style>
			#adqs_categorychecklist,
			#adqs_categorychecklist-pop,
			#adqs_locationchecklist,
			#adqs_locationchecklist-pop {
				display: none;
			}
		</style>

		<div class="adqs_select_directory_type_wrap">
			<label>
				<?php esc_html_e('Directory', 'adirectory'); ?>
			</label>
			<select name="adqs_directory_type" id="adqs_directory_type" data-post-id="<?php echo !empty($post_id) ? esc_attr($post_id) : ''; ?>" required>
				<?php
				if (!empty($directory_types)) :

					foreach ($directory_types as $type) :

				?>
						<option value="<?php echo esc_attr($type->term_id); ?>" <?php !empty($getDirTypeID) ? selected($getDirTypeID, $type->term_id) : selected('General', trim($type->name)); ?>>
							<?php echo esc_html($type->name); ?>
						</option>
				<?php
					endforeach;
				endif;
				?>
			</select>
		</div>

		<!-- Dynamic Fields Area Start \--->
		<div id="adqs_daynamicFields_area">
			<?php $this->render_dynamic_fields($post_id, $getDirTypeID); ?>

		</div>


		<?php
	}


	/**
	 * Method render_front_dynamic_field
	 *
	 * @param $post_id $post_id 
	 * @param $directory_type_id $directory_type_id 
	 *
	 * @return void
	 */
	public function render_front_dynamic_field($post_id = 0, $directory_type_id = 0)
	{
		$meta_fields = adqs_get_listing_fields($directory_type_id);
		if (!empty($meta_fields) && is_array($meta_fields)) :
		?>
			<?php
			if (!empty($meta_fields) && is_array($meta_fields)) :
				foreach ($meta_fields as $key => $section) :
					$sectionTitle     = isset($section['sectiontitle']) ? $section['sectiontitle'] : esc_html__('Section', 'adirectory');
					$sectionId        = isset($section['id']) ? $section['id'] : '';
			?>
					<div class="qsd-add-step-wrapper">
						<!-- Meta fields title -->
						<div class="qsd-meta-fields-title">
							<h3>
								<?php echo esc_html($sectionTitle); ?>
							</h3>

						</div>
						<?php
						if (isset($section['fields']) && !empty($section['fields'])) :
						?>
							<!-- Form Section Start /-->
							<div class="qsd-form-seaction">
								<?php
								foreach ($section['fields'] as $data) :
									// all preset fields
									$this->get_all_preset_fields($post_id, $data);

									// all custom fields
									$this->get_all_custom_fields($post_id, $data);

								endforeach;
								?>
							</div><!-- Form Section end \-->
						<?php
						endif;
						?>
					</div>
			<?php


				endforeach;
			endif;
		endif; // end
	}


	/**
	 * Method render_dynamic_fields
	 *
	 * @param $post_id $post_id 
	 * @param $directory_type_id $directory_type_id 
	 *
	 * @return void
	 */
	public function render_dynamic_fields($post_id = 0, $directory_type_id = 0)
	{

		$meta_fields = adqs_get_listing_fields($directory_type_id);
		if (!empty($meta_fields) && is_array($meta_fields)) :
			?>
			<section class="adTabs-wrapper">

				<div class="adTabs-block">
					<div id="adTabs-section" class="adTabs">
						<ul class="adTab-head">
							<?php
							$sectionNavs = wp_list_pluck($meta_fields, 'sectiontitle', 'id');
							if (!empty($sectionNavs) && is_array($sectionNavs)) :
								$nav_index = 1;
								foreach ($sectionNavs as $id => $secTitle) :
									$navActiveClass = $nav_index === 1 ? 'active' : '';
							?>
									<li>
										<a href="#<?php echo esc_attr($id); ?>" class="adTab-link <?php echo esc_attr($navActiveClass); ?>">
											<?php echo esc_html($secTitle); ?>
										</a>
									</li>
							<?php
									++$nav_index;
								endforeach;
							endif;
							?>
						</ul>


						<?php
						if (!empty($meta_fields) && is_array($meta_fields)) :
							foreach ($meta_fields as $key => $section) :
								$sectionTitle     = isset($section['sectiontitle']) ? $section['sectiontitle'] : esc_html__('Section', 'adirectory');
								$sectionId        = isset($section['id']) ? $section['id'] : '';
								$panelActiveClass = $key === 0 ? 'active qsd-active-content' : '';
						?>
								<div id="<?php echo esc_attr($sectionId); ?>" class="adTab-body qsd-entry-content <?php echo esc_attr($panelActiveClass); ?>">
									<!-- Meta fields title -->
									<div class="qsd-meta-fields-title qsd-mscHeading-wrap">
										<h3>
											<?php echo esc_html($sectionTitle); ?>
										</h3>
										<div class="qsd-form-seaction">
											<div class="qsd-form-check-control">
												<input type="checkbox" id="adqs_mtsh_hide_<?php echo esc_attr($sectionId); ?>" name="adqs_mtsh_hide_<?php echo esc_attr($sectionId); ?>" value="yes" <?php checked(get_post_meta($post_id, "adqs_mtsh_hide_{$sectionId}", true), 'yes'); ?>>
												<label for="adqs_mtsh_hide_<?php echo esc_attr($sectionId); ?>"><?php echo esc_html__('Hide heading in slingle listing page', 'adirectory'); ?></label>
											</div>
										</div>
									</div>
									<?php
									if (isset($section['fields']) && !empty($section['fields'])) :
									?>
										<!-- Form Section Start /-->
										<div class="qsd-form-seaction">
											<?php
											foreach ($section['fields'] as $data) :
												// all preset fields
												$this->get_all_preset_fields($post_id, $data);

												// all custom fields
												$this->get_all_custom_fields($post_id, $data);
											endforeach;
											?>
										</div><!-- Form Section end \-->
									<?php
									endif;
									?>
								</div>
						<?php
							endforeach;
						endif;
						?>



					</div>
				</div>
			</section>
		<?php
		endif; // end

	}


	/**
	 * Method save_misc_actions
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_misc_actions($post_id = 0)
	{
		if (!wp_verify_nonce($_POST['adqs_misc_actions_metabox'] ?? '', basename(__FILE__)) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
			return;
		}


		$isFeatured = AD()->Helper->post_data($_POST, '_is_featured', '');
		update_post_meta($post_id, '_is_featured', sanitize_text_field($isFeatured));



		$getData = AD()->Helper->post_data($_POST, '_expiry_date', []);
		if (!empty($getData)) {
			update_post_meta($post_id, '_expiry_date', join('_', map_deep(array_values($getData), 'sanitize_text_field')));
		}

		$getExpData = AD()->Helper->post_data($_POST, '_expiry_never', '');
		update_post_meta($post_id, '_expiry_never', sanitize_text_field($getExpData));
	}



	/**
	 * Method save_slider_metabox
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_slider_metabox($post_id = 0)
	{
		if (!isset($_POST['_images']) || !wp_verify_nonce($_POST['adqs_slider_metabox'] ?? '', basename(__FILE__)) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		$getData = AD()->Helper->post_data($_POST, '_images', array());
		$getData = map_deep($getData, 'absint');
		update_post_meta($post_id, '_images', $getData);
	}

	/**
	 * Method section_title_display
	 *
	 * @param $post_id $post_id 
	 * @param $directory_type_id $directory_type_id 
	 *
	 * @return void
	 */
	public function section_title_display($post_id = 0, $directory_type_id = 0)
	{
		$meta_fields = adqs_get_listing_fields($directory_type_id);
		if (!empty($meta_fields) && is_array($meta_fields)) {
		?>
			<?php
			if (!empty($meta_fields) && is_array($meta_fields)) {
				foreach ($meta_fields as $section) {
					$sectionId = isset($section['id']) ? $section['id'] : '';
					update_post_meta($post_id, "adqs_mtsh_hide_{$sectionId}", sanitize_text_field($_POST["adqs_mtsh_hide_{$sectionId}"] ?? ''));
				}
			}
		}
	}


	/**
	 * Method save_directory_type
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_directory_type($post_id = 0)
	{
		if (!isset($_POST['adqs_select_directory']) || !wp_verify_nonce($_POST['adqs_select_directory'] ?? '', 'directory_type') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}



		$dirTypeId = isset($_POST['adqs_directory_type']) ? absint($_POST['adqs_directory_type']) : 0;
		update_post_meta($post_id, 'adqs_directory_type', $dirTypeId);

		// update directory type in category and location

		$this->update_term_meta_directory_type($dirTypeId, 'adqs_category');
		$this->update_term_meta_directory_type($dirTypeId, 'adqs_location');

		// delete all cache
		wp_cache_delete('adqs_maxPrice', 'adqs_cache');
		wp_cache_delete('adqs_all_agents', 'adqs_cache');
		wp_cache_delete('adqs_all_agents_total', 'adqs_cache');



		// save is section title
		$this->section_title_display($post_id, $dirTypeId);

		// save preset fields data
		$this->save_all_preset_fields($post_id);

		// save preset fields data
		$this->save_all_custom_fields($post_id);
	}


	/**
	 * Method save_metabox_data
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_metabox_data($post_id)
	{
		$this->save_directory_type($post_id);
		$this->save_misc_actions($post_id);
		$this->save_slider_metabox($post_id);
	}



	/**
	 * Method directory_type_change
	 *
	 * @return array
	 */
	public function directory_type_change()
	{
		check_ajax_referer('metabox-security', 'security');

		$post_id        = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
		$directory_type = isset($_POST['directory_type']) ? $_POST['directory_type'] : 0;
		$defaultOptions = array(
			'has_input'     => true,
			'has_directory' => true,
			'directory_id'  => $directory_type,
			'has_children'  => true,
		);
		$Helper         = AD()->Helper;
		// adqs_category
		ob_start();
		$this->render_tax_lists(
			array('taxonomy' => 'adqs_category'),
			array_merge($defaultOptions, array('saved_data' => $Helper->get_pluck_terms($post_id, 'adqs_category', 'term_id')))
		);

		$adqs_category = ob_get_clean();

		ob_start();
		$this->render_pop_tax_inputs($post_id, $directory_type, 'adqs_category');
		$pop_adqs_category = ob_get_clean();

		// adqs_location
		ob_start();
		$this->render_tax_lists(
			array('taxonomy' => 'adqs_location'),
			array_merge($defaultOptions, array('saved_data' => $Helper->get_pluck_terms($post_id, 'adqs_location', 'term_id')))
		);

		$adqs_location = ob_get_clean();

		ob_start();
		$this->render_pop_tax_inputs($post_id, $directory_type, 'adqs_location');
		$pop_adqs_location = ob_get_clean();

		// adqs_location
		ob_start();
		$this->render_dynamic_fields($post_id, $directory_type);
		$adqs_dynamic_fields = ob_get_clean();

		wp_send_json_success(
			array(
				'val'                => $directory_type,
				'adqs_category'       => $adqs_category,
				'pop_adqs_category'   => $pop_adqs_category,
				'adqs_location'       => $adqs_location,
				'pop_adqs_location'   => $pop_adqs_location,
				'adqs_dynamic_fields' => $adqs_dynamic_fields,
			)
		);

		wp_die();
	}



	/**
	 * update term meta directory type
	 *
	 * @param integer $dirt_id
	 * @param string $tax_name
	 * @return void
	 */
	public function update_term_meta_directory_type($dirt_id = 0, $tax_name = '')
	{

		if (!isset($_POST['adqs_select_directory']) || !wp_verify_nonce($_POST['adqs_select_directory'] ?? '', 'directory_type')) {
			return;
		}
		if (!empty($dirt_id) && isset($_POST['tax_input'][$tax_name]) && !empty(array_filter($_POST['tax_input'][$tax_name]))) {
			foreach (array_filter($_POST['tax_input'][$tax_name]) as $term_id) {
				$listing_types =  !empty(get_term_meta(absint($term_id), 'listing_types', true)) ? get_term_meta(absint($term_id), 'listing_types', true) : [];
				if (!empty($listing_types) && in_array($dirt_id, $listing_types)) {
					continue;
				}
				update_term_meta(absint($term_id), 'listing_types', [$dirt_id]);
			}
		}
	}



	/**
	 * Method render_pop_tax_inputs
	 *
	 * @param $post_id $post_id 
	 * @param $directory_id $directory_id 
	 * @param $taxonomy $taxonomy 
	 *
	 * @return void
	 */
	public function render_pop_tax_inputs($post_id, $directory_id, $taxonomy)
	{
		check_ajax_referer('metabox-security', 'security');
		$pop_default_terms = isset($_POST['pop_' . $taxonomy]) ? array_map('intval', $_POST['pop_' . $taxonomy]) : array();

		$post_terms = get_the_terms($post_id, $taxonomy);
		$post_terms = wp_list_pluck($post_terms, 'term_id');
		$terms      = get_terms(
			array(
				'taxonomy'     => $taxonomy,
				'include'      => $pop_default_terms,
				'orderby'      => 'count',
				'order'        => 'DESC',
				'number'       => 10,
				'hide_empty'   => false,
				'hierarchical' => false,
			)
		);

		if ($terms) {
			foreach ($terms as $term) {
				$directory_types = get_term_meta($term->term_id, 'listing_types', true);
				$directory_types = !empty($directory_types) ? array_map('intval', $directory_types) : array();
				$checked         = in_array($term->term_id, $post_terms) ? 'checked' : '';
				if (in_array($directory_id, $directory_types)) :

			?>
					<li id="popular-<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>" class="popular-category">
						<label class="selectit"><input value="<?php echo esc_attr($term->term_id); ?>" type="checkbox" id="in-popular-<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>" <?php echo !empty($checked) ? esc_attr($checked) : ''; ?>>
							<?php echo esc_html($term->name); ?>
						</label>
					</li>

<?php
				endif;
			}
		}
	}


	/**
	 * Method assetsloading
	 *
	 * @return void
	 */
	public function assetsloading()
	{
		// current page id
		$currentPageId = !empty(get_current_screen()->id) ? get_current_screen()->id : '';

		$metaboxPages = array('adqs_directory');
		if (in_array($currentPageId, $metaboxPages)) {

			wp_enqueue_style('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/leaflet.css', array(), ADQS_DIRECTORY_VERSION);

			wp_enqueue_style('qsd-admin-metabox', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/metabox.css', array(), ADQS_DIRECTORY_VERSION);
			// leaflet map
			wp_enqueue_script('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/leaflet.js', array(), ADQS_DIRECTORY_VERSION, true);

			wp_enqueue_script('jquery.ba-throttle-debounce', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/jquery.ba-throttle-debounce.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);

			wp_enqueue_script('qsd-admin-metabox', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/metabox.js', array('jquery', 'wp-i18n'), ADQS_DIRECTORY_VERSION, true);

			wp_enqueue_script('qsd-jquery-repeat', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/jquery.repeater.min.js', array('jquery', 'wp-i18n'), ADQS_DIRECTORY_VERSION, true);

			wp_localize_script(
				'qsd-admin-metabox',
				'qsAdminMetaBox',
				array(
					'security' => wp_create_nonce('metabox-security'),
				)
			);


			// conditionel render field
			wp_enqueue_script('qsd-display-if', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/display-if.min.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);
		}
	}
} // end