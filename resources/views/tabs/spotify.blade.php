<br>
@if(!empty($user->spotify_artists) && count($user->spotify_artists))
    <div class="container-login100-form-btn m-t-17">
        <a class="login100-form-btn unlinked" href="{{ route('following_list.get') }}">Artists</a>
    </div>
    <div class="container-login100-form-btn m-t-17">
        <a class="login100-form-btn unlinked" href="{{ route('latest_releases.get') }}">Latest Releases</a>
    </div>
@endif
