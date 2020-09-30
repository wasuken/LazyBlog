<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageCommentController extends Controller
{
    //
    public function index()
    {
        $comments = \App\PageComment::all();
        return view('comments.index', ['comments' => $comments]);
    }
    public function store(Request $req)
    {
        $req->validate([
            'user' => 'max:100|not_url',
            'comment' => 'required|min:1|max:3000|not_url',
            'id' => 'required|exists:pages,id',
        ]);
        \App\PageComment::create([
            'page_id' => $req->id,
            'handle_name' => (isset($req->user) && !empty($req->user))? $req->user: "guest",
            'comment' => $req->comment,
            'ip_address' => request()->ip(),
        ]);
        return redirect('/page?id=' . $req->id);
    }
}
