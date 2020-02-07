<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'), // password
                'remember_token' => Str::random(10),
                'role' => 1,
            ],
            [
                'name' => 'normal',
                'email' => 'user@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('user123'), // password
                'remember_token' => Str::random(10),
                'role' => 2,
            ],
        ]);
    }
}
