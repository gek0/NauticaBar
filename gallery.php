<?php
require_once "inc/database.php";

    //get event name
    $event_name = $_GET["event"];

    //is event name empty
    if(empty($event_name)){
        header("Location: ./");
        die();
    }

    //does event really exists in DB
    $db_check = $events->event_exists_gallery($event_name);
    if($db_check == false){
        header("Location: ./");
        die();
    }

    $event_id = $db_check[0]['id'];

    //get all data for current event
    $eventData = $events->getEvent($event_id);

    //get images from event gallery
    $eventGallery = array();
    $eventGallery = $events->getEventGallery($event_id);

    //get number of unique arrays
    if(!empty($eventGallery))
    {
        $unique_arrays = count(array_unique($eventGallery, SORT_REGULAR));
    }
    else
    {
        header("Location: ./");
        die();
    }

?>
<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="ie ie7" lang="hr"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="hr"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html lang="hr"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <title>Nautica Bar ~ <?php echo $eventData['name']; ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="nautica, bar, hvar, croatia, events, concerts" />
	<meta name="description" content="Nautica Bar is the finest club/bar in Hvar, Croatia">
	<meta name="author" content="Nautica Bar">
    <meta property="og:title" content="Club/bar in Hvar, Croatia" />
    <meta property="og:site_name" content="Nautica Bar" />
    <meta property="og:url" content="http://www.nautica-bar.com/" />
    <meta property="og:description" content="Nautica Bar is the finest club/bar in Hvar, Croatia." />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="http://www.nautica-bar.com/nautica.png" />

    <link rel="canonical" href="http://www.nautica-bar.com/" />
    <link rel="image_src" href="nautica.png" />
    <link rel="shortcut icon" href="favicon.png" >

    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/animsition.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="js/animsition.js"></script>
    <script src="js/modernizr.js"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
