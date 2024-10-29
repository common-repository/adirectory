<?php

namespace ADQS_Directory\Database\Custom_Widgets\All_Widgets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Listings By
 */

class Related_Listings extends \WP_Widget
{


	public $Helper;

	/**
	 * Class Constructor
	 *
	 * @param  /ragister name and uniq id
	 * @return void
	 */
	public function __construct()
	{

		// helper class
		$this->Helper = AD()->Helper;

		parent::__construct(
			'adqs_related_listings',  // Base ID
			esc_html__('AD : Related Listings', 'adirectory'), // Name
			array('description' => esc_html__('You can display Related Listings by Select options.', 'adirectory')) // Args
		);
	}


	/**
	 * Widget Merkup and display
	 *
	 * @param  /args and instance
	 */
	public function widget($args, $instance)
	{
		wp_enqueue_style('adqs_main');
		wp_enqueue_style('tabler');
		$w_args     = $args;
		$w_instance = $instance;
		$widget_id  = $this->id_base;
		$Helper     = $this->Helper;
		echo wp_kses_post($w_args['before_widget']);
		if ($widget_id === 'adqs_related_listings') {
			adqs_get_template_part('global/widgets/listings', 'all', compact('w_args', 'w_instance', 'widget_id', 'Helper'));
		}

		echo wp_kses_post($w_args['after_widget']);
	}

	/**
	 * form input fields
	 *
	 * @param  /instance field data
	 * @return void
	 */
	public function form($instance)
	{
		$h              = $this->Helper;
		$title          = $h->check_data($instance, 'title') ? $instance['title'] : '';
		$relatedDir     = $h->check_data($instance, 'related_directory') ? $instance['related_directory'] : '1';
		$order_by       = $h->check_data($instance, 'order_by') ? $instance['order_by'] : '';
		$display_number = $h->check_data($instance, 'display_number') ? $instance['display_number'] : 5;
?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('related_directory')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Related - view page directory', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('related_directory')); ?>" name="<?php echo esc_attr($this->get_field_name('related_directory')); ?>">
				<option value="1" <?php selected($relatedDir, '1'); ?>>
					<?php echo esc_html__('Yes', 'adirectory'); ?>
				</option>
				<option value="0" <?php selected($relatedDir, '0'); ?>>
					<?php echo esc_html__('No', 'adirectory'); ?>
				</option>

			</select>
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('order_by')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Order By (Popularity)', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('order_by')); ?>" name="<?php echo esc_attr($this->get_field_name('order_by')); ?>">
				<?php
				$orderByItems = apply_filters(
					'adqs_relatedlistings_order_by',
					array(
						'date-desc'    => esc_html__('Latest listings', 'adirectory'),
						'date-asc'     => esc_html__('Oldest listings', 'adirectory'),
						'title-asc'    => esc_html__('A to Z (title)', 'adirectory'),
						'title-desc'   => esc_html__('Z to A (title)', 'adirectory'),
						'price-asc'    => esc_html__('Price (low to high)', 'adirectory'),
						'price-desc'   => esc_html__('Price (high to low)', 'adirectory'),
						'rand'         => esc_html__('Random listings', 'adirectory'),
						'views-desc'   => esc_html__('Popular listings', 'adirectory'),
						'rating-desc'  => esc_html__('5 to 1 (star rating)', 'adirectory'),
						'review-count' => esc_html__('Review Count', 'adirectory'),
					)
				);
				foreach ($orderByItems as $val => $name) :
				?>

					<option value="<?php echo esc_attr($val); ?>" <?php selected($order_by, $val); ?>>
						<?php echo esc_html($name); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('display_number')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Number of listings', 'adirectory'); ?>:</label>
			<input type="number" min="1" id="<?php echo esc_attr($this->get_field_id('display_number')); ?>" name="<?php echo esc_attr($this->get_field_name('display_number')); ?>" value="<?php echo esc_attr($display_number); ?>">
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
		$h                             = $this->Helper;
		$instance                      = array();
		$instance['title']             = $h->check_data($new_instance, 'title') ? sanitize_text_field($new_instance['title']) : '';
		$instance['related_directory'] = $h->check_data($new_instance, 'related_directory') ? sanitize_text_field($new_instance['related_directory']) : '';
		$instance['order_by']          = $h->check_data($new_instance, 'order_by') ? sanitize_text_field($new_instance['order_by']) : '';
		$instance['display_number']    = $h->check_data($new_instance, 'display_number') ? sanitize_text_field($new_instance['display_number']) : 5;
		return $instance;
	}
}