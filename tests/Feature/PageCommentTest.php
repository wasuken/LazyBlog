<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PageCommentTest extends TestCase
{
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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendComment()
    {
        $user = 'hoge' . Str::random(60);
        $comment = 'fuga' . Str::random(60);
        $page = \App\PageComment::all()->first();
        $this->followingRedirects()->post('/comment', [
            'user' => $user,
            'comment' => $comment,
            'id' => $page->id,
        ])->assertDontSee("The user field is required.")
            ->assertDontSee("The comment field is required.")
            ->assertDontSee("The id field is required.");
        $this->assertDatabaseHas('page_comments', [
            'handle_name' => $user,
            'comment' => $comment,
            'page_id' => $page->id,
        ]);
    }
    public function testSendCommentFormEmptyFailed()
    {
        $this->followingRedirects()->post('/comment', [])
            ->assertSee("The comment field is required.")
            ->assertSee("The id field is required.");
    }
    public function testSendComentValidateFailed()
    {
        $user = 'hoge' . Str::random(600);
        $comment = 'fuga' . Str::random(6000);
        $this->followingRedirects()->post('/comment', [
            'user' => $user,
            'comment' => $comment,
            'id' => "hogehogehogehoge" . Str::random(60),
        ]);
        $this->assertDatabaseMissing('page_comments', [
            'handle_name' => $user,
            'comment' => $comment,
        ]);
    }
}
