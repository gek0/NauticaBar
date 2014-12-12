<?php
require_once "../inc/database.php";
if($session->session_test() === true)
{
	if(isset($_GET["logout"]) && empty($_GET["logout"]))
	{
		$users->logout();
	}

    //get event ID for gallery
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

    //get event data
    $eventData = $events->getEvent($event_id);

    //for finding dir of gallery and cover
    $event_name = safe_name($eventData['name']);
    $event_cover = $eventData['file_name'];

    //call main function
    $delete = $events->delete($event_id, $event_name, $event_cover);
    if($delete === false)
    {
        header("Location: ./");
    }
    else
    {
        header("Location: ./");
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

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/init.js"></script>
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