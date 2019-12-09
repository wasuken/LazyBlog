<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //
    public function index(Request $req)
    {
        $req->validate([
            'writer' => 'exists:users,name',
        ]);
        $pages = DB::table('pages');
        // $page_mosts = \App\User::where('');
        $all_comments = \App\PageComment::all();
        if(isset($req->writer)){
            $pages = $pages
                   ->join('users', 'users.id', 'pages.user_id')
                   ->select('pages.*', 'users.name')
                   ->where('users.name', $req->writer);
        }
        return view('pages.index', [
            'pages' => $pages->orderBy('created_at', 'desc')->paginate(15),
            'writer' => $req->writer,
            'all_comments' => $all_comments,
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
        return view('pages.show', ['page' => $page, 'comments' => $comments, 'tags' => $tags]);
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
