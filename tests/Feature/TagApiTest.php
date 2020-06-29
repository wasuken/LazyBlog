<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBasic()
    {
        $resp = $this->get('/api/tag');
        $resp->assertSuccessful();

        $keys = array_keys($resp->decodeResponseJson()[0]);

        $this->assertTrue(array_search('name', $keys) !== false);
    }
}
