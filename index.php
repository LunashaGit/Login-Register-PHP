<?php 
$title = "Login";
include 'config.php';

session_start();

error_reporting(0);

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
}

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);

	$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
	$result = mysqli_query($conn, $sql);
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_assoc($result);
		$_SESSION['username'] = $row['username'];
		header("Location: welcome.php");
	} else {
		$error = "Email or Password is Wrong.";
	}
}

?>

<?php include './parts/head.php'; ?>
<body>
	<div class="container">
		<form action="" method="POST">
			<h1 class="form-title">Login</h1>
			<p style="color: red; text-align:center;"><?= (!empty($error)) ? $error : "" ?></p>
			<div class="form-group">
				<input type="email" placeholder="Email" autocomplete="off" name="email" value="<?= $email; ?>" required>
				<input type="password" placeholder="Password" name="password"  required>
				<button name="submit" class="btn">Login</button>
			</div>
			<hr>
			<p><a href="forgot.php">Forgot Password?</a></p>
			<p><a href="register.php">Register Page</a></p>
		</form>
	</div>
</body>
</html>