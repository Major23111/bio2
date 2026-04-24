<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (App\Models\Cart\Cart::query()->with('items')->orderByDesc('id')->take(10)->get() as $cart) {
    echo json_encode([
        'id' => $cart->id,
        'user_id' => $cart->user_id,
        'session_id' => $cart->session_id ?? null,
        'currency' => $cart->currency ?? null,
        'items' => $cart->items->map(fn($item) => [
            'id' => $item->id,
            'variant' => $item->product_variant_id,
            'qty' => $item->quantity,
        ])->values()->all(),
    ], JSON_UNESCAPED_SLASHES), PHP_EOL;
}
