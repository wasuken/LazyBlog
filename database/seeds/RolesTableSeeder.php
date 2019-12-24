<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->truncate();

        $roles = [
            [
                'name' => 'guest',
            ],
            [
                'name' => 'writer',
            ],
            [
                'name' => 'admin',
            ],
        ];

        foreach($roles as $role) {
            \App\Role::create($role);
        }
    }
}
