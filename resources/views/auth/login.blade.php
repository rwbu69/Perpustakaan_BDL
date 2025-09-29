@extends('layouts.app')

@section('title', 'Login')

@section('content')
@section('title', 'Masuk')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>Sistem Manajemen Perpustakaan</h1>
        <h2>Masuk ke Akun Anda</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="login">Username or Email</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus 
                   placeholder="Enter your username or email">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required 
                   placeholder="Enter your password">
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;">
                <input type="checkbox" name="remember" style="width: auto;">
                Remember me
            </label>
        </div>
        
        <button type="submit" class="btn" style="width: 100%;">Login</button>
    </form>
    
    <div class="text-center mt-20">
                <div class="auth-footer">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
        </div>
    </div>
</div>
@endsection