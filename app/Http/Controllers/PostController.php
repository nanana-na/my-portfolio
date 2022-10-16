<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
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
            return view('posts.create');
        }

        // if (Auth::check()) {
        //     return view('posts.create');
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
    public function store(PostRequest $request)
    {
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $post = new Post;
        $post->group  = $request->group;
        $post->place  = $request->place;
        $post->date  = $request->date;
        $post->icon  = $request->icon;
        $post->time   = $request->time;
        $post->body   = $request->body;
        $user = User::whereNumber($request->number)->first();
        $post->user_id  = $user->id;
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/images/circle/');
            $post->image_path = basename($image_path);
        }
        $post->save();
        // create() を使用して新規投稿を保存しましょう。
        return redirect()->route('posts.index');
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
        $post = Post::find($id);
        if (!isset($post->image_path)) {
            $post->image_path = "no_image.jpg";
        }
        return view('posts.show', compact('post', 'user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('posts.index');
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $user_id = Auth::id();
        $post = Post::find($id);
        if ($user_id !== $post->user_id) {
            return abort(404);
        }
        $post->group  = $request->group;
        $post->place  = $request->place;
        $post->date  = $request->date;
        $post->time  = $request->time;
        $post->body   = $request->body;
        $post->user_id  = Auth::id();
        if ($request->file('image')) {
            Storage::delete('public/images/' . $post->image_path);
            $image_path = $request->file('image')->store('public/images/circle/');
            $post->image_path = basename($image_path);
        }
        $post->save();
        return view('posts.show', compact('post', 'user_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (Auth::id() !== $post->user_id) {
            return abort(404);
        }
        Storage::delete('public/images/circle/' . $post->image_path);
        $post->delete();
        return redirect()->route('posts.index');
    }
}
