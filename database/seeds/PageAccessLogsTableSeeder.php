<?php

use Illuminate\Database\Seeder;

class PageAccessLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('page_accesslogs')->truncate();

        $page_id_list = [];
        foreach(\App\Page::all() as $page){
            $page_id_list = array_merge($page_id_list, array($page->id));
        }
        for($i=0;$i<1000;$i++) {
            shuffle($page_id_list);
            $id = $page_id_list[0];
            \App\PageAccesslog::create([
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000/page?id=' . $id,
                'status_code' => 200,
            ]);
        }
        for($i=0;$i<100;$i++) {
            \App\PageAccesslog::create([
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ]);
        }
    }
}
