<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use App\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::all();
        $jobs->load('user');
        return view('jobs.index', compact('jobs'));
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
            return view('jobs.create');
        }

        // if (Auth::check()) {
        //     return view('jobs.create');
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
    public function store(JobRequest $request)
    {
        $job = new Job;
        $job->company  = $request->company;
        $job->money  = $request->money;
        $job->place  = $request->place;
        $job->near  = $request->near;
        $job->place_path  = $request->place_path;
        $job->work_content  = $request->work_content;
        $job->feature  = $request->feature;
        $job->call  = $request->call;
        $job->apply  = $request->apply;
        $job->period  = $request->period;
        $job->user_id  = Auth::id();
        if ($request->file('image')) {
            $image_path = $request->file('image')->store('public/images/job/');
            $job->image_path = basename($image_path);
        }
        $job->save();
        // create() を使用して新規投稿を保存しましょう。
        return redirect()->route('jobs.index');
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
        $job = Job::find($id);
        if (!isset($job->image_path)) {
            $job->image_path = "no_image.jpg";
        }
        return view('jobs.show', compact('job', 'user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id);
        if (Auth::id() !== $job->user_id) {
            return abort(404);
        }

        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JobRequest $request, $id)
    {
        $user_id = Auth::id();
        $job = Job::find($id);
        if (Auth::id() !== $job->user_id) {
            return abort(404);
        }
        $job->company  = $request->company;
        $job->money = $request->money;
        $job->place = $request->place;
        $job->near = $request->near;
        $job->work_content = $request->work_content;
        $job->feature  = $request->feature;
        $job->call  = $request->call;
        $job->apply  = $request->apply;
        $job->period  = $request->period;
        if ($request->file('image')) {
            Storage::delete('public/images/job/' . $job->image_path);
            $image_path = $request->file('image')->store('public/images/job/');
            $job->image_path = basename($image_path);
        }

        $job->save();
        return view('jobs.show', compact('job', 'user_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::find($id);
        if (Auth::id() !== $job->user_id) {
            return abort(404);
        }
        Storage::delete('public/images/job/' . $job->image_path);
        $job->delete();
        return redirect()->route('jobs.index');
    }
}
