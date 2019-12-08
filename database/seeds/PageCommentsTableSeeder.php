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
        $page_comments = [
            [
                'page_id' => $page->id,
                'comment' => 'ガババビッチ',
            ],
            [
                'page_id' => $page->id,
                'comment' => 'うんち',
            ],
            [
                'page_id' => $page->id,
                'comment' => 'うんちっち',
            ],
        ];

        foreach($page_comments as $page_comment) {
            \App\PageComment::create($page_comment);
        }
    }
}
