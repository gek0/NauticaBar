<?php
require_once "../../inc/database.php";
if($session->session_test() === true)
{
	if(isset($_GET["logout"]) && empty($_GET["logout"]))
	{
		$users->logout();
	}

        //get user data to fill the form
    $userid = (int)$_SESSION['id'];
    $current_user = $users->user_get_data($userid);

        //change user credentials
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //save POST data to vars
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_again = $_POST['password_again'];

            //validation and function calls
        if($password != $password_again){
            $errors[] = "Unjete lozinke nisu iste!";
        }
        else if($username == '' || $email == ''){
            $errors[] = "Korisničko ime i email adresa ne mogu biti prazni!";
        }
        else if($password == '' && $password_again == ''){
            $change = $users->user_small_change($userid, $username, $email);

            //check result
            if($change === false){
                $errors[] = "Podaci nisu mogli biti promjenjeni!";
            }
            else{
                $announces[] = "Podaci su uspješno promjenjeni!";
                $current_user = $users->user_get_data($userid); //refresh data
            }
        }
        else{
            $change = $users->user_full_change($userid, $username, $email, $password);

            //check result
            if($change === false){
                $errors[] = "Podaci nisu mogli biti promjenjeni!";
            }
            else{
                $announces[] = "Podaci su uspješno promjenjeni!";
                $current_user = $users->user_get_data($userid); //refresh data
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
    <link rel="shortcut icon" href="../../favicon.png" >

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/animsition.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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
                        <li><a href="../covers">Slike naslovnice</a></li>
                        <li class="active">
                            <a href="./">Korisničke postavke</a>
                        </li>
                        <li><a href="../contact_settings">Kontakt postavke</a></li>
                        <li><a href="index.php?logout">Odjavi se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header> <!-- Header End -->

    <section id="settings">
        <div class="row">
            <div class="col full">
                <form action="" method="POST" name="settings" id="settings" role="form">
                    <div class="form-group">
                        <label for="username">Korisničko ime:</label>
                        <input type="text" id="username" name="username" placeholder="Korisničko ime" value="<?php print_r($current_user[0]['username']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email adresa:</label>
                        <input type="email" id="email" name="email" placeholder="E-mail" value="<?php print_r($current_user[0]['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Nova lozinka:</label>
                        <input type="password" id="password" name="password" placeholder="Nova lozinka">
                    </div>
                    <div class="form-group">
                        <label for="password_again">Ponovi lozinku:</label>
                        <input type="password" id="password_again" name="password_again" placeholder="Ponovi lozinku">
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
                    <button class="submit" id="submit"><span class="glyphicon glyphicon-ok"></span> Promjeni</button>
                </form>
            </div>
        </div>
    </section> <!-- end settings section -->

    <script type="text/javascript" src="../js/jquery-migrate-1.2.1.min.js"></script>
    <script src="../js/init.js"></script>
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