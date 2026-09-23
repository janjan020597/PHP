<?php
$host = 'localhost';
$db   = '3h'; // kun ano ginpangalan nyo sa database nyo!
$user = 'root'; // XAMPP default
$pass = '';     // XAMPP default is blank

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Optional: you can append $e->getMessage() for debugging, 
    // but keep it hidden in production for security!
    die("Database connection failed.");
}
?>