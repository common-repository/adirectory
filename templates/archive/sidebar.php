<?php

/**
 * The template for displaying listing sidebar in the archive-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive/sidebar.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_active_sidebar('adqs_archive_listings')) : ?>
    <div class="qsd-prodcut-grid-with-side-bar-item">

        <?php
        /**
         * Dynamic archive listings page sidebar
         */
        dynamic_sidebar('adqs_archive_listings');

        ?>
    </div>
<?php endif; ?>
