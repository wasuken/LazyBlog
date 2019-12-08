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
        $page = \App\Page::all()->first();
        $tag_js = \App\Tag::where('name', 'JavaScript')->first();
        $tag_php = \App\Tag::where('name', 'PHP')->first();
        $tag_ruby = \App\Tag::where('name', 'Ruby')->first();

        $page_tags = [
            [
                'page_id' => $page->id,
                'tag_id' => $tag_js->id,
            ],
            [
                'page_id' => $page->id,
                'tag_id' => $tag_php->id,
            ],
            [
                'page_id' => $page->id,
                'tag_id' => $tag_ruby->id,
            ],
        ];

        foreach($page_tags as $page_tag) {
            \App\PageTag::create($page_tag);
        }
    }
}
