<?php

namespace ADQS_Directory\Frontend;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Api handlers class
 * Since 2,0
 */
class Shortcode
{



	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		add_shortcode('adqs_listings', array($this, 'render_listing_shortcode'));
		add_shortcode('adqs_taxonomies', array($this, 'render_all_listing_categories'));
		add_shortcode('adqs_search', array($this, 'render_search_bar'));
		add_shortcode('adqs_social_share', array($this, 'render_social_share'));
		add_shortcode('adqs_dashboard', array($this, 'frontend_dashbaord'));
		add_shortcode('adqs_agents', array($this, 'render_all_agents'));
		//User login and registration
		add_shortcode('adqs_user_log_regi', array($this, 'user_log_regi'));
	}


	/**
	 * Method render_all_agents
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_all_agents($atts)
	{

		extract(shortcode_atts([
			'post_type'  => 'adqs_directory',
			'per_page'  => 8,

		], $atts));

		$per_page = absint($per_page);

		wp_enqueue_style('adqs_all_agents');


		ob_start();

		adqs_get_template_part(
			'content',
			'agents',
			compact('post_type', 'per_page')
		);

		return ob_get_clean();
	}




	/**
	 * Method user_log_regi
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function user_log_regi($atts, $content = null)
	{
		if (is_user_logged_in()) {
			ob_start();
			$loginPage = adqs_get_permalink_by_key('adqs_user_dashboard');
			if (!empty($loginPage)) :
?>
				<script>
					if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
						window.location.href = '<?php echo esc_url($loginPage); ?>';
					}
				</script>
		<?php
			endif;
			return ob_get_clean();
		}



		ob_start();

		wp_enqueue_style('adqs-user-log-regi');
		wp_enqueue_script('adqs-user-log-regi');

		?>



		<div class="adqs-log-regi-tabs">
			<nav>
				<ul class="adqs-log-regi-tabs-navigation">
					<li><a href="#" data-content="login" class="selected"><?php echo esc_html__('Sign In', 'adirectory'); ?></a>
					</li>
					<?php if (!empty(get_option('users_can_register'))) :  ?>
						<li><a href="#" data-content="signup"><?php echo esc_html__('Sign Up', 'adirectory'); ?></a></li>
					<?php endif; ?>
				</ul>
			</nav>
			<ul class="adqs-log-regi-tabs-content">
				<?php do_action("adqs_regi_login_error"); ?>

				<li data-content="login" class="selected">
					<?php adqs_get_template_part('users/login'); ?>
				</li>
				<?php if (!empty(get_option('users_can_register'))) :  ?>
					<li data-content="signup">

						<?php adqs_get_template_part('users/registration'); ?>

					</li>
				<?php endif; ?>
			</ul>
		</div> <!-- end adqs-log-regi-tabs -->

		<?php return ob_get_clean();
	}



	/**
	 * Method frontend_dashbaord
	 *
	 * @return string
	 */
	public function frontend_dashbaord()
	{

		if (!is_user_logged_in()) {
			ob_start();
			$loginPage = adqs_get_permalink_by_key('adqs_login_regi');
			if (!empty($loginPage)) :
		?>
				<script>
					if (window.location.href !== '<?php echo esc_url($loginPage); ?>') {
						window.location.href = '<?php echo esc_url($loginPage); ?>';
					}
				</script>
		<?php
			endif;
			return ob_get_clean();
		}

		wp_enqueue_script('qs-frontdashdeps');
		wp_enqueue_style('qs-frontdash-css');
		wp_enqueue_style('qs-frontdash-tailwind');
		wp_enqueue_style('adqs-toast');




		return "<div id='user_dashboard' class='user_dashboard'></div>";
	}



	/**
	 * Method render_all_listing_categories
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_all_listing_categories($atts)
	{

		extract(shortcode_atts([
			'tax_name'  => 'adqs_category',
			'per_page'  => '10',
			'top_bar_show'  => 'true',
			'pagination_type'  => 'pagination',
			'terms'  => '',
			'order'  => 'DESC',
			'orderby'  => 'count',
			'carousel_settings'  => '',
			'from_addon'  => 'false',

		], $atts));

		$top_bar_show = ('true' === $top_bar_show) ? true : false;
		$from_addon = ('true' === $from_addon) ? true : false;


		wp_enqueue_style('adqs_taxonomy_archive');

		$uniqId = uniqid();
		if ($pagination_type === 'carousel') {
			$carousel_settings = apply_filters("adqs_taxonomy_carousel_settings", maybe_unserialize($carousel_settings), $tax_name);
			$carousel_settings['uniq_id'] = $uniqId;
			$carousel_settings = wp_json_encode($carousel_settings);
			if (empty($from_addon)) {
				wp_enqueue_style('slick');
				wp_enqueue_style('slick-init');
				wp_enqueue_script('slick');
				wp_enqueue_script('slick-init');
			}
		}




		$per_page = absint($per_page);

		$args = [
			'taxonomy'   => $tax_name,
			'hide_empty' => true,
			'number'  => $per_page,
			'order'      => $order,
			'orderby'    => $orderby,
		];
		$pagination_args = [];
		if ($pagination_type === 'pagination') {
			$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args['offset'] = ($page - 1) * $per_page;

			// Pagination
			$total_terms = wp_count_terms([
				'taxonomy'   => $tax_name,
				'hide_empty' => true,
			]);

			$pagination_args = array(
				'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999, false)),
				'format'    => '?paged=%#%',
				'current'   =>  $page,
				'total'     => ceil($total_terms / $per_page),
				'show_all'     => false,
				'type'         => 'list',
				'prev_next'    => true,
				'prev_text' => '<i class="fas fa-angle-left"></i>',
				'next_text' => '<i class="fas fa-angle-right"></i>',
			);
		}
		if (!empty($terms)) {
			$args['include'] = array_map('absint', explode(',', $terms));
		}


		$get_category_terms = get_terms($args);
		$template_slug = ($tax_name === 'adqs_location') ? 'taxonomy-locations' : 'taxonomy';
		ob_start();

		adqs_get_template_part(
			'content',
			$template_slug,
			compact(
				'get_category_terms',
				'tax_name',
				'pagination_args',
				'top_bar_show',
				'pagination_type',
				'carousel_settings',
				'uniqId'
			)
		);

		return ob_get_clean();
	}

	/**
	 * Method render_listing_shortcode
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function render_listing_shortcode($atts, $content = null)
	{
		wp_enqueue_style('adqs_single_grid');

		wp_enqueue_script('img_svg_inline');
		wp_enqueue_script('grid-page-script');

		extract(shortcode_atts([
			'filter_show'  => 'true',
			'top_bar_show'  => 'true',
			'pagination_type'  => 'pagination',
			'reset_filter'  => 'true',
			'has_map_view'  => 'true',
			'per_page'  => '',
			'directory_type'  => '',
			'category'  => '',
			'location'  => '',
			'tags'  => '',
			'rating'  => '',
			'display_listings'  => '',
			'short_by'  => '',
			'from_addon'  => 'false',
			'view_type'  => '',
			'carousel_settings'  => '',
			'post__not_in'  => '',
		], $atts));

		$from_addon = ('true' === $from_addon) ? true : false;
		// get all list page id
		if (empty($from_addon)) {
			$alllistingspage_option_key = 'adqs_all_listings_page';
			$get_alllistingspage_id = absint(get_option($alllistingspage_option_key, 0));
			if ($get_alllistingspage_id !== get_the_ID()) {
				update_option($alllistingspage_option_key, get_the_ID());
			}
		}

		$uniqId = uniqid();
		if ($pagination_type === 'carousel') {
			$carousel_settings = apply_filters("adqs_listing_carousel_settings", maybe_unserialize($carousel_settings));
			$carousel_settings['uniq_id'] = $uniqId;
			$carousel_settings = wp_json_encode($carousel_settings);
			if (empty($from_addon)) {
				wp_enqueue_style('slick');
				wp_enqueue_style('slick-init');
				wp_enqueue_script('slick');
				wp_enqueue_script('slick-init');
			}
		}


		$filter_show = ('true' === $filter_show) ? true : false;
		$top_bar_show = ('true' === $top_bar_show) ? true : false;
		$reset_filter = ('true' === $reset_filter) ? true : false;
		$has_map_view = ('true' === $has_map_view) ? true : false;


		$paged = !empty(adqs_post_paged()) ? adqs_post_paged() : 1;
		$setting_per_page = !empty(AD()->Helper->get_setting('listing_per_page')) ? absint(AD()->Helper->get_setting('listing_per_page')) : 6;

		$per_page = !empty($per_page) ? absint($per_page) : $setting_per_page;

		$queryArgs = [
			'post_type' => 'adqs_directory',
			'posts_per_page' => $per_page,
		];
		if ($pagination_type === 'pagination') {
			$queryArgs['paged'] =  $paged;
		}


		if (!empty($directory_type)) {
			$queryArgs['directory_type'] = $directory_type;
		}
		if (!empty($category)) {
			$queryArgs['category'] = $category;
		}
		if (!empty($location)) {
			$queryArgs['location'] = $location;
		}
		if (!empty($tags)) {
			$queryArgs['tags'] = $tags;
		}
		if (!empty($rating)) {
			$queryArgs['rating'] = $rating;
		}
		if (!empty($display_listings)) {
			$queryArgs['display_listings'] = $display_listings;
		}

		$setQueryArgs = adqs_listing_query_filter_args($queryArgs);


		/**
		 *  Short By
		 */
		if (!empty(adqs_listing_query_sort_by($short_by))) {
			$setQueryArgs = array_merge($setQueryArgs, adqs_listing_query_sort_by($short_by));
		}

		if (!empty($post__not_in)) {
			$post__not_in = array_map(function ($id) {
				return absint($id);
			}, explode(',', $post__not_in));
			$setQueryArgs['post__not_in'] = $post__not_in;
		}

		if (isset($_GET['view_type'])) {
			$view_type = $_GET['view_type'];
		}


		$listings_query = new \WP_Query($setQueryArgs);

		$custom_directory = !empty($directory_type) ? explode(',', $directory_type) : [];

		ob_start();

		do_action('adqs_before_main_content');
		?>

		<?php
		if ($filter_show) {
			adqs_get_template_part('grid/advanced', 'top-filter');
		}

		?>


		<section class="qsd-prodcut-grid-with-side-bar-main">
			<div class="<?php echo is_singular('adqs_directory') ? esc_attr('container') : esc_attr('adqs-admin-container'); ?>">
				<div
					class="qsd-prodcut-grid-with-side-bar-main-item <?php !empty($view_type) ?  esc_attr($view_type . '-view') : ''; ?>">

					<div class="qsd-prodcut-grid-right">
						<?php
						if ($top_bar_show) {
							adqs_get_template_part('grid/header-top', 'bar', compact('listings_query', 'has_map_view', 'reset_filter', 'per_page', 'custom_directory', 'view_type'));
						}
						if ($has_map_view && ($view_type === 'map')) {
							adqs_get_template_part('grid/map', 'view');
						} else {
							adqs_get_template_part('grid/gridlist', 'view', compact('listings_query', 'pagination_type', 'view_type', 'carousel_settings', 'uniqId'));

							do_action('adqs_after_listings');
						}
						?>
					</div>
				</div>
			</div>
		</section>
	<?php
		do_action('adqs_after_main_content');
		return ob_get_clean();
	}

	/**
	 * Method render_search_bar
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function render_search_bar($atts, $content = null)
	{


		wp_enqueue_script('img_svg_inline');

		extract(shortcode_atts([
			'from_addon'  => 'false',
			'search_page_url'  => '',
			'new_tab'  => 'true',
		], $atts));

		$from_addon = (bool) $from_addon;
		$new_tab = (bool) $new_tab;


		ob_start();

		do_action('adqs_before_main_content');

		adqs_get_template_part('global/search', 'bar', compact('search_page_url', 'new_tab'));

		do_action('adqs_after_main_content');
		return ob_get_clean();
	}



	/**
	 * Method render_social_share
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function render_social_share($atts, $content = null)
	{
		// Get current page URL
		$sb_url = urlencode(get_permalink());

		// Get current page title
		$sb_title = get_the_title();

		// Get Post Thumbnail for pinterest
		$sb_thumb = get_the_post_thumbnail_url();

		// Construct sharing URL without using any script
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $sb_url;
		$twitterURL  = 'https://twitter.com/intent/tweet?text=' . $sb_title . '&url=' . $sb_url . '&via=adqs_listing';

		// Based on popular demand added Pinterest too

		$pinterestURL = add_query_arg(['description' => get_the_title(), 'url' => $sb_url, 'media' => $sb_thumb], 'https://pinterest.com/pin/create/button');


		ob_start();
	?>
		<span class="listing-grid-details-btn qsd-socialShare">
			<img src="<?php echo esc_url(ADQS_DIRECTORY_ASSETS_URL); ?>/frontend/img/share.svg" alt="img">
			<span class="qsd-text"><?php echo esc_html__('Share Now', 'adirectory'); ?></span>
			<div class="qsd-socialShare-btn">
				<a class="qsd-ssb-facebook" href="<?php echo esc_url($facebookURL); ?>" target="_blank"
					rel="nofollow"><?php echo esc_html__('Facebook', 'adirectory'); ?></a>
				<a class="qsd-ssb-twitter" href="<?php echo esc_url($twitterURL); ?>" target="_blank"
					rel="nofollow"><?php echo esc_html__('Twitter', 'adirectory'); ?></a>
				<a class="qsd-ssb-pinterest" href="<?php echo esc_url($pinterestURL); ?>" target="_blank"
					rel="nofollow"><?php echo esc_html__('Pinterest', 'adirectory'); ?></a>
			</div>
		</span>

<?php
		return ob_get_clean();
	}
}
