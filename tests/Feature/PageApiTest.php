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
use Carbon\Carbon;

class PageApiTest extends TestCase
{
    use RefreshDatabase;
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
    public function apiGetBaseResponse($params)
    {
        $paramList = array_map(function($k, $v){
            return $k . '=' . $v;
        },array_keys($params), array_values($params));
        $paramString = '?'.join('&', $paramList);

        return $this->followingRedirects()
                    ->get('/api/page' . $paramString);
    }
    // 少なくとも、ここら辺さえしっかり動けば実用的には問題ないと思われる箇所のテスト
    public function testApiPageIndexBase(){
        $params = [
            'q' => 'af',
            'count' => '100',
            'current' => '1',
        ];
        // 単体のクエリのテスト
        $resp = $this->apiGetBaseResponse($params);
        $resp->assertSuccessful();
        $singleCnt = count($resp->decodeResponseJson());
        // 複数に分割される場合のクエリのテスト
        $params['q'] = 'af af af af af';
        $resp = $this->apiGetBaseResponse($params);
        $resp->assertSuccessful();
        if(count($resp->decodeResponseJson()) === $singleCnt){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
        // ソートチェック
        $params['sortKey'] = 'pageView';
        $params['order'] = 'desc';
        $resp = $this->apiGetBaseResponse($params);
        $resp->assertSuccessful();
        $max = PHP_INT_MAX;
        foreach($resp->decodeResponseJson()['data'] as $rec){
            if(intval($rec['cnt']) <= $max){
                $max = intval($rec['cnt']);
            }else{
                $this->assertTrue(false);
            }
        }
        $this->assertTrue(true);
        // 件数テスト
        $params['count'] = '10';
        $resp = $this->apiGetBaseResponse($params);
        if(count($resp->decodeResponseJson()) === 10){
            $this->assertTrue(true);
        }
        // ページングテスト
        $params['q'] = 'a';
        $params['count'] = '100';
        $respJson = $this->apiGetBaseResponse($params)->decodeResponseJson()['data'];
        $params['count'] = '10';
        $params['current'] = '2';
        $respJson2 = $this->apiGetBaseResponse($params)->decodeResponseJson()['data'];
        $this->assertEquals(array_slice($respJson, 10, 10), $respJson2);
    }
    public function testApiPageIndexDetail()
    {
        $params = [
            'q' => 'af cb',
            'count' => '100',
            'current' => '1',
            'pb' => '2019-12-06',
            'pe' => '2019-12-29',
        ];
        $pb = Carbon::parse($params['pb']);
        $pe = Carbon::parse($params['pe']);
        foreach($this->apiGetBaseResponse($params)->decodeResponseJson()['data'] as $x){
            if($pb > Carbon::parse($x['updated_at']) || Carbon::parse($x['updated_at']) > $pe){
                $this->assertTrue(false);
            }
        }
        $this->assertTrue(true);
        $params['pb'] = '2019-12-30';
        $this->assertEquals(count($this->apiGetBaseResponse($params)
                                ->decodeResponseJson()), 0);
        foreach(\App\Page::all() as $x){
            $x->delete();
        }
        $params = [
            'q' => 'af cb',
            'count' => '100',
            'current' => '1',
            'pb' => '2019-12-06',
            'pe' => '2019-12-29',
        ];
        $this->assertEquals(count($this->apiGetBaseResponse($params)->decodeResponseJson()), 0);
        $this->setup();
    }
}
