<?php

/**
 * The template for displaying email footer
 *
 * This template can be overridden by copying it to yourtheme/adirectory/emails/email-footer.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>

<!-- Footer -->
<table width="700" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f4f4; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center"
                        style="padding: 20px 0; font-size: 14px; font-family: Arial, sans-serif; color: #777;">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</td>
</tr>
</table>