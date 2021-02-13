<br>
<div class="flex-sb-m w-full p-t-3 p-b-24" style="text-align: center;">
    <table style="width: 100%;">
        <thead>
        <tr>
            <th>Service</th>
            <th>Last Update</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr style="border-top: 12px solid transparent;">
            <td><img src="{{ asset('assets/images/spotify.png') }}" alt="Spotify" style="max-width: 150px;"></td>
            <td>
                @if($last_release = \App\Models\SpotifyRelease::query()->whereNotNull('last_updated')->orderByDesc('release_date')->first())
                    {{  \Carbon\Carbon::parse($last_release->last_updated)->format('d.m.Y H:i') }}
                @else
                    None
                @endif
            </td>
            <td><a href="{{ route('spotify.toggle.get') }}"><img src="{{ asset('assets/images/'. ($user->spotify_access_token ? 'on' : 'off') .'.png') }}" alt="Off" style="max-width: 50px;"></a></td>
        </tr>
        <tr style="border-top: 12px solid transparent;">
            <td><img src="{{ asset('assets/images/telegram.png') }}" alt="Telegram" style="max-width: 150px;"></td>
            <td>{{ ($user->last_notified) ? \Carbon\Carbon::parse($user->last_notified)->format('d.m.Y H:i') : 'None' }}</td>
            <td><a href="{{ route('telegram.toggle.get') }}"><img src="{{ asset('assets/images/'. ($user->telegram_chat_id ? 'on' : 'off') .'.png') }}" alt="On" style="max-width: 50px;"></a></td>
        </tr>
        </tbody>
    </table>
</div>
