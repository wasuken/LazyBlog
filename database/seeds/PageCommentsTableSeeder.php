<?php

use Illuminate\Database\Seeder;

class PageCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('page_comments')->truncate();
        $page = \App\Page::all()->first();
        $page_2 = \App\Page::all()->skip(1)->first();
        $page_comments = [
            [
                'page_id' => $page->id,
                'comment' => 'ガババビッチ',
                'handle_name' => 'none',
            ],
            [
                'page_id' => $page->id,
                'comment' => 'うんち',
                'handle_name' => 'ノーン',
            ],
            [
                'page_id' => $page->id,
                'comment' => 'うんちっち',
                'handle_name' => 'unchi',
            ],
            [
                'page_id' => $page_2->id,
                'comment' => 'foo',
                'handle_name' => 'none',
            ],
            [
                'page_id' => $page_2->id,
                'comment' => 'huga',
                'handle_name' => 'ノーン',
            ],
            [
                'page_id' => $page_2->id,
                'comment' => 'hoge',
                'handle_name' => 'unchi',
            ],
        ];

        foreach($page_comments as $page_comment) {
            \App\PageComment::create($page_comment);
        }
    }
}
