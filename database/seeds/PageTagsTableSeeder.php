<?php

use Illuminate\Database\Seeder;

class PageTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('page_tags')->truncate();
        $tag_list = [];
        foreach(\App\Tag::all() as $tag){
            $tag_list = array_merge($tag_list, array($tag));
        }

        foreach(\App\Page::all() as $page) {
            shuffle($tag_list);
            $end = rand(0, count($tag_list));
            for($i = 0; $i < $end ;$i++){
                \App\PageTag::create([
                    'page_id' => $page->id,
                    'tag_id' => $tag_list[$i]->id,
                ]);
            }
        }
    }
}
