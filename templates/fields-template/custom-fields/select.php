<?php
if (!defined('ABSPATH')) {
	return '';
}
extract($args);
$label   = $data['label'] ?? __("Select", "adirectory");
$fieldid = $data['fieldid'];

$name        = "_select_{$fieldid}";
$placeholder = $data['placeholder'] ?? '';
$value       = !empty(get_post_meta($post_id, $name, true)) ? get_post_meta($post_id, $name, true) : '';
$options     = $data['options'] ?? array();

if ($ispricing) {
	if ($pricing_active === "self") {
		$order_id = $_GET['adqs_order'] ?? 0;
		$pricing = adp_get_query_order((int)$order_id, 'pricing_id');
		$exist_field = adp_get_query_pricing_meta($pricing->pricing_id, "lm" . $name, true);
		if ($exist_field === "no") {
			return;
		}
	} else if ($pricing_active === "wc") {
		$order_id = $_GET['adqs_order'] ?? 0;
		$pricing = adp_wc_get_query_order((int)$order_id);
		$exist_field = adp_wc_get_query_pricing_meta($pricing, "lm" . $name, true);
		if ($exist_field === "no") {
			return;
		}
	}
}




?>


<div class="single-field-wrapper">
	<div class="adqs-form-inner">
		<h4 class="qsd-form-label">
			<?php echo esc_html($label); ?><span><?php echo isset($data['is_required']) ? '*' : ''; ?> </h4>
		<select name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="adqs-title-input">
			<option value=""><?php echo esc_html($placeholder); ?></option>
			<?php
			foreach ($options as $option) :

				$optId  = $option['id'] ? trim($option['id']) : '';
				$optVal = $option['value'] ? trim($option['value']) : '';
			?>
				<option value="<?php echo esc_attr($optVal); ?>" <?php selected($value, $optVal); ?>>
					<?php echo esc_html($optVal); ?></option>
			<?php endforeach; ?>

		</select>
	</div>
</div>