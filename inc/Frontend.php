<?php

namespace ADQS_Directory;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Admin handlers class
 */
class Frontend
{

	/**
	 * Class constructor
	 */


	function __construct()
	{
		self::register_services();
	}
	/**
	 * Store all the classes inside an array
	 *
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		return array(
			Frontend\Shortcode::class,
			Frontend\TemplateLoader::class,
			Frontend\Assets::class,
			Frontend\Ajax::class,
			Frontend\Customize::class,
			// custom post type
			/*
			CPT\Directory::class,
			CPT\DirectoryT::class, */

		);
	}


	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 *
	 * @return
	 */
	public static function register_services()
	{
		foreach (self::get_services() as $class) {
			self::instantiate($class);
		}
	}



	/**
	 * Initialize the class
	 *
	 * @param  /class from the services array
	 * @return /class instance  new instance of the class
	 */
	private static function instantiate($class)
	{
		if (class_exists($class)) {
			$service = new $class();
			return $service;
		}
	}
}
