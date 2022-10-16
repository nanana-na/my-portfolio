<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FreeRequest;
use App\Free;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class FreeController extends Controller
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
        $frees = new Free;
        $frees = Free::all()->sortBy('number');
        $frees->load('user');
        $frees->load('comments');
        return view('frees.index', compact('frees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user()->number;
        if ($user == '20000000') {
            return view('frees.create');
        } else {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FreeRequest $request)
    {
        $free = new Free;
        $free->title  = $request->title;
        $free->number   = $request->number;
        $free->user_id  = Auth::id();
        $free->save();
        // create() を使用して新規投稿を保存しましょう。
        return redirect()->route('frees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::id();
        $free = Free::find($id);
        $free->load('user', 'comments');
        return view('frees.show', compact('free', 'user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $free = Free::find($id);
        if (Auth::id() !== $free->user_id) {
            return redirect()->route('frees.index');
        }

        return view('frees.edit', compact('free'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FreeRequest $request, $id)
    {
        $user_id = Auth::id();
        $free = Free::find($id);
        if (Auth::id() !== $free->user_id) {
            return abort(404);
        }
        $free->title  = $request->title;
        $free->number  = $request->number;
        $free->save();
        return view('frees.show', compact('free', 'user_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $free = Free::find($id);
        $free->delete();
        return redirect()->route('frees.index');
    }
}
