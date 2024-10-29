<?php

namespace ADQS_Directory\Frontend;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Assets handlers class
 */
class Assets
{

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'assetsloading'));
		add_action('adqs_ajax_search', array($this, 'assetsloading_ajax_search'));
	}


	/**
	 * Method assetsloading
	 *
	 * @return void
	 */
	public function assetsloading()
	{

		//Elementor widgets

		if (class_exists('Elementor\Plugin')) {
			wp_register_style('adqs-top-misc', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/widgets/top-misc.css', array(), ADQS_DIRECTORY_VERSION, 'all');
		}

		wp_enqueue_style('adqs-top-misc');

		// User and agent frontend dashbaord

		$frontdashdeps = array(
			'dependencies' => '',
			'version'      => 1.0,
		);

		if (file_exists(ADQS_DIRECTORY_BUILD_DIR_PATH . '/userdashboard/userdashboard.asset.php')) {
			$frontdashdeps = include_once ADQS_DIRECTORY_BUILD_DIR_PATH . '/userdashboard/userdashboard.asset.php';
		}

		wp_register_script('qs-frontdashdeps', ADQS_DIRECTORY_BUILD_DIR_URL . '/userdashboard/userdashboard.js', $frontdashdeps['dependencies'], $frontdashdeps['version'], true);


		wp_localize_script(
			'qs-frontdashdeps',
			'userDashObj',
			array(
				'admin_ajax'     => admin_url('admin-ajax.php'),
				'rest_url'       => rest_url(),
				'user_dash_nonce'       => wp_create_nonce('__qs_directory_userdash'),
				'qsd_assets_path' => ADQS_DIRECTORY_ASSETS_URL . '/admin/img/',
				'is_logged_in'   => is_user_logged_in(),
				'user_role'     =>  wp_get_current_user()->roles,
				'current_user_id' => get_current_user_id(),
				'activated_plugins' => get_option('active_plugins'),
				'addon_status'   => true,
				'add_listing_perm' => adqs_get_permalink_by_key('adqs_add_listing')
			)
		);

		wp_register_style('qs-frontdash-tailwind', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/userdash-default.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style('qs-frontdash-css', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/userdash.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_enqueue_editor();



		//Check if the page is frontend dashbaord page

		//Login and registration asset

		wp_register_style("adqs-user-log-regi", ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/user-log-regi.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style("adqs_all_agents", ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/all-agents.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_script("adqs-user-log-regi", ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/user-log-regi.js', array(), ADQS_DIRECTORY_VERSION, true);

		wp_register_style('slick', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/slick.css', array(), ADQS_DIRECTORY_VERSION, 'all');
		wp_register_style('slick-init', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/slick-init.css', array(), ADQS_DIRECTORY_VERSION, 'all');
		// Script load
		wp_register_script('slick', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/slick.min.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);
		wp_register_script('slick-init', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/slick-init.js', array('slick'), ADQS_DIRECTORY_VERSION, true);




		$pages = get_option("adqs_onboarding_pages", array());

		$addlisting = $pages['adqs_add_listing'] ?? 0;
		$currentPageId = get_queried_object_id();

		if ($addlisting === $currentPageId) {

			//front list submit
			wp_enqueue_style('qsd-front-list-submit', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/front-list-submit.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			//leaflet
			wp_enqueue_style('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/leaflet.css', array(), ADQS_DIRECTORY_VERSION);

			// leaflet map
			wp_enqueue_script('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/leaflet.js', array(), ADQS_DIRECTORY_VERSION, true);

			//add lsit front
			wp_enqueue_script('qsd-add-list-front', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/front-add-listing.js', array(), ADQS_DIRECTORY_VERSION, true);


			wp_localize_script(
				'qsd-add-list-front',
				'frontaddObj',
				array(
					'admin_ajax'     => admin_url('admin-ajax.php'),
					'rest_url'       => rest_url(),
					'front_dash_list_nonce'       => wp_create_nonce('__qs_front_dash_list_nonce'),
					'qsd_assets_path' => ADQS_DIRECTORY_ASSETS_URL . '/admin/img/',
				)
			);
		}


		// enqueue and register style
		wp_enqueue_style('font-awesome-free', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/fontawesome-all.min.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style('tabler', ADQS_DIRECTORY_ASSETS_URL . '/admin/extra/icon-picker/stylesheets/tabler-icons.min.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style('adqs_single_grid', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/grid-global.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style('adqs_taxonomy_archive', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/listing-taxonomy.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_enqueue_style('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/leaflet.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_enqueue_style('leaflet-cluster', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/leaflet-cluster.css', array('leaflet'), ADQS_DIRECTORY_VERSION, 'all');

		wp_register_style(
			'nou-slider',
			ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/nouslider.min.css',
			array(),
			ADQS_DIRECTORY_VERSION,
			'all'
		);



		// other script
		wp_enqueue_script('leaflet', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/leaflet.js', array(), ADQS_DIRECTORY_VERSION, true);

		wp_register_script('img_svg_inline', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/img-svg-to-inline.js', array(), ADQS_DIRECTORY_VERSION, true);

		// enqueue and register script

		wp_enqueue_script('leaflet-cluster', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/leaflet-cluster.js', array('leaflet'), ADQS_DIRECTORY_VERSION, true);

		// range slider
		wp_register_script('nou-slider', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/nouslider.min.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);
		wp_register_script('adqs_price_range_slider', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/price_range_slider.js', array('jquery', 'nou-slider'), ADQS_DIRECTORY_VERSION, true);

		wp_register_script('grid-page-script', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/grid-page-script.js', array(), ADQS_DIRECTORY_VERSION, true);

		wp_localize_script(
			'grid-page-script',
			'adqsGridPage',
			array(
				'ajaxurl'  => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce('adqs___grid_page'),
				'login_msg' => esc_html__('Please login first.', 'adirectory'),
			)
		);



		if (is_singular('adqs_directory')) {


			wp_enqueue_style('simple-lightbox', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/simple-lightbox.min.css', array(), ADQS_DIRECTORY_VERSION, 'all');
			wp_enqueue_style('video-popup', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/video-popup.css', array(), ADQS_DIRECTORY_VERSION, 'all');


			wp_enqueue_style('single-listing-style', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/single-listing-style.css', array('dashicons'), ADQS_DIRECTORY_VERSION, 'all');




			wp_enqueue_style('adqs_single_page', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/single-page.css', array(), ADQS_DIRECTORY_VERSION, 'all');

			wp_enqueue_script('simple-lightbox', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/simple-lightbox.jquery.min.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);

			wp_enqueue_script('single-script', ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/single-script.js', array('jquery', 'wp-i18n'), ADQS_DIRECTORY_VERSION, true);

			wp_localize_script(
				'single-script',
				'qsSingleData',
				array(
					'ajaxurl'  => admin_url('admin-ajax.php'),
					'security' => wp_create_nonce('adqs___directory_frontend'),
				)
			);
		} // end single page

		// is post type archive
		if (adqs_is_listing_archive()) {

			wp_enqueue_style('adqs_single_grid');

			wp_enqueue_script('img_svg_inline');
		}

		// author page
		if (adqs_is_author_listing_archive()) {
			wp_enqueue_style('adqs_author_infos', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/author.css', array(), ADQS_DIRECTORY_VERSION, 'all');
		}

		wp_enqueue_style('adqs_single_grid');


		$get_alllistingspage_id = absint(get_option('adqs_all_listings_page', 0));
		if ($get_alllistingspage_id === get_the_ID()) {
			wp_enqueue_style('adqs_single_grid');
		}

		// enqueue main styles and scripts
		wp_enqueue_style('adqs_main', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/main.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		//Libary css for frontend
		wp_register_style('adqs-toast', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/lib/toast.css', array(), ADQS_DIRECTORY_VERSION, 'all');
	}

	// ajax search
	public function assetsloading_ajax_search()
	{
		wp_enqueue_style('adqs_ajax_search', ADQS_DIRECTORY_ASSETS_URL . '/frontend/css/ajax-search.css', array(), ADQS_DIRECTORY_VERSION, 'all');

		wp_enqueue_script('jquery.ba-throttle-debounce', ADQS_DIRECTORY_ASSETS_URL . '/admin/js/jquery.ba-throttle-debounce.js', array('adqs_ajax_search'), ADQS_DIRECTORY_VERSION, true);
		wp_enqueue_script("adqs_ajax_search", ADQS_DIRECTORY_ASSETS_URL . '/frontend/js/ajax-search.js', array('jquery'), ADQS_DIRECTORY_VERSION, true);
	}
}
