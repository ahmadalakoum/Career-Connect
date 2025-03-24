<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "career_connect";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed " . $e->getMessage();
}
?>