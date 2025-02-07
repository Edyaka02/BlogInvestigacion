@extends('layouts.layout')

@section('title', 'Articulos')

@section('content')
    {{-- <div class="full-height">
        <div class="login-container">
            <h2>Login</h2>
            <p>Please enter your login and password!</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <p><a href="#">Forgot password?</a></p>

                <button type="submit">Login</button>
            </form>

            <div class="signup-link">
                <p>Don't have an account? <a href="#">Sign Up</a></p>
            </div>
        </div>
    </div> --}}
    <div class="full-height">
        <div class="login-card">
            <h2>Login</h2>
            <p>Please enter your login and password!</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <p><a href="#">Forgot password?</a></p>

                <button type="submit">Login</button>
            </form>

            <div class="signup-link">
                {{-- <p>Don't have an account? <a href="#">Sign Up</a></p> --}}
            </div>
        </div>
    </div>

@endsection
