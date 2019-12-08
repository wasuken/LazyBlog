<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_roles')->truncate();
        // role
        $role_guest = \App\Role::where('name', 'guest')->first();
        $role_writer = \App\Role::where('name', 'writer')->first();
        $role_admin = \App\Role::where('name', 'admin')->first();
        // user
        $user_guest = \App\User::where('name', 'test_guest')->first();
        $user_writer = \App\User::where('name', 'test_writer')->first();
        $user_admin = \App\User::where('name', 'test_admin')->first();
        $user_role_list = [
            [
                'user_id' => $user_guest->id,
                'role_id' => $role_guest->id,
            ],
            [
                'user_id' => $user_writer->id,
                'role_id' => $role_writer->id,
            ],
            [
                'user_id' => $user_admin->id,
                'role_id' => $role_admin->id,
            ],
        ];

        foreach($user_role_list as $user_role) {
            \App\UserRole::create($user_role);
        }
    }
}
