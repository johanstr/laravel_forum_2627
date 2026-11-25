<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Toon het dashboard voor de admin.
     */
    public function index(): View
    {
        // Tegels met aantallen
        $userCount = User::count();
        $adminCount = User::where('is_admin', true)->count();
        $threadCount = Thread::count();
        $emptyThreadCount = Thread::doesntHave('topics')->count();
        $topicCount = Topic::count();
        $unansweredTopicCount = Topic::doesntHave('replies')->count();
        $replyCount = Reply::count();
        $averageReplies = $topicCount > 0 ? $replyCount / $topicCount : 0;

        // Aandacht nodig: wat het langst wacht staat bovenaan
        $unansweredTopics = Topic::with('author')->doesntHave('replies')->oldest()->take(5)->get();
        $emptyThreads = Thread::with('author')->doesntHave('topics')->oldest()->take(5)->get();

        // Nieuwste eerst
        $latestReplies = Reply::with(['author', 'topic'])->latest()->take(5)->get();
        $latestTopics = Topic::with(['author', 'thread'])->latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();

        return view('dashboard', compact(
            'userCount',
            'adminCount',
            'threadCount',
            'emptyThreadCount',
            'topicCount',
            'unansweredTopicCount',
            'replyCount',
            'averageReplies',
            'unansweredTopics',
            'emptyThreads',
            'latestReplies',
            'latestTopics',
            'latestUsers',
        ));
    }
}
