<?php
require_once "../../inc/database.php";

if($session->session_test() === true) {
    if (isset($_GET["logout"]) && empty($_GET["logout"])) {
        $users->logout();
    }

    //get settings data to fill the form
    $userid = (int)$_SESSION['id'];

    //get covers
    $eventCovers = array();
    $eventCovers = $events->getBarCovers();

    //get number of unique arrays
    if (!empty($eventCovers))
        $unique_arrays = count(array_unique($eventCovers, SORT_REGULAR));


    //returning argument if upload successful
    $eventGallery = false;

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        //images validation and path
        $extensions = array("jpeg", "jpg", "png", "gif", "bmp");
        $max_file_size = 8388608; //8MB
        $path = "../../covers/";    //dir name where to store cover
        $path_name = "covers/";  //dir name what to store to db

        //deal with the images
        $gallery = array();
        $image_counter = 0;
        foreach ($_FILES['gallery']['name'] as $f => $image_name) {
            if ($_FILES['gallery']['error'][$f] == 0) {
                if ($_FILES['gallery']['size'][$f] > $max_file_size) {
                    $errors[] = "" . $image_counter . ". slika je prevelika! Maksimalna veličina iznosi 8MB.";
                    continue;
                }

                //check image extension
                $file_ext = explode('.', $_FILES['gallery']['name'][$f]);
                $file_ext = end($file_ext);
                $file_ext = strtolower(end(explode('.', $_FILES['gallery']['name'][$f])));

                $image_name = "NauticaBar_coverPhoto_" . random_string(3) . "." . $file_ext;

                if (in_array($file_ext, $extensions) === false) {
                    $errors[] = "" . $image_counter . ". slika nije dozvoljene ekstenzije!";
                    continue;
                } else {

                    if (move_uploaded_file($_FILES["gallery"]["tmp_name"][$f], $path . $image_name) && file_exists($path)) {
                        //store image info to array and send to function
                        $image_info = array($image_name, $path_name, $_FILES['gallery']['size'][$f]);

                        //send to function
                        $eventGallery = $events->fillBarCovers($image_info);
                        $image_counter++;
                    }
                }
            } else {
                //
            }
        }

        //final check
        if ($eventGallery === false) {
            $errors[] = "Slike nisu dodane. :(";
        } else {
            $announces[] = "Upload završen. Uspješno je dodano slika (" . $image_counter . ") kao naslovnica.";
            //refresh covers after submitting the form
            $eventCovers = $events->getBarCovers();
            if (!empty($eventCovers))
                $unique_arrays = count(array_unique($eventCovers, SORT_REGULAR));
        }
    }


    //delete covers
    if($_SERVER["REQUEST_METHOD"] == 'GET' && !empty($_GET['image_id']))
    {
        //get image ID
        $image_id = $_GET['image_id'];

        //is ID empty
        if(empty($image_id)){
            header("Location: ./");
            die();
        }

        //does event really exists in DB
        $db_check = $events->imageCover_exists($image_id);
        if($db_check === false){
            header("Location: ./");
            die();
        }

        //get image data
        $imageData = $events->getCoverImage($image_id);

        //call main function
        $img_delete = $events->coverDelete($image_id, $imageData);
        if($img_delete === false)
        {
            header("Location: ./"); //not deleted
        }
        else{
            header("Location: ./");
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
        <link rel="shortcut icon" href="../../favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="../css/layout.css">
        <link rel="stylesheet" href="../css/animsition.min.css">
        <link rel="stylesheet" href="../css/jquery.fancybox.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../js/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="../js/jquery.mousewheel-3.0.6.min.js"></script>
        <script src="../js/jquery.fancybox.min.js"></script>
        <script src="../js/animsition.js"></script>
        <script src="../js/modernizr.js"></script>
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
                    <a href="../../"><img alt="" src="../images/logo.png" title="Nautica Bar"></a>
                </div>
                <nav id="nav-wrap">
                    <a class="mobile-btn" href="#nav-wrap" title="Prikaži navigaciju">Prikaži navigaciju</a>
                    <a class="mobile-btn" href="#" title="Sakrij navigaciju">Sakrij navigaciju</a>
                    <ul id="nav" class="nav">
                        <li><a href="../">Eventi</a></li>
                        <li class="active">
                            <a href="./">Slike naslovnice</a>
                        </li>
                        <li><a href="../settings">Korisničke postavke</a></li>
                        <li><a href="../contact_settings">Kontakt postavke</a></li>
                        <li><a href="index.php?logout">Odjavi se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header> <!-- Header End -->

    <section id="settings">
        <div class="row">
            <form action="" method="POST" name="add" id="add" enctype="multipart/form-data" role="form">
                <div class="row">
                    <div class="col g-6">
                        <div class="form-group">
                            <label for="cover">Dodaj naslovne slike</label>
                            <input type="file" class="filestyle" id="gallery" name="gallery[]" multiple="multiple"
                                   accept="image/*">
                        </div>
                    </div>
                    <div class="col g-6">
                        <div id="errors">
                            <?php
                            if (empty($errors) === false) {
                                foreach ($errors as $error) {
                                    echo $error . "<span class='dismiss' id='error_dismiss' title='Zatvori'>&times;</span>";
                                }
                            }
                            ?>
                        </div>
                        <div id="announces">
                            <?php
                            if (empty($announces) === false) {
                                foreach ($announces as $announce) {
                                    echo $announce . "<span class='dismiss' id='announce_dismiss' title='Zatvori'>&times;</span>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div clas="col g-12">
                        <div class="text-center">
                            <button type="submit" class="submit"><span class="glyphicon glyphicon-upload"></span> Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section> <!-- end settings section -->

    <?php
        if (!empty($eventCovers)) {
            echo "<section id='event_content'>
                        <div class='container'>
                            <div class='row'>";

            for ($i = 0; $i < $unique_arrays; $i++)
            {
                echo "<div class='col g-4 thumb'>
                                <a class='fancybox thumbnail' rel='".$i."' href='../../".$eventCovers[$i]['file_location'].$eventCovers[$i]['file_name']."'>
                                    <img class='img-responsive' src='../../".$eventCovers[$i]['file_location'].$eventCovers[$i]['file_name']."' alt='".imageAlt($eventCovers[$i]['file_name'])."' />
                                </a>
                                <span class='tiny'>
                                    <a href='javascript:confirm_delete(".$eventCovers[$i]['id'].");'><span class='glyphicon glyphicon-trash'></span> Obriši sliku</a>
                                </span>
                              </div>";
            }

            echo "  </div> <!-- end row -->
                            <div class='row'>
                                <a href='./'><button class='form'><span class='glyphicon glyphicon-home'></span> Početna</button></a>
                            </div>
                          </div> <!-- end container -->";

            echo " </section>  <!-- end section#gallery -->";
        }
    ?>

    <script type="text/javascript" src="../js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-filestyle.min.js"></script>
    <script src="../js/init.js"></script>
    <script>
        $(":file").filestyle({buttonBefore: true, input: false, buttonText: "Odaberi slike"});

        $(document).ready(function() {
            $(".fancybox").fancybox();
        });

        function confirm_delete(image_id) {
            if (confirm('Stvarno želiš obrisat sliku?')) {
                self.location.href = 'index.php?image_id=' + image_id;
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
	$session->session_false("../login.php");
}
?>