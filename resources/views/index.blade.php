<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Vortex</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400|Material+Icons" rel="stylesheet" type="text/css">
        <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style></style>
    </head>

    <body>
        <!-- Vue Application -->
        <div id="app">
            <app><app/>
        </div>

        <!-- Scripts -->
        <script src="{{ mix('js/main.js') }}"></script>
    </body>
</html>
