<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'အသားငါး'],
            ['name' => 'အသီးအရွှက်'],
            ['name' => 'အအေး'],
            ['name' => 'အခြား'],
        ];

        foreach ($datas as $data) {
            Category::create($data);
        }
    }
}