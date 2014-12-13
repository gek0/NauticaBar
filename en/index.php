<?php
require_once "../inc/database.php";
require_once "../inc/dragonPreload.php";

$_SESSION['captcha'] = simple_php_captcha();

    //get events data
    $eventsData = array();
    $eventsData = $events->getLastEventsPublic();

    //get cover photos
    $eventCovers = array();
    $eventCovers = $events->getBarCovers();

    //get contact settings data
    $contact_settings = $users->contact_get_data();

    //get number of unique events and covers
    $unique_arrays = count(array_unique($eventsData, SORT_REGULAR));
    $unique_arrays2 = count(array_unique($eventCovers, SORT_REGULAR));

?>
<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="ie ie7" lang="hr"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="hr"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html lang="hr"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <title>Nautica Bar</title>

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
    <link rel="shortcut icon" href="../favicon.png" >

    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/base.css">
	<link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/jquery.fancybox.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="../js/jquery.mousewheel-3.0.6.min.js"></script>
    <script src="../js/jquery.fancybox.min.js"></script>
    <script src="../js/modernizr.js"></script>
    <script src="../js/jssor.js"></script>
    <script src="../js/jssor.slider.js"></script>
    <script src="../music/audiojs/audio.min.js"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body data-spy="scroll" data-target="#nav-wrap">

