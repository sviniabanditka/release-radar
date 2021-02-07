@extends('auth.master')

@section('title', 'Register page')

@section('content')
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <form class="login100-form validate-form flex-sb flex-w" method="post" action="{{ route('auth.register.post') }}">
                @csrf
                <div class="title m-b-md">Sign Up</div>

                <div class="wrap-input100 validate-input m-b-16" data-validate = "Email is required">
                    <input class="input100" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-16" data-validate = "Password confirmation is required">
                    <input class="input100" type="password" name="password_confirmation" placeholder="Password confirmation" value="{{ old('password_confirmation') }}">
                    <span class="focus-input100"></span>
                </div>

                <div class="container-login100-form-btn m-t-17">
                    <button class="login100-form-btn" type="submit">Sign Up</button>
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
