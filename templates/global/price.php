<?php

/**
 * The template for displaying listing price in the globaly template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/meta.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$post_id = !empty($post->ID) ? $post->ID : get_the_ID();

$price = AD()->Helper->get_price($post_id);

?>
<?php if( !empty($price) ): ?>
    <?php echo wp_kses_post($price); ?>

<?php endif; ?>
