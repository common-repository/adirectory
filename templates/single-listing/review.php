<?php

/**
 * The template for displaying listing Review item in the single-listing-reviews.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/review.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $comment;
$comment_id = !empty($comment->comment_ID) ? absint($comment->comment_ID) : 0;
$comment_author_id = !empty($comment->user_id) ? absint($comment->user_id) : 0;
?>
<div <?php comment_class('listing-grid-review-item');?> id="comment-id-<?php echo esc_attr($comment_id);?>">
    <div class="listing-grid-review-item-top">
        <div class="rating">
            <?php
                $ratings = get_comment_meta( $comment_id, 'adqs_review_rating', true );
                AD()->Helper->get_review_rating_html($ratings);

            ?>
            <?php edit_comment_link(); ?>
        </div>
        <div class="rating-btn"><?php echo esc_html(get_comment_date('d F Y')); ?></div>
    </div>
    <div class="rating-dec">
    <?php comment_text(); ?>
    </div>
    <?php if ( $comment->comment_approved == '0' ) : ?>
    <em class="comment-awaiting-moderation"><?php echo esc_html__( 'Your Review is awaiting moderation.', 'adirectory' ); ?></em>
    <?php endif; ?>
    <div class="listing-grid-review-inner">
        <div class="listing-grid-review-inner-thumb">
            <?php echo get_avatar( $comment, 80 ); ?>
        </div>
        <div class="listing-grid-review-inner-txt">
            <h5 class="review-name"><?php comment_author(); ?></h5>
            <?php 
           
            if( ( get_userdata( $comment_author_id ) !== false ) && get_the_author_meta('adqs_address_info',$comment_author_id) ): ?>
            <p class="review-client"><?php the_author_meta('adqs_address_info',$comment_author_id); ?></p>
            <?php endif;?>
        </div>
    </div>

</div>
