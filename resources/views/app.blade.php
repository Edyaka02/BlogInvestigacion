<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'blog')</title>

    @if (Request::is('dashboard/*'))
        <!-- Archivos específicos de admin -->
        @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @else
        <!-- Archivos específicos de public -->
        @vite(['resources/css/public.css', 'resources/js/app.js'])
    @endif
    

</head>
<body>
    @yield('body') <!-- Cada layout específico puede definir su estructura aquí -->
</body>
</html>