<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'မာလာမောက်ချိုက်'],
            ['name' => 'ရှမ်းကော'],
            ['name' => 'ငါးကင်'],
        ];
        foreach ($datas as $data) {
            Menu::create($data);
        }
    }
}