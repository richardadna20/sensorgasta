@extends('layouts.main')

@section('content')
    <div class="card">
        <h2>Edit Profil</h2>

        @if (session('success'))
            <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">{{ session('success') }}</div>
        @endif
        
        <!-- {{-- Menampilkan error validasi dari Controller --}} -->
        @if ($errors->any())
            <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            <!-- {{-- Tambahkan method PUT atau PATCH untuk update resource --}} -->
            @method('PUT') 

            <label>Nama</label>
            <!-- {{-- Perbaikan: Menggunakan $user?->name dan old('name') --}} -->
            <input type="text" name="name" 
                   value="{{ old('name', $user?->name) }}" required
                   style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Email</label>
            <!-- {{-- Perbaikan: Menggunakan $user?->email dan old('email') --}} -->
            <input type="email" name="email" 
                   value="{{ old('email', $user?->email) }}" required
                   style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Password Baru (opsional)</label>
            <input type="password" name="password" 
                   style="width:100%;padding:8px;margin-bottom:10px;">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" 
                   style="width:100%;padding:8px;margin-bottom:10px;">

            <button type="submit" style="padding:10px 20px;background:#2563eb;color:white;border:none;border-radius:5px;">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection