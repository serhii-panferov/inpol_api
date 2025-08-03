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
        Schema::create('people_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedTinyInteger('status');
            $table->string('signature')->nullable();
            $table->uuid('type_id');
            $table->json('type_names');
            $table->string('person');
            $table->timestamp('creation_date');
            $table->foreignId('inpol_account_id')
                ->constrained('inpol_accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people_cases');
    }
};
