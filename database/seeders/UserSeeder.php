<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('users')->insert([
            [
                'username' => 'admin',
                'borrower_id' => '0',
                'email' => 'admin@gmail.com',
                'password' => Hash::make(111),
                'roles' => 'admin',
            ],
       ]); 
    }
}