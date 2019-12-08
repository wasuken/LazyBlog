<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PagesTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    protected function setup(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->seed("UsersTableSeeder");
        $this->seed("PagesTableSeeder");
        $this->seed("RolesTableSeeder");
        $this->seed("UserRoleTableSeeder");
        $this->seed("TagsTableSeeder");
        $this->seed("PageTagsTableSeeder");
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
    }
    public function testPagesFail()
    {

        $response = $this->followingRedirects()
                  ->get('/pages?writer=')
                  ->assertSee("The selected writer is invalid.");
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
}
