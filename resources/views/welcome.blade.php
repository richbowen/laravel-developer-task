<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <script src="{{ asset('js/app.js') }}" defer></script>

        <link rel="icon" href="{{ asset('favicon-32x32.png') }}">
    </head>
    <body>
       <div id="app"></div>
    </body>
</html>
