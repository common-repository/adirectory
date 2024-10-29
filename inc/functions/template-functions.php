<?php

/**
 * QS Directories Template
 *
 * Functions @hooked for the templating system.
 *
 * @package  QS Directories\Functions
 * @version  1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

use ADQS_Directory\EmailSender;


/* ==== templates/loop - folder ==== */

/**
 * Output Loop start listing item.
 */
if (!function_exists('adqs_listing_loop_start')) {
	function adqs_listing_loop_start()
	{
		adqs_get_template_part('loop/loop', 'start');
	}
}

/* ==== templates/global - folder ==== */

/**
 * Output start page wrapper.
 */
if (!function_exists('adqs_output_content_wrapper_start')) {
	function adqs_output_content_wrapper_start()
	{

		adqs_get_template_part('global/wrapper', 'start');
	}
}

/**
 * Output end page wrapper.
 */
if (!function_exists('adqs_output_content_wrapper_end')) {
	function adqs_output_content_wrapper_end()
	{
		adqs_get_template_part('global/wrapper', 'end');
	}
}

/* ==== templates/single-listing - folder ==== */

/**
 * Output single listing slider.
 */
if (!function_exists('adqs_single_listing_slider')) {
	function adqs_single_listing_slider()
	{
		adqs_get_template_part('single-listing/slider');
	}
}

/**
 * Output single listing details.
 */
if (!function_exists('adqs_single_listing_details')) {
	function adqs_single_listing_details()
	{
		adqs_get_template_part('single-listing/details');
	}
}

/**
 * Output single listing meta.
 */
if (!function_exists('adqs_single_listing_meta')) {
	function adqs_single_listing_meta()
	{
		adqs_get_template_part('single-listing/meta');
	}
}

/**
 * Output single listing meta.
 */
if (!function_exists('adqs_single_listing_title')) {
	function adqs_single_listing_title()
	{
		adqs_get_template_part('single-listing/title');
	}
}
/**
 * Output single listing description.
 */
if (!function_exists('adqs_single_listing_description')) {
	function adqs_single_listing_description()
	{
		adqs_get_template_part('single-listing/description');
	}
}

/**
 * Output single listing Review.
 */
if (!function_exists('adqs_single_listing_review')) {
	function adqs_single_listing_review()
	{
		if (!is_singular('adqs_directory')) {
			return;
		}
		/*
		Write Review */
		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || get_comments_number()) {
			comments_template();
		}
	}
}

/**
 * Output single listing Related Listing.
 */
if (!function_exists('adqs_single_listing_related')) {
	function adqs_single_listing_related()
	{
		if (!is_singular('adqs_directory')) {
			return;
		}
		$post_id = get_the_ID();
		$directory_type_id = get_post_meta(get_the_ID(), 'adqs_directory_type', true);
		$directory_type = get_term_by('term_id', absint($directory_type_id), 'adqs_listing_types')->slug ?? '';
		$category = '';
		$tags = '';


		if (!empty(get_the_terms(get_the_ID(), 'adqs_category'))) {
			$category = join(',', wp_list_pluck(get_the_terms(get_the_ID(), 'adqs_category'), 'slug'));
		} else {
			if (!empty(get_the_terms(get_the_ID(), 'adqs_tags'))) {
				$tags = join(',', wp_list_pluck(get_the_terms(get_the_ID(), 'adqs_tags'), 'slug'));
			}
		}

		echo '<div class="adqs-relatedListings_area">';
		echo '<div class="container">';
		echo '<h2 class="adqs-relatedListings_title">' . apply_filters('adqs_related_listing_title', esc_html__('Related Listing', 'adirectory')) . '</h2>';
		echo '</div>';
		$shortcode = "[adqs_listings
		filter_show='false'
		top_bar_show='false'
		pagination_type=''
		per_page='3'
		directory_type='{$directory_type}'
		category='{$category}'
		tags='{$tags}'
		post__not_in='{$post_id}'
		]";

		echo do_shortcode($shortcode);
		echo '</div>';
	}
}


/**
 * Listing meta fields.
 */
