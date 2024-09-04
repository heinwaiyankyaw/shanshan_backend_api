<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'အသား', 'qty' => 100, 'is_gram' => 1, 'prices' => 4000, 'category_id' => 1],
            ['name' => 'အရွက်', 'qty' => 1, 'is_gram' => 0, 'prices' => 1000, 'category_id' => 1],
            ['name' => 'ငါး', 'qty' => 1, 'is_gram' => 0, 'prices' => 15000, 'category_id' => 1],
            ['name' => 'ဟင်းရည်/အနှစ်', 'qty' => 1, 'is_gram' => 0, 'prices' => 3500, 'category_id' => 1, 'is_default' => 1],
            ['name' => 'ပဲလိပ်', 'qty' => 1, 'is_gram' => 0, 'prices' => 2500, 'category_id' => 1],
            ['name' => 'ထမင်း', 'qty' => 1, 'is_gram' => 0, 'prices' => 1000, 'category_id' => 1],
            ['name' => 'ရေ', 'qty' => 1, 'is_gram' => 0, 'prices' => 1000, 'category_id' => 1],
            ['name' => 'BHC', 'qty' => 1, 'is_gram' => 0, 'prices' => 3500, 'category_id' => 1],
            ['name' => 'Chapay', 'qty' => 1, 'is_gram' => 0, 'prices' => 3500, 'category_id' => 1],
            ['name' => 'JDB', 'qty' => 1, 'is_gram' => 0, 'prices' => 2500, 'category_id' => 1],
            ['name' => 'Cola', 'qty' => 1, 'is_gram' => 0, 'prices' => 2000, 'category_id' => 1],
            ['name' => 'Pepsi', 'qty' => 1, 'is_gram' => 0, 'prices' => 2000, 'category_id' => 1],
        ];

        foreach ($datas as $data) {
            Product::create($data);
        }
    }
}
