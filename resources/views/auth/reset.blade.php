@extends('master')

@section('title', 'Reset password')

@section('content')
<div class="limiter">
    <div class="container">
        <div class="wrap p-t-50 p-b-90">
            <form class="auth-form validate-form flex-sb flex-w" method="post" action="{{ route('auth.reset.post') }}">
                @csrf
                <div class="title mb-4" style="font-size: 55px;">Reset password</div>

                <input type="hidden" name="code" value="{{ $code }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="wrap-input m-b-16" data-validate = "Password is required">
                    <input class="input" type="password" name="password" placeholder="New password" value="{{ old('password') }}">
                    <span class="focus-input"></span>
                </div>

                <div class="wrap-input m-b-16" data-validate = "Password confirmation is required">
                    <input class="input" type="password" name="password_confirmation" placeholder="New password confirmation" value="{{ old('password_confirmation') }}">
                    <span class="focus-input"></span>
                </div>

                <div class="container-form-btn m-t-17">
                    <button class="form-btn" type="submit">Update Password</button>
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
