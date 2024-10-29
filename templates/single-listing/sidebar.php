<?php

/**
 * The template for displaying listing sidebar in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/sidebar.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_active_sidebar( 'adqs_single_listing' ) ) : ?>
<div class="listing-grid-details-right qsd-grid-details-sidebar">

    <?php
    /**
     * Dynamic Single Listing page sidebar
     */
    dynamic_sidebar( 'adqs_single_listing' );

    ?>
</div>
<?php endif; ?>
