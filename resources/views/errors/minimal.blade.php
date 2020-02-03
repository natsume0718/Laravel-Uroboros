<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <meta name="author" content="© uroboros">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- description --}}
    <meta name="keywords" content="継続,習慣,積み上げ,学習">
    <meta name=”description” content=”継続を記録して、Twitterに投稿しよう。使い方は簡単、3秒でログイン・登録して活動内容を記録するだけ。時間や日数が積み上がっていきます”>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="shortcut icon" href="/favicon.ico">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}" defer>

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 100;
            height: 80vh;
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

        .code {
            border-right: 2px solid;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message {
            font-size: 18px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header id="nav" class="bg-aqua-gradiention">
        @include('layouts.nav')
        <!-- Header Section End -->
    </header>
    <div class="flex-center position-ref full-height">
        <div class="code">
            @yield('code')
        </div>

        <div class="message" style="padding: 10px;">
            @yield('message')
        </div>
    </div>
    @include('layouts.footer')
    <script src="{{ mix('js/app.js') }}" defer></script>

</body>

</html>