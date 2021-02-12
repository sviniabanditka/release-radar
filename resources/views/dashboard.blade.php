@extends('auth.master')

@section('title', 'Dashboard')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md">Dashboard</div>
                <div>
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a href="#home" class="nav-link active" data-toggle="tab"><i class="las la-home la-3x text-black"></i></a>
                        </li>
                        @if(!empty($user->spotify_artists) && count($user->spotify_artists))
                            <li class="nav-item">
                                <a href="#spotify" class="nav-link" data-toggle="tab"><i class="lab la-spotify la-3x text-black"></i></a>
                            </li>
                        @endif
                        @if(!empty($user->telegram_chat_id))
                            <li class="nav-item">
                                <a href="#telegram" class="nav-link" data-toggle="tab"><i class="lab la-telegram la-3x text-black"></i></a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="#profile" class="nav-link" data-toggle="tab"><i class="las la-user la-3x text-black"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('auth.logout.get') }}" class="nav-link"><i class="las la-sign-out-alt la-3x text-black"></i></a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        {{--HOME TAB--}}
                        <div class="tab-pane fade show active" id="home">
                            @include('tabs.home')
                        </div>

                        {{--SPOTIFY TAB--}}
                        <div class="tab-pane fade" id="spotify">
                            @include('tabs.spotify')
                        </div>

                        {{--TELEGRAM TAB--}}
                        <div class="tab-pane fade" id="telegram">
                            @include('tabs.telegram')
                        </div>

                        {{--PROFILE TAB--}}
                        <div class="tab-pane fade" id="profile">
                            @include('tabs.profile')
                        </div>


                    </div>
                </div>

                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('landing.get') }}">Go home</a>
                </div>
                {{--@todo: make admin-side functional--}}
                {{--@if(Sentinel::check() && Sentinel::inRole('admin'))
                    <div class="container-login100-form-btn m-t-17">
                        <a class="login100-form-btn unlinked" href="{{ route('telegram.webhook.get') }}">Set Telegram Webhook</a>
                    </div>
                @endif--}}

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