<div class="animsition">
   <header class="mobile">
      <div class="row">
         <div class="col full">
            <div class="logo">
               <a href="#"><img alt="" src="images/logo.png"></a>
            </div>
            <nav id="nav-wrap">
               <a class="mobile-btn" href="#nav-wrap" title="Prikaži navigaciju">Prikaži navigaciju</a>
	           <a class="mobile-btn" href="#" title="Sakrij navigaciju">Sakrij navigaciju</a>
               <ul id="nav" class="nav">
	               <li class="active"><a href="./">Početna / Homepage</a></li>
               </ul>
            </nav>
         </div>
      </div>
   </header> <!-- Header End -->

   <div id="social_aside">
       <a href="https://www.facebook.com/nautica.bar" target="_blank"><img src="images/facebook_aside.png" alt="Facebook aside" title="Facebook" /></a>
       <a href="https://www.youtube.com/channel/UCNfgGj0KG8pcr7BeVnR_Smg" target="_blank"><img src="images/youtube_aside.png" alt="YouTube aside" title="YouTube" /></a>
   </div>

    <div id="partners_aside">
        <img src="images/partner_sail_croatia.png" alt="Sail Croatia aside" title="Sail Croatia" id="sail_croatia" />
        <img src="images/parter_fanatics.png" alt="Fanatics aside" title="Fanatics" id="fanatics" />
        <img src="images/partner_villa_skansi.png" alt="Villa Skansi aside" title="Villa Skansi" id="villa_skansi" />
    </div>

   <section id="gallery">
       <div class="contentContainer">
           <h1><?php echo $eventData['name']; ?></h1>
           <p><?php echo $eventData['description']; ?></p>
           <p><?php echo $eventData['description_en']; ?></p>

           <div id="grid-gallery" class="grid-gallery">
               <section class="grid-wrap">
                   <ul class="grid">
                       <li class="grid-sizer"></li>
                       <?php
                           for($i = 0; $i < $unique_arrays; $i++)
                           {
                                echo "<li>
                                        <figure>
                                            <img class='lazy' src='".$eventGallery[$i]['file_location'].$eventGallery[$i]['file_name']."' alt='".imageAlt($eventGallery[$i]['file_name'])."' />
                                        </figure>
                                      </li>";
                           }
                       ?>
                   </ul>
               </section><!-- // grid-wrap -->
               <section class="slideshow">
                   <ul>
                       <?php
                           for($i = 0; $i < $unique_arrays; $i++)
                           {
                               echo "<li>
                                        <figure>
                                            <img class='lazy borderedGallery' src='".$eventGallery[$i]['file_location'].$eventGallery[$i]['file_name']."' alt='".imageAlt($eventGallery[$i]['file_name'])."' />
                                        </figure>
                                     </li>";
                           }
                       ?>
                   </ul>
               <nav>
                   <span class="icon nav-prev" title="Previous / Prethodna"></span>
                   <span class="icon nav-next" title="Next / Sljedeća"></span>
                   <span class="icon nav-close" title="Close / Zatvori"></span>
               </nav>
               </section><!-- // slideshow -->
           </div><!-- // grid-gallery -->
       </div>

   </section>  <!-- end section#gallery -->

   <footer>
      <div class="row">
         <div class="col g-7">
            <ul class="copyright">
               <li>&copy; <?php echo date('Y'); ?> Nautica Bar</li>
               <li>Design &amp; code by <span class="copyright-span">Matija Buriša</span></li>
            </ul>
         </div>

         <div class="col g-5 pull-right text-center">
             <div class="social-links">
                <a href="https://www.facebook.com/nautica.bar" target="_blank"><span class="facebook-social-slide"></span></a>
                <a href="https://www.youtube.com/channel/UCNfgGj0KG8pcr7BeVnR_Smg" target="_blank"><span class="youtube-social-slide"></span></a>
             </div>
         </div>
          <div class="clear"></div>
          <div class="partners-links">
              <a href="http://www.sail-croatia.com/" target="_blank"><img src="images/partner_sail_croatia.png" alt="Sail Croatia aside" title="Sail Croatia" /></a>
              <a href="http://www.thefanatics.com/" target="_blank"><img src="images/parter_fanatics.png" alt="Fanatics aside" title="Fanatics" /></a>
              <a href="http://www.tripadvisor.com/Hotel_Review-g303808-d1520421-Reviews-Villa_Skansi-Hvar_Hvar_Island_Split_Dalmatia_County_Dalmatia.html" target="_blank"><img src="images/partner_villa_skansi.png" alt="Villa Skansi aside" title="Villa Skansi" /></a>
          </div>
      </div>
   </footer> <!-- Footer End-->


   <script src="js/jquery-migrate-1.2.1.min.js"></script>
   <script src="js/cbpGridGallery.js"></script>
   <script src="js/jquery.lazyload.min.js"></script>
   <script src="js/imagesloaded.pkgd.min.js"></script>
   <script src="js/masonry.pkgd.min.js"></script>
   <script src="js/classie.js"></script>
   <script src="js/cbpGridGallery.js"></script>
   <script>
       new CBPGridGallery(document.getElementById('grid-gallery'));
   </script>
   <script>
       jQuery(document).ready(function() {
           $(".animsition").animsition({
               inClass               :   'fade-in',
               outClass              :   'fade-out',
               inDuration            :    1000,
               outDuration           :    800,
               linkElement           :   '.animsition-link',
               loading               :    true,
               loadingParentElement  :   'body',
               loadingClass          :   'animsition-loading',
               unSupportCss          : [ 'animation-duration',
                   '-webkit-animation-duration',
                   '-o-animation-duration'
               ],
               overlay               :   false,
               overlayClass          :   'animsition-overlay-slide',
               overlayParentElement  :   'body'
           });
       });

       $(document).ready(function() {
           $("#social_aside").hide();
           $("#partners_aside").hide();
           $(function() {
               $(window).scroll(function() {
                   if ($(this).scrollTop() > 200) {
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
       });
   </script>
</div> <!-- end animsition -->
</body>
</html>