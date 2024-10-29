<?php

/**
 * The template for displaying listing map content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/map.php.
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
$name_lat = '_map_lat';
$name_lon = '_map_lon';
$name_hide_map = '_hide_map';
if(in_array($name_lat,$skip_fields) || in_array($name_lon,$skip_fields)){
	return;
}
$value_lat = $Helper->meta_val($post_id, $name_lat);
$value_lon = $Helper->meta_val($post_id, $name_lon);
$value_hide_map = absint($Helper->meta_val($post_id, $name_hide_map));


if (!empty($value_lat) && !empty($value_lon) && empty($value_hide_map)) :
?>
    <div class="listing-grid-info listing-grid-location">
        <?php if( !empty($label) ): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif;?>    
        <div class="listing-grid-location-item">
            <div class="listing-grid-location-map">
            <div id="qsdMap" data-lat="<?php echo esc_attr($value_lat);?>" data-lon="<?php echo esc_attr($value_lon);?>"></div>
            </div>
        </div>
    </div>

<?php endif; ?>