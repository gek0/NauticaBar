<?php
require_once "../inc/database.php";
if($session->session_test() === true)
{
	if(isset($_GET["logout"]) && empty($_GET["logout"]))
	{
		$users->logout();
	}

        //get event ID for edit
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

        //get all events by name
    $eventNames = array();
    $eventNames[] = $events->getEventsNames();

        //get all data for current event
    $eventData = $events->getEvent($event_id);

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        $valid_action_status = array("yes", "no");

        if(empty($_POST["name"]))
        {
            $errors[] = "Ime eventa je obavezno!";
        }
        else if(!in_array($_POST['active'], $valid_action_status))
        {
            $errors[] = "Status eventa nije važeći!";
        }
        else if(in_array($_POST['name'], $eventNames))
        {
            $errors[] = "Event s tim imenom već postoji!";
        }
        else {
            //save to variables
            $event_name = htmlspecialchars($_POST['name'], ENT_NOQUOTES, "UTF-8");
            $event_description = $_POST['description'];
            $event_description_en = $_POST['description_en'];
            $event_active = $_POST['active'];

            //cover validation and path
            $extensions = array("jpeg", "jpg", "png", "gif");
            $max_file_size = 8388608; //8MB
            $path = "../events/";    //dir name where to store cover
            $path_name = "events/";  //dir name what to store to db

                //check if new cover is uploaded
            if ($_FILES['cover']['size'] > 0)
            {
                //deal with the cover
                $cover = array();
                if ($_FILES['cover']['error'] == 0) {
                    //check image extension
                    $file_ext = explode('.', $_FILES['cover']['name']);
                    $file_ext = end($file_ext);
                    $file_ext = strtolower(end(explode('.', $_FILES['cover']['name'])));


                    if ($_FILES['cover']['size'] > $max_file_size)
                    {
                        $errors[] = "Slika je prevelika! Maksimalna veličina iznosi 8MB.";
                    } else if (in_array($file_ext, $extensions) === false)
                    {
                        $errors[] = "Ekstenzija slike nije dozvoljena!";
                    }
                    else
                    {
                        $image_prefix = safe_name($event_name); //clean and prepare event name for image name
                        $image_name = $image_prefix . "." . $file_ext;

                        //upload image to disk
                        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $path . $image_name))
                        {
                            //store image info to array and send to function
                            $image_info = array($image_name, $path_name, $_FILES['cover']['size']);

                            //delete old cover
                            unlink("../".$eventData['file_location'].$eventData['file_name']);

                            //get all images from gallery
                            $galleryImages = $events->getGalleryImageNames($event_id);

                            //call main functions
                            $event = $events->edit($eventData['id'], $event_name, $event_description, $event_description_en, $event_active, $image_info);
                            $galleryEdit = $events->editGallery($eventData['id'], $event_name, $eventData['name'], $galleryImages);

                            //final check
                            if ($event === false)
                            {
                                $errors[] = "Event nije izmjenjen.";
                            }
                            else
                            {
                                if($galleryEdit === false)
                                {
                                    $errors[] = "Event nije izmjenjen.";
                                }
                                else
                                {
                                    $announces[] = "Event je uspješno izmjenjen!";
                                }
                            }
                        }
                    }
                }
            }
            else    //old cover used
            {
                //rename cover name with new name if changed
                $image_prefix = safe_name($event_name); //clean and prepare event name for image name
                $image_name = $image_prefix.".".substr($eventData['file_name'], -3);

                //rename file on disk
                $old = "../".$eventData['file_location'].$eventData['file_name'];
                $new = "../".$eventData['file_location'].$image_name;
                rename($old, $new);

                //store image info to array and send to function
                $image_info = array($image_name, $eventData['file_location'], $eventData['file_size']);

                //get all images from gallery
                $galleryImages = $events->getGalleryImageNames($event_id);

                //call main functions
                $event = $events->edit($eventData['id'], $event_name, $event_description, $event_description_en, $event_active, $image_info);
                $galleryEdit = $events->editGallery($eventData['id'], $event_name, $eventData['name'], $galleryImages);

                //final check
                if ($event === false)
                {
                    $errors[] = "Event nije izmjenjen.";
                }
                else
                {
                    if($galleryEdit === false)
                    {
                        $errors[] = "Event nije izmjenjen.";
                    }
                    else
                    {
                        $announces[] = "Event je uspješno izmjenjen!";
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
                        <input type="text" id="name" name="name" placeholder="Ime eventa" value="<?php echo $eventData['name']; ?>" required>
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
                                        <textarea name="description" id="description" placeholder="Kratak opis eventa..."><?php echo $eventData['description']; ?></textarea>
                                    </div>
                                </div>
                                <div class="simpleTabsContent">
                                    <div class="form-group">
                                        <label for="description_en">Event description:</label>
                                        <textarea name="description_en" id="description_en" placeholder="Short event description..."><?php echo $eventData['description_en']; ?></textarea>
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
                                    <input type="radio" name="active" id="active" value="yes" <?php echo ($eventData['active'] == 'yes' ? 'checked="checked"' : '') ?>> Da
                                </label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="active" id="active" value="no" <?php echo ($eventData['active'] == 'no' ? 'checked="checked"' : '') ?>> Ne
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
                        <button class="form" id="submit" type="submit"><span class="glyphicon glyphicon-pencil"></span> Izmjeni event</button>
                        <button class="form" id="reset" type="reset"><span class="glyphicon glyphicon-remove"></span> Resetiraj</button>
                    </div>
                </form>
            </div>
            <a href="./"><button class="form"><span class="glyphicon glyphicon-home"></span> Početna</button></a>

            <div class="inline">
                <a href="javascript:confirm_delete(<?php echo $eventData['id']; ?>);"><button class="form del"><span class="glyphicon glyphicon-trash"></span> Obriši event i sve slike</button></a>
            </div>

        </div>
    </section> <!-- end event_content -->

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
    <script type="text/javascript" src="js/init.js"></script>
    <script type="text/javascript" src="js/simpletabs_1.3.packed.js"></script>
    <script>
        $(":file").filestyle({buttonBefore: true, input: false, buttonText: "Odaberi sliku"});

        function confirm_delete(id) {
            if (confirm('Stvarno želiš obrisat sve?')) {
                self.location.href = 'delete.php?id=' + id;
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