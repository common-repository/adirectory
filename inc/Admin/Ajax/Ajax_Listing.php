<?php

namespace ADQS_Directory\Admin\Ajax;

use ADQS_Directory\Admin\AdminHelper;
use ADQS_Directory\Admin\Setting;



// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class Ajax_Listing extends Ajax_Base
{

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 *
	 * @since 2.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @return object initialized object of class.
	 *
	 * @since 2.0.0
	 */
	public static function get_instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register_ajax_events.
	 *
	 * @return void
	 */
	public function register_ajax_events()
	{

		$ajax_events = array(
			'admin_dashbaord_content',
			'add_terms_and_fields',
			'get_fields_by_term',
			'delete_listing_type',
		);

		$this->init_ajax_events($ajax_events);
	}


	/**
	 * Admin dashboard content
	 */
	public function admin_dashbaord_content()
	{
		if (!check_ajax_referer('adqs___directory_admin', 'security', false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}

		$all_listings_count       = AdminHelper::listing_count_by_status();
		$published_listings_count = AdminHelper::listing_count_by_status('publish');
		$pending_listings_count   = AdminHelper::listing_count_by_status('pending');
		$today_listings_count     = AdminHelper::listing_count_by_today();
		$all_listing_types        = get_terms(
			array(
				'taxonomy'   => 'adqs_listing_types',
				'hide_empty' => false,
			)
		);




		// $settings_nav = array(
		// 	array(
		// 		"title" => "Listing",
		// 		"path"  =>  "listing",
		// 		"sub_settings" => array(
		// 			array(
		// 				"title" => "General",
		// 				"path" =>  "general"
		// 			),
		// 			array(
		// 				"title" => "All Listing",
		// 				"path" =>  "all_listing"
		// 			),
		// 		),
		// 	),
		// 	array(
		// 		"title" => "Search",
		// 		"path"  =>  "search",
		// 		"sub_settings" => array(
		// 			array(
		// 				"title" => "Serach Listing",
		// 				"path" =>  "search_listing"
		// 			),
		// 		),
		// 	),
		// 	array(
		// 		"title" => "Payment Gateway",
		// 		"path"  =>  "payment",
		// 	)
		// );




		// $settings_fields = array(
		// 	array(
		// 		"path"  => "general",
		// 		"input" => "text",
		// 		"label" => "When to send expire notice",
		// 		'option_name' => 'send_expiry_notice'
		// 	),
		// 	array(
		// 		"path"  => "general",
		// 		"input" => "toggle",
		// 		"label" => "Enable Multidirectory",
		// 		'option_name' => 'enable_multi_directory'
		// 	),
		// 	array(
		// 		"path"  => "general",
		// 		"input" => "text",
		// 		"label" => "When to send renewal reminde",
		// 		'option_name' => 'send_renwal_reminder'
		// 	),
		// 	array(
		// 		"path"  => "all_listing",
		// 		"input" => "text",
		// 		"label" => "Filters Button Text",
		// 		'option_name' => 'filter_btn_text'
		// 	),
		// 	array(
		// 		"path"  => "search_listing",
		// 		"input" => "text",
		// 		"label" => "Search Bar Title",
		// 		'option_name' => 'search_bar_title'
		// 	),
		// 	array(
		// 		"path"  => "payment",
		// 		"input" => "text",
		// 		"label" => "Paypal api secret",
		// 		'option_name' => "paypal_api_secret"
		// 	),
		// 	array(
		// 		"path"  => "payment",
		// 		"input" => "checkbox",
		// 		"label" => "Payment Methods",
		// 		'option_name' => 'payment_methods',
		// 		'value' => array(
		// 			'paypal',
		// 			'stripe',
		// 			'paddle'
		// 		),
		// 		'options' =>  array(
		// 			array(
		// 				"label" => "Paypal",
		// 				"value" => "paypal"
		// 			),
		// 			array(
		// 				"label" => "Stripe",
		// 				"value" => "stripe"
		// 			),
		// 			array(
		// 				"label" => "Paddle",
		// 				"value" => "paddle"
		// 			),
		// 		)
		// 	),
		// 	array(
		// 		"path" => "general",
		// 		"input" => "dropdown",
		// 		"label" =>  "Select Dropdown",
		// 		"option_name" =>  "select_dropdown",
		// 		"value" => "dopdown_two",
		// 		"options" => array(
		// 			array(
		// 				"label" =>  "Dropdown One",
		// 				"value" => "dopdown_one",
		// 			),
		// 			array(
		// 				"label" =>  "Dropdown Two",
		// 				"value" => "dopdown_two",
		// 			)
		// 		)

		// 	),
		// 	array(
		// 		"path" => "general",
		// 		"input" => "textarea",
		// 		"label" =>  "Agent Address",
		// 		"option_name" =>  "agent_address",
		// 	),
		// 	array(
		// 		"path" => "general",
		// 		"input" => "colorpicker",
		// 		"label" =>  "Filter color",
		// 		"option_name" =>  "filter_color",
		// 		"value" => "#dddd"
		// 	),
		// 	array(
		// 		"path" => "general",
		// 		"input" => "media",
		// 		"label" =>  "Default Preview Image",
		// 		"option_name" =>  "default_preview_image",
		// 	),


		// );


		$settings_nav = Setting::get_settings_nav();
		$settings_fields = Setting::get_settings_fields();

		$settings_value =  is_array(get_option("adqs_admin_settings")) ? get_option("adqs_admin_settings") : array();

		wp_send_json_success(
			array(
				'stats'     => array(
					'all_count'       => $all_listings_count,
					'published_count' => $published_listings_count,
					'pending_count'   => $pending_listings_count,
					'today_count'     => $today_listings_count,
				),
				'dir_types' => $all_listing_types,
				'settings_nav' =>  $settings_nav,
				'settings_fields' => $settings_fields,
				'settings_value'  => $settings_value
			)
		);
	}


	/**
	 * Ajax handler for getting directory infromation by id
	 * return void
	 */
	public function get_fields_by_term()
	{
		if (!check_ajax_referer('adqs___directory_admin', 'security', false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}
		$builder = get_term_meta(absint(sanitize_text_field($_POST['termid'])), 'adqs_metafields_types', true);
		wp_send_json_success($builder);
	}

	/**
	 * Ajax handler for deleting directory listing type by id
	 */
	public function delete_listing_type()
	{
		if (!check_ajax_referer('adqs___directory_admin', 'security', false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}

		$term_id = absint(sanitize_text_field($_POST['termid']));
		$termid  = wp_delete_term($term_id, 'adqs_listing_types');
		if ($termid) {
			wp_send_json_success();
		}
	}

	/**
	 * Ajax handler for adding directory name and builder with data
	 */
	public function add_terms_and_fields()
	{
		if (!check_ajax_referer('adqs___directory_admin', 'security', false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}
		$status = sanitize_text_field($_POST['status']);
		if ('edit' === $status) {
			$json_to_array = json_decode(map_deep(wp_unslash($_POST['submisiion_fields']), 'sanitize_text_field'), true);
			$termid        = absint(sanitize_text_field($_POST['termid']));

			$termname = sanitize_text_field($_POST['termname']);

			wp_update_term(
				$termid,
				'adqs_listing_types', // Replace with your taxonomy (e.g., 'category', 'post_tag', or custom taxonomy)
				array(
					'name' => $termname,
				)
			);

			update_term_meta($termid, 'adqs_metafields_types', $json_to_array);
			wp_send_json_success();
		} else {
			$json_to_array = json_decode(map_deep(wp_unslash($_POST['submisiion_fields']), 'sanitize_text_field'), true);
			$term_insert_result = wp_insert_term(sanitize_text_field($_POST['termname']), 'adqs_listing_types');
			if (is_wp_error($term_insert_result)) {
				// Handle error
				wp_send_json_error(array('messsage' => $this->get_error_msg('default')));
			} else {
				$termid     = isset($term_insert_result['term_id']) ? absint(sanitize_text_field($term_insert_result['term_id'])) : 0;
				$termicon   = isset($_POST['termicon']) ? sanitize_text_field($_POST['termicon']) : '';
				$termimg   = isset($_POST['termimg']) ? sanitize_text_field($_POST['termimg']) : '';
				$previewimg = isset($_POST['previewimg']) ? sanitize_text_field($_POST['previewimg']) : '';
				update_term_meta($termid, 'adqs_metafields_types', $json_to_array);
				update_term_meta($termid, 'adqs_term_icon', $termicon);
				update_term_meta($termid, 'adqs_term_img', $termimg);
				update_term_meta($termid, 'adqs_term_preview_img', $previewimg);
				wp_send_json_success(['termid' => $termid, 'termname' => sanitize_text_field($_POST['termname'] ?? '')]);
			}
		}
	}
}
