<?php

namespace ADQS_Directory\Database\Custom_Widgets\All_Widgets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Connect Agent Class
 */

class Advanced_Sidebar_Filter extends \WP_Widget
{


	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(
			'adqs_advanced_sidebar_filter',  // Base ID
			esc_html__('AD : Advanced Sidebar Filter', 'adirectory'), // Name
			array('description' => esc_html__('You can show a advanced listing filter by this widget.', 'adirectory')) // Args
		);
	}


	/**
	 * Widget Merkup and display
	 *
	 * @param  /args and instance
	 */
	public function widget($args, $instance)
	{

		wp_enqueue_style('adqs_single_grid');
		wp_enqueue_script('grid-page-script');

		$w_args     = $args;
		$w_instance = $instance;
		echo wp_kses_post($args['before_widget']);
		adqs_get_template_part('global/widgets/advanced-sidebar', 'filter', compact('w_args', 'w_instance'));
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
		$title       = !empty($instance['title']) ? $instance['title'] : '';
		$listing_url = !empty($instance['listing_url']) ? $instance['listing_url'] : '';
?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('listing_url')); ?>"><?php echo esc_html__('All listing Page Url (Required) :', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('listing_url')); ?>" name="<?php echo esc_attr($this->get_field_name('listing_url')); ?>" type="url" value="<?php echo esc_attr($listing_url); ?>" required>
		</p>
<?php
	}



	/**
	 * Method update
	 *
	 * @param $new_instance $new_instance 
	 * @param $old_instance $old_instance 
	 *
	 * @return array
	 */
	public function update($new_instance, $old_instance)
	{
		$instance                = array();
		$instance['title']       = (isset($new_instance['title']) && !empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['listing_url'] = (isset($new_instance['listing_url']) && !empty($new_instance['listing_url'])) ? sanitize_url($new_instance['listing_url']) : '';
		return $instance;
	}
}
