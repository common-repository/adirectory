<?php

/**
 * The template for displaying listing select content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/select.php.
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

$name = "_select_{$fieldid}";
if(in_array($name,$skip_fields)){
	return;
}
$value = $Helper->meta_val($post_id, $name);
if (!empty($value)) :
?>
    <div class="listing-grid-info listing-grid-select qsd-small-info qsd-custom-info" id="<?php echo esc_attr($name);?>">
        <?php if( !empty($label) ): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif;?>
        <p><?php echo esc_html($value);?></p>
    </div>

<?php endif; ?>