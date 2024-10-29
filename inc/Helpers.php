<?php

namespace ADQS_Directory;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
class Helpers
{

	// Hold the class instance
	private static $instance = null;

	// The static method to get the singleton instance
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * dd
	 */
	public function dd($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}


	/**
	 * check data
	 *
	 * @param [type] $data
	 * @param [type] $key
	 * @return bool
	 */
	public function check_data($data, $key)
	{
		return (isset($data[$key]) && !empty($data[$key])) ? true : false;
	}

	/**
	 * Required input attributes
	 *
	 * @param [type] $data
	 * @return void
	 */
	public function required($data)
	{
		echo $this->check_data($data, 'is_required') ? 'required="required"' : '';
	}

	public function required_html($data)
	{
		echo $this->check_data($data, 'is_required') ? '<span class="adqs_required_mark"> *</span>' : '';
	}

	/**
	 * is admin only
	 */
	public function admin_view($data)
	{
		return $this->check_data($data, 'admin_view') ? current_user_can('manage_options') : true;
	}

	/**
	 * Get Data
	 */
	public function get_data($data = array(), $key = '', $default = '')
	{
		return $this->check_data($data, $key) ? $data[$key] : $default;
	}

	/**
	 * Meta Value for adding
	 */
	public function meta_val($post_id = 0, $meta_key = '', $default = '')
	{
		return !empty(get_post_meta($post_id, $meta_key, true)) ? get_post_meta($post_id, $meta_key, true) : $default;
	}

	/**
	 * Meta Post Data
	 */
	public function post_data($post = array(), $meta_key = '', $default = '')
	{
		return isset($post[$meta_key]) ? $post[$meta_key] : $default;
	}

	/**
	 * Get File name From Folder
	 */
	public function file_name_from_folder($path = '', $ext = 'php')
	{
		$filenames = glob($path);

		$filenames = array_map(
			function ($path) use ($ext) {
				return basename($path, ".{$ext}");
			},
			$filenames
		);
		return $filenames;
	}

	/**
	 * Text to slug generate
	 *
	 * @param [type] $str
	 * @param string $delimiter
	 * @return string
	 */
	public function text_to_slug($str, $delimiter = '-')
	{

		$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
		return $slug;
	}

	/**
	 * determine Video Url Type
	 *
	 * @param [type] $url
	 * @return array
	 */
	public function determineVideoUrlType($url)
	{
		if (empty($url)) {
			return array();
		}

		$yt_rx             = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
		$has_match_youtube = preg_match($yt_rx, $url, $yt_matches);

		$vm_rx           = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/';
		$has_match_vimeo = preg_match($vm_rx, $url, $vm_matches);

		// Then we want the video id which is:
		if ($has_match_youtube) {
			$video_id = $yt_matches[5] ?? '';
			$type     = 'youtube';
		} elseif ($has_match_vimeo) {
			$video_id = $vm_matches[5] ?? '';
			$type     = 'vimeo';
		} else {
			$video_id = 0;
			$type     = 'none';
		}

		$data['video_id']   = $video_id;
		$data['video_type'] = $type;

		return $data;
	}

