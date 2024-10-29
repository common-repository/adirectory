<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}
/**
 * Method adqs_listing_query_filter_args
 *
 * @param $queryArgs
 *
 * @return array
 */
if (!function_exists('adqs_listing_query_filter_args')) {


	function adqs_listing_query_filter_args($queryArgs = [])
	{


		$defaults = [
			'category' => $_GET['category'] ?? '',
			'location' => $_GET['location'] ?? '',
			'directory_type' => $_GET['directory_type'] ?? '',
			'tags' => $_GET['tags'] ?? '',
			'ls' => $_GET['ls'] ?? '',
			'minPrice' => $_GET['minPrice'] ?? '',
			'maxPrice' => $_GET['maxPrice'] ?? '',
			'rating' => $_GET['rating'] ?? '',
			'display_listings' => $_GET['display_listings'] ?? '',
		];

		$args = wp_parse_args($queryArgs, $defaults);

		$category       = $args['category'];
		$location       = $args['location'];
		$directory_type = $args['directory_type'];
		$tags           = $args['tags'];
		$search         = $args['ls'];
		$minprice       = $args['minPrice'];
		$maxprice       = $args['maxPrice'];
		$rating         = $args['rating'];
		$display_listings = $args['display_listings'];



		$tax_query  = [];
		$meta_query = [];

		if (!empty($directory_type)) {
			$directory_type = explode(',', $directory_type);
			$directory_ids = [];
			if (is_array($directory_type)) {
				foreach ($directory_type as $d_type) {
					$directory_type_id = get_term_by('slug', $d_type, 'adqs_listing_types');
					$directory_ids[] = !empty($directory_type_id->term_id) ? $directory_type_id->term_id : 0;
				}
			}

			if (!empty($directory_ids)) {
				$meta_query[] = array(
					'key'     => 'adqs_directory_type',
					'value'   => $directory_ids,
					'compare' => 'IN'
				);
			}
		}

		if (!empty($search)) {
			$args['s'] = urlencode_deep(preg_replace('/[^a-zA-Z0-9\s]/', '', $search));
		}


		if (($minprice >= 0) && !empty($maxprice)) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array($minprice, $maxprice),
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			);
		}

		if (!empty($rating)) {
			$meta_query[] = array(
				'key'     => 'adqs_avg_ratings',
				'value'   => $rating,
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);
		}

		if (!empty($display_listings)) {
			switch ($display_listings) {
				case 'featured':
					$meta_query[] = array(
						'key'     => '_is_featured',
						'value'   => 'yes',
					);
					break;
			}
		}

		if (!empty($category)) {
			$tax_query[] = array(
				'taxonomy' => 'adqs_category',
				'field'    => 'slug',
				'terms'    => explode(',', $category),
				'operator' => 'IN',
			);
		}

		if (!empty($location)) {
			$tax_query[] = array(
				'taxonomy' => 'adqs_location',
				'field'    => 'slug',
				'terms'    => explode(',', $location),
				'operator' => 'IN',
			);
		}

		if (!empty($tags)) {
			$tax_query[] = array(
				'taxonomy' => 'adqs_tags',
				'field'    => 'term_id',
				'terms'    => array_map('absint', explode(',', $tags)),
				'operator' => 'IN',
			);
		}

		if (count($tax_query) > 0) {
			if (count($tax_query) > 1) {
				$tax_query['relation'] = 'AND';
			}
			$args['tax_query'] = $tax_query;
		}

		if (count($meta_query) > 0) {
			if (count($meta_query) > 1) {
				$meta_query['relation'] = 'AND';
			}
			$args['meta_query'] = $meta_query;
		}
		return $args;
	}
} // end



/**
 * Method adqs_listing_query_sort_by
 *
 * @param $sort_by $sort_by [explicite description]
 *
 * @return array
 */
if (!function_exists('adqs_listing_query_sort_by')) {


	function adqs_listing_query_sort_by($sort_by = '')
	{
		if (!empty($_GET['sort_by'] ?? '')) {
			$sort_by = $_GET['sort_by'];
		}
		if (empty($sort_by)) {
			return false;
		}

		$sortBySet = [];

		switch ($sort_by) {
			case 'date-asc':
				$sortBySet['order'] = 'ASC';
				break;
			case 'review-count':
				$sortBySet['orderby'] = 'comment_count';
				break;
			case 'rating-desc':
				$sortBySet['meta_key'] = 'adqs_avg_ratings';
				$sortBySet['orderby']  = 'meta_value_num';
				$sortBySet['order']    = 'DESC';
				break;
			case 'title-asc':
				$sortBySet['orderby'] = 'title';
				$sortBySet['order']   = 'ASC';
				break;
			case 'title-desc':
				$sortBySet['orderby'] = 'title';
				$sortBySet['order']   = 'DESC';
				break;
			case 'price-asc':
				$sortBySet['meta_key'] = '_price';
				$sortBySet['orderby']  = 'meta_value_num';
				$sortBySet['order']    = 'ASC';
				break;
			case 'price-desc':
				$sortBySet['meta_key'] = '_price';
				$sortBySet['orderby']  = 'meta_value_num';
				$sortBySet['order']    = 'DESC';
				break;
			case 'rand':
				$sortBySet['orderby'] = 'rand';
				break;
			case 'views-desc':
				$sortBySet['meta_key'] = '_view_count';
				$sortBySet['orderby']  = 'meta_value_num';
				$sortBySet['order']    = 'DESC';
				break;
		}

		return !empty($sortBySet) ? $sortBySet : array();
	}
}// end
