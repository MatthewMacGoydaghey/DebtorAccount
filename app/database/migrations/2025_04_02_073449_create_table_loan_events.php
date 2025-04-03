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
        Schema::create('loan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_event_action_id')->constrained('loan_event_actions', 'id');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_events', function (Blueprint $table) {
            $table->dropForeign(['loan_event_action_id']);
        });

        Schema::dropIfExists('loan_events');
    }
};
