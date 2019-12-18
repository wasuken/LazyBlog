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
        $parser = new \cebe\markdown\Markdown();
        $page = \App\Page::create([
            'title' => $req->title,
            'body' => $req->type === "html" ? $req->body : $parser->parse($req->body),
            'user_id' => \App\User::where('api_token', $req->token)->first()->id,
        ]);
        if(isset($req->tags)){
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
        }
        return ['id'=> $page->id,
                'title' => $page->title,
                'body' => $page->body,];
    }
}
