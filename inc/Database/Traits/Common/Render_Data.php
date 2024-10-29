<?php

namespace ADQS_Directory\Database\Traits\Common;

trait Render_Data
{


	/**
	 * render tax lists
	 *
	 * @param array   $args
	 * @param boolean $has_directory
	 * @param integer $directory_id
	 * @param array   $saved_data
	 * @return void
	 */
	public function render_tax_lists($args = array(), $options = array())
	{

		$has_input     = $options['has_input'] ?? false;
		$has_directory = $options['has_directory'] ?? false;
		$directory_id  = isset($options['directory_id']) ? absint($options['directory_id']) : 0;
		$has_children  = $options['has_children'] ?? true;
		$saved_data    = $options['saved_data'] ?? array();
		$display_count = $options['display_count'] ?? true;

		$default_args = array(
			'taxonomy'     => 'adqs_category',
			'hide_empty'   => false,
			'hierarchical' => true,
			'parent'       => 0,
		);
		$args         = array_merge($default_args, $args);
		$terms        = get_terms($args);
		$taxonomy     = $args['taxonomy'] ?? 'adqs_category';
		if ($terms) {
			foreach ($terms as $term) {
				$directory_types = get_term_meta($term->term_id, 'listing_types', true);
				$directory_types = !empty($directory_types) ? array_map('intval', $directory_types) : array();
				$checked         = in_array($term->term_id, $saved_data, true) ? 'checked' : '';
				if ($has_directory ? in_array($directory_id, $directory_types, true) : true) :
					$child_terms = get_term_children($term->term_id, $taxonomy);

?>
					<li id="<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>" class="<?php echo !empty($child_terms) ? 'has-children' : 'no-children'; ?>">
						<?php if (!empty($has_input)) : ?>
							<label class="selectit">
								<input value="<?php echo esc_attr($term->term_id); ?>" type="checkbox" name="tax_input[<?php echo esc_attr($taxonomy); ?>][]" id="in-<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>" <?php echo !empty($checked) ? esc_attr($checked) : ''; ?>>
								<?php echo esc_html($term->name); ?>
							</label>
						<?php
						else :
							$icon = get_term_meta($term->term_id, 'adqs_category_icon_id', true);
							$icon = !empty($icon) ? "<i class='{$icon}'></i>" : '';
						?>
							<a href="<?php echo esc_url(get_term_link($term->term_id)); ?>">

								<span>
									<?php if (!empty($icon)) : ?>
										<span class="icon">
											<?php echo wp_kses_post($icon); ?>
										</span>
									<?php endif; ?>
									<span class="txt">
										<?php echo esc_html($term->name); ?>
									</span>
								</span>
								<?php if (!empty($display_count)) : ?>
									(<?php echo !empty($term->count) ? esc_html($term->count) : '0'; ?>)
								<?php endif; ?>
							</a>

						<?php endif; ?>
						<?php

						if (!empty($child_terms) && !empty($has_children)) :
						?>
							<ul class="children">

								<?php
								$this->render_tax_lists(
									array(
										'taxonomy' => $taxonomy,
										'parent'   => $term->term_id,
									),
									$options
								);
								?>
							</ul>

						<?php endif; ?>
					</li>
<?php
				endif;
			}
		}
	}
}
