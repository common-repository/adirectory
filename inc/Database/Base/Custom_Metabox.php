<?php

namespace ADQS_Directory\Database\Base;

use ADQS_Directory\Database\Custom_Metabox\Field_Helper;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Custom_Metabox class
 */
abstract class Custom_Metabox
{


	public $Field_Helper = null;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		$this->Field_Helper = AD()->Helper;
		// Init Custom Post Type
		add_action('add_meta_boxes', array($this, 'register_custom_metabox'));
		add_action('save_post', array($this, 'save_metabox_data'));
	}


	/**
	 * Method register_custom_metabox
	 *
	 * @return void
	 */
	abstract public function register_custom_metabox();


	/**
	 * Method save_metabox_data
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	abstract public function save_metabox_data($post_id);
}
