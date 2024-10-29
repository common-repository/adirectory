<?php

namespace ADQS_Directory\Database\Custom_Posts;

use ADQS_Directory\Database\Base\Custom_Posts;


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Directory class
 */


class Directory extends Custom_Posts
{


	private $slug = 'adqs_directory';

	/**
	 * Method register_custom_post_type
	 *
	 * @return void
	 */
	public function register_custom_post_type()
	{
		add_filter("manage_{$this->slug}_posts_columns", array($this, 'custom_table_columns'));
		add_action("manage_{$this->slug}_posts_custom_column", array($this, 'custom_table_custom_column'), 10, 2);
		add_filter("manage_taxonomies_{$this->slug}_columns", array($this, 'manage_table_taxonomies'));

		add_filter("manage_edit-{$this->slug}_sortable_columns", array($this, 'sortable_columns'));

		$this->name = esc_html__('Listing', 'adirectory');
		$this->menu = esc_html__('All Listings', 'adirectory');
		$settings   = array(
			'menu_icon'          => ADQS_DIRECTORY_ASSETS_URL . '/admin/img/menu-icon.png',
			'supports'           => array('title', 'thumbnail', 'editor', 'comments'),
			'rewrite'            => array('slug' => $this->slug),
			'publicly_queryable' => $this->public_quary,
			'show_in_menu'       => true,
			'rewrite'            => array(
				'slug' => esc_html__('directory', 'adirectory'),
			),
		);
		$labels     = array(
			'name'      => esc_html__('Listings', 'adirectory'),
			'all_items' => esc_html__('All Listings', 'adirectory'),
			'menu_name' => esc_html__('aDirectory', 'adirectory'),
		);
		$this->init(
			$this->slug,
			$this->name,
			$this->menu,
			$settings,
			$labels
		);
	}



	/**
	 * Method custom_table_columns
	 *
	 * @param $columns $columns 
	 *
	 * @return void
	 */
	public function custom_table_columns($columns)
	{
		$columns = array(
			'cb'                    => $columns['cb'],
			'title'                 => esc_html__('Name', 'adirectory'),
			'preview_image'         => esc_html__('Preview Image', 'adirectory'),
			'directory'             => esc_html__('Directory', 'adirectory'),
			'taxonomy-adqs_location' => esc_html__('Location', 'adirectory'),
			'taxonomy-adqs_category' => esc_html__('Categories', 'adirectory'),
			'status'                => esc_html__('Status', 'adirectory'),
			'author'                => esc_html__('Author', 'adirectory'),
			'date'                  => esc_html__('Create Date', 'adirectory'),
		);
		return $columns;
	}
	/**
	 * Method sortable_columns
	 *
	 * @return void
	 */
	public function sortable_columns()
	{
		$columns['author'] = esc_html__('Author', 'adirectory');
		return $columns;
	}
	/**
	 * Method custom_table_custom_column
	 *
	 * @param $column $column 
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function custom_table_custom_column($column, $post_id)
	{
		switch ($column) {
			case 'preview_image':
				echo get_the_post_thumbnail($post_id, array(50, 50));
				break;
			case 'directory':
				$getDirTypeID = get_post_meta($post_id, 'adqs_directory_type', true);
				$getDirTypeID = !empty($getDirTypeID) ? absint($getDirTypeID) : 0;
				$term         = get_term_by('id', $getDirTypeID, 'adqs_listing_types');
				echo !empty($term->name) ? esc_html($term->name) : '';
				break;
			case 'status':
				echo esc_html(ucfirst(get_post_status($post_id)));
				break;
		}
	}
	/**
	 * Method manage_table_taxonomies
	 *
	 * @return array
	 */
	public function manage_table_taxonomies()
	{
		$taxonomies[] = 'adqs_location';
		$taxonomies[] = 'adqs_category';
		return $taxonomies;
	}
}
