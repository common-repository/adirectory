<?php

namespace ADQS_Directory\Frontend;

// Traits
use ADQS_Directory\Database\Traits\Common\Save_Data;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Ajax handlers class
 */
class Ajax
{

	use Save_Data;


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{

		add_action('phpmailer_init', function ($phpmailer) {
			$phpmailer->SMTPDebug = 3;
		});

		// ajax comments

		add_action('wp_ajax_adqs_ajaxlistingreview', array($this, 'submit_ajax_review_comment'));

		add_action('wp_ajax_nopriv_adqs_ajaxlistingreview', array($this, 'submit_ajax_review_comment'));

		// ajax show more review comment
		add_action('wp_ajax_adqs_ajaxlistingreview_more', array($this, 'show_more_review_comment'));

		add_action('wp_ajax_nopriv_adqs_ajaxlistingreview_more', array($this, 'show_more_review_comment'));

		// ajax show more review comment
		add_action('wp_ajax_adqs_ajaxlisting_contact_owner', array($this, 'listing_contact_owner'));

		//Review comment no priv
		add_action('wp_ajax_nopriv_adqs_ajaxlisting_contact_owner', array($this, 'listing_contact_owner'));

		//User dashbaord ajax handlers

		add_action('wp_ajax_adqs_user_dash_get_listings', array($this, 'qsd_user_dash_get_listings'));

		//User dashbaord ajax handlers norpiv

		add_action('wp_ajax_nopriv_adqs_user_dash_get_listings', array($this, 'qsd_user_dash_get_listings'));

		//Delete listing from user dashboard
		add_action('wp_ajax_adqs_delete_listing', array($this, 'adqs_delete_listing'));

		//Get submisioin field by slug

		add_action('wp_ajax_qs_get_submission_by_slug', array($this, 'qs_get_submission_by_slug'));

		//Get all directory type  lists
		add_action('wp_ajax_qs_get_all_directory_types', array($this, 'qs_get_all_directory_types'));

		//Frontend user profile update

		add_action('wp_ajax_adqs_user_profile_update', array($this, 'user_profile_update'));

		//Frontend get user profile data
		add_action('wp_ajax_adqs_get_userdata', array($this, 'get_front_userdata'));

		//Add or remove fav listing
		add_action('wp_ajax_adqs_add_rmv_fav_listing', array($this, 'manage_fav_listing'));

		add_action('wp_ajax_adqs_rmv_fav_listing', array($this, 'remove_fav_listing'));

		//Get user fav listing
		add_action('wp_ajax_adqs_get_user_fav_list', array($this, 'get_user_fav_list'));

		// get user prcing plans
		add_action('wp_ajax_adqs_user_dash_get_pricingPackage', array($this, 'user_dash_get_pricing_package'));

		//User dashbaord ajax handlers norpiv

		add_action('wp_ajax_nopriv_adqs_user_dash_get_pricingPackage', array($this, 'user_dash_get_pricing_package'));


		// ajax search

		add_action('wp_ajax_adqs_ajax_search', array($this, 'ajax_search'));
		add_action('wp_ajax_nopriv_adqs_ajax_search', array($this, 'ajax_search'));
	}

	/**
	 * Method adqs_delete_listing
	 *
	 * @return void
	 */


	public function adqs_delete_listing()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		if (!current_user_can('read')) {
			wp_send_json_error(array('messsage' => 'Do not have any permisiion'));
		}

		$postid = isset($_POST['listid']) ? absint($_POST['listid']) : 0;

		$delete = wp_delete_post($postid);

		if ($delete) {
			wp_send_json_success();
		}

