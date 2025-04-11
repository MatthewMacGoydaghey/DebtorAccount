<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_statuses')->insertOrIgnore([
            [ 'id' => 1, 'name' => 'Новый'],
            [ 'id' => 2, 'name' => 'В обработке'],
            [ 'id' => 3, 'name' => 'Оплачено'],
            [ 'id' => 4, 'name' => 'Не оплачено'],
            [ 'id' => 5, 'name' => 'Ошибка'],
        ]);

        DB::statement('ALTER TABLE payment_statuses AUTO_INCREMENT = 10;');
    }
}