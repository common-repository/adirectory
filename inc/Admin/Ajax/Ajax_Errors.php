<?php
namespace ADQS_Directory\Admin\Ajax;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax_Errors {


	private static $instance;
	private static $errors = array();

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {

		self::$errors = array(
			'permission' => esc_html__( 'Sorry, you are not allowed to do this operation.', 'adirectory' ),
			'nonce'      => esc_html__( 'Nonce validation failed', 'adirectory' ),
			'default'    => esc_html__( 'Sorry, something went wrong.', 'adirectory' ),
		);
	}

	/**
	 * Get error message.
	 *
	 * @param string $type Message type.
	 * @return string
	 */
	public function get_error_msg( $type ) {

		if ( ! isset( self::$errors[ $type ] ) ) {
			$type = 'default';
		}

		return self::$errors[ $type ];
	}
}

Ajax_Errors::get_instance();
