<?php

/**
 * The Template for displaying all archive-listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive-listing.php.
 *

 * @package     Adqs Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

extract($args);

function is_pricing_available()
{
	$plugins = get_option("active_plugins", []);
	$enable_pricing = get_option("adqs_enable_pricing", "0");
	$pricing_order = get_post_meta(isset($_GET['postid']) ? $_GET['postid'] : 0, 'adqs_pricing_order_no', true);

	if (empty($pricing_order) && isset($_GET['postid'])) {
		return false;
	}
	if (!in_array('ad-pricing-package/ad-pricing-package.php', $plugins) && !in_array('ad-wc-pricing-package/ad-wc-pricing-package.php', $plugins)) {
		return false;
	}
	return true;
}

$plugins_installed = get_option("active_plugins", []);

$ispricing = is_pricing_available();

$pricing_active = is_pricing_available()
	? (in_array('ad-pricing-package/ad-pricing-package.php', $plugins_installed) ? 'self' : 'wc')
	: '';



if (isset($_POST['submit'])) {



	$preseet_fields = ['address', 'businesshour', 'fax', 'map', 'phone', 'pricing', 'tagline', 'video', 'view_count', 'website', 'zip'];

	$custom_fields = ['checkbox', 'date', 'field_images', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url'];

	$posts_args = array(
		'post_title' => sanitize_text_field($_POST['post_title']),
		'post_content' => sanitize_textarea_field($_POST['post_content']),
		'post_type' => 'adqs_directory',
		'post_status' => current_user_can('manage_options') ? 'publish' : 'pending'
	);




	if (isset($_POST['tax_input'])) {
		$posts_args['tax_input'] = $_POST['tax_input'];
	}

	if (isset($_GET['postid'])) {
		$posts_args['ID'] = absint($_GET['postid']);
		$post_id = wp_update_post($posts_args);
		do_action("adqs_listing_updated", $post_id);
	} else {
		$post_id = wp_insert_post($posts_args);
		if (isset($_GET['adqs_order'])) {
			update_post_meta($post_id, 'adqs_pricing_order_no', absint($_GET['adqs_order']));
		}
		do_action("adqs_new_listing_submitted", $post_id);
	}

	if (isset($_POST['feat_thumbnail_id'])) {
		if ($_POST['feat_thumbnail_id'] !== '0' && !empty($_POST['feat_thumbnail_id'])) {
			update_post_meta($post_id, '_thumbnail_id', sanitize_text_field($_POST['feat_thumbnail_id']));
		}
	}


	if (isset($_POST['slider_thumbnail_id'])) {
		if ($_POST['slider_thumbnail_id'] !== '0' && !empty($_POST['slider_thumbnail_id'])) {
			$sliderids = explode(',', sanitize_text_field($_POST['slider_thumbnail_id']));
			update_post_meta($post_id, '_images', $sliderids);
		}
	}

	update_post_meta($post_id, 'adqs_directory_type', absint($directory_type));

	if (isset($_POST['adqs_plan_pkg'])) {

		$order_id = isset($_GET['adqs_order']) ? $_GET['adqs_order'] : 0;
		if ($pricing_active === "self") {
			if ($_POST['adqs_plan_pkg'] === "adqs_reg_list") {

				$regular_listing = get_user_meta(get_current_user_id(), "adp_order_reg_listing_{$order_id}", true);

				$regular_listing--;

				update_user_meta(get_current_user_id(), "adp_order_reg_listing_{$order_id}", $regular_listing);
			} else {
				$featured_listing = get_user_meta(get_current_user_id(), "adp_order_fea_listing_{$order_id}", true);
				$featured_listing--;

				update_user_meta(get_current_user_id(), "adp_order_fea_listing_{$order_id}", $featured_listing);
				update_post_meta($post_id, '_is_featured', 'yes');
			}
		} else {
			if ($_POST['adqs_plan_pkg'] === "adqs_reg_list") {

				$regular_listing = get_user_meta(get_current_user_id(), "adp_wc_order_reg_listing_{$order_id}", true);

				$regular_listing--;

				update_user_meta(get_current_user_id(), "adp_wc_order_reg_listing_{$order_id}", $regular_listing);
			} else {
				$featured_listing = get_user_meta(get_current_user_id(), "adp_wc_order_fea_listing_{$order_id}", true);
				$featured_listing--;

				update_user_meta(get_current_user_id(), "adp_wc_order_fea_listing_{$order_id}", $featured_listing);
				update_post_meta($post_id, '_is_featured', 'yes');
			}
		}
	}




	$listing_fields = adqs_get_listing_fields($directory_type) ?? array();

	$custom_fields_data = array();

	foreach ($listing_fields as $section) {
		$sectionFields = $section['fields'] ?? array();
		foreach ($sectionFields as $field) {
			$input_type = $field['input_type'] ?? '';
			if (in_array($input_type, $custom_fields)) {
				$fieldid                        = $field['fieldid'] ?? '';
				$custom_fields_data[$fieldid] = $input_type;
			}
		}
	}


	if (!empty($custom_fields_data) && is_array($custom_fields_data)) {
		foreach ($custom_fields_data as $id => $input_type) {
			$meta_key = sanitize_key("_{$input_type}_{$id}");

			switch ($input_type) {
				case 'text':
				case 'number':
				case 'date':
				case 'time':
				case 'select':
				case 'radio':
					update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$meta_key] ?? ''));
					break;
				case 'url':
					update_post_meta($post_id, $meta_key, sanitize_url($_POST[$meta_key] ?? 0));
					break;
				case 'textarea':
					update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$meta_key] ?? 0));

					$meta_key = sanitize_key("_{$input_type}_list_{$id}");

					update_post_meta($post_id, $meta_key, $_POST[$meta_key] ?? '');
					break;
				case 'checkbox':
					$getData = $_POST[$meta_key] ?? array();;
					$getData = map_deep($getData, 'sanitize_text_field');
					update_post_meta($post_id, $meta_key, $getData);
					break;
				case 'field_images':
					// $getData = $Helper->post_data( $_POST, $meta_key, array() );
					// $getData = map_deep( $getData, 'absint' );
					// update_post_meta( $post_id, $meta_key, $getData );
					// break;
			}
		}
	}

	if (!empty($preseet_fields) && is_array($preseet_fields)) {
		foreach ($preseet_fields as $name_slug) {
			$meta_key  = sanitize_key("_{$name_slug}");
			switch ($name_slug) {
				case 'address':
				case 'fax':
				case 'phone':
				case 'tagline':
				case 'view_count':
				case 'zip':
					update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$meta_key] ?? 0));
					break;
				case 'video':
				case 'website':
					update_post_meta($post_id, $meta_key, sanitize_url($_POST[$meta_key] ?? 0));
					break;
				case 'map':
					$_map_lat  =  $_POST['_map_lat'] ?? '';
					$_map_lon  =  $_POST['_map_lon'] ?? '';
					$_hide_map =  $_POST['_hide_map'] ?? '0';
					update_post_meta($post_id, '_map_lat', sanitize_text_field($_map_lat));
					update_post_meta($post_id, '_map_lon', sanitize_text_field($_map_lon));
					update_post_meta($post_id, '_hide_map', sanitize_text_field($_hide_map));
					break;
				case 'pricing':
					$_price_type  = $_POST['_price_type'] ?? '';
					$_price       = $_POST['_price'] ?? '';
					$_price_sub   = $_POST['_price_sub'] ?? '';
					$_price_range = $_POST['_price_range'] ?? '';

					update_post_meta($post_id, '_price_type', sanitize_text_field($_price_type));
					update_post_meta($post_id, '_price', sanitize_text_field($_price));
					update_post_meta($post_id, '_price_sub', sanitize_text_field($_price_sub));
					update_post_meta($post_id, '_price_range', sanitize_text_field($_price_range));
					break;
				case 'businesshour':
					$business_hour_data = $_POST['bhc'] ?? array();
					update_post_meta($post_id, 'adqs_bsuiness_data', $business_hour_data);
			}
		}
	}



	$loginPage = adqs_get_permalink_by_key('adqs_user_dashboard'); ?>

<script>
if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
    window.location.href = '<?php echo esc_url($loginPage); ?>';
}
</script>



<?php }  ?>
<div class="adqs-frontend-add-list">
    <form action="" method="post">
        <?php wp_nonce_field('directory_type', 'adqs_select_directory');

		$extensions = get_option("active_plugins", []);

		if ((in_array('ad-pricing-package/ad-pricing-package.php', $extensions) || in_array('ad-wc-pricing-package/ad-wc-pricing-package.php', $extensions)) && !$post_id) {

			$pricing_plans = [];
			if ($pricing_active === 'self') {
				$pricing_plans = !empty(adp_get_price_and_order_by_type($directory_type, "completed", get_current_user_id())) ? adp_get_price_and_order_by_type($directory_type, "completed", get_current_user_id()) : [];
			} else {
				if (function_exists('adp_wc_order_by_directory_id')) {
					$pricing_plans = !empty(adp_wc_order_by_directory_id($directory_type, get_current_user_id())) ? adp_wc_order_by_directory_id($directory_type, get_current_user_id()) : [];
				}
			}

			if (empty($pricing_plans)) {
				ob_start();

				$loginPage = adqs_get_permalink_by_key('adqs_pricing_package');
				if (!empty($loginPage)) :
		?>
        <script>
        if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
            window.location.href = '<?php echo esc_url($loginPage); ?>';
        }
        </script>
        <?php
				endif;
				exit;
				return ob_get_clean();
			} else {

				if (!isset($_GET['adqs_order'])) {
					adqs_get_template_part('listing-add/plan', 'select', compact('pricing_plans', 'ispricing', 'directory_type', 'pricing_active'));
				}

				if (isset($_GET['adqs_listing_type']) && isset($_GET['adqs_order'])) {
					if (!$post_id) {
						adqs_get_template_part('listing-add/pacakge', 'select', compact('pricing_active', 'ispricing'));
					}
					adqs_get_template_part('listing-add/listing', 'form', compact('directory_type', 'post_id', 'ispricing', 'pricing_active')); ?>

        <input class="adqs-submit-btn" type="submit" value="Submit"
            name="<?php echo isset($post_id) ? 'submit' : 'update'; ?>" />


        <?php  }
			}
		} else {
			adqs_get_template_part('listing-add/listing', 'form', compact('directory_type', 'ispricing', 'post_id', 'pricing_active'));  ?>
        <input class="adqs-submit-btn" type="submit" value="Submit"
            name="<?php echo isset($post_id) ? 'submit' : 'update'; ?>" />
        <?php  }
		?>

    </form>
</div>