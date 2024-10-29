<?php

namespace ADQS_Directory\Database\Custom_Widgets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Register_Widgets class
 */
class Register_Widgets
{


	/**
	 * Class Contructor
	 */
	public function __construct()
	{
		// Init Register Widgets
		add_action('widgets_init', array($this, 'register_widgets'));
	}

	/**
	 * Register all sidebars for frontend template
	 *
	 * @return array Full list of sidebars
	 */
	public static function register_sidebar()
	{
		return array(
			array(
				'name'          => esc_html__('AD - Single Listing Sidebar', 'adirectory'),
				'id'            => 'adqs_single_listing',
				'description'   => esc_html__('Add widgets for the sidebar on single listing page.', 'adirectory'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '</h2>',
			),
			array(
				'name'          => esc_html__('AD - Archive Listings Sidebar', 'adirectory'),
				'id'            => 'adqs_archive_listings',
				'description'   => esc_html__('Add widgets for the sidebar on archive listings page.', 'adirectory'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '</h2>',
			),
		);
	}

	/**
	 * Store all the classes inside an array
	 *
	 * @return array Full list of classes
	 */
	public static function get_widgets()
	{
		return array(
			All_Widgets\Connect_Agent::class,
			All_Widgets\Listings_By::class,
			All_Widgets\Populer_Listings::class,
			All_Widgets\Related_Listings::class,
			All_Widgets\Categories::class,
			All_Widgets\Locations::class,
			All_Widgets\Tags::class,
			All_Widgets\Advanced_Sidebar_Filter::class,
		);
	}

	/**
	 * Register Widgets and Sidebar
	 *
	 * @return void
	 */
	public function register_widgets()
	{

		// Register all custom widgets
		foreach (self::get_widgets() as $class) {
			if (class_exists($class)) {
				register_widget($class);
			}
		}

		// Register all custom sidebar
		foreach (self::register_sidebar() as $sidebar) {
			register_sidebar($sidebar);
		}
	}
}
