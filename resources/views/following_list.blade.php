@extends('master')

@section('title', 'My Artists')

@section('content')
    <div class="limiter">
        <div class="container">
            <div class="wrap p-t-50 p-b-90">
                <div class="title mb-4" style="font-size: 60px;">Artists</div>
                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div class="row">

                        <form method="get" action="" style="width: 100%; padding-bottom: 20px;">
                            <input class="filter-input" type="text" name="q" placeholder="Filter..." value="{{ request()->get('q') ?? '' }}">
                            <button class="filter-submit" type="submit">Apply</button>
                        </form>

                        @foreach($artists as $artist)
                            <div class="column" style="background-color:#fff;width:100%;">
                                <div style="padding-bottom: 10px;">
                                    <div style="float:left; padding-right:10px;">
                                        <a href="{{ $artist->spotify_url ?? '#' }}" target="_blank">
                                            @if(!empty($artist->releases->sortByDesc('release_date')->first()->cover))
                                                <img src="{{ $artist->releases->sortByDesc('release_date')->first()->cover }}" style="max-width: 125px;">
                                            @else
                                                <img src="https://via.placeholder.com/300" style="max-width: 100px;">
                                            @endif
                                        </a>
                                    </div>
                                    <div >
                                        <div style="display: flex;justify-content: space-between;">
                                            <h2 style="line-height:0.1rem;"><a href="{{ route('artist_releases.get', ['id' => $artist->id]) }}" style="color:#666666; font-size:1.2rem;" class="unlinked">{{ $artist->name }}</a></h2>
                                            <div class="text-right" style="padding-top: 10px; padding-right: 0; text-align: right">
                                                <a href="{{ route('artist.toggle.get', ['id' => $artist->id]) }}"><img src="{{ asset('assets/images/'.($artist->pivot->is_active ? 'on' : 'off').'.png') }}" alt="On" style="max-width: 50px;"></a>
                                            </div>
                                        </div>
                                        <div style="padding-bottom:0;">
                                            Last Release:
                                            <a href="{{ $artist->releases->sortByDesc('release_date')->first()->spotify_url ?? '#' }}" target="_blank" style="color:#666666; font-size:1rem;" class="unlinked">{{ $artist->releases->sortByDesc('release_date')->first()->name }}</a>
                                            <br>
                                            Last Release Date: {{ \Carbon\Carbon::parse($artist->releases->sortByDesc('release_date')->first()->release_date)->format('d.m.Y') }}
                                            <br>
                                            Last Release Type: {{ \App\Models\SpotifyRelease::$TYPES[$artist->releases->sortByDesc('release_date')->first()->album_group] ?? '' }}
                                            <br>
                                            Last Release Artists:
                                            @foreach($artist->releases->sortByDesc('release_date')->first()->artists as $art)
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
                                <div class="clearfix"></div>
                                <hr>
                            </div>
                        @endforeach
                        @if($artists->lastPage() > 1)
                            <div style="display: flex;justify-content: space-between;width: 100%;">
                                @if($artists->currentPage() > 1)
                                    <a class="form-btn unlinked" style="margin-right: 5px;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$artists->currentPage()-1))) }}"><<</a>
                                @endif
                                <a class="form-btn unlinked" style="margin-right: 5px;" href="">{{ $artists->currentPage() }}</a>
                                @if($artists->lastPage() > $artists->currentPage())
                                    <a class="form-btn unlinked" style="margin-right: 0;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$artists->currentPage()+1))) }}">>></a>
                                @endif
                            </div>
                        @endif
                        <div class="container-form-btn m-t-17">
                            <a class="form-btn unlinked" href="{{ route('dashboard.get') }}#spotify">Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