	/**
	 * Get review rating
	 *
	 * @return $maxPrice
	 */
	public function get_author_ratings($author_id = 0)
	{
		if (empty($author_id)) {
			return;
		}

		global $wpdb;

		$authlrRatings = wp_cache_get('adqs_authlrRatings', 'adqs_cache');
		if ($authlrRatings === false) {
			$authlrRatings = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT AVG(meta_value) FROM $wpdb->postmeta WHERE meta_key='adqs_avg_ratings' AND post_id IN(SELECT ID FROM $wpdb->posts WHERE post_type='adqs_directory' AND post_status='publish' AND post_author=%d)",
					$author_id
				)
			);
			wp_cache_set('adqs_authlrRatings', $authlrRatings, 'adqs_cache');
		}

		return !empty($authlrRatings) ? round($authlrRatings, 1) : false;
	}


	/**
	 * Get review count
	 *
	 * @return $maxPrice
	 */
	public function get_author_review_count($author_id = 0)
	{
		if (empty($author_id)) {
			return;
		}

		global $wpdb;

		$authlrReviewCount = wp_cache_get('adqs_authlrReviewCount', 'adqs_cache');
		if ($authlrReviewCount === false) {

			$authlrReviewCount = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID IN(SELECT ID FROM $wpdb->posts WHERE post_type='adqs_directory' AND post_status='publish' AND post_author=%d)",
					$author_id
				)
			);
			wp_cache_set('adqs_authlrReviewCount', $authlrReviewCount, 'adqs_cache');
		}

		return !empty($authlrReviewCount) ? $authlrReviewCount : false;
	}



	/**
	 * Get Max Price
	 *
	 * @return $maxPrice
	 */
	public function get_maxPrice()
	{
		global $wpdb;

		$maxPrice = wp_cache_get('adqs_maxPrice', 'adqs_cache');
		if ($maxPrice === false) {
			$maxPrice = $wpdb->get_var("SELECT MAX(CAST(meta_value AS DECIMAL(10, 0))) AS max_numeric_price FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value REGEXP '^[0-9]+\.?[0-9]*$' AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type='adqs_directory' AND post_status='publish')");
			wp_cache_set('adqs_maxPrice', $maxPrice, 'adqs_cache');
		}

		return !empty($maxPrice) ? $maxPrice : false;
	}

	/**
	 * Price
	 *
	 * @param [type] $post_id
	 * @return string
	 */
	public function get_price($post_id)
	{
		$priceRangeTypes = apply_filters(
			'adqs_meta_price_range',
			array(
				'skimming'       => esc_html__('$$$$', 'adirectory'),
				'moderate'       => esc_html__('$$$', 'adirectory'),
				'economy'        => esc_html__('$$', 'adirectory'),
				'bellow_economy' => esc_html__('$', 'adirectory'),
			)
		);
		$value_price     = $this->meta_val($post_id, '_price') ?? '';

		$value_price_sub   = $this->meta_val($post_id, '_price_sub');
		$value_price_range = $this->meta_val($post_id, '_price_range');
		$value_price_type  = $this->meta_val($post_id, '_price_type');
		if ($value_price_type === '_price_range') {
			return $priceRangeTypes[$value_price_range] ?? '';
		} else {
			$value_price     = apply_filters('adqs_listing_price', $value_price);
			$value_price_sub = !empty($value_price_sub) ? "<span>{$value_price_sub}</span>" : '';
			$price           = adqs_get_price_ws($value_price);
			return $price . ' ' . $value_price_sub;
		}
	}

	/**
	 * Comment Review Html
	 *
	 * @param integer $ratings
	 * @return void
	 */
	public function get_review_rating_html($ratings = 0)
	{
		$ratings = !empty($ratings) ? floatval($ratings) : 0;
		$stars   = '<div class="qsd-stars">';
		for ($i = 1; $i <= 5; $i++) {
			if ($i <= $ratings) {
				$stars .= '<span class="dashicons dashicons-star-filled"></span>';
			} elseif ($i - $ratings < 1) {
				$stars .= '<span class="dashicons dashicons-star-half"></span>';
			} else {
				$stars .= '<span class="dashicons dashicons-star-empty"></span>';
			}
		}
		$stars .= '</div>';
		echo wp_kses_post($stars);
	}

	/**
	 * get post average ratings
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_post_average_ratings($id = 0)
	{
		$avg_ratings = get_post_meta($id, 'adqs_avg_ratings', true);
		return !empty($avg_ratings) ? $avg_ratings : 0;
	}

	/**
	 * Post View Count
	 *
	 * @param [type] $post_id
	 * @return int
	 */
	public function get_view_count($post_id)
	{
		$value = $this->meta_val($post_id, '_view_count');
		return $value;
	}

	/**
	 * Get pluck terms
	 *
	 * @param integer $post_id
	 * @param string  $taxonomy
	 * @param boolean $column
	 * @return array
	 */
	public function get_pluck_terms($post_id = 0, $taxonomy = '', $field = '', $index_key = null)
	{
		$post_terms = get_the_terms($post_id, $taxonomy);
		return wp_list_pluck($post_terms, $field, $index_key);
	}

	/**
	 * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
	 *
	 * @param $n
	 * @return string
	 */
	public function get_number_short_format($n = 0, $after = '+')
	{
		$n = !empty($n) ? absint($n) : 0;
		if ($n >= 0 && $n < 1000) {
			// 1 - 999
			$n_format = floor($n);
			$suffix   = '';
		} elseif ($n >= 1000 && $n < 1000000) {
			// 1k-999k
			$n_format = floor($n / 1000);
			$suffix   = "K{$after}";
		} elseif ($n >= 1000000 && $n < 1000000000) {
			// 1m-999m
			$n_format = floor($n / 1000000);
			$suffix   = "M{$after}";
		} elseif ($n >= 1000000000 && $n < 1000000000000) {
			// 1b-999b
			$n_format = floor($n / 1000000000);
			$suffix   = "B{$after}";
		} elseif ($n >= 1000000000000) {
			// 1t+
			$n_format = floor($n / 1000000000000);
			$suffix   = "T{$after}";
		}

		return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
	}

	/**
	 * Get Settings Data
	 *
	 * @param string $key
	 * @return string|boolean|array
	 */
	public function get_setting($key = '')
	{
		$settings = get_option('adqs_admin_settings');
		return (isset($settings[$key]) && !empty($settings[$key])) ? $settings[$key] : '';
	}
}
