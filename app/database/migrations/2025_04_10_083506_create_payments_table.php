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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Дата платежа');
            $table->string('payer_name')->nullable()->comment('От кого платёж');;
            $table->string('purpose_of_payment')->nullable()->comment('Основание для платежа');;
            $table->decimal('amount');
            $table->foreignId('payment_status_id')->constrained('payment_statuses', 'id');
            $table->foreignId('loan_id')->constrained('loans', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
