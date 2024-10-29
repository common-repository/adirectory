<?php

namespace ADQS_Directory\Frontend;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Api handlers class
 */
class TemplateLoader
{

	private $post_type = 'adqs_directory';
	private $slug      = 'listing';

	/**
	 * Class constructor
	 */
	function __construct()
	{
		// Customize Frontend Template
		add_filter('template_include', array($this, 'template_loader'));

		// Custom Author Page
		add_filter('generate_rewrite_rules', array($this, 'author_page_rewrite_rules'));

		// Customize Review Comment Template
		add_filter('comments_template', array($this, 'comment_review_template_loader'));
	}



	/**
	 * template include for overwrite
	 *
	 * @return void
	 */
	public function template_loader($template)
	{
		$file = false;

		if (is_singular($this->post_type)) {
			$file = "single-{$this->slug}.php";
		}

		if (adqs_is_listing_archive()) {
			$file = "archive-{$this->slug}.php";
		}

		// There is a file, create a new template path
		if ($file) {
			// Check in Theme for folder adirectory/templates/ and the file inside
			$template = locate_template("adirectory/{$file}");

			// There is no template in the theme
			if (!$template) {
				// Get from Plugin's folder 'templates'
				$template = adqs_templates_location() . "/{$file}";
			}
		}

		return $template;
	} // end

	/**
	 * Author listing Page rewrite rules
	 *
	 * @return string
	 */
	public function author_page_rewrite_rules($wpr)
	{
		$key        = '^author/([a-zA-Z0-9]+)/directory(?:/page/([0-9]+))?/?$';
		$rewrite    = 'index.php?post_type=adqs_directory&author_name=$matches[1]&paged=$matches[2]';
		$newRules   = array($key => $rewrite);
		$wpr->rules = $newRules + $wpr->rules;

		return $wpr;
	} // end

	/**
	 * template include for overwrite
	 * filter hook for single listing page
	 *
	 * @return string
	 */
	public function comment_review_template_loader($template)
	{
		global $post;
		if (!(is_singular($this->post_type) && (have_comments() || ('open' == $post->comment_status)))) {
			return $template;
		}
		if ($post->post_type == $this->post_type) {
			$theme_dir     = get_stylesheet_directory() . '/adirectory';
			$template_name = 'single-listing-reviews.php';
			if (file_exists($theme_dir . '/' . $template_name)) {
				$template = $theme_dir . '/' . $template_name;
			} elseif (file_exists(adqs_templates_location() . $template_name)) {
				$template = adqs_templates_location() . $template_name;
			}
		}
		return $template;
	} // end
}
