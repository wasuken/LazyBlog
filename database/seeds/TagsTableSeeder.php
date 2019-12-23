<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tags')->truncate();

        $tags = [
            [
                'name' => 'JavaScript',
            ],
            [
                'name' => 'PHP',
            ],
            [
                'name' => 'Ruby',
            ],
            [
                'name' => 'Nim',
            ],
            [
                'name' => 'Rust',
            ],
        ];

        foreach($tags as $tag) {
            \App\Tag::create($tag);
        }
    }
}
