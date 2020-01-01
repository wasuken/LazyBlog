<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PageController extends Controller
{
    //
    public function store(Request $req)
    {
        $req->validate([
            'title' => 'required|min:1|max:200|unique:pages,title',
            'body' => 'required|min:1',
            'tags' => 'array',
            'token' => 'required|exists:users,api_token',
            'type' => 'in:md,html',
        ]);
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
