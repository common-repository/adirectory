<?php
/*
 * All Listing
*/

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



class Listing_By extends Widget_Base
{


	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'adqs_alllistings';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('AD : All Listings', 'adirectory');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of  the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget.
	 */
	public function get_categories()
	{
		return ['adqs-category'];
	}

	/**
	 * Retrieve the list of scripts the Advanced Scroll Sequence Widget depended on.
	 *
	 * Used to set scripts dependencies required to run the Widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends()
	{
		return ['slick', 'slick-init'];
	}

	public function get_style_depends()
	{
		return ['slick', 'slick-init'];
	}

	/* get terms */
	public function getTerms($name = null, $index_type = null, $hierarchical = true)
	{
		if (empty(adqs_get_terms($name, ['hierarchical' => $hierarchical]))) {
			return [];
		}
		return wp_list_pluck(adqs_get_terms($name, ['hierarchical' => $hierarchical]), 'name', $index_type);
	}

	/* array data join */
	public function get_join_data($data = [])
	{
		if (empty($data) && !is_array($data)) {
			return '';
		}
		return join(',', $data);
	}



	/**
	 * Register  listing by widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls()
	{

		$this->start_controls_section(
			'section_options',
			[
				'label' => esc_html__('Options', 'adirectory'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'filter_show',
			[
				'label'        => esc_html__('Filter Show ?', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'adirectory'),
				'label_off'    => esc_html__('No', 'adirectory'),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'top_bar_show',
			[
				'label'        => esc_html__('Top Bar Show ?', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'adirectory'),
				'label_off'    => esc_html__('No', 'adirectory'),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);


		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__('Pagination Type', 'adirectory'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'' => esc_html__('None', 'adirectory'),
					'pagination' => esc_html__('Pagination', 'adirectory'),
					'carousel' => esc_html__('Carousel', 'adirectory'),
				],
				'default' => 'pagination',
			]
		);

		$this->add_control(
			'view_type',
			[
				'label' => esc_html__('View Type', 'adirectory'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'grid' => esc_html__('Grid', 'adirectory'),
					'list' => esc_html__('List', 'adirectory'),
				],
				'default' => 'grid'
			]
		);


		$this->end_controls_section(); // end: Section

		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__('Listings Query', 'adirectory'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$display_listings = [
			'' => esc_html__('All', 'adirectory'),
			'featured' => esc_html__('Featured', 'adirectory'),
		];

		$this->add_control(
			'display_listings',
			[
				'label' => esc_html__('Display Listings', 'adirectory'),
				'type' => Controls_Manager::SELECT,
				'options' => $display_listings,
			]
		);

		$directory_type = wp_list_pluck(adqs_get_directory_types(), 'name', 'slug') ?? [];
		$this->add_control(
			'directory_type',
			[
				'label' => esc_html__('By Directory', 'adirectory'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $directory_type,
				'default' => $directory_type[0]->slug ?? '',

			]
		);

		$this->add_control(
			'category',
			[
				'label' => esc_html__('By Categories', 'adirectory'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->getTerms('adqs_category', 'slug'),

			]
		);
		$this->add_control(
			'location',
			[
				'label' => esc_html__('By Locations', 'adirectory'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->getTerms('adqs_location', 'slug'),

			]
		);
		$this->add_control(
			'tags',
			[
				'label' => esc_html__('By Tags', 'adirectory'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->getTerms('adqs_tags', 'term_id', false),

			]
		);
		$this->add_control(
			'rating',
			[
				'label' => esc_html__('Rating Up / Equel', 'adirectory'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__('All', 'adirectory'),
					'5' => esc_html__('5', 'adirectory'),
					'4' => esc_html__('4', 'adirectory'),
					'3' => esc_html__('3', 'adirectory'),
					'2' => esc_html__('2', 'adirectory'),
					'1' => esc_html__('1', 'adirectory'),
				],
			]
		);
		$this->add_control(
			'short_by',
			[
				'label' => esc_html__('Order By', 'adirectory'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'date-desc' => esc_html__('Latest listings', 'adirectory'),
					'date-asc' => esc_html__('Oldest listings', 'adirectory'),
					'views-desc' => esc_html__('Popular listings', 'adirectory'),
					'rating-desc' => esc_html__('5 to 1 (star rating)', 'adirectory'),
					'review-count' => esc_html__('Review Count', 'adirectory'),
					'title-asc' => esc_html__('A to Z (title)', 'adirectory'),
					'title-desc' => esc_html__('Z to A (title)', 'adirectory'),
					'price-asc' => esc_html__('Price (low to high)', 'adirectory'),
					'price-desc' => esc_html__('Price (high to low)', 'adirectory'),
					'rand' => esc_html__('Random listings', 'adirectory'),
				],
				'default' => 'date-desc',
			]
		);


		$this->add_control(
			'per_page',
			[
				'label' => esc_html__('Per Page Items', 'adirectory'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 6,
			]
		);


		$this->end_controls_section(); // end: Section

		// Carousel setting
		$this->start_controls_section(
			'slider_option',
			[
				'label'     => esc_html__('Carousel Option', 'adirectory'),
				'condition' => [
					'pagination_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'slitems',
			[
				'label'     => esc_html__('Slider Items', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 20,
				'step'      => 1,
				'default'   => 3,
			]
		);

		$this->add_control(
			'slrows',
			[
				'label'     => esc_html__('Slider Rows', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 5,
				'step'      => 1,
			]
		);



		$this->add_control(
			'slarrows',
			[
				'label'        => esc_html__('Slider Arrow', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes',
			]
		);

		$this->add_responsive_control(
			'slitemmargin',
			[
				'label'     => esc_html__('Slider Item Margin', 'element-ready-pro'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'default'   => 1,
				'selectors' => [
					'{{WRAPPER}} .adqs-slick-item-col' => 'margin-left: {{VALUE}}px;margin-right:{{VALUE}}px;',
					'{{WRAPPER}} .slick-list'   => 'margin-left: -{{VALUE}}px;margin-right: -{{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'sldots',
			[
				'label'        => esc_html__('Slider dots', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slpause_on_hover',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __('No', 'adirectory'),
				'label_on'     => __('Yes', 'adirectory'),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes',
				'label'        => __('Pause on Hover?', 'adirectory'),
			]
		);

		$this->add_control(
			'slcentermode',
			[
				'label'        => esc_html__('Center Mode', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slcenterpadding',
			[
				'label'     => esc_html__('Center padding', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 500,
				'step'      => 1,
				'default'   => 50,
				'condition' => [
					'slcentermode' => 'yes',
				]
			]
		);

		$this->add_control(
			'slfade',
			[
				'label'        => esc_html__('Slider Fade', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slfocusonselect',
			[
				'label'        => esc_html__('Focus On Select', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slvertical',
			[
				'label'        => esc_html__('Vertical Slide', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slinfinite',
			[
				'label'        => esc_html__('Infinite', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'slrtl',
			[
				'label'        => esc_html__('RTL Slide', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slautolay',
			[
				'label'        => esc_html__('Slider auto play', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slautoplay_speed',
			[
				'label'     => __('Autoplay speed', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
			]
		);


		$this->add_control(
			'slanimation_speed',
			[
				'label'     => __('Autoplay animation speed', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 300,
			]
		);

		$this->add_control(
			'slscroll_columns',
			[
				'label'     => __('Slider item to scroll', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'default'   => 1,
			]
		);

		$this->add_control(
			'heading_tablet',
			[
				'label'     => __('Tablet', 'adirectory'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'sltablet_display_columns',
			[
				'label'     => __('Slider Items', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 8,
				'step'      => 1,
				'default'   => 1,
			]
		);

		$this->add_control(
			'sltablet_scroll_columns',
			[
				'label'     => __('Slider item to scroll', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 8,
				'step'      => 1,
				'default'   => 1,
			]
		);

		$this->add_control(
			'sltablet_width',
			[
				'label'       => __('Tablet Resolution', 'adirectory'),
				'description' => __('The resolution to tablet.', 'adirectory'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 750,
			]
		);

		$this->add_control(
			'heading_mobile',
			[
				'label'     => __('Mobile Phone', 'adirectory'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slmobile_display_columns',
			[
				'label'     => __('Slider Items', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'default'   => 1,
			]
		);

		$this->add_control(
			'slmobile_scroll_columns',
			[
				'label'     => __('Slider item to scroll', 'adirectory'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'default'   => 1,
			]
		);

		$this->add_control(
			'slmobile_width',
			[
				'label'       => __('Mobile Resolution', 'adirectory'),
				'description' => __('The resolution to mobile.', 'adirectory'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 480,
			]
		);

		$this->end_controls_section();
		/*-----------------------
            SLIDER OPTIONS END
        -------------------------*/

