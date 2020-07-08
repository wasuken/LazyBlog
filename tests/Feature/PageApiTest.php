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
    public function simpleRequestCheck($params, $expectStatusCode)
    {
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }

        $this->assertEquals($expectStatusCode, $status);
    }
    public function simpleRequestChecks($dataSet)
    {
        foreach($dataSet as $v){
            $this->simpleRequestCheck($v[0], $v[1]);
        }
    }
    public function testApiPageQuery()
    {
        $this->simpleRequestChecks([
            [['q' => ''], 400],
            [['q' => Str::random(201)], 400],
            [['q' => Str::random(50)], 0],
        ]);
    }
    public function testApiPageTag()
    {
        $this->simpleRequestChecks([
            [['tag' => ''], 400],
            [['tag' => Str::random(201)], 400],
            [['tag' => Str::random(50)], 0],
        ]);
    }
    public function testApiPageWriter()
    {
        $this->simpleRequestChecks([
            [['writer' => ''], 400],
            [['writer' => Str::random(201)], 400],
            [['writer' => Str::random(50)], 0],
        ]);
    }
    public function testApiPageSortKey()
    {
        $this->simpleRequestChecks([
            [['sortKey' => ''], 400],
            [['sortKey' => Str::random(40)], 400],
            [['sortKey' => 'pageView'], 0],
            [['sortKey' => 'date'], 0],
        ]);
    }
    public function testApiPageOrder()
    {
        $this->simpleRequestChecks([
            [['order' => ''], 400],
            [['order' => Str::random(40)], 400],
            [['order' => 'asc'], 0],
            [['order' => 'desc'], 0],
        ]);
    }
    public function testApiPageCount()
    {
        $this->simpleRequestChecks([
            [['count' => ''], 400],
            [['count' => 0], 400],
            [['count' => 101],400],
            [['count' => Str::random(20) . 'z'],400],
            [['count' => 50], 0],
        ]);
    }
    public function testApiPageCurrent()
    {
        $this->simpleRequestChecks([
            [['current' => ''], 400],
            [['current' => 0], 400],
            [['current' => 1],0],
        ]);
        $params = [
            'current' => '',
        ];
        // 空白はエラー
        $status = $this->apiGetBaseResponse($params)->decodeResponseJson()['status'] || 0;
        $this->assertEquals(400, $status);

        // 正常値
        $params['count'] = 50;
        $status = $this->apiGetBaseResponse($params)->decodeResponseJson()['status'] || 0;
        $this->assertEquals(400, $status);
    }
    public function testApiPagePb()
    {
        $this->simpleRequestChecks([
            [['pb' => ''], 400],
            [['pb' => Str::random(40) .  "^1230kdsal;3"], 400],
            [['pb' => '2019-07-07'], 0],
        ]);
    }
    public function testApiPagePe()
    {
        $this->simpleRequestChecks([
            [['pe' => ''], 400],
            [['pe' => Str::random(40) .  "^1230kdsal;3"], 400],
            [['pe' => '2019-08-07'], 0],
        ]);
    }
    public function testApiPagePbPe()
    {
        $this->simpleRequestChecks([
            [['pb' => '', 'pe' => '2019-08-07'], 400],
            [['pb' => Str::random(40) .  "^1230kdsal;3", 'pe' => '2019-08-07'], 400],
            [['pb' => '2019-07-07', 'pe' => '2019-08-07'], 0],
        ]);
    }
    public function testApiPageQueryTag()
    {
        $params = [
            'q' => 'af',
            'tag' => 'JavaScript',
        ];
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }
        $this->assertEquals(0, $status);

        foreach($j['data'] as $rec){
            if((array_search('JavaScript', array_values($rec['tags'])) === false)
               || (preg_match('/af/', mb_strtolower($rec['body'])) <= 0)){
                $this->assertTrue(false);
            }
        }
        $this->assertTrue(true);
    }
    public function testApiPageQuerySortKeyOrderPageView()
    {
        $params = [
            'q' => 'af',
            'tag' => 'JavaScript',
            'order' => 'desc',
            'sortKey' => 'pageView',
        ];
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }
        $this->assertEquals(0, $status);

        $lastValue = 999999999;
        foreach($j['data'] as $v){
            if($lastValue < $v['cnt']){
                $this->assertTrue(false);
            }
            $lastValue = $v['cnt'];
        }
        $this->assertTrue(true);

        $params['order'] = 'asc';
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }
        $this->assertEquals(0, $status);

        $lastValue = -1;
        foreach($j['data'] as $v){
            if($lastValue > $v['cnt']){
                $this->assertTrue(false);
            }
            $lastValue = $v['cnt'];
        }
        $this->assertTrue(true);
    }
    public function testApiPageQuerySortKeyOrderDate()
    {
        $params = [
            'q' => 'af',
            'tag' => 'JavaScript',
            'order' => 'desc',
            'sortKey' => 'date',
        ];
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }
        $this->assertEquals(0, $status);

        $lastValue = Carbon::parse('2100-01-01');
        foreach($j['data'] as $v){
            if($lastValue < Carbon::parse($v['updated_at'])){
                $this->assertTrue(false);
            }
            $lastValue = Carbon::parse($v['updated_at']);
        }
        $this->assertTrue(true);

        $params['order'] = 'asc';
        $j = $this->apiGetBaseResponse($params)->decodeResponseJson();
        $status = 0;
        if(array_key_exists('status', $j)){
            $status = $j['status'];
        }
        $this->assertEquals(0, $status);

        $lastValue = Carbon::parse('1990-01-01');
        foreach($j['data'] as $v){
            if($lastValue > Carbon::parse($v['updated_at'])){
                $this->assertTrue(false);
            }
            $lastValue = Carbon::parse($v['updated_at']);
        }
        $this->assertTrue(true);
    }
}
