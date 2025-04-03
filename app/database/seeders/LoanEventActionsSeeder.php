<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanEventActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_event_actions')->insertOrIgnore([
            [ 'id' => 1, 'name' => 'Оплата'],
            [ 'id' => 2, 'name' => 'Входящее сообщение'],
            [ 'id' => 3, 'name' => 'Исходящее сообщение'],
        ]);

        DB::statement('ALTER TABLE loan_event_actions AUTO_INCREMENT = 10;');
    }
}