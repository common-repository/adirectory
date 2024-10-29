<?php

namespace ADQS_Directory\Database\Base;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Custom_Posts class
 */
abstract class Custom_Posts
{

	protected $name         = '';
	protected $menu         = '';
	protected $public_quary = true;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		// Init Custom Post Type
		add_action('init', array($this, 'register_custom_post_type'));
	}


	/**
	 * Method register_custom_post_type
	 *
	 * @return void
	 */
	abstract public function register_custom_post_type();

	/**
	 * init
	 *
	 * @return void
	 */
	public function init($type, $singular_label, $plural_label, $settings = array(), $labels = array())
	{

		$default_labels = array(
			/* translators: %s: Placeholder for the plural label */
			'name'               => esc_html($plural_label),

			/* translators: %s: Placeholder for the singular label */
			'singular_name'      => esc_html($singular_label),

			/* translators: %s: Placeholder for the singular label */
			'add_new'            => sprintf(esc_html__('Add New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the singular label */
			'add_new_item'       => sprintf(esc_html__('Add New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the singular label */
			'edit_item'          => sprintf(esc_html__('Edit %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the singular label */
			'new_item'           => sprintf(esc_html__('New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the plural label */
			'view_item'          => sprintf(esc_html__('New %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the plural label */
			'search_items'       => sprintf(esc_html__('Search %s', 'adirectory'), esc_html($plural_label)),

			/* translators: %s: Placeholder for the plural label */
			'not_found'          => sprintf(esc_html__('No %s', 'adirectory'), esc_html($plural_label)),

			/* translators: %s: Placeholder for the plural label */
			'not_found_in_trash' => sprintf(esc_html__('No %s', 'adirectory'), esc_html($plural_label)),

			/* translators: %s: Placeholder for the singular label */
			'parent_item_colon'  => sprintf(esc_html__('Parent %s', 'adirectory'), esc_html($singular_label)),

			/* translators: %s: Placeholder for the plural label */
			'menu_name'          => esc_html($plural_label),
		);

		$default_settings = array(

			'labels'        => array_merge($default_labels, $labels),
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => '',
			'menu_position' => 10,
			'supports'      => array(
				'title',
				'editor',
				'thumbnail',
			),
			'rewrite'       => array(
				'slug' => sanitize_title_with_dashes($plural_label),
			),
		);
		register_post_type($type, array_merge($default_settings, $settings));


		register_post_status('unread', array(
			'label'                     => _x('Expired', 'expire listing'),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop('Expired (%s)', 'Expired (%s)'),
		));
	}
}
