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

class Locations extends \WP_Widget
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
			'adqs_locations',  // Base ID
			esc_html__('AD : Locations', 'adirectory'), // Name
			array('description' => esc_html__('You can display listings locations.', 'adirectory')) // Args
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
		$related_directory = $instance['related_directory'] ?? '';
		$has_children      = $instance['has_children'] ?? '';
		$directory_type    = $instance['directory_type'] ?? false;
		$order_by          = $instance['order_by'] ?? false;
		$order             = $instance['order'] ?? false;
		$display_number    = isset($instance['display_number']) ? (int) $instance['display_number'] : -1;
		$widget_id         = $this->id_base;
		echo wp_kses_post($w_args['before_widget']);
		if ($widget_id === 'adqs_locations') {
			$options = array(
				'has_input'     => false,
				'has_children'  => false,
				'display_count' => false,
			);

			if ($has_children == '1') {
				$options = array(
					'has_children' => true,
				);
			}

			// Options for related
			if (($related_directory == '1') && is_singular('adqs_directory')) {

				$directory_id = get_post_meta(get_the_ID(), 'adqs_directory_type', true);
				if ($directory_id != '') {
					$options['has_directory'] = true;
					$options['directory_id']  = $directory_id;
				}
			} elseif (!empty($directory_type)) {
				$options['has_directory'] = true;
				$options['directory_id']  = $directory_type;
			}

			$args = array(
				'taxonomy'   => 'adqs_location',
				'hide_empty' => true,
				'order'      => $order,
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

			echo '<ul class="qsd-categories-list">';
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
		$dirType        = $h->check_data($instance, 'directory_type') ? $instance['directory_type'] : '';
		$relatedDir     = $h->check_data($instance, 'related_directory') ? $instance['related_directory'] : '0';
		$has_children   = $h->check_data($instance, 'has_children') ? $instance['has_children'] : '0';
		$order_by       = $h->check_data($instance, 'order_by') ? $instance['order_by'] : '';
		$order          = $h->check_data($instance, 'order') ? $instance['order'] : '';
		$display_number = $h->check_data($instance, 'display_number') ? $instance['display_number'] : '';
?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'adirectory'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>

		<p>
			<?php
			$directory_types = adqs_get_directory_types();
			?>
			<label for="<?php echo esc_attr($this->get_field_id('directory_type')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Directory Type', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('directory_type')); ?>" name="<?php echo esc_attr($this->get_field_name('directory_type')); ?>">
				<option value="">
					<?php echo esc_html__('All', 'adirectory'); ?>
				</option>
				<?php
				if (!empty($directory_types)) :
					foreach ($directory_types as $type) :
				?>
						<option value="<?php echo esc_attr($type->term_id); ?>" <?php selected($dirType, $type->term_id); ?>>
							<?php echo esc_html($type->name); ?>
						</option>
				<?php
					endforeach;
				endif;
				?>
			</select>
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('related_directory')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Related - view page directory', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('related_directory')); ?>" name="<?php echo esc_attr($this->get_field_name('related_directory')); ?>">
				<option value="0" <?php selected($relatedDir, '0'); ?>>
					<?php echo esc_html__('No', 'adirectory'); ?>
				</option>
				<option value="1" <?php selected($relatedDir, '1'); ?>>
					<?php echo esc_html__('Yes', 'adirectory'); ?>
				</option>
			</select>
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('has_children')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Display child', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('has_children')); ?>" name="<?php echo esc_attr($this->get_field_name('has_children')); ?>">
				<option value="0" <?php selected($has_children, '0'); ?>>
					<?php echo esc_html__('No', 'adirectory'); ?>
				</option>
				<option value="1" <?php selected($has_children, '1'); ?>>
					<?php echo esc_html__('Yes', 'adirectory'); ?>
				</option>
			</select>
		</p>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('order_by')); ?>" style="display: block;margin-bottom: 2px;"><?php echo esc_html__('Order By', 'adirectory'); ?>:</label>
			<select id="<?php echo esc_attr($this->get_field_id('order_by')); ?>" name="<?php echo esc_attr($this->get_field_name('order_by')); ?>">
				<?php
				$orderByItems = apply_filters(
					'adqs_locations_order_by',
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
		$h                             = $this->Helper;
		$instance                      = array();
		$instance['title']             = $h->check_data($new_instance, 'title') ? sanitize_text_field($new_instance['title']) : '';
		$instance['related_directory'] = $h->check_data($new_instance, 'related_directory') ? sanitize_text_field($new_instance['related_directory']) : '';
		$instance['has_children']      = $h->check_data($new_instance, 'has_children') ? sanitize_text_field($new_instance['has_children']) : '';
		$instance['directory_type']    = $h->check_data($new_instance, 'directory_type') ? sanitize_text_field($new_instance['directory_type']) : '';
		$instance['order_by']          = $h->check_data($new_instance, 'order_by') ? sanitize_text_field($new_instance['order_by']) : '';
		$instance['order']             = $h->check_data($new_instance, 'order') ? sanitize_text_field($new_instance['order']) : '';
		$instance['display_number']    = $h->check_data($new_instance, 'display_number') ? sanitize_text_field($new_instance['display_number']) : '';
		return $instance;
	}
}
