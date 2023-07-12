<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <style>
            .c-bg{
                background-color: #ecebe4;
            }
        </style>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://fonts.cdnfonts.com/css/angkest" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora&display=swap">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="antialiased bg-gradient-to-r from-rose-50 via-35% via-neutral-200 to-gray-100 select-none no-highlight">
        @inertia
    </body>
</html>
