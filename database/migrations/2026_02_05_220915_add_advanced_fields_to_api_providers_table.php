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
            $table->string('network_type')->nullable()->after('name'); // e.g., MTN, GLO, etc.
            $table->string('request_method')->default('POST')->after('base_url');
            $table->json('request_headers')->nullable()->after('request_method');
            $table->json('request_body')->nullable()->after('request_headers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_providers', function (Blueprint $table) {
            $table->dropColumn(['network_type', 'request_method', 'request_headers', 'request_body']);
        });
    }
};
