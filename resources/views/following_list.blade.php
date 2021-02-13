@extends('auth.master')

@section('title', 'My Artists')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md" style="font-size: 60px;">Artists</div>
                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div class="row">

                        <form method="get" action="" style="width: 100%; padding-bottom: 20px;">
                            <input style="width:70%; float:left;" class="input100" type="text" name="q" placeholder="Filter..." value="{{ request()->get('q') ?? '' }}">
                            <button style="width:25%; float:right;" class="login100-form-btn" type="submit">Apply</button>
                        </form>

                        @foreach($artists as $artist)
                            <div class="column" style="background-color:#fff;width:100%;">
                                <div style="padding-bottom: 10px;">
                                    <div style="float:left; padding-right:10px;">
                                        <a href="{{ $artist->spotify_url ?? '#' }}" target="_blank">
                                            @if(!empty($artist->releases->sortByDesc('release_date')->first()->cover))
                                                <img src="{{ $artist->releases->sortByDesc('release_date')->first()->cover }}" style="max-width: 100px;">
                                            @else
                                                <img src="https://via.placeholder.com/300" style="max-width: 100px;">
                                            @endif
                                        </a>
                                    </div>
                                    <div >
                                        <div style="display: flex;justify-content: space-between;">
                                            <h2 style="display:inline;"><a href="{{ route('artist_releases.get', ['id' => $artist->id]) }}" style="color:#666666; font-size:1.7rem;" class="unlinked">{{ $artist->name }}</a></h2>
                                            <div class="text-right" style="display:inline; padding-top: 10px; padding-right: 0; text-align: right">
                                                <a href="{{ route('artist.toggle.get', ['id' => $artist->id]) }}"><img src="{{ asset('assets/images/'.($artist->pivot->is_active ? 'on' : 'off').'.png') }}" alt="On" style="max-width: 50px;"></a>
                                            </div>
                                        </div>
                                        <div style="padding-bottom:0;">
                                            Last Release:
                                            <a href="{{ $artist->releases->last()->spotify_url ?? '#' }}" target="_blank" style="color:#666666; font-size:1rem;" class="unlinked">{{ $artist->releases->sortByDesc('release_date')->first()->name }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div><hr></div>
                            </div>
                        @endforeach
                        @if($artists->lastPage() > 1)
                            <div style="display: flex;justify-content: space-between;width: 100%;">
                                @if($artists->currentPage() > 1)
                                    <a class="login100-form-btn unlinked" style="margin-right: 5px;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$artists->currentPage()-1))) }}"><<</a>
                                @endif
                                <a class="login100-form-btn unlinked" style="margin-right: 5px;" href="">{{ $artists->currentPage() }}</a>
                                @if($artists->lastPage() > $artists->currentPage())
                                    <a class="login100-form-btn unlinked" style="margin-right: 0;" href="?{{ http_build_query(array_merge($_GET, array("page"=>$artists->currentPage()+1))) }}">>></a>
                                @endif
                            </div>
                        @endif
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
