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
        Schema::table('reservation_slots', function(Blueprint $table) {
            $table->bigInteger('reservation_queues_id')->unsigned()->after('type_people_case_id');
            $table->foreign('reservation_queues_id')
                ->references('id')
                ->on('reservation_queues');
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_slots', function (Blueprint $table) {
            $table->dropForeign(['reservation_queues_id']);
            $table->dropColumn('reservation_queues_id');
        });
    }
};
