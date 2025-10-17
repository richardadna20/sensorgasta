@extends('layouts.main')

@section('content')
    <div class="card">
        <h2>Edit Profil</h2>

        @if (session('success'))
            <div style="color: green">{{ session('success') }}</div>
        @endif

        <form action="/profil" method="POST">
            @csrf
            <label>Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required
                style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Password Baru (opsional)</label>
            <input type="password" name="password" style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" style="width:100%;padding:8px;margin-bottom:10px;">

            <button type="submit" style="padding:10px 20px;background:#2563eb;color:white;border:none;border-radius:5px;">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
