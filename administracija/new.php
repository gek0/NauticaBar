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

        //get events data
    $eventNames = array();
    $eventNames[] = $events->getEventsNames();

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        $valid_action_status = array("yes", "no");

        if(empty($_POST["name"]) || $_FILES['cover']['size'] == 0)
        {
            $errors[] = "Ime i slike eventa su obavezna polja!";
        }
        else if(!in_array($_POST['active'], $valid_action_status))
        {
            $errors[] = "Status eventa nije važeći!";
        }
        else if(in_array($_POST['name'], $eventNames))
        {
            $errors[] = "Event s tim imenom već postoji!";
        }
        else
        {
                //save to variables
            $event_name = htmlspecialchars($_POST['name'], ENT_NOQUOTES, "UTF-8");
            $event_description = $_POST['description'];
            $event_description_en = $_POST['description_en'];
            $event_active = $_POST['active'];

                //cover validation and path
            $extensions = array("jpeg", "jpg", "png", "gif", "bmp");
            $max_file_size = 8388608; //8MB
            $path = "../events/";	//dir name where to store cover
            $path_name = "events/";  //dir name what to store to db

                //deal with the cover
            $cover = array();
            if($_FILES['cover']['error'] == 0)
            {
                    //check image extension
                $file_ext = explode('.', $_FILES['cover']['name']);
                $file_ext = end($file_ext);
                $file_ext = strtolower(end(explode('.', $_FILES['cover']['name'])));

                if ($_FILES['cover']['size'] > $max_file_size)
                {
                    $errors[] = "Slika je prevelika! Maksimalna veličina iznosi 8MB.";
                }
                else if(in_array($file_ext, $extensions) === false)
                {
                    $errors[] = "Ekstenzija slike nije dozvoljena!";
                }
                else
                {
                    $image_prefix = safe_name($event_name); //clean and prepare event name for image name
                    $image_name = $image_prefix.".".$file_ext;

                        //upload image to disk
                    if(move_uploaded_file($_FILES["cover"]["tmp_name"], $path.$image_name))
                    {
                            //store image info to array and send to function
                        $image_info = array($image_name, $path_name, $_FILES['cover']['size']);

                            //call main function
                        $event = $events->add($event_name, $event_description, $event_description_en, $event_active, $image_info);

                            //final check
                        if($event === false)
                        {
                            $errors[] = "Event nije dodan.";
                        }
                        else{
                            $announces[] = "Event je uspješno dodan!";
                        }
                    }
                }
            }
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

    <section id="event_content">
        <div class="row">
            <div class="col full">
                <form action="" method="POST" name="new" id="new" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <label for="name">Ime eventa:</label>
                        <input type="text" id="name" name="name" placeholder="Ime eventa" required>
                    </div>

                    <div class="row">
                        <div class="col g-8">
                            <div class="simpleTabs">
                                <ul class="simpleTabsNavigation">
                                    <li><a href="#">Opis na hrvatskom</a></li>
                                    <li><a href="#">Opis na engleskom</a></li>
                                </ul>
                                <div class="simpleTabsContent">
                                    <div class="form-group">
                                        <label for="description">Opis eventa:</label>
                                        <textarea name="description" id="description" placeholder="Kratak opis eventa..."></textarea>
                                    </div>
                                </div>
                                <div class="simpleTabsContent">
                                    <div class="form-group">
                                        <label for="description_en">Event description:</label>
                                        <textarea name="description_en" id="description_en" placeholder="Short event description..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col g-4">
                            <div class="form-group">
                                <label for="cover">Slika eventa:</label>
                                <input type="file" class="filestyle" id="cover" name="cover" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Event aktivan:</label>
                                <label class="radio-inline">
                                    <input type="radio" name="active" id="active" value="yes" checked="checked"> Da
                                </label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="active" id="active" value="no"> Ne
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="errors">
                        <?php
                        if(empty($errors) === false)
                        {
                            foreach($errors as $error)
                            {
                                echo $error."<span class='dismiss' id='error_dismiss' title='Zatvori'>&times;</span>";
                            }
                        }
                        ?>
                    </div>
                    <div id="announces">
                        <?php
                        if(empty($announces) === false)
                        {
                            foreach($announces as $announce)
                            {
                                echo $announce."<span class='dismiss' id='announce_dismiss' title='Zatvori'>&times;</span>";
                            }
                        }
                        ?>
                    </div>

                    <div class="text-center">
                        <button class="form" id="submit" type="submit"><span class="glyphicon glyphicon-ok"></span> Dodaj event</button>
                        <button class="form" id="reset" type="reset"><span class="glyphicon glyphicon-remove"></span> Resetiraj</button>
                    </div>
                </form>
            </div>
            <a href="./"><button class="form"><span class="glyphicon glyphicon-home"></span> Početna</button></a>

        </div>
    </section> <!-- end event_content -->

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
    <script type="text/javascript" src="js/init.js"></script>
    <script type="text/javascript" src="js/simpletabs_1.3.packed.js"></script>
    <script>
        $(":file").filestyle({buttonBefore: true, input: false, buttonText: "Odaberi sliku"});
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