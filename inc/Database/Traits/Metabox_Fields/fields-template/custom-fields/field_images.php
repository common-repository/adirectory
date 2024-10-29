<?php
if (!defined('ABSPATH') || !$Helper->admin_view($data)) {
	return '';
}

$label   = $Helper->get_data($data, 'label', esc_html__('Images', 'adirectory'));
$fieldid = $Helper->get_data($data, 'fieldid');
$name    = "_field_images_{$fieldid}";
$value   = $Helper->meta_val($post_id, $name, array());
?>


<div class="qsd-form-group qsd-slider-images-fields qsd-images-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html($label); ?>:
		<?php $Helper->required_html($data); ?>
	</h4>
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
					<input style="display:none;" type="text" name="<?php echo esc_attr("{$name}[]"); ?>" value="<?php echo absint($image_id); ?>" <?php $Helper->required($data); ?>><i class="dashicons dashicons-remove"></i>
				</div>
		<?php
			endforeach;
		endif;
		?>
		<div class="qsd-remove-sliders">
			<i class="dashicons dashicons-trash"></i>
		</div>
	</div>
</div><!-- end \\-->