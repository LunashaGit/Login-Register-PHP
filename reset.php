<?php
include 'config.php';

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = $_GET['email'];
    $token = $_GET['token'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
    if($password === $cpassword){
        $sql = "SELECT * FROM users WHERE email='$email' AND reset_token='$token'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            if($result){
                $response = "UPDATE users SET password='$password', reset_token='' WHERE email='$email' AND reset_token='$token'";
                mysqli_query($conn,$response);
                $error = ""; 
            } else{
                $error = "Update failed.";
            }
            
        } else {
            $error = "Email not found";
        }
    } else {
        $error = "Password doesn't match";
    }
} else {
    $error = "Email & token not set";
}
?>
<?php include './parts/head.php'; ?>
<body>
	<div class="container">
    <form action="" method="POST">
        <h1 class="form-title">Reset Password</h1>
        <p style="color: red; text-align:center;"><?= (!empty($error)) ? $error : "" ?></p>
        <div class="form-group">
            <input type="password" class="password" placeholder="Password" name="password" required>
            <input type="password" class="password" placeholder="Confirm Password" name="cpassword" required>
            <button name="submit" class="btn">Reset</button>
        </div>
    </form>
    </div>
</body>