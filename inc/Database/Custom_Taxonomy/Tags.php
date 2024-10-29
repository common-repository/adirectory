<?php

namespace ADQS_Directory\Database\Custom_Taxonomy;

use ADQS_Directory\Database\Base\Custom_Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Tags class
 */
class Tags extends Custom_Taxonomy {


	// Taxonomy slug
	private $slug = 'adqs_tags';



		
	/**
	 * Method register_custom_taxonomy
	 *
	 * @return void
	 */
	public function register_custom_taxonomy() {

		// init taxonomy Settings
		$settings = array( 'hierarchical' => false );
		$this->init(
			$this->slug,
			esc_html__( 'Tag', 'adirectory' ),
			esc_html__( 'Tags', 'adirectory' ),
			$this->post_types,
			$settings
		);
	}
}
