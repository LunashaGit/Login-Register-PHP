<?php 

$title = "Welcome page";

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

?>

<?php include './parts/head.php'; ?>
<body>
    <div class="container">
        <h1 class="form-title"><?= "Welcome : " . $_SESSION['username'] . " !"; ?></h1>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>