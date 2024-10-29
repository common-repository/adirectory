<?php

namespace ADQS_Directory\Frontend;

// Traits
use ADQS_Directory\Frontend\Traits\Customize\Listing_Review;
use ADQS_Directory\Database\Traits\Common\Save_Data;


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Customize handlers class
 */
class Customize
{

	use Listing_Review;
	use Save_Data;

	private $post_type = 'adqs_directory';
	private $category  = 'adqs_category';
	private $location  = 'adqs_location';
	private $tag       = 'adqs_tags';

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		// Listing Archive Query Customize.
		add_action('pre_get_posts', array($this, 'listing_archive_query_customize'));

		// Save the meta data.
		add_action('wp_head', array($this, 'save_meta_data'));

		// Listing Review Hooked
		$this->comment_review_hooked();

		add_filter('get_avatar', [$this, 'customize_default_avatar'], 10, 5);

		// ajax search html
		add_action('adqs_ajax_search', array($this, 'ajax_search_html'));
		add_action('wp_head', [$this, 'schema_markup']);
	}

	/**
	 * listing archive query customize
	 *
	 * @param [type] $query
	 * @return void
	 */
	public function listing_archive_query_customize($query)
	{
		$directory_type = isset($_GET['directory_type']) ? $_GET['directory_type'] : '';
		if (($query->is_main_query() && !is_admin()) && ($query->is_post_type_archive($this->post_type) || $query->is_tax($this->category) || $query->is_tax($this->location) || $query->is_tax($this->tag) || $query->is_author)) {
			if (!empty($directory_type)) {
				$directory_type_id = get_term_by('slug', $directory_type, 'adqs_listing_types');
				$directory_type_id = !empty($directory_type_id->term_id) ? $directory_type_id->term_id : 0;
				$query->set('meta_key', 'adqs_directory_type');
				$query->set('meta_value', $directory_type_id);
			}
			$is_listings = $_GET['listings'] ?? '';
			$search      = $_GET['ls'] ?? '';
			if ($query->is_author && ($is_listings === 'yes')) {
				$query->set('post_type', array($this->post_type));
			}

			if (!empty($search)) {
				$query->set('s', $search);
			}

			$query->set('posts_per_page', apply_filters('adqs_listing_archive_per_page', 6));

			/**
			 *  Short By
			 */
			if (!empty(adqs_listing_query_sort_by())) {
				$sortByArgs = adqs_listing_query_sort_by();
				if (isset($sortByArgs['meta_key']) && !empty($sortByArgs['meta_key'])) {
					$query->set('meta_key', $sortByArgs['meta_key']);
				}
				if (isset($sortByArgs['orderby']) && !empty($sortByArgs['orderby'])) {
					$query->set('orderby', $sortByArgs['orderby']);
				}
				if (isset($sortByArgs['order']) && !empty($sortByArgs['order'])) {
					$query->set('order', $sortByArgs['order']);
				}
			}
		}
	}

	/**
	 * get post average ratings
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function save_meta_data()
	{
		if (!is_singular($this->post_type)) {
			return;
		}
		$this->save_views_count(get_the_ID());
		$this->update_avg_ratings(get_the_ID());
	}


	/**
	 * Update View Count By IP Address
	 *
	 * @param [type] $post_id
	 * @return void
	 */
	public function save_views_count($postID = null)
	{
		$postID  = !empty($postID) ? $postID : get_the_ID();
		$count_key = '_view_count';
		$count     = absint(get_post_meta($postID, $count_key, true));
		if ($count == '') {
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, 0);
		} else {
			++$count;
			update_post_meta($postID, $count_key, $count);
		}
	}


	/**
	 * 	customize default avatar() {
	 *
	 * @return string
	 */
	public function customize_default_avatar($avatar, $id_or_email, $size, $default_value, $alt)
	{
		$userId = is_object($id_or_email) ? $id_or_email->user_id : $id_or_email;
		if (!empty(get_user_meta($userId ?? 0, 'profile_picture', true))) {
			return '<img class="avatar avatar-' . esc_attr($size) . ' photo" src="' . esc_url(get_user_meta($userId, 'profile_picture', true)) . '" height="' . esc_attr($size) . '" width="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" decoding="async"/>';
		}
		return $avatar;
	}

	// ajax search html
	public function ajax_search_html()
	{
		$ajax_url = admin_url('admin-ajax.php');
		$security = wp_create_nonce('adqs_ajax_search_nonce');
		$directory_type_id = get_term_by('slug', $_REQUEST['directory_type'] ?? '', 'adqs_listing_types');
		$directory_type_id = !empty($directory_type_id->term_id) ? $directory_type_id->term_id : '';
		$author_id = adqs_is_author_listing_archive() ? get_query_var('author') : '';
		echo "<div class='adqs_ajax_search_results' data-ajax-url='{$ajax_url}' data-ajax-security='{$security}' data-directory-type='{$directory_type_id}' data-author-id='{$author_id}'></div>";
	}

	public function schema_markup()
	{
		$options = adqs_get_setting_option('schema_options', []);
		if (!is_singular('adqs_directory') || empty($options)) {
			return;
		}

		global $post;

		// Get post meta and other necessary fields, sanitized
		$headline = esc_html(get_the_title($post->ID));
		$tagline = esc_html(get_post_meta($post->ID, '_tagline', true));
		$address = esc_html(get_post_meta($post->ID, '_address', true));
		$zip = esc_html(get_post_meta($post->ID, '_zip', true));
		$latitude = esc_attr(get_post_meta($post->ID, '_map_lat', true));
		$longitude = esc_attr(get_post_meta($post->ID, '_map_lon', true));
		$phone = esc_html(get_post_meta($post->ID, '_phone', true));
		$website = esc_url(get_post_meta($post->ID, '_website', true));
		$fax = esc_html(get_post_meta($post->ID, '_fax', true));
		$email = esc_html(get_post_meta($post->ID, '_email', true)) ?: esc_html(get_the_author_meta('user_email'));
		$author_name = esc_html(get_the_author_meta('display_name', $post->post_author));
		$description = esc_html(get_the_excerpt($post->ID)) ?: wp_trim_words(esc_html(strip_tags($post->post_content)), 30);
		$date_published = esc_html(get_the_date('c', $post->ID)); // ISO 8601 format
		$date_modified = esc_html(get_the_modified_date('c', $post->ID)); // ISO 8601 format
		$rating_value = AD()->Helper->get_post_average_ratings($post->ID) ?: 0;
		$rating_count = get_comments_number($post->ID) ?: 0;

		// Get featured image if available
		$image_url = has_post_thumbnail($post->ID) ? esc_url(get_the_post_thumbnail_url($post->ID, 'full')) : null;

		// Build the schema JSON array
		$schemaJson = [
			"@context" => "https://schema.org",
			"@type" => "LocalBusiness",
			"name" => $headline,
		];

		// Optional fields based on the selected schema options
		if (in_array('headline', $options)) {
			$schemaJson["headline"] = $headline;
		}
		if (in_array('tagline', $options) && !empty($tagline)) {
			$schemaJson["tagline"] = $tagline;
		}

		if (in_array('description', $options) && !empty($description)) {
			$schemaJson["description"] = $description;
		}

		if (in_array('image', $options) && !empty($image_url)) {
			$schemaJson["image"] = $image_url;
		}

		// Address and postal code
		if (in_array('address', $options) && (!empty($address) || !empty($zip))) {
			$schemaJson["address"] = [
				"@type" => "PostalAddress",
			];
			if (!empty($address)) {
				$schemaJson["address"]["streetAddress"] = $address;
			}
			if (!empty($zip)) {
				$schemaJson["address"]["postalCode"] = $zip;
			}
		}

		// Geolocation
		if (in_array('geo', $options) && !empty($latitude) && !empty($longitude)) {
			$schemaJson["geo"] = [
				"@type" => "GeoCoordinates",
				"latitude" => floatval($latitude),
				"longitude" => floatval($longitude),
			];
		}

		// Phone and fax
		if (in_array('telephone', $options) && !empty($phone)) {
			$schemaJson["telephone"] = $phone;
		}

		if (in_array('fax', $options) && !empty($fax)) {
			$schemaJson["faxNumber"] = $fax;
		}

		// Website and email
		if (in_array('website', $options) && !empty($website)) {
			$schemaJson["url"] = $website;
		}

		if (in_array('email', $options) && !empty($email)) {
			$schemaJson["email"] = $email;
		}

		// Author information
		if (in_array('author', $options) && !empty($author_name)) {
			$schemaJson["author"] = [
				"@type" => "Person",
				"name" => $author_name,
			];
		}

		// Ratings information
		if (in_array('rating', $options) && $rating_value > 0 && $rating_count > 0) {
			$schemaJson["aggregateRating"] = [
				"@type" => "AggregateRating",
				"ratingValue" => floatval($rating_value),
				"reviewCount" => intval($rating_count),
			];
		}

		// Date information
		if (in_array('date', $options)) {
			if (!empty($date_published)) {
				$schemaJson["datePublished"] = $date_published;
			}
			if (!empty($date_modified)) {
				$schemaJson["dateModified"] = $date_modified;
			}
		}

		// Output the structured data securely
?>
<script type="application/ld+json">
<?php echo wp_json_encode($schemaJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
<?php
	}
}