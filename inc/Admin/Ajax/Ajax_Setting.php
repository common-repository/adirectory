<?php

namespace ADQS_Directory\Admin\Ajax;

use ADQS_Directory\Admin\Setting;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class Ajax_Setting extends Ajax_Base
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
			'get_initial_settings',
			'save_all_settings',
			'gen_common_asset',
			'gen_single_page'
		);

		$this->init_ajax_events($ajax_events);
	}


	public function save_all_settings()
	{
		if (!check_ajax_referer('adqs___directory_admin', 'security', false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}

		$settings = json_decode(stripslashes_deep($_POST['settings']), true);

		$old_value = get_option('adqs_admin_settings');

		if ($settings === $old_value || maybe_serialize($settings) === maybe_serialize($old_value)) {
			wp_send_json_success(
				array(
					"status" => false,
					'message' => esc_html__('Same value already exist', 'adirectory'),
				)
			);
		}

		$update_settings = update_option('adqs_admin_settings', $settings);

		if ($update_settings) {
			wp_send_json_success(
				array(
					"status" => true,
					'message' => esc_html__('Settings saved successfully', 'adirectory'),
				)
			);
		}

		wp_send_json_error(
			array(
				"status" => false,
				'message' =>  $this->get_error_msg('default'),
			)
		);
	}
	public function gen_common_asset()
	{
		$this->check_permission_nonce('adqs___directory_admin');
		$option_key = 'adqs_onboarding_pages';
		$existed_pages = get_option($option_key, []);

		$insertPages = array(
			array(
				'post_title'   => esc_html__('All Listings', 'adirectory'),
				'post_content' => '[adqs_listings]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_all_listing',
			),
			array(
				'post_title'   => esc_html__('All Location', 'adirectory'),
				'post_content' => '[adqs_taxonomies tax_name="adqs_location"]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_all_locations',
			),
			array(
				'post_title'   => esc_html__('All Categories', 'adirectory'),
				'post_content' => '[adqs_taxonomies tax_name="adqs_category"]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_all_categories',
			),
			array(
				'post_title'   => esc_html__('Add Listing', 'adirectory'),
				'post_content' => '[adqs_add_listing]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_add_listing',
			),
			array(
				'post_title'   => esc_html__('User Dashboard', 'adirectory'),
				'post_content' => '[adqs_dashboard]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_user_dashboard',
			),
			array(
				'post_title'   => esc_html__('Login - Registration', 'adirectory'),
				'post_content' => '[adqs_user_log_regi]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_login_regi',
			),
			array(
				'post_title'   => esc_html__('All Agents', 'adirectory'),
				'post_content' => '[adqs_agents]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_agents',
			),
		);

		$pageKeys = array_column($insertPages, 'page_key');

		foreach ($pageKeys as $key => $value) {
			if (array_key_exists($value, $existed_pages) &&  !get_post($existed_pages[$value]) !== null) {
				wp_delete_post($existed_pages[$value]);
			}
		}

		$allPages = [];

		if (is_array($insertPages) && !empty($insertPages)) {
			foreach ($insertPages as $insert) {
				$get_page_id = wp_insert_post(array(
					'post_title'   => sprintf(esc_html__("%s", "adirectory"), $insert['post_title']),
					'post_content' => $insert['post_content'],
					'post_status'  => $insert['post_status'],
					'post_type'    => $insert['post_type'],
				));
				if (!empty($get_page_id)) {
					$allPages[$insert['page_key']] = $get_page_id;
				}
			}
		}
		if (!empty($allPages)) {
			update_option($option_key, array_merge($existed_pages, $allPages));
		}

		wp_send_json_success("Success");
	}

	public function gen_single_page()
	{
		$this->check_permission_nonce('adqs___directory_admin');
		$option_key = 'adqs_onboarding_pages';
		$existed_pages = get_option($option_key, []);

		$page_key = sanitize_key($_POST['page_key']);
		$page_title = sanitize_text_field($_POST['page_title']);
		$page_content = sanitize_text_field($_POST['page_content']);


		if (array_key_exists($page_key, $existed_pages) && !get_post($existed_pages[$page_key]) !== null) {
			wp_delete_post($existed_pages[$page_key]);
		}

		$pageid = wp_insert_post(array(
			'post_title'   => $page_title,
			'post_content' => "[$page_content]",
			'post_status'  => 'publish',
			'post_type'    => 'page',
		));

		$update = update_option($option_key, array_merge($existed_pages, array($page_key => (int) $pageid)));

		if ($update) {
			wp_send_json_success('Success');
		} else {
			wp_send_json_error();
		}
	}

	public function get_initial_settings()
	{
		$this->check_permission_nonce('adqs___directory_admin');

		// $value = $this->check_post_value();
		$options = is_array(get_option("adqs_admin_settings")) ? get_option("adqs_admin_settings") : array();


		$settings_nav = Setting::get_settings_nav();
		$settings_fields = Setting::get_settings_fields();

		if ($options) {

			wp_send_json_success(
				array(
					'message' => 'Settings fetched successfully',
					'settings'    => $options,
					'settings_nav' =>  $settings_nav,
					'settings_fields' => $settings_fields,
				)
			);
		}
		wp_send_json_error(array('messsage' => $this->get_error_msg('default')));
	}
}
