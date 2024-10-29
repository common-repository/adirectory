<?php
/*
 * All Listing
*/

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



class Topmisc extends Widget_Base
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
		return 'adqs_topmisc';
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
		return esc_html__('AD : Top Misc', 'adirectory');
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

	//Script dependency

	public function get_style_depends()
	{
		return ['adqs-top-misc'];
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

		$activated_plugins = get_option('active_plugins', []);

		$extension_check = true;


		if (in_array('ad-compare-listing/ad-compare-listing.php', $activated_plugins) && $extension_check) {
			$this->start_controls_section(
				'compare_content_option',
				[
					'label' => esc_html__('Compare Listing', 'directory'),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'hide_compare',
				[
					'label' => __('Hide Compare Listing', 'adirectory'),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __('Show', 'adirectory'),
					'label_off' => __('Hide', 'adirectory'),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'compare_icon',
				[
					'label' => esc_html__('Icon', 'adirectory'),
					'type' => \Elementor\Controls_Manager::ICONS,
					'condition' => [
						'hide_compare' => 'yes',
					],
					'default' => [
						'value' => 'fa fa-code-compare',
						'library' => 'fa-solid',
					],
				]
			);

			$this->end_controls_section();
		}




		$this->start_controls_section(
			'fav_content_option',
			[
				'label' => esc_html__('Favourite Listing', 'directory'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hide_fav',
			[
				'label' => __('Hide Favourite Listing', 'adirectory'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'adirectory'),
				'label_off' => __('Hide', 'adirectory'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'fav_icon',
			[
				'label' => esc_html__('Icon', 'adirectory'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'hide_fav' => 'yes',
				],
				'default' => [
					'value' => 'fas fa-heart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'addlist_content_option',
			[
				'label' => esc_html__('Add Listing', 'directory'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'add_listing',
			[
				'label' => __('Hide Add Listing', 'adirectory'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'adirectory'),
				'label_off' => __('Hide', 'adirectory'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'addlist_icon',
			[
				'label' => esc_html__('Icon', 'adirectory'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'add_listing' => 'yes',
				],
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'addlist_btn_text',
			[
				'label' => __('Add Listing Button Text', 'adirectory'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Add Listing', 'adirectory'),
				'condition' => [
					'add_listing' => 'yes',
				],
				'placeholder' => __('Type your title here', 'adirectory'),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dashboard_content_option',
			[
				'label' => esc_html__('Account', 'directory'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hide_dash',
			[
				'label' => __('Hide Account', 'adirectory'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'adirectory'),
				'label_off' => __('Hide', 'adirectory'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'dashbaord_icon',
			[
				'label' => esc_html__('Icon', 'adirectory'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => [
					'hide_dash' => 'yes',
				],
				'default' => [
					'value' => 'fas fa-user',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'dash_btn_text',
			[
				'label' => __('Before Login Button Text', 'adirectory'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('My Account', 'adirectory'),
				'condition' => [
					'hide_dash' => 'yes',
				],
				'placeholder' => __('Type your title here', 'adirectory'),
			]
		);
		$this->add_control(
			'after_dash_btn_text',
			[
				'label' => __('After Login Button Text', 'adirectory'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('My Account', 'adirectory'),
				'condition' => [
					'hide_dash' => 'yes',
				],
				'placeholder' => __('Type your title here', 'adirectory'),
			]
		);

		$this->end_controls_section();


		//Style controle

		$this->start_controls_section(
			'top_misc_global_style',
			[
				'label' => esc_html__('Global Styles', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .adqs-topmisc-bar',
			]
		);


		$this->end_controls_section();

		//Compare

		$this->start_controls_section(
			'compare_style_Section',
			[
				'label' => esc_html__('Compare Styles', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'compare_svg_color',
			[
				'label' => __('Compare Svg Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-compare-widget-wrapper .rel_icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'compare_svg_size',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Compare Svg Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-compare-widget-wrapper .rel_icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'compare_outer_bg',
			[
				'label' => __('Compare Outer Bg', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-compare-widget-wrapper a' => 'background: {{VALUE}}',
				],
			]
		);



		$this->add_control(
			'compare_count_bg',
			[
				'label' => __('Compare Count Bg', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-compare-widget-wrapper .abs-count' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'compare_count_text',
			[
				'label' => __('Compare Count Font Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-compare-widget-wrapper .abs-count' => 'color: {{VALUE}}',
				],
			]
		);




		$this->end_controls_section();

		//Favorite styles

		$this->start_controls_section(
			'fav_style_section',
			[
				'label' => esc_html__('Favourite Styles', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'fav_svg_color',
			[
				'label' => __('Favourite Svg Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-favlist-widget-wrapper .rel_icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'fav_svg_size',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Favourite Svg Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-favlist-widget-wrapper .rel_icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'fav_outer_bg',
			[
				'label' => __('Favourite Outer Bg', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-favlist-widget-wrapper a' => 'background: {{VALUE}}',
				],
			]
		);



		$this->add_control(
			'fav_count_bg',
			[
				'label' => __('Favourite Count Bg', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-favlist-widget-wrapper .abs-count' => 'background: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'fav_count_text',
			[
				'label' => __('Favorite Count Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-favlist-widget-wrapper .abs-count' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_section();

		//Add listing styles

		$this->start_controls_section(
			'addlist_style_section',
			[
				'label' => esc_html__('Add Listing  Styles', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Addlist Button Styles Tab Control
		$this->start_controls_tabs(
			'addlist_style_tabs'
		);

		$this->start_controls_tab(
			'addlist_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'textdomain'),
			]
		);

		$this->add_control(
			'addlist_svg_color',
			[
				'label' => __('Icon Svg Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper .btn_icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'addlist_svg_size',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Svg Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper .btn_icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'addlist_text_color',
			[
				'label' => __('Button Text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper .btn_text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'addlist_btn_txt_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Font Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper .btn_text' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'addlist_btn_bg',
			[
				'label' => __('Button Background', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper a' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'addlist_btn_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border_addlist',
				'selector' => '{{WRAPPER}} .adqs-addlist-widget-wrapper a',
			]
		);

		$this->add_responsive_control(
			'addlist_btn_border_radius',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('Border Radius', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'addlist_style_hover_tab',
			[
				'label' => esc_html__('Hover', 'textdomain'),
			]
		);
		$this->add_control(
			'addlist_svg_hover_color',
			[
				'label' => __('Icon Hover Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper:hover .btn_icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'addlist_text_color_hover',
			[
				'label' => __('Text Hover Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper:hover .btn_text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'addlist_btn_bg_hover',
			[
				'label' => __('Button Hover Background', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-addlist-widget-wrapper:hover a' => 'background: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .adqs-addlist-widget-wrapper:hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		//dashboard styles

		$this->start_controls_section(
			'dasah_style_section',
			[
				'label' => esc_html__('Account Styles', 'adirectory'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'dash_svg_color',
			[
				'label' => __('Account icon Svg Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper .btn_icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'dash_svg_size',
		// 	[
		// 		'label' => __('Dashboard Svg Size', 'adirectory'),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'selectors' => [
		// 			'{{WRAPPER}} .adqs-dshaboard-widget-wrapper .btn_icon svg' => 'width: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'dash_svg_size',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Account Svg Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper .btn_icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'dash_text_color',
			[
				'label' => __('Account btn text Color', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper .btn_text' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_responsive_control(
			'dash_btn_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__('ACCOUNT Button Padding', 'adirectory'),
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->add_control(
			'dash_btn_bg',
			[
				'label' => __('account Button Bg', 'adirectory'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper a' => 'background: {{VALUE}}',
				],
			]
		);


		$this->add_responsive_control(
			'dash_btn_txt_size',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Account Font Size Font Size', 'adirectory'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .adqs-dshaboard-widget-wrapper .btn_text' => 'font-size: {{SIZE}}{{UNIT}};',
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
		$pages = get_option('adqs_onboarding_pages', []);

		$active_plugins = get_option('active_plugins');


		ob_start(); ?>

		<div class="adqs-topmisc-bar">
			<!-- Compare listing -->
			<?php
			if (isset($settings['hide_compare']) && $settings['hide_compare'] === "yes") :
				$listing_ids = isset($_COOKIE['qsd_compare_listing_ids']) ? $_COOKIE['qsd_compare_listing_ids'] : '';
				$compare_count = empty($listing_ids) ? 0 : count(explode(',', $listing_ids));
				$compare_permalink = adqs_get_permalink_by_key('adqs_compare_listing' ?? '');
			?>
				<div class="adqs-compare-widget-wrapper com-fav-common">
					<a href="<?php echo esc_url($compare_permalink); ?>" class="compare-inner-relative">
						<span class="rel_icon">
							<?php \Elementor\Icons_Manager::render_icon($settings['compare_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<span class="abs-count"><?php echo esc_html($compare_count); ?></span>
					</a>
				</div>

			<?php endif; ?>

			<!-- Favorite listing -->

			<?php
			if (isset($settings['hide_fav']) && $settings['hide_fav'] === "yes") :
				$fav_count = !empty(get_user_meta(get_current_user_id(), 'adqs_user_fav_list', true)) ? count(get_user_meta(get_current_user_id(), 'adqs_user_fav_list', true)) : count([]);

				$fav_permalink = add_query_arg(array(
					'path' => 'fav-listings',
				), adqs_get_permalink_by_key('adqs_user_dashboard' ?? ''));

			?>
				<div class="adqs-favlist-widget-wrapper com-fav-common">
					<a href="<?php echo esc_url($fav_permalink); ?>" class="compare-inner-relative">
						<span class="rel_icon">
							<?php \Elementor\Icons_Manager::render_icon($settings['fav_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<span class="abs-count"><?php echo esc_html($fav_count); ?></span>
					</a>
				</div>

			<?php endif; ?>



			<!-- Myaccount\Dashboard -->

			<?php
			if (isset($settings['hide_dash']) && $settings['hide_dash'] === "yes") :
				$dash_btn_text = $settings['dash_btn_text'] ?? esc_html__('My Account', 'adirectory');
				$after_dash_btn_text = $settings['after_dash_btn_text'] ?? esc_html__('My Account', 'adirectory');
				$dash_permalink = adqs_get_permalink_by_key('adqs_user_dashboard' ?? '');
				$logged_in = is_user_logged_in();
				$dash_permalink = $logged_in ? wp_logout_url($dash_permalink) : $dash_permalink;
				$btn_text = $logged_in ? $after_dash_btn_text : $dash_btn_text;


			?>
				<div
					class="adqs-dshaboard-widget-wrapper btn-icon-text <?php echo $logged_in ? esc_attr('adqs-has-login') : ''; ?>">
					<a href="<?php echo esc_url($dash_permalink); ?>" class="icon-text-wrapper ">
						<span class="btn_icon">
							<?php \Elementor\Icons_Manager::render_icon($settings['dashbaord_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<span class="btn_text"><?php echo esc_html($btn_text); ?></span>
					</a>
				</div>

			<?php endif; ?>


			<!-- Add Listing -->

			<?php

			if (isset($settings['add_listing']) && $settings['add_listing'] === "yes") :
				$addlist_btn_text = $settings['addlist_btn_text'] ?? esc_html__('Add Listing', 'adirectory');
				$addlist_permalink = adqs_get_permalink_by_key('adqs_add_listing' ?? '');

			?>
				<div class="adqs-addlist-widget-wrapper btn-icon-text">
					<a href="<?php echo esc_url($addlist_permalink); ?>" class="icon-text-wrapper">
						<span class="btn_icon">
							<?php \Elementor\Icons_Manager::render_icon($settings['addlist_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<span class="btn_text"><?php echo esc_html($addlist_btn_text); ?></span>
					</a>
				</div>

			<?php endif;
			?>

		</div>

<?php echo ob_get_clean();
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


Plugin::instance()->widgets_manager->register(new Topmisc());
