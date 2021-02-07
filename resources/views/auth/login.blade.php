@extends('auth.master')

@section('title', 'Login page')

@section('content')
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <form class="login100-form validate-form flex-sb flex-w" method="post" action="{{ route('auth.login.post') }}">
                @csrf
                <div class="title m-b-md">Login</div>

                <div class="wrap-input100 validate-input m-b-16" data-validate = "Email is required">
                    <input class="input100" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    <span class="focus-input100"></span>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">Remember me</label>
                    </div>

                    <div><a href="{{ route('auth.forgot.get') }}" class="txt1">Forgot password?</a></div>
                </div>

                <div class="container-login100-form-btn m-t-17">
                    <button class="login100-form-btn" type="submit">Login</button>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <a class="txt1" href="{{ route('landing.get') }}">Go home</a>
                </div>

            </form>
        </div>
    </div>
</div>
<div id="dropDownSelect1"></div>
@endsection
