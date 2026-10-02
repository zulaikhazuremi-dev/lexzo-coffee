<?php

// $host = "127.0.0.1";
// $dbname = "lexzo_coffee";
// $username = "root";
// $password = "";

$host = "sql205.infinityfree.com";
$dbname = "if0_42921650_lexzo_coffee";
$username = "if0_42921650";
$password = "j2VHEkNSX3g";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}