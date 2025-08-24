@extends('layouts.app')

@section('body')

    <body class="public body d-flex flex-column min-vh-100">

        <!-- Rectángulo azul -->

        
        <div class="blue-banner">
        </div>
        <header class="header">
            @include('components.navbar-public')
        </header>

        <div class="blue-rectangle"></div>
        

        <!-- Contenido dinámico -->
        <main class="flex-grow-1 container mt-4 main">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-body-tertiary text-center">
            <div class="footer">

                <div class="row">
                    <h5>Sigueme en:</h5>
                </div>
                <div class="icon-row">
                    <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-researchgate"></i></a>
                </div>
            </div>
        </footer>

    </body>
@endsection
