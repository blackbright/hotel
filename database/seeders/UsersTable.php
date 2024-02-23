<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'user' => 'admin',
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('kkk'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'user' => 'user',
                'name' => 'User',
                'email' => 'user@mail.com',
                'password' => Hash::make('kkk'),
                'role' => 'user',
                'status' => 'active',
            ],
            [
                'user' => 'test',
                'name' => 'test',
                'email' => 'test@mail.com',
                'password' => Hash::make('kkk'),
                'role' => 'user',
                'status' => 'active',
            ],

        ]);
    }
}
