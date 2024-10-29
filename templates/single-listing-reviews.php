<?php

/**
 * The Template for displaying all single Listing Review
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing-reviews.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (post_password_required()) {
	return;
}
$post_id = get_the_ID();
$Helper = AD()->Helper;
?>

<div id="comments" class="comments-area qsd-comments-area">
	<div class="comments-section" id="adqs_comments_section">
		<div class="listing-grid-review">

			<div class="listing-grid-review-top">
				<?php if (get_comments_number()) : ?>
					<h2 class="listing-grid-review-title">

						<?php
						$avgRatings = $Helper->get_post_average_ratings($post_id);
						?>
						<div class="qsd-avgRatings"><?php echo esc_html($avgRatings); ?></div>
						<div class="qsd-avgRatings-overview">
							<?php $Helper->get_review_rating_html($avgRatings); ?>
							<div class="qsd-totelReview"><?php
															$countReview = get_comments_number();
															$reviewText =  $countReview > 1 ? esc_html__('Reviews', 'adirectory') : esc_html__('Review', 'adirectory');
															echo esc_html($countReview) . ' ' . esc_html($reviewText);

															?>
							</div>
						</div>
					</h2>
				<?php endif; ?>
				<?php if (!(!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments'))) : ?>
					<a href="#adqs_writeReview" class="review-top-btn">
						<span>
							<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M1.29199 4.49996C1.29199 3.00419 2.50455 1.79163 4.00033 1.79163H9.00033C9.3455 1.79163 9.62533 1.5118 9.62533 1.16663C9.62533 0.821448 9.3455 0.541626 9.00033 0.541626H4.00033C1.8142 0.541626 0.0419922 2.31383 0.0419922 4.49996V14.5C0.0419922 16.6861 1.8142 18.4583 4.00033 18.4583H14.0003C16.1865 18.4583 17.9587 16.6861 17.9587 14.5V9.49996C17.9587 9.15478 17.6788 8.87496 17.3337 8.87496C16.9885 8.87496 16.7087 9.15478 16.7087 9.49996V14.5C16.7087 15.9957 15.4961 17.2083 14.0003 17.2083H4.00033C2.50455 17.2083 1.29199 15.9957 1.29199 14.5V4.49996ZM12.6829 1.73086C13.4352 0.978547 14.6549 0.978547 15.4072 1.73086L16.7694 3.09305C17.5217 3.84537 17.5217 5.06511 16.7694 5.81743L15.5617 7.02519C15.4668 6.97824 15.3671 6.92747 15.2637 6.87302C14.5675 6.50651 13.7413 5.9921 13.1248 5.37554C12.5082 4.75898 11.9938 3.93283 11.6273 3.23665C11.5728 3.1332 11.5221 3.03344 11.4751 2.93862L12.6829 1.73086ZM12.2409 6.25942C12.9705 6.98902 13.8984 7.56324 14.6332 7.95364L9.88369 12.7032C9.58895 12.9979 9.20657 13.1891 8.79394 13.248L5.93335 13.6567C5.29766 13.7475 4.75278 13.2026 4.8436 12.5669L5.25225 9.70634C5.3112 9.29371 5.50239 8.91133 5.79713 8.61659L10.5467 3.86706C10.937 4.60191 11.5113 5.52981 12.2409 6.25942Z" fill="white" />
							</svg>
						</span>
						<?php echo esc_html__('Write a Review', 'adirectory'); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php
			// You can start editing here -- including this comment!
			if (have_comments()) :
				$per_page = apply_filters('adqs_show_review_per_page', 3);

			?>

				<div class="adqs_comments_items_wrap">
					<?php
					$comment_args = array(
						'post_id' => $post_id,
						'number' => $per_page,
						'parent' => 0,
						'status'  => 'approve',
					);

					if (is_user_logged_in()) {
						$comment_args['include_unapproved'] = array(get_current_user_id());
					} else {
						$unapproved_email = wp_get_unapproved_comment_author_email();

						if ($unapproved_email) {
							$comment_args['include_unapproved'] = array($unapproved_email);
						}
					}
					$comments = get_comments($comment_args);

					if (!empty($comments)) :
						global $comment;
						foreach ($comments as $comment) :
							adqs_get_template_part('single-listing/review');

						endforeach;
					endif;

					?>
				</div><!-- .comment-list -->
				<?php if (get_comments_number() > $per_page) :
					$current_cpage = get_query_var('cpage') ? get_query_var('cpage')  : 1;
				?>
					<div class="review-btn-main" data-per-page="<?php echo esc_attr($per_page); ?>" data-current-page="<?php echo esc_attr($current_cpage); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
						<button class="review-btn qsd-review-more"><?php echo esc_html__('See More Reviews', 'adirectory'); ?></button>
					</div>
				<?php endif; ?>
			<?php
			endif; // Check for have_comments().
			?>
		</div>
	</div><!-- .comments-section -->
	<?php
	/* ============================================
	  Comment Forms
	=============================================== */
	if (comments_open()) : ?>

		<div class="write-review" id="adqs_writeReview">
			<h2 class="write-review-title"><?php echo esc_html__('Write Review', 'adirectory'); ?></h2>
			<div class="write-review-item">

				<?php ob_start(); ?>
				<fieldset class="comments-rating">
					<h3 class="write-review-inner-txt"><?php echo esc_html__('Rating', 'adirectory'); ?><span class="required">*</span></h3>
					<span class="rating-container">
						<?php for ($i = 5; $i >= 1; $i--) : ?>
							<input type="radio" id="rating-<?php echo esc_attr($i); ?>" name="adqs_review_rating" value="<?php echo esc_attr($i); ?>" /><label for="rating-<?php echo esc_attr($i); ?>"></label>
						<?php endfor; ?>
					</span>
				</fieldset>
				<?php wp_nonce_field('review_action', 'review_nonce'); ?>
				<?php $review_field = ob_get_clean(); ?>


				<?php ob_start(); ?>
				<div class="form-item-two">
					<div class="form-item-inner">
						<label class="label-txt"><?php echo esc_html__('Name', 'adirectory'); ?> <span class="required">*</span></label>
						<div class="form-item-inner-box">
							<div class="icon">
								<span>
									<svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M7 8C9.20914 8 11 6.20914 11 4C11 1.79086 9.20914 0 7 0C4.79086 0 3 1.79086 3 4C3 6.20914 4.79086 8 7 8ZM7 18C10.866 18 14 16.2091 14 14C14 11.7909 10.866 10 7 10C3.13401 10 0 11.7909 0 14C0 16.2091 3.13401 18 7 18Z" fill="#DBEAFF" />
										<path fill-rule="evenodd" clip-rule="evenodd" d="M7 8C9.20914 8 11 6.20914 11 4C11 1.79086 9.20914 0 7 0C4.79086 0 3 1.79086 3 4C3 6.20914 4.79086 8 7 8ZM7 18C10.866 18 14 16.2091 14 14C14 11.7909 10.866 10 7 10C3.13401 10 0 11.7909 0 14C0 16.2091 3.13401 18 7 18Z" fill="black" fill-opacity="0.2" />
									</svg>
								</span>
							</div>
							<input id="author" placeholder="<?php echo esc_attr__('Your name...', 'adirectory'); ?>" name="author" type="text" value="<?php echo esc_attr($commenter['comment_author']); ?>" maxlength="245" required="required">
						</div>
					</div>
					<?php $author_field = ob_get_clean(); ?>

					<?php ob_start(); ?>
					<div class="form-item-inner">
						<label class="label-txt"><?php echo esc_html__('Email', 'adirectory'); ?> <span class="required">*</span></label>
						<div class="form-item-inner-box">
							<div class="icon">
								<span>
									<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M4 0C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4C20 1.79086 18.2091 0 16 0H4ZM4.41603 4.37592C4.07138 4.14616 3.60573 4.23929 3.37597 4.58393C3.1462 4.92858 3.23933 5.39423 3.58398 5.624L7.36518 8.1448C8.9607 9.20848 11.0393 9.20848 12.6348 8.14479L16.416 5.624C16.7607 5.39423 16.8538 4.92858 16.624 4.58393C16.3943 4.23929 15.9286 4.14616 15.584 4.37592L11.8028 6.89672C10.7111 7.6245 9.2889 7.6245 8.19723 6.89672L4.41603 4.37592Z" fill="#DBEAFF" />
										<path fill-rule="evenodd" clip-rule="evenodd" d="M4 0C1.79086 0 0 1.79086 0 4V14C0 16.2091 1.79086 18 4 18H16C18.2091 18 20 16.2091 20 14V4C20 1.79086 18.2091 0 16 0H4ZM4.41603 4.37592C4.07138 4.14616 3.60573 4.23929 3.37597 4.58393C3.1462 4.92858 3.23933 5.39423 3.58398 5.624L7.36518 8.1448C8.9607 9.20848 11.0393 9.20848 12.6348 8.14479L16.416 5.624C16.7607 5.39423 16.8538 4.92858 16.624 4.58393C16.3943 4.23929 15.9286 4.14616 15.584 4.37592L11.8028 6.89672C10.7111 7.6245 9.2889 7.6245 8.19723 6.89672L4.41603 4.37592Z" fill="black" fill-opacity="0.2" />
									</svg>
								</span>
							</div>
							<input id="email" placeholder="<?php echo esc_attr__('Your email...', 'adirectory'); ?>" name="email" type="email" value="<?php echo esc_attr($commenter['comment_author_email']); ?>" maxlength="100" required>
						</div>
					</div>
				</div>
				<?php $email_field = ob_get_clean(); ?>
				<?php
				$fields = apply_filters('adqs_comment_form_default_fields', [
					'author' => $author_field,
					'email' => $email_field
				]);
				$defaults = array(
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields' => $fields,
					'id_form'              => 'commentform',
					'id_submit'            => 'submit',
					'title_reply'          => esc_html__('Write a Review', 'adirectory'),
					'class_submit' 		   => 'btn btn-sky submit',
					'label_submit'         => esc_html__('Submit Review', 'adirectory'),
					'submit_button'        => '<button id="adqs_reviewSubmit" class="from-btn">%4$s</button>',
					'submit_field'         => '<p class="form-submit from-btn-main">%1$s %2$s</p>',
					'comment_field'         => $review_field . '<div class="form-item">
				<label class="label-txt">' . esc_attr__('Your Review', 'adirectory') . ' <span class="required">*</span></label>
					<textarea id="comment" name="comment" class="form-control"  rows="5"
						placeholder="' . esc_attr__('Type review...', 'adirectory') . '" maxlength = "5000" required></textarea>
					<p class="form-item-dec">' . esc_html__('Maximum character must be 5000', 'adirectory') . '</p>
				</div>',
				);
				comment_form($defaults);
				?>
			</div>

		</div>
	<?php endif; ?>
</div><!-- #comments -->