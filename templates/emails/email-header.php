<?php

/**
 * The template for displaying email header
 *
 * This template can be overridden by copying it to yourtheme/adirectory/emails/email-header.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>
<table width="100%">
    <tr>
        <td align="center">
            <table width="700" border="0" cellspacing="0" cellpadding="0"
                style="background-color: #2B69FA; padding: 20px;">
                <tr>
                    <td align="center">
                        <table width="600" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="padding: 20px 0;">
                                    <a href="<?php echo home_url(); ?>" target="_blank">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"
                                    style="padding: 20px 0; font-size: 24px; font-family: Arial, sans-serif; color: #333;">
                                    <strong><?php bloginfo('name'); ?></strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>