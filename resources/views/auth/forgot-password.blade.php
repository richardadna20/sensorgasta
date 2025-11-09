@extends('layouts.auth')

@section('title', 'Ganti Password Langsung')

@section('content')
    <div class="auth-card">
        <div class="header-logo text-center mb-4">
            <i class="fas fa-redo-alt fa-3x" style="color: #2563eb;"></i>
        </div>
        
        <h2>Ganti Password</h2>
        <p class="text-muted mb-4" style="text-align: center; color: #6b7280; font-size: 0.9rem;">
            Masukkan email Anda dan atur password baru.
        </p>
        
        @if ($errors->any())
            <div class="error-message" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form dikirim ke route password.update --}}
        <form method="POST" action="{{ route('password.update') }}"> 
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                       placeholder="Email terdaftar Anda" style="width:100%;padding:8px;margin-bottom:10px;">
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Password baru Anda" style="width:100%;padding:8px;margin-bottom:10px;">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required 
                       placeholder="Ulangi password baru" style="width:100%;padding:8px;margin-bottom:10px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 10px 0;">
                Atur Ulang Password
            </button>
        </form>

        <p class="auth-link" style="text-align: center; margin-top: 15px;">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </p>
    </div>
@endsection