		wp_send_json_error();
	}


	/**
	 * Method remove_fav_listing
	 *
	 * @return void
	 */
	public function remove_fav_listing()
	{

		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}
		if (!is_user_logged_in()) {
			wp_send_json_error(array('messsage' => 'Not allowed'));
		}

		$user_id = get_current_user_id();
		$listing_id = absint($_POST['postid']);

		$user_fav_list = !empty(get_user_meta($user_id, 'adqs_user_fav_list', true)) ? get_user_meta($user_id, 'adqs_user_fav_list', true) : array();

		if (in_array($listing_id, $user_fav_list)) {
			foreach (array_keys($user_fav_list, $listing_id) as $key) {
				unset($user_fav_list[$key]);
			}
		} else {
			$user_fav_list[] = $listing_id;
		}

		update_user_meta($user_id, 'adqs_user_fav_list', $user_fav_list);

		wp_send_json_success();
	}




	/**
	 * Method get_user_fav_list
	 *
	 * @return array
	 */


	public function get_user_fav_list()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}
		if (!is_user_logged_in()) {
			wp_send_json_error(array('messsage' => 'Not allowed'));
		}

		$fav_listing_ids = !empty(get_user_meta(get_current_user_id(), 'adqs_user_fav_list', true)) ? get_user_meta(get_current_user_id(), 'adqs_user_fav_list', true) : [];


		if (empty($fav_listing_ids)) {
			wp_send_json_success(array());
		}

		$args = array(
			'post_type' => ADQS_LISTING_POST_TYPE,
			'posts_per_page' => 10,
			'post__in'  =>  $fav_listing_ids
		);

		$listing = new \WP_Query($args);

		$fav_content = array();
		if ($listing->have_posts()) :
			while ($listing->have_posts()) :
				$listing->the_post();
				$dir_type_id =  get_post_meta(get_the_ID(), 'adqs_directory_type', true);

				$dir_term = get_term($dir_type_id, ADQS_LISTING_TYPE_TAX);
				ob_start();
				adqs_get_template_part('global/price');
				$price = ob_get_clean();

				$fav_content[] = array(
					"list_id" => get_the_ID(),
					"feat_img" => get_default_preview_image(get_the_ID()),
					"title" => get_the_title(),
					"permalink" => get_the_permalink(),
					"dir_type" => $dir_term->name ?? '',
					"price" => $price
				);

			endwhile;
		endif;

		wp_send_json_success($fav_content);
	}


	/**
	 * Method manage_fav_listing
	 *
	 * @return array
	 */
	public function manage_fav_listing()
	{
		if (!check_ajax_referer('adqs___grid_page', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}
		if (!is_user_logged_in()) {
			wp_send_json_error(array('messsage' => 'Permission not allowed'));
		}

		$user_id = get_current_user_id();
		$listing_id = absint($_POST['postid']);

		$user_fav_list = !empty(get_user_meta($user_id, 'adqs_user_fav_list', true)) ? get_user_meta($user_id, 'adqs_user_fav_list', true) : array();

		if (in_array($listing_id, $user_fav_list)) {
			foreach (array_keys($user_fav_list, $listing_id) as $key) {
				unset($user_fav_list[$key]);
			}
		} else {
			$user_fav_list[] = $listing_id;
		}

		update_user_meta($user_id, 'adqs_user_fav_list', $user_fav_list);

		wp_send_json_success($user_fav_list);
	}



	/**
	 * Method get_front_userdata
	 *
	 * @return array
	 */
	public function get_front_userdata()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		$current_user = wp_get_current_user();

		// Get user data
		$user_data = array(
			'display_name' => $current_user->display_name,
			'first_name' => $current_user->first_name,
			'user_name' => $current_user->user_login,
			'last_name' => $current_user->last_name,
			'email' => $current_user->user_email,
			'phone' => get_user_meta($current_user->ID, 'adqs_phone', true),
			'website' => esc_url($current_user->user_url),
			'address' => get_user_meta($current_user->ID, 'adqs_address_info', true),
			'fb_url' => get_user_meta($current_user->ID, 'adqs_facebook_profile', true),
			'twitter_url' => get_user_meta($current_user->ID, 'adqs_twitter_profile', true),
			'linkedin_url' => get_user_meta($current_user->ID, 'adqs_linked_profile', true),
			'instagram_url' => get_user_meta($current_user->ID, 'adqs_instagram_profile', true),
			'description' => get_user_meta($current_user->ID, 'description', true),
			'profile_picture' => get_user_meta($current_user->ID, 'profile_picture', true)
		);


		wp_send_json_success($user_data);
	}




	/**
	 * Method user_profile_update
	 *
	 * @return void
	 */
	public function user_profile_update()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('message' => 'Nonce verification failed'));
		}

		$user_id = get_current_user_id();

		if (!current_user_can('edit_user', $user_id)) {
			wp_send_json_error(array('message' => 'You are not allowed to do this operation'));
		}

		$current_user = wp_get_current_user();

		// Update basic user information
		$user_data = array(
			'ID' => $current_user->ID,
			'display_name' => isset($_POST['dname']) ? sanitize_text_field($_POST['dname']) : '',
			'first_name' => isset($_POST['fname']) ? sanitize_text_field($_POST['fname']) : '',
			'last_name' => isset($_POST['lname']) ? sanitize_text_field($_POST['lname']) : '',
			'user_email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
			'user_url' => isset($_POST['website']) ? esc_url($_POST['website']) : '',
		);

		$user_id = wp_update_user($user_data);


		// Update additional user meta
		$meta_data = array(
			'adqs_phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '',
			'adqs_address_info' => isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '',
			'adqs_facebook_profile' => isset($_POST['fburl']) ? sanitize_text_field($_POST['fburl']) : '',
			'adqs_twitter_profile' => isset($_POST['twitterurl']) ? sanitize_text_field($_POST['twitterurl']) : '',
			'adqs_instagram_profile' => isset($_POST['instaurl']) ? sanitize_text_field($_POST['instaurl']) : '',
			'adqs_linked_profile' => isset($_POST['linkedinurl']) ? sanitize_text_field($_POST['linkedinurl']) : '',
			'description' => isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '',
		);

		foreach ($meta_data as $meta_key => $meta_value) {
			update_user_meta($current_user->ID, $meta_key, $meta_value);
		}

		// Handle password update
		if (!empty($_POST['new_password']) && $_POST['new_password'] === $_POST['confirm_password']) {
			wp_set_password($_POST['new_password'], $current_user->ID);
		}

		if (!empty($_FILES['profileFile']['name'])) {
			$upload_overrides = array('test_form' => false);
			$movefile = wp_handle_upload($_FILES['profileFile'], $upload_overrides);

			if ($movefile && !isset($movefile['error'])) {
				$profile_picture_url = $movefile['url'];
				update_user_meta($current_user->ID, 'profile_picture', $profile_picture_url);
			} else {
				wp_send_json_error(array(
					"error" => "Error uploading file"
				));
			}
		}

		wp_send_json_success();
	}




	/**
	 * Method qs_get_all_directory_types
	 *
	 * @return array
	 */
	public function qs_get_all_directory_types()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		$all_listing_types        = get_terms(
			array(
				'taxonomy'   => 'qs_listing_types',
				'hide_empty' => false,
			)
		);

		$all_terms = array();

		foreach ($all_listing_types as $listing_type) {

			$dir_type_icon = get_term_meta($listing_type->term_id, '_qsd_term_icon', true);
			$dir_id = $listing_type->term_id ? $listing_type->term_id : 0;
			$dir_name = $listing_type->name ? $listing_type->name : '';
			$dir_slug = $listing_type->slug ? $listing_type->slug : '';

			$all_terms[] = [
				'icon' =>  $dir_type_icon,
				'id'  => $dir_id,
				'name'  => $dir_name,
				'slug' => $dir_slug
			];
		}


		if ($all_listing_types) {
			wp_send_json_success($all_terms);
		}


		wp_send_json_error("Something went wrong");
	}


	/**
	 * Method qs_get_submission_by_slug
	 *
	 * @return array
	 */
	public function qs_get_submission_by_slug()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		$termsslug = isset($_POST['termslug']) ? sanitize_text_field($_POST['termslug']) : '';

		if (empty($termsslug)) {
			wp_send_json_error(array('messsage' => 'Term slug is required'));
		}

		$termid = !(array)get_term_by('slug', $termsslug, 'qs_listing_types') ? 0 : get_term_by('slug', $termsslug, 'qs_listing_types')->term_id;

		$builder = get_term_meta((int) $termid, '_qsd_metafields_types', true);

		wp_send_json_success(array(
			"fields" => $builder
		));
	}




	/**
	 * Method qsd_user_dash_get_listings
	 *
	 * @return array
	 */

	public function qsd_user_dash_get_listings()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}

		$author_id = absint($_POST['author_id']);
		$paged = isset($_POST['cuurent_page']) ? sanitize_text_field($_POST['cuurent_page']) : 1;
		$searchq = isset($_POST['searchq']) ? sanitize_text_field($_POST['searchq']) : false;

		if (empty($author_id)) {
			wp_send_json_error(array('messsage' => 'Author id missing'));
		}

		$post_status_key = isset($_POST['post_status_key']) ? sanitize_text_field($_POST['post_status_key']) : 'all-listings';

		$post_status = '';

		$posts_per_page = 6;

		switch ($post_status_key) {
			case 'published-listing':
				$post_status = 'publish';
				break;
			case 'pending-listing':
				$post_status = 'pending';
				break;
			case 'expired-listing':
				$post_status = 'expired';
				break;
			default:
				$post_status = 'any';
		}

		$args = array(
			'post_type' => 'adqs_directory',
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'author' => $author_id
		);

		if ($post_status !== 'expired') {
			$args['post_status'] = $post_status;
		}

		if ($searchq) {
			$args['s'] = $searchq;
		}


		$meta_query = array();

		if ($post_status === 'expired') {

			$meta_query[] = array(
				'key' => '_qs_listing_expired',
				'value' => '0',
				'compare' => '='
			);
		}

		if (count($meta_query) > 0) {
			if (count($meta_query) > 1) {
				$meta_query['relation'] = 'AND';
			}
			$args['meta_query'] = $meta_query;
		}


		$listings = new \WP_Query($args);

		$total_listing = $listings->found_posts;

		$total_pages = ceil($total_listing / $posts_per_page);

		$listings_data = array();

		if ($listings->have_posts()) {
			while ($listings->have_posts()) {
				$listings->the_post();

				$listing_type_id = get_post_meta(get_the_ID(), 'adqs_directory_type', true);

				$pricing_order = get_post_meta(get_the_ID(), 'adqs_pricing_order_no', true);

				$directory_term         = get_term_by('id', $listing_type_id, 'adqs_listing_types');

				$directory_name = !empty($directory_term->name) ? $directory_term->name  : '';

				$default_expiry = get_term_meta($listing_type_id, '_qsd_term_default_expiry', true) ? get_term_meta($listing_type_id, '_qsd_term_default_expiry', true) : 15;

				$edit_query_args = array(
					"postid" => (int) get_the_ID(),
				);
				if (!empty($pricing_order)) {
					$edit_query_args['adqs_listing_type'] = (int) $listing_type_id;
					$edit_query_args['adqs_order'] = (int) $pricing_order;
				}


				$edit_permalink =  add_query_arg($edit_query_args, esc_url(adqs_get_permalink_by_key('adqs_add_listing')));

				$post_date = get_the_date('Y-m-d', get_the_ID());

				$default_expiry = date('Y-m-d', strtotime($post_date . ' + ' . $default_expiry . ' days'));

				ob_start();
				adqs_get_template_part('global/price');
				$price = ob_get_clean();


				$listings_data[] = array(
					"id" =>  get_the_ID(),
					"title" => get_the_title(),
					"permalink" => get_the_permalink(),
					"price" => $price,
					"dir_type" => $directory_name,
					"feat_img" => get_default_preview_image(get_the_ID(), 'thumbnail'),
					"status"   => get_post_status(get_the_ID()),
					"default_expiry" => $default_expiry,
					"edit_permalink" => $edit_permalink,
				);
			}
		}

		wp_send_json_success(array(
			'listings' => $listings_data,
			"total_pages" => $total_pages
		));
	}


	/**
	 * Method user_dash_get_pricing_package
	 *
	 * @return array
	 */
	public function user_dash_get_pricing_package()
	{
		if (!check_ajax_referer('__qs_directory_userdash', 'security', 'false')) {
			wp_send_json_error(array('messsage' => 'Nonce verification failed'));
		}



		$author_id = absint($_POST['author_id']);
		$paged = isset($_POST['current_page']) ? absint($_POST['current_page']) : 1;
		$searchq = isset($_POST['searchq']) ? sanitize_text_field($_POST['searchq']) : false;
		$args = [];
		if (!empty($searchq)) {
			$args['s'] = $searchq;
		}
		if (empty($author_id)) {
			wp_send_json_error(array('message' => 'Author id missing'));
		}

		$status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : null;
		$posts_per_page = 6;
		$offset = ($paged - 1) * $posts_per_page;
		$allStatus = [];
		$getStatus = adp_get_order_by_user_id($author_id, 'status', null, true);
		foreach ($getStatus as $s) {
			if (!in_array($s->status, $allStatus)) {
				$allStatus[] = $s->status ?? '';
			}
		}
		$query_args = array_merge($args, ['posts_per_page' => $posts_per_page, 'offset' => $offset]);
		$pricingPackage = adp_get_order_by_user_id($author_id, 'id,pricing_id,directory_id,price_payment,payment_type,status,order_date', $status, true, $query_args);

		$total_listing = adp_get_order_count_by_user_id($author_id, $status, $args);
		$total_pages = ceil($total_listing / $posts_per_page);

		$packages_data = [];

		if (!empty($pricingPackage)) {
			foreach ($pricingPackage as $package) {
				$getPricing = adp_get_query_pricing($package->pricing_id, 'name');
				$view_page = get_option('adp_view_page_id');

				$permalink = !empty($view_page) ? get_permalink(absint($view_page)) . 'ad-pricing/' . $package->pricing_id : '';

				$order_permalink = !empty($view_page) ? adp_get_order_page_url(absint($view_page), $package->id) : '';
				$directory = get_term_by('term_id', absint($package->directory_id ?? 0), 'adqs_listing_types');
				$directory_permalink = !empty($view_page) ? add_query_arg('directory_type', $directory->slug ?? '', get_permalink(absint($view_page))) : '';

				$reg_listing = get_user_meta($author_id, "adp_order_reg_listing_{$package->id}", true);
				$fea_listing = get_user_meta($author_id, "adp_order_fea_listing_{$package->id}", true);
				$order_expire = get_user_meta($author_id, "adp_order_expire_{$package->id}", true);
				if (($reg_listing !== 'unlimited') && !empty($reg_listing)) {
					$reg_listing = esc_html__('Regular ', 'adirectory') . ' ' . $reg_listing;
				}
				if (($fea_listing !== 'unlimited') && !empty($reg_listing)) {
					$fea_listing = esc_html__('Featured ', 'adirectory') . ' ' . $fea_listing;
				}

				if (($order_expire !== 'never_expire') && !empty($order_expire)) {
					$order_expire = wp_date('F d, Y', strtotime($order_expire));
				}
				if (($order_expire === 'never_expire')) {
					$order_expire = esc_html__('Never ', 'adirectory');
				}


				$remain_listing = [
					'reg_listing' => $reg_listing,
					'fea_listing' => $fea_listing,
				];
				$price = adqs_get_price_ws($package->price_payment ?? '');
				if (function_exists('adp_get_query_order_meta')) {
					if (!empty(adp_get_query_order_meta($package->id, 'adqsp_currency_switch', true))) {
						$currency_switch = adp_get_query_order_meta($package->id, 'adqsp_currency_switch', true);
						if (adp_get_currency($currency_switch, 'postion') === 'before') {
							$price = esc_html(adp_get_currency($currency_switch, 'symbol')) . ($package->price_payment ?? '');
						}
						if (adp_get_currency($currency_switch, 'postion') === 'after') {
							$price = ($package->price_payment ?? '') . esc_html(adp_get_currency($currency_switch, 'symbol'));
						}
					}
				}


				$packages_data[] = array(
					"order_id" =>  $package->id ?? '',
					"package_name" => $getPricing->name ?? '',
					"plan_type" => ucwords($directory->name ?? ''),
					"permalink" => $permalink,
					"order_permalink" => $order_permalink,
					"directory_permalink" => $directory_permalink,
					"price" => $price,
					"remain_listing" => $remain_listing,
					"payment_type" => ucwords(str_replace('_', ' ', $package->payment_type ?? '')),
					"status" => $package->status ?? '',
					"order_date" => wp_date('F d, Y', strtotime($package->order_date)),
					"order_expire" => $order_expire,
				);
			}
		}

		wp_send_json_success([
			'all_status' => $allStatus,
			'packages' => $packages_data,
			'total_pages' => $total_pages
		]);
	}


	/**
	 * Method submit_ajax_review_comment
	 *
	 * @return array
	 */
	public function submit_ajax_review_comment()
	{
		check_ajax_referer('adqs___directory_frontend', 'security');
		global $comment;
		$errorMessage      = false;
		$alreadyHasComment = false;
		$comment_html      = '';
		$email             = sanitize_email($_POST['email'] ?? '');
		$comment_post_ID   = absint(sanitize_text_field($_POST['comment_post_ID'] ?? 0));
		$hasComment        = (int) get_comments(
			array(
				'author_email' => $email,
				'post_id'      => $comment_post_ID,
				'status'       => 'approve',
				'count'        => true,
			)
		);

		if (!empty($email) && ($hasComment > 0)) {
			$alreadyHasComment = true;
		} else {

			// delete author review count
			wp_cache_delete('adqs_authlrRatings', 'adqs_cache');
			wp_cache_delete('adqs_authlrReviewCount', 'adqs_cache');

			$comment = wp_handle_comment_submission(wp_unslash($_POST));
			if (is_wp_error($comment)) {
				$errorMessage = '<p>' . esc_html__('Comment Submission Failure', 'adirectory') . '</p>';
			} else {
				$this->update_avg_ratings($comment_post_ID);
			}
			/*
			* Set Cookies
			*/
			$user = wp_get_current_user();
			do_action('set_comment_cookies', $comment, $user);
		}

		if (!empty($comment)) :

			ob_start();
			adqs_get_template_part('single-listing/review');
			$comment_html = ob_get_clean();

			ob_start();
			$Helper      = AD()->Helper;
			$avgRatings  = $Helper->get_post_average_ratings($comment_post_ID);
			$countReview = get_comments_number($comment_post_ID);
			if (!empty($countReview)) :
?>
				<div class="qsd-avgRatings"><?php echo esc_html($avgRatings); ?></div>
				<div class="qsd-avgRatings-overview">
					<?php $Helper->get_review_rating_html($avgRatings); ?>
					<div class="qsd-totelReview">
						<?php

						$reviewText = $countReview > 1 ? esc_html__('Reviews', 'adirectory') : esc_html__('Review', 'adirectory');
						echo esc_html($countReview) . ' ' . esc_html($reviewText);

						?>
					</div>
				</div>
		<?php
			endif;
			$avgRatings_html = ob_get_clean();

		endif;

		wp_send_json_success(
			array(
				'comment_html'      => $comment_html,
				'avgRatings_html'   => $avgRatings_html,
				'alreadyHasComment' => $alreadyHasComment,
				'errorMessage'      => $errorMessage,
			)
		);
		wp_die();
	}

	/**
	 * check has next comment
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function has_next_review_comment($comment_args)
	{
		if (isset($comment_args['paged'])) {
			$comment_args['paged'] = $comment_args['paged'] + 1;
			$comments              = get_comments($comment_args);
			if (!empty($comments)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * show more review comment
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function show_more_review_comment()
	{
		check_ajax_referer('adqs___directory_frontend', 'security');

		$post_id      = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
		$per_page     = isset($_POST['per_page']) ? absint($_POST['per_page']) : 3;
		$current_page = isset($_POST['current_page']) ? absint($_POST['current_page']) : 1;
		$current_page = $current_page + 1;

		$comment_args = array(
			'post_id' => $post_id,
			'number'  => $per_page,
			'paged'   => $current_page,
			'parent'  => 0,
			'status'  => 'approve',
		);

		$comments = get_comments($comment_args);
		global $comment, $comment_id, $comment_author_id;
		ob_start();
		if (!empty($comments)) :
			foreach ($comments as $comment) :
				$comment_id        = get_comment_ID();
				$comment_author_id = !empty($comment->user_id) ? $comment->user_id : 0;
				adqs_get_template_part('single-listing/review');

			endforeach;
		endif;
		$comment_html = ob_get_clean();

		wp_send_json_success(
			array(
				'comment_html'     => $comment_html,
				'get_current_page' => $current_page,
				'has_next'         => $this->has_next_review_comment($comment_args),
			)
		);
		wp_die();
	}


	/**
	 * Method listing_contact_owner
	 *
	 * @return void
	 */
	public function listing_contact_owner()
	{
		check_ajax_referer('adqs___directory_frontend', 'security');

		$listing_id = !empty($_POST['adqs_listing_id']) ? absint($_POST['adqs_listing_id']) : 0;
		$auth_id    = !empty($_POST['adqs_co']) ? absint($_POST['adqs_co']) : 0;

		if (!$listing_id || !$auth_id) {
			wp_send_json_error(__('Invalid listing or author ID.', 'adirectory'));
			wp_die();
		}

		$to      = get_the_author_meta('user_email', $auth_id);
		$subject = esc_html__('For listing', 'adirectory') . ' : ' . get_the_title($listing_id);

		if (!$to) {
			wp_send_json_error(__('No email found for the author.', 'adirectory'));
			wp_die();
		}

		// Retrieve sender's name and email from POST
		$sender_name  = sanitize_text_field($_POST['adqs_ca_name'] ?? '');
		$sender_email = sanitize_email($_POST['adqs_ca_email'] ?? '');

		if (!$sender_email || !is_email($sender_email)) {
			wp_send_json_error('Invalid sender email.');
			wp_die();
		}

		// Build email body
		ob_start();
		?>
		<p><strong><?php echo esc_html__('Sender Name', 'adirectory'); ?> : </strong>
			<?php echo esc_html($sender_name); ?></p>
		<p><strong><?php echo esc_html__('Email', 'adirectory'); ?> : </strong>
			<?php echo esc_html($sender_email); ?></p>
		<p><strong><?php echo esc_html__('Phone', 'adirectory'); ?> :
			</strong><?php echo esc_html($_POST['adqs_ca_phone'] ?? ''); ?></p>
		<p><strong><?php echo esc_html__('Message', 'adirectory'); ?> :
			</strong><?php echo esc_html($_POST['adqs_ca_msg'] ?? ''); ?></p>
<?php
		$body = ob_get_clean();

		// Make sure only logged-in users can send emails if required
		$onlyLoginUser = (($_POST['only_login_user'] ?? '') === 'no') ? true : is_user_logged_in();
		if (!$onlyLoginUser) {
			wp_send_json_error(__('You need to be logged in to send emails.', 'adirectory'));
			wp_die();
		}
		if (!function_exists('wp_mail')) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}

		// Set headers dynamically with "From" using sender's name and email
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . esc_html($sender_name) . ' <' . esc_html($sender_email) . '>'
		);

		// Send the email
		$send_mail = wp_mail($to, $subject, $body, $headers);

		if ($send_mail) {
			wp_send_json_success(array('send_mail' => true));
		} else {
			wp_send_json_error(__('Failed to send email.', 'adirectory'));
		}

		wp_die();
	}






	/**
	 * Method ajax_search
	 *
	 * @return void
	 */
	public function ajax_search()
	{
		check_ajax_referer('adqs_ajax_search_nonce', 'security');

		$searchVal = isset($_GET['searchVal']) ? sanitize_text_field($_GET['searchVal']) : '';
		$categoryVal = isset($_GET['categoryVal']) ? sanitize_text_field($_GET['categoryVal']) : '';
		$locationVal = isset($_GET['locationVal']) ? sanitize_text_field($_GET['locationVal']) : '';
		$directoryType = isset($_GET['directoryType']) ? sanitize_text_field($_GET['directoryType']) : '';
		$author_id = isset($_GET['author_id']) ? absint($_GET['author_id']) : '';

		// Perform the search
		$queryArgs = array(
			's' => $searchVal,
			'post_type' => 'adqs_directory',

		);
		$tax_query  = [];
		$meta_query = [];
		if (!empty($categoryVal)) {
			$tax_query[] = array(
				'taxonomy' => 'adqs_category',
				'field'    => 'slug',
				'terms'    => explode(',', $categoryVal),
				'operator' => 'IN',
			);
		}

		if (!empty($locationVal)) {
			$tax_query[] = array(
				'taxonomy' => 'adqs_location',
				'field'    => 'slug',
				'terms'    => explode(',', $locationVal),
				'operator' => 'IN',
			);
		}
		if (count($tax_query) > 0) {
			if (count($tax_query) > 1) {
				$tax_query['relation'] = 'AND';
			}
			$queryArgs['tax_query'] = $tax_query;
		}

		if (!empty($directoryType)) {
			$queryArgs['meta_key'] = 'adqs_directory_type';
			$queryArgs['meta_value'] = $directoryType;
		}
		if (!empty($author_id)) {
			$queryArgs['author'] = $author_id;
		}


		// Execute the query
		$search_results = new \WP_Query($queryArgs);



		ob_start();

		adqs_get_template_part('global/ajax', 'search', compact('search_results'));

		// Collect the results
		$results_html = ob_get_clean();

		// Return the results as JSON
		wp_send_json_success([
			'results_html' => $results_html,
		]);

		wp_die();
	} // end

}
