<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    //
    public function index(Request $req)
    {
        $req->validate([
            'writer' => 'exists:users,name',
        ]);
        $pages = DB::table('pages');
        if(isset($req->writer)){
            $pages = $pages
                   ->join('users', 'users.id', 'pages.user_id')
                   ->select('pages.*', 'users.name')
                   ->where('users.name', $req->writer);
        }
        return view('pages.index', [
            'pages' => $pages->orderBy('created_at')->paginate(15),
            'writer' => $req->writer,
        ]);
    }
    public function show(Request $req)
    {
        $req->validate([
            'id' => 'required|exists:pages,id',
        ]);
        $page = \App\Page::find($req->id);
        $comments = \App\PageComment::where('page_id', $page->id);
        $tags = DB::table('tags')
              ->join('page_tags', 'page_tags.tag_id', 'tags.id')
              ->select('tags.name', 'page_tags.page_id')
              ->where('page_tags.page_id', $page->id)
              ->get();
        return view('pages.show', ['page' => $page, 'comments' => $comments, 'tags' => $tags]);
    }
}
