<?php

/**
 * The template for displaying listing website content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/website.php.
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
$name = '_website';
if(in_array($name,$skip_fields)){
	return;
}

$value = $Helper->meta_val($post_id, $name);
if (!empty($value)) :
?>
    <div class="listing-grid-info listing-grid-website qsd-small-info">
        <?php if( !empty($label) ): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif;?>
        <p class="qsd-has-icon"> <i class="dashicons dashicons-admin-site-alt"></i> <a href="<?php echo esc_url($value);?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(untrailingslashit($value));?></a></p>
    </div>

<?php endif; ?>
