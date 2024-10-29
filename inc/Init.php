<?php

use ADQS_Directory\Addons;
use ADQS_Directory\Frontend;
use ADQS_Directory\Database;
use ADQS_Directory\Admin;
use ADQS_Directory\Formhandler;
use ADQS_Directory\Admin\DefaultDatas;
use ADQS_Directory\Admin\Setting;
use ADQS_Directory\CookiesHandler;
use ADQS_Directory\Helpers;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * The main init plugin class
 */

final class ADQS_Init
{


	public $Helper;
	public $Cookie;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	private function __construct()
	{

		$this->Helper = Helpers::getInstance();
		$this->Cookie = CookiesHandler::getInstance();

		// plugin activation hook
		register_activation_hook(ADQS_DIRECTORY_FILE, array($this, 'plugin_activate'));

		$this->init_appsero_sdk();
		$this->update_setting();

		// when plugin loaded
		add_action('plugins_loaded', array($this, 'init_plugin'));
		add_action('init', array($this, 'permalinks_flushed'));
		add_action('init', array($this, 'blocks_init'), 10090);
	}


	public function init_appsero_sdk()
	{
		if (! class_exists('Appsero\Client')) {
			require_once __DIR__ . '/appsero/src/Client.php';
		}

		$client = new Appsero\Client('f5f86071-9f3d-43e2-b259-602b34155ffd', 'aDirectory - Directory Listing WordPress Plugin', ADQS_DIRECTORY_FILE);

		$client->insights()->init();
	}


	/**
	 * Method blocks_init
	 *
	 * @return void
	 */

	public function blocks_init()
	{
		$this->load_translation();
		if (!get_option('adqs_admin_settings')) {
			$adqs_settings_array =  Setting::get_settings_fields();

			$admin_setting = array();

			if (is_array($adqs_settings_array)) {
				foreach ($adqs_settings_array as $single_setting) {
					if (isset($single_setting['option_name'])) {
						$admin_setting[$single_setting['option_name']] = isset($single_setting['value']) ? $single_setting['value'] : '';
					}
				}
				update_option('adqs_admin_settings', $admin_setting);
			}

			update_option('adqs_admin_settings', $admin_setting);
		}

		$checkHasTerms = (term_exists('general', 'adqs_listing_types') || get_option('adqs_term_builder_default')) ? true : false;
		if (!$checkHasTerms) {

			$id             = wp_insert_term('General', 'adqs_listing_types');
			$deafult_fields = DefaultDatas::builder();
			update_term_meta(absint($id['term_id']) ?? 0, 'adqs_metafields_types', $deafult_fields);
			update_term_meta(absint($id['term_id']) ?? 0, 'adqs_term_icon', 'fas fa-map');
			update_term_meta(absint($id['term_id']) ?? 0, 'adqs_term_default_expiry', '15');

			update_option('adqs_term_builder_default', 1);
		}
	}



	/**
	 * Method plugin activate
	 *
	 * @return void
	 */
	public function plugin_activate()
	{


		update_option('adqs_permalinks_flushed', 0);
		$this->adqs_onborading_pages();
	}

	public function update_setting()
	{
		$option = get_option('adqs_admin_settings', array());

		$adqs_email_templates = DefaultDatas::email_templates();

		if (!array_key_exists('select_currency', $option)) {
			$option['select_currency'] = 'USD__$__before';
		}

		if (!array_key_exists('adqs_admin_templates', $option)) {
			$option['adqs_admin_templates'] = $adqs_email_templates;
		}

		if (!array_key_exists('adqs_admin_emails_triggers', $option)) {
			$option['adqs_admin_emails_triggers'] = ['new_user_reg', 'new_listing_sub', 'new_listing_up', 'order_created', 'order_completed'];
		}

		if (!array_key_exists('adqs_user_emails_triggers', $option)) {
			$option['adqs_user_emails_triggers'] = array('new_user_reg', 'new_listing_sub', 'listing_is_approved', 'listing_about_expire', 'listing_expired', 'order_created', 'order_completed');
		}

		if (!array_key_exists('from_email_name', $option)) {
			$option['from_email_name'] = get_bloginfo('name');
		}

		if (!array_key_exists('from_email_address', $option)) {
			$option['from_email_address'] = get_option('admin_email');
		}

		if (!array_key_exists('enable_bank_transfer', $option)) {
			$option['enable_bank_transfer'] = 1;
		}

		update_option('adqs_admin_settings', $option);
	}
	/**
	 * Method adqs_onborading_pages
	 *
	 * @return void
	 */
	public function adqs_onborading_pages()
	{
		$option_key = 'adqs_onboarding_pages';
		if (empty(get_option($option_key))) {
			$insertPages = DefaultDatas::onborading_pages();

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
				update_option($option_key, $allPages);
			}
		}
	}



	/**
	 * Method permalinks_flushed
	 *
	 * @return void
	 */
	public function permalinks_flushed()
	{
		if (!get_option('adqs_permalinks_flushed')) {
			flush_rewrite_rules(false);
			update_option('adqs_permalinks_flushed', 1);
		}
	}



	/**
	 * Initializes a singleton instance
	 */

	public static function instance()
	{
		static $instance = false;

		if (!$instance) {
			$instance = new self();
		}

		return $instance;
	}


	/**
	 * Include Files
	 */
	public function inlcude_files()
	{
		if (file_exists(ADQS_DIRECTORY_INC . '/Functions.php')) {
			require_once ADQS_DIRECTORY_INC . '/Functions.php';
		}
	}


	/**
	 * Load translation for both server and client side
	 */
	public function load_translation()
	{

		load_plugin_textdomain('adirectory', false, dirname(ADQS_DIRECTORY_PLUGIN_BASE) . '/languages');
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin()
	{

		// load functions php files
		$this->inlcude_files();

		// loaded all required plugin
		new Frontend();
		new Addons();
		new Database();

		new Formhandler();
		if (is_admin()) {
			new Admin();
		}
	}
}

/* ADQS_Directory Loder */


function AD()
{
	return ADQS_Init::instance();
}
AD();
