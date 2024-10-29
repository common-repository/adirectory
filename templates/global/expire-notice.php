<?php

/**
 * The template for displaying listing price in the globaly template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/expire-notice.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if access directly
}
global $post;
$post_id = !empty($post->ID) ? $post->ID : get_the_ID();

$expiryNever = AD()->Helper->meta_val($post_id, '_expiry_never', '');
if ($expiryNever === 'yes') {
    return;
}


$value = AD()->Helper->meta_val($post_id, '_expiry_date', '');


if (!empty($value)):
    list($mm, $jj, $aa, $hh, $mn) = explode('_', $value);
    $expirationDateString =  "{$aa}-{$mm}-{$jj} {$hh}:{$mn}";

    if (time() > strtotime($expirationDateString)): ?>
        <span class="listing-grid-details-btn adqs-llisting-expire-notice">
            <i class="dashicons dashicons-warning"></i>
            <?php echo apply_filters('adqs_expire_notice_text', esc_html__('Expired', 'adirectory')); ?>
        </span>

<?php
    endif;
endif;
