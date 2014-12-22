<?php
/*
*	admin area login
*/
require_once "../inc/database.php";
	
	//if user already authenticated -> redirect
	$session->session_true("index.php");

    //bruteforce protection
    $IP_denyList = $users->getAllFailedLogins(10);
    if (in_array($_SERVER['REMOTE_ADDR'], $IP_denyList)) {
        header("Location: ./");
        exit();
    }


if($_SERVER["REQUEST_METHOD"] == 'POST'){
	usleep(1000000);
	if(empty($_POST["username"]) || empty($_POST["password"])){
		$errors[] = "Sva polja su obavezna!";
	} 
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$login = $users->login($username, $password, $ip);

		if($login === false){
            //set failed login for IP ban
            $failedLogin = $users->getFailedLogin($ip);

            if($failedLogin === false){
                $users->addFailedLogin($ip);
            }
            else{
                $users->updateFailedLogin($ip, $failedLogin);
            }

			$errors[] = "Pogrešno korisničko ime ili lozinka!";
		} 
		else{
			$_SESSION[$session_id] = $login;
			$_SESSION['username'] = htmlspecialchars($username);
			header("Location: ");
			exit();
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
	<title>Nautica Bar ~ Administracija</title>
	<meta name="description" content="Nautica Bar, Club, Hvar, Croatia">
	<meta name="author" content="Nautica Bar">
    <link rel="shortcut icon" href="../favicon.png" >

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/animsition.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="js/animsition.js"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
	<body>
    <div class="animsition">
		<section id="login">
		    <div class="login-inner">
		    	<div class="login-logo"></div>

				<form action="" method="POST" name="login" id="login" role="form">
					<div class="form-group">
						<input type="text" id="username" name="username" placeholder="Korisničko ime" required>
					</div>
					<div class="form-group">
						<input type="password" id="password" name="password" placeholder="Lozinka" required>
					</div>

					<span id="image-loader">
                       	<img src="images/loader.gif" alt="" /> Loading...
                    </span>
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
		            <button class="submit" id="submit">Login</button> 
				</form>
			</div>
		</section>
        <script>
			jQuery(document).ready(function(){
				setTimeout(function(){
					$("#submit").click(function(){
					   if($.trim($('form#login input').val()) !== ''){
					      $("#image-loader").css("display","block");
					   }					
					});
				}, 2000);

                    //dismissable alert
                $(document).on('click', '#error_dismiss', function () {
                    $(this).parent().fadeOut();
                });

                $(".animsition").animsition({

                    inClass               :   'fade-in',
                    outClass              :   'fade-out',
                    inDuration            :    1000,
                    outDuration           :    800,
                    linkElement           :   '.animsition-link',
                    // e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
                    loading               :    true,
                    loadingParentElement  :   'body', //animsition wrapper element
                    loadingClass          :   'animsition-loading',
                    unSupportCss          : [ 'animation-duration',
                        '-webkit-animation-duration',
                        '-o-animation-duration'
                    ],
                    //"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
                    //The default setting is to disable the "animsition" in a browser that does not support "animation-duration".

                    overlay               :   false,

                    overlayClass          :   'animsition-overlay-slide',
                    overlayParentElement  :   'body'
                });
			});
		</script>
    </div>
	</body>
</html>