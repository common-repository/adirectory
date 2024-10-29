<?php

namespace ADQS_Directory\Admin;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Notice handlers class
 */
class Notice
{
	private $notice_url = 'https://plugins.quomodosoft.com/templates/wp-json/quomodo-notice/v1/remote?type=quomodo-notice-adirectory';

	/**
	 * Class constructor
	 */
	function __construct()
	{
		add_action('admin_notices', [$this, 'add_admin_remote_notice']);
	}

	public function add_admin_remote_notice()
	{

		$data = wp_remote_retrieve_body(wp_remote_get(esc_url_raw($this->notice_url)));

		$_data = json_decode($data, true);

		if (!isset($_data['show'])) {
			return;
		}
		if ($_data['show'] == false) {
			return;
		}

		if (is_wp_error($_data)) {
			return false;
		}

		if ($_data['msg'] == '""') {
			return;
		}

		$notice = $_COOKIE['quomodo-notice-adirectory'] ?? false;
		if ('is_dismissed' === $notice) {
			return '';
		}
?>
		<style>
			.adirectory-admin-notice-remote img {
				max-width: 100%;
			}

			.adirectory-admin-notice-remote .notice-dismiss:before {
				color: red;
				font-size: 20px;
			}
		</style>
		<div class="notice is-dismissible adirectory-admin-notice-remote"
			style="border:0; background:transparent;padding-left:0">
			<div class="notice-content">
				<?php
				echo wp_kses_post(base64_decode($_data['msg']));
				?>
			</div>
			<button type="button" class="notice-dismiss" onclick="adqs_dismissNotice(this)">
				<span class="screen-reader-text">
					<?php echo esc_html__('Dismiss this notice.', 'adirectory'); ?>
				</span>
			</button>
		</div>
		<script>
			function adqs_setCookie(name, value, days) {
				let expires = "";
				if (days) {
					let date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = "; expires=" + date.toUTCString();
				}
				document.cookie = name + "=" + (value || "") + expires + "; path=/";
			}

			function adqs_dismissNotice(button) {
				button.closest('.notice.is-dismissible').remove();
				adqs_setCookie('quomodo-notice-adirectory', 'is_dismissed', 3);
			}
		</script>
<?php

	} // end method
}
