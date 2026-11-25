<!-- BEGIN WACHTWOORD WIJZIGEN -->
<div class="card">
    <div class="card-content">
        <span class="card-title">Wachtwoord wijzigen</span>
        <p class="form-intro">Gebruik een lang en willekeurig wachtwoord.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')
            <div class="row">
                <div class="input-field col s12 @error('current_password', 'updatePassword') has-error @enderror">
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                    <label for="current_password">Huidig wachtwoord</label>
                    @error('current_password', 'updatePassword')
                    <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 @error('password', 'updatePassword') has-error @enderror">
                    <input id="new_password" name="password" type="password" autocomplete="new-password">
                    <label for="new_password">Nieuw wachtwoord</label>
                    @error('password', 'updatePassword')
                    <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    <label for="password_confirmation">Herhaal nieuw wachtwoord</label>
                </div>
            </div>
            <div class="row last-row">
                <div class="col s12">
                    <button type="submit" class="btn right cyan darken-1">Bewaren</button>
                    @if (session('status') === 'password-updated')
                    <p class="form-status">Je wachtwoord is gewijzigd.</p>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- EINDE WACHTWOORD WIJZIGEN -->
