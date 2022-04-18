<?php
$title = "Reset";
include 'config.php';

if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
    if (isset($_POST['submit'])) {
        if ($password === $cpassword) {
            $sql = "SELECT * FROM users WHERE id='$id' AND reset_token='$token'";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
                if ($result) {
                    $response = "UPDATE users SET password='$password', reset_token='' WHERE id='$id' AND reset_token='$token'";
                    mysqli_query($conn, $response);
                    $validation = "Password changed successfully";
                } else {
                    $error = "Update failed.";
                }
            } else {
                $error = "Email or Token not valid.";
            }
        } else {
            $error = "Password and Confirm Password does not match";
        }
    }
} else {
    $error = "Email & token not set.";
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
                <label for="show-password" style="display: inherit;" class="show-password">
                    <input type="checkbox" name="showpassword" style="box-shadow: none; width: auto; margin:1.2rem 1rem;" onclick="showPassword()">
                    <p>show Password</p>
                </label>
                <button name="submit" class="btn">Reset</button>
            </div>
            <p style="color: green; text-align:center;"><?= (!empty($validation)) ? $validation : "" ?></p>
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