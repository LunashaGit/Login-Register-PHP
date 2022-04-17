<?php
$title = "Reset";

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
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        if ($result) {
            $token = str_random(60);
            $update = "UPDATE users SET reset_token='$token' WHERE email='$email'";
            mysqli_query($conn,$update);
            $mail->AddAddress($email);
	        $mail->Body = "With this link you can reset your password: \n http://localhost:8005/reset.php?email={$email}&token={$token}";
            $mail->send();
            $email = "";
            $_POST['email'] = "";
            $validation = "An email has been sent!";
        } else {
            $error =  "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $error = "Email not found";
    }
}
?>
<?php include './parts/head.php'; ?>
<body>
    <div class="container">
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