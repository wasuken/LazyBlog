<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;

class AccesslogTest extends TestCase
{
    use RefreshDatabase;
    protected function setup(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->seed("UsersTableSeeder");
        $this->seed("PagesTableSeeder");
        $this->seed("PageCommentsTableSeeder");
        $this->seed("RolesTableSeeder");
        $this->seed("UserRoleTableSeeder");
        $this->seed("TagsTableSeeder");
        $this->seed("PageTagsTableSeeder");
        $this->seed("PageAccessLogsTableSeeder");
    }
    public function testBasic()
    {
        // successes
        $response = $this->get('/');
        $page_accesslog = \App\PageAccesslog::where('url', 'like', $_SERVER['APP_URL'])->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertTrue(true);
        }
        $response = $this->get('/pages');
        $page_accesslog = \App\PageAccesslog::where('url', 'like', "%/pages")->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertTrue(true);
        }
        $response = $this->get('/pages');
        $page_accesslog = \App\PageAccesslog::where('url', 'like', "%/pages")->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertTrue(true);
        }
        $dir_and_file = '/page?id=' . \App\Page::all()->first()->id;
        $response = $this->get($dir_and_file);
        $page_accesslog = \App\PageAccesslog::where('url', 'like', "%$dir_and_file")->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertTrue(true);
        }
        // fails
        $dir_and_file = '/page?id=lkdsajfklfkldja';
        $response = $this->get($dir_and_file);
        $page_accesslog = \App\PageAccesslog::where('url', 'like', "%$dir_and_file")->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertEquals($page_accesslog->status_code, 302);
            $this->assertTrue(true);
        }
        $dir_and_file = '/ldksafjldsajfkdjsakjl;';
        $response = $this->get($dir_and_file);
        $page_accesslog = \App\PageAccesslog::where('url', 'like', "%$dir_and_file")->first();
        if($page_accesslog === null){
            $this->fail();
        }else{
            $this->assertEquals($page_accesslog->status_code, 404);
            $this->assertTrue(true);
        }
    }
}
