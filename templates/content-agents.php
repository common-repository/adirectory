<?php

/**
 * The template for displaying listing content in the shortcode template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/content-taxonomy.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;

// template part $args
extract($args);

$current_page = isset($_REQUEST['adqs_page']) ? absint($_REQUEST['adqs_page']) : 1;

// Get author post counts and order them by post count
$offset = ($current_page - 1) * $per_page;
$cache_key = "adqs_all_agents";
$author_posts = wp_cache_get($cache_key, 'adqs_cache');

if ($author_posts === false) {
    $query = $wpdb->prepare("
        SELECT post_author, COUNT(*) as post_count
        FROM $wpdb->posts
        WHERE post_type = %s
        AND post_status = 'publish'
        GROUP BY post_author
        ORDER BY post_count DESC
        LIMIT %d, %d
    ", $post_type, $offset, $per_page);

    $author_posts = $wpdb->get_results($query);

    wp_cache_set($cache_key, $author_posts, 'adqs_cache');
}

// Caching the total author count query
$total_authors_cache_key = "adqs_all_agents_total";
$total_authors = wp_cache_get($total_authors_cache_key, 'adqs_cache');

if ($total_authors === false) {
    $total_authors = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(DISTINCT post_author)
        FROM $wpdb->posts
        WHERE post_type = %s
        AND post_status = 'publish'
    ", $post_type));

    // Set the cache with a timeout of 1 hour (3600 seconds)
    wp_cache_set($total_authors_cache_key, $total_authors, 'adqs_cache');
}

if (!empty($author_posts)) :



    do_action('adqs_before_main_content');
?>

    <div class="adqs-agents-area">
        <div class="adqs-admin-container">
            <div class="adqs-agent-all">
                <?php
                foreach ($author_posts as $author_post) :
                    $author_id = $author_post->post_author ?? 0;
                    $author = get_userdata($author_id);
                    $author_posts_url = adqs_listing_author_url($author_id, $post_type);
                    $Helper = AD()->Helper;
                    $review_ratings = $Helper->get_author_ratings($author_id);
                    $review_count = $Helper->get_author_review_count($author_id);
                    $review_text = (1 < (int) $review_count) ?  esc_html__('Reviews', 'adirectory') : esc_html__('Review', 'adirectory');
                ?>
                    <div class="adqs-agent-wrapper">
                        <div class="wrapper-img">
                            <?php echo get_avatar($author_id, 400); ?>


                            <a href="<?php echo esc_url($author_posts_url); ?>" class="img-overlay"></a>

                            <?php

                            $authroSocials = [
                                'adqs_facebook_profile' => 'fa-facebook',
                                'adqs_twitter_profile' => 'fa-twitter',
                                'adqs_instagram_profile' => 'fa-instagram',
                                'adqs_linked_profile' => 'fa-linkedin',
                            ];
                            $authroSocials = array_filter($authroSocials, function ($key) use ($author_id) {
                                return get_user_meta($author_id, $key, true) ? true : false;
                            }, ARRAY_FILTER_USE_KEY);
                            if (!empty($authroSocials)) :

                            ?>
                                <div class="social-icons">

                                    <?php
                                    foreach ($authroSocials as $key_name => $icon) :
                                    ?>
                                        <?php if (get_user_meta($author_id, $key_name, true)) : ?>
                                            <a class="adqs-icon" href="<?php echo esc_url(get_user_meta($author_id, $key_name, true)); ?>" target="_blank">
                                                <span><i class="fa-brands <?php echo esc_attr($icon); ?>"></i></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>


                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="wrapper-content">
                            <h6 class="wrapper-title"><a href="<?php echo esc_url($author_posts_url); ?>"><?php echo esc_html($author->display_name ?? ''); ?></a><?php do_action('adqs_after_author', $author_id); ?></h6>

                            <ul class="adqs-profile-reviews-item">
                                <?php if (!empty($review_ratings)) : ?>
                                    <li> <span><i class="fa-solid fa-star"></i></span> <span><?php echo esc_html($review_ratings); ?></span> <?php echo esc_html("( {$review_count} {$review_text} )"); ?>
                                    </li>
                                <?php endif; ?>


                                <li> <span><?php echo esc_html(count_user_posts($author_id, 'adqs_directory')); ?></span> <?php echo esc_html__('Listing', 'adirectory'); ?></li>

                            </ul>
                        </div>
                    </div>
                <?php
                endforeach;

                ?>
            </div>
            <?php
            // Add pagination
            $total_pages = ceil($total_authors / $per_page);
            if ($total_pages > 1) :
            ?>
                <ul class="page-numbers">
                    <?php if ($current_page > 1) : ?>
                        <li><a class="prev page-numbers" href="<?php echo esc_url(adqs_get_current_page_url(['adqs_page' => ($current_page - 1)])); ?>"><i class="fa-solid fa-angle-left"></i></a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li>
                            <?php if ($i == $current_page) : ?>
                                <span aria-current="page" class="page-numbers current"><?php echo $i; ?></span>
                            <?php else : ?>
                                <a class="page-numbers" href="<?php echo esc_url(adqs_get_current_page_url(['adqs_page' => $i])); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages) : ?>
                        <li><a class="next page-numbers" href="<?php echo esc_url(adqs_get_current_page_url(['adqs_page' => ($current_page + 1)])); ?>"><i class="fa-solid fa-angle-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
<?php
    do_action('adqs_after_main_content');
endif;
