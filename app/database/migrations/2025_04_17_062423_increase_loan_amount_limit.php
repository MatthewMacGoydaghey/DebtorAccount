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
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('loan_amount', 15, 2)->change()->comment('Сумма займа');
            $table->decimal('total_outstanding_amount', 15, 2)->change()->comment('Общая сумма задолженности');
            $table->decimal('remaining_amount', 15, 2)->change()->comment('Остаток задолженности');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
