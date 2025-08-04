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
        Schema::create('reservation_slots', function (Blueprint $table) {
            $table->id();
            $table->uuid('slot_id');
            $table->timestamp('date');
            $table->foreignId('type_people_case_id')
                ->constrained('types_people_cases');
            $table->smallInteger('count');
            $table->tinyInteger('status')->default(0); // 0 - available, 1 - reserved,
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_slots');
    }
};
