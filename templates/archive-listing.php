<?php

/**
 * The Template for displaying all archive-listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive-listing.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
/**
 * adqs_before_main_content hook.
 *
 * @hooked adqs_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action('adqs_before_main_content');
adqs_get_template_part('content', 'archive-listing');
do_action('adqs_after_main_content');

get_footer();
