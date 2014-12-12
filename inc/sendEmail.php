<?php
session_start();

// Replace this with your own email address
$siteOwnersEmail = '';


if($_POST) {
    $error = array();
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));
    $contact_ver = trim(stripslashes($_POST['contactVer']));
    $contact_captcha = trim(stripslashes($_POST['contactCaptcha']));

    //check if real captcha is good
    if(strtolower($_SESSION['captcha']['code']) != strtolower($contact_captcha)) {
        $error['captcha'] = "Verifikacijski kod je netočan. Refreshajte stranicu.";
    }
    //check if bots filled fake captcha field
    if (strlen($contact_ver) > 0) {
        $error['ver'] = ".";
    }
   	// Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Ime je obavezno polje.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "E-mail adresa nije ispravna.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Poruka je obavezna. Minimalno 15 znakova!";
	}
   // Subject
	if ($subject == '') { $subject = "Nautica Bar : Web E-mail"; }


   // Set Message
   $message = "";
   $message .= "Email from: " . $name . "<br />";
   $message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";


   if (!$error) {

      ini_set("sendmail_from", $siteOwnersEmail); // for windows server
      $mail = mail($siteOwnersEmail, $subject, $message, $headers);

	  if ($mail) { echo "E-mail uspješno poslan."; }
      else { echo "Dogodila se greška. Molimo pokušajte kasnije."; }
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
        $response .= (isset($error['ver'])) ? $error['ver'] . "<br />" : null;
        $response .= (isset($error['captcha'])) ? $error['captcha'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>