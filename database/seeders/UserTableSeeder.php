<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'username' => 'shanshan',
            'email' => 'shanshan@gmail.com',
            'password' => Hash::make('shanshan'),
        ];

        User::create($data);
    }
}
