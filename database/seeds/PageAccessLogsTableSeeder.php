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
        $page_accesslogs = [
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
            [
                'ip_address' => '192.168.10.' . rand(2, 253),
                'user_agent' => Str::random(30),
                'refer' => Str::random(30),
                'url' => 'http://localhost:8000',
                'status_code' => 200,
            ],
        ];

        foreach($page_accesslogs as $page_accesslog) {
            \App\PageAccesslog::create($page_accesslog);
        }
    }
}
