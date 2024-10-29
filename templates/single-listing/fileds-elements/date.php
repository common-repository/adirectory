<?php

/**
 * The template for displaying listing date content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/date.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// template part $args
extract($args);

if (!$Helper->admin_view($data)) {
    return '';
}

$label = $Helper->get_data($data, 'label');
$fieldid = $Helper->get_data($data, 'fieldid');

$name = "_date_{$fieldid}";
if(in_array($name,$skip_fields)){
	return;
}

$value = $Helper->meta_val($post_id, $name);
if (!empty($value)) :
?>
    <div class="listing-grid-info listing-grid-date qsd-small-info qsd-custom-info" id="<?php echo esc_attr($name);?>">
        <?php if( !empty($label) ): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif;?>
        <p class="qsd-has-icon"> <i class="dashicons dashicons-calendar"></i> <?php echo esc_html(wp_date( get_option( 'date_format' ), strtotime($value),get_option( 'timezone_string' )));  ?></p>
    </div>

<?php endif; ?>
