<?php
if (!defined('ABSPATH')) {
	return '';
}

extract($args);
$label       = $args['data']['label'] ?? $args['data']['name'];
$fieldid = $args['data']['fieldid'];
$name            = "_textarea_{$fieldid}";
$name_list_show  = "_textarea_list_{$fieldid}";
$value       = !empty(get_post_meta($post_id, $name, true)) ? get_post_meta($post_id, $name, true) : '';
$value_list_show       = !empty(get_post_meta($post_id, $name_list_show, true)) ? get_post_meta($post_id, $name_list_show, true) : '';

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
		<textarea name="<?php echo esc_attr($name); ?>" rows="8" class="adqs-title-input" placeholder=""
			<?php echo isset($args['data']['is_required']) ? 'required' : ''; ?>><?php echo esc_html($value); ?></textarea>
		<div class="qsd-form-check-control">
			<input type="checkbox" id="<?php echo esc_attr($name_list_show); ?>"
				name="<?php echo esc_attr($name_list_show); ?>" value="1" <?php checked($value_list_show, 1); ?>>
			<label for="<?php echo esc_attr($name_list_show); ?>">
				<?php echo esc_html__('Each new line will be printed with list item', 'adirectory'); ?>
			</label>
		</div>
	</div>
</div>