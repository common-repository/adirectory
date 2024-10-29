<?php

/**
 * The Template for displaying all archive-listing
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive-listing.php.
 *

 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


extract($args);
$order_id = isset($_GET['adqs_order']) ? $_GET['adqs_order'] : 0;


if ("self" === $pricing_active) {
    $regular_listing = get_user_meta(get_current_user_id(), "adp_order_reg_listing_{$order_id}", true);
    $featured_listing = get_user_meta(get_current_user_id(), "adp_order_fea_listing_{$order_id}", true);
    $expring_date = get_user_meta(get_current_user_id(), "adp_order_expire_{$order_id}", true);
} else {
    $regular_listing = get_user_meta(get_current_user_id(), "adp_wc_order_reg_listing_{$order_id}", true);
    $featured_listing = get_user_meta(get_current_user_id(), "adp_wc_order_fea_listing_{$order_id}", true);
    $expring_date = get_user_meta(get_current_user_id(), "adp_wc_order_expire_{$order_id}", true);
}

?>

<?php

$current_time_stamp = strtotime(date('Y-m-d h:i:s'));
$expiring_time_stamp = strtotime($expring_date);


if ($expiring_time_stamp > $current_time_stamp) : ?>
    <div class="adqs-pkg-select">
        <div class="adqs-section-padding">
            <div class="container">
                <div class="row">
                    <?php
                    if ($regular_listing > 0 || $regular_listing === "unlimited") { ?>

                        <div class="col-lg-6">
                            <div class="adqs-radio-action">
                                <input type="radio" name="adqs_plan_pkg" id="opt1" class="hidden" checked
                                    value="adqs_reg_list" />
                                <div class="radio-btn">
                                    <label for="opt1" class="label">
                                        <span class="text"><?php echo esc_html__("Regular Listing", "adirectory"); ?></span>
                                        <p style="color:black;margin-top:10px;font-weight:normal">
                                            <?php echo esc_html($regular_listing) . " listing left"; ?></p>
                                    </label>

                                </div>
                            </div>
                        </div>

                    <?php } else { ?>

                    <?php }
                    ?>
                    <?php

                    if ($featured_listing > 0 || $featured_listing === "unlimited") { ?>
                        <div class="col-lg-6">
                            <div class="adqs-radio-action">
                                <input type="radio" name="adqs_plan_pkg" id="opt2" class="hidden" value="adqs_fea_list" />
                                <div class="radio-btn">
                                    <label for="opt2" class="label">
                                        <span class="text"> <?php echo esc_html__("Feature Listing", "adirectory"); ?> </span>
                                        <p style="color:black;margin-top:10px;font-weight:normal">
                                            <?php echo esc_html($featured_listing) . " listing left"; ?></p>
                                    </label>

                                </div>
                            </div>
                        </div>
                    <?php } else {
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php else :
    adqs_get_template_part('listing-add/plan', 'none');
    $loginPage = adqs_get_permalink_by_key('adqs_pricing_package');
    if (!empty($loginPage)) :
    ?>
        <script>
            if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
                window.location.href = '<?php echo esc_url($loginPage); ?>';
            }
        </script>
<?php
    endif;
    exit;
    return ob_get_clean();
endif;


?>