<div id="ip-container" class="ip-container">
    <!-- initial header -->
    <div class="ip-header">
        <h1 class="ip-logo">
            <?php echo $preLoad; ?>
        </h1>
        <div class="ip-loader">
            <svg class="ip-inner" width="60px" height="60px" viewBox="0 0 80 80">
                <path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
                <path id="ip-loader-circle" class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
            </svg>
        </div>
    </div>


   <div class="ip-main">
   <header class="mobile">
      <div class="row">
         <div class="col full">
            <div class="logo">
               <a href="#"><img alt="" src="../images/logo.png"></a>
            </div>
            <nav id="nav-wrap">
               <a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show navigation</a>
	           <a class="mobile-btn" href="#" title="Hide navigation">Hide navigation</a>
               <ul id="nav" class="nav">
	               <li><a href="#intro">Intro</a></li>
	               <li><a href="#services">Services</a></li>
	               <li><a href="#events">Events</a></li>
                   <li><a href="#about">About us</a></li>
                   <li><a href="#contact">Contact</a></li>
                   <li><a href="../"><img src="../images/croatia.png" alt="Hrvatski" title="Prebacite na hrvatski jezik" /></a></li>
               </ul>
            </nav>
         </div>
      </div>
   </header> <!-- Header End -->

   <div id="social_aside">
       <a href="https://www.facebook.com/nautica.bar" target="_blank"><img src="../images/facebook_aside.png" alt="Facebook aside" title="Facebook" /></a>
       <a href="https://www.youtube.com/channel/UCNfgGj0KG8pcr7BeVnR_Smg" target="_blank"><img src="../images/youtube_aside.png" alt="YouTube aside" title="YouTube" /></a>
   </div>

   <div id="partners_aside">
       <a href="http://www.sail-croatia.com/" target="_blank"><img src="../images/partner_sail_croatia.png" alt="Sail Croatia aside" title="Sail Croatia" id="sail_croatia" /></a>
       <a href="http://www.thefanatics.com/" target="_blank"><img src="../images/parter_fanatics.png" alt="Fanatics aside" title="Fanatics" id="fanatics" /></a>
       <a href="http://www.tripadvisor.com/Hotel_Review-g303808-d1520421-Reviews-Villa_Skansi-Hvar_Hvar_Island_Split_Dalmatia_County_Dalmatia.html" target="_blank"> <img src="../images/partner_villa_skansi.png" alt="Villa Skansi aside" title="Villa Skansi" id="villa_skansi" /></a>
   </div>

   <section id="intro">
       <div id="slider1_container" class="slider_container">
           <div u="slides" class="slider_slides">
               <?php
               for ($j = 0; $j < $unique_arrays2; $j++)
               {
                   echo '<div>
                               <img u="image" src="../'.$eventCovers[$j]['file_location'].$eventCovers[$j]['file_name'].'" alt="'.imageAlt($eventCovers[$j]['file_name']).'" />
                               <div class="slider_title"><img src="../images/logo_big.png" alt="Nautica Bar logo big" title="Nautica Bar" /></div>
                               <div class="slider_text"></div>
                             </div>';
               }
               ?>
           </div>

           <!-- bullet navigator container -->
           <div u="navigator" class="jssorb21" id="bulletNavigator">
               <div u="prototype" id="prototype"></div>
           </div>
           <!-- Bullet Navigator Skin End -->

           <span u="arrowleft" class="jssora21l"></span>
           <span u="arrowright" class="jssora21r"></span>
       </div>
       <!-- Jssor Slider End -->
   </section> <!-- Intro Section End-->


   <!-- Services Section -->
   <section id="services">

      <div class="row section-head">

         <div class="col one-third">
            <h2>Services</h2>
            <p class="desc">See what Nautica Bar offers</p>
         </div>

         <div class="col two-thirds">
            <div class="intro">
             <p>
                 From a wide array of drinks (more than 100 different cocktails and shooters), ever popular pitchers, Jaeger Train (burning Jägerbombs)
                 and our specialty - Nautica Shot!<br>
                 Come and have fun with our variety of offers.
              </p>
            </div>
         </div>

      </div>

      <div class="row">
         <div class="services-wrapper">

            <div class="col two-thirds">
                <div class="image-effect-future">
                    <div class="share-layer">
                        <div class="text-center">
                            <p class="services_effect_title">Bottles</p>
                            <span class="services_effect_price">from 500 kn</span><hr>
                            <img class="services_effect_logo img-responsive" src="../images/logo.png" alt="Nautica Bar services logo" />
                        </div>
                    </div>
                    <div class="image-layer">
                        <img src="../images/services/services_bottles.png" alt="Bottles">
                    </div>
                </div>
            </div>

            <div class="col two-thirds">
                <div class="image-effect-future">
                    <div class="share-layer">
                        <div class="text-center">
                            <p class="services_effect_title">Pitchers</p>
                            <span class="services_effect_price">from 170 kn</span><hr>
                            <img class="services_effect_logo img-responsive" src="../images/logo.png" alt="Nautica Bar services logo" />
                        </div>
                    </div>
                    <div class="image-layer">
                        <img src="../images/services/services_pitchers.png" alt="Pitchers">
                    </div>
                </div>
            </div>

            <div class="col two-thirds">
                <div class="image-effect-future">
                    <div class="share-layer">
                        <div class="text-center">
                            <p class="services_effect_title">Jägerbomb</p>
                                <span class="services_effect_price">35 kn</span><hr>
                            <img class="services_effect_logo img-responsive" src="../images/logo.png" alt="Nautica Bar services logo" />
                        </div>
                    </div>
                    <div class="image-layer">
                        <img src="../images/services/services_jagerBomb.png" alt="Jager Bomb">
                    </div>
                </div>
            </div>

         </div> <!-- Services-Wrapper End -->
      </div> <!--end row -->
   </section> <!-- Services Section End -->

   <section class="module parallax parallax-0"> </section>
   
   <!-- events -->
   <section id="events">

      <div class="row section-head">
         <div class="col full">
            <h2>Events</h2>
            <p class="desc">What is behind us and what can you expect</p>

            <p class="intro">This year we finished in an excellent atmosphere of concerts where we danced,<br> partied like crazy and had a good time.
                             Some famous people from Croatia and others visited us <br>and we hope that next year will be even better.<br><br>

                             We are preparing many new and interesting events for the next summer.<br>
                             The opportunity for the best entertainment in style that our club and staff provides.
         </div>
      </div>

      <div class="row">
		   <!-- Events Wrapper -->
		   <div id="events-wrapper">
               <?php
                   for($i = 0; $i < $unique_arrays; $i++)
                   {
                       echo '<div class="col events-item">
                                <div class="item-wrap">
                                    <a href="#" data-reveal-id="modal-0'.$i.'"><img src="../'.$eventsData[$i]['file_location'].$eventsData[$i]['file_name'].'" alt="'.$eventsData[$i]['name'].'"/></a>
                                    <div class="events-item-meta">
                                        <h5><span>'.$eventsData[$i]['name'].'</span></h5>
                                    </div>
                                </div>
                            </div>';
                   }
               ?>
		   </div> <!-- Events Wrapper End -->
      </div> <!-- End Row -->

      <?php
          for($i = 0; $i < $unique_arrays; $i++)
          {
                echo '<div id="modal-0'.$i.'" class="reveal-modal">
                        <img class="scale-with-grid" src="../'.$eventsData[$i]['file_location'].$eventsData[$i]['file_name'].'" alt="'.$eventsData[$i]['name'].'" />

                         <div class="description-box">
                             <h4>'.$eventsData[$i]['name'].'</h4>
                             <p>'.$eventsData[$i]['description_en'].'</p>
                         </div>

                         <div class="link-box">';
                if($events->eventImageGalleryCounter($eventsData[$i]['id']) > 0)
                {
                    echo '<a target="_blank" href="../gallery.php?event='.$eventsData[$i]['name'].'"><button class="form"><span class="glyphicon glyphicon-picture"></span> Image gallery</button></a>';
                }

                echo '<a class="close-reveal-modal"><button class="form"><span class="glyphicon glyphicon-chevron-up"></span> Close</button></a>
                         </div>
                     </div>';
          }
      ?>

      <br>
      <div class="row">
           <div class="embed-responsive embed-responsive-16by9" id="youtube_frame">
                <iframe class="embed-responsive-item" allowfullscreen="" src="//www.youtube.com/embed/p37oJpF_t1g?&amp;showinfo=0&amp;rel=0&amp;hd=1&amp;vq=hd720&amp;wmode=transparent"></iframe>
           </div>
      </div>
      <br><hr>

   </section> <!-- Portfolio End -->

   <section class="module parallax parallax-1"> </section>

   <section id="about">

      <div class="row section-head">
         <div class="col one-fourth">
            <h2>About Nautica Bar</h2>
            <p class="desc">Something about us</p>
         </div>

         <div class="col three-fourths">
             <p class="intro">Thanks to the collaboration with the architectural studio Kaliterna Arhitektura and signature designer Duje Kaliterne,
                 Nautica has received a completely different design. The interior is completely changed. From the old design, only old wooden beams remained that the designer decided to fit into the space.
                 Game of the old and the new is reflected in every detail: the retro sofas and "space" bar, French wallpapers and modern chandeliers,
                 and a crown of Modern Art - a unique, unseen ceiling which covers 17,000 glass vial mounted between old wooden beams.</p>
         </div>
      </div> <!-- end row section-head -->

      <br>
      <div class="row text-center intro">
          <p class="quoted">Nautica bar - favorite place of the locals - best recommendation for the guests</p>
      </div>

      <div class="row">
         <div class="col g-12">
            <h4>Design</h4>

            <p>The interior is dominated by antique details such as retro sofas and stylized French wallpaper.
                Successfully combined with modern elements such as industrial chandeliers, it gives the space a modern and
                fresh look. Emphasizeing the ceiling lighting, bars and retro counter are the only elements
                in the interior that can be seen while there is a large crowd. <br>LED lighting used can produce
                the entire spectrum of colors to enable visual change of atmosphere in the café.
            </p>
         </div>
      </div>

      <!-- Testimonials -->
      <div class="row">
         <div class="col full section-head">
            <h2>Words from our clients and visitors</h2>
            <p class="desc">What do guests at Nautica Bar say about it</p>
         </div>
      </div>

      <div class="cd-testimonials-wrapper cd-container">
          <ul class="cd-testimonials">

              <li>
                  <p>With the latest cocktails and nonstop dance music – ranging from techno to hip hop – this disco-style bar is an obligatory stop on Hvar’s night-crawl circuit. </p>
                  <div class="cd-author">
                      <img src="../images/user-01.png" alt="Author image">
                      <ul class="cd-author-info">
                          <li>Lonely Planet</li>
                          <li>Agency</li>
                      </ul>
                  </div>
              </li>

              <li>
                  <p>I'm so excited.... I'll be down there in 2 weeks. <br>Can't wait to see you all.</p>
                  <div class="cd-author">
                      <img src="../images/user-02.png" alt="Author image">
                      <ul class="cd-author-info">
                          <li>Janice</li>
                          <li>Visitor at Nautica Bar</li>
                      </ul>
                  </div>
              </li>

              <li>
                  <p>Omiljeno hvarsko okupljalište, bar Nautica, nedavno je dobilo novi izgled interijera s potpisom arhitektonskog studija Kaliterna Arhitektura.</p>
                  <div class="cd-author">
                      <img src="../images/user-03.png" alt="Author image">
                      <ul class="cd-author-info">
                          <li>buro247.hr</li>
                          <li>Website</li>
                      </ul>
                  </div>
              </li>

          </ul> <!-- cd-testimonials -->

          <span id="#0" class="cd-see-all">Show all</span>
      </div> <!-- cd-testimonials-wrapper -->

      <div class="cd-testimonials-all">
          <div class="cd-testimonials-all-wrapper">
              <ul>
                  <li class="cd-testimonials-item">
                      <p>Hey! My 3 friends and I went to Nautica Bar for the first time last night and we loved it!
                          It was without a doubt our favourite place to party in Hvar.</p>

                      <div class="cd-author">
                          <img src="../images/user-02.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Anna</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>I just wanted to thank you so much for organising things on Saturday night.
                          We all had a fantastic time.</p>

                      <div class="cd-author">
                          <img src="../images/user-03.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Dan</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Had lots of fun for my sis's bachelorette party. Got table service, and received good service.
                          Dance floor was lots of fun. Staff was responsive whenever there was a hot spot of activity and got things back to normal quick.</p>

                      <div class="cd-author">
                          <img src="../images/user-03.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Charlie</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Omiljeno hvarsko okupljalište, bar Nautica, nedavno je dobilo novi izgled interijera s potpisom arhitektonskog studija Kaliterna Arhitektura.</p>

                      <div class="cd-author">
                          <img src="../images/user-01.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>buro247.hr</li>
                              <li>Website</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>With the latest cocktails and nonstop dance music – ranging from techno to hip hop – this disco-style bar is an obligatory stop on Hvar’s night-crawl circuit.</p>

                      <div class="cd-author">
                          <img src="../images/user-01.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Lonely Planet</li>
                              <li>Agency</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>I'm so excited.... I'll be down there in 2 weeks. Can't wait to see you all.</p>

                      <div class="cd-author">
                          <img src="../images/user-02.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Janice</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Zato što je od stare Nautice ostalo je ono najvažnije, ono što mjesto čini posebnim: provjerena ekipa šarmantnih barmena i konobara, prostranost i (posebno važi za za treću smjenu!)
                          nevjerojatna ponuda od preko 100 koktela koji vam putovanje morem dobre zabave čine još uzbudljivijim.</p>

                      <div class="cd-author">
                          <img src="../images/user-01.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>otok-hvar.com</li>
                              <li>Web Blog</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Vrhunski koncert Zorice Kondže za Valentinovo, pogotovo u ovakvoj intimnoj atmosferi, neponovljivo!</p>

                      <div class="cd-author">
                          <img src="../images/user-02.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Danijela</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Noć za pamćenje u Nautica baru, obavezno se vraćamo iduće ljeto!!</p>

                      <div class="cd-author">
                          <img src="../images/user-03.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Antonio</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>

                  <li class="cd-testimonials-item">
                      <p>Provod za pet u Nautici, odlični kokteli po pristupačnoj cijeni i odlična zabava.</p>

                      <div class="cd-author">
                          <img src="../images/user-02.png" alt="Author image">
                          <ul class="cd-author-info">
                              <li>Melita</li>
                              <li>Visitor at Nautica Bar</li>
                          </ul>
                      </div> <!-- cd-author -->
                  </li>
              </ul>
          </div>	<!-- cd-testimonials-all-wrapper -->

          <span id="#0" class="close-btn" title="Zatvori">Zatvori</span>
      </div> <!-- cd-testimonials-all -->
   </section> <!-- About Section End-->

       <section id="hvar-images">
           <div class="row section-head">
               <div class="col full">
                   <h2>City of Hvar</h2>
                   <p class="desc">Explore the natural beauty of Hvar and visit us!</p>
               </div>
           </div>

           <div class="row">
               <div class="col g-3">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica1.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica1.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
               <div class="col g-3">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica2.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica2.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
               <div class="col g-3">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica3.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica3.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
               <div class="col g-3">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica4.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica4.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
           </div> <!-- end row -->
           <br>
           <div class="row">
               <div class="col g-4">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica5.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica5.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
               <div class="col g-4">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica6.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica6.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
               <div class="col g-4">
                   <a class="fancybox thumbnail" rel="location_hvar_nautica1" href="../images/hvar/location_hvar_nautica7.jpg">
                       <img class="img-responsive lazy" src="../images/hvar/location_hvar_nautica7.jpg" alt="Nauticaa@Hvar">
                   </a>
               </div>
           </div> <!-- end row -->
           <br><br>
       </section>  <!-- Hvar Images section End-->

   <section id="map">
      <p class="map-error">Turn on JavaScript in your internet browser to see the map, thank you for your understanding.</p>
   </section> <!-- Map Section End-->


   <section id="contact">
      <div class="row section-head">
         <div class="col full">
            <h2>Contact us</h2>
            <p class="desc">Contact us if you have any question or just want to say hello. <br>Fields marked with <span class="required">*</span> are mandatory!</p>
         </div>
      </div>

      <form name="contactForm" id="contactForm" method="post" action="">
      <div class="row">
        <div class="col g-4">
            <div>
                <input name="contactName" type="text" id="contactName" placeholder="Full name *" value="" required>
            </div>
        </div>
        <div class="col g-4">
            <div>
			    <input name="contactEmail" type="email" id="contactEmail" placeholder="E-mail *" value="" required>
            </div>
        </div>
        <div class="col g-4">
            <div>
			    <input name="contactSubject" type="text" id="contactSubject" placeholder="Subject" value="" required>
            </div>
        </div>
      </div>
      <div class="row">
         <div class="col g-12">
            <div>
                <textarea name="contactMessage" id="contactMessage" placeholder="Message *" required></textarea>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col g-2">
            <div class="text-center">
                <?php echo '<img src="'.$_SESSION['captcha']['image_src'].'" alt="CAPTCHA code" class="responsive captcha">'; ?>
            </div>
         </div>
         <div class="col g-10 text-right">
                <input name="contactCaptcha" type="text" id="contactCaptcha" placeholder="Verifikacijski kod *" value="" required>
         </div>
      </div>
      <div class="row mailFormVer">
         <div class="col g-12">
            <div>
                <input name="contactVer" type="text" id="contactVer" placeholder="fill" value="" required>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col g-12">
            <button class="submit contact">Send</button>
                <div class="text-center">
                    <span id="image-loader">
                        <img src="../images/loader.gif" alt="" />
                    </span>
                </div>
         </div>
      </div>
      <div class="row">
         <div class="col g-12">
            <!-- contact-warning -->
            <div id="message-warning"></div>
            <!-- contact-success -->
				<div id="message-success">
                    <i class="icon-ok"></i>Message has been sent successfully!<br>
				</div>
         </div>
      </div>
      </form><br><br>

       <div class="row">
           <div class="contact-fade">
             <div class="col g-4 contact-form-info">
                 <span class="glyphicon glyphicon-map-marker" title="Location"></span>
                 <p class="contact-info"><?php echo $contact_settings[0]['address']; ?></p>
             </div>
             <div class="col g-4 contact-form-info">
                 <span class="glyphicon glyphicon-earphone" title="Telephone number"></span>
                 <p class="contact-info"><?php echo $contact_settings[0]['telephone']; ?></p>
             </div>
             <div class="col g-4 contact-form-info">
                 <span class="glyphicon glyphicon-envelope" title="E-mail address"></span>
                 <p class="contact-info"><?php echo $contact_settings[0]['email']; ?></p>
             </div>
           </div>
       </div>
   </section> <!-- Contact Section End-->

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
                <a href="https://www.facebook.com/nautica.bar"><span class="facebook-social-slide"></span></a>
                <a href="https://www.youtube.com/watch?v=p37oJpF_t1g"><span class="youtube-social-slide"></span></a>
             </div>
         </div>
          <div class="clear"></div>
          <div class="partners-links">
              <a href="http://www.sail-croatia.com/" target="_blank"><img src="../images/partner_sail_croatia.png" alt="Sail Croatia aside" title="Sail Croatia" /></a>
              <a href="http://www.thefanatics.com/" target="_blank"><img src="../images/parter_fanatics.png" alt="Fanatics aside" title="Fanatics" /></a>
              <a href="http://www.tripadvisor.com/Hotel_Review-g303808-d1520421-Reviews-Villa_Skansi-Hvar_Hvar_Island_Split_Dalmatia_County_Dalmatia.html" target="_blank"><img src="../images/partner_villa_skansi.png" alt="Villa Skansi aside" title="Villa Skansi" /></a>
          </div><hr>
          <audio src="../music/<?php echo file_get_contents('../music/song_name.txt', true); ?>.mp3" preload="auto"></audio>
      </div>
   </footer> <!-- Footer End-->

    </div> <!-- /ip-main -->
</div> <!-- /container -->
   <script src="../js/jquery.viewportchecker.js"></script>
   <script src="../js/jquery-migrate-1.2.1.min.js"></script>
   <script src="../js/jquery.iframetracker.js"></script>
   <script src="../js/scrollspy.js"></script>
   <script src="../js/jquery.flexslider.js"></script>
   <script src="../js/jquery.reveal.js"></script>
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg3tldWfNzZrzRh-WI4Z8NKixK6cCIW5A&amp;sensor=false"></script>
   <script src="../js/gmaps.js"></script>
   <script src="../js/init.js"></script>
   <script src="../js/mail_en.js"></script>
   <script src="../js/masonry.pkgd.min.js"></script>
   <script src="../js/smoothscrolling.js"></script>
   <script src="../js/classie.js"></script>
   <script src="../js/pathLoader.js"></script>
   <script src="../js/preloader_main.js"></script>
   <script src="../js/jquery.lazyload.min.js"></script>
</body>
</html>