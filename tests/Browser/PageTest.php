<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageTest extends DuskTestCase
{
    private $base_url = 'http://localhost:8000/';
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
        $this->user = \App\User::where('name', 'test_admin')->first();
    }
    public function testBasic()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->base_url)
                ->assertSee(config('app.name', ''));
        });
    }
    public function testLogin()
    {
        $this->browse(function ($browser) {
            $browser
                ->visit($this->base_url . 'login')
                ->type('email', $this->user->email)
                ->type('password', 'testtest')
                ->press('Login')
                ->assertPathIs('/');
        });
    }
}
