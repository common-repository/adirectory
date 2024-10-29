<?php

/**
 * The template for displaying listing textarea content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/fileds-elements/textarea.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

// template part $args
extract($args);

if (!$Helper->admin_view($data)) {
    return '';
}

$label = $Helper->get_data($data, 'label');
$fieldid = $Helper->get_data($data, 'fieldid');

$name = "_textarea_{$fieldid}";
if(in_array($name,$skip_fields)){
	return;
}
$name_list_show = "_textarea_list_{$fieldid}";
$value = $Helper->meta_val($post_id, $name);
$value_list_show = absint($Helper->meta_val($post_id, $name_list_show));

if (!empty($value)) :
?>
    <div class="listing-grid-info listing-grid-textarea qsd-custom-info" id="<?php echo esc_attr($name);?>">
        <?php if(!empty($label)): ?>
        <h4 class="listing-grid-section-title"><?php echo esc_html($label); ?></h4>
        <?php endif;?>
        <div class="listing-grid-textarea-box">
            <?php
            if (!empty($value_list_show)):
                $value = explode("\n", $value);
                if (!empty($value) && is_array($value)) :
            ?>
                    <ul class="listing-grid-textarea-item">
                        <?php
                        foreach ($value as $list) :

                            if (!empty(trim($list))) :
                        ?>
                                <li>
                                    <span>
                                        <img src="<?php echo esc_url(ADQS_DIRECTORY_ASSETS_URL); ?>/frontend/img/check.png" alt="#">
                                    </span>
                                    <?php echo esc_html($list); ?>
                                </li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                <?php
                endif;

                else: ?>
                <p><?php echo esc_html($value); ?></p>
            <?php
            endif;
            ?>
        </div>

    </div>
<?php endif; ?>
