<?php
$current_page = !empty(adqs_post_paged()) ? adqs_post_paged() : 1;
$taxonomy = 'adqs_location';
$per_page     = 1;
$offset       = ($current_page - 1) * $per_page;
$args  = array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => true,
    'number'     => $per_page,
    'offset'     => $offset,
);
$terms = get_terms($args);
$allTerms = [];
if (!empty($terms)) :
?>
    <div class="map-container">
        <?php
        foreach ($terms as $term) {
            $allTerms[] = $term->slug;
        }
        $args = array(
            'post_type'   => 'adqs_directory',
            'post_status' => 'publish',
            'tax_query'   => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $allTerms,
                    'operator' => 'IN',
                ),
            ),
            'meta_query' => array(
                array(
                    'key'      => '_map_lat',
                    'value'    => '',
                    'compare' => '!='
                ),
                array(
                    'key'      => '_map_lon',
                    'value'    => '',
                    'compare' => '!='
                ),
            ),
        );
        $query = new \WP_Query($args);
        $data  = array();
        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();
                $inner_aray          = array();
                $inner_aray['title'] = "<div class='adqs_map_popup'><img src='" . esc_url(get_the_post_thumbnail_url()) . "' /><div class='adqs_popup_meta'><a href='" . esc_url(get_the_permalink()) . "'>" . esc_html(get_the_title()) . '</a></div></div>';
                $inner_aray['lat']   = get_post_meta(get_the_ID(), '_map_lat', true);
                $inner_aray['lon']   = get_post_meta(get_the_ID(), '_map_lon', true);
                array_push($data, $inner_aray);
            endwhile;
            wp_reset_postdata();
        ?>
            <div id="markers_map"></div>
            <script id="markers_data" type="application/json">
                <?php echo wp_json_encode($data); ?>
            </script>
        <?php endif; ?>
    </div>
    <?php
    // Pagination
    $total_terms = wp_count_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
    ]);
    $total_pages = ceil($total_terms / $per_page);
    $pagination_args = array(
        'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999, false)),
        'format'    => '?paged=%#%',
        'current'   => $current_page,
        'total'     => $total_pages,
        'show_all'     => false,
        'type'         => 'list',
        'prev_next'    => true,
        'prev_text' => '<i class="fas fa-angle-left"></i>',
        'next_text' => '<i class="fas fa-angle-right"></i>',
    );
    if (!empty($pagination_args) && !empty(paginate_links($pagination_args))) :
    ?>
        <div class='adqs_pagination'>
            <?php echo wp_kses_post(paginate_links($pagination_args)); ?>
        </div>
    <?php endif; ?>
<?php endif;
