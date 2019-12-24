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
        $page_list = [];
        foreach(\App\Page::all() as $page){
            $page_list = array_merge($page_list, array($page));
        }
        for($i=0; $i < 1000; $i++) {
            shuffle($page_list);
            \App\PageComment::create([
                'page_id' => $page_list[0]->id,
                'comment' => 'hoge' . $i,
                'handle_name' => 'unchi',
            ]);
        }
    }
}
