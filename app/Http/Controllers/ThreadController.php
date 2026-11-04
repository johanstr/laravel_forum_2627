<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $threads = Thread::with(['author'])
            ->withCount('topics')   // $thread->topics_count
            ->get();

        return view('thread.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $thread = Thread::with([
            'topics' => fn ($query) => $query->withCount('replies'),
            'topics.author'
        ])->findOrFail($id);
//        $thread = Thread::where('id', $id)->first();
//        $topics = Topic::with(['author'])
//            ->withCount('replies')
//            ->where('thread_id', $id)
//            ->get();

        return view('thread.show', compact('thread'));
//        return view('thread.show', compact(['thread', 'topics']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
