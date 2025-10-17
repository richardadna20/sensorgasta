@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">
        {{-- Logo/Ikon (Ditambahkan agar sesuai dengan master layout) --}}
        <div class="header-logo text-center mb-4">
            <i class="fas fa-lock fa-3x" style="color: #2563eb;"></i>
        </div>
        
        <h2>Login Monitoring Gas</h2>

        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email">
            </div>

            <div class="form-group">
                {{-- Menggunakan password-header untuk menyejajarkan Label dan Link ke kanan --}}
                
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
                <div class="password-header">

                    {{-- Menggunakan forgot-link dan tujuan yang benar --}}
                    <a href="/profil/index" class="forgot-link">Lupa password?</a>
                </div>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>
        <p class="auth-link">
            Belum punya akun? <a href="/register">Daftar di sini</a>
        </p>
    </div>
@endsection
