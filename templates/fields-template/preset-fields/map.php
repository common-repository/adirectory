<?php
if (!defined('ABSPATH')) {
    return '';
}
extract($args);

$label          =  $args['data']['label'] ?? $args['data']['name'];
$name_lat       = '_map_lat';
$name_lon       = '_map_lon';
$name_hide_map  = '_hide_map';
$placeholder    = $args['data']['placeholder'] ?? '';
$value_lat      = !empty(get_post_meta($post_id, $name_lat, true)) ? get_post_meta($post_id, $name_lat, true) : '';
$value_lon      = !empty(get_post_meta($post_id, $name_lon, true)) ? get_post_meta($post_id, $name_lon, true) : '';
$value_hide_map = !empty(get_post_meta($post_id, $name_hide_map, true)) ? get_post_meta($post_id, $name_hide_map, true) : '';

if ($ispricing) {
    if ($pricing_active === "self") {
        $order_id = $_GET['adqs_order'] ?? 0;
        $pricing = adp_get_query_order((int)$order_id, 'pricing_id');
        $exist_field = adp_get_query_pricing_meta($pricing->pricing_id, "lm" . $name, true);
        if ($exist_field === "no") {
            return;
        }
    } else if ($pricing_active === "wc") {
        $order_id = $_GET['adqs_order'] ?? 0;
        $pricing = adp_wc_get_query_order((int)$order_id);
        $exist_field = adp_wc_get_query_pricing_meta($pricing, "lm_map", true);
        if ($exist_field === "no") {
            return;
        }
    }
}

?>

<div class="single-field-wrapper">
    <h4 class="qsd-form-label">
        <?php echo esc_html($label); ?><span><?php echo isset($data['is_required']) ? '*' : ''; ?> </h4>
    <div class="adqs-form-inner">

        <div class="qsd-form-wrap qsd-map-field">
            <div class="adqs_map-latlon-wrap">
                <div class="adqs_map-input adqs_map-lat">
                    <input type="text" placeholder="Enter the Latitude" name="<?php echo esc_attr($name_lat); ?>"
                        id="<?php echo esc_attr($name_lat); ?>" class="adqs-title-input"
                        value="<?php echo esc_attr($value_lat); ?>">
                </div>
                <div class="adqs_map-input adqs_map-lon">
                    <input type="text" placeholder="Enter the Longitude" name="<?php echo esc_attr($name_lon); ?>"
                        id="<?php echo esc_attr($name_lon); ?>" class="adqs-title-input"
                        value="<?php echo esc_attr($value_lon); ?>">
                </div>
                <div class="adqs-map-actions">
                    <div class="adqs_map-input adqs_map-generate">
                        <button type="button" class="qsd-btn qsd-btn-latlon-generate">
                            <?php echo esc_html__('Map Generate', 'adirectory'); ?>
                        </button>
                    </div>
                    <div class="adqs_map-input adqs_map-hide">
                        <div class="qsd-form-check-control">
                            <input type="checkbox" id="<?php echo esc_attr($name_hide_map); ?>"
                                name="<?php echo esc_attr($name_hide_map); ?>" value="1"
                                <?php checked($value_hide_map, 1); ?>>
                            <label for="<?php echo esc_attr($name_hide_map); ?>">
                                <?php echo esc_html__('Hide Map', 'adirectory'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="adqs_map"></div>
        </div>
    </div>
</div>