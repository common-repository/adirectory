<?php

namespace ADQS_Directory\Admin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Assets handlers class
 */
class Assets
{


	/**
	 * Class constructor
	 */
	function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'assetsloading'));
	}


	/**
	 * Load Admin scripts and style
	 */
	public function assetsloading()
	{
		// current page id
		$currentPageId = !empty(get_current_screen()->id) ? get_current_screen()->id : '';
		$pages_only    = array('adqs_directory_page_adqs_directory_builder');

		if (in_array($currentPageId, $pages_only)) {

			$builderdeps = array(
				'dependencies' => '',
				'version'      => 1.0,
			);

			if (file_exists(ADQS_DIRECTORY_BUILD_DIR_PATH . '/directorybuilder/directorybuilder.asset.php')) {
				$builderdeps = include_once ADQS_DIRECTORY_BUILD_DIR_PATH . '/directorybuilder/directorybuilder.asset.php';
			}

			wp_enqueue_script('qsd-directory-builder', ADQS_DIRECTORY_BUILD_DIR_URL . '/directorybuilder/directorybuilder.js', $builderdeps['dependencies'], $builderdeps['version'], true);

			wp_set_script_translations('qsd-directory-builder', 'adirectory', ADQS_DIRECTORY_DIR_PATH . 'languages');

			wp_localize_script(
				'qsd-directory-builder',
				'qsdObj',
				array(
					'admin_route'     => wp_parse_url(admin_url(), PHP_URL_PATH),
					'admin_ajax'      => admin_url('admin-ajax.php'),
					'rest_url'        => rest_url(),
					'site_url' => site_url(),
					'adqs_admin_nonce' => wp_create_nonce('adqs___directory_admin'),
					'adqs_assets_path' => ADQS_DIRECTORY_ASSETS_URL . '/admin/img/',
				)
			);

			wp_enqueue_media();

			wp_enqueue_style('adqs_admin_styles', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/admin-dashboard.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			wp_enqueue_style('adqs_dashboard_setting', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/dashboard-settings.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			wp_enqueue_style('adqs_react_toastify', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/react-toast.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			wp_enqueue_style('adqs_tailwind_default', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/tailwind-default.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			wp_enqueue_style('adqs_font_awesome', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/fontawesome-all.min.css', array(), ADQS_DIRECTORY_VERSION, 'all');
		}

		// register some script and style

		wp_register_style('adqs_admin_main', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/admin.main.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_script('adqs_admin_main', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/admin-main.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);

		$admin_pages_only = array('widgets');
		if (in_array($currentPageId, $admin_pages_only)) {
			wp_enqueue_script('qsd-multichecbox-dropdown', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/multichecbox-dropdown.js', array('jquery', 'wp-i18n'), ADQS_DIRECTORY_VERSION, true);
			wp_enqueue_style('adqs_admin_main');
			wp_enqueue_script('adqs_admin_main');
		}

		// csv export import
		if ($currentPageId === 'adqs_directory_page_adqs_export_import') {
			wp_enqueue_style('adqs_export_import', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/csv-export-import.css', array(), ADQS_DIRECTORY_VERSION, 'all');
			wp_enqueue_script('adqs_export_import', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/csv-export-import.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);
			wp_localize_script('adqs_export_import', 'adqsExIm', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('import_export_nonce'),
			]);
		}
	}
}
