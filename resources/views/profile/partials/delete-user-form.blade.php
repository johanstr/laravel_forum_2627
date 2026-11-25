<!-- BEGIN ACCOUNT VERWIJDEREN -->
<div class="card">
    <div class="card-content">
        <span class="card-title">Account verwijderen</span>
        <p class="form-intro">Dit kan niet ongedaan worden gemaakt. Vul je wachtwoord in om je account te verwijderen.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Weet je zeker dat je je account wilt verwijderen?');">
            @csrf
            @method('delete')
            <div class="row">
                <div class="input-field col s12 l6 @error('password', 'userDeletion') has-error @enderror">
                    <input id="delete_password" name="password" type="password" autocomplete="current-password">
                    <label for="delete_password">Wachtwoord</label>
                    @error('password', 'userDeletion')
                    <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row last-row">
                <div class="col s12">
                    <button type="submit" class="btn right red">Account verwijderen</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- EINDE ACCOUNT VERWIJDEREN -->
