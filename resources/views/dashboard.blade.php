@extends('auth.master')

@section('title', 'Dashboard')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md">Dashboard</div>
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
                            <tr>
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
                            <tr>
                                <td><img src="{{ asset('assets/images/telegram.png') }}" alt="Telegram" style="max-width: 150px;"></td>
                                <td>{{ ($user->last_notified) ? \Carbon\Carbon::parse($user->last_notified)->format('d.m.Y H:i') : 'None' }}</td>
                                <td><a href="#"><img src="{{ asset('assets/images/'. ($user->telegram_chat_id ? 'on' : 'off') .'.png') }}" alt="On" style="max-width: 50px;"></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr><br>
                <form action="{{ route('auth.email.post') }}" method="post">
                    @csrf
                    <div class="wrap-input100 validate-input m-b-16" data-validate = "Email is required">
                        <input class="input100" type="text" name="email" placeholder="Email" value="{{ $user->email }}">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="container-login100-form-btn m-t-17">
                        <button class="login100-form-btn" type="submit">Update Email</button>
                    </div>
                </form>
                <br><hr><br>
                <form action="{{ route('auth.password.post') }}" method="post">
                    @csrf
                    <div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
                        <input class="input100" type="password" name="password" placeholder="New password">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input m-b-16" data-validate = "Password confirmation is required">
                        <input class="input100" type="password" name="password_confirmation" placeholder="New password confirmation">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="container-login100-form-btn m-t-17">
                        <button class="login100-form-btn" type="submit">Update Password</button>
                    </div>
                </form>
                <br><hr><br>
                @if($user->spotify_artists)
                    <div class="container-login100-form-btn m-t-17">
                        <a class="login100-form-btn unlinked" href="{{ route('following_list.get') }}">Artists</a>
                    </div>
                @endif
                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('landing.get') }}">Go home</a>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('auth.logout.get') }}">Logout</a>
                </div>

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
