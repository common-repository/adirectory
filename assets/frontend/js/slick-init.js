(function ($) {
    /*
     * Swiper init
     */
    function adqs_slick_init() {
        if ($('.qsd-slick-wrapper').length === 0) {
            return;
        }
        $('.qsd-slick-wrapper').each(function () {
            let that = $(this),
            settings = that.data('settings');

            // Extract settings with defaults
            let {
                arrows = false,
                dots = false,
                autoplay = false,
                autoplay_speed = 3000,
                animation_speed = 300,
                pause_on_hover = false,
                center_mode = false,
                center_padding = '50px',
                rows = 1,
                fade = false,
                focusonselect = false,
                vertical = false,
                infinite = true,
                rtl = false,
                display_columns = 1,
                scroll_columns = 1,
                tablet_width = 800,
                tablet_display_columns = 1,
                tablet_scroll_columns = 1,
                mobile_width = 480,
                mobile_display_columns = 1,
                mobile_scroll_columns = 1,
                uniq_id = 1
            } = settings;

            // Parse integer values where necessary
            autoplay_speed = parseInt(autoplay_speed, 10);
            animation_speed = parseInt(animation_speed, 10);
            center_padding = center_padding ? center_padding : '50px';
            rows = parseInt(rows, 10);
            display_columns = parseInt(display_columns, 10);
            scroll_columns = parseInt(scroll_columns, 10);
            tablet_width = parseInt(tablet_width, 10);
            tablet_display_columns = parseInt(tablet_display_columns, 10);
            tablet_scroll_columns = parseInt(tablet_scroll_columns, 10);
            mobile_width = parseInt(mobile_width, 10);
            mobile_display_columns = parseInt(mobile_display_columns, 10);
            mobile_scroll_columns = parseInt(mobile_scroll_columns, 10);

			if(rtl){
				$('.qsd-slick-wrapper').attr('dir','rtl');
			}

            // Initialize Slick slider
            that.not('.slick-initialized').slick({
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                arrows: arrows,
                nextArrow: ".adqs-slick-prev-"+uniq_id,
                prevArrow: ".adqs-slick-next-"+uniq_id,
                dots: dots,
                customPaging: function (slider, i) {
                    return '<button type="button"></button>';
                },
                appendDots: $(".adqs-slick-pagination-"+uniq_id),
                pauseOnHover: pause_on_hover,
                centerMode: center_mode,
                centerPadding: center_padding,
                rows: rows,
                fade: fade,
                focusOnSelect: focusonselect,
                vertical: vertical,
                infinite: infinite,
                rtl: rtl,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        });
    }

    /*
     * Load all functions when DOM is ready
     */
    $(function () {
        if (typeof adqs_slick_init === 'function') {
            adqs_slick_init();
        }
    });

    /*----- ELEMENTOR LOAD FUNCTION CALL ---*/
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/adqs_alllistings.default', adqs_slick_init);
        elementorFrontend.hooks.addAction('frontend/element_ready/adqs_taxonomy.default', adqs_slick_init);
    });
})(jQuery);
