<br>
@if(!empty($user->spotify_artists) && count($user->spotify_artists))
    <div>
        Your artists count: {{ count($user->spotify_artists) }}
        <br>
        Last artists list update: {{ \Carbon\Carbon::parse($user->spotify_artists->sortByDesc('updated_at')->first()->updated_at)->format('d.m.Y H:i') }}
        <br>
        Last releases update:
        @if($last_release = \App\Models\SpotifyRelease::query()->whereNotNull('last_updated')->orderByDesc('release_date')->first())
            {{  \Carbon\Carbon::parse($last_release->last_updated)->format('d.m.Y H:i') }}
        @else
            None
        @endif
    </div>
    <div class="container-form-btn m-t-17">
        <a class="form-btn unlinked" href="{{ route('following_list.get') }}">Artists</a>
    </div>
    <div class="container-form-btn m-t-17">
        <a class="form-btn unlinked" href="{{ route('latest_releases.get') }}">Latest Releases</a>
    </div>
@endif
