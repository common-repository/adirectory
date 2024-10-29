<?php


if (!defined('ABSPATH')) {
	return '';
}
extract($args);


$label = $data['label'] ?? 'Business Hour';
$value = !empty(get_post_meta($post_id, 'adqs_bsuiness_data', true)) ? get_post_meta($post_id, 'adqs_bsuiness_data', true) : array();

$daysOfWeek = array(
	'sunday' => 'Sunday',
	'monday' => 'Monday',
	'tuesday' => 'Tuesday',
	'wednesday' => 'Wednesday',
	'thursday' => 'Thursday',
	'friday' => 'Friday',
	'saturday' => 'Saturday'
);

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
		<?php echo esc_html($label); ?><span><?php echo isset($data['is_required']) ? '*' : ''; ?> </h4>
	<div class="adqs-business-hour-container">
		<div class="adqs-form-wrap">
			<div class="single-group">
				<input type="radio" value="open_twenty_four" id="adqs_b_open_24" name="bhc[status]"
					<?php echo isset($value['status']) && $value['status'] === 'open_twenty_four' ? 'checked' : ''; ?> />
				<label for="adqs_b_open_24"><?php echo __("Open 24 Hour", "adirectory"); ?></label>
			</div>
			<div class="single-group">
				<input type="radio" value="hide_b_h" id="adqs_b_hide" name="bhc[status]"
					<?php echo isset($value['status']) && $value['status'] === 'hide_b_h' ? 'checked' : ''; ?> />
				<label for="adqs_b_hide"><?php echo __("Hide Business Hour", "adirectory"); ?></label>
			</div>
			<div class="single-group">
				<input type="radio" value="open_specific" id="adqs_b_open_spec" name="bhc[status]"
					<?php echo isset($value['status']) && $value['status'] === 'open_specific' ? 'checked' : '';  ?>
					<?php echo $post_id === 0 ? 'checked' : ''; ?> />
				<label for="adqs_b_open_spec"><?php echo __("Open For Specific Date", "adirectory"); ?></label>
			</div>
		</div>
		<div class="adqs-business-hour-data">
			<?php



			if (!empty($value)) {

				foreach ($value as $dayslug => $dayvalue) :
					if ($dayslug === "status") {
						continue;
					}
			?>

					<div class="single-day">
						<div class="day-switch-open">
							<div class="day-switch-grp">
								<label class="switch">
									<input class="day-switch-b" type="checkbox"
										name="bhc[<?php echo esc_attr($dayslug); ?>][enable]"
										<?php echo isset($dayvalue['enable']) && $dayvalue['enable'] === 'on' ? 'checked' : '';  ?> />

									<span class="slider round">

									</span></label>
								<span><?php echo esc_html(ucfirst($dayslug)); ?></span>
							</div>
							<div class="day-switch-grp twemnty_four_open_switch">
								<input type="checkbox" name="bhc[<?php echo esc_attr($dayslug); ?>][open_24]"
									class="open_twenty_four_trigger" id="open_24_<?php echo esc_attr($day); ?>"
									<?php echo isset($dayvalue['open_24']) && $dayvalue['open_24'] === 'on' ? 'checked' : '';  ?> />

								<label for="open_24_<?php echo esc_attr($day); ?>">Open 24 Hour</label>
							</div>
						</div>

						<div class="adqs-oen-close-time-wrapper">
							<div class="all-slot-wrapper">

								<?php
								$keys = array_keys($dayvalue);
								foreach ($dayvalue  as $key => $daydata) :
									if ($key === "enable" || $key === "open_24") {
										continue;
									}
								?>

									<div class="single-slot-open-close">
										<select
											name="bhc[<?php echo esc_attr($dayslug); ?>][<?php echo array_search($key, $keys); ?>][open]"
											id="">
											<option
												value="<?php echo !empty($daydata['open']) ? esc_attr($daydata['open']) : ''; ?>">
												<?php echo !empty($daydata['open']) ? esc_attr($daydata['open']) : 'Open'; ?>
											</option>
											<option value="">
												<?php echo esc_html__('Open', 'adirectory'); ?>
											</option>

											<?php
											for ($i = 0; $i < 24; $i++) :
												for ($j = 0; $j < 60; $j += 15) :
													$time           = strtotime("$i:$j");
													$formatted_time = gmdate('h:i A', $time);
											?>
													<option value="<?php echo esc_attr($formatted_time); ?>">
														<?php echo esc_html($formatted_time); ?>
													</option>
											<?php
												endfor;
											endfor;
											?>
										</select>
										<select
											name="bhc[<?php echo esc_attr($dayslug); ?>][<?php echo array_search($key, $keys); ?>][close]"
											id="">
											<option
												value="<?php echo !empty($daydata['close']) ? esc_attr($daydata['close']) : ''; ?>">
												<?php echo !empty($daydata['close']) ? esc_attr($daydata['close']) : 'Close'; ?>
											</option>
											<option value="">
												<?php echo esc_html__('Close', 'adirectory'); ?>
											</option>
											<?php
											for ($i = 0; $i < 24; $i++) :
												for ($j = 0; $j < 60; $j += 15) :
													$time           = strtotime("$i:$j");
													$formatted_time = gmdate('h:i A', $time);
											?>
													<option value="<?php echo esc_attr($formatted_time); ?>">
														<?php echo esc_html($formatted_time); ?>
													</option>
											<?php
												endfor;
											endfor;
											?>
										</select>
										<button class="adqs-time-slot-delete">
											<i class="dashicons dashicons-trash"></i>
										</button>
									</div>

								<?php endforeach;
								?>
							</div>


							<button class="adqs-add-time-slot" data-day="<?php echo esc_attr($dayslug); ?>">
								<i class="dashicons dashicons-plus-alt2"></i>
							</button>
						</div>
					</div>
				<?php endforeach;
			} else {
				foreach ($daysOfWeek as $key => $day) : ?>
					<div class="single-day">
						<div class="day-switch-open">
							<div class="day-switch-grp">
								<label class="switch"><input class="day-switch-b" type="checkbox"
										name="bhc[<?php echo esc_attr($key); ?>][enable]"><span
										class="slider round"></span></label>
								<span><?php echo esc_html($day); ?></span>
							</div>

							<div class="day-switch-grp twemnty_four_open_switch">
								<input type="checkbox" name="bhc[<?php echo esc_attr($key); ?>][open_24]"
									class="open_twenty_four_trigger" id="open_24_<?php echo esc_attr($day); ?>" />
								<label for="open_24_<?php echo esc_attr($day); ?>">Open 24 Hour</label>
							</div>
						</div>


						<div class="adqs-oen-close-time-wrapper">
							<div class="all-slot-wrapper">
								<div class="single-slot-open-close">
									<select name="bhc[<?php echo esc_attr($key); ?>][0][open]" id="">
										<option value="">
											<?php echo esc_html__('Open', 'adirectory'); ?>
										</option>
										<?php
										for ($i = 0; $i < 24; $i++) :
											for ($j = 0; $j < 60; $j += 15) :
												$time           = strtotime("$i:$j");
												$formatted_time = gmdate('h:i A', $time);
										?>
												<option value="<?php echo esc_attr($formatted_time); ?>">
													<?php echo esc_html($formatted_time); ?>
												</option>
										<?php
											endfor;
										endfor;
										?>
									</select>
									<select name="bhc[<?php echo esc_attr($key); ?>][0][close]" id="">
										<option value="">
											<?php echo esc_html__('Close', 'adirectory'); ?>
										</option>
										<?php
										for ($i = 0; $i < 24; $i++) :
											for ($j = 0; $j < 60; $j += 15) :
												$time           = strtotime("$i:$j");
												$formatted_time = gmdate('h:i A', $time);
										?>
												<option value="<?php echo esc_attr($formatted_time); ?>">
													<?php echo esc_html($formatted_time); ?>
												</option>
										<?php
											endfor;
										endfor;
										?>
									</select>
									<button class="adqs-time-slot-delete">
										<i class="dashicons dashicons-trash"></i>
									</button>
								</div>
							</div>


							<button class="adqs-add-time-slot" data-day="<?php echo esc_attr($key); ?>">
								<i class="dashicons dashicons-plus-alt2"></i>
							</button>
						</div>
					</div>
			<?php endforeach;
			}
			?>
		</div>
	</div>
</div>