<!-- BEGIN PROFIELGEGEVENS -->
<div class="card">
    <div class="card-content">
        <span class="card-title">Profielgegevens</span>
        <p class="form-intro">Pas hier je naam en e-mailadres aan.</p>

        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            <div class="row">
                <div class="input-field col s12 @error('name') has-error @enderror">
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                    <label for="name" class="active">Naam</label>
                    @error('name')
                    <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 @error('email') has-error @enderror">
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    <label for="email" class="active">E-mail</label>
                    @error('email')
                    <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p>
                Je e-mailadres is nog niet bevestigd.
                <button type="submit" form="send-verification" class="btn-flat">Stuur de bevestigingsmail opnieuw</button>
            </p>
                @if (session('status') === 'verification-link-sent')
                <p class="form-status">Er is een nieuwe bevestigingslink verstuurd.</p>
                @endif
            @endif

            <div class="row last-row">
                <div class="col s12">
                    <button type="submit" class="btn right cyan darken-1">Bewaren</button>
                    @if (session('status') === 'profile-updated')
                    <p class="form-status">Je gegevens zijn opgeslagen.</p>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- EINDE PROFIELGEGEVENS -->
