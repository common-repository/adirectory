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
} ?>

<div class="adqs-no-plan">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mt-10">
                <?php _e("You don't have any active plan", "adirectory"); ?>
            </div>
        </div>
    </div>

</div>