<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очистим таблицу перед заполнением (опционально)
        // User::truncate();

        User::create([
            'role' => 'admin',
            'email' => 'babchenkod1999@gmail.com',
            'password' => '123123123',
        ]);

    }
}