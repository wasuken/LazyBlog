<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();
        $pwd = 'testtest';

        $users = [
            [
                'name' => 'test_guest',
                'email' => 'guest@tmail.com',
                'password' => Hash::make($pwd),
                'api_token' => Str::random(60),
            ],
            [
                'name' => 'test_admin',
                'email' => 'admin@tmail.com',
                'password' => Hash::make($pwd),
                'api_token' => Str::random(60),
            ],
            [
                'name' => 'test_writer',
                'email' => 'writer@tmail.com',
                'password' => Hash::make($pwd),
                'api_token' => Str::random(60),
            ],
        ];
        foreach($users as $user) {
            \App\User::create($user);
        }
    }
}
