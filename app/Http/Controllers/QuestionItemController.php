<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Question;
use App\QuestionItem;


class QuestionItemController extends Controller
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
    public function store(Request $request)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $question_item = new QuestionItem;              //commentのインスタンスを作成
        $question_item->option    = $request->option;
        $question_item->question_id = $request->question_id;
        $question_item->save();
        return
            redirect()->route('questions.index');
        // view('questions.show', compact('free', 'user_id'));
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
        $question_item = QuestionItem::find($id);
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        return view('questionitems.edit', compact('question_item'));
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
        $user_id = Auth::id();
        $question_item = QuestionItem::find($id);
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $question_item->option  = $request->option;
        $question_item->save();
        return redirect()->route('questions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
