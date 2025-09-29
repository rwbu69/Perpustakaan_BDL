@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Tambah Buku Baru</h1>
    <a href="{{ route('books.index') }}" class="btn">Kembali ke Daftar Buku</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-2">
            <div class="form-group">
                <label for="title">Judul *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                       placeholder="Masukkan judul buku">
            </div>
            
            <div class="form-group">
                <label for="author">Penulis *</label>
                <input type="text" id="author" name="author" value="{{ old('author') }}" required 
                       placeholder="Masukkan nama penulis">
            </div>
            
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" 
                       placeholder="Masukkan ISBN (opsional)">
            </div>
            
            <div class="form-group">
                <label for="type">Jenis *</label>
                <select id="type" name="type" required>
                    <option value="">Pilih jenis</option>
                    <option value="light_novel" {{ old('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    <option value="manga" {{ old('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="publisher">Penerbit</label>
                <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}" 
                       placeholder="Masukkan nama penerbit">
            </div>
            
            <div class="form-group">
                <label for="publication_date">Tanggal Terbit</label>
                <input type="date" id="publication_date" name="publication_date" value="{{ old('publication_date') }}">
            </div>
            
            <div class="form-group">
                <label for="total_copies">Jumlah Eksemplar *</label>
                <input type="number" id="total_copies" name="total_copies" value="{{ old('total_copies', 1) }}" 
                       min="1" required placeholder="Jumlah eksemplar">
            </div>
            
            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" 
                       min="0" step="100" placeholder="Masukkan harga">
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4" 
                      placeholder="Masukkan deskripsi buku">{{ old('description') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="cover_image">Gambar Sampul</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*">
            <small style="color: #6c757d;">Maksimal 2MB. Format yang didukung: JPEG, PNG, JPG, GIF</small>
        </div>
        
        <div class="text-right">
            <a href="{{ route('books.index') }}" class="btn" style="background: #6c757d;">Batal</a>
            <button type="submit" class="btn btn-success">Buat Buku</button>
        </div>
    </form>
</div>
@endsection