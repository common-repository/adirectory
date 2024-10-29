<?php

/**
 * The Template for displaying all archive-listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive-listing.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
} ?>

<div class="adqs-select-pricing-select">
    <h3 class="adqs-select-package-title"><?php echo __('Select package', 'adirectory'); ?></h3>
    <?php
	global $wp;
	extract($args);

	if (count($pricing_plans) === 1) {
		$query_arg = add_query_arg(
			array(
				"adqs_listing_type" => $directory_type,
				"adqs_order" => $pricing_plans[0]['orderid'],
			),
			home_url($wp->request)
		);
	?>


    <a href="<?php echo add_query_arg(array("adqs_listing_type" => $directory_type, "adqs_order" => $pricing_plans[0]['orderid']), home_url($wp->request)); ?>"
        class="qsd-front-single-dir-item" data-pricing-id="<?php echo esc_attr($pricing_plans[0]['pricing_id']); ?>">
        <?php echo esc_attr($pricing_plans[0]['name']); ?>
    </a>

    <?php } else {
		foreach ($pricing_plans as $order) : ?>
    <a href="<?php echo add_query_arg(array("adqs_listing_type" => $directory_type, "adqs_order" => $order['orderid']), home_url($wp->request)); ?>"
        class="qsd-front-single-dir-item" data-pricing-id="<?php echo esc_attr($order['pricing_id']); ?>">
        <?php echo esc_attr($order['name']); ?>
    </a>
    <?php endforeach;
	}
	?>
</div>