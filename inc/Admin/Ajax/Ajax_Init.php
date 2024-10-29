<?php
namespace ADQS_Directory\Admin\Ajax;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Ajax_Init.
 */
class Ajax_Init {

	private $dynamic_properties = array();
	public function __construct() {

		$this->initialize_hooks();
	}
	public function initialize_hooks() {
		$this->register_all_ajax_events();
	}

	public function __set( $name, $value ) {
		$this->dynamic_properties[ $name ] = $value;
	}

	public function __get( $name ) {
		return $this->dynamic_properties[ $name ] ? $this->dynamic_properties[ $name ] : null;
	}

	public function register_all_ajax_events() {

		$controllers = array(
			'ADQS_Directory\Admin\Ajax\Ajax_Setting',
			'ADQS_Directory\Admin\Ajax\Ajax_Listing',
		);

		foreach ( $controllers as $controller ) {
			$this->$controller = $controller::get_instance();
			$this->{$controller}->register_ajax_events();
		}
	}
}
