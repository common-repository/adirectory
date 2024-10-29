<?php
if (!defined('ABSPATH') || !$Helper->admin_view($data)) {
	return '';
}

$label             = $Helper->get_data($data, 'label', esc_html__('Price', 'adirectory'));
$name_price_type   = '_price_type';
$name_price        = '_price';
$name_price_sub    = '_price_sub';
$name_price_range  = '_price_range';
$placeholder       = $Helper->get_data($data, 'placeholder', esc_html__('Select Price Range', 'adirectory'));
$value_price       = $Helper->meta_val($post_id, $name_price);
$value_price_sub   = $Helper->meta_val($post_id, $name_price_sub);
$value_price_range = $Helper->meta_val($post_id, $name_price_range);
$value_price_type  = $Helper->meta_val($post_id, $name_price_type);
$price_type        = $Helper->get_data($data, 'price_type', 'both');
?>

<div class="qsd-form-group qsd-input-price">
	<h4 class="qsd-form-label">
		<?php echo esc_html($label); ?>:
		<?php $Helper->required_html($data); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">

		<?php if ($price_type == 'both') : ?>
			<div class="qsd-field-inline qsd-choose-fields qsd-radio-choose">
				<div class="qsd-form-check-control">
					<input type="radio" id="_price_type_price" name="_price_type" checked value="<?php echo esc_attr($name_price); ?>" <?php checked($value_price_type, $name_price); ?>>
					<label for="_price_type_price">
						<?php echo esc_html__('Price [USD]', 'adirectory'); ?>
					</label>
				</div>
				<div class="qsd-form-check-control">
					<input type="radio" id="_price_type_range" name="_price_type" value="<?php echo esc_attr($name_price_range); ?>" <?php checked($value_price_type, $name_price_range); ?>>
					<label for="_price_type_range">
						<?php echo esc_html__('Price Range', 'adirectory'); ?>
					</label>
				</div>
			</div>
		<?php endif; ?>

		<?php if (($price_type == 'both') || ($price_type == 'unit')) : ?>
			<div class="qsd-priceFields_unit <?php echo ($price_type == 'both') ? esc_attr('display-if') : ''; ?>" <?php
																														if ($price_type == 'both') :
																														?> data-target_name="_price_type" data-target_type="radio" data-target_value="<?php echo esc_attr($name_price); ?>" <?php endif; ?>>
				<div class="qsd-price_units">
					<input type="number" id="_price" class="qsd-form-control" name="<?php echo esc_attr($name_price); ?>" value="<?php echo esc_attr($value_price); ?>" placeholder="<?php echo esc_html__('Price amount', 'adirectory'); ?>">
					<input type="text" id="_price_sub" class="qsd-form-control" name="<?php echo esc_attr($name_price_sub); ?>" value="<?php echo esc_attr($value_price_sub); ?>" placeholder="<?php echo esc_html__('Period time', 'adirectory'); ?>">
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
			<select id="_price_range" class="qsd-form-control <?php echo ($price_type == 'both') ? esc_attr('display-if') : ''; ?>" name="<?php echo esc_attr($name_price_range); ?>" <?php
																																															if ($price_type == 'both') :
																																															?> data-target_name="_price_type" data-target_type="radio" data-target_value="<?php echo esc_attr($name_price_range); ?>">
			<?php endif; ?>
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

</div><!-- end \\-->