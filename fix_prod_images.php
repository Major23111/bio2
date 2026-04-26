<?php
/**
 * Fix product_image relationships on Railway production database.
 */

$host     = 'monorail.proxy.rlwy.net';
$port     = 19509;
$user     = 'root';
$password = 'ouTgRuPpZPwjuHeUdqsTlIJYxlRbxeNZ';
$database = 'railway';

echo "Connecting to Railway MySQL...\n";

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $user, $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Fixing existing product images...\n";

    $sql = "UPDATE product_image 
            SET product_id = (SELECT id FROM products WHERE products.product_image_id = product_image.id) 
            WHERE EXISTS (SELECT 1 FROM products WHERE products.product_image_id = product_image.id)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    echo "Fixed " . $stmt->rowCount() . " image relationships.\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
