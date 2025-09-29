@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="card" style="max-width: 500px; margin: 50px auto;">
    <h2 class="text-center mb-20">Daftar ke Sistem Perpustakaan</h2>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
                    <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="Masukkan nama lengkap Anda">
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                       placeholder="Pilih username">
            </div>
            
            <div class="form-group">
                <label for="email">Email (Opsional)</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       placeholder="Masukkan alamat email Anda">
            </div>
            
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required 
                       placeholder="Masukkan nomor telepon Anda">
            </div>
            
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="3" required 
                          placeholder="Masukkan alamat Anda">{{ old('address') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Buat kata sandi">
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required 
                       placeholder="Konfirmasi kata sandi Anda">
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Daftar</button>
    </form>
    
    <div class="text-center" style="margin-top: 20px;">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </div>
</div>
@endsection