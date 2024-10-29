<?php

namespace ADQS_Directory\Database\Traits\Taxonomy_Fields;

trait Taxonomy_Upload
{


	public $term_upload_name;

	/**
	 * Main In Hook
	 *
	 * @since 1.0.0
	 */
	public function load_taxonomy_image()
	{
		$this->term_upload_name = $this->slug . '_image_id';
		// Image fields and actions
		add_action($this->slug . '_add_form_fields', array($this, 'add_taxonomy_image'));
		add_action('created_' . $this->slug, array($this, 'save_taxonomy_image'));
		add_action($this->slug . '_edit_form_fields', array($this, 'update_taxonomy_image'));
		add_action('edited_' . $this->slug, array($this, 'updated_taxonomy_image'));
		add_action('admin_enqueue_scripts', array($this, 'load_media_scripts'));
		add_action('admin_footer', array($this, 'add_image_script'));
	}


	/**
	 * Add a form field in the new taxonomy page
	 *
	 * @since 1.0.0
	 */
	public function add_taxonomy_image()
	{
		wp_nonce_field('tax_nonce_action', 'tax_nonce');
?>
		<div class="form-field term-upload-wrap">
			<label for="<?php echo esc_attr($this->term_upload_name); ?>">
				<?php esc_html_e('Image', 'adirectory'); ?>
			</label>
			<input style="display:none;" type="number" id="<?php echo esc_attr($this->term_upload_name); ?>" name="<?php echo esc_attr($this->term_upload_name); ?>" value="">
			<div id="taxonomy-image-wrapper"></div>
			<p>
				<input type="button" class="button button-secondary adqs_tax_media_button" id="adqs_tax_media_button" name="adqs_tax_media_button" value="<?php esc_html_e('Add Image', 'adirectory'); ?>" />
				<input type="button" style="display:none;" class="button button-secondary adqs_tax_media_remove" id="adqs_tax_media_remove" name="adqs_tax_media_remove" value="<?php esc_html_e('Remove Image', 'adirectory'); ?>" />
			</p>
		</div>
	<?php
	}

	/**
	 * Save the form field
	 *
	 * @since 1.0.0
	 */
	public function save_taxonomy_image($term_id)
	{
		if (!isset($_POST['tax_nonce']) || !wp_verify_nonce($_POST['tax_nonce'], 'tax_nonce_action')) {
			return;
		}
		if (isset($_POST[$this->term_upload_name]) && ('' !== $_POST[$this->term_upload_name])) {
			add_term_meta($term_id, $this->term_upload_name, absint($_POST[$this->term_upload_name]), true);
		}
	}

	/**
	 * Edit the form field
	 *
	 * @since 1.0.0
	 */
	public function update_taxonomy_image($term)
	{
		wp_nonce_field('tax_nonce_action', 'tax_nonce');
	?>
		<tr class="form-field term-upload-wrap">
			<th scope="row">
				<label for="<?php echo esc_attr($this->term_upload_name); ?>">
					<?php esc_html_e('Image', 'adirectory'); ?>
				</label>
			</th>
			<td>
				<?php
				$image_id = get_term_meta($term->term_id, $this->term_upload_name, true);

				?>
				<input style="display:none;" type="number" id="<?php echo esc_attr($this->term_upload_name); ?>" name="<?php echo esc_attr($this->term_upload_name); ?>" value="<?php echo esc_attr($image_id); ?>">
				<div id="taxonomy-image-wrapper">
					<?php
					if ($image_id) {
						echo wp_get_attachment_image($image_id, 'thumbnail');
					}
					?>
				</div>
				<p>
					<input type="button" class="button button-secondary adqs_tax_media_button" id="adqs_tax_media_button" name="adqs_tax_media_button" value="<?php esc_html_e('Update Image', 'adirectory'); ?>" />

					<input type="button" style="display:<?php echo !$image_id ? esc_attr('none') : ''; ?>;" class="button button-secondary adqs_tax_media_remove" id="adqs_tax_media_remove" name="adqs_tax_media_remove" value="<?php esc_html_e('Remove Image', 'adirectory'); ?>" />

				</p>
			</td>
		</tr>
	<?php
	}

	/**
	 * Update the form field value
	 *
	 * @since 1.0.0
	 */
	public function updated_taxonomy_image($term_id)
	{
		if (!isset($_POST['tax_nonce']) || !wp_verify_nonce($_POST['tax_nonce'], 'tax_nonce_action')) {
			return;
		}
		if (isset($_POST[$this->term_upload_name]) && ('' !== $_POST[$this->term_upload_name])) {
			update_term_meta($term_id, $this->term_upload_name, absint($_POST[$this->term_upload_name]));
		} else {
			update_term_meta($term_id, $this->term_upload_name, '');
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 */
	public function load_media_scripts()
	{
		if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] != $this->slug) {
			return;
		}
		wp_enqueue_media();
	}
	public function add_image_script()
	{
		if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] != $this->slug) {
			return;
		}
	?>
		<script>
			jQuery(document).ready(function($) {

				var custom_uploader;
				$('body').on('click', '.adqs_tax_media_button.button', function(e) {

					e.preventDefault();
					if (custom_uploader) {
						custom_uploader.open();
						return;
					}
					custom_uploader = wp.media.frames.file_frame = wp.media({
						multiple: false,
						library: {
							type: 'image'
						},
						button: {
							text: '<?php esc_html_e('Use This Image', 'adirectory'); ?>'
						},
					});
					custom_uploader.on('select', function() {
						attachment = custom_uploader.state().get('selection').first().toJSON();
						$('#<?php echo esc_attr($this->term_upload_name); ?>').val(attachment.id);
						$('#taxonomy-image-wrapper').html('<img class="custom_media_image" src="' +
							attachment.url +
							'" style="margin:0;padding:0;max-height:150px;float:none;" />');

						$('.adqs_tax_media_remove').removeAttr('style');
					});
					custom_uploader.open();

				});

				function btnRemoveAndRest() {
					$('#<?php echo esc_attr($this->term_upload_name); ?>').val('');
					$('#taxonomy-image-wrapper').html('');

					setTimeout(function() {
						if (!($('.custom_media_image').length)) {
							$('.adqs_tax_media_remove').hide();
						}
					}, 100);
				}

				$('body').on('click', '.adqs_tax_media_remove', function() {
					btnRemoveAndRest();
				});

				<?php if (!isset($_GET['tag_ID'])) : ?>
					$(document).ajaxComplete(function(event, xhr, settings) {

						var xml = xhr.responseXML;
						$response = $(xml).find('term_id').text();
						if ($response != "") {
							// Clear the thumb image
							btnRemoveAndRest();
						}
					});
				<?php endif; ?>

			});
		</script>
<?php
	}
}
