<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//モデル作成→ここに追記
use App\Models\Memo;
use App\Models\Tag;

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
        $user = \Auth::user();
        //メモ一覧を取得
        //whereで条件を指定できる（指定したuse_idのみ取得）
        //->whereで'かつ'の条件（指定したuser_idかつstatus1の時）
        //orderByで並べ順を指定 ASC=大きい順、DESC=小さい順
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        //dd($memos);
        return view('home', compact('user', 'memos'));
    }

    public function create()
    {
        //変数 $userでログイン中のユーザー情報を渡す
        $user = \Auth ::user();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        //compactで受け取っユーザー情報をviewで表示させる
        return view('create', compact('user', 'memos'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す
        
            //先にタグをインサート
            $tag_id = Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]);

        $memo_id = Memo::insertGetId([
            'content' => $data['content'],
             'user_id' => $data['user_id'], 
             'tag_id' => $tag_id,
             'status' => 1
        ]);
        
        // リダイレクト処理
        return redirect()->route('home');
    }

    public function edit($id){
        // 該当するIDのメモをデータベースから取得
        $user = \Auth::user();
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])
          ->first();
        //   dd($memo);
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        //取得したメモをViewに渡す
        //$tags = Tag::where(user_id, $user['id'])->get();
        return view('edit',compact('memo', 'user', 'memos'));
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        // dd($inputs);
        //whereでアップデートしたいメモを指定（忘れると全て更新されてしまうので注意）
        Memo::where('id', $id)->update(['content' => $inputs['content']]);
        return redirect()->route('home');
    }
}
