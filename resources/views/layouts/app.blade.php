<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Vue Starter Kit</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        <div>
            home page
        </div>

        @vite(['resources/js/app.js'])
    </body>
</html>
