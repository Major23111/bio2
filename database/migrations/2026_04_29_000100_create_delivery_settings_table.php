<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();         // e.g. 'pan_india_rate', 'lucknow_rate'
            $table->decimal('value', 10, 2)->default(0);
            $table->string('label')->nullable();      // Human-readable label
            $table->text('description')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('updated_by_user_id')->references('id')->on('users')->nullOnDelete();
        });

        // Seed defaults
        \Illuminate\Support\Facades\DB::table('delivery_settings')->insert([
            [
                'key' => 'pan_india_rate',
                'value' => 150.00,
                'label' => 'PAN India Delivery Rate',
                'description' => 'Standard flat-rate delivery fee across all Indian states (excluding Lucknow).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'lucknow_rate',
                'value' => 40.00,
                'label' => 'Lucknow Local Delivery Rate',
                'description' => 'Last-mile delivery pricing within Lucknow municipal boundaries.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};
