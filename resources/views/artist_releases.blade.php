@extends('auth.master')

@section('title', $artist->name.' Releases')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md" style="font-size: 60px;">Releases</div>
                <div class="center" style="text-align: center; font-weight: bold">
                    Artist: <a href="{{ $artist->spotify_url ?? '#' }}" target="_blank">{{ $artist->name }}</a> <br><br>
                </div>
                <div class="flex-sb-m w-full p-t-3 p-b-24" style="text-align: center;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Release</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($releases as $release)
                            <tr>
                                <td><a href="{{ $release->spotify_url ?? '#' }}" target="_blank">{{ $release->name }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($release->release_date)->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('following_list.get') }}">Artists</a>
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
