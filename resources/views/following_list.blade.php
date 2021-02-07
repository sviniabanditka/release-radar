@extends('auth.master')

@section('title', 'My Artists')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md" style="font-size: 60px;">Artists</div>
                <div class="flex-sb-m w-full p-t-3 p-b-24" style="text-align: center;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Artist</th>
                                <th>Notifications</th>
                                <th>Releases</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($artists as $artist)
                            <tr>
                                <td><a href="{{ $artist->spotify_url ?? '#' }}" target="_blank">{{ $artist->name }}</a></td>
                                <td><a href="#"><img src="{{ asset('assets/images/on.png') }}" alt="On" style="max-width: 50px;"></a></td>
                                <td><a href="{{ route('artist_releases.get', ['id' => $artist->id]) }}">Show</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('dashboard.get') }}">Dashboard</a>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('landing.get') }}">Go home</a>
                </div>

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
