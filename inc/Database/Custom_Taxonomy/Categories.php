<?php

namespace ADQS_Directory\Database\Custom_Taxonomy;

use ADQS_Directory\Database\Base\Custom_Taxonomy;

// Traits
use ADQS_Directory\Database\Traits\Taxonomy_Fields\Taxonomy_Upload;
use ADQS_Directory\Database\Traits\Taxonomy_Fields\Taxonomy_Icon;
use ADQS_Directory\Database\Traits\Taxonomy_Fields\Taxonomy_Listing_Type;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Categories class
 */
class Categories extends Custom_Taxonomy
{


	use Taxonomy_Upload;
	use Taxonomy_Icon;
	use Taxonomy_Listing_Type;

	// Taxonomy slug
	private $slug = 'adqs_category';



	/**
	 * Method register_custom_taxonomy
	 *
	 * @return void
	 */
	public function register_custom_taxonomy()
	{

		add_filter('manage_' . $this->slug . '_custom_column', array($this, 'category_rows'), 15, 3);
		add_filter('manage_edit-' . $this->slug . '_columns', array($this, 'category_columns'));
		add_filter('manage_edit-' . $this->slug . '_sortable_columns', array($this, 'add_category_column_sortable'));
		// Load Taxonomy Type Category

		$this->load_taxonomy_listing_type();

		// Load Taxonomy Image Picker
		$this->load_taxonomy_icon();

		// Load Taxonomy Image Upload
		$this->load_taxonomy_image();

		// init taxonomy Settings
		$this->init(
			$this->slug,
			esc_html__('Category', 'adirectory'),
			esc_html__('Categories', 'adirectory'),
			$this->post_types,
			array(
				'show_admin_column' => true,
			)
		);
	}


	/**
	 * Method category_columns
	 *
	 * @param $prev_columns $prev_columns 
	 *
	 * @return void
	 */
	public function category_columns($prev_columns)
	{
		$new_columns = $prev_columns ? $prev_columns : [];
		array_splice($new_columns, 2); // in this way we could place our columns on the first place

		$new_columns[$this->slug . '_image'] = esc_html__('Image', 'adirectory');
		$new_columns[$this->slug . '_icon']  = esc_html__('Icon', 'adirectory');

		$new_columns[$this->slug . '_listing_types'] = esc_html__('Directory Type', 'adirectory');

		return array_merge($new_columns, $prev_columns);
	}


	/**
	 * Method category_rows
	 *
	 * @param $row $row 
	 * @param $column_name $column_name 
	 * @param $term_id $term_id 
	 *
	 * @return string
	 */
	public function category_rows($row, $column_name, $term_id)
	{

		if ($column_name == $this->slug . '_image') {
			$image_id = get_term_meta($term_id, $this->term_upload_name, true);
			$img_url  = wp_get_attachment_image_url($image_id, array(50, 50));
			return !empty($img_url) ? '<img style="max-width:50px;" src="' . esc_url($img_url) . '" alt="#">' : '';
		}
		if ($column_name == $this->slug . '_icon') {
			$icon_name = get_term_meta($term_id, $this->term_icon_name, true);
			return !empty($icon_name) ? '<i class="' . esc_attr($icon_name) . '"></i>' : '';
		}

		if ($column_name == $this->slug . '_listing_types') {
			$directory_type = get_term_meta($term_id, $this->term_listing_name, true);
			if (!empty($directory_type)) {
				$listing_type = array();
				foreach ($directory_type as $term_id) {
					if (is_int($term_id)) {
						$get_type       = get_term_by('term_id', $term_id, 'adqs_listing_types');
						$listing_type[] = !empty($get_type) ? $get_type->slug : '';
					} else {
						$listing_type[] = $term_id;
					}
				}
				return join(', ', $listing_type);
			}
		}

		return $row;
	}


	/**
	 * Method add_category_column_sortable
	 *
	 * @param $sortable $sortable 
	 *
	 * @return array
	 */
	public function add_category_column_sortable($sortable)
	{

		$sortable[$this->slug . '_listing_types'] = esc_html__('Directory Type', 'adirectory');

		return $sortable;
	}
}
