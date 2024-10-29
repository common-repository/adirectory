<?php

namespace ADQS_Directory\Admin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}


/**
 * Customize handlers class
 */
class Customize
{

	public function __construct()
	{

		// Admin Table Filter
		add_action('restrict_manage_posts', array($this, 'listing_table_filter_fields'));

		// Admin Table Filter
		add_action('pre_get_posts', array($this, 'listing_table_filter'));

		// add user contact methode
		add_filter('user_contactmethods', array($this, 'user_profile_fields'));

		// admin global css
		add_action('admin_head', array($this, 'admin_head_css'));
		// admin global script
		add_action('admin_footer', array($this, 'admin_footer_script'));
	}


	/**
	 * listing table filter fields
	 *
	 * @param [type] $query
	 * @return void
	 */
	public function listing_table_filter_fields($post_type)
	{
		$directory_types = adqs_get_directory_types();
		$nonce           = $_REQUEST['adqs_nonce'] ?? '';

		if (!is_admin() || empty($directory_types) || ($post_type !== 'adqs_directory')) {
			return;
		}

?>
<select name="directory_type" id="filter-directory_type">
    <option value="">
        <?php echo esc_html__('Select directory type', 'adirectory'); ?>
    </option>
    <?php
			$directory_type = (isset($_GET['directory_type']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['adqs_nonce'] ?? '')), 'adqs_action')) ? sanitize_text_field($_GET['directory_type']) : '';

			foreach ($directory_types as $type) :
			?>
    <option value="<?php echo esc_attr($type->term_id); ?>" <?php selected($directory_type, $type->term_id); ?>>
        <?php echo esc_html($type->name); ?>
    </option>
    <?php endforeach; ?>
</select>
<?php
		wp_nonce_field('adqs_action', 'adqs_nonce');
	}

	/**
	 * listing table filter
	 *
	 * @param [type] $query
	 * @return void
	 */
	public function listing_table_filter($query)
	{
		$directory_types = adqs_get_directory_types();
		global $pagenow;
		$nonce     = sanitize_text_field($_GET['adqs_nonce'] ?? '');
		$post_type = sanitize_text_field($_GET['post_type'] ?? '');

		if (!is_admin() || empty($directory_types) || ($pagenow !== 'edit.php') || ($post_type !== 'adqs_directory') || !wp_verify_nonce($nonce, 'adqs_action')) {
			return;
		}
		$directory_type = isset($_GET['directory_type']) ? $_GET['directory_type'] : '';
		if (!empty($directory_type)) {
			$query->set('meta_key', 'adqs_directory_type');
			$query->set('meta_value', $directory_type);
		}
	}


	/**
	 * Add User Contact Info
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function user_profile_fields($contactmethods)
	{
		// Add a new field for Biographical Info
		$contactmethods['adqs_address_info']      = esc_html__('Address', 'adirectory');
		$contactmethods['adqs_phone']             = esc_html__('Phone', 'adirectory');
		$contactmethods['adqs_facebook_profile']  = esc_html__('Facebook profile', 'adirectory');
		$contactmethods['adqs_twitter_profile']   = esc_html__('Twitter profile', 'adirectory');
		$contactmethods['adqs_instagram_profile'] = esc_html__('Instagram profile', 'adirectory');
		$contactmethods['adqs_linked_profile']    = esc_html__('LinkedIn profile', 'adirectory');
		return $contactmethods;
	}

	/**
	 * Admin head css
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_head_css()
	{
	?> <style>
#adminmenu .menu-icon-adqs_directory .wp-menu-image img {
    width: 18px;
    display: inline-block;
}
</style>
<?php
	}

	/**
	 * Admin footer script
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_footer_script()
	{

		if (($_GET['adqs_review'] ?? '') !== 'yes') {
			return;
		}

	?>
<script>
(function($) {
    $('#menu-comments')
        .removeClass('current')
        .addClass('wp-not-current-submenu')
        .find('> a').removeClass('current');

    $('#menu-posts-adqs_directory')
        .removeClass('wp-not-current-submenu')
        .addClass('wp-has-current-submenu wp-menu-open')
        .find('> a')
        .removeClass('wp-not-current-submenu')
        .addClass('wp-has-current-submenu wp-menu-open')
        .end()
        .find('a[href*="page=adqs_directory_review"]')
        .closest('li')
        .addClass('current')
        .find('> a')
        .addClass('current');
})(jQuery);
</script>
<?php
	}
}