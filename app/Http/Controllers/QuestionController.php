<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Question;
use App\QuestionItem;
use App\Http\Requests\QuestionRequest;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $questions = new Question;
        $questions = Question::withCount('question_items')->latest()->get();
        $questions->load('answers');
        return view('questions.index', compact('questions', 'user_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user = Auth::user()->number;
        if ($user == '20000000') {
            return view('questions.create');
        }

        // if (Auth::check()) {
        //     return view('questions.create');
        // }
        else {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $question = new Question;
        $question->title = $request->title;
        if ($request->limit !== NULL) {
            $question->limit = $request->limit;
        }
        $question->save();
        return redirect()->route('questions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        return view('questions.edit', compact('question'));
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
        $question = Question::find($id);
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $question->title  = $request->title;
        if ($request->limit !== NULL) {
            $question->limit = $request->limit;
        }
        $question->save();
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
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $question = Question::find($id);
        $question->delete();
        return redirect()->route('questions.index');
    }
}