		$this->start_controls_section(
			'all_listing_filter_container_styles',
			[
				'label' => esc_html__('Filter Box', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'filter_show' => 'true',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'all_listing_filter_title_typography',
				'selector' => '{{WRAPPER}} .qsd-prodcut-grid-with-side-bar-titel',
			]
		);

		$this->add_control(
			'all_listing_filter_title_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-grid-with-side-bar-titel' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'all_listing_filter_container_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-main-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'all_listing_container_styles',
			[
				'label' => esc_html__('Lisitng Container', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'all_listing_grid_container_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-grid-with-side-bar-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'all_listing_styles',
			[
				'label' => esc_html__('Lisitng Grid', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'all_listing_style_tabs'
		);

		$this->start_controls_tab(
			'all_listing_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'adirectory'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'all_listing_title_typography',
				'selector' => '{{WRAPPER}} .grid-list-inner-txt',
			]
		);

		$this->add_control(
			'all_listing_title_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .grid-list-inner-txt' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'all_listing_title_wishlist_color',
			[
				'label' => esc_html__('Fovorite Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-single-group.adqs-add-fav-btn button path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'all_listing_grid_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-grid-list-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'all_listing_style_hover_tab',
			[
				'label' => esc_html__('Hover', 'adirectory'),
			]
		);
		$this->add_control(
			'all_listing_title_hover_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .grid-list-inner-txt:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'all_listing_title_wishlist_hover_color',
			[
				'label' => esc_html__('Fovorite Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-single-group.adqs-active-fav button path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render Advanced widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$action_elementor = Plugin::instance()->editor->is_edit_mode() ? true : false;
		$filter_show = $settings['filter_show'] ?? 'true';
		$top_bar_show = $settings['top_bar_show'] ?? 'true';
		$pagination_type = $settings['pagination_type'] ?? 'pagination';
		$view_type = $settings['view_type'] ?? 'grid';
		$rating = $settings['rating'] ?? '';
		$display_listings = $settings['display_listings'] ?? '';
		$short_by = $settings['short_by'] ?? '';
		$per_page = $settings['per_page'] ?? 6;

		// arr val
		$directory_type = $this->get_join_data($settings['directory_type']);
		$category = $this->get_join_data($settings['category']);
		$location = $this->get_join_data($settings['location']);
		$tags = $this->get_join_data($settings['tags']);

		$carousel_settings = [];
		if ($pagination_type === 'carousel') {
			$carousel_settings = [
				'arrows'           => ('yes' === ($settings['slarrows'] ?? '')),
				'dots'             => ('yes' === ($settings['sldots'] ?? '')),
				'autoplay'         => ('yes' === ($settings['slautoplay'] ?? '')),
				'autoplay_speed'   => absint($settings['slautoplay_speed'] ?? 3000),
				'animation_speed'  => absint($settings['slanimation_speed'] ?? 300),
				'pause_on_hover'   => ('yes' === ($settings['slpause_on_hover'] ?? '')),
				'center_mode'      => ('yes' === ($settings['slcentermode'] ?? '')),
				'center_padding'   => !empty($settings['slcenterpadding'] ?? '') ? $settings['slcenterpadding'] . 'px' : '50px',
				'rows'             => absint($settings['slrows']  ?? 1),
				'fade'             => ('yes' === ($settings['slfade'] ?? '')),
				'focusonselect'    => ('yes' === ($settings['slfocusonselect'] ?? '')),
				'vertical'         => ('yes' === ($settings['slvertical'] ?? '')),
				'rtl'              => ('yes' === ($settings['slrtl'] ?? '')),
				'infinite'         => ('yes' === ($settings['slinfinite'] ?? '')),
				'display_columns'        => absint($settings['slitems'] ?? 3),
				'scroll_columns'         => absint($settings['slscroll_columns'] ?? 1),
				'tablet_width'           => absint($settings['sltablet_width'] ?? 750),
				'tablet_display_columns' => absint($settings['sltablet_display_columns'] ?? 1),
				'tablet_scroll_columns'  => absint($settings['sltablet_scroll_columns'] ?? 1),
				'mobile_width'           => absint($settings['slmobile_width'] ?? 480),
				'mobile_display_columns' => absint($settings['slmobile_display_columns'] ?? 1),
				'mobile_scroll_columns'  => absint($settings['slmobile_scroll_columns'] ?? 1),
			];
		}

		$carousel_settings = maybe_serialize($carousel_settings);


		$shortcode = "[adqs_listings 
		filter_show='{$filter_show}' 
		top_bar_show='{$top_bar_show}' 
		pagination_type='{$pagination_type}'
		view_type='{$view_type}'
		per_page='{$per_page}'
		directory_type='{$directory_type}'
		category='{$category}'
		location='{$location}'
		tags='{$tags}'
		rating='{$rating}'
		display_listings='{$display_listings}'
		short_by='{$short_by}'
		carousel_settings='{$carousel_settings}'
		from_addon='true'
		]";
		echo do_shortcode($shortcode);
	}

	/**
	 * Render  widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */


	//protected function _content_template(){}

}
Plugin::instance()->widgets_manager->register(new Listing_By());
