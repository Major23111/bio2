<?php
$host     = 'monorail.proxy.rlwy.net';
$port     = 19509;
$user     = 'root';
$password = 'ouTgRuPpZPwjuHeUdqsTlIJYxlRbxeNZ';
$database = 'railway';

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $user, $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    $products = $pdo->query("SELECT id, name, sku FROM products WHERE id <= 25")->fetchAll();
    foreach($products as $p) {
        echo "Product ID: {$p['id']} | SKU: {$p['sku']} | Name: {$p['name']}\n";
    }

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
