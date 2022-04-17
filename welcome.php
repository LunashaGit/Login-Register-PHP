<?php 

$title = "Hobby";

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

?>

<?php include './parts/head.php'; ?>
<body>
    <?= "<h1>Welcome " . $_SESSION['username'] . "</h1>"; ?>
    <a href="logout.php">Logout</a>
</body>
</html>