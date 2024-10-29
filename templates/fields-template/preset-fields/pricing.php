<?php
if (!defined('ABSPATH')) {
	return '';
}

extract($args);

$label             = $data['label'] ?? $args['data']['name'];
$name_price_type   = '_price_type';
$name_price        = '_price';
$name_price_sub    = '_price_sub';
$name_price_range  = '_price_range';
$placeholder       = $data['placeholder'] ?? __('Select price', 'adirectory');
$value_price       = !empty(get_post_meta($post_id, $name_price, true)) ? get_post_meta($post_id, $name_price, true) : '';
$value_price_sub   = !empty(get_post_meta($post_id, $name_price_sub, true)) ? get_post_meta($post_id, $name_price_sub, true) : '';
$value_price_range = !empty(get_post_meta($post_id, $name_price_range, true)) ? get_post_meta($post_id, $name_price_range, true) : '';
$value_price_type  = !empty(get_post_meta($post_id, $name_price_type, true)) ? get_post_meta($post_id, $name_price_type, true) : '';
$price_type        = 'both';

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
		$exist_field = adp_wc_get_query_pricing_meta($pricing, "lm_pricing", true);
		if ($exist_field === "no") {
			return;
		}
	}
}
?>

<div class="single-field-wrapper">
	<h4 class="qsd-form-label">
		<?php echo esc_html($label); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">

		<?php if ($price_type == 'both') : ?>
			<div class="adqs-field-inline adqs-choose-fields adqs-radio-choose">
				<div class="adqs-form-check-control">
					<input type="radio" class="adqs-price-type-trigger" id="adqs_price_type_price" name="adqs_price_type"
						checked value="<?php echo esc_attr($name_price); ?>"
						<?php checked($value_price_type, $name_price); ?>>
					<label for="adqs_price_type_price">
						<?php echo esc_html__('Price [USD]', 'adirectory'); ?>
					</label>
				</div>
				<div class="adqs-form-check-control">
					<input type="radio" class="adqs-price-type-trigger" id="adqs_price_type_range" name="adqs_price_type"
						value="<?php echo esc_attr($name_price_range); ?>"
						<?php checked($value_price_type, $name_price_range); ?>>
					<label for="adqs_price_type_range">
						<?php echo esc_html__('Price Range', 'adirectory'); ?>
					</label>
				</div>
			</div>
		<?php endif; ?>


		<?php if (($price_type == 'both') || ($price_type == 'unit')) : ?>
			<div id="adqs_pricefield_unit" <?php if ($price_type == 'both') : ?> data-target_type="radio" <?php endif; ?>>
				<div class="adqs-price_units">
					<input type="number" id="adqs_price" class="adqs-form-control"
						name="<?php echo esc_attr($name_price); ?>" value="<?php echo esc_attr($value_price); ?>"
						placeholder="<?php echo esc_html__('Price amount', 'adirectory'); ?>">

					<input type="text" id="adqs_price_sub" class="adqs-form-control"
						name="<?php echo esc_attr($name_price_sub); ?>" value="<?php echo esc_attr($value_price_sub); ?>"
						placeholder="<?php echo esc_html__('Period time', 'adirectory'); ?>">
				</div>
			</div>
		<?php endif; ?>

		<?php if (($price_type == 'both') || ($price_type == 'range')) : ?>
			<?php
			$price_range_options = apply_filters(
				'adqs_meta_price_range',
				array(
					'skimming'       => esc_html__('Ultra High ($$$$)', 'adirectory'),
					'moderate'       => esc_html__('Expensive ($$$)', 'adirectory'),
					'economy'        => esc_html__('Moderate ($$)', 'adirectory'),
					'bellow_economy' => esc_html__('Cheap ($)', 'adirectory'),
				)
			);
			?>
			<select id="adqs_price_range" class="adqs-form-control" name="<?php echo esc_attr($name_price_range); ?>"
				<?php if ($price_type == 'both') : ?> data-target_type="radio" <?php endif; ?>>
				<option value="">
					<?php echo esc_html__('Select Price Range', 'adirectory'); ?>
				</option>
				<?php
				if (!empty($price_range_options)) :
					foreach ($price_range_options as $pr_key => $pr_item) :
				?>
						<option value="<?php echo esc_attr($pr_key); ?>" <?php selected($value_price_range, $pr_key); ?>>
							<?php echo esc_html($pr_item); ?>
						</option>
				<?php
					endforeach;
				endif;
				?>
			</select>
		<?php endif; ?>

	</div>

</div>