@extends('layouts.main')

@section('pagetitle')
    Topic - {{ $topic->title }}
@endsection

@section('content')
    <div class="col s12">
        <!-- BEGIN TOPIC -->
        <div class="card cyan lighten-5">
            <div class="card-content">
                <div class="collection">
                    <div class="collection-item row">
                        <div class="col s3">
                            <div class="avatar collection-link">
                                <div class="row">
                                    <div class="col s3">
                                        <img src="http://www.gravatar.com/avatar/fc7d81525f7040b7e34b073f0218084d?s=50" alt="" class="square">
                                    </div>
                                    <div class="col s9">
                                        <p class="user-name">{{ $topic->author->name }}</p>
                                    </div>
                                </div>
                                <p class="post-timestamp">
                                    Gepost op {{ $topic->created_at->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="col s9">
                            <div class="row last-row">
                                <div class="col s12">
                                    <h6 class="title">{{ $topic->title }}</h6>
                                    <p>
                                        {{ $topic->content }}
                                    </p>
                                </div>
                            </div>
                            <div class="row last-row block-timestamp">
                                <div class="col s6 push-s6">
                                    <p class="post-timestamp last-post-timestamp">
                                        Laatst aangepast op: {{ $topic->updated_at->format('d-m-Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- EINDE TOPIC -->

        <!-- BEGIN REPLY -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">Replies</span>
                <div class="collection">
                    @forelse($topic->replies as $reply)
                    <div class="collection-item row">
                        <div class="col s2">
                            <span class="reply-username">{{ $reply->author->name }}</span>
                            <span class="reply-timestamp">Geplaatst op {{ $reply->created_at->format('d-m-Y H:i') }}</span>
                        </div>
                        <div class="col s10">
                            {{-- CKEditor bewaart HTML; we tonen voorlopig alleen de tekst (bescherming tegen XSS) --}}
                            <p style="white-space: pre-line;">{{ html_entity_decode(strip_tags($reply->content)) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="collection-item">
                        <p>Er zijn nog geen replies op deze topic.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- EINDE REPLY -->

        <!-- TOEVOEGEN VAN EEN REPLY -->
        <div class="card">
            <div class="card-content">
                @auth
                <form method="POST" action="{{ route('replies.store', ['id' => $topic->id]) }}">
                    @csrf
                    <div class="row">
                        <div class="col s12">
                            <textarea id="message-body" name="content">{{ old('content') }}</textarea>
                            @error('content')
                            <div class="has-error">
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6 push-s6">
                            <button type="submit" class="btn right cyan darken-1">
                                Bewaren
                            </button>
                        </div>
                    </div>
                </form>
                @endauth

                @guest
                <p><a href="{{ route('login') }}">Meld je aan</a> om een reply te plaatsen.</p>
                @endguest
            </div>
        </div>
        <!-- EINDE TOEVOEGEN VAN EEN REPLY -->
    </div>
@endsection

@auth
    @push('scripts')
        <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/editor.js') }}"></script>
    @endpush
@endauth
