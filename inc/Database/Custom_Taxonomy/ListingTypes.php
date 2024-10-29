<?php

namespace ADQS_Directory\Database\Custom_Taxonomy;

use ADQS_Directory\Database\Base\Custom_Taxonomy;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * ListingTypes class
 */
class ListingTypes extends Custom_Taxonomy
{


	private $slug = 'adqs_listing_types';

	/**
	 * Method register_custom_taxonomy
	 *
	 * @return void
	 */
	public function register_custom_taxonomy()
	{

		$settings = array('show_ui' => false);
		$this->init(
			$this->slug,
			esc_html__('Listing Type', 'adirectory'),
			esc_html__('Listing Types', 'adirectory'),
			$this->post_types,
			$settings
		);
	}
}
