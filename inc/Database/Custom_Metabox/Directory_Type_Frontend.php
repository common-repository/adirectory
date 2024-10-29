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

class Directory_Type_Frontend extends Custom_Metabox
{

	use Preset_Fields;
	use Custom_Fields;
	use Render_Data;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		parent::__construct();

		add_action('admin_enqueue_scripts', array($this, 'assetsloading'));

		add_action('wp_ajax_adqs_add_feature_image', array($this, 'adqs_add_feature_image'));
		add_action('wp_ajax_adqs_add_slider_images', array($this, 'adqs_add_slider_images'));

		add_shortcode('adqs_add_listing', array($this, 'front_add_listing'));
	}



	/**
	 * Method front_add_listing
	 *
	 * @return string
	 */
	public function front_add_listing($atts)
	{

		$atts = shortcode_atts(array(
			'dir_type_media' => 'icon'
		), $atts, 'adqs_add_listing');


		if (!current_user_can('read')) {
			ob_start();
			$loginPage = adqs_get_permalink_by_key('adqs_user_dashboard');
			if (!empty($loginPage)) :
?>
				<script>
					if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
						window.location.href = '<?php echo esc_url($loginPage); ?>';
					}
				</script>
				<?php
			endif;
			return ob_get_clean();
		}

		wp_enqueue_style('qs-frontdash-css');

		$directory_type = isset($_GET['adqs_listing_type']) ? absint($_GET['adqs_listing_type']) : 0;
		$post_id = isset($_GET['postid']) ? absint($_GET['postid']) : 0;

		// $pricing_plans = adp_wc_order_by_directory_id(48, get_current_user_id(), "completed");

		// echo "<pre>";
		// var_dump($pricing_plans);
		// echo "</pre>";
		// exit;

		if ($post_id) {
			$directory_type = get_post_meta($post_id, 'adqs_directory_type', true);
			ob_start();
			adqs_get_template_part('listing-add/listing', 'add', compact('directory_type', 'post_id'));
			return ob_get_clean();
		}

		if (!isset($_GET['adqs_listing_type'])) {
			$directory_types = adqs_get_directory_types();
			ob_start();
			if (!empty($directory_types)) {
				global $wp;
				foreach ($directory_types as $dir_type) :
					$dir_name = $dir_type->name;
					$dir_type_icon = get_term_meta($dir_type->term_id, 'adqs_term_icon', true);
					$dir_type_img = get_term_meta($dir_type->term_id, 'adqs_term_img', true);

				?>
					<a href="<?php echo add_query_arg(array("adqs_listing_type" => $dir_type->term_id), home_url($wp->request)); ?>"
						class="qsd-front-single-dir-item" data-term-id="<?php echo esc_attr($dir_type->term_id); ?>">
						<?php
						if (!empty($dir_type_img)) { ?>
							<img class="adqs-dir-img" src="<?php echo esc_url($dir_type_img); ?>" alt="" srcset="">
						<?php } else { ?>
							<i class="<?php echo esc_attr($dir_type_icon); ?>"></i>
						<?php }
						?>
						<h4 class="adqs-add-listing-dir-titile"><?php echo  esc_html__($dir_name, 'adirectory') ?></h4>
					</a>
		<?php endforeach;
			}

			return ob_get_clean();
		}

		ob_start();

		adqs_get_template_part('listing-add/listing', 'add', compact('directory_type', 'post_id'));

		return ob_get_clean();
	}


	/**
	 * Method adqs_add_slider_images
	 * 
	 * @return array
	 */
	public function adqs_add_slider_images()
	{
		if (!check_ajax_referer('__qs_front_dash_list_nonce', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		// sleep(5 * 60);
		if (isset($_FILES['sliderimgaes'])) {
			$uploaded_files = $_FILES['sliderimgaes'];
			$upload_overrides = array('test_form' => false);
			$uploaded_images = [];

			// Loop through each file and handle the upload
			for ($i = 0; $i < count($uploaded_files['name']); $i++) {
				// Create an array representing a single file
				$file = array(
					'name'     => $uploaded_files['name'][$i],
					'type'     => $uploaded_files['type'][$i],
					'tmp_name' => $uploaded_files['tmp_name'][$i],
					'error'    => $uploaded_files['error'][$i],
					'size'     => $uploaded_files['size'][$i]
				);

				$movefile = wp_handle_upload($file, $upload_overrides);

				if ($movefile && !isset($movefile['error'])) {
					$filename = $movefile['file'];
					$attachment = array(
						'post_mime_type' => $movefile['type'],
						'post_title'     => sanitize_file_name($file['name']),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);

					$attach_id = wp_insert_attachment($attachment, $filename);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
					wp_update_attachment_metadata($attach_id, $attach_data);

					$attachment_url = wp_get_attachment_url($attach_id);

					$uploaded_images[] = array(
						'attachment_id' => $attach_id,
						'attachment_url' => $attachment_url
					);
				} else {
					wp_send_json_error(array('error' => $movefile['error']));
				}
			}

			wp_send_json_success(array('uploaded_images' => $uploaded_images));
		} else {
			wp_send_json_error(array('message' => 'No files uploaded.'));
		}
	}




	/**
	 * Method adqs_add_feature_image
	 *
	 * @return array
	 */
	public function adqs_add_feature_image()
	{
		if (!check_ajax_referer('__qs_front_dash_list_nonce', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		// sleep(5 * 60);

		if (isset($_FILES['files'])) {
			$upload_overrides = array('test_form' => false);
			$movefile = wp_handle_upload($_FILES['files'], $upload_overrides);

			if ($movefile && !isset($movefile['error'])) {
				$filename = $movefile['file'];
				$attachment = array(
					'post_mime_type' => $movefile['type'],
					'post_title'     => sanitize_file_name($_FILES['files']['name']),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attach_id = wp_insert_attachment($attachment, $filename);

				if (!is_wp_error($attach_id)) {
					// if attachment post was successfully created, insert it as a thumbnail to the post $post_id.
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');

					$attachment_data = wp_generate_attachment_metadata($attach_id, $filename);

					wp_update_attachment_metadata($attach_id,  $attachment_data);
				}

				$attachment_url = wp_get_attachment_url($attach_id);

				wp_send_json_success(array(
					'attachment_id' => $attach_id,
					'attachment_url' => $attachment_url
				));
			} else {
				// Handle the error
				wp_send_json_error(array('error' => $movefile['error']));
			}
		} else {
			wp_send_json_error();
		}
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
	}


	/**
	 * Slider Metabox
	 *
	 * @return void
	 */
	public function slider_metabox_view($post)
	{
		wp_nonce_field(basename(__FILE__), 'adqs_slider_metabox');
		$name  = '_images';
		$value = AD()->Helper->meta_val($post->ID, $name, array());
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
							<input type="hidden" name="_images[]" value="<?php echo absint($image_id); ?>"><i
								class="dashicons dashicons-remove"></i>
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
			<select name="adqs_directory_type" id="adqs_directory_type"
				data-post-id="<?php echo !empty($post_id) ? esc_attr($post_id) : ''; ?>" required>
				<?php
				if (!empty($directory_types)) :

					foreach ($directory_types as $type) :

				?>
						<option value="<?php echo esc_attr($type->term_id); ?>"
							<?php !empty($getDirTypeID) ? selected($getDirTypeID, $type->term_id) : selected('General', trim($type->name)); ?>>
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
								<div id="<?php echo esc_attr($sectionId); ?>"
									class="adTab-body qsd-entry-content <?php echo esc_attr($panelActiveClass); ?>">
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
						?>



					</div>
				</div>
			</section>
			<?php
		endif; // end

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
		if (!isset($_POST['_images']) || !wp_verify_nonce($_POST['adqs_slider_metabox'], basename(__FILE__)) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		$getData = AD()->Helper->post_data($_POST, '_images', array());
		$getData = map_deep($getData, 'absint');
		update_post_meta($post_id, '_images', $getData);
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
		if (!isset($_POST['adqs_select_directory']) || !wp_verify_nonce($_POST['adqs_select_directory'], 'directory_type') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
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
		$this->save_slider_metabox($post_id);
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

		if (!isset($_POST['adqs_select_directory']) || !wp_verify_nonce($_POST['adqs_select_directory'], 'directory_type')) {
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
						<label class="selectit"><input value="<?php echo esc_attr($term->term_id); ?>" type="checkbox"
								id="in-popular-<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>"
								<?php echo !empty($checked) ? esc_attr($checked) : ''; ?>>
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

			// conditionel render field
			wp_enqueue_script('qsd-display-if', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/display-if.min.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);

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
		}
	}
} // end