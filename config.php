<?php 

require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = mysqli_connect($_ENV['server'], $_ENV['user'], $_ENV['password'], $_ENV['database']);

if (!$conn) {
    die("<script>alert('Connection Failed.')</script>");
}

?>