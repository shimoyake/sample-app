<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
        $user = \Auth ::user();
        //メモ一覧を取得
        //whereで条件を指定できる（指定したuse_idのみ取得）
        //->whereで'かつ'の条件（指定したuser_idかつstatus1の時）
        //orderByで並べ順を指定 ASC=大きい順、DESC=小さい順
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();

        return view('home', compact('user', 'memos'));
    }

    public function create()
    {
        //変数 $userでログイン中のユーザー情報を渡す
        $user = \Auth ::user();
        //compactで受け取っユーザー情報をviewで表示させる
        return view('create', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す

        $memo_id = Memo::insertGetId([
            'content' => $data['content'],
             'user_id' => $data['user_id'], 
             'status' => 1
        ]);
        
        // リダイレクト処理
        return redirect()->route('home');
    }
}