if (!function_exists('adqs_single_listing_fileds_elements')) {
	function adqs_single_listing_fileds_elements()
	{
		global $post;
		$post_id     = !empty($post->ID) ? $post->ID : 0;
		$Helper      = AD()->Helper;
		$fields_data = adqs_single_page_fields_render($post->ID);
		$dir         = 'single-listing/fileds-elements';
		if (!empty($fields_data)) :
			foreach ($fields_data as $indx => $section) :
				$sectiontitle = $section['sectiontitle'] ?? '';
				$sectionId = isset($section['id']) ? $section['id'] : '';

				$section_index = $indx + 1;
?>
				<div class="adqs-singleSection" id="adqs-singleSection_<?php echo esc_attr($section_index); ?>">

					<?php if (get_post_meta($post_id, "adqs_mtsh_hide_{$sectionId}", true) !== 'yes') : ?>
						<h2 class="adqs-main-section-title">
							<?php echo esc_html($sectiontitle); ?>
						</h2>
					<?php endif; ?>
					<?php
					$skip_fields = apply_filters('adqs_skip_single_listing_meta_fileds', [], $post_id);
					if (isset($section['fields']) && !empty($section['fields'])) {
						foreach ($section['fields'] as $data) {
							$inputtype = $data['input_type'] ?? '';


							if (!empty($inputtype)) {
								adqs_get_template_part($dir . '/' . $inputtype, null, compact('post_id', 'Helper', 'data', 'skip_fields'));
							}
						}
					}
					?>
				</div>
		<?php

			endforeach;
		endif;
	}
}


/**
 * Fav listing add butto to grid
 */

if (!function_exists('adqs_fav_btn_add_to_grid')) {
	function adqs_fav_btn_add_to_grid($id)
	{
		$user_id = get_current_user_id();

		$fav_list = !empty(get_user_meta($user_id, 'adqs_user_fav_list', true)) ? get_user_meta($user_id, 'adqs_user_fav_list', true) : [];

		?>
		<div class="qsd-single-group adqs-add-fav-btn <?php echo in_array($id, $fav_list) ? 'adqs-active-fav' : '' ?>" id=""
			data-fav-id="<?php echo esc_attr($id); ?>">
			<button>
				<svg width="22" height="22" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M10 2.52422L10.765 1.70229C12.8777 -0.567429 16.3029 -0.567429 18.4155 1.70229C20.5282 3.972 20.5282 7.65194 18.4155 9.92165L11.5301 17.3191C10.685 18.227 9.31495 18.227 8.4699 17.3191L1.58447 9.92165C-0.528156 7.65194 -0.528155 3.972 1.58447 1.70229C3.69709 -0.56743 7.12233 -0.567428 9.23495 1.70229L10 2.52422ZM15 2.25C14.5858 2.25 14.25 2.58579 14.25 3C14.25 3.41421 14.5858 3.75 15 3.75C15.6904 3.75 16.25 4.30964 16.25 5C16.25 5.41421 16.5858 5.75 17 5.75C17.4142 5.75 17.75 5.41421 17.75 5C17.75 3.48122 16.5188 2.25 15 2.25Z"
						fill="#EB5757" />
				</svg>
			</button>
		</div>
<?php }
}


if (!function_exists('adqs_mail_to_admin')) {
	function adqs_mail_to_admin($user_id)
	{
		if ($user_id > 0) {
			$user_name = '';
			$user_email = '';
			$user = get_user_by('ID', $user_id);

			if ($user) {
				// Retrieve the user's email and username
				$user_email = $user->user_email;
				$user_name = $user->user_login;
			}

			$user = new EmailSender();
			$user->send_mail('new_user_reg', 'admin', array('user_email' => $user_email, 'user_name' => $user_name));
		}
	}
}

if (!function_exists('adqs_mail_to_user')) {
	function adqs_mail_to_user($user_id)
	{

		if ($user_id > 0) {
			$user_name = '';

			$user = get_user_by('ID', $user_id);
			if ($user) {
				// Retrieve the user's email and username
				$user_name = $user->user_login;
				$user_email = $user->user_email;
			}

			$user = new EmailSender();
			$user->send_mail('new_user_reg', 'user', array('user_email' => $user_email, 'user_name' => $user_name));
		}
	}
}
if (!function_exists('get_author_info_by_id')) {
	function get_author_info_by_id($post_id, $column)
	{
		// Get the email of the author
		$author_id = get_post_field('post_author', $post_id);

		// Get the email of the author
		$author_column = get_the_author_meta($column, $author_id);

		return $author_column;
	}
}


if (!function_exists('adqs_new_listing_mail_admin')) {
	function adqs_new_listing_mail_admin($list_id)
	{
		$title = get_the_title($list_id);
		$submitted_by = get_author_info_by_id($list_id, 'user_login');
		$user_email = get_author_info_by_id($list_id, 'user_email');
		$user = new EmailSender();
		$user->send_mail('new_listing_sub', 'admin', array('title' => $title, 'submitted_by' => $submitted_by, 'user_email' => $user_email));
	}
}

