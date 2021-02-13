@extends('master')

@section('title', 'Restore access')

@section('content')
    <div class="limiter">
        <div class="container">
            <div class="wrap p-t-50 p-b-90">
                <div class="title mb-4">Success</div>
                <div class="m-b-16 m-l-100" style="width: 50%;">
                    <img src="{{ asset('assets/images/checked.png') }}" width="100%">
                </div>

                <div class="w-full p-t-3 p-b-24" style="text-align: center; width: 100%;">
                    Email with restore link was sent to your address.
                </div>

                <div class="container-form-btn m-t-17">
                    <a class="form-btn unlinked" href="{{ route('landing.get') }}">Go home</a>
                </div>

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
