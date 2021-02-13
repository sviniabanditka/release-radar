<!doctype html>
<html>
<head>
    <title>ReleaseRadar</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @toastr_css
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title mb-4">
            {{ env('APP_NAME') }}
        </div>
        <div class="links">
            <a href="{{ route('about.get') }}">About</a>
            @if(Sentinel::check())
                <a href="{{ route('dashboard.get') }}">Dashboard</a>
                @if(!empty(Sentinel::getUser()->spotify_access_token))
                    <a href="{{ route('following_list.get') }}">Artists</a>
                @endif
                <a href="{{ route('auth.logout.get') }}">Logout</a>
            @else
                <a href="{{ route('auth.login.get') }}">Login</a>
                <a href="{{ route('auth.register.get') }}">Sign Up</a>
            @endif
        </div>
    </div>
</div>
</body>
@jquery
@toastr_js
@toastr_render
@stack('scripts')
<script>
    @if(!empty($errors) && count($errors) > 0)
    @foreach($errors->all() as $error)
    toastr.error("{{ $error }}");
    @endforeach
    @endif
</script>
</html>
