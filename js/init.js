/*-----------------------------------------------------------------------------------
/*
/* Init JS
/*
-----------------------------------------------------------------------------------*/

 jQuery(document).ready(function() {

/*----------------------------------------------------*/
/*	gmaps
------------------------------------------------------*/

   var map;

   // main directions
   map = new GMaps({
      el: '#map', lat: 43.1723624, lng: 16.4408177, zoom: 16, zoomControl : true,
      zoomControlOpt: { style : 'SMALL', position: 'TOP_LEFT' }, panControl : false, scrollwheel: false
   });

   // add address markers
   map.addMarker({ lat: 43.1723624, lng: 16.4408177, title: 'Nautica Bar' });

/*----------------------------------------------------*/
/*	animations
------------------------------------------------------*/
     jQuery('.contact-fade').addClass("hidden_2").viewportChecker({
         classToAdd: 'visible_2 animated flipInX',
         offset: 100,
         repeat: false
     });

/*----------------------------------------------------*/
/*	tesemonials slider
------------------------------------------------------*/
     //create the slider
     $('.cd-testimonials-wrapper').flexslider({
         selector: ".cd-testimonials > li",
         animation: "slide",
         controlNav: false,
         slideshow: true,
         slideshowSpeed: 4000,
         smoothHeight: true,
         start: function(){
             $('.cd-testimonials').children('li').css({
                 'opacity': 1,
                 'position': 'relative'
             });
         }
     });

     //open the testimonials modal page
     $('.cd-see-all').on('click', function(){
         $('.cd-testimonials-all').addClass('is-visible');
     });

     //close the testimonials modal page
     $('.cd-testimonials-all .close-btn').on('click', function(){
         $('.cd-testimonials-all').removeClass('is-visible');
     });
     $(document).keyup(function(event){
         //check if user has pressed 'Esc'
         if(event.which=='27'){
             $('.cd-testimonials-all').removeClass('is-visible');
         }
     });

     //build the grid for the testimonials modal page
     $('.cd-testimonials-all-wrapper').children('ul').masonry({
         itemSelector: '.cd-testimonials-item'
     });

/*----------------------------------------------------*/
/*	main image slider
------------------------------------------------------*/


     var options = {
         $FillMode: 2,                                       //[Optional] The way to fill image in slide, 0 stretch, 1 contain (keep aspect ratio and put all inside slide), 2 cover (keep aspect ratio and cover whole slide), 4 actual size, 5 contain for large image, actual size for small image, default value is 0
         $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
         $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
         $PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

         $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
         $SlideEasing: $JssorEasing$.$EaseOutQuint,          //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
         $SlideDuration: 800,                               //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
         $MinDragOffsetToSlide: 20,
         $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
         $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
         $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
         $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
         $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
         $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

         $BulletNavigatorOptions: {                          //[Optional] Options to specify and enable navigator or not
             $Class: $JssorBulletNavigator$,                 //[Required] Class to create navigator instance
             $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
             $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
             $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
             $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
             $SpacingX: 8,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
             $SpacingY: 8,                                   //[Optional] Vertical space between each item in pixel, default value is 0
             $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
         },

         $ArrowNavigatorOptions: {                           //[Optional] Options to specify and enable arrow navigator or not
             $Class: $JssorArrowNavigator$,                  //[Requried] Class to create arrow navigator instance
             $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
             $AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
             $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
         }
     };

     var jssor_slider1 = new $JssorSlider$("slider1_container", options);

     //responsive code begin
     //you can remove responsive code if you don't want the slider scales while window resizes
     function ScaleSlider() {
         var bodyWidth = document.body.clientWidth;
         if (bodyWidth)
             jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 1920));
         else
             window.setTimeout(ScaleSlider, 30);
     }
     ScaleSlider();

     $(window).bind("load", ScaleSlider);
     $(window).bind("resize", ScaleSlider);
     $(window).bind("orientationchange", ScaleSlider);
     //responsive code end

/*----------------------------------------------------*/
/*	social and partners aside
------------------------------------------------------*/
     $("#social_aside").hide();
     $("#partners_aside").hide();
     $(function() {
         $(window).scroll(function() {
             if ($(this).scrollTop() > 600) {
                 $("#social_aside").fadeIn();
                 $("#partners_aside").fadeIn();
             } else {
                 $("#social_aside").fadeOut();
                 $("#partners_aside").fadeOut();
             }
         });
     });

     $('img#sail_croatia').mouseenter(function() {
         $('img#sail_croatia').css("right", "0");
     });
     $('img#sail_croatia').mouseleave(function() {
         $('img#sail_croatia').css("right", "-25px");
     });
     $('img#fanatics').mouseenter(function() {
         $('img#fanatics').css("right", "0");
     });
     $('img#fanatics').mouseleave(function() {
         $('img#fanatics').css("right", "-25px");
     });
     $('img#villa_skansi').mouseenter(function() {
         $('img#villa_skansi').css("right", "0");
     });
     $('img#villa_skansi').mouseleave(function() {
         $('img#villa_skansi').css("right", "-25px");
     });

/*----------------------------------------------------*/
/* music player
------------------------------------------------------*/

     setTimeout(function() {
         audiojs.events.ready(function() {
             audiojs.createAll();
         });
     }, 3000);

/*----------------------------------------------------*/
/* hvar images
------------------------------------------------------*/
     $(".fancybox").fancybox();

     $(".lazy").lazyload({
         effect : "fadeIn"
     });
});