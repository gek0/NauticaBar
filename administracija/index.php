<?php
require_once "../inc/database.php";
if($session->session_test() === true)
{
	if(isset($_GET["logout"]) && empty($_GET["logout"]))
	{
		$users->logout();
	}

        //get user data
    $userid = (int)$_SESSION['id'];
    $current_user = $users->user_get_data($userid);

        //get events data
    $event_data = $events->event_count();
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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

    <section id="user_content">
        <div class="row">
            <div class="col g-7">
                <h3>Dobrodošao <?php echo $current_user[0]['username']; ?></h3>
                <div id="clock"></div>
            </div>
            <div class="col g-3">
                Aktivni eventi: <span class="count-notif"><?php echo $event_data[1][0]; ?></span><br>
                Ukupno: <span class="count-notif"><?php echo $event_data[0][0]; ?></span>
            </div>
            <div class="col g-2">
                <a href="new.php"><button class="form"><span class="glyphicon glyphicon-plus"></span> Novi event</button></a>
            </div>
        </div>
    </section> <!-- user_content end -->


    <?php
            //get events data
        $eventsData = array();
        $eventsData = $events->getAllEventsAdmin();

            //get number of unique arrays (events)
        $unique_arrays = count(array_unique($eventsData, SORT_REGULAR));

        for($i = 0; $i < $unique_arrays; $i++)
        {
            if($i % 2 == 0) echo '<div class="row">';

                echo '<div class="col g-6">
                        <div id="effect-6" class="effects clearfix">
                            <div class="img">
                                <img src="../'.$eventsData[$i]['file_location'].$eventsData[$i]['file_name'].'" alt="">
                                <div class="overlay">
                                    <a href="edit.php?id='.$eventsData[$i]['id'].'" class="expand" title="Izmjeni event"><span class="glyphicon glyphicon-cog"></span></a>
                                    <a class="close-overlay hidden">x</a>
                                </div>
                            </div>
                        </div>
                       <h3 lcass="">
                            <a href="edit.php?id='.$eventsData[$i]['id'].'">'.$eventsData[$i]['name'].'</a>
                       </h3>
                       <p><a href="gallery.php?id='.$eventsData[$i]['id'].'"><span class=" glyphicon glyphicon-th"></span> Galerija slika</a></p>
                      </div>';

            if($i % 2 == 1) echo '</div><br> <!-- end row -->';
        }
            //if odd number of rows
        if($unique_arrays % 2 == 1) echo '</div> <!-- end row -->';
    ?>

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/init.js"></script>
    <script src="js/clock.js"></script>
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