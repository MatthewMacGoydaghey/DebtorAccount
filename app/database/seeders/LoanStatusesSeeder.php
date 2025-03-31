<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_statuses')->insertOrIgnore([
            [ 'id' => 1, 'name' => 'Активно'],
            [ 'id' => 2, 'name' => 'Кредит закрыт'],
            [ 'id' => 3, 'name' => 'Смена кредитора'],
            [ 'id' => 4, 'name' => 'Кредит аннулирован'],
        ]);

        DB::statement('ALTER TABLE loan_statuses AUTO_INCREMENT = 10;');
    }
}
