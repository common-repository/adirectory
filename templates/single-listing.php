<?php
/**
 * The Template for displaying all single Listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

	<?php
		/**
		 * adqs_before_main_content hook.
		 *
		 * @hooked adqs_output_content_wrapper_start - 10 (outputs opening divs for the content)
		 */
		do_action( 'adqs_before_main_content' );
	?>

		<?php 
		if ( have_posts() ) :
		while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php adqs_get_template_part( 'content', 'single-listing' ); ?>

		<?php endwhile; // end of the loop. 
		endif;
		wp_reset_postdata();  // avoid errors further down the page
		/**
		 * adqs_after_main_content hook.
		 *
		 * @hooked adqs_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'adqs_after_main_content' );

get_footer();

