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

    //get user data
    $userid = (int)$_SESSION['id'];
    $current_user = $users->user_get_data($userid);

    //get event data
    $eventData = $events->getEvent($event_id);

    //returning argument if upload successful
    $eventGallery = false;

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        //clean name and use for images name
        $event_name = safe_name($eventData['name']);
        $event_id = (int)$eventData['id'];

        //images validation and path
        $extensions = array("jpeg", "jpg", "png", "gif", "bmp");
        $max_file_size = 8388608; //8MB
        $path = "../events_gallery/".$event_name."/";	//dir name where to store cover
        $path_name = "events_gallery/".$event_name."/";  //dir name what to store to db

        //deal with the images
        $gallery = array();
        $image_counter = 0;
        foreach ($_FILES['gallery']['name'] as $f => $image_name)
        {
            if ($_FILES['gallery']['error'][$f] == 0)
            {
                if ($_FILES['gallery']['size'][$f] > $max_file_size)
                {
                    $errors[] = "".$image_counter.". slika je prevelika! Maksimalna veličina iznosi 8MB.";
                    continue;
                }

                //check image extension
                $file_ext = explode('.', $_FILES['gallery']['name'][$f]);
                $file_ext = end($file_ext);
                $file_ext = strtolower(end(explode('.', $_FILES['gallery']['name'][$f])));

                $image_name = $event_name."_".random_string(10).".".$file_ext;

                if(in_array($file_ext, $extensions) === false)
                {
                    $errors[] = "".$image_counter.". slika nije dozvoljene ekstenzije!";
                    continue;
                }
                else
                {
                    //check for image directory
                    if (!file_exists($path))
                    {
                        mkdir($path, 0777);
                    }

                    if(move_uploaded_file($_FILES["gallery"]["tmp_name"][$f], $path.$image_name) && file_exists($path))
                    {
                        //store image info to array and send to function
                        $image_info = array($image_name, $path_name, $_FILES['gallery']['size'][$f]);

                        //send to function
                        $eventGallery = $events->fillEventGallery($event_id, $image_info);
                        $image_counter++;
                    }
                }
            }
            else
            {
                //
            }
        }

        //final check
        if($eventGallery === false)
        {
            $errors[] = "Slike nisu dodane. :(";
        }
        else
        {
            $announces[] = "Upload završen. Uspješno je dodano slika (".$image_counter.") u galeriju eventa.";
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
        <form action="" method="POST" name="add" id="add" enctype="multipart/form-data" role="form">
            <div class="row">
                <div class="col g-6">
                    <div class="form-group">
                        <label for="cover">Dodaj slike u galeriju:</label>
                        <input type="file" class="filestyle" id="gallery" name="gallery[]" multiple="multiple" accept="image/*">
                    </div>
                </div>
                <div class="col g-6">
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
                </div>
            </div>
            <div class="row">
                <div clas="col g-12">
                    <div class="text-center">
                        <button type="submit" class="submit"><span class="glyphicon glyphicon-upload"></span> Upload</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <a href="gallery.php?id=<?php echo $eventData['id']; ?>"><button class="form"><span class="glyphicon glyphicon-chevron-left"></span> Povratak na galeriju</button></a>
        </div>
    </section> <!-- event_content end -->

    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
    <script src="js/init.js"></script>
    <script>
        $(":file").filestyle({buttonBefore: true, input: false, buttonText: "Odaberi slike"});
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