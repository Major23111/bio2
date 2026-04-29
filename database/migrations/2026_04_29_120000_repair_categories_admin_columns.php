<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'description')) {
                $table->string('description')->nullable();
            }

            if (! Schema::hasColumn('categories', 'application')) {
                $table->string('application')->nullable();
            }

            if (! Schema::hasColumn('categories', 'hsm_code')) {
                $table->string('hsm_code')->nullable();
            }

            if (! Schema::hasColumn('categories', 'IsDisplayedOnHomePage')) {
                $table->boolean('IsDisplayedOnHomePage')->default(false);
            }

            if (! Schema::hasColumn('categories', 'default_image_path')) {
                $table->string('default_image_path')->nullable();
            }

            if (! Schema::hasColumn('categories', 'gst_rate')) {
                $table->decimal('gst_rate', 5, 2)->default(18.00);
            }

            if (! Schema::hasColumn('categories', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0);
            }

            if (! Schema::hasColumn('categories', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('categories', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // This is a production repair migration for existing Railway databases.
        // Rollback is intentionally a no-op so existing category data is never removed.
    }
};
