<?php

/**
 * QS Directories Template Hooks
 *
 * Action/filter hooks used for QS Directories /templates.
 *
 * @package QS Directories\Templates
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Content Wrappers.
 *
 * @see adqs_output_content_wrapper()
 * @see adqs_output_content_wrapper_end()
 */
add_action('adqs_before_main_content', 'adqs_output_content_wrapper_start', 10);
add_action('adqs_after_main_content', 'adqs_output_content_wrapper_end', 10);

/**
 * Single Page Elements
 *
 * @see adqs_single_listing_slider()
 * @see adqs_single_listing_details()
 * @see adqs_single_listing_meta()
 * @see adqs_single_listing_title()
 * @see adqs_single_listing_description()
 * @see adqs_single_listing_fileds_elements()
 * @see adqs_single_listing_review()
 * @see adqs_single_listing_contact_author()
 */

add_action('adqs_single_listing_elements', 'adqs_single_listing_slider', 10);
add_action('adqs_single_listing_elements', 'adqs_single_listing_details', 11);
add_action('adqs_single_listing_elements', 'adqs_single_listing_related', 12);
add_action('adqs_single_listing_details', 'adqs_single_listing_meta', 10);
add_action('adqs_single_listing_details', 'adqs_single_listing_title', 11);
add_action('adqs_single_listing_details', 'adqs_single_listing_description', 12);
add_action('adqs_single_listing_details', 'adqs_single_listing_fileds_elements', 13);
add_action('adqs_single_listing_details', 'adqs_single_listing_review', 14);



// add_action('qs/single/info', 'adqs_single_info');
// Listing Loop Start
// add_action('adqs_listing_loop_before', 'adqs_listing_loop_start');

add_action('adqs_grid_thumnail_btn_group', 'adqs_fav_btn_add_to_grid', 15, 1);

//Mail sending hooks

add_action('adqs_after_new_registration', 'adqs_mail_to_admin');
add_action('adqs_after_new_registration', 'adqs_mail_to_user');
add_action("adqs_new_listing_submitted", 'adqs_new_listing_mail_admin');
add_action("adqs_new_listing_submitted", 'adqs_new_listing_mail_user');
add_action("post_updated", 'adqs_updated_listing_mail', 10, 3);
add_action("transition_post_status", "adqs_post_status_change_mail", 10, 3);
add_action("adqs_order_created_mail_to_both", "adqs_order_created_mail_to_both", 10, 3);
add_action("adqs_order_completed_mail_to_both", "adqs_order_completed_mail_to_both", 10, 3);
