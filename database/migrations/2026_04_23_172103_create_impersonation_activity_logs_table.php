<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Create the impersonation activity log table.
    // Every important action performed during an impersonation session is stored here.
    public function up(): void
    {
        Schema::create('impersonation_activity_logs', function (Blueprint $table): void {
            $table->id();

            // Link each log row to its parent impersonation session.
            $table->unsignedBigInteger('impersonation_audit_id');

            // The URL the impersonated user accessed.
            $table->string('url', 500);

            // The HTTP method used (POST, PUT, DELETE, etc.).
            $table->string('http_method', 10);

            // A short plain-text description of what action was taken.
            $table->string('action_description', 255)->nullable();

            // The IP address at the time of the action.
            $table->string('ip_address', 45)->nullable();

            // When exactly the action happened.
            $table->timestamp('performed_at');

            $table->timestamps();

            $table->foreign('impersonation_audit_id')
                ->references('id')
                ->on('impersonation_audits')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impersonation_activity_logs');
    }
};
