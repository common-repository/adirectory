<?php

/**
 * The template for displaying listing phone content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/phone.php.
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
$name = '_email';
if (in_array($name, $skip_fields)) {
    return;
}
$value = $Helper->meta_val($post_id, $name);
if (!empty($value)) :
?>
<div class="listing-grid-info listing-grid-phone qsd-small-info">
    <?php if (!empty($label)) : ?>
    <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
    <?php endif; ?>
    <p class="qsd-has-icon"> <i class="dashicons dashicons-email"></i> <?php echo esc_html($value); ?></p>
</div>

<?php endif; ?>