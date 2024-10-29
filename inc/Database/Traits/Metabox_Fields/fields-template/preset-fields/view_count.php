<?php
if (! defined('ABSPATH') || ! $Helper->admin_view($data)) {
	return '';
}

$label       = $Helper->get_data($data, 'label', esc_html__('View Count', 'adirectory'));
$name        = '_view_count';
$placeholder = $Helper->get_data($data, 'placeholder');
$value       = $Helper->meta_val($post_id, $name);
?>

<div class="qsd-form-group qsd-view_count-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html($label); ?>:
		<?php $Helper->required_html($data); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">
		<input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>"
			class="qsd-form-control" value="<?php echo esc_attr($value); ?>"
			placeholder="<?php echo esc_attr($placeholder); ?>" <?php $Helper->required($data); ?>>


		<?php
		if (!($data['is_required'] ?? false)): ?>
			<p class="qsd-desc">
				<?php echo esc_html__('Leave this field blank to display a real view count in a single listing meta.', 'adirectory'); ?>
			</p>
		<?php endif;
		?>

	</div>

</div><!-- end \\-->