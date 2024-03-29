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
try {
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
} catch (Exception $e) {
	echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}


if (isset($_SESSION['username'])) {
	header("Location: index.php");
}

if (isset($_POST['submit'])) {
	$username = htmlspecialchars(strtolower($_POST['username']));
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		$error = "Username can only contain letters and numbers!";
	} else {
		$email = strtolower($_POST['email']);
		if ($password == $cpassword) {
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
			$cpassword = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);
			$sql = $conn->prepare("SELECT * FROM users WHERE email='$email'");
			$sql->execute();
			if (!$sql->rowCount() > 0) {
				$result = $conn->prepare("INSERT INTO users (username, email, password)
					VALUES ('$username', '$email', '$password')");
				$result->execute();
				if ($result) {
					$mail->AddAddress($email, $username);
					$mail->isHTML(true);
					$mail->Body = '
					<h1>Welcome to the website!</h1>
					<p>Your account has been created!</p>
					<p>Enjoy!</p>
					';
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
}

?>

<?php include './parts/head.php'; ?>

<body>
	<div class="container">
		<form action="" method="POST">
			<a href="index.php"><i class="arrow left"></i></a>
			<h1 class="form-title">SIGN UP</h1>
			<p style="color: red; text-align:center;"><?= (!empty($error)) ? $error : "" ?></p>
			<div class="form-group">
				<input type="text" placeholder="Username" autocomplete="off" name="username" value="<?= $username; ?>" required>
				<input type="email" placeholder="Email" autocomplete="off" name="email" value="<?= $email; ?>" required>
				<input type="password" class="password" placeholder="Password" name="password" required>
				<input type="password" class="password" placeholder="Confirm Password" name="cpassword" required>
				<label for="show-password" style="display: inherit;" class="show-password">
					<input type="checkbox" name="showpassword" style="box-shadow: none; width: auto; margin:1.2rem 1rem;" onclick="showPassword()">
					<p>show Password</p>
				</label>
				<button name="submit" class="btn">SIGN UP</button>
			</div>
			<p style="color: green; text-align:center;"><?= (!empty($validation)) ? $validation . "<br>" . "Going to login page in 3 seconds !" : "" ?></p>
			<hr>
			<p class="login-register-text"><a href="index.php">Login Page</a></p>
		</form>
	</div>
	<script>
		function showPassword() {
			const x = document.querySelectorAll(".password");
			x.forEach(element => {
				if (element.type === "password") {
					element.type = "text";
				} else {
					element.type = "password";
				}
			});
		}
	</script>
</body>

</html>