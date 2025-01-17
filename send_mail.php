<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


function email_form_submission() {

    if(is_spam()){
        die("recognized spam");
    }

    $mail = new PHPMailer(true);

    setup_authentication($mail);
    setup_addresses($mail);
    setup_message($mail);

    if (!$mail->send()) {
        header("Location: error.html");
    } 
    else {
        header("Location: index.html");
    }

}

function is_spam(){

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['honeypot'])) {
            return true;
        }
    }
    return false;
}

function setup_authentication($mail){

    $mail->isSMTP(); //Deactivate on server
    $mail->SMTPAuth = true;
    $mail->Host = "smtp host";
    $mail->Port = 587;
    $mail->Username = "mail";
    $mail->Password = 'password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
}

function setup_addresses($mail){

    $mail->setFrom("mail", $_POST["name"]);
    $mail->addAddress("your mail");
    $mail->addReplyTo($_POST['email']);
}

function setup_message($mail){

    $message = "Name: ".$_POST['name'];
    $message .= '<br>Email: '.$_POST['email'];
    $message .= '<br>Message: '.$_POST['message'];

    $mail->isHTML(true);
    $mail->Subject = "Contact Form";
    $mail->Body = $message;
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
}

email_form_submission();

?>