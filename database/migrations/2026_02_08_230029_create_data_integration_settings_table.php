<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_integration_settings', function (Blueprint $table) {
            $table->id();
            $table->string('base_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('last_tested_at')->nullable();
            $table->string('test_status')->nullable(); // 'success', 'failed', null
            $table->text('test_message')->nullable(); // Error message or success details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_integration_settings');
    }
};
