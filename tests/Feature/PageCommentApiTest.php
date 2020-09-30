<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageCommentApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBasic()
    {
        $resp = $this->get('/api/comment');

        $keys = array_keys($resp->decodeResponseJson()[0]);

        $this->assertTrue(array_search('page_id', $keys) !== false);
        $this->assertTrue(array_search('handle_name', $keys) !== false);
        $this->assertTrue(array_search('comment', $keys) !== false);
        $this->assertFalse(array_search('ip_address', $keys));
    }
}
