<?php

/**
 * The template for displaying listing price in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/title.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php if(!empty(get_the_title())):?>
<h1 class="listing-grid-details-heading">
    <?php the_title();?>
</h1>
<?php endif;?>
