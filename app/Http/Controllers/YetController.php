<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\YetRequest;
use App\User;
use App\Yet;
use App\Mail\Test;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class YetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $yets = Yet::all();
        return view('yets.index', compact('yets'));
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
        $yet = new Yet;
        $yet->name = Str::random(7);
        $yet->number = $request->number;
        $yet->password = Hash::make($request->password);
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/images/yet/');
            $yet->image_path = basename($image_path);
        } else {
            return view('/error');
        }
        $yet->save();
        return redirect()->route('posts.index')->with('flash_message', '認証されるまでしばらくお待ちください。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $yet = Yet::find($id);
        if (empty($yet->image_path)) {
            $yet->image_path = "no_image.jpg";
        }
        return view('yets.show', compact('yet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!isset(Auth::user()->number)) {
            return redirect('/error')->with('flash_message', 'エラーが出ました');
        }
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $yet = Yet::find($id);
        Storage::delete('public/images/yetuser/' . $yet->image_path);
        $user = new User;
        $user->name = $yet->name;
        $user->number = $yet->number;
        $user->password = $yet->password;
        $user->save();
        $yet->delete();
        return redirect()->route('yets.index');
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
    public function destroy($id)
    {
        $yet = Yet::find($id);
        Storage::delete('public/images/yetuser/' . $yet->image_path);
        $yet->delete();
        return redirect()->route('yets.index');
    }
}
