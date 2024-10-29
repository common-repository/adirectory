<?php
if (!defined('ABSPATH')) {
	return '';
}

extract($args);

$label   = $data['label'] ?? esc_html__('Checkbox', 'adirectory');
$fieldid = $data['fieldid'];

$name        = "_checkbox_{$fieldid}";
$placeholder =  $data['placeholder'] ?? '';

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
	} else {
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
			<?php echo esc_html($label); ?><span><?php echo isset($data['is_required']) ? '*' : ''; ?>
		</h4>
		<?php
		foreach ($options as $option) :

			$optId  = $option['id'] ? trim($option['id']) : '';
			$optVal = $option['value'] ? trim($option['value']) : '';
		?>
			<div class="qsd-form-check-control">
				<input type="checkbox" id="<?php echo esc_attr($optId); ?>" name="<?php echo esc_attr($name); ?>[]"
					value="<?php echo esc_attr($optVal); ?>" <?php
																echo in_array($optVal, $value) ? esc_attr('checked') : '';
																?>>
				<label for="<?php echo esc_attr($optId); ?>"><?php echo esc_html($optVal); ?></label>
			</div>
		<?php endforeach; ?>
	</div>
</div>