<?php
/*
 * All Listing
*/

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



class Listing_Taxonomy extends Widget_Base
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
        return 'adqs_taxonomy';
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
        return esc_html__('AD : All Taxonomy', 'directory');
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
        return 'eicon-product-categories';
    }

    /**
     * Retrieve the list of  the widget belongs to.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget Scroll Sequence.
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
        return ['adqs_taxonomy_archive', 'slick', 'slick-init'];
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
                'label' => esc_html__('Options', 'directory'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );



        $this->add_control(
            'top_bar_show',
            [
                'label'        => esc_html__('Top Bar Show ?', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'directory'),
                'label_off'    => esc_html__('No', 'directory'),
                'return_value' => 'true',
                'default'      => 'true',
            ]
        );


        $this->add_control(
            'pagination_type',
            [
                'label' => esc_html__('Pagination Type', 'directory'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'multiple' => true,
                'options' => [
                    '' => esc_html__('None', 'directory'),
                    'pagination' => esc_html__('Pagination', 'directory'),
                    'carousel' => esc_html__('Carousel', 'directory'),
                ],
                'default' => 'pagination',
            ]
        );


        $this->end_controls_section(); // end: Section

        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Taxonomy Query', 'directory'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'tax_name',
            [
                'label' => esc_html__('Taxonomy Type', 'directory'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'adqs_category' => esc_html__('Categories', 'directory'),
                    'adqs_location' => esc_html__('Locations', 'directory'),
                ],
                'default' => 'adqs_category'
            ]
        );



        $this->add_control(
            'include_category',
            [
                'label' => esc_html__('Include Categories', 'directory'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->getTerms('adqs_category', 'term_id'),
                'condition' => [
                    'tax_name' => 'adqs_category',
                ],

            ]
        );
        $this->add_control(
            'include_location',
            [
                'label' => esc_html__('Include Locations', 'directory'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->getTerms('adqs_location', 'term_id'),
                'condition' => [
                    'tax_name' => 'adqs_location',
                ],
            ]
        );
        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'directory'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__('ASC', 'directory'),
                    'DESC' => esc_html__('DESC', 'directory'),
                ],
                'default' => 'ASC',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order By', 'directory'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'name' => esc_html__('Name', 'directory'),
                    'slug' => esc_html__('Slug', 'directory'),
                    'term_id' => esc_html__('Term Id', 'directory'),
                    'term_order' => esc_html__('Term Order', 'directory'),
                    'count' => esc_html__('Count', 'directory'),
                ],
                'default' => 'count',
            ]
        );


        $this->add_control(
            'per_page',
            [
                'label' => esc_html__('Per Page Items', 'directory'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 10,
            ]
        );


        $this->end_controls_section(); // end: Section


        // Carousel setting
        $this->start_controls_section(
            'slider_option',
            [
                'label'     => esc_html__('Carousel Option', 'directory'),
                'condition' => [
                    'pagination_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'slitems',
            [
                'label'     => esc_html__('Slider Items', 'directory'),
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
                'label'     => esc_html__('Slider Rows', 'directory'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 5,
                'step'      => 1,
            ]
        );



        $this->add_control(
            'slarrows',
            [
                'label'        => esc_html__('Slider Arrow', 'directory'),
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
                    '{{WRAPPER}} .qsd-tax-grid-single' => 'margin-left: {{VALUE}}px;margin-right:{{VALUE}}px;',
                    '{{WRAPPER}} .slick-list'   => 'margin-left: -{{VALUE}}px;margin-right: -{{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'sldots',
            [
                'label'        => esc_html__('Slider dots', 'directory'),
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
                'label_off'    => __('No', 'directory'),
                'label_on'     => __('Yes', 'directory'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'label'        => __('Pause on Hover?', 'directory'),
            ]
        );

        $this->add_control(
            'slcentermode',
            [
                'label'        => esc_html__('Center Mode', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slcenterpadding',
            [
                'label'     => esc_html__('Center padding', 'directory'),
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
                'label'        => esc_html__('Slider Fade', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slfocusonselect',
            [
                'label'        => esc_html__('Focus On Select', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slvertical',
            [
                'label'        => esc_html__('Vertical Slide', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slinfinite',
            [
                'label'        => esc_html__('Infinite', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'slrtl',
            [
                'label'        => esc_html__('RTL Slide', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slautoplay',
            [
                'label'        => esc_html__('Slider auto play', 'directory'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'slautoplay_speed',
            [
                'label'     => __('Autoplay speed', 'directory'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
            ]
        );


        $this->add_control(
            'slanimation_speed',
            [
                'label'     => __('Autoplay animation speed', 'directory'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
            ]
        );

        $this->add_control(
            'slscroll_columns',
            [
                'label'     => __('Slider item to scroll', 'directory'),
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
                'label'     => __('Tablet', 'directory'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'sltablet_display_columns',
            [
                'label'     => __('Slider Items', 'directory'),
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
                'label'     => __('Slider item to scroll', 'directory'),
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
                'label'       => __('Tablet Resolution', 'directory'),
                'description' => __('The resolution to tablet.', 'directory'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 750,
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label'     => __('Mobile Phone', 'directory'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'slmobile_display_columns',
            [
                'label'     => __('Slider Items', 'directory'),
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
                'label'     => __('Slider item to scroll', 'directory'),
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
                'label'       => __('Mobile Resolution', 'directory'),
                'description' => __('The resolution to mobile.', 'directory'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 480,
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            SLIDER OPTIONS END
        -------------------------*/
    }

    /**
     * Render Advanced Products widget output on the frontend.
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
        $tax_name = $settings['tax_name'] ?? 'adqs_category';
        $top_bar_show = $settings['top_bar_show'] ?? 'true';
        $pagination_type = $settings['pagination_type'] ?? 'pagination';

        $order = $settings['order'] ?? 'DESC';
        $orderby = $settings['orderby'] ?? 'count';
        $per_page = $settings['per_page'] ?? '10';

        // Retrieve terms based on taxonomy
        $terms = $this->get_join_data($settings['include_category']);
        if ($tax_name === 'adqs_location') {
            $terms = $this->get_join_data($settings['include_location']);
        }
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
        $shortcode = "[adqs_taxonomies
        tax_name='{$tax_name}'
        top_bar_show='{$top_bar_show}'
        pagination_type='{$pagination_type}'
        per_page='{$per_page}'
        terms='{$terms}'
        order='{$order}'
        orderby='{$orderby}'
        carousel_settings='{$carousel_settings}'
        from_addon='true'
    ]";

        echo do_shortcode($shortcode);
    }


    /**
     * Render Advanced Products widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */


    //protected function _content_template(){}

}
Plugin::instance()->widgets_manager->register(new Listing_Taxonomy());
