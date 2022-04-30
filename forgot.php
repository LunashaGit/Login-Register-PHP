<?php

$title = "Forgot";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';	
require './vendor/phpmailer/phpmailer/src/SMTP.php';	
require './vendor/phpmailer/phpmailer/src/Exception.php';	

include 'config.php';

function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

error_reporting(0);

session_start();

$mail = new PHPMailer(true);

try{
	$mail->IsSMTP();
	$mail->SMTPAuth = 1;
	$mail->Host = $_ENV['SMTP_server'];               
	$mail->Port = 465;                          
	$mail->SMTPSecure = 'ssl';
	$mail->Username   =  $_ENV['SMTP_account'];   
	$mail->Password   =  $_ENV['SMTP_password'];         
	$mail->setFrom($_ENV['SMTP_from']);  
	$mail->addReplyTo($_ENV['SMTP_account'], $_ENV['SMTP_name']); 
	$mail->Subject    = 'Reset Your Password';
} catch (Exception $e) {
	echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

if (isset($_POST['submit'])) {
	$email = strtolower($_POST['email']);
    $sql = $conn->prepare("SELECT * FROM users WHERE email='$email'");
    $sql->execute();
    if ($sql->rowCount() > 0) {
        if ($sql) {
            $row = $sql->fetch();
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $token = str_random(60);
            $update = $conn->prepare("UPDATE users SET reset_token='$token' WHERE email='$email'");
            $update->execute();
            $mail->AddAddress($email);
            $mail->isHTML(true);
	        $mail->Body = "
            <h1>Hey ". $row['username'] . "</h1>
            <h2>Reset Password</h2>
            <p>Click on the link below to reset your password:</p>
            <a href='http://localhost:8005/reset.php?id={$_SESSION['id']}&token={$token}'>Reset Password</a>
            <p>Or copy and paste this link in your browser:</p>
            <p>http://localhost:8005/reset.php?id={$_SESSION['id']}&token={$token}</p>
            <p>If you did not request a password reset, please ignore this email.</p>
            ";
            $mail->send();
            $email = "";
            $_POST['email'] = "";
            $validation = "An email has been sent!";
        } else {
            $error =  "Error: " . $sql . "<br>" . "Error Code: " . $conn->error;
        }
    } else {
        $error = "Email not found";
    }
}
?>
<?php include './parts/head.php'; ?>
<body>
    <div class="container">
        <a href="index.php"><i class="arrow left"></i></a>
        <h1 class="form-title">Forgot Password</h1>
        <p style="color: red; text-align:center;"><?= (!empty($error)) ? $error : "" ?></p>
        <form action="" method="POST">
            <div class="form-group">
                <input type="email" autocomplete="off" name="email"  class="form-control" placeholder="Email" required>
                <button name="submit" class="btn">Send Mail</button>
            </div>
			<p style="color: green; text-align:center;"><?= (!empty($validation)) ? $validation : "" ?></p>
        </form>
    </div>
</body>