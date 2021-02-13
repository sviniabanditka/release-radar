@extends('auth.master')

@section('title', $artist->name.' Releases')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md" style="font-size: 60px;">Releases</div>
                <div class="center" style="text-align: center; font-weight: bold">
                    <a href="{{ $artist->spotify_url ?? '#' }}" target="_blank" class="unlinked" style="color:#666666; font-size:1.7rem;">{{ $artist->name }}</a> <br><br>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div class="row">

                        <form method="get" action="" style="width: 100%; padding-bottom: 20px;">
                            <input style="width:70%; float:left;" class="input100" type="text" name="q" placeholder="Filter..." value="{{ request()->get('q') ?? '' }}">
                            <button style="width:25%; float:right;" class="login100-form-btn" type="submit">Apply</button>
                        </form>

                        @foreach($releases as $release)
                            <div class="column" style="background-color:#fff;width:100%;">
                                <div style="padding-bottom: 10px;">
                                    <div style="float:left; padding-right:10px;">
                                        <a href="{{ $release->spotify_url ?? '#' }}" target="_blank">
                                            @if(!empty($release->cover))
                                                <img src="{{ $release->cover }}" style="max-width: 125px;">
                                            @else
                                                <img src="https://via.placeholder.com/300" style="max-width: 125px;">
                                            @endif
                                        </a>
                                    </div>
                                    <div>
                                        <h2><a href="{{ $release->spotify_url ?? '#' }}" style="color:#666666; font-size:1.7rem;" class="unlinked" target="_blank">{{ $release->name }}</a></h2>
                                        <div style="padding-bottom:0;">
                                            Release Date: {{ \Carbon\Carbon::parse($release->release_date)->format('d.m.Y') }}
                                            <br>
                                            Album Type: {{ \App\Models\SpotifyRelease::$TYPES[$release->album_group] ?? '' }}
                                            <br>
                                            Artists:
                                            @foreach($release->artists as $art)
                                                @if(!empty($art['name']))
                                                    @if(!empty($art['external_urls']['spotify']))
                                                        <a href="{{ $art['external_urls']['spotify'] }}" style="color:#666666;" class="unlinked" target="_blank">{{ $art['name'] }}</a>
                                                    @else
                                                        {{ $art['name'] }}
                                                    @endif
                                                    @if(!$loop->last)
                                                        ,
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div><hr></div>
                            </div>
                        @endforeach
                        @if($releases->lastPage() > 1)
                            <div style="display: flex;justify-content: space-between;width: 100%;">
                                @if($releases->currentPage() > 1)
                                    <a class="login100-form-btn unlinked" style="margin-right: 5px;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$releases->currentPage()-1))) }}"><<</a>
                                @endif
                                <a class="login100-form-btn unlinked" style="margin-right: 5px;" href="">{{ $releases->currentPage() }}</a>
                                @if($releases->lastPage() > $releases->currentPage())
                                    <a class="login100-form-btn unlinked" style="margin-right: 0;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$releases->currentPage()+1))) }}">>></a>
                                @endif
                            </div>
                        @endif
                        <div class="container-login100-form-btn m-t-17">
                            <a class="login100-form-btn unlinked" href="{{ route('following_list.get') }}">Artists</a>
                        </div>
                        <div class="container-login100-form-btn m-t-17">
                            <a class="login100-form-btn unlinked" href="{{ route('dashboard.get') }}">Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
