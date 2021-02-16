@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => 'Telegram Bot',
        'button_link' => route('telegram.webhook.get'),
        'button_text' => 'Set webhook',
    ];
@endphp

@section('content')
@endsection
