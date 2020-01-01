<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageAccesslogController extends Controller
{
    //
    public function index()
    {
        return view('accesslogs.index', ['token' => Auth::user()->api_token]);
    }
}
