<?php

namespace ADQS_Directory\Admin;
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class AdminHelper
{

	public static function setting_by_key($key)
	{
		$settings = json_decode(wp_json_encode(get_option('adqs_admin_settings')), true);

		return $settings;
	}
	public static function listing_count_by_status($status = 'all')
	{
		if ($status === 'all') {
			$args = array(
				'post_type'      => 'adqs_directory',
				'post_status'    => array('publish', 'pending'),
				'posts_per_page' => -1, // Retrieve all posts
				'fields'         => 'ids', // Fetch only post IDs to optimize performance
			);

			$listings_count = count(get_posts($args));

			return (int) $listings_count;
		} else {
			$args = array(
				'post_type'      => 'adqs_directory',
				'post_status'    => $status,
				'posts_per_page' => -1, // Retrieve all posts
				'fields'         => 'ids', // Fetch only post IDs to optimize performance
			);

			$listings_count = count(get_posts($args));

			if ($listings_count) {
				return (int) $listings_count;
			} else {
				return 0;
			}
		}
	}

	public static function listing_count_by_today($status = 'publish')
	{
		$args = array(
			'post_type'      => 'adqs_directory',
			'post_status'    => $status,
			'date_query'     => array(
				array(
					'year'  => current_time('Y'),
					'month' => current_time('m'),
					'day'   => current_time('d'),
				),
			),
			'posts_per_page' => -1, // Retrieve all posts
			'fields'         => 'ids', // Fetch only post IDs to optimize performance
		);

		$listings_count = count(get_posts($args));

		if ($listings_count) {
			return (int) $listings_count;
		} else {
			return 0;
		}
	}
}
