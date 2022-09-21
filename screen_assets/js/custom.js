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
  	$('.slider .main').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  autoplay: true,
	  autoplaySpeed: 1000,
	  pauseOnHover: false,
	  arrows: false,
	  fade: true,
	  asNavFor: '.slider .thumb'
	});
	$('.slider .thumb').slick({
	  slidesToShow: 5,
	  slidesToScroll: 1,
	  asNavFor: '.slider .main',
	  focusOnSelect: true,
	  arrows: false,
	});
  }
}