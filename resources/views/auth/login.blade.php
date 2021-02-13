@extends('master')

@section('title', 'Login page')

@section('content')
<div class="limiter">
    <div class="container">
        <div class="wrap p-t-50 p-b-90">
            <form class="auth-form validate-form flex-sb flex-w" method="post" action="{{ route('auth.login.post') }}">
                @csrf
                <div class="title mb-4">Login</div>

                <div class="wrap-input m-b-16" data-validate = "Email is required">
                    <input class="input" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="focus-input"></span>
                </div>
                <div class="wrap-input m-b-16" data-validate = "Password is required">
                    <input class="input" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    <span class="focus-input"></span>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div>
                        <input class="option-input checkbox" id="ckb1" type="checkbox" name="remember-me">
                        <label for="ckb1">Remember me</label>
                    </div>

                    <div><a href="{{ route('auth.forgot.get') }}" class="label">Forgot password?</a></div>
                </div>

                <div class="container-form-btn m-t-17">
                    <button class="form-btn" type="submit">Login</button>
                </div>
                <div class="container-form-btn m-t-17">
                    <a class="label" href="{{ route('landing.get') }}">Go home</a>
                </div>

            </form>
        </div>
    </div>
</div>
<div id="dropDownSelect1"></div>
@endsection
