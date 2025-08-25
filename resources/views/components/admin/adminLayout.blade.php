@extends('app')

@section('body')

    <body class="admin" id="body-pd">
        {{-- @include('components.navbar-admin2') --}}
        @include('components.admin.sidebar')
        <main class="main d-flex">
            @yield('content')
            @stack('modals')
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
