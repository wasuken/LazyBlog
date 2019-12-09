<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('pages')->truncate();
        $user_list = [];
        $user_list[0] = \App\User::where('name', 'test_writer')->first();
        $user_list[1] = \App\User::where('name', 'test_admin')->first();


        for($i = 0; $i<100; $i++) {
            shuffle($user_list);
            \App\Page::create([
                'title' => '今日のうんち' . $i,
                'body' => Str::random(300),
                'user_id' => $user_list[0]->id,
            ]);
        }
    }
}
