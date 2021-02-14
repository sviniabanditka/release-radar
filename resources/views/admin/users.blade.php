@extends('master')

@section('title', 'Users')

@section('content')
    <div class="limiter">
        <div class="container">
            <div class="wrap p-t-50 p-b-90">
                <div class="title mb-4">Users</div>

                <div class="container-form-btn m-t-17">
                    <a class="form-btn unlinked" href="{{ route('dashboard.get') }}">Dashboard</a>
                </div>

            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
