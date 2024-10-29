<?php

/**
 * The template for displaying listing slider in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/slider.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$sliderImages = get_post_meta(get_the_ID(), '_images', true);
if( !empty($sliderImages) && is_array($sliderImages)):

?>

<section class="adqs-signle-listing-HeroSection">
  <div class="container">
    <div class="adqs-gallary">
          <?php
          foreach($sliderImages as $key => $imgId):
            $getImgSrc = wp_get_attachment_image_url( absint($imgId), 'full' );
            $relId = $key+1;
          ?>
          <a href="<?php echo esc_url($getImgSrc);?>" class="adqs-gitem <?php echo esc_attr($relId > 3 ? 'hidden' : '');?>" 
          <?php if($relId > 3):?>
            rel="rel<?php echo esc_attr($relId);?>"
          <?php endif;?>
          >
              <img src="<?php echo esc_url($getImgSrc);?>" alt="#" />
              <?php if($relId === 3): ?>
                <div class="adqs-gSeeAll">
                <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.222 9.20837L12.2627 7.79765C11.2445 7.0646 9.85313 7.14036 8.92062 7.97962L6.19006 10.4371C5.25755 11.2764 3.86614 11.3521 2.84801 10.6191L0.888672 9.20837M3.55534 14.5417H11.5553C13.0281 14.5417 14.222 13.3478 14.222 11.875V3.87504C14.222 2.40228 13.0281 1.20837 11.5553 1.20837H3.55534C2.08258 1.20837 0.888672 2.40228 0.888672 3.87504V11.875C0.888672 13.3478 2.08258 14.5417 3.55534 14.5417ZM6.88867 5.54171C6.88867 6.46218 6.14248 7.20837 5.22201 7.20837C4.30153 7.20837 3.55534 6.46218 3.55534 5.54171C3.55534 4.62123 4.30153 3.87504 5.22201 3.87504C6.14248 3.87504 6.88867 4.62123 6.88867 5.54171Z" stroke="white" stroke-linecap="round"/>
                </svg>
                <span><?php echo esc_html__('See all ','adirectory');?>(<?php echo esc_html(count($sliderImages));?>)</span>
                </div>
              <?php endif; ?>
          </a>
        <?php endforeach; ?>  
        
      </div>
    </div>  
</section>
<!-- end of hero slider -->

<?php else:
	$preview_image = wp_get_attachment_url( get_post_thumbnail_id(),'full' );
	$preview_image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
	if( !empty($preview_image) ):
?>
<div class="qsd-single-preview"><img src="<?php echo esc_url($preview_image);?>" alt="<?php echo esc_attr($preview_image_alt);?>"></div>

	<?php endif;?>
<?php endif;?>
<!-- listing-grid end -->


