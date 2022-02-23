<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        //変数 $userでログイン中のユーザー情報を渡す
        $user = \Auth ::user();
        //compactで受け取っユーザー情報をviewで表示させる
        return view('create', compact('user'));
    }
}
