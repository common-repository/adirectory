<?php
/*
 * All Listing
*/

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



class Listing_Search_Bar extends Widget_Base
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
		return 'adqs_search_Bar';
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
		return esc_html__('AD : Search Bar', 'adirectory');
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
		return 'eicon-site-search';
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
			'search_page_url',
			[
				'label' => esc_html__('Search Page URL', 'adirectory'),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'options' => false,
			]
		);
		$this->add_control(
			'search_page_notice',
			[
				'type' => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => true,
				'heading' => esc_html__('Add Search Page URL', 'adirectory'),
				'content' => esc_html__('Add your Search page Url here.For exapmle: You can put you all listing page URL here.', 'adirectory'),
			]
		);
		$this->add_control(
			'new_tab',
			[
				'label'        => esc_html__('Open New Tab ?', 'adirectory'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'adirectory'),
				'label_off'    => esc_html__('No', 'adirectory'),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->end_controls_section(); // end: Section


		//Style controle

		$this->start_controls_section(
			'search_box_styles',
			[
				'label' => esc_html__('Search Box', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .qsd-prodcut-main-box',
			]
		);

		$this->add_responsive_control(
			'searchbox_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-main-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .qsd-prodcut-main-box',
			]
		);

		$this->add_responsive_control(
			'searchbox_border_radius',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Border Radius', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .qsd-prodcut-main-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .qsd-prodcut-main-box',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'searchbox_button_styles',
			[
				'label' => esc_html__('Search Button', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// Addlist Button Styles Tab Control
		$this->start_controls_tabs(
			'searchbox_button_style_tabs'
		);

		$this->start_controls_tab(
			'searchbox_button_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'adirectory'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'searchbox_content_typography',
				'selector' => '{{WRAPPER}} .qsd-main-btn',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'searchbox_button_text_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-main-btn' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'searchbox_background',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .qsd-main-btn',
			]
		);

		$this->add_responsive_control(
			'searchbox_button_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-main-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'searchbox_border',
				'selector' => '{{WRAPPER}} .qsd-main-btn',
			]
		);

		$this->add_responsive_control(
			'searchbox_button_border_radius',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Border Radius', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-main-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'searchbox_button_style_hoverl_tab',
			[
				'label' => esc_html__('Hover', 'adirectory'),
			]
		);

		$this->add_control(
			'searchbox_button_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-main-btn:hover' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'searchbox_hover_background',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .qsd-main-btn:hover',
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'search_input_box_styles',
			[
				'label' => esc_html__('Search Input', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'searchbox_input_typography',
				'selector' => '{{WRAPPER}} .qsd-form-select, {{WRAPPER}} .qsd-form-input',
			]
		);

		$this->add_control(
			'searchbox_input_text_color',
			[
				'label' => esc_html__('Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qsd-form-select,{{WRAPPER}} .qsd-form-input' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'searchbox_input_text_border_radius',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Border Radius', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .qsd-searchBar-wrap .qsd-form-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
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
		$search_page_url = $settings['search_page_url']['url'] ?? '';
		$new_tab = $settings['new_tab'] ?? 'true';


		$shortcode = "[adqs_search 
		search_page_url='{$search_page_url}' 
		new_tab='{$new_tab}' 
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
Plugin::instance()->widgets_manager->register(new Listing_Search_Bar());
