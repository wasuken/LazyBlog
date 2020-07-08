<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageController extends Controller
{
    public function index(Request $req)
    {
        $maxId = \App\Page::max('id');
        if(empty($maxId)){
            return [];
        }
        $validator = \Validator::make($req->all(), [
            // 検索に使うクエリ
            'q' => 'min:1|max:200',
            // タグ検索に引っかかったものを優先的に表示する
            'tag' => 'min:1|max:200',
            // 記事投稿者で検索を行う。
            'writer' => 'min:1|max:200',
            // ソートに使うキー
            'sortKey' => 'in:pageView,date',
            // 並び順
            'order' => 'in:asc,desc',
            // 最大件数(100まで)
            'count' => 'integer|min:1|max:100',
            // ページ数。currentの分だけ検索結果の先頭を無視する
            'current' => 'integer|min:1',
            // pbで指定された日付以上の日付を持つ記事を対象にする。
            'pb' => 'date',
            // peで指定された日付以下の日付を持つ記事を対象にする。
            'pe' => 'date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        if(!isset($req->count)){
            $req->count = 30;
        }

        // 何もしていされなければ一週間ぐらいのスパン
        $latest = \App\Page::orderBy('updated_at', 'desc')->first();
        $pb = $latest->updated_at->addWeeks(-1);
        if(isset($req->pb)){
            $pb = Carbon::parse($req->pb);
        }
        $pe = clone $pb;
        $pe->addWeeks(1);
        if(isset($req->pe)){
            $pe = Carbon::parse($req->pe);
        }
        if($pe <= $pb){
            return [];
        }

        if(!isset($req->current)){
            $req->current = 0;
        }else{
            // 添字調整
            $req->current -= 1;
        }
        $pages = DB::table('pages')
               ->where('pages.updated_at', '>=', $pb->format('Y-m-d H:i:s'))
               ->where('pages.updated_at', '<=', $pe->format('Y-m-d H:i:s'));
        $searchResult = array();
        // 全角スペースを半角スペースに置換する。
        $req->q = preg_replace('/　/', ' ', $req->q);
        $idList = array();
        if(!isset($req->tag)){
            $req->tag = '';
        }
        if(!isset($req->writer)){
            $req->writer = '';
        }
        $req->tag = preg_replace('/　/', ' ', $req->tag);
        $tagList = array_filter(preg_split('/\s+/', $req->tag),
                                function($x){ return !empty($x);});
        // or検索だけ実装する。
        $words = array_filter(preg_split('/\s+/', $req->q),
                              function($x){ return !empty($x);});
        if(!empty($tagList)){
            $pages = $pages->join('page_tags', 'page_tags.page_id', 'pages.id')
                           ->join('tags', 'tags.id', 'page_tags.tag_id')
                           ->whereIn('tags.name', $tagList)
                           ->select('pages.*');
        }
        if(!empty($req->writer)){
            $pages = $pages->join('users', 'users.id', 'pages.user_id')
                           ->where('users.name', $req->writer)
                           ->select('pages.*');
        }
        if(!empty($words)){
            foreach($words as $word){
                $likeQuery = '%' . mb_strtolower($word) . '%';
                $wSearch = $pages->where('body', 'like', $likeQuery);
                // grep検索
                // 絶対遅い
                foreach($wSearch->get() as $page){
                    $id = $page->id;
                    if(array_search($id, $idList) === false){
                        $searchResult = array_merge($searchResult, array($page));
                        $idList = array_merge($idList, array($id));
                    }
                }
            }
        }else{
            foreach($pages->get() as $page){
                $id = $page->id;
                if(array_search($id, $idList) === false){
                    $searchResult = array_merge($searchResult, array($page));
                    $idList = array_merge($idList, array($id));
                }
            }
        }

        // パターンとしては、
        // 1. そもそもsortKeyがない場合 -> デフォルトで日付最新で返す
        // 2. sortKeyはあるけど、orderがない場合 ->
        // 昇順降順の指定がない場合は昇順。

        // 1
        if(!isset($req->sortKey)){
            $req->sortKey = 'date';
        }
        // 2(1も通る)
        if(!isset($req->order)){
            $req->order = 'asc';
        }

        switch($req->sortKey){
        case 'date':
            if(isset($req->date)){
                usort($searchResult, function($a, $b){
                    return $a->updated_at <=> $b->updated_at;
                });
            }
        case 'pageView':
            $searchResultExtend = array();
            // ページ閲覧数を算出する。
            foreach($searchResult as $v){
                $count = \App\PageAccessLog::where('url', 'like',
                                                   '%page?id=' . $v->id . '%')
                       ->count();
                $searchResultExtend = array_merge($searchResultExtend,
                                                  array(array('id' => $v->id,
                                                              'data' => $v,
                                                              'cnt' => $count)));
            }
            usort($searchResultExtend, function($a, $b){
                return $a['cnt'] <=> $b['cnt'];
            });
            $searchResult = array();
            $users = \App\User::select('id', 'name')->get();
            $idNamePairs = array();
            foreach($users as $u){
                $idNamePairs[$u->id] = $u->name;
            }
            foreach($searchResultExtend as $v){
                $v2 = get_object_vars($v['data']);
                $v2['cnt'] = $v['cnt'];
                $v2['user_name'] = $idNamePairs[intval($v2['user_id'])];
                unset($v2['user_id']);
                $searchResult = array_merge($searchResult,
                                            array($v2));
            }
        }
        // 降順
        if($req->order === 'desc'){
            $searchResult = array_reverse($searchResult);
        }

        $result = array();

        $result['data'] = array_map(function($x){
            $tags = \App\Tag::join('page_tags', 'tags.id', 'page_tags.tag_id')
                  ->where('page_tags.page_id', $x['id'])->get();
            $tagNames = array();
            foreach($tags as $tag){
                $tagNames = array_merge($tagNames, array($tag->name));
            }
            $x['tags'] = $tagNames;
            return $x;
        }, array_slice($searchResult, ($req->current * $req->count), $req->count));

        $result['pageCount'] = ceil(count($searchResult) / $req->count);

        return $result;
    }
    //
    public function store(Request $req)
    {
        $validator = \Validator::make($req->all(), [
            'title' => 'required|min:1|max:200|unique:pages,title',
            'body' => 'required|min:1',
            'tags' => 'array',
            'token' => 'required|exists:users,api_token',
            'type' => 'in:md,html',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }
        if(!isset($req->type)){
            $req->type = "html";
        }
        $parser = new \cebe\markdown\GithubMarkdown();
        $page = \App\Page::create([
            'title' => $req->title,
            'body' => $req->type === "html" ? $req->body : $parser->parse($req->body),
            'user_id' => \App\User::where('api_token', $req->token)->first()->id,
        ]);
        \App\Tag::tagsCreate($req->tags, $page->id);
        return ['id'=> $page->id,
                'title' => $page->title,
                'body' => $page->body,];
    }
}
