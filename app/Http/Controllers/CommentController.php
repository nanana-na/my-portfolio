<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Comment;
use App\Free;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        $free = Free::find($request->free_id);  //まず該当の投稿を探す
        $comment = new Comment;              //commentのインスタンスを作成
        $comment->body    = $request->body;
        $comment->user_id = Auth::id();
        $comment->free_id = $request->free_id;
        $comment->save();
        $user_id = Auth::id();
        return
            redirect()->route('frees.show', compact('free'));
        // view('frees.show', compact('free', 'user_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $comment = Comment::find($request->comment_id);
        $comment->delete();
        return redirect('/frees');
    }
    public function reduce()
    {
        if (Auth::user()->number !== '20000000') {
            return redirect()->route('friends.index')->with('flash_message', 'エラーが出ました');
        }
        $today = new Carbon('today');
        $today->subDays(3);
        $comment = Comment::where('created_at', '<', $today);
        $comment->delete();
        return redirect()->route('frees.index');
    }
}
