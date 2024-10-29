<?php

/**
 * The template for displaying listing video content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/video.php.
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
$name = '_video';
if(in_array($name,$skip_fields)){
	return;
}
$value = $Helper->meta_val($post_id, $name);
$video = $Helper->determineVideoUrlType($value);
$video_id = isset($video['video_id']) ? $video['video_id'] : '';
$video_type = isset($video['video_type']) ? $video['video_type'] : '';
$onlyVideoType = ['youtube','vimeo'];

if ( !empty($video) && !empty($video_id) && in_array($video_type,$onlyVideoType) ) :
$video_img = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
?>
<div class="listing-grid-info listing-grid-vedio">
    <?php if(!empty($label)): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
    <?php endif;?>
    <div class="listing-grid-vedio-item">
        <div class="listing-grid-vedio-item-thumb">
            <?php if( $video_type === 'youtube' ): ?>
            <img src="<?php echo esc_url("https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg");?>" alt="<?php echo esc_attr($video_type);?>">
            <?php else: ?>
                <img src="<?php echo esc_url("https://vumbnail.com/{$video_id}_large.jpg");?>" alt="<?php echo esc_attr($video_type);?>">
            <?php endif; ?>
            <div id="video-bg" class="lightbox">
            <div class="lightbox-container">
                <div class="lightbox-content">

                <button  class="lightbox-close">
                    <?php echo esc_html__('Close | ✕','adirectory');?>
                </button>
                <div class="video-container">
                <?php if( $video_type === 'youtube' ):

                    ?>
                    <iframe id="video-iframe" width="960" height="540" src="<?php echo esc_url("https://www.youtube.com/embed/{$video_id}?showinfo=0");?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <iframe id="video-iframe" width="960" height="540" src="<?php echo esc_url("https://player.vimeo.com/video/{$video_id}?h=006e527e7c&title=0&byline=0&portrait=0");?>" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <img src="<?php echo esc_url("https://vumbnail.com/{$video_id}_large.jpg");?>" alt="<?php echo esc_attr($video_type);?>">
                <?php endif; ?>
                </div>

                </div>
            </div>
            </div>
            <button class="my-video-links">
                <span>
                    <svg width="22" height="26" viewBox="0 0 22 26" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.3363 9.79056L4.72636 0.771905C4.28015 0.50791 3.772 0.366757 3.25355 0.362793C2.43857 0.362793 1.65697 0.686543 1.08069 1.26282C0.504414 1.8391 0.180664 2.6207 0.180664 3.43568V22.7912C0.180768 23.3321 0.325746 23.863 0.600524 24.3288C0.875303 24.7946 1.26985 25.1784 1.74314 25.4401C2.21643 25.7018 2.75119 25.8319 3.29181 25.8169C3.83243 25.802 4.35917 25.6425 4.81727 25.355L20.4454 15.4818C20.9313 15.1777 21.3302 14.7528 21.6031 14.2487C21.876 13.7446 22.0137 13.1784 22.0027 12.6053C21.9917 12.0321 21.8324 11.4716 21.5404 10.9783C21.2483 10.485 20.8335 10.0758 20.3363 9.79056Z" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>
