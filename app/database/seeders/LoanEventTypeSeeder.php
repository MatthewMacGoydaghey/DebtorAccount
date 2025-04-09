<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_event_types')->insertOrIgnore([
            ['id' => 1, 'content' => 'Запрос документов', 'active' => "true"],
            ['id' => 2, 'content' => 'Вопрос по сумме задолженности', 'active' => "true"],
            ['id' => 3, 'content' => 'Я не брал кредит', 'active' => "true"],
            ['id' => 4, 'content' => 'Являюсь участником СВО', 'active' => "true"],
            ['id' => 5, 'content' => 'Являюсь членом семьи участника СВО', 'active' => "true"],
            ['id' => 6, 'content' => 'Заказать звонок', 'active' => "true"],
            ['id' => 7, 'content' => 'Вопрос по кредитной истории', 'active' => "true"],
            ['id' => 8, 'content' => 'Кредитные каникулы', 'active' => "true"],
            ['id' => 9, 'content' => 'Оплатил задолженность', 'active' => "true"],
            ['id' => 10, 'content' => 'Признан Банкротом', 'active' => "true"],
        ]);

        DB::statement('ALTER TABLE loan_event_types AUTO_INCREMENT = 50;');
    }
}