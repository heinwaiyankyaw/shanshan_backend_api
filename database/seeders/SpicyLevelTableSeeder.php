<?php

namespace Database\Seeders;

use App\Models\SpicyLevel;
use Illuminate\Database\Seeder;

class SpicyLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'အချို', 'position' => 1],
            ['name' => 'ပါရုံ', 'position' => 2],
            ['name' => 'လျော့', 'position' => 3],
            ['name' => 'ပုံမှန်', 'position' => 4],
            ['name' => 'ပုံမှန်ထက်ပို', 'position' => 5],
        ];

        foreach ($datas as $data) {
            SpicyLevel::create($data);
        }
    }
}