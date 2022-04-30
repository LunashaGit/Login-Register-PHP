<?php 

require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = new PDO($_ENV['server'], $_ENV['user'], $_ENV['password']);

if (!$conn) {
    die("<script>alert('Connection Failed.')</script>");
}

?>