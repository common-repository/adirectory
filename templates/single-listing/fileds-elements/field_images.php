<?php

/**
 * The template for displaying listing field_images content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/field_images.php.
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

$name = "_field_images_{$fieldid}";
if(in_array($name,$skip_fields)){
	return;
}
$field_images = $Helper->meta_val($post_id, $name);


if (!empty($field_images)) :
?>
    <div class="listing-grid-info listing-grid-field_images">
        <?php if (!empty($label)) : ?>
            <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif; ?>
        <div class= "qsd-grid-fieldImages_wrap">
        <?php
        foreach ($field_images as $imgId) :
            $getImgSrc = wp_get_attachment_image_url(absint($imgId), 'full');
        ?>
            <div class="listing-grid-field_image-item">
                <img src="<?php echo esc_url($getImgSrc); ?>" alt="#" class=" object-fit">
            </div>
        <?php endforeach; ?>
        </div>
    </div>

<?php endif;
