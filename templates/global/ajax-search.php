<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// template part $args
extract($args);

if ($search_results->have_posts()):
	while ($search_results->have_posts()):
		$search_results->the_post();
?>

		<div class="adqs-ajax-search-item" data-title="<?php the_title(); ?>">
			<div class="adqs-as-img"><?php the_post_thumbnail('thumbnail'); ?></div>
			<div class="adqs-as-meta">
				<h5><?php the_title(); ?></h5>
				<div class="adqs-as-metaInfo">
					<div class="adqs-asMeta-price"><?php adqs_get_template_part('global/price'); ?></div>
					<?php
					$avgRatings = AD()->Helper->get_post_average_ratings(get_the_ID());
					if (!empty($avgRatings)) :
					?>
						<div class="adqs-asMeta-rating"><?php wp_kses_post(AD()->Helper->get_review_rating_html($avgRatings)); ?></div>

					<?php endif; ?>
				</div>
			</div>
		</div>

<?php
	endwhile;
	wp_reset_postdata();
endif;
