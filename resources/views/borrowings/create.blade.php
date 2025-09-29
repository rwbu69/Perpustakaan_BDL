@extends('layouts.app')

@section('title', 'Buat Peminjaman Baru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Buat Peminjaman Baru</h1>
    <a href="{{ route('borrowings.index') }}" class="btn">Kembali ke Peminjaman</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('borrowings.store') }}">
        @csrf
        
        <div class="grid grid-2">
            <div class="form-group">
                <label for="user_id">Pilih Pengguna *</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Pilih pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->username }})
                            @if($user->email) - {{ $user->email }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="buku_id">Pilih Buku *</label>
                <select id="buku_id" name="buku_id" required>
                    <option value="">Pilih buku</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('buku_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} oleh {{ $book->author }}
                            ({{ ucfirst(str_replace('_', ' ', $book->type)) }}) 
                            - Tersedia: {{ $book->available_copies }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="borrow_days">Periode Peminjaman *</label>
            <select id="borrow_days" name="borrow_days" required>
                <option value="7" {{ old('borrow_days', 7) == 7 ? 'selected' : '' }}>7 hari (Standar)</option>
                <option value="3" {{ old('borrow_days') == 3 ? 'selected' : '' }}>3 hari (Jangka pendek)</option>
                <option value="14" {{ old('borrow_days') == 14 ? 'selected' : '' }}>14 hari (Diperpanjang)</option>
                <option value="21" {{ old('borrow_days') == 21 ? 'selected' : '' }}>21 hari (Jangka panjang)</option>
                <option value="30" {{ old('borrow_days') == 30 ? 'selected' : '' }}>30 hari (Maksimal)</option>
            </select>
            <small style="color: #6c757d;">Denda: Rp 7.000 per hari setelah tanggal jatuh tempo</small>
        </div>
        
        <div class="text-right">
            <a href="{{ route('borrowings.index') }}" class="btn" style="background: #6c757d;">Batal</a>
            <button type="submit" class="btn btn-success">Buat Peminjaman</button>
        </div>
    </form>
</div>

<div class="card">
    <h3>Referensi Cepat</h3>
    <div class="grid grid-2">
        <div>
            <h4>Buku Tersedia ({{ $books->count() }})</h4>
            @if($books->count() > 0)
                <ul style="max-height: 200px; overflow-y: auto;">
                    @foreach($books->take(10) as $book)
                        <li>{{ $book->title }} - {{ $book->available_copies }} tersedia</li>
                    @endforeach
                    @if($books->count() > 10)
                        <li><em>... dan {{ $books->count() - 10 }} lainnya</em></li>
                    @endif
                </ul>
            @else
                <p>Tidak ada buku tersedia untuk dipinjam.</p>
            @endif
        </div>
        
        <div>
            <h4>Pengguna Aktif ({{ $users->count() }})</h4>
            @if($users->count() > 0)
                <ul style="max-height: 200px; overflow-y: auto;">
                    @foreach($users->take(10) as $user)
                        <li>{{ $user->name }} ({{ $user->username }})</li>
                    @endforeach
                    @if($users->count() > 10)
                        <li><em>... dan {{ $users->count() - 10 }} lainnya</em></li>
                    @endif
                </ul>
            @else
                <p>Tidak ada pengguna tersedia.</p>
            @endif
        </div>
    </div>
</div>
@endsection