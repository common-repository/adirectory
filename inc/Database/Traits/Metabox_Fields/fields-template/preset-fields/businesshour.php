<?php


if (!defined('ABSPATH') || !$Helper->admin_view($data)) {
	return '';
}
$name = 'adqs_bsuiness_data';
$value = $Helper->meta_val($post_id, $name);
$valudata = $Helper->meta_val($post_id, 'adqs_bsuiness_data',[]);
$valstatus =  $valudata['status'] ?? 'open_specific';

$daysOfWeek = array(
	'sunday' => 'Sunday',
	'monday' => 'Monday',
	'tuesday' => 'Tuesday',
	'wednesday' => 'Wednesday',
	'thursday' => 'Thursday',
	'friday' => 'Friday',
	'saturday' => 'Saturday'
);



?>


<!-- Form Section Start /-->
<div class="qsd-form-group qsd-business-hours">
    <h4 class="qsd-form-label"><?php echo esc_html__('Business Hours', 'adirectory'); ?></h4>
    <div class="qsd-form-group qsd-input-b-hour-fields">
        <div class="qsd-form-wrap">
            <div class="qsd-form-check-control">
                <input type="radio" id="24_hours" name="bhc[status]" value="open_twenty_four"
                    <?php echo isset($valstatus) && $valstatus === 'open_twenty_four' ? 'checked' : ''; ?>>
                <label for="24_hours"><?php echo esc_html__('Open 24 hours 7 days', 'adirectory'); ?></label>
            </div>
            <div class="qsd-form-check-control">
                <input type="radio" id="hide" value="hide_b_h" name="bhc[status]"
                    <?php echo isset($valstatus) && $valstatus === 'hide_b_h' ? 'checked' : ''; ?>>
                <label for="hide"><?php echo esc_html__('Hide business hours', 'adirectory'); ?></label>
            </div>
            <div class="qsd-form-check-control">
                <input type="radio" id="open" value="open_specific" name="bhc[status]"
                    <?php echo isset($valstatus) && $valstatus === 'open_specific' ? 'checked' : ''; ?>>
                <label for="open"><?php echo esc_html__('Open for Selected Hours', 'adirectory'); ?></label>
            </div>
        </div>

        <div class="qsd-b-hour-fields-wrap display-if" data-target_name="bhc[status]" data-target_type="radio"
            data-target_value="open_specific">
            <?php

				if (!empty($value)) {
					foreach ($value as $daysslug => $dayvalue) { 
						if($daysslug === 'status'){
							continue;		
						}
						
						?>
            <div class="qsd-b-hour-group-day-single">
                <div class="qsd-b-hour-item-enables">
                    <div class="qsd-b-hour-item qsd-b-hour-item-swtice">
                        <div class="qsd-form-switch-control">
                            <label class="qsd-switch" for="<?php echo esc_attr($daysslug); ?>">
                                <input type="checkbox" name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                                    id="<?php echo esc_attr($daysslug); ?>" value="on"
                                    <?php checked($dayvalue['enable'] ?? '', 'on'); ?> />

                                <span class="qsd-switch-slider round"></span>

                                <span
                                    class="qsd-switch-text"><?php echo esc_html__(ucfirst($daysslug), "adirectory"); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="qsd-b-hour-item qsd-b-hour-item-checkbox display-if"
                        data-target_name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                        data-target_type="checkbox" data-target_has_any_value="on">
                        <div class="qsd-form-check-control qsd-open24Each-checkbox">
                            <input type="checkbox" id="<?php echo esc_attr("open_id" . $daysslug);  ?>"
                                name="<?php echo esc_attr("bhc[" . $daysslug . "][open_24]"); ?>"
                                <?php checked($dayvalue['open_24'] ?? '', 'on'); ?> />

                            <label
                                for="<?php echo esc_attr("open_id" . $daysslug);  ?>"><?php echo esc_html__('Open 24 hours', "adirectory"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="qsd-b-hour-item-choice-area display-if <?php echo esc_attr((($dayvalue['open_24'] ?? '') === 'on') ? 'hidden' : ''); ?>"
                    data-target_name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                    data-target_type="checkbox" data-target_has_any_value="on">
                    <div class="qsd-repeater-items">
                        <div class="qsd-b-hour-item-choice-list"
                            data-repeater-list="bhc[<?php echo esc_attr($daysslug); ?>]">

                            <?php

										foreach ($dayvalue  as $key => $daydata) {
											if ($key === "enable" || $key === "open_24") {
												continue;
											} ?>



                            <div class="qsd-b-hour-choice" data-repeater-item>
                                <div class="qsd-b-hour-c-select">
                                    <select class="qsd-form-control" name="open">
                                        <option value="">
                                            <?php echo esc_html__('Open', 'adirectory'); ?>
                                        </option>

                                        <?php
														for ($i = 0; $i < 24; $i++) :
															for ($j = 0; $j < 60; $j += 15) :
																$time           = strtotime("$i:$j");
																$formatted_time = gmdate('h:i A', $time);
														?>
                                        <option value="<?php echo esc_attr($formatted_time); ?>"
                                            <?php selected($daydata['open'] ?? '', $formatted_time); ?>>
                                            <?php echo esc_html($formatted_time); ?>
                                        </option>
                                        <?php
															endfor;
														endfor;
														?>

                                    </select>
                                </div>
                                <div class="qsd-b-hour-c-select">
                                    <select name="close" class="qsd-form-control">
                                        <option value="">
                                            <?php echo esc_html__('Close', 'adirectory'); ?>
                                        </option>

                                        <?php
														for ($i = 0; $i < 24; $i++) :
															for ($j = 0; $j < 60; $j += 15) :
																$time           = strtotime("$i:$j");
																$formatted_time = gmdate('h:i A', $time);
														?>
                                        <option value="<?php echo esc_attr($formatted_time); ?>"
                                            <?php selected($daydata['close'] ?? '', $formatted_time); ?>>
                                            <?php echo esc_html($formatted_time); ?>
                                        </option>
                                        <?php
															endfor;
														endfor;
														?>

                                    </select>
                                </div>
                                <div class="qsd-b-hour-c-delete" data-repeater-delete>
                                    <i class="dashicons dashicons-trash"></i>
                                </div>
                            </div>


                            <?php }


										?>

                        </div>
                        <div class="qsd-b-hour-c-add" data-repeater-create>
                            <i class="dashicons dashicons-plus-alt2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
				} else {

					foreach ($daysOfWeek as $daysslug => $dayvalue) : ?>
            <div class="qsd-b-hour-group-day-single">
                <div class="qsd-b-hour-item-enables">
                    <div class="qsd-b-hour-item qsd-b-hour-item-swtice">
                        <div class="qsd-form-switch-control">
                            <label class="qsd-switch" for="<?php echo esc_attr($daysslug); ?>">
                                <input type="checkbox" name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                                    id="<?php echo esc_attr($daysslug); ?>" />
                                <span class="qsd-switch-slider round"></span>
                                <span class="qsd-switch-text"><?php echo esc_html__($dayvalue, "adirectory"); ?></span>
                            </label>
                        </div>
                    </div>
                    <div class="qsd-b-hour-item qsd-b-hour-item-checkbox display-if"
                        data-target_name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                        data-target_type="checkbox" data-target_has_any_value="on">
                        <div class="qsd-form-check-control qsd-open24Each-checkbox">
                            <input type="checkbox" id="<?php echo esc_attr("open_id" . $daysslug);  ?>"
                                name="<?php echo esc_attr("bhc[" . $daysslug . "][open_24]"); ?>">
                            <label
                                for="<?php echo esc_attr("open_id" . $daysslug);  ?>"><?php echo esc_html__('Open 24 hours', "adirectory"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="qsd-b-hour-item-choice-area display-if"
                    data-target_name="<?php echo esc_attr("bhc[" . $daysslug . "][enable]"); ?>"
                    data-target_type="checkbox" data-target_has_any_value="on">
                    <div class="qsd-repeater-items">
                        <div class="qsd-b-hour-item-choice-list"
                            data-repeater-list="bhc[<?php echo esc_attr($daysslug); ?>]">
                            <div class="qsd-b-hour-choice" data-repeater-item>
                                <div class="qsd-b-hour-c-select">
                                    <select class="qsd-form-control" name="open">
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
                                </div>
                                <div class="qsd-b-hour-c-select">
                                    <select name="close" class="qsd-form-control">
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
                                </div>
                                <div class="qsd-b-hour-c-delete" data-repeater-delete>
                                    <i class="dashicons dashicons-trash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="qsd-b-hour-c-add" data-repeater-create>
                            <i class="dashicons dashicons-plus-alt2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;
				}

				?>
        </div>

    </div><!-- end \\-->

</div><!-- Form Section end \-->