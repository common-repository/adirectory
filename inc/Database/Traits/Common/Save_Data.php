<?php
namespace ADQS_Directory\Database\Traits\Common;

trait Save_Data {

	/**
	 * update average ratings
	 *
	 * @param [type] Post $id
	 * @return void
	 */
	public function update_avg_ratings( $id ) {
		$getAvgRatings = 0;
		$comments      = get_approved_comments( $id );

		if ( $comments ) {
			$i     = 0;
			$total = 0;
			foreach ( $comments as $comment ) {
				$rate = get_comment_meta( $comment->comment_ID, 'adqs_review_rating', true );
				if ( isset( $rate ) && '' !== $rate ) {
					++$i;
					$total += $rate;
				}
			}

			if ( $i > 0 ) {
				$getAvgRatings = round( $total / $i, 1 );
			}
		}
		$meta_key = sanitize_key( 'adqs_avg_ratings' );
		update_post_meta( $id, $meta_key, $getAvgRatings, get_post_meta( $id, $meta_key, true ) );

		if ( ! get_comments_number( $id ) ) {
			delete_post_meta( $id, $meta_key );
		}
	}
}
