<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $user;
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
    protected function createPostDataTable()
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
}
