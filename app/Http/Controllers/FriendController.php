<?php

namespace App\Http\Controllers;

use App\Ask;
use Illuminate\Http\Request;
use App\Http\Requests\FriendRequest;
use App\Friend;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;

class FriendController extends Controller
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
        $user_id  = Auth::id();
        $user = User::find($user_id);
        $friends = new Friend;
        $now = new DateTime();
        $today = Carbon::today();
        $time = $now->format('H:i:00');
        $today = Carbon::today();
        $friends = Friend::all()->sortBy('enjoy_time');
        $friends = $friends->where('enjoy_day', '=', $today)->where('enjoy_time', '>', $time)->where('state', '0');
        $friends->day = $today;
        $friends->day_name = "今日";
        $friends->load('user');
        return view('friends.index', compact('friends', 'user', 'user_id'));
    }

    public function tomorrow(Request $request)
    {
        $user_id  = Auth::id();
        $user = User::find($user_id);
        $friends = new Friend;
        $today = Carbon::today();
        if ($request->day == 0) {
            return redirect()->route('friends.index');
        }
        if ($request->day == 1) {
            $today = $today->addDay();
            $day_name = "明日";
        }
        if ($request->day == 2) {
            $today = $today->addDays(2);
            $day_name = "明後日";
        }
        $friends = Friend::all()->sortBy('enjoy_time');
        $friends = $friends->where('enjoy_day', '=', $today)->where('state', '0');
        $friends->day = $today;
        $friends->day_name = $day_name;
        $friends->load('user');
        return view('friends.index', compact('friends', 'user', 'user_id'));
    }
    public function list()
    {
        $user_id  = Auth::id();
        $user = User::find($user_id);
        $friends = new Friend;
        $friends = Friend::all()->sortBy('enjoy_time')->sortBy('enjoy_day');
        $friends = $friends->where('user_id', $user_id);
        $friends->load('user');
        return view('lists.index', compact('friends', 'user', 'user_id'));
    }
    public function asklist()
    {
        $user_id  = Auth::id();
        $user = User::find($user_id);
        $user->chat_alert = 0;
        $user->save();
        $asks = new Ask;
        $asks = Ask::where('ask_id', $user_id)->get();
        $asks = $asks->where('evaluation', 2)->sortBy('evaluation');
        $asks->load('friend');
        $asks->load('user');
        return view('lists.asklist', compact('asks', 'user', 'user_id'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            $user = User::find(Auth::id());
            if ($user->image_path == NULL) {
                return redirect()->route('friends.index')->with('flash_message', 'プロフィール画像を設定してください');
            }
            return view('friends.create');
        } else {
            return redirect()->route('friends.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FriendRequest $request)
    {
        $user_id  = Auth::id();
        $user = User::find($user_id);
        if ($user->image_path == NULL) {
            return redirect()->route('friends.index')->with('flash_message', 'プロフィール画像を設定してください');
        }
        $today = Carbon::today();
        $friend = new Friend;
        $friend->body   = $request->body;
        $friend->user_id  = Auth::id();
        if ($request->day == "1") {
            $now = new DateTime();
            if ($request->time < $now->format('H:i:s')) {
                return redirect()->route('friends.create')->with('flash_message', '現在の時間より前は設定できません');
            }
            $friend->enjoy_day = $today;
        } elseif ($request->day == "2") {
            $today = $today->addDay();
            $friend->enjoy_day = $today;
        } elseif ($request->day == "3") {
            $today = $today->addDays(2);
            $friend->enjoy_day = $today;
        }
        if (Friend::where('user_id', $user_id)->where('enjoy_day', $today)->count() > 1) {
            return redirect()->route('friends.index')->with('flash_message', 'その日の予定は2つまでしか作れません。消すとまた作れます');
        }
        $friend->enjoy_time = $request->time;
        $friend->save();
        return redirect()->route('friends.index');
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
        $friend = Friend::find($id);
        $friend->load('asks');

        $ask_id =
            $friend->asks->where('ask_id', $user_id)->where('evaluation', 2)->first();

        if ($user_id == $friend->user_id || $ask_id !== NULL) {
            $friend->load('user', 'friendmessages');
            $asks = Ask::where('friend_id', $id)->where('evaluation', 2)->get();
            $asks->load('ask');
            return view('friends.show', compact('friend', 'user_id', 'asks'));
        }
        return redirect()->route('friends.index')->with('flash_message', '許可されていません');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $friend = Friend::find($id);
        if (Auth::id() !== $friend->user_id) {
            return redirect()->route('friends.index')->with('flash_message', 'エラーが出ました');
        }

        return view('friends.edit', compact('friend'));
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
        $friend = Friend::find($id);
        if (Auth::id() !== $friend->user_id) {
            return redirect('/error');
        }
        $friend->load('asks');
        $evaluation = $friend->asks->where('evaluation', '2')->first();
        if ($evaluation == NULL) {
            return redirect()->route('friends.show', $request->id)->with('flash_message', 'メンバーがいません');
        }
        $friend->state  = 1;
        $friend->save();
        return redirect()->route('friends.show', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $friend = Friend::find($id);
        if (Auth::id() !== $friend->user_id) {
            return redirect('/error');
        }
        $friend->delete();
        return redirect()->route('friends.index');
    }

    public function yesterday()
    {
        if (Auth::user()->number !== '20000000') {
            return redirect()->route('friends.index')->with('flash_message', 'エラーが出ました');
        }
        $today = new Carbon('today');
        $friend = Friend::where('enjoy_day', '<', $today);

        $friend->delete();
        return redirect()->route('friends.index');
    }
}
