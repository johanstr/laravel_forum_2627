@extends('layouts.main')

@section('pagetitle')
    Profiel
@endsection

@section('content')
    <div class="col s12 l6">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="col s12 l6">
        @include('profile.partials.update-password-form')
    </div>

    <div class="col s12">
        @include('profile.partials.delete-user-form')
    </div>
@endsection
