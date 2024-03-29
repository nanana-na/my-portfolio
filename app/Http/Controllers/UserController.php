<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\YetRequest;
use App\Http\Requests\ImageRequest;
use App\User;
use App\Yet;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(YetRequest $request)
    {
        $user = new User;
        $user->name = Str::random(7);
        $user->number = $request->number;
        $user->password = Hash::make($request->password);
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/images/user/');
            $user->image_path = basename($image_path);
        }
        $user->save();
        return redirect()->route('creates.index');
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
        $user = User::find($id);
        if (Auth::id() !== $user->id) {
            return redirect()->route('user.index');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        if (Auth::id() !== $user->id) {
            return abort(404);
        }
        if ($user->number < 12000000) {
            return redirect()->route('posts.index',)->with('flash_message', 'このアカウントの名前,画像は変えられません');
        }
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/images/user/');
            $user->image_path = basename($image_path);
            $user->save();
            return redirect()->route('posts.index');
        }

        $new_time = (new DateTime)->format('Ymd');
        $change_time = $user->updated_at->format('Ymd');
        if ($new_time == $change_time) {
            $user->count += 1;
        } else {
            $user->count = 0;
        }
        if ($user->count > 4) {
            return redirect()->route('posts.index',)->with('flash_message', '名前、画像は1日5回までしか変えられません');
        }
        $user->name = $request->name;
        $user->save();
        return redirect()->route('posts.index');
    }


    public function updateimage(ImageRequest $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if (Auth::id() !== $user->id) {
            return abort(404);
        }
        if ($user->number < 12000000) {
            return redirect()->route('posts.index',)->with('flash_message', 'このアカウントの名前,画像は変えられません');
        }

        $new_time = (new DateTime)->format('Ymd');
        $change_time = $user->updated_at->format('Ymd');
        if ($new_time == $change_time) {
            $user->count += 1;
        } else {
            $user->count = 0;
        }
        if ($user->count > 4) {
            return redirect()->route('posts.index',)->with('flash_message', '名前、画像は1日5回までしか変えられません');
        }
        if ($request->file('image')) {
            Storage::delete('public/images/user/' . $user->image_path);
            $image_path = $request->file('image')->store('public/images/user/');
            $user->image_path = basename($image_path);
            $user->save();
        }
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (Auth::id() !== $user->user_id) {
            return abort(404);
        }
        Storage::delete('public/images/user/' . $user->image_path);
        $user->delete();
        return redirect()->route('users.index');
    }
}
