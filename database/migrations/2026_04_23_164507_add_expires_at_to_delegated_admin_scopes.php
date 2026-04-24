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
        Schema::table('delegated_admin_scopes', function (Blueprint $table) {
            // Add the missing expires_at column that was defined in the original
            // migration but was not applied to the existing database table.
            if (! Schema::hasColumn('delegated_admin_scopes', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('assigned_by_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delegated_admin_scopes', function (Blueprint $table) {
            if (Schema::hasColumn('delegated_admin_scopes', 'expires_at')) {
                $table->dropColumn('expires_at');
            }
        });
    }
};
