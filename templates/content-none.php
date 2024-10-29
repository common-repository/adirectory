<?php

/**
 * The template for displaying listing content none template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/content-none.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

do_action('adqs_before_listing_taxonomy');
?>
   <div class="qsd-content-none"><?php esc_html_e('Oops! Content Type not found...','adirectory');?>😒</div>
<?php
do_action('adqs_after_listing_taxonomy');
