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
        Schema::table('loan_events', function (Blueprint $table) {
            $table->foreignId('loan_event_type_id')->constrained('loan_event_types', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_events', function (Blueprint $table) {
            $table->dropForeign(['loan_event_type_id']);
            $table->dropColumn('loan_event_type_id');
        });
    }
};
