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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string("date_of_contract")->comment("Дата договора");
            $table->string("lender")->comment("Кредитор");
            $table->string("contract_number")->comment("Номер договора");
            $table->decimal("loan_amount")->comment("Сумма займа");
            $table->decimal("total_outstanding_amount")->comment("Общая сумма задолжености");
            $table->decimal("remaining_amount")->comment("Остаток задолженности");
            $table->foreignId('loan_status_id')->constrained('loan_statuses', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['loan_status_id']);
        });
        
        Schema::dropIfExists('loans');
    }
};
