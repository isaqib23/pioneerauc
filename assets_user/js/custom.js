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
		this.timer();
	},
	utilities: function(){

		// $(document).on("change", "#make", function(){
		// 	// alert();
		// 	$('#model').selectpicker('refresh');
		// });
		// $('#model').selectpicker('refresh');
		
		$(function() {
	          AOS.init();
	     });
		

		$(".nav-icon").on("click", function() {
			$("html").addClass("menu-open");
		});
		$(".close-modal").on("click", function() {
			$("html").removeClass("menu-open");
		});

		$(".head.show-on-reponsive").on("click", function(){
			$(".toggle-wrapper").slideToggle();
		});

		// $('#datetimepicker').datetimepicker();
		

		$('html:not(.rtl) .banner-slider').slick({
		  infinite: true,
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  arrows: false,
		  dots: true,
		  autoplay: true
		});

		$('.rtl .banner-slider').slick({
		  infinite: true,
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  arrows: false,
		  rtl: true,
		  dots: true,
		  autoplay: true
		});

		$(".faq .card-header button").on("click" , function(e){
		      e.preventDefault();
		      var faq = $(this).closest(".card");
		      if (faq.hasClass("active")) {
		        faq.removeClass("active");
		      }
		      else {
		        $(".card").removeClass("active");
		        faq.addClass("active");
		      }
		    });

		$partner_slider = $('html:not(.rtl) .partner-slider').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			responsive: [
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 601,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 385,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$partner_slider = $('.rtl .partner-slider').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			rtl: true,
  			responsive: [
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 601,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 385,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});
		if ($('html').hasClass('rtl')) {
			$partner_slider.slick('slickSetOption', 'rtl', true ,true);
		} else {
			$partner_slider.slick('slickSetOption', 'rtl', false ,false);
		}
		$partner_slider.slick('slickPlay');

		$('html:not(.rtl) .live-auction:not(.extended-slider) .upcomming-slider').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			responsive: [
				{
					breakpoint: 801,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$('.rtl .live-auction:not(.extended-slider) .upcomming-slider').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			rtl: true,
  			responsive: [
				{
					breakpoint: 801,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$upcomming_slider = $('html:not(.rtl) .live-auction.extended-slider .upcomming-slider').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			responsive: [
  				{
					breakpoint: 1201,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 801,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$upcomming_slider = $('.rtl .live-auction.extended-slider .upcomming-slider').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 2000,
  			arrows: false,
  			dots: false,
  			rtl: true,
  			responsive: [
  				{
					breakpoint: 1201,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 801,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});
		if ($('html').hasClass('rtl')) {
			$upcomming_slider.slick('slickSetOption', 'rtl', true ,true);
			$upcomming_slider.slick('slickPlay');
		} else {
			$upcomming_slider.slick('slickSetOption', 'rtl', false ,false);
			$upcomming_slider.slick('slickPlay');
		}

		$pro_slider = $('.pro-slider .main').slick({
			infinite: false,
			slidesToShow: 1,
			slidesToScroll: 1,
  			arrows: false,
  			dots: false,
  			asNavFor: '.pro-slider .thumb'
		});
		if ($('html').hasClass('rtl')) {
			$pro_slider.slick('slickSetOption', 'rtl', true ,true);
			$pro_slider.slick('slickPlay');
		} else {
			$pro_slider.slick('slickSetOption', 'rtl', false ,false);
			$pro_slider.slick('slickPlay');
		}

		$pro_slider_thumb = $('html:not(.rtl) .pro-slider .thumb').slick({
			infinite: false,
			slidesToShow: 5,
			slidesToScroll: 1,
  			arrows: true,
  			dots: false,
  			focusOnSelect: true,
  			asNavFor: '.pro-slider .main',
  			responsive: [
				{
					breakpoint: 668,
					settings: {
						slidesToShow: 4,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 415,
					settings: {
						slidesToShow: 2,
					}
				},
			]
		});

		$pro_slider_thumb = $('.rtl .pro-slider .thumb').slick({
			infinite: false,
			slidesToShow: 5,
			slidesToScroll: 1,
  			arrows: true,
  			dots: false,
  			rtl: true,
  			focusOnSelect: true,
  			asNavFor: '.pro-slider .main',
  			responsive: [
				{
					breakpoint: 668,
					settings: {
						slidesToShow: 4,
					}
				},
				{
					breakpoint: 501,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 415,
					settings: {
						slidesToShow: 2,
					}
				},
			]
		});
		if ($('html').hasClass('rtl')) {
			$pro_slider_thumb.slick('slickSetOption', 'rtl', true ,true);
			$pro_slider_thumb.slick('slickPlay');
		} else {
			$pro_slider_thumb.slick('slickSetOption', 'rtl', false ,false);
			$pro_slider_thumb.slick('slickPlay');
		}

		$('html:not(.rtl) .related-items .slider').slick({
			// infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
  			arrows: true,
  			dots: false,
  			responsive: [
				{
					breakpoint: 1101,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 676,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$('.rtl .related-items .slider').slick({
			// infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
  			arrows: true,
  			dots: false,
  			responsive: [
				{
					breakpoint: 1101,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 993,
					settings: {
						slidesToShow: 2,
					}
				},
				{
					breakpoint: 676,
					settings: {
						slidesToShow: 1,
					}
				},
			]
		});

		$(".custom-accordion .head").on("click", function() {
			$(this).parent().find(".body").slideToggle();
			$(this).parent().toggleClass("active");
		});

		// $(".list").on("click", function(e) {
		// 	e.preventDefault();
		// 	$(this).addClass("active");
		// 	$(this).parents(".tab-pane").find('.grid').removeClass("active");
		// 	$(this).parents(".tab-pane").addClass("list-view");
		// 	$(this).parents(".tab-pane").removeClass("grid-view");

		// });
		// $(".grid").on("click", function(e) {
		// 	e.preventDefault();
		// 	$(this).addClass("active");
		// 	$(this).parents(".tab-pane").find('.list').removeClass("active");
		// 	$(this).parents(".tab-pane").addClass("grid-view");
		// 	$(this).parents(".tab-pane").removeClass("list-view");
		// });



		// $(".list").on("click", function(e) {
		// 	e.preventDefault();
		// 	$(this).addClass("active");
		// 	$('.grid').removeClass("active");
		// 	$(".listing-page").addClass("list-view");
		// 	$(".listing-page").removeClass("grid-view");
		// });
		// $(".grid").on("click", function(e) {
		// 	e.preventDefault();
		// 	$(this).addClass("active");
		// 	$('.list').removeClass("active");
		// 	$(".listing-page").removeClass("list-view");
		// 	$(".listing-page").addClass("grid-view");
		// });


		$(".list").on("click", function(e) {
		 	e.preventDefault();
		 	$(this).addClass("active");
		 	$(this).parents(".tab-pane").find('.grid').removeClass("active");
		 	$(this).parents(".tab-pane").addClass("list-view");
		 	$(this).parents(".tab-pane").removeClass("grid-view");

		 });
		 $(".grid").on("click", function(e) {
		 	e.preventDefault();
		 	$(this).addClass("active");
		 	$(this).parents(".tab-pane").find('.list').removeClass("active");
		 	$(this).parents(".tab-pane").addClass("grid-view");
		 	$(this).parents(".tab-pane").removeClass("list-view");
		});


		// if(navigator.platform.toUpperCase().indexOf('MAC')>=0) {
		// 	$('body').addClass('mac');
		// }

		$(".auto-bid").on("click", function(e) {
			e.preventDefault();
			$(".bid-form").slideToggle();
		});

		$(".toggle-link").on("click", function(e) {
			e.preventDefault();
			$(this).parent().find(".sub-menu").slideToggle();
		});

		$(document).on("click", ".live-banner .desc h3 a", "click", function(e) {
			e.preventDefault();
			var id = $(this).attr("href");
			$(".stocks-wrapper").find(".stocks").hide();
			$(".stocks-wrapper").find(id).show();
		});

		$(".advance-filter").on("click", function(e) {
			e.preventDefault();
			$(this).toggleClass("active");
			$(".advance-wrapper").slideToggle();
		});

	},
	timer: function() {
		$('.timer').syotimer({
	        year: 2020,
            month: 08,
            day: 25,
            hour: 18,
            minute: 34  
	    });	
	}
}