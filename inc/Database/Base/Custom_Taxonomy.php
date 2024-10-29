<?php

namespace ADQS_Directory\Database\Base;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Custom_Taxonomy Base class
 */
abstract class Custom_Taxonomy
{



	// Post Types
	protected $post_types = 'adqs_directory';

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		// Init Custom Post Type
		add_action('init', array($this, 'register_custom_taxonomy'));
	}


	/**
	 * Method register_custom_taxonomy
	 *
	 * @return void
	 */
	abstract public function register_custom_taxonomy();

	/**
	 * Method init
	 *
	 * @return void
	 */
	public function init($type, $singular_label, $plural_label, $post_types, $settings = array(), $labels = array())
	{

		$default_labels = array(
			/* translators: Placeholder for the plural label */
			'name'                  => esc_html($plural_label),

			/* translators: Placeholder for the singular label */
			'singular_name'         => esc_html($singular_label),

			/* translators: Placeholder for the singular label */
			'add_new_item'          => sprintf(esc_html__('Add New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the singular label */
			'new_item_name'         => sprintf(esc_html__('Add New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the singular label */
			'edit_item'             => sprintf(esc_html__('Edit %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the singular label */
			'update_item'           => sprintf(esc_html__('Update %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the plural label */
			'add_or_remove_items'   => sprintf(esc_html__('Add or remove %s', 'adirectory'), esc_html(strtolower($plural_label))),

			/* translators: Placeholder for the plural label */
			'search_items'          => sprintf(esc_html__('Search %s', 'adirectory'), esc_html($plural_label)),

			/* translators: Placeholder for the plural label */
			'popular_items'         => sprintf(esc_html__('Popular %s', 'adirectory'), esc_html($plural_label)),

			/* translators: Placeholder for the plural label */
			'all_items'             => sprintf(esc_html__('All %s', 'adirectory'), esc_html($plural_label)),

			/* translators: Placeholder for the singular label */
			'parent_item'           => sprintf(esc_html__('Parent %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the plural label */
			'choose_from_most_used' => sprintf(esc_html__('Choose from the most used %s', 'adirectory'), esc_html(strtolower($plural_label))),

			/* translators: Placeholder for the plural label */
			'parent_item_colon'     => sprintf(esc_html__('Parent %s', 'adirectory'), esc_html($singular_label)),

			/* translators: Placeholder for the plural label */
			'menu_name'             => esc_html($plural_label),
		);

		$default_settings = array(

			'labels'            => array_merge($default_labels, $labels),
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => true,
			'show_tagcloud'     => false,
			'show_ui'           => true,
			'rewrite'           => array(
				'slug' => sanitize_title_with_dashes($plural_label),
			),
			'capabilities' => array(
				'manage_terms' => 'manage_categories',
				'edit_terms' => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'read'
			)
		);
		register_taxonomy($type, $post_types, array_merge($default_settings, $settings));
	}
}
