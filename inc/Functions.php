<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}


$functions_path = ADQS_DIRECTORY_FUNCTIONS . '/*.php';

$filenames = glob($functions_path);
if (!empty($filenames) && is_array($filenames)) {
	foreach ($filenames as $name_slug) {
		if (file_exists($name_slug)) {
			include_once $name_slug;
		}
	}
}


/**
 * get all directory types
 */
if (!function_exists('adqs_get_directory_types')) {
	function adqs_get_directory_types($taxonomy = 'adqs_listing_types', $hide_empty = false)
	{
		$directory_type = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => $hide_empty,
			)
		);
		return $directory_type;
	}
} // end

/**
 * get all directory types
 */
if (!function_exists('adqs_get_listing_fields')) {
	function adqs_get_listing_fields($term_id = 0)
	{
		$term_vals = get_term_meta($term_id, 'adqs_metafields_types', true);
		return $term_vals;
	}
}

if (!function_exists('adqs_fetch_listing_default_image')) {
	function adqs_fetch_listing_default_image($termid)
	{
		$default_image = get_term_meta($termid, 'adqs_term_preview_img', true);
		return $default_image;
	}
}
/**
 *
 */
if (!function_exists('adqs_single_page_fields_render')) {
	function adqs_single_page_fields_render($post_id)
	{
		$sections = adqs_get_listing_fields(get_post_meta($post_id, 'adqs_directory_type', true));
		return $sections;
	}
}

// Grid post meta functions


if (!function_exists('adqs_get_common_listing_meta')) {
	function adqs_get_common_listing_meta($post_id, $metakey, $single = true)
	{
		return get_post_meta($post_id, $metakey, $single);
	}
}

if (!function_exists('adqs_get_listing_type_info')) {
	function adqs_get_listing_type_info($post_id, $entity)
	{
		$listingtype   = get_post_meta($post_id, 'adqs_directory_type', true);
		$listytypename = get_term((int) $listingtype)->name;
		if ($entity === 'name') {
			return $listytypename;
		} else {
			$listingtypeicon = get_term_meta((int) $listingtype, 'adqs_term_icon', true);
			return $listingtypeicon;
		}
	}
}
/**
 * Undocumented function
 *
 * @param [type] $taxonomy
 * @return void
 */
if (!function_exists('adqs_render_repeated_tax')) {
	function adqs_render_repeated_tax($post_id, $tax = 'adqs_category', $display_item = 2)
	{
		$terms = get_the_terms($post_id, $tax) ? get_the_terms($post_id, $tax) : array();
		// return array_slice($terms, 0, $display_item);
		return $terms;
	}
}
/**
 * Undocumented function
 *
 * @param [type] $taxonomy
 * @return void
 */
if (!function_exists('adqs_get_listing_feature')) {
	function adqs_get_listing_feature($post_id)
	{
		$feature_image = get_the_post_thumbnail_url($post_id);
		if ($feature_image) {
			return $feature_image;
		}
	}
}
/**
 * Undocumented function
 *
 * @param [type] $taxonomy
 * @return void
 */
if (!function_exists('adqs_get_terms')) {

	function adqs_get_terms($taxonomy, $args = array())
	{
		return get_terms(
			array_merge(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
				),
				$args
			)
		);
	}
}

/**
 * Method adqs_directory_type_nav_url
 *
 * @param $type $type [explicite description]
 * @param $base_url $base_url [explicite description]
 *
 * @return void
 */
if (!function_exists('adqs_directory_type_nav_url')) {

	function adqs_directory_type_nav_url($type = 'all', $base_url = null)
	{
		if (empty($base_url)) {
			$base_url = remove_query_arg(array('page', 'paged'));
			$base_url = preg_replace('~/page/(\d+)/?~', '', $base_url);
			$base_url = preg_replace('~/paged/(\d+)/?~', '', $base_url);
		}

		$url = add_query_arg(array('directory_type' => $type), $base_url);

		return $url;
	}
}
/**
 * Method adqs_post_paged
 *
 * @return integer
 */
if (!function_exists('adqs_post_paged')) {

	function adqs_post_paged()
	{
		global $paged;
		if (get_query_var('paged')) {
			$post_page = get_query_var('paged');
		} else {
			if (get_query_var('page')) {
				$post_page = get_query_var('page');
			} else {
				$post_page = 1;
			}
			set_query_var('paged', $post_page);
			$paged = $post_page;
		}
		return $paged;
	}
}
/**
 * Method adqs_post_pagination
 *
 * @param $query $query [explicite description]
 *
 * @return string [explicite description]
 */
if (!function_exists('adqs_post_pagination')) {

	function adqs_post_pagination($query = null)
	{
		global $wp_query;
		$big            = 999999999;
		$paginate_links = paginate_links(
			array(
				'base'         => str_replace($big, '%#%', get_pagenum_link($big)),
				'total'        => ($query != null) ? $query->max_num_pages : $wp_query->max_num_pages,
				'current'      => max(1, get_query_var('paged')),
				'format'       => '?paged=%#%',
				'show_all'     => false,
				'type'         => 'list',
				'end_size'     => 2,
				'mid_size'     => 1,
				'prev_next'    => true,
				'prev_text'    => '<i class="fa-solid fa-angle-left"></i>',
				'next_text'    => '<i class="fa-solid fa-angle-right"></i>',
				'add_args'     => false,
				'add_fragment' => '',
			)
		);
		if (!empty($paginate_links)) {
			echo wp_kses_post($paginate_links);
		} else {
			echo '';
		}
	}
}

/**
 * is listing archive
 */
if (!function_exists('adqs_is_listing_archive')) {
	function adqs_is_listing_archive()
	{
		$post_type = 'adqs_directory';
		$category  = 'adqs_category';
		$location  = 'adqs_location';
		$tag       = 'adqs_tags';
		if (is_post_type_archive($post_type) || is_tax($category) || is_tax($location) || is_tax($tag) || adqs_is_author_listing_archive()) {
			return true;
		} else {
			return false;
		}
	}
} // end


/**
 * is Authr Archive
 */
if (!function_exists('adqs_is_author_listing_archive')) {
	function adqs_is_author_listing_archive()
	{
		$is_listings = $_GET['listings'] ?? '';
		if (is_author() && (($is_listings === 'yes') || (get_post_type() === 'adqs_directory'))) {
			return true;
		} else {
			return false;
		}
	}
} // end


/**
 * Method adqs_listing_author_url
 *
 * @param $author_id $author_id [explicite description]
 * @param $post_type $post_type [explicite description]
 *
 * @return string
 */
if (!function_exists('adqs_listing_author_url')) {

	function adqs_listing_author_url($author_id = 0, $post_type = '')
	{
		if (empty($post_type)) {
			return '';
		}

		$author_posts_url = get_author_posts_url($author_id);
		if (preg_match('/author\/([a-zA-Z0-9]+)/', $author_posts_url) && preg_match('/%postname%/', get_option('permalink_structure'))) {
			return !empty($author_posts_url) ? trailingslashit($author_posts_url) . 'directory' : '';
		} else {
			return !empty($author_posts_url) ? add_query_arg('listings', 'yes', $author_posts_url) : '';
		}
	}
}
