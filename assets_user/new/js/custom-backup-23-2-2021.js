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
        $('.home-banner .slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true
        });

        $('.detail-slider .slider-for').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          fade: false,
          infinite: false,
          asNavFor: '.slider-nav'
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
          infinite : false
        });

        $('.filter-title').on("click", function(){
          $('.filter').slideToggle();
        });

        $(".testimonial-slider").slick({
          dots: true,
          arrows: false,
          infinite: false,
          speed: 300,
          slidesToShow: 1,
          variableWidth: true,
          focusOnSelect: true,
          responsive: [
              {
              breakpoint: 992,
                  settings: {
                      slidesToShow: 2,
                      slidesToScroll: 1,
                      variableWidth: false,
                      focusOnSelect: false,
                  }
              },
              {
              breakpoint: 767,
                  settings: {
                      slidesToShow: 1,
                      slidesToScroll: 1,
                      variableWidth: false,
                      focusOnSelect: false,
                  }
              }
          ]
      });

      $(".menu-toggler").on("click", function() {
          $("html").toggleClass("menu-open");
      });

      $(document).on("click", ".close", function(){
        if ($('.modal.show').length) {
          setTimeout(function(){
            $('body').addClass('modal-open');
          }, 500);
        }  
      });

      // modal_custom-Link


      // $(".outer-footer h3").on("click", function () {
      //     $(this).parent().toggleClass("active");
      //     $(this).parent().find("ul").slideToggle();
      // });

      $('.advance-filter h3').on("click",function(){
          $('.toggle-filter').slideToggle();
      });

        var total_slides = $(".detail-slider .slider-for").find(".slick-slide").length;
        var current_slide = $(".detail-slider .slider-for").find(".slick-current").index() + 1;
        $(".detail-slider").find(".current").text(current_slide);
        $(".detail-slider").find(".total").text(total_slides);


      $('.slick-initialized').on('afterChange', function(event, slick, currentSlide, nextSlide, nextPrev){
        var total_slides = $(".detail-slider .slider-for").find(".slick-slide").length;
        var current_slide = $(".detail-slider .slider-for").find(".slick-current").index() + 1;
        $(".detail-slider").find(".current").text(current_slide);
        $(".detail-slider").find(".total").text(total_slides);
      });

      $(".datatable").DataTable();

      $('.datatable-checkbox').DataTable( {
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
      });

      $('[data-toggle="tooltip"]').tooltip();

      $(".date-picker").datepicker({
        autoclose: true,
      });
    }
}