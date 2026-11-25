@extends('layouts.main')

@section('pagetitle')
    Dashboard
@endsection

@section('content')
    <!-- BEGIN TEGELS MET AANTALLEN -->
    <div class="col s12 m6 l3">
        <div class="card cyan darken-1 white-text tile">
            <div class="card-content">
                <p class="tile-number">{{ $userCount }}</p>
                <p class="tile-label">Gebruikers</p>
                <p class="tile-info">waarvan {{ $adminCount }} admin(s)</p>
            </div>
        </div>
    </div>
    <div class="col s12 m6 l3">
        <div class="card cyan darken-1 white-text tile">
            <div class="card-content">
                <p class="tile-number">{{ $threadCount }}</p>
                <p class="tile-label">Threads</p>
                <p class="tile-info">waarvan {{ $emptyThreadCount }} zonder topics</p>
            </div>
        </div>
    </div>
    <div class="col s12 m6 l3">
        <div class="card cyan darken-1 white-text tile">
            <div class="card-content">
                <p class="tile-number">{{ $topicCount }}</p>
                <p class="tile-label">Topics</p>
                <p class="tile-info">waarvan {{ $unansweredTopicCount }} onbeantwoord</p>
            </div>
        </div>
    </div>
    <div class="col s12 m6 l3">
        <div class="card cyan darken-1 white-text tile">
            <div class="card-content">
                <p class="tile-number">{{ $replyCount }}</p>
                <p class="tile-label">Replies</p>
                <p class="tile-info">gemiddeld {{ number_format($averageReplies, 1, ',', '.') }} per topic</p>
            </div>
        </div>
    </div>
    <!-- EINDE TEGELS MET AANTALLEN -->

    <!-- BEGIN AANDACHT NODIG -->
    <div class="col s12">
        <div class="card cyan lighten-5">
            <div class="card-content">
                <span class="card-title">Aandacht nodig</span>
                <div class="row last-row">
                    <div class="col s12 l6">
                        <h6 class="title">Topics zonder reply</h6>
                        <div class="collection">
                            @forelse($unansweredTopics as $topic)
                            <a href="{{ route('topics.show', ['id' => $topic->id]) }}" class="collection-item collection-link">
                                <span class="title">{{ $topic->title }}</span>
                                <span class="tile-meta">{{ $topic->author?->name ?? 'Verwijderde gebruiker' }} op {{ $topic->created_at->format('d-m-Y H:i') }}</span>
                            </a>
                            @empty
                            <div class="collection-item">Alle topics hebben een reply.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col s12 l6">
                        <h6 class="title">Threads zonder topics</h6>
                        <div class="collection">
                            @forelse($emptyThreads as $thread)
                            <a href="{{ route('threads.show', ['id' => $thread->id]) }}" class="collection-item collection-link">
                                <span class="title">{{ $thread->title }}</span>
                                <span class="tile-meta">{{ $thread->author?->name ?? 'Verwijderde gebruiker' }} op {{ $thread->created_at->format('d-m-Y H:i') }}</span>
                            </a>
                            @empty
                            <div class="collection-item">Alle threads hebben topics.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- EINDE AANDACHT NODIG -->

    <!-- BEGIN LAATSTE REPLIES -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Laatste replies</span>
                <div class="collection">
                    @forelse($latestReplies as $reply)
                    <a href="{{ route('topics.show', ['id' => $reply->topic_id]) }}" class="collection-item collection-link">
                        <span class="title">{{ Str::limit(html_entity_decode(strip_tags($reply->content)), 100) }}</span>
                        <span class="tile-meta">{{ $reply->author?->name ?? 'Verwijderde gebruiker' }} op {{ $reply->created_at->format('d-m-Y H:i') }} in: {{ $reply->topic?->title ?? 'Verwijderde topic' }}</span>
                    </a>
                    @empty
                    <div class="collection-item">Er zijn nog geen replies.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- EINDE LAATSTE REPLIES -->

    <!-- BEGIN NIEUWE TOPICS -->
    <div class="col s12 l6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Nieuwe topics</span>
                <div class="collection">
                    @forelse($latestTopics as $topic)
                    <a href="{{ route('topics.show', ['id' => $topic->id]) }}" class="collection-item collection-link">
                        <span class="title">{{ $topic->title }}</span>
                        <span class="tile-meta">{{ $topic->author?->name ?? 'Verwijderde gebruiker' }} op {{ $topic->created_at->format('d-m-Y H:i') }} in: {{ $topic->thread?->title ?? 'Verwijderde thread' }}</span>
                    </a>
                    @empty
                    <div class="collection-item">Er zijn nog geen topics.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- EINDE NIEUWE TOPICS -->

    <!-- BEGIN NIEUWE GEBRUIKERS -->
    <div class="col s12 l6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Nieuwe gebruikers</span>
                <div class="collection">
                    @foreach($latestUsers as $user)
                    <div class="collection-item">
                        <span class="title">{{ $user->name }}</span>
                        @if ($user->isAdmin())
                        <span class="status-badge status-solved tile-badge">admin</span>
                        @endif
                        <span class="tile-meta">{{ $user->email }} sinds {{ $user->created_at->format('d-m-Y H:i') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- EINDE NIEUWE GEBRUIKERS -->
@endsection
