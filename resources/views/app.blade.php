<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'TicketHub') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <noscript>
            <strong
              >We're sorry but {{env('APP_NAME')}} doesn't work properly without JavaScript
              enabled. Please enable it to continue.
        </strong>
          </noscript>

        @inertia
    </body>
    <script src="https://use.fontawesome.com/03f8a0ebd4.js"></script>

</html>
