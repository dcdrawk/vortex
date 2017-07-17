<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Devin',
            'email' => 'devin.cook@canadadrives.ca',
            'password' => bcrypt('password123'),
        ]);
    }
}