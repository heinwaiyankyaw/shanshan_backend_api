<?php

namespace Database\Seeders;

use App\Models\AhtoneLevel;
use Illuminate\Database\Seeder;

class AhtoneLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'ပါရုံ', 'position' => 1],
            ['name' => 'လျော့', 'position' => 2],
            ['name' => 'ပုံမှန်', 'position' => 3],
            ['name' => 'ပုံမှန်ထက်ပို', 'position' => 4],
        ];

        foreach ($datas as $data) {
            AhtoneLevel::create($data);
        }
    }
}