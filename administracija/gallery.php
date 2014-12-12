<?php
require_once "../inc/database.php";
if($session->session_test() === true)
{
	if(isset($_GET["logout"]) && empty($_GET["logout"]))
	{
		$users->logout();
	}

    //get event ID
    $event_id = $_GET["id"];

    //is ID empty
    if(empty($event_id)){
        header("Location: ./");
        die();
    }

    //does event really exists in DB
    $db_check = $events->event_exists($event_id);
    if($db_check === false){
        header("Location: ./");
        die();
    }

    //get user data
    $userid = (int)$_SESSION['id'];

    //get all data for current event
    $eventData = $events->getEvent($event_id);

    //get images from event gallery
    $eventGallery = array();
    $eventGallery = $events->getEventGallery($event_id);

    //get number of unique arrays
    if(!empty($eventGallery))
        $unique_arrays = count(array_unique($eventGallery, SORT_REGULAR));


    //delete images in gallery
    if($_SERVER["REQUEST_METHOD"] == 'GET' && !empty($_GET['image_id']))
    {
        //get image ID
        $image_id = $_GET['image_id'];

        //is ID empty
        if(empty($image_id)){
            header("Location: gallery.php?id=".$event_id."");
            die();
        }

        //does event really exists in DB
        $db_check = $events->imageGallery_exists($image_id);
        if($db_check === false){
            header("Location: gallery.php?id=".$event_id."");
            die();
        }

        //get image data
        $imageData = $events->getGalleryImage($image_id);

        //call main function
        $img_delete = $events->imageDelete($image_id, $imageData);
        if($img_delete === false)
        {
            header("Location: gallery.php?id=".$event_id.""); //not deleted
        }
        else{
            header("Location: gallery.php?id=".$event_id."");
        }
    }

?>
<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="ie ie7" lang="hr"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="hr"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html lang="hr"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nautica Bar ~ Administracija</title>
    <meta name="description" content="Nautica Bar, Club, Hvar, Croatia">
    <meta name="author" content="Nautica Bar">
    <link rel="shortcut icon" href="../favicon.png" >

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/animsition.min.css">
    <link rel="stylesheet" href="css/jquery.fancybox.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="js/jquery.mousewheel-3.0.6.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/animsition.js"></script>
    <script src="js/modernizr.js"></script>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#nav-wrap">
<div class="animsition">
    <header class="mobile">
        <div class="row">
            <div class="col full">
                <div class="logo">
                    <a href="../"><img alt="" src="images/logo.png" title="Nautica Bar"></a>
                </div>
                <nav id="nav-wrap">
                    <a class="mobile-btn" href="#nav-wrap" title="Prikaži navigaciju">Prikaži navigaciju</a>
                    <a class="mobile-btn" href="#" title="Sakrij navigaciju">Sakrij navigaciju</a>
                    <ul id="nav" class="nav">
                        <li class="active">
                            <a href="./">Eventi</a>
                        </li>
                        <li><a href="covers">Slike naslovnice</a></li>
                        <li><a href="settings">Korisničke postavke</a></li>
                        <li><a href="contact_settings">Kontakt postavke</a></li>
                        <li><a href="index.php?logout">Odjavi se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header> <!-- Header End -->

    <section id="event_content">
    <?php
        echo "<div class='row'>
                 <div class='col g-8'>
                    <h2>".$eventData['name']."</h2>
                    <p class='notice'>Galerija slika</p>
                 </div>
                 <div class='col g-4 text-right'>
                    <a href='add.php?id=".$eventData['id']."' style='display: inline-block;'><button class='submit'><span class='glyphicon glyphicon-plus'></span> Dodaj slike</button></a>
                 </div>
              </div>";

            //check if there are any images in event gallery
        if(empty($eventGallery))
        {
            echo "<div class='text-center'>
                    <p>Trenutno nije dodana niti jedna slika.</p>
                  </div>
                  <div class='row'>
                    <a href='./'><button class='form'><span class='glyphicon glyphicon-home'></span> Početna</button></a>
                  </div>";
        }
        else{

            echo "<div class='container'>
                    <div class='row'>";

            for($i = 0; $i < $unique_arrays; $i++)
            {
                echo "<div class='col g-4 thumb'>
                        <a class='fancybox thumbnail' rel='".$eventData['name']."' href='../".$eventGallery[$i]['file_location'].$eventGallery[$i]['file_name']."'>
                            <img class='img-responsive lazy' src='../".$eventGallery[$i]['file_location'].$eventGallery[$i]['file_name']."' alt='".imageAlt($eventGallery[$i]['file_name'])."'>
                        </a>
                        <span class='tiny'>
                            <a href='javascript:confirm_delete(".$event_id.", ".$eventGallery[$i]['id'].");'><span class='glyphicon glyphicon-trash'></span> Obriši sliku</a>
                        </span>
                      </div>";
            }

            echo "  </div> <!-- end row -->
                    <div class='row'>
                        <a href='./'><button class='form'><span class='glyphicon glyphicon-home'></span> Početna</button></a>
                    </div>
                  </div> <!-- end container -->";
        }
    ?>
    </section> <!-- event_content end -->

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
    <script src="js/init.js"></script>
    <script>
        $(document).ready(function() {
            $(".fancybox").fancybox();

            $("img.lazy").lazyload({
                effect : "fadeIn"
            });
        });

        function confirm_delete(event_id, image_id) {
            if (confirm('Stvarno želiš obrisat sliku?')) {
                self.location.href = 'gallery.php?id=' + event_id + '&image_id=' + image_id;
            }
        }
    </script>
</div>
</body>
</html>
<?php
}
else
{
	$session->session_false("login.php");
}
?>