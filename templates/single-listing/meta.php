<?php

/**
 * The template for displaying listing meta in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/qsd-directories/single-listing/meta.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$Helper = AD()->Helper;
$view_count = $Helper->get_view_count(get_the_ID());
$author_id = get_the_author_meta( 'ID' );
$post_type = get_post_type();
$author_posts_url = adqs_listing_author_url($author_id, $post_type);
?>
<div class="listing-grid-details-price-btn-item qsd-meta_wrap">
    <h3 class="listing-grid-details-price">
    <?php
        adqs_get_template_part( 'global/price' );

    ?>
    </h3>
    <div class="listing-grid-details-btn-item">

        <div class="listing-grid-details-btn adqs-autor-inner">
            <a class="connect-agents-inner-thumb" href="<?php echo esc_url( $author_posts_url ); ?>">
                <?php echo get_avatar($author_id,80);?>
            </a>
            <a class="connect-agents-inner-txt" href="<?php echo esc_url( $author_posts_url ); ?>">
                <h4 class="connect-agents-txt"><?php the_author(); ?></h4>
            </a> 
            <a class="connect-author-verify-batch" href="<?php echo esc_url( $author_posts_url ); ?>">
                <?php do_action('adqs_after_author',$author_id);?>
            </a>
        </div>
        <!-- View Count -->
        <?php if( !empty($view_count) ):?>
        <span class="listing-grid-details-btn qsd-meta-view-count">
            <i class="dashicons dashicons-welcome-view-site"></i>
            <?php echo esc_html($view_count);?>
        </span>
        <?php endif; ?>

        <!-- Social Share -->
        <?php echo do_shortcode('[adqs_social_share]');?>
        <?php adqs_get_template_part( 'global/expire','notice' ) ;?>


        <!-- <a href="#" class="listing-grid-details-btn">
            <img src="<?php // echo esc_url(ADQS_DIRECTORY_ASSETS_URL);?>/frontend/img/flag.svg" alt="img">
            <?php //echo esc_html__('Report','adirectory');?>

        </a> -->
    </div>
</div>
