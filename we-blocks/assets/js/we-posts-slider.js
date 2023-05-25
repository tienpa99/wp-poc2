/*
We Block Posts Slider
*/
jQuery(document).ready(function($) {
  var masnoryContainer = $(".we_psb_container");
  masnoryContainer.each(function() {
    var sliderSettings = {
      dots: $(this).data("enabledots") ? $(this).data("enabledots") : true,
      infinite: $(this).data("infinite") ? $(this).data("infinite") : true,
      slidesToShow: $(this).data("slidestoshow") ? $(this).data("slidestoshow") : 1,
      slidesToScroll: $(this).data("slidestoscroll") ? $(this).data("slidestoscroll") : 1,
      fade: $(this).data("fadeanimation") ? $(this).data("fadeanimation") : false,
      autoplay:$(this).data("autoplay") ? $(this).data("autoplay") : true,
      speed: $(this).data("animationspeed") ? $(this).data("animationspeed") : 1000,
      autoplaySpeed: $(this).data("autoplaydelay") ? $(this).data("autoplaydelay") : 4000,
      initialSlide: $(this).data("initialslide") ? $(this).data("initialslide") : 0,
      lazyLoad: $(this).data("ladzloadslides") ? $(this).data("ladzloadslides") : false,
      adaptiveHeight: $(this).data("adaptiveheight") ? $(this).data("adaptiveheight") : true,
      pauseOnHover: $(this).data("pauseonhover") ? $(this).data("pauseonhover") : true,
      swipeToSlide: $(this).data("swipetoslide") ? $(this).data("swipetoslide") : false,
      vertical: $(this).data("verticalslider") ? $(this).data("verticalslider") : false,
      focusOnSelect: $(this).data("focusonselect") ? $(this).data("focusonselect") : false,
      // rtl: $(this).data("reverseslidescroll") ? $(this).data("reverseslidescroll") : false, //images are not showing due to some bug in js
      arrows: $(this).data("showarrows") ? $(this).data("showarrows") : true,
      variableWidth: $(this).data("variablewidth") ? $(this).data("variablewidth") : false,
      centerMode: $(this).data("centermode") ? $(this).data("centermode") : false,
      cssEase: $(this).data("cssease") ? $(this).data("cssease") : "linear" ,
      dotsClass: "slick-dots "+ ($(this).data("sliderdotsclass") ? $(this).data("sliderdotsclass") : ""),
    }

    if(sliderSettings['slidesToShow'] == 1){
      $(".we_psb_container").addClass('single_slide');
    }

    if($(this).data("responsivesettings")){
      sliderSettings["responsive"] = [
        {
         breakpoint : 1024,
         settings: {
           slidesToShow: $(this).data("deskslidestoshow") ? $(this).data("deskslidestoshow") : 1,
           slidesToScroll: $(this).data("deskslidestoscroll") ? $(this).data("deskslidestoscroll") : 1,
           arrows: $(this).data("deskarrows") ? $(this).data("deskarrows") : true,
           dots: $(this).data("deskdots") ? $(this).data("deskdots") : true
         }
        },
        {
         breakpoint : 600,
         settings: {
           slidesToShow: $(this).data("tabslidestoshow") ? $(this).data("tabslidestoshow") : 1,
           slidesToScroll: $(this).data("tabslidestoscroll") ? $(this).data("tabslidestoscroll") : 1,
           arrows: $(this).data("tabarrows") ? $(this).data("tabarrows") : true,
           dots: $(this).data("tabdots") ? $(this).data("tabdots") : true
         }
        },
        {
         breakpoint : 480,
         settings: {
           slidesToShow: $(this).data("mobslidestoshow") ? $(this).data("mobslidestoshow") : 1,
           slidesToScroll: $(this).data("mobslidestoscroll") ? $(this).data("mobslidestoscroll") : 1,
           arrows: $(this).data("mobarrows") ? $(this).data("mobarrows") : true,
           dots: $(this).data("mobdots") ? $(this).data("mobdots") : true
         }
        }
      ]     
    }
    $(this).slick(sliderSettings);    
  });
});
