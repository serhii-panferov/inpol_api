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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('request_type')->nullable();
            $table->string('method')->nullable();
            $table->string('status_code')->nullable();
            $table->string('url')->nullable();
            $table->text('request_body')->nullable();
            $table->text('request_headers')->nullable();
            $table->text('response_body')->nullable();
            $table->text('response_headers')->nullable();
            $table->text('cookies')->nullable();
            $table->integer('duration_ms')->nullable()->after('cookies');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
