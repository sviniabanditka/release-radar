@extends('auth.master')

@section('title', 'About Us')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-t-50 p-b-90">
                <div class="title m-b-md">About Us</div>
                <div class="flex-sb-m w-full p-t-3 p-b-24" style="text-align: center;">
                    Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Pellentesque in ipsum id orci
                    porta dapibus. Cras ultricies ligula sed magna dictum porta. Vestibulum ante ipsum primis in
                    faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam
                    vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Quisque velit nisi,
                    pretium ut lacinia in, elementum id enim. Curabitur aliquet quam id dui posuere blandit. Praesent
                    sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id
                    imperdiet et, porttitor at sem. Cras ultricies ligula sed magna dictum porta.
                </div>

                <div class="container-login100-form-btn m-t-17">
                    <a class="login100-form-btn unlinked" href="{{ route('landing.get') }}">Go home</a>
                </div>

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
