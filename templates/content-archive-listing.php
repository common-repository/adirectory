<?php

/**
 * The template for displaying listing content in the archive-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/content-archive-listing.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
global $wp_query;
$listings_query = $wp_query;

do_action('adqs_before_archive_listing');
$wap_classes = is_active_sidebar('adqs_archive_listings') ? ["column-2"] : ["column-3"];

if(adqs_is_author_listing_archive()){
	$wap_classes = ["column-3"];
	adqs_get_template_part('archive/author', 'info');
}

$view_type = $_GET['view_type'] ?? '';
if ($view_type === 'list') {
	$wap_classes[] = "{$view_type}-view";
	$wap_classes[] = "column-2";
}




$wap_classes = join(' ', array_filter($wap_classes));

$has_map_view = false;
$reset_filter = true;


?>

<section class="qsd-prodcut-grid-with-side-bar-main qsd-archive">
	<div class="adqs-admin-container">
		<div class="qsd-prodcut-grid-with-side-bar-main-item">
		<?php
		if( !adqs_is_author_listing_archive() ){
			// archive page sidebar
			adqs_get_template_part('archive/sidebar');
		}
		?>

			<div class="qsd-prodcut-grid-right">
				<?php adqs_get_template_part('grid/header-top', 'bar',compact('listings_query','has_map_view','reset_filter'));

				if (have_posts()) :
				?>
					<div class="<?php echo esc_attr($wap_classes); ?> qsd-prodcut-grid-list-main">
						<?php
						while (have_posts()) :
							the_post();

							adqs_get_template_part('content', 'listing');
						endwhile;
						?>
					</div>
				<?php
					adqs_post_pagination();
					wp_reset_postdata();
				else:
					adqs_get_template_part('content', 'none');
				endif;
				?>
			</div>
		</div>
	</div>
</section>
<?php
do_action('adqs_after_archive_listing');
