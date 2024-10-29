<?php

namespace ADQS_Directory\Addons;


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Api handlers class
 * Since 2,0
 */
class ElementorAddon
{

	/**
	 * Class constructor
	 */

	public function __construct()
	{
		// elementor categories
		add_action('elementor/elements/categories_registered', array($this, 'widget_categories'));

		// elementor editor css
		add_action('elementor/editor/after_enqueue_scripts', function () {
			wp_enqueue_style('adqs-elemntor-editor', ADQS_DIRECTORY_ASSETS_URL . '/admin/css/elemntor-editor.css', array(), ADQS_DIRECTORY_VERSION);
		});
		add_action('elementor/widgets/widgets_registered', array($this, 'widgets_registered'));
	}


	/**
	 * Widgets elements categories
	 *
	 * @since   1.0.0
	 */
	public function widget_categories($elements_manager)
	{
		$elements_manager->add_category(
			'adqs-category',
			[
				'title' => __('ADirectory', 'adirectory'),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * Widgets elements
	 *
	 * @since   1.0.0
	 */
	public function widgets_registered()
	{
		$directory = ADQS_DIRECTORY_INC . '/Addons/elementor/widgets' . '/*.php';
		$phpFiles = glob($directory);

		foreach ($phpFiles as $file) {
			if (file_exists($file)) {
				require_once($file);
			}
		}
	}
}
