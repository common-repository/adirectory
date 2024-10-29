<?php

namespace ADQS_Directory;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Assets handlers class
 */
class Database {


	/**
	 * Class constructor
	 */
	function __construct() {
		self::register_services();
	}
	/**
	 * Store all the classes inside an array
	 *
	 * @return array Full list of classes
	 */
	public static function get_services() {
		return array(
			// Custom Post Type
			Database\Custom_Posts\Directory::class,

			// Custom Taxonomy
			Database\Custom_Taxonomy\Categories::class,
			Database\Custom_Taxonomy\Locations::class,
			Database\Custom_Taxonomy\Tags::class,
			Database\Custom_Taxonomy\ListingTypes::class,

			// Custom Metabox
			Database\Custom_Metabox\Directory_Type::class,
			Database\Custom_Metabox\Directory_Type_Frontend::class,

			// Setting
			// Database\Helpers\AdminHelper::class,

			// Custom Widgets
			Database\Custom_Widgets\Register_Widgets::class,
		);
	}


	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 *
	 * @return
	 */
	public static function register_services() {
		foreach ( self::get_services() as $class ) {
			self::instantiate( $class );
		}
	}


	/**
	 * Initialize the class
	 *
	 * @param  /class from the services array
	 * @return /class instance  new instance of the class
	 */
	private static function instantiate( $class ) {
		if ( class_exists( $class ) ) {
			$service = new $class();
			return $service;
		}
	}
}
