<?php 

$title = "Register";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';	
require './vendor/phpmailer/phpmailer/src/SMTP.php';	
require './vendor/phpmailer/phpmailer/src/Exception.php';	

include 'config.php';

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
	$mail->Subject    = 'Your Account Has Been Created';
	$mail->Body       = 'Your account has been created. Your account is now ready to use.';
} catch (Exception $e) {
	echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}


if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
	$username = strtolower($_POST['username']);
	$email = strtolower($_POST['email']);
	$password = md5($_POST['password']);
	$cpassword = md5($_POST['cpassword']);

	if ($password == $cpassword) {
		$sql = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);
		if (!$result->num_rows > 0) {
			$sql = "INSERT INTO users (username, email, password)
					VALUES ('$username', '$email', '$password')";
			$result = mysqli_query($conn, $sql);
			if ($result) {
				$mail->AddAddress($email,$username);
				$mail->send();
				$username = "";
				$email = "";
				$_POST['password'] = "";
				$_POST['cpassword'] = "";
				$validation =  "User Registration Completed.";
				header('refresh:3;url=index.php');
			} else {
				$error = "Something Wrong Went.";
			}
		} else {
			$error = "Email Already Exists.";
		}
	} else {
		$error = "Password Not Matched.";
	}
}

?>

<?php include './parts/head.php'; ?>

<body>
	<div class="container">
		<form action="" method="POST">
            <h1 class="form-title">SIGN UP</h1>
			<p style="color: red; text-align:center;"><?= (!empty($error)) ? $error : "" ?></p>
			<div class="form-group">
				<input type="text" placeholder="Username" autocomplete="off" name="username" value="<?= $username; ?>" required>
				<input type="email" placeholder="Email" autocomplete="off" name="email" value="<?= $email; ?>" required>
				<input type="password" placeholder="Password" name="password" required>
				<input type="password" placeholder="Confirm Password" name="cpassword" required>
				<button name="submit" class="btn">SIGN UP</button>
			</div>
			<p style="color: green; text-align:center;"><?= (!empty($validation)) ? $validation . "<br>" . "Going to login page in 3 seconds !" : "" ?></p>
			<hr>
			<p class="login-register-text"><a href="index.php">Login Page</a></p>
		</form>
	</div>
</body>
</html>