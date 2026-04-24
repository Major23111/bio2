<?php
require __DIR__ . '/vendor/autoload.php';
$pricing = (new App\Services\Utility\OrderItemCalculator())->calculateItemPricing([
    'amount' => 100,
    'base_amount' => 120,
    'tax_amount' => 18,
    'price_after_gst' => 118,
    'currency' => 'INR',
    'price_type' => 'logged_in',
    'gst_rate' => 18,
    'min_order_quantity' => 1,
    'max_order_quantity' => 3,
    'lot_size' => 1,
    'discount_amount' => 5,
], 2);
echo json_encode($pricing, JSON_UNESCAPED_SLASHES), PHP_EOL;
