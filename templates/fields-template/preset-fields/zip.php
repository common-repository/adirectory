<?php
if (!defined('ABSPATH')) {
	return '';
}
extract($args);
$label       = $args['data']['label'] ?? $args['data']['name'];
$name        = '_zip';
$placeholder = $args['data']['placeholder'] ?? '';
$value       = !empty(get_post_meta($post_id, $name, true)) ? get_post_meta($post_id, $name, true) : '';

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
	<h4 class="qsd-form-label">
		<?php echo esc_html($label); ?>:
	</h4>
	<input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>"
		class="qsd-form-control" value="<?php echo esc_attr($value); ?>"
		placeholder="<?php echo esc_attr($placeholder); ?>"
		<?php echo isset($args['data']['is_required']) ? 'required' : ''; ?>>


</div>