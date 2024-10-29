<?php
/**
 * The template for displaying listing slider in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/details.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
$post_id = !empty($post->ID) ?  $post->ID : 0;
?>
<div class="listing-grid-details">
        <div class="container">
            <div class="listing-grid-details-df">
                <div class="listing-grid-details-main">
                <?php
                    /**
                     * Hook: adqs_single_listing_details.
                     *
                     * @hooked adqs_single_listing_meta - 10
                     * @hooked adqs_single_listing_title - 11
                     * @hooked adqs_single_listing_description - 12
                     * @hooked adqs_single_listing_fileds_elements - 13
                     * @hooked adqs_single_listing_review - 14
                     * 
                     */
                    do_action( 'adqs_single_listing_details' );
                    ?>

                </div>

                
                <?php
                    /**
                     *  Single listing sidebar
                     */ 
                    adqs_get_template_part( 'single-listing/sidebar' );
                ?>      
                
            </div>
        </div>
    </div>