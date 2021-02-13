@extends('master')

@section('title', 'Register page')

@section('content')
<div class="limiter">
    <div class="container">
        <div class="wrap p-t-50 p-b-90">
            <form class="auth-form validate-form flex-sb flex-w" method="post" action="{{ route('auth.register.post') }}">
                @csrf
                <div class="title mb-4">Sign Up</div>

                <div class="wrap-input m-b-16" data-validate = "Email is required">
                    <input class="input" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="focus-input"></span>
                </div>
                <div class="wrap-input m-b-16" data-validate = "Password is required">
                    <input class="input" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    <span class="focus-input"></span>
                </div>

                <div class="wrap-input m-b-16" data-validate = "Password confirmation is required">
                    <input class="input" type="password" name="password_confirmation" placeholder="Password confirmation" value="{{ old('password_confirmation') }}">
                    <span class="focus-input"></span>
                </div>

                <div class="container-form-btn m-t-17">
                    <button class="form-btn" type="submit">Sign Up</button>
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
