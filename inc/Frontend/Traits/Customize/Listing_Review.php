<?php

namespace ADQS_Directory\Frontend\Traits\Customize;

trait Listing_Review
{
	private $post_type = 'adqs_directory';


	/**
	 * Method comment_review_hooked
	 *
	 * @return void
	 */
	public function comment_review_hooked()
	{

		// Hook to add meta box to comment edit screen
		add_action('add_meta_boxes_comment', array($this, 'add_comment_meta_box'));

		// Edit Comment Rating.
		add_action('edit_comment', array($this, 'edit_comment_rating'));

		// Save the rating submitted by the user.
		add_action('comment_post', array($this, 'save_comment_rating'));

		// require rating submitted by the user.
		add_filter('preprocess_comment', array($this, 'comment_require_rating'));

		// restrict_users only one comment
		add_filter('comments_open', array($this, 'restrict_users'), 10, 2);

		// manage comments admin columns
		if (isset($_GET['adqs_review']) && $_GET['adqs_review'] === 'yes') {
			add_filter('manage_edit-comments_columns', array($this, 'add_comments_columns'));
			add_action('manage_comments_custom_column', array($this, 'add_comment_columns_content'), 10, 2);

			// display if has rating
			add_filter('comments_clauses', array($this, 'table_filter_by_ratings'));
		}
	}

	/**
	 * Add comment meta_box
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_comment_meta_box($comment)
	{
		$comment_ID = !empty($comment->comment_ID) ? absint($comment->comment_ID) : 0;
		$ratings    = get_comment_meta($comment_ID, 'adqs_review_rating', true);
		if (empty($ratings)) {
			return;
		}
		add_meta_box(
			'adqs_listing_ratings_meta_box',
			esc_html__('Directory Listing Ratings', 'adirectory'),
			array($this, 'render_comment_meta_box'),
			'comment',
			'normal',
			'default'
		);
	}

	/**
	 * Render comment meta box field
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function render_comment_meta_box($comment)
	{
		wp_nonce_field(basename(__FILE__), 'adqs_comment_metabox');
		$comment_id = !empty($comment->comment_ID) ? $comment->comment_ID : '';
		$ratings    = get_comment_meta($comment_id, 'adqs_review_rating', true);
?>

		<div class="qsd-form-group qsd-vreview_rating-field">
			<h4 class="qsd-form-label" style="margin-bottom: 8px;font-size: 14px;">
				<?php echo esc_html__('Ratings', 'adirectory'); ?>
			</h4>
			<div class="qsd-form-wrap qsd-form-field">
				<input type="number" name="adqs_review_rating" min="1" max="5" value="<?php echo esc_attr($ratings); ?>" id="adqs_review_rating">
				<p class="qsd-desc" style="margin-top: 8px;font-weight: 500;">
					<?php echo esc_html__('You can update ratings', 'adirectory'); ?>
				</p>
			</div>

		</div>
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


	/**
	 * edit comment rating
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function edit_comment_rating($comment_id)
	{
		if (!isset($_POST['adqs_review_rating']) || !wp_verify_nonce($_POST['adqs_comment_metabox'], basename(__FILE__)) || !current_user_can('edit_comment', $comment_id)) {
			return $comment_id;
		}
		$review_rating = absint($_POST['adqs_review_rating']);
		$ratings       = get_comment_meta($comment_id, 'adqs_review_rating', true);
		update_comment_meta($comment_id, 'adqs_review_rating', $review_rating, $ratings);
	}

	/**
	 * Save Review Rating
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function save_comment_rating($comment_id)
	{

		if (isset($_POST['adqs_review_rating']) && ('' !== $_POST['adqs_review_rating']) && wp_verify_nonce($_POST['review_nonce'], 'review_action')) {
			$rating = intval($_POST['adqs_review_rating']);
			add_comment_meta($comment_id, 'adqs_review_rating', $rating);
		}
	}

	/**
	 * Save Review Rating
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function comment_require_rating($commentdata)
	{
		if (!is_singular($this->post_type)) {
			return $commentdata;
		}
		if (!is_admin() && (!isset($_POST['adqs_review_rating']) || (0 === absint($_POST['adqs_review_rating'])) || !wp_verify_nonce($_POST['review_nonce'], 'review_action'))) {
			wp_die(esc_html__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.', 'adirectory'));
		}
		return $commentdata;
	}

	/**
	 * Only One Review restrict users
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function restrict_users($open, $post_id)
	{
		if (!is_singular($this->post_type)) {
			return $open;
		}
		if (intval($post_id) && get_post($post_id)) {
			$args = array(
				'post_id' => $post_id,
				'count'   => true,
			);
			$user = wp_get_current_user();
			if ($user && intval($user->ID)) { // for registered users
				$skip             = false;
				$ignoreTheseRoles = array('administrator', 'editor'); // which user roles should be ignored
				if ($user->roles && is_array($user->roles)) {
					foreach ($user->roles as $role) {
						if (in_array($role, $ignoreTheseRoles)) {
							$skip = true;
							break;
						}
					}
				}
				if (!$skip) {
					$args['user_id'] = $user->ID;
					$open            = get_comments($args) ? false : true;
				}
			}
		}
		return $open;
	}


	/**
	 * Add comments columns
	 *
	 * @param [type] $cols
	 * @return string
	 */
	public function add_comments_columns($cols)
	{
		$add_columns = array(
			'adqs_ratings' => esc_html__('Ratings', 'adirectory'),
		);
		$cols        = array_slice($cols, 0, 3, true) + $add_columns + array_slice($cols, 3, null, true);

		return $cols;
	}

	/**
	 * Add comment columns content
	 *
	 * @param [type] $column
	 * @param [type] $comment_ID
	 * @return void
	 */
	public function add_comment_columns_content($column, $comment_ID)
	{
		// global $comment;
		if ($column === 'adqs_ratings') {
			$ratings = get_comment_meta($comment_ID, 'adqs_review_rating', true);
			if (!empty($ratings)) {
				AD()->Helper->get_review_rating_html($ratings);
			}
		}
	}

	/**
	 * comment table filter by ratings
	 *
	 * @param [type] $pieces
	 * @return string
	 */
	public function table_filter_by_ratings($pieces)
	{
		global $wpdb;

		// Ensure that the join with commentmeta table is included
		if (false === strpos($pieces['join'], "JOIN $wpdb->commentmeta ON")) {
			$pieces['join'] .= " JOIN $wpdb->commentmeta ON {$wpdb->commentmeta}.comment_id = {$wpdb->comments}.comment_ID";
		}

		// Add condition to filter comments by the meta key and ensure the meta value is not empty
		$pieces['where'] .= " AND {$wpdb->commentmeta}.meta_key = 'adqs_review_rating' AND {$wpdb->commentmeta}.meta_value != ''";

		// Group by comment ID to avoid duplicate comments
		if (!strpos($pieces['groupby'], "{$wpdb->comments}.comment_ID")) {
			$pieces['groupby'] = "{$wpdb->comments}.comment_ID";
		}

		return $pieces;
	}
}
