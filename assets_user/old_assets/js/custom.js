jQuery(document).ready(function($){ 
  customScript.init({
  });
});

var self;
var customScript = {
  init: function(settings){
    this.settings = settings;
    self = this;
        
    this.utilities();
  },
  utilities: function(){

    $('.datepicker').datepicker({
        autoclose: true
    });

    $('.timepicker').timepicker();

    $(".card-header button").on("click" , function(e){
      e.preventDefault();
      var faq = $(this).closest(".card");
      if (faq.hasClass("active")) {
        faq.removeClass("active");
      }
      else {
        $(".card").removeClass("active")
        faq.addClass("active");
      }
    });

    // $(".timer").TimeCircles({
    //   start:true,
    //   animation:"smooth",
    //   circle_bg_color:"transparent",
    //   fg_width: .1,
    //   bg_width: .1,
    //   time: {
    //     Days: {
    //       show:true,
    //       text:"Days",
    //       color:"#fff"
    //     },
    //     Hours: {
    //       show:true,
    //       text:"Hours",
    //       color:"#fff"
    //     },
    //     Minutes: {
    //       show:true,
    //       text:"Mins",
    //       color:"#fff"
    //     },
    //     Seconds: {
    //       show:true,
    //       text:"Secs",
    //       color:"#fff"
    //     }
    //   }

    // });

    /*$('.timer').syotimer({
        year: 2020,
        month: 4,
        day: 31,
        hour: 20,
        minute: 30
    });*/

    $("select").selectpicker(); 

    //$('.rating').raty();

    $('.data-table').DataTable( {
        "dom": '<"top"i>rt<"bottom"flp>',
        // columnDefs: [ {
        //     orderable: false,
        //     className: 'select-checkbox',
        //     targets:   0
        // } ],
        // select: {
        //     style:    'os',
        //     selector: 'td:first-child'
        // },
        // order: [[ 1, 'asc' ]]
    });

    $('.data-table2.with-checkbox').DataTable( {
        "dom": '<"top"i>rt<"bottom"flp>',
      columnDefs: [ {
        targets: 0,
        data: null,
        defaultContent: '',
        orderable: false,
        className: 'select-checkbox'
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]]
    });

    $(".select-checkbox").on("click", function() {
      $(this).parents().find(".selected").removeClass("selected");
      $(this).parent().addClass("selected")
    });

    $(".grid-view").on("click" , function(e){
      e.preventDefault();
      $(".page-wrapper").addClass("gird-show");
      $(".grid-view").addClass("active");
      $(".list-view").removeClass("active");
    });

    $(".list-view").on("click" , function(e){
      e.preventDefault();
      $(".page-wrapper").removeClass("gird-show");
      $(".list-view").addClass("active");
      $(".grid-view").removeClass("active");

    });

    $('html:not(.rtl) .slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.slider-nav'
    });
    $('html:not(.rtl) .slider-nav').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      focusOnSelect: true,
      responsive: [
        {
          breakpoint: 1025,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 670,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 501,
          settings: {
            slidesToShow: 2,
          }
        },
        ]
    });

    $('html.rtl .slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      rtl: true,
      fade: true,
      asNavFor: '.slider-nav'
    });
    $('html.rtl .slider-nav').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      rtl: true,
      focusOnSelect: true,
      responsive: [
        {
          breakpoint: 1025,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 670,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 501,
          settings: {
            slidesToShow: 2,
          }
        },
        ]
    });

    $('html:not(.rtl) .our-services .slide').slick({
      infinite: true,
      slidesToShow: 6,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1180,
          settings: {
            slidesToShow: 5,
          }
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 720,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 578,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 390,
          settings: {
            slidesToShow: 1,
          }
        },
      ]
    });

    $('html.rtl .our-services .slide').slick({
      infinite: true,
      slidesToShow: 6,
      slidesToScroll: 1,
      rtl: true,
      responsive: [
        {
          breakpoint: 1180,
          settings: {
            slidesToShow: 5,
          }
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 720,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 578,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 390,
          settings: {
            slidesToShow: 1,
          }
        },
      ]
    });

    $('html:not(.rtl) .upcoming-events .slide').slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1180,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    $('html.rtl .upcoming-events .slide').slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      rtl: true,
      responsive: [
        {
          breakpoint: 1180,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });
     
    $(".scroll-arrow").click(function(e){
      e.preventDefault();
      $('html,body').animate({
        scrollTop : $(".our-services").offset().top},'slow');
    });  

    $(".catergory a" ).on("click", function(e){
      e.preventDefault();
      $(this).toggleClass("active");
      $(this).parent().find("ul").slideToggle();
    }); 

    $(".file-uploader input[type='file']").on("change" , function(e){
      var file = e.target.files[0].name;
      $(this).parent().find('.ozlabel').text(file);
    });

    $(".menu-icon").on("click", function() {
      $("body").addClass("menu-open");
    });
    $(".close-icon").on("click", function() {
      $("body").removeClass("menu-open");
    });

    $('html:not(.rtl) .clients-slider').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 501,
          settings: {
            slidesToShow: 2,
          }
        }
      ]
    });

     $('html.rtl .clients-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        rtl: true,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 3,
            }
          },
          {
            breakpoint: 501,
            settings: {
              slidesToShow: 2,
            }
          }
        ]
    });

    $(".our-history .nav-tabs li a").on("click", function() {
      var href = $(this).attr("href");

      $(".nav-tabs li").removeClass("active");
      $(this).parent().addClass("active");

      $(".tab-pane").removeClass("active")
      $(".tab-content").find(href).addClass("active");
    });

    $(".get-start").on("click", function(e){
      e.preventDefault();
      $(".valuation-page").addClass("step2");
      // $(".steps li").removeClass("active");
      $(".steps li:nth-child(2)").addClass("active");
    });

    $(window).load(function() {
      setTimeout(function(){
        new WOW().init();
      }, 700);
    });

    $('html:not(.rtl) .feed-slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 1000,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 501,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });

    $('html.rtl .feed-slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      autoplay: true,
      rtl: true,
      autoplaySpeed: 1000,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 501,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });

    $('html:not(.rtl) .banner-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
    });

    $('html.rtl .banner-slider').slick({
      slidesToShow: 1,
      rtl: true,
      slidesToScroll: 1,
    });

    $(".slick-slider .slick-slider").slick('unslick');

    $('.carousel').carousel({
       interval: 1000,
       pause: "false"
    });

  }
}

$(window).on("load", function() {
  setTimeout(function(){
    $(".skitter").skitter({
      auto_play: false,
      dots: false,
      navigation: true,
    });
  }, 800);
})