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

        // foreach($users as $user) {
        //     \App\User::create($user);
        // }
        // DB::table('users')->truncate();
        // $pwd = Str::random(30);
        // $token = Str::random(60);
        // if(file_exists("pwd.txt")){
        //     unlink("pwd.txt");
        // }
        // if(file_exists("token.txt")){
        //     unlink("token.txt");
        // }
        // file_put_contents("pwd.txt", "pwd:" . $pwd . "\n");
        // file_put_contents("token.txt", "token:" . $token . "\n");

        // $users = [
        //     [
        //         'name' => 'admin',
        //         'email' => 'wevorence@gmail.com',
        //         'password' => Hash::make($pwd),
        //         'api_token' => $token,
        //     ],
        // ];

        // foreach($users as $user) {
        //     \App\User::create($user);
        // }
    }
}
