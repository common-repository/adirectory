<?php

namespace ADQS_Directory\Admin\Ajax;

use ADQS_Directory\Admin\Ajax\Ajax_Errors;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

abstract class Ajax_Base
{

	private $prefix = 'adqs';

	public $errors = null;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct()
	{
		$this->errors = Ajax_Errors::get_instance();
	}

	/**
	 * Register ajax events.
	 *
	 * @param array $ajax_events Ajax events.
	 */
	public function init_ajax_events($ajax_events)
	{

		if (!empty($ajax_events)) {

			foreach ($ajax_events as $ajax_event) {
				add_action('wp_ajax_' . $this->prefix . '_' . $ajax_event, array($this, $ajax_event));

				// $this->localize_ajax_action_nonce( $ajax_event );
			}
		}
	}

	/**
	 * Localize nonce for ajax call.
	 *
	 * @param string $action Action name.
	 * @return void
	 */
	public function localize_ajax_action_nonce($action)
	{

		if (current_user_can('manage_options')) {

			add_filter(
				'adqs_localize',
				function ($localize) use ($action) {

					$localize[$action . '_nonce'] = wp_create_nonce($this->prefix . '_' . $action);
					return $localize;
				}
			);
		}
	}


	/**
	 * Get ajax error message.
	 *
	 * @param string $type Message type.
	 * @return string
	 */
	public function get_error_msg($type)
	{

		return $this->errors->get_error_msg($type);
	}


	/**
	 * Checks if the user has the permission to perform the requested action and verifies the nonce.
	 *
	 * @param string $option The name of the option to check the nonce against.
	 * @param string $scope The capability required to perform the action. Default is 'manage_options'.
	 * @param string $security The security to check the nonce against. Default is 'security'.
	 * @return void
	 *
	 * @since 2.5.0
	 */
	public function check_permission_nonce($option, $scope = 'manage_options', $security = 'security')
	{
		if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['security'])), 'adqs___directory_admin')) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('permission')));
		}

		if (!current_user_can($scope)) {
			wp_send_json_error(array('messsage' => esc_html__("You can't do the action.", "adirectory")));
		}
		/**
		 * Nonce verification
		 */
		if (!check_ajax_referer($option, $security, false)) {
			wp_send_json_error(array('messsage' => $this->get_error_msg('nonce')));
		}
	}




	/**
	 * Save setting - Sanitizes form inputs.
	 *
	 * @param array $input_settings setting data.
	 * @return array    The sanitized form inputs.
	 */
	public function sanitize_form_inputs($input_settings = array())
	{
		$new_settings = array();

		if (!empty($input_settings)) {
			foreach ($input_settings as $key => $value) {

				$new_key = sanitize_text_field($key);

				if (is_array($value)) {
					$new_settings[$new_key] = $this->sanitize_form_inputs($value);
				} else {
					$new_settings[$new_key] = sanitize_text_field($value);
				}
			}
		}

		return $new_settings;
	}
}
