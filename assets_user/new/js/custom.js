$(document).ready(function() {
    customScript.init({});
});

var self;
var customScript = {
    init: function(settings) {
        this.settings = settings;
        self = this;

        this.utilities();

    },
    utilities: function() {
        $('html:not(.rtl) .home-banner .slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            fade: true
        });
        $('.rtl .home-banner .slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            fade: true,
            rtl: true
        });
        // $('.home-banner .slider').slick({
        //     infinite: true,
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     fade: true
        // });

        $(' html:not(.rtl) .detail-slider .slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: false,
            infinite: false,
            asNavFor: '.slider-nav',
            responsive: [{
                breakpoint: 1000,
                settings: {
                    autoplay: true,
                    arrows: false
                }
            }, ]
        });


        $('.rtl .detail-slider .slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: false,
            infinite: false,
            asNavFor: '.slider-nav',
            rtl: true,
            responsive: [{
                breakpoint: 1000,
                settings: {
                    autoplay: true,
                    arrows: false
                }
            }, ]
        });

        $('.detail-slider .slider-nav').slick({
            slidesToShow: 9,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
            variableWidth: true,
            infinite: false
        });

        // $('html:not(.rtl) .detail-slider .slider-nav').slick({
        //     slidesToShow: 9,
        //     slidesToScroll: 1,
        //     asNavFor: '.slider-for',
        //     dots: false,
        //     arrows: false,
        //     centerMode: false,
        //     focusOnSelect: true,
        //     variableWidth: true,
        //     infinite: false
        // });
        // $('.rtl .detail-slider .slider-nav').slick({
        //     slidesToShow: 9,
        //     slidesToScroll: 1,
        //     asNavFor: '.slider-for',
        //     dots: false,
        //     arrows: false,
        //     centerMode: false,
        //     focusOnSelect: true,
        //     variableWidth: true,
        //     infinite: false,
        //     rtl: true
        // });
        // $('.show-img .slider-for-img').slick({
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     autoplay: true,
        //     arrows: true,
        //     fade: false,
        //     infinite: true,
        //     // asNavFor: '.slider-nav',
        //     responsive: [{
        //         breakpoint: 1000,
        //         settings: {
        //             autoplay: true,
        //             arrows: false
        //         }
        //     }, ]
        // });
        $('html:not(.rtl) .show-img .slider-for-img').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            arrows: true,
            fade: false,
            infinite: true,
            // asNavFor: '.slider-nav',
            responsive: [{
                breakpoint: 1000,
                settings: {
                    autoplay: true,
                    arrows: false
                }
            }, ]
        });
        $('.rtl .show-img .slider-for-img').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            arrows: true,
            fade: false,
            infinite: true,
            rtl: true,
            // asNavFor: '.slider-nav',
            responsive: [{
                breakpoint: 1000,
                settings: {
                    autoplay: true,
                    arrows: false
                }
            }, ]
        });
        // $('.navigation .list').slick({
        //   slidesToShow: 6,
        //   slidesToScroll: 1,
        //   dots: false,
        //   arrows: false,
        //   focusOnSelect: true,
        //   variableWidth: true,
        //   infinite : false,
        //   // centerMode: true,
        //   swipe: true,
        //   swipeToSlide: true,
        //   // initialSlide: 0,
        // });

        $('.filter-title').on("click", function() {
            $('.filter').slideToggle();
        });
        // $('.links h3').on("click", function() {
        //     $('.links ul').slideToggle();
        // });
        // $('.links h4').on("click", function() {
        //     $('.links ul').slideToggle();
        // });
        // $(".testimonial-slider").slick({
        //     dots: true,
        //     arrows: false,
        //     infinite: false,
        //     speed: 300,
        //     slidesToShow: 1,
        //     variableWidth: true,
        //     focusOnSelect: true,
        //     responsive: [{
        //             breakpoint: 992,
        //             settings: {
        //                 slidesToShow: 2,
        //                 slidesToScroll: 1,
        //                 variableWidth: false,
        //                 focusOnSelect: false,
        //             }
        //         },
        //         {
        //             breakpoint: 767,
        //             settings: {
        //                 slidesToShow: 1,
        //                 slidesToScroll: 1,
        //                 variableWidth: false,
        //                 focusOnSelect: false,
        //             }
        //         }
        //     ]
        // });

        $(".menu_icon").on("click", function() {
            $("html").addClass("open-menu");
        });

        $(".close-icon").on("click", function() {
            $("html").removeClass("open-menu");
        });

        $('.search_icon').on("click", function() {
            $('.new_mobile_menu').find('.search_wrapper').slideToggle();
        });


        $(document).on("click", ".close", function() {
            if ($('.modal.show').length) {
                setTimeout(function() {
                    $('body').addClass('modal-open');
                }, 500);
            }
        });

        // modal_custom-Link

        if ($(window).width() <= 1000) {
            $(".footer-links h4").on("click", function() {
                $(this).parent().toggleClass("active");
                $(this).parent().find("ul").slideToggle();
            });
        }

        if ($(window).width() <= 1000) {
            $(".outer-footer h3").on("click", function() {
                $(this).parent().toggleClass("active");
                $(this).parent().find("ul").slideToggle();
            });
        }

        // if ($(window).width() <= 1000) {
        //     $(".products .row").slick({
        //         dots: false,
        //         arrows: false,
        //         infinite: false,
        //         speed: 300,
        //         slidesToShow: 1,
        //         variableWidth: true,
        //         focusOnSelect: true,
        //     });
        // }

        $('.advance-filter h3').on("click", function() {
            $('.toggle-filter').slideToggle();
            $('.advance-filter h3').toggleClass('filter-down filter-up');

        });
        $(document).on("click", ".filter-icon", function() {
            $(".left-bar.filter-respnsive").slideDown('slow');
        });
        $(document).on("click", ".view-more1", function() {
            $(".about-inner .desc p").toggleClass('v-height l-height');
            // console.log($(".view-more1").text());
            // var txt = $(".about-inner .desc p").is(':visible') ? 'View More' : 'View Less';
            var txt = ($(".view-more1").text() == 'View More') ? 'View Less' : 'View More';
            $(".view-more1").text(txt);

        });

        $(document).on("click", ".close-filter", function() {
            $(".left-bar.filter-respnsive").slideUp('slow');
        });
        $(function() {

            $('#clear').click(function() {
                $('.selectpicker').selectpicker('val', '');
                $(':input', '#filtersForm')
                    // $(':select', '#filtersForm')
                    // .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');
            });

        });
        var total_slides = $(".detail-slider .slider-for").find(".slick-slide").length;
        var current_slide = $(".detail-slider .slider-for").find(".slick-current").index() + 1;
        $(".detail-slider").find(".current").text(current_slide);
        $(".detail-slider").find(".total").text(total_slides);


        $('.slick-initialized').on('afterChange', function(event, slick, currentSlide, nextSlide, nextPrev) {
            var total_slides = $(".detail-slider .slider-for").find(".slick-slide").length;
            var current_slide = $(".detail-slider .slider-for").find(".slick-current").index() + 1;
            $(".detail-slider").find(".current").text(current_slide);
            $(".detail-slider").find(".total").text(total_slides);
        });

        // $(".datatable").DataTable({
        //     "language": {
        //         "paginate": {
        //             "previous": '<span class="material-icons">keyboard_arrow_left</span>',
        //             "next": '<span class="material-icons">keyboard_arrow_right</span>'
        //         }
        //     }
        // });

        $('.datatable-checkbox').DataTable({
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [
                [1, 'asc']
            ]
        });

        $('[data-toggle="tooltip"]').tooltip();

        $(".date-picker").datepicker({
            autoclose: true,
        });

        $('#imgModal').on('shown.bs.modal', function() {
            $('.show-img .slider-for-img')[0].slick.refresh();
        });
    }
}