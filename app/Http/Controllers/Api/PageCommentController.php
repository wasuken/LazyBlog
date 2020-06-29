<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        //
        $req->validate([
            'order' => 'in:asc,desc',
        ]);
        if(!isset($req->order)){
            $req->order = 'desc';
        }
        return \App\PageComment::select('page_id', 'handle_name', 'comment', 'updated_at')
                  ->orderBy('updated_at', $req->order)
                  ->get();
    }
}
