<?php

/**
 * The template for displaying listing descrption content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/desciption.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$redirect_to = isset($_GET['redirect_to']) ? sanitize_url($_GET['redirect_to']) : adqs_get_permalink_by_key('adqs_user_dashboard');
?>
<form method="post">
	<div class="adqs-form-fields">
		<label for="username"><?php echo esc_html__('Username', 'adirectory'); ?></label>
		<div class="adqs-input-wrapper">
			<input type="text" name="log" size="20">
			<div class="icon">
				<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M3.66688 0.0388336C1.7032 0.0388336 0.111328 1.64617 0.111328 3.62891V12.6041C0.111328 14.5868 1.7032 16.1942 3.66688 16.1942H14.3336C16.2972 16.1942 17.8891 14.5868 17.8891 12.6041V3.62891C17.8891 1.64617 16.2972 0.0388336 14.3336 0.0388336H3.66688ZM4.03669 3.9663C3.73033 3.76009 3.31642 3.84367 3.11219 4.153C2.90795 4.46233 2.99073 4.88026 3.29709 5.08648L6.65815 7.34894C8.0764 8.30361 9.92405 8.30361 11.3423 7.34894L14.7034 5.08648C15.0097 4.88026 15.0925 4.46233 14.8883 4.153C14.684 3.84367 14.2701 3.76009 13.9638 3.9663L10.6027 6.22877C9.63231 6.88197 8.36813 6.88197 7.39775 6.22877L4.03669 3.9663Z"
						fill="#DBEAFF" />
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M3.66688 0.0388336C1.7032 0.0388336 0.111328 1.64617 0.111328 3.62891V12.6041C0.111328 14.5868 1.7032 16.1942 3.66688 16.1942H14.3336C16.2972 16.1942 17.8891 14.5868 17.8891 12.6041V3.62891C17.8891 1.64617 16.2972 0.0388336 14.3336 0.0388336H3.66688ZM4.03669 3.9663C3.73033 3.76009 3.31642 3.84367 3.11219 4.153C2.90795 4.46233 2.99073 4.88026 3.29709 5.08648L6.65815 7.34894C8.0764 8.30361 9.92405 8.30361 11.3423 7.34894L14.7034 5.08648C15.0097 4.88026 15.0925 4.46233 14.8883 4.153C14.684 3.84367 14.2701 3.76009 13.9638 3.9663L10.6027 6.22877C9.63231 6.88197 8.36813 6.88197 7.39775 6.22877L4.03669 3.9663Z"
						fill="black" fill-opacity="0.2" />
				</svg>
			</div>

		</div>

	</div>
	<div class="adqs-form-fields">
		<label for="password"><?php echo esc_html__('Password', 'adirectory'); ?></label>
		<div class="adqs-input-wrapper">
			<input type="password" name="password" name="pwd" spellcheck="false" size="20">
			<div class="icon">
				<i class="fa fa-eye" aria-hidden="true"></i>
				<i class="fa fa-eye-slash" aria-hidden="true"></i>
			</div>
		</div>

	</div>

	<div class="adqs-form-fields rememeber-check">
		<input type="checkbox" name="keeplogged" value="forever" id="remember-check">
		<label for="remember-check"><?php echo esc_html__('Keep me logged in', 'adirectory'); ?></label>
	</div>

	<span class="error"></span>
	<input type="hidden" name="adqs_login_form" value="true" />
	<input name="adqs_login_regi_submit" value="Sign In" type="submit" class="adqs-log-regi-btn" />

	<?php

	wp_nonce_field('adqs-login', 'adqs-login-nonce');

	if (!empty($_GET['adpc_priceing'] ?? '')) {
		$adpViewPage_id = absint(get_option('adp_view_page_id'));
		if (!empty($adpViewPage_id)) {
			$adp_pricing = $_GET['adpc_priceing'];
			$redirect_to = get_permalink($adpViewPage_id) . "ad-pricing/{$adp_pricing}";
		}
	}


	echo sprintf('<input type="hidden" name="redirect_to" value="%s" />', esc_url($redirect_to));

	?>
</form>
<?php do_action("adqs_social_login"); ?>