if (!function_exists('adqs_new_listing_mail_user')) {
	function adqs_new_listing_mail_user($list_id)
	{
		$title = get_the_title($list_id);
		$submitted_by = get_author_info_by_id($list_id, 'user_login');
		$user_email = get_author_info_by_id($list_id, 'user_email');
		$user = new EmailSender();
		$user->send_mail('new_listing_sub', 'user', array('title' => $title, 'submitted_by' => $submitted_by, 'user_email' => $user_email));
	}
}

if (!function_exists('adqs_updated_listing_mail')) {
	function adqs_updated_listing_mail($post_ID, $post_after, $post_before)
	{

		if (get_post_type($post_ID) !== "adqs_directory") {
			return;
		}

		$title = get_the_title($post_ID);
		$user_name = get_author_info_by_id($post_ID, 'user_login');
		$user = new EmailSender();
		if ($post_after->post_status === 'publish' && $post_before->post_status !== 'publish') {
			$user_email = get_author_info_by_id($post_ID, 'user_email');
			$user->send_mail('listing_is_approved', 'user', array('title' => $title, 'user_name' => $user_name, 'user_email' => $user_email));
		}

		$updated_by = get_post_meta($post_ID, '_edit_last', true);
		$updated_by_email = get_the_author_meta('user_email', $updated_by,);
		$user->send_mail('new_listing_up', 'admin', array('title' => $title, 'updated_by' => $updated_by_email));
	}
}

if (!function_exists('adqs_post_status_change_mail')) {
	function adqs_post_status_change_mail($new_status, $old_status, $post)
	{
		if (get_post_type($post->ID) !== "adqs_directory") {
			return;
		}

		if ($new_status === 'publish' && $old_status !== 'publish') {
			$title = get_the_title($post->ID);
			$user_name = get_author_info_by_id($post->ID, 'user_login');
			$user = new EmailSender();
			$user_email = get_author_info_by_id($post->ID, 'user_email');
			$user->send_mail('listing_is_approved', 'user', array('title' => $title, 'user_name' => $user_name, 'user_email' => $user_email));
		}
	}
}

if (!function_exists('adqs_wc_order_mail_admin')) {
	function adqs_wc_order_mail_admin($id, $post_obj)
	{
		if (get_post_type($post->ID ?? 0) !== "adqs_directory") {
			return;
		}

		if (($new_status ?? '') === 'publish' && ($old_status ?? '') !== 'publish') {
			$title = get_the_title($post->ID ?? '');
			$user_name = get_author_info_by_id($post->ID ?? 0, 'user_login');
			$user = new EmailSender();
			$user_email = get_author_info_by_id($post->ID ?? 0, 'user_email');
			$user->send_mail('listing_is_approved', 'user', array('title' => $title, 'user_name' => $user_name, 'user_email' => $user_email));
		}
	}
}



if (!function_exists('adqs_wc_order_mail_user')) {
	function adqs_wc_order_mail_user($id, $post_obj)
	{
		if (get_post_type($post->ID ?? 0) !== "adqs_directory") {
			return;
		}

		if (($new_status ?? '') === 'publish' && ($old_status ?? '') !== 'publish') {
			$title = get_the_title($post->ID ?? 0);
			$user_name = get_author_info_by_id($post->ID ?? 0, 'user_login');
			$user = new EmailSender();
			$user_email = get_author_info_by_id($post->ID ?? 0, 'user_email');
			$user->send_mail('listing_is_approved', 'user', array('title' => $title, 'user_name' => $user_name, 'user_email' => $user_email));
		}
	}
}

if (!function_exists('adqs_order_created_mail_to_both')) {
	function adqs_order_created_mail_to_both($order_id, $customer_id, $order_url = "")
	{
		$customer_name  = get_author_info_by_id($customer_id, 'user_login');
		$user_email = get_author_info_by_id($customer_id, 'user_email');
		$user = new EmailSender();
		$user->send_mail('order_created', 'admin', array('order_id' => $order_id, 'customer_name' => $customer_name, 'user_email' => $user_email));
		$user->send_mail('order_created', 'user', array('order_id' => $order_id, 'customer_name' => $customer_name, 'user_email' => $user_email));
	}
}

if (!function_exists('adqs_order_completed_mail_to_both')) {
	function adqs_order_completed_mail_to_both($order_id, $customer_id, $order_url = "")
	{
		$customer_name  = get_author_info_by_id($customer_id, 'user_login');
		$user_email = get_author_info_by_id($customer_id, 'user_email');
		$user = new EmailSender();
		$user->send_mail('order_completed', 'admin', array('order_id' => $order_id, 'customer_name' => $customer_name, 'user_email' => $user_email));
		$user->send_mail('order_completed', 'user', array('order_id' => $order_id, 'customer_name' => $customer_name, 'user_email' => $user_email));
	}
}
