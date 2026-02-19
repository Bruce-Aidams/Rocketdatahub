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
        Schema::table('api_providers', function (Blueprint $table) {
            // Timeout and retry configuration
            $table->integer('timeout_seconds')->default(30)->after('is_active');
            $table->integer('retry_attempts')->default(3)->after('timeout_seconds');

            // Request body template with placeholders like {phone}, {amount}, {package}
            $table->text('request_body_template')->nullable()->after('request_body');

            // Response field mapping for parsing API responses
            $table->string('response_success_field')->default('success')->after('request_body_template');
            $table->string('response_data_field')->default('data')->after('response_success_field');
            $table->string('response_error_field')->default('error')->after('response_data_field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_providers', function (Blueprint $table) {
            $table->dropColumn([
                'timeout_seconds',
                'retry_attempts',
                'request_body_template',
                'response_success_field',
                'response_data_field',
                'response_error_field'
            ]);
        });
    }
};
