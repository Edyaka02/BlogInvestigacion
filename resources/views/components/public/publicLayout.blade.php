@extends('app')

@section('meta')
    @yield('meta')
@endsection

@push('styles')
    @stack('styles')
@endpush

@section('body')
    <body class="public body d-flex flex-column min-vh-100">
        <!-- Rectángulo azul -->
        <div class="blue-banner">
        </div>
        <header class="header">
            @include('components.public.navbar')
        </header>

        <div class="blue-rectangle"></div>
        
        <!-- Contenido dinámico -->
        <main class="flex-grow-1 container mt-4 main">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.public.footer')

    </body>

    @stack('scripts')
@endsection
