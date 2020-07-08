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
            'type' => 'in:atom,rss2.0'
        ]);
        if(isset($req->type)){
            $pages = \App\Page::orderBy('updated_at', 'desc')
                   ->take(20)
                   ->get();
            $feed = Helper::pageToFeed($req->type, $pages);
            if(!empty($feed)){
                return response($feed, 200)
                    ->header('Content-Type', 'text/xml');
            }
        }
        return view('pages.index');
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
    public function destroy(Request $req)
    {
        $req->validate([
            'id' => 'required|exists:pages,id|is_author'
        ]);
        \App\Page::destroy($req->id);
        return redirect('/');
    }
    public function store(Request $req)
    {
        $req->validate([
            'title' => 'required|min:1|max:200|unique:pages,title',
            'body' => 'required|min:1',
            'tags' => 'array',
            'type' => 'in:md,html',
        ]);
        if(!isset($req->type)){
            $req->type = "html";
        }
        $parser = new \cebe\markdown\GithubMarkdown();
        $page = \App\Page::create([
            'title' => $req->title,
            'body' => $req->type === "html" ? $req->body : $parser->parse($req->body),
            'user_id' => Auth::user()->id,
        ]);
        \App\Tag::tagsCreate($req->tags, $page->id);
        return redirect('/');
    }
}
