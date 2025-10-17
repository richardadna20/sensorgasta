@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <h2>Register Monitoring Gas</h2>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Masukkan email">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn-primary">Daftar</button>
    </form>

    <p class="auth-link">
        Sudah punya akun? <a href="/login">Login</a>
    </p>
@endsection
