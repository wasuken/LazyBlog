<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageAccesslogController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'token' => 'required|exists:users,api_token',
        ]);
        $logs = DB::table('page_accesslogs')->Select('ip_address', 'user_agent', 'refer', 'url')->get();

        return $logs;
    }
}
