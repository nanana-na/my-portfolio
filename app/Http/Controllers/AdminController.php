<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminupRequest;
use App\Http\Requests\YetRequest;
use App\Http\Requests\MakeRequest;
use App\User;
use App\Yet;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
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
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $users = User::all();
        return view('admins.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $pas = Str::random(5);
        return view('admins.create', compact('pas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $user = new User;
        $user->name = $request->name;
        $user->number = $request->number;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $user = User::find($id);
        return view('admins.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $user = User::find($id);
        $user->pas = Str::random(5);
        return view('admins.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminupRequest $request, $id)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $user = User::find($id);
        $user->name = $request->name;
        $user->count = $request->count;
        $user->ask_count = $request->ask_count;
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('admins.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_number = Auth::user()->number;
        if ($user_number !== '20000000') {
            return  redirect('/error');
        }
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admins.index');
    }
}
