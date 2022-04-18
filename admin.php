<?php 

$title = "Admin page";
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    header("Location: index.php");
}
?>

<?php include './parts/head.php'; ?>
<body>
	<div class="container">
		<h1><?= "Welcome Back Admin " . $_SESSION['username'] . " !"?></h1>
        <a href="logout.php">Logout</a>
	</div>
</body>
</html>