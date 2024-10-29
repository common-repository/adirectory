<?php

/**
 * The Template for displaying all archive-listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive-listing.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

extract($args);

$post_id = isset($post_id) ? $post_id : 0;

?>

<!-- Title and description -->

<?php adqs_get_template_part('fields-template/general', '', compact('post_id', 'directory_type', 'ispricing', 'pricing_active'));

$meta_fields = adqs_get_listing_fields($directory_type);
if (!empty($meta_fields) && is_array($meta_fields)) :
?>
	<?php
	if (!empty($meta_fields) && is_array($meta_fields)) :
		foreach ($meta_fields as $key => $section) :
			$sectionTitle     = isset($section['sectiontitle']) ? $section['sectiontitle'] : esc_html__('Section', 'adirectory');
			$sectionId        = isset($section['id']) ? $section['id'] : '';
	?>
			<div class="adqs-section-wrapper">
				<!-- Meta fields title -->
				<div class="adqs-section-head-title">
					<h3>
						<?php echo esc_html($sectionTitle); ?>
					</h3>
				</div>
				<?php
				if (isset($section['fields']) && !empty($section['fields'])) :
				?>
					<!-- Form Section Start /-->
					<div class="adqs-section-body">
						<?php
						foreach ($section['fields'] as $data) :

							$input_type = (isset($data['input_type']) && !empty($data['input_type'])) ? $data['input_type'] : false;

							$preseet_fields = ['address', 'businesshour', 'fax', 'map', 'phone', 'pricing', 'tagline', 'video', 'view_count', 'website', 'zip', 'email'];

							$custom_fields = ['checkbox', 'date', 'field_images', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url'];

							if (in_array($input_type, $preseet_fields)) {

								adqs_get_template_part("fields-template/preset-fields/{$input_type}", '', compact('data', 'post_id', 'ispricing', 'pricing_active'));
							} else {

								adqs_get_template_part("fields-template/custom-fields/{$input_type}", '', compact('data', 'post_id', 'ispricing', 'pricing_active'));
							}

						endforeach;
						?>
					</div>
				<?php
				endif;
				?>
			</div>
<?php


		endforeach;
	endif;
endif; // end

adqs_get_template_part("fields-template/media", "images", compact('post_id', 'ispricing', 'pricing_active'));

?>