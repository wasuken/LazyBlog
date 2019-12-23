<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\Helper;

class PageTest extends TestCase
{
    use RefreshDatabase;
    private $user;
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
        $this->user = \App\User::where('name', 'test_admin')->first();
    }
    public function testBasic()
    {
        $response = $this->get('/')
                  ->assertSee(config('app.name', ''))
                  ->assertDontSee('logout')
                  ->assertDontSee('post');
        $response = $this->get('/pages')
                  ->assertSee(config('app.name', ''))
                  ->assertDontSee('logout')
                  ->assertDontSee('post');
    }
    public function testLogin()
    {
        $this->followingRedirects()->post('/login', [
            'email' => $this->user->email,
            'password' => 'testtest',
        ])->assertSee($this->user->name)
            ->assertDontSee('login')
            ->assertSee('post')
            ->assertSee('logout');
        $this->assertTrue(Auth::check());
    }
    public function testLoginFail()
    {
        $this->followingRedirects()->post('/login', [
            'email' => $this->user->email,
            'password' => 'testtestasdsjak',
        ])->assertSee('These credentials do not match our records.');
        $this->assertFalse(Auth::check());
    }
    public function testPages()
    {
        $response = $this->followingRedirects()
                  ->get('/pages?writer=' . $this->user->name)
                  ->assertDontSee("The selected writer is invalid.");
        $tag_name = \App\Tag::all()->first()->name;
        $response = $this->followingRedirects()
                  ->get('/pages?tag=' . $tag_name)
                  ->assertDontSee("The selected writer is invalid.");
    }
    public function testPagesFail()
    {
        $response = $this->followingRedirects()
                  ->get('/pages?writer=')
                  ->assertSee("The selected writer is invalid.");
        $response = $this->followingRedirects()
                  ->get('/pages?tag=')
                  ->assertSee("The selected tag is invalid.");
    }
    public function testPageShow()
    {
        $page = \App\Page::all()->first();
        $response = $this->followingRedirects()
                  ->get('/page?id=' . $page->id)
                  ->assertSee($page->title);
        $page_tags = \App\Tag::join('page_tags', 'page_tags.tag_id', 'tags.id')
                   ->select('tags.*', 'page_tags.page_id')
                   ->where('page_tags.page_id', $page->id)
                   ->get();
        foreach($page_tags as $page_tag){
            $response->assertSee($page_tag->name);
        }
    }
    public function testPageShowFail()
    {
        $response = $this->followingRedirects()
                  ->get('/page?id=' . Str::random(100))
                  ->assertSee('The selected id is invalid.');
    }
    public function testCommentsInPages()
    {
        $comments = Helper::myOrderBy(new \App\PageComment, 'created_at', 'desc')
                  ->orderBy('id', 'desc')
                  ->take(10)->get();
        $resp = $this->get('/');
        foreach($comments as $comment){
            $resp->assertSee($comment->comment);
        }
    }
    public function testCommentsInPage()
    {
        $page = \App\Page::all()->first();
        $comments = \App\PageComment::where('page_id', $page->id)
                  ->where('page_id', $page->id)->get();

        $resp = $this->get('/page?id=' . $page->id);
        foreach($comments as $comment){
            $resp->assertSee($comment->comment);
        }
    }
    public function testCommentsInPageFail()
    {
        $page = \App\Page::all()->first();
        $comments = \App\PageComment::where('page_id', '<>', $page->id)->get();
        $resp = $this->followingRedirects()
              ->get('/page?id=lskdjfldskdjlkdfjlkd;aj')
              ->assertSee('The selected id is invalid.');
    }
    public function createPostDataTable()
    {
        $post_data_table = [];
        $post_data_table['success'] = [];
        $post_data_table['success'][0] = [
            'title' => 'a',
            'body' => 'b',
            'tags' => ['Ruby', 'b'],
        ];
        $post_data_table['fail'] = [];
        $post_data_table['fail'][0] = [

        ];
        return $post_data_table;
    }
    public function testPagePost()
    {
        $this->followingRedirects()->post('/login', [
            'email' => $this->user->email,
            'password' => 'testtest',
        ]);
        $post_data_table = $this->createPostDataTable();

        foreach($post_data_table['success'] as $post_data){
            $this->followingRedirects()->post('/page', $post_data)
                ->assertDontSee('The title field is required.')
                ->assertDontSee('The body field is required.')
                ->assertDontSee('The tags field is required.');
        }
    }
    public function testPagePostFailNoLogin()
    {
        $post_data_table = $this->createPostDataTable();

        foreach($post_data_table['success'] as $post_data){
            $this->post('/page', $post_data)->assertRedirect('/login');
        }
    }
    public function testPagePostFailNoParams()
    {
        $post_data_table = $this->createPostDataTable();
        $this->followingRedirects()->post('/login', [
            'email' => $this->user->email,
            'password' => 'testtest',
        ]);
        foreach($post_data_table['success'] as $post_data){
            $this->followingRedirects()->post('/page', [])
                ->assertSee('The title field is required.')
                ->assertSee('The body field is required.');
        }
    }
    public function testPageApiPost()
    {
        $token = $this->user->api_token;
        $post_data_table = $this->createPostDataTable();

        foreach($post_data_table['success'] as $post_data){
            $post_data['token'] = $token;
            $resp = $this->followingRedirects()
                ->post('/api/page', $post_data);
            $page = \App\Page::where('title', $post_data['title'])->first();
            $resp->assertJson([
                    'id'=> $page->id,
                    'title' => $page->title,
                    'body' => $page->body,
            ]);
        }
    }
    public function testPageApiPostFail()
    {
        $token = $this->user->api_token;
        $post_data_table = $this->createPostDataTable();

        foreach($post_data_table['success'] as $post_data){
            $resp = $this->followingRedirects()
                ->post('/api/page', $post_data);
            $page = \App\Page::where('title', $post_data['title'])->first();
            if(empty($page)){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }
    public function testRssPage()
    {
        $this->get(url('?type=atom'))->assertStatus(200);
        $this->get(url('?type=rss2.0'))->assertStatus(200);
    }
    public function testPostMarkdown()
    {
        $this->followingRedirects()->post('/login', [
            'email' => $this->user->email,
            'password' => 'testtest',
        ]);
        $this->assertTrue(Auth::check());
        $this->followingRedirects()->post('/page', [
            'title' => 'hogehoge',
            'body' => '# hogemi\n~~hogehoge~~',
            'tags' => ["test", "hoge", "unchi"],
            'type' => 'md',
        ]);
        $page = \App\Page::where('title', 'hogehoge')->first();
        $this->assertFalse(empty($page));
        $this->followingRedirects()->post('/page', [
            'title' => 'fugafuga',
            'body' => '# hogemi\n~~hogehoge~~',
            'tags' => ["test", "hoge", "unchi"],
            'type' => 'hoghoge',
        ])->assertSee("The selected type is invalid.");
        $page = \App\Page::where('title', 'fugafuga')->first();
        $this->assertTrue(empty($page));
    }
}
