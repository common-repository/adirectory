<?php

/**
 * The template for displaying listing content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/content-single-listing.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Hook: adqs_before_single_listing.
 *
 * @hooked adqs_output_all_notices - 10
 */
do_action( 'adqs_before_single_listing' );

if ( post_password_required() ) {
	echo wp_kses_post(get_the_password_form());
	return;
}
?>
<div id="listing-<?php the_ID(); ?>" <?php adqs_listing_classes(['qsd-single_listing']); ?>>

	<?php
	/**
	 * Hook: adqs_single_listing_elements.
	 *
	 * @hooked adqs_single_listing_slider - 10
	 * @hooked adqs_single_listing_details - 11
	 */
	do_action( 'adqs_single_listing_elements' );

	?>
</div>

<?php do_action( 'adqs_after_single_listing' ); ?>
