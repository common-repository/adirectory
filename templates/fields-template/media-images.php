<?php

/**
 * The template for displaying listing slider in the archive-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive/author-info.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

extract($args);

$fetaure_image = get_post_meta($post_id, '_thumbnail_id', true);
$slider_images =  get_post_meta($post_id, '_images', true);


$slider_value = !empty($slider_images) ? implode(',', $slider_images) : '';


if ($ispricing) {



    if ($pricing_active === "self") {
        $order_id = $_GET['adqs_order'] ?? 0;
        $pricing = adp_get_query_order((int)$order_id, 'pricing_id');
        $exist_feature_image = adp_get_query_pricing_meta($pricing->pricing_id, "lm_faturimg", true);
        $exist_gallery_image = adp_get_query_pricing_meta($pricing->pricing_id, "lm_slider", true);
    } else {
        $order_id = $_GET['adqs_order'] ?? 0;
        $pricing = adp_wc_get_query_order((int)$order_id);
        $exist_feature_image = adp_wc_get_query_pricing_meta($pricing, "lm_faturimg", true);
        $exist_gallery_image = adp_wc_get_query_pricing_meta($pricing, "lm_slider", true);
    }
}



?>

<?php
if ($ispricing) { ?>
    <div class="adqs-section-wrapper">
        <div class="adqs-section-head-title">
            <h3><?php echo esc_html__("Media", "adirectory"); ?></h3>
        </div>
        <div class="adqs-section-body">
            <?php
            if ($ispricing && $exist_feature_image) : ?>
                <div class="single-field-wrapper">

                    <div class="adqs-form-inner">
                        <h4 class="qsd-form-label">
                            <?php echo esc_html__("Feature Image", "adirectory"); ?> </h4>
                        <div class="adqs-image-section">
                            <div class="adqs-uplode-thumb-main">
                                <span class="uplode-thumb upload-img">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.6821 6.96778C17.5939 6.96778 18.333 6.22865 18.333 5.3169C18.333 4.40514 17.5939 3.66602 16.6821 3.66602C15.7704 3.66602 15.0312 4.40514 15.0312 5.3169C15.0312 6.22865 15.7704 6.96778 16.6821 6.96778Z"
                                            fill="#2B74FE"></path>
                                        <path
                                            d="M19.8 0H2.2C0.9856 0 0 0.9856 0 2.2V13.6048V19.8C0 21.0144 0.9856 22 2.2 22H19.8C21.0144 22 22 21.0144 22 19.8V15.6922V2.2C22 0.9856 21.0144 0 19.8 0ZM17.2251 11.5051C16.8098 11.1408 16.1902 11.1408 15.7749 11.5051L14.1205 12.9571L8.15408 7.7264C7.73872 7.36208 7.1192 7.36208 6.70384 7.7264L0.73392 12.9606V2.2C0.73392 1.3904 1.39216 0.73392 2.2 0.73392H19.8C20.6096 0.73392 21.2661 1.39216 21.2661 2.2V15.048L17.2251 11.5051Z"
                                            fill="#2B74FE"></path>
                                    </svg>
                                </span>
                                <div class="uplode-thumb-main-item">
                                    <p>
                                        Drag &amp; Drop or
                                        <span class="adqs-inner-text adqs-input-file">Choose File</span>
                                    </p>
                                    <input type="hidden" name="feat_thumbnail_id" id="feat_thumbnail_id"
                                        value="<?php echo esc_attr($fetaure_image); ?>" />
                                    <input type="file" name="feature_img" id="adqs-front-feat-img"
                                        accept="image/jpeg, image/jpg, image/png, image/webp" class="adqs-input-item" />
                                </div>
                            </div>
                            <div class="feature-img-container">
                                <?php
                                if (!empty($fetaure_image)) { ?>
                                    <div class="single-feat-wrapper"><img
                                            src="<?php echo esc_url(wp_get_attachment_url($fetaure_image)); ?>">
                                        <div id="feat-remove"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z"
                                                    fill="#FAFAFA"></path>
                                            </svg>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;

            if ($ispricing && $exist_gallery_image) : ?>
                <div class="single-field-wrapper">
                    <div class="adqs-form-inner">
                        <h4 class="qsd-form-label">
                            <?php echo esc_html__("Gallery Images", "adirectory"); ?> </h4>
                        <div class="adqs-image-section">
                            <div class="adqs-uplode-thumb-main">
                                <span class="uplode-thumb upload-img">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.6821 6.96778C17.5939 6.96778 18.333 6.22865 18.333 5.3169C18.333 4.40514 17.5939 3.66602 16.6821 3.66602C15.7704 3.66602 15.0312 4.40514 15.0312 5.3169C15.0312 6.22865 15.7704 6.96778 16.6821 6.96778Z"
                                            fill="#2B74FE"></path>
                                        <path
                                            d="M19.8 0H2.2C0.9856 0 0 0.9856 0 2.2V13.6048V19.8C0 21.0144 0.9856 22 2.2 22H19.8C21.0144 22 22 21.0144 22 19.8V15.6922V2.2C22 0.9856 21.0144 0 19.8 0ZM17.2251 11.5051C16.8098 11.1408 16.1902 11.1408 15.7749 11.5051L14.1205 12.9571L8.15408 7.7264C7.73872 7.36208 7.1192 7.36208 6.70384 7.7264L0.73392 12.9606V2.2C0.73392 1.3904 1.39216 0.73392 2.2 0.73392H19.8C20.6096 0.73392 21.2661 1.39216 21.2661 2.2V15.048L17.2251 11.5051Z"
                                            fill="#2B74FE"></path>
                                    </svg>
                                </span>
                                <div class="uplode-thumb-main-item">
                                    <p>
                                        Drag &amp; Drop or
                                        <span class="adqs-inner-text adqs-input-file">Choose File</span>
                                    </p>
                                    <input type="hidden" name="slider_thumbnail_id" id="slider_thumbnail_id"
                                        value="<?php echo esc_attr($slider_value); ?>" />
                                    <input type="file" id="slider_image_inp"
                                        accept="image/jpeg, image/jpg, image/png, image/webp" class="adqs-input-item" multiple>
                                </div>
                            </div>
                            <div class="slider-img-container">
                                <?php
                                if (!empty($slider_images)) {
                                    foreach ($slider_images as $value) { ?>
                                        <div class="single-slide-wrapper" data-attach-id="<?php echo esc_attr($value); ?>"><img
                                                src="<?php echo esc_url(wp_get_attachment_url($value)); ?>">
                                            <div id="slide-remove"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z"
                                                        fill="#FAFAFA"></path>
                                                </svg></div>
                                        </div>
                                <?php }
                                }


                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;

            ?>
        </div>
    </div>
<?php } else { ?>
    <div class="adqs-section-wrapper">
        <div class="adqs-section-head-title">
            <h3><?php echo esc_html__("Media", "adirectory"); ?></h3>
        </div>
        <div class="adqs-section-body">
            <div class="single-field-wrapper">

                <div class="adqs-form-inner">
                    <h4 class="qsd-form-label">
                        <?php echo esc_html__("Feature Image", "adirectory"); ?> </h4>
                    <div class="adqs-image-section">
                        <div class="adqs-uplode-thumb-main">
                            <span class="uplode-thumb upload-img">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.6821 6.96778C17.5939 6.96778 18.333 6.22865 18.333 5.3169C18.333 4.40514 17.5939 3.66602 16.6821 3.66602C15.7704 3.66602 15.0312 4.40514 15.0312 5.3169C15.0312 6.22865 15.7704 6.96778 16.6821 6.96778Z"
                                        fill="#2B74FE"></path>
                                    <path
                                        d="M19.8 0H2.2C0.9856 0 0 0.9856 0 2.2V13.6048V19.8C0 21.0144 0.9856 22 2.2 22H19.8C21.0144 22 22 21.0144 22 19.8V15.6922V2.2C22 0.9856 21.0144 0 19.8 0ZM17.2251 11.5051C16.8098 11.1408 16.1902 11.1408 15.7749 11.5051L14.1205 12.9571L8.15408 7.7264C7.73872 7.36208 7.1192 7.36208 6.70384 7.7264L0.73392 12.9606V2.2C0.73392 1.3904 1.39216 0.73392 2.2 0.73392H19.8C20.6096 0.73392 21.2661 1.39216 21.2661 2.2V15.048L17.2251 11.5051Z"
                                        fill="#2B74FE"></path>
                                </svg>
                            </span>
                            <div class="uplode-thumb-main-item">
                                <p>
                                    Drag &amp; Drop or
                                    <span class="adqs-inner-text adqs-input-file">Choose File</span>
                                </p>
                                <input type="hidden" name="feat_thumbnail_id" id="feat_thumbnail_id"
                                    value="<?php echo esc_attr($fetaure_image); ?>" />
                                <input type="file" name="feature_img" id="adqs-front-feat-img"
                                    accept="image/jpeg, image/jpg, image/png, image/webp" class="adqs-input-item" />
                            </div>
                        </div>
                        <div class="feature-img-container">
                            <?php
                            if (!empty($fetaure_image)) { ?>
                                <div class="single-feat-wrapper"><img
                                        src="<?php echo esc_url(wp_get_attachment_url($fetaure_image)); ?>">
                                    <div id="feat-remove"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z"
                                                fill="#FAFAFA"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-field-wrapper">
                <div class="adqs-form-inner">
                    <h4 class="qsd-form-label">
                        <?php echo esc_html__("Gallery Images", "adirectory"); ?> </h4>
                    <div class="adqs-image-section">
                        <div class="adqs-uplode-thumb-main">
                            <span class="uplode-thumb upload-img">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.6821 6.96778C17.5939 6.96778 18.333 6.22865 18.333 5.3169C18.333 4.40514 17.5939 3.66602 16.6821 3.66602C15.7704 3.66602 15.0312 4.40514 15.0312 5.3169C15.0312 6.22865 15.7704 6.96778 16.6821 6.96778Z"
                                        fill="#2B74FE"></path>
                                    <path
                                        d="M19.8 0H2.2C0.9856 0 0 0.9856 0 2.2V13.6048V19.8C0 21.0144 0.9856 22 2.2 22H19.8C21.0144 22 22 21.0144 22 19.8V15.6922V2.2C22 0.9856 21.0144 0 19.8 0ZM17.2251 11.5051C16.8098 11.1408 16.1902 11.1408 15.7749 11.5051L14.1205 12.9571L8.15408 7.7264C7.73872 7.36208 7.1192 7.36208 6.70384 7.7264L0.73392 12.9606V2.2C0.73392 1.3904 1.39216 0.73392 2.2 0.73392H19.8C20.6096 0.73392 21.2661 1.39216 21.2661 2.2V15.048L17.2251 11.5051Z"
                                        fill="#2B74FE"></path>
                                </svg>
                            </span>
                            <div class="uplode-thumb-main-item">
                                <p>
                                    Drag &amp; Drop or
                                    <span class="adqs-inner-text adqs-input-file">Choose File</span>
                                </p>
                                <input type="hidden" name="slider_thumbnail_id" id="slider_thumbnail_id"
                                    value="<?php echo esc_attr($slider_value); ?>" />
                                <input type="file" id="slider_image_inp"
                                    accept="image/jpeg, image/jpg, image/png, image/webp" class="adqs-input-item" multiple>
                            </div>
                        </div>
                        <div class="slider-img-container">
                            <?php
                            if (!empty($slider_images)) {
                                foreach ($slider_images as $value) { ?>
                                    <div class="single-slide-wrapper" data-attach-id="<?php echo esc_attr($value); ?>"><img
                                            src="<?php echo esc_url(wp_get_attachment_url($value)); ?>">
                                        <div id="slide-remove"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.7874 9.25496C11.2109 9.67867 11.2109 10.3632 10.7874 10.7869C10.5762 10.9982 10.2988 11.1043 10.0213 11.1043C9.74402 11.1043 9.46671 10.9982 9.25545 10.7869L6.0001 7.53138L2.74474 10.7869C2.53348 10.9982 2.25617 11.1043 1.97886 11.1043C1.70134 11.1043 1.42403 10.9982 1.21277 10.7869C0.789265 10.3632 0.789265 9.67867 1.21277 9.25496L4.46833 5.99961L1.21277 2.74425C0.789265 2.32055 0.789265 1.63599 1.21277 1.21228C1.63648 0.788776 2.32103 0.788776 2.74474 1.21228L6.0001 4.46784L9.25545 1.21228C9.67916 0.788776 10.3637 0.788776 10.7874 1.21228C11.2109 1.63599 11.2109 2.32055 10.7874 2.74425L7.53186 5.99961L10.7874 9.25496Z"
                                                    fill="#FAFAFA"></path>
                                            </svg></div>
                                    </div>
                            <?php }
                            }


                            ?>
                        </div>
                    </div>
                    <div class="adqs-gallery-image-restriction">
                        <p style="">** A maximum of five images is allowed, and their total size must be less than 3 MB</p>
                        <p id="gallery-image-err" style="color:red"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
?>