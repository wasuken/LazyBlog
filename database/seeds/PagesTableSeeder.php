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
        $user_writer = \App\User::where('name', 'test_writer')->first();
        $user_admin = \App\User::where('name', 'test_admin')->first();
        $pages = [
            [
                'title' => '今日のうんち1',
                'body' => 'うんちブリブリ',
                'user_id' => $user_writer->id,
            ],
            [
                'title' => '今日のうんち2',
                'body' => 'うんちブリブリ',
                'user_id' => $user_admin->id,
            ],
            [
                'title' => '今日のうんち3',
                'body' => 'うんちブリブリ',
                'user_id' => $user_admin->id,
            ],
        ];

        foreach($pages as $page) {
            \App\Page::create($page);
        }
    }
}
