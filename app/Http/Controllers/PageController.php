<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class PageController extends Controller
{
    //
    public function index(Request $req)
    {
        $req->validate([
            'writer' => 'exists:users,name',
            'tag' => 'exists:tags,name',
        ]);
        $pages = DB::table('pages');
        $access_mosts_10 = \App\PageAccesslog::where('url', 'like', '%/page?id=%')
                         ->whereBetween('status_code', [200, 299])
                         ->select(DB::raw('count(*) as cnt, status_code, url'))
                         ->groupBy('url', 'status')
                         ->orderBy('cnt', 'desc')
                         ->take(10)
                         ->get();
        $page_mosts_10 = [];
        foreach($access_mosts_10 as $pal){
            preg_match('/id=(\w+)/', $pal->url, $match);
            $page_mosts_10 = array_merge($page_mosts_10,
                                         array(\App\Page::find($match[1])));
        }
        $all_comments = Helper::myOrderBy(new \App\PageComment, 'created_at', 'desc')
                      ->orderBy('id', 'desc')->take(10)->get();
        if(isset($req->writer)){
            $pages = $pages
                   ->join('users', 'users.id', 'pages.user_id')
                   ->select('pages.*', 'users.name')
                   ->where('users.name', $req->writer);
        }
        if(isset($req->tag)){
            $pages = $pages
                   ->join('page_tags', 'page_tags.page_id', 'pages.id')
                   ->join('tags', 'tags.id', 'page_tags.tag_id')
                   ->select('pages.*', 'tags.name')
                   ->where('tags.name', $req->tag);
        }
        return view('pages.index', [
            'pages' => Helper::myOrderBy($pages, 'created_at', 'desc')->orderBy('id', 'desc')->paginate(15),
            'writer' => $req->writer,
            'tag' => $req->tag,
            'all_comments' => $all_comments,
            'page_mosts_10' => $page_mosts_10,
        ]);
    }
    public function show(Request $req)
    {
        $req->validate([
            'id' => 'required|exists:pages,id',
        ]);
        $page = \App\Page::find($req->id);
        $comments = \App\PageComment::where('page_id', $page->id)->get();
        $tags = DB::table('tags')
              ->join('page_tags', 'page_tags.tag_id', 'tags.id')
              ->select('tags.name', 'page_tags.page_id')
              ->where('page_tags.page_id', $page->id)
              ->get();
        return view('pages.show', ['page' => $page,
                                   'comments' => $comments,
                                   'tags' => $tags]);
    }
    public function create()
    {
        return view('pages.create', []);
    }
    public function store(Request $req)
    {
        $req->validate([
            'title' => 'required|min:1|max:200|unique:pages,title',
            'body' => 'required|min:1',
            'tags' => 'required|array'
        ]);
        $page = \App\Page::create([
            'title' => $req->title,
            'body' => $req->body,
            'user_id' => Auth::user()->id,
        ]);
        foreach($req->tags as $tag){
            $target_tag = \App\Tag::where('name', $tag)->first();
            if($target_tag === null){
                $target_tag = \App\Tag::create([
                    'name' => $tag,
                ]);
            }
            \App\PageTag::create([
                'page_id' => $page->id,
                'tag_id' => $target_tag->id,
            ]);
        }
        return redirect('/');
    }
}
