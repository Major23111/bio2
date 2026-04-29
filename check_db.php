<?php

$host = "hopper.proxy.rlwy.net";
$port = 34576;
$db = "railway";
$user = "root";
$pass = "UhoACHhcsRWyQRjfRJZBYPFXrzPORnCz";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected successfully\n";

    // test products
    $stmt = $pdo->query("SELECT id, name FROM products LIMIT 1");
    echo "Products query OK. ";
    
    // test orders
    $stmt = $pdo->query("SELECT id, total_amount, status FROM orders LIMIT 1");
    echo "Orders query OK. ";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
