@extends('layouts.app')

@section('body')

    <body class="admin" id="body-pd">
        {{-- <header class="header">
            @include('components.navbar-admin2')
        </header> --}}
        {{-- <main class="main d-flex flex-column flex-grow-1">
            @include('components.navbar-admin2')
            @yield('content')
        </main> --}}
        @include('components.navbar-admin2')
        <main class="main d-flex">
            {{-- Menú lateral --}}
            
            {{-- @include('components.navbar-admin2') --}}

            {{-- Contenido principal --}}
            {{-- <div class="content flex-grow-1 p-3"> --}}
                @yield('content')
                @stack('modals')
            {{-- </div> --}}
        </main>

        

        @stack('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pasar mensajes de sesión a JavaScript
                @if (session('success'))
                    window.sessionMessage = "{{ session('success') }}";
                @endif
                @if (session('error'))
                    window.sessionError = "{{ session('error') }}";
                @endif
            });
        </script>

    </body>
@endsection
