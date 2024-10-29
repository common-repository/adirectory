<?php

namespace ADQS_Directory\Database\Custom_Widgets\All_Widgets;

// Traits
use ADQS_Directory\Database\Traits\Common\Render_Data;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Listings By
 */

class Tags extends \WP_Widget
{
	use Render_Data;

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
			'adqs_tags',  // Base ID
			esc_html__('AD : Tags', 'adirectory'), // Name
			array('description' => esc_html__('You can display listings tags.', 'adirectory')) // Args
		);
	}


	/**
	 * Widget Merkup and display
	 *
	 * @param  /args and instance
	 */
	public function widget($w_args, $instance)
	{
		wp_enqueue_style('adqs_main');
		wp_enqueue_style('tabler');
		$order_by       = $instance['order_by'] ?? false;
		$order          = $instance['order'] ?? false;
		$display_number = isset($instance['display_number']) ? (int) $instance['display_number'] : -1;
		$widget_id      = $this->id_base;
		echo wp_kses_post($w_args['before_widget']);
		if ($widget_id === 'adqs_tags') {
			$options = array(
				'has_input'     => false,
				'has_children'  => false,
				'display_count' => false,
				'has_directory' => false,

			);

			$args = array(
				'taxonomy'     => 'adqs_tags',
				'hide_empty'   => true,
				'hierarchical' => false,
				'order'        => $order,
			);
			if (!empty($order_by)) {
				$args['orderby'] = $order_by;
			}
			if (!empty($display_number)) {
				$args['number'] = $display_number;
			}
			echo '<div class="qsd-categories">';

			if (isset($instance['title']) && !empty($instance['title'])) {
				echo wp_kses_post($w_args['before_title'] . apply_filters('widget_title', $instance['title']) . $w_args['after_title']);
			}

			echo '<ul class="qsd-tag">';
			$this->render_tax_lists(
				$args,
				$options
			);
			echo '</ul>';
			echo '</div>';
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
		$order_by       = $h->check_data($instance, 'order_by') ? $instance['order_by'] : '';
		$order          = $h->check_data($instance, 'order') ? $instance['order'] : '';
		$display_number = $h->check_data($instance, 'display_number') ? $instance['display_number'] : '';
?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>

		<p>

			<label for="<?php echo esc_attr($this->get_field_id('order_by')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Order By', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('order_by')); ?>" name="<?php echo esc_attr($this->get_field_name('order_by')); ?>">
				<?php
				$orderByItems = apply_filters(
					'adqs_tags_order_by',
					array(
						'name'        => esc_html__('Name', 'adirectory'),
						'slug'        => esc_html__('Slug', 'adirectory'),
						'term_group'  => esc_html__('Term Group', 'adirectory'),
						'term_id'     => esc_html__('Term id', 'adirectory'),
						'description' => esc_html__('Description', 'adirectory'),
						'parent'      => esc_html__('Parent', 'adirectory'),
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

			<label for="<?php echo esc_attr($this->get_field_id('order')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Order', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
				<option value="DESC" <?php selected($order, 'DESC'); ?>>
					<?php echo esc_html__('Descending', 'adirectory'); ?>
				</option>
				<option value="ASC" <?php selected($order, 'ASC'); ?>>
					<?php echo esc_html__('Ascending', 'adirectory'); ?>
				</option>
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
		$h                          = $this->Helper;
		$instance                   = array();
		$instance['title']          = $h->check_data($new_instance, 'title') ? sanitize_text_field($new_instance['title']) : '';
		$instance['order_by']       = $h->check_data($new_instance, 'order_by') ? sanitize_text_field($new_instance['order_by']) : '';
		$instance['order']          = $h->check_data($new_instance, 'order') ? sanitize_text_field($new_instance['order']) : '';
		$instance['display_number'] = $h->check_data($new_instance, 'display_number') ? sanitize_text_field($new_instance['display_number']) : '';
		return $instance;
	}
}
