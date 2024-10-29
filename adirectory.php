<?php

/**
 * Plugin Name: aDirectory - Directory Listing WordPress Plugin
 * Plugin URI: 	https://profiles.wordpress.org/adirectory
 * Author:  adirectory
 * Author URI: 	http://adirectory.io
 * Description: Directory Plugins that help to build Business Directory, Classified listing and WordPress Listing Directory websites.
 * Version: 	1.3.3
 * Requires at least: 6.0
 * Tested up to: 6.6.2
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: adirectory
 * Domain Path: /languages
 */


if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

// defined all require dir and path
define('ADQS_DIRECTORY_VERSION', '1.3');
define('ADQS_DIRECTORY_FILE', __FILE__);
define('ADQS_DIRECTORY_PLUGIN_BASE', plugin_basename(ADQS_DIRECTORY_FILE));
define('ADQS_DIRECTORY_URL', plugins_url('/', ADQS_DIRECTORY_FILE));
define('ADQS_DIRECTORY_DIR_PATH', plugin_dir_path(ADQS_DIRECTORY_FILE));
define('ADQS_DIRECTORY_ASSETS_URL', ADQS_DIRECTORY_URL . 'assets');
define('ADQS_DIRECTORY_INC', ADQS_DIRECTORY_DIR_PATH . 'inc');
define('ADQS_DIRECTORY_FUNCTIONS', ADQS_DIRECTORY_INC . '/functions');
define('ADQS_DIRECTORY_BUILD_DIR_PATH', ADQS_DIRECTORY_DIR_PATH . 'build');
define('ADQS_DIRECTORY_BUILD_DIR_URL', ADQS_DIRECTORY_URL . 'build');
define('ADQS_LISTING_POST_TYPE', 'adqs_directory');
define('ADQS_LISTING_TYPE_TAX', 'adqs_listing_types');


if (file_exists(ADQS_DIRECTORY_DIR_PATH . '/vendor/autoload.php') && file_exists(ADQS_DIRECTORY_INC . '/Init.php')) {
	require_once ADQS_DIRECTORY_DIR_PATH . '/vendor/autoload.php';
	require_once ADQS_DIRECTORY_INC . '/Init.php';
}
