<!doctype html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('css')
    @toastr_css
</head>
<body>
@yield('content')
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
