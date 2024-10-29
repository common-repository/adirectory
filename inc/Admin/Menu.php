<?php

namespace ADQS_Directory\Admin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Menu handlers class
 */
class Menu
{


	/**
	 * Class constructor
	 */

	function __construct()
	{
		add_action('admin_menu', array($this, 'register_sub_menu'));
	}

	/**
	 * All Sub Menu Here
	 */
	public function register_sub_menu()
	{
		add_submenu_page(
			'edit.php?post_type=adqs_directory',
			esc_html__('aDirectory Builder Page', 'adirectory'),
			esc_html__('Settings & Builder', 'adirectory'),
			'manage_options',
			'adqs_directory_builder',
			array($this, 'directory_builder_content')
		);


		add_submenu_page(
			'edit.php?post_type=adqs_directory',
			esc_html__('aDirectory Review Page', 'adirectory'),
			esc_html__('Reviews', 'adirectory'),
			'manage_options',
			'adqs_directory_review',
			function () {
?>
			<script>
				window.location = "<?php echo esc_url(admin_url('edit-comments.php?adqs_review=yes')); ?>";
			</script>
		<?php
			}
		);

		add_submenu_page(
			'edit.php?post_type=adqs_directory',
			esc_html__('CSV Import Export Page', 'adirectory'),
			esc_html__('Import / Export', 'adirectory'),
			'manage_options',
			'adqs_export_import',
			array($this, 'csv_export_import')
		);


		add_submenu_page(
			'edit.php?post_type=adqs_directory',
			esc_html__('Themes & Extension Page', 'adirectory'),
			esc_html__('Themes & Extensions', 'adirectory'),
			'manage_options',
			'adqs_go_pro',
			function () {
		?>
			<script>
				window.location =
					"<?php echo admin_url('edit.php?post_type=adqs_directory&page=adqs_directory_builder&path=go-pro'); ?>";
			</script>
		<?php
			},
		);
	}

	/**
	 * directory builder content
	 *
	 * @return string
	 */

	public function directory_builder_content()
	{
		?>
		<div class="adqs_admin_dashboard" id="adqs_admin_dashboard"></div>
<?php

	}

	/**
	 * CSV Export Import
	 *
	 * @return string
	 */
	public function csv_export_import()
	{

		if (ADQS_DIRECTORY_INC . '/Admin/ImportExport/Form.php') {
			include_once(ADQS_DIRECTORY_INC . '/Admin/ImportExport/Form.php');
		}
	}
}
