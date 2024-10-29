<?php

namespace ADQS_Directory\Database\Custom_Widgets\All_Widgets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Connect Agent Class
 */

class Connect_Agent extends \WP_Widget
{


	/**
	 * Class Constructor
	 *
	 * @param  /ragister name and uniq id
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(
			'adqs_connect_agent',  // Base ID
			esc_html__('AD : Connect Listing Owner', 'adirectory'), // Name
			array('description' => esc_html__('You can show a form to contact the listing owners by this widget.', 'adirectory')) // Args
		);
	}


	/**
	 * Widget Merkup and display
	 *
	 * @param  /args and instance
	 */
	public function widget($args, $instance)
	{
		if (!is_singular('adqs_directory')) {
			return '';
		}

		$w_args     = $args;
		$w_instance = $instance;
		echo wp_kses_post($args['before_widget']);
		adqs_get_template_part('global/widgets/contact', 'author', compact('w_args', 'w_instance'));
		echo wp_kses_post($args['after_widget']);
	}

	/**
	 * form input fields
	 *
	 * @param  /instance field data
	 * @return void
	 */
	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$onlyForLoginUser = AD()->Helper->check_data($instance, 'only_for_login_user') ? $instance['only_for_login_user'] : '';
		$onlyForLoginUser = ($onlyForLoginUser === 'no') ? 'no' : 'yes';

?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('only_for_login_user')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Only - For login user', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('only_for_login_user')); ?>" name="<?php echo esc_attr($this->get_field_name('only_for_login_user')); ?>">
				<option value="yes" <?php selected($onlyForLoginUser, 'yes'); ?>>
					<?php echo esc_html__('Yes', 'adirectory'); ?>
				</option>
				<option value="no" <?php selected($onlyForLoginUser, 'no'); ?>>
					<?php echo esc_html__('No', 'adirectory'); ?>
				</option>

			</select>
		</p>
<?php
	}


	/**
	 * form methode for update
	 *
	 * @param  /new_instance and old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance)
	{
		$instance          = array();
		$instance['title'] = (isset($new_instance['title']) && !empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['only_for_login_user']    = AD()->Helper->check_data($new_instance, 'only_for_login_user') ? sanitize_text_field($new_instance['only_for_login_user']) : '';
		return $instance;
	}
}
