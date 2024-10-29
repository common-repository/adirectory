
<?php

/**
 * The template for displaying the All listings by select option is shown in the Listing Sidebar
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/widgets/listings-by.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// template part $args
extract($args);

$post_type = 'adqs_directory';
$dir_type_meta_key = 'adqs_directory_type';
$post_sl_id = get_the_ID();


$related_directory   = $w_instance['related_directory'] ?? '';
$directory_type   = $w_instance['directory_type'] ?? false;
$order_by   = $w_instance['order_by'] ?? false;
$display_number   = isset($w_instance['display_number']) ? (int) $w_instance['display_number'] : -1;


/**
 *  Listing Post type widget
 * */

$queryArgs = [
    'post_type' => $post_type,
    'posts_per_page' => $display_number

];

// all meta query
$metaQuery = [];

// Options for listings by
if (($related_directory == '1') && is_singular($post_type)) {

    $directory_id = get_post_meta($post_sl_id, $dir_type_meta_key, true);
    if ($directory_id != '') {
        $metaQuery[] = [
            'key'      => $dir_type_meta_key,
            'value'    => $directory_id,
            'compare' => '='
        ];
    }
} else {
    if (!empty($directory_type)) {
        $metaQuery[] = [
            'key'      => $dir_type_meta_key,
            'value'    => $directory_type,
            'compare' => '='
        ];
    }
}


if (!empty($order_by)) {

    $queryArgs['orderby'] = $order_by;

    switch ($order_by) {
        case 'rating-desc':
            $metaQuery[] = [
                'key'      => 'adqs_avg_ratings',
                'value'    => 0,
                'compare' => '>'
            ];
            break;
        case 'review-count':
            $queryArgs['comment_count'] = [
                'value'   => 0,
                'compare' => '>',
            ];
            break;
    }



    /**
     *  Short By
     */
    if (!empty(adqs_listing_query_sort_by($order_by))) {
        $queryArgs = array_merge($queryArgs,adqs_listing_query_sort_by($order_by));
    }


}



if (!empty($metaQuery)) {
    if (count($metaQuery) > 1) {
        $metaQuery['relation'] = 'AND';
    }
    $queryArgs['meta_query'] = $metaQuery;
}

if ($widget_id === 'adqs_related_listings'){
    $taxQuery = [];

    if( !empty($Helper->get_pluck_terms($post_sl_id,'adqs_category','term_id')) ){
        $taxQuery[] = [
            'taxonomy'  => 'adqs_category',
            'field' => 'term_id',
            'terms' => $Helper->get_pluck_terms($post_sl_id,'adqs_category','term_id')
        ];
    }
    elseif( !empty($Helper->get_pluck_terms($post_sl_id,'adqs_location','term_id')) ){
        $taxQuery[] = [
            'taxonomy'  => 'adqs_location',
            'field' => 'term_id',
            'terms' => $Helper->get_pluck_terms($post_sl_id,'adqs_location','term_id')
        ];
    }else{
        if( !empty($Helper->get_pluck_terms($post_sl_id,'adqs_tags','term_id')) ){
            $taxQuery[] = [
                'taxonomy'  => 'adqs_tags',
                'field' => 'term_id',
                'terms' => $Helper->get_pluck_terms($post_sl_id,'adqs_tags','term_id')
            ];
        }
    }

    if (!empty($taxQuery)) {
        if (count($taxQuery) > 1) {
            $taxQuery['relation'] = 'OR';
        }
        $queryArgs['tax_query'] = $taxQuery;
    }

}





$listItem = new \WP_Query($queryArgs);
if ($listItem->have_posts()) :
?>

    <div class="featured-listings">
        <?php
        if (!empty($w_instance['title'] ?? '')) {
            echo wp_kses_post($w_args['before_title'] . apply_filters('widget_title', $w_instance['title']) . $w_args['after_title']);
        }
        ?>
        <?php
        while ($listItem->have_posts()) :
            $listItem->the_post();
            $post_id = get_the_ID();
            if (is_singular($post_type) && $post_sl_id === $post_id) {
                continue;
            }
            

        ?>
            <div class="fl-single-item">
                <div class="featured-listings-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="featured-listings-item-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="featured-listings-inner">
                        <h3 class="featured-listings-inner-txt">
                            <a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
                        </h3>
                        <?php
                        $widget_ids = ['adqs_listings_by','adqs_related_listings'];
                        // Meta for Listings By Widget
                        if (in_array($widget_id,$widget_ids)) :
                            $categories = wp_list_pluck(adqs_render_repeated_tax($post_id, 'adqs_category',1), 'name', 'term_id');
                            if (!empty($categories) && is_array($categories)) :
                        ?>
                                <div class="fl-cats">
                                    <?php
                                    foreach ($categories as $term_id => $cat_name) :
                                      $icon = get_term_meta( $term_id, 'adqs_category_icon_id', true );
                                      $icon = !empty($icon) ? "<i class='{$icon}'></i>" : "#";

                                    ?>

                                        <a href="<?php echo esc_url(get_term_link($term_id)); ?>">
                                            <?php echo wp_kses_post($icon);?>
                                            <?php echo esc_html($cat_name); ?>
                                        </a>
                                    <?php
                                    endforeach;
                                    ?>
                                </div>
                        <?php endif;
                        endif; ?>

                        <?php
                        // Meta for Populer Listings Widget
                        if ($widget_id === 'adqs_populer_listings') :

                            if ($order_by === 'views-desc') :
                                $view_count = $Helper->get_number_short_format($Helper->get_view_count($post_id));
                        ?>
                                <div class="fl-viewCount">
                                    <img src="<?php echo esc_url(ADQS_DIRECTORY_ASSETS_URL . '/frontend/img/eye.svg'); ?>" alt="#"><strong><?php echo esc_html($view_count); ?></strong><span><?php echo esc_html__('View', 'adirectory'); ?></span>

                                </div>
                            <?php endif;

                            if (($order_by === 'rating-desc')  || ($order_by === 'review-count')) :
                                $avgRatings = $Helper->get_post_average_ratings($post_id);
                                $countReview = get_comments_number();
                        ?>
                            <div class="fl-viewCount fl-avgRatings">
                                <i class="dashicons dashicons-star-filled"></i><strong><?php echo esc_html($avgRatings); ?></strong>
                                <span>
                                <?php

                                    echo '('.esc_html($countReview).')';
                                ?>
                                </span>
                            </div>
                            <?php endif; ?>

                        <?php endif; ?>

                        <p class="featured-listings-inner-txt-price"><?php adqs_get_template_part('global/price'); ?></p>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
<?php endif;
