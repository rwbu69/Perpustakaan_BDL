@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Edit Buku: {{ $buku->title }}</h1>
    <div>
        <a href="{{ route('books.show', $buku) }}" class="btn">Lihat Buku</a>
        <a href="{{ route('books.index') }}" class="btn">Kembali ke Daftar Buku</a>
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('books.update', $buku) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-2">
            <div class="form-group">
                <label for="title">Judul *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $buku->title) }}" required 
                       placeholder="Masukkan judul buku">
            </div>
            
            <div class="form-group">
                <label for="author">Penulis *</label>
                <input type="text" id="author" name="author" value="{{ old('author', $buku->author) }}" required 
                       placeholder="Masukkan nama penulis">
            </div>
            
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $buku->isbn) }}" 
                       placeholder="Masukkan ISBN (opsional)">
            </div>
            
            <div class="form-group">
                <label for="type">Jenis *</label>
                <select id="type" name="type" required>
                    <option value="">Pilih jenis</option>
                    <option value="light_novel" {{ old('type', $buku->type) == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    <option value="manga" {{ old('type', $buku->type) == 'manga' ? 'selected' : '' }}>Manga</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="publisher">Penerbit</label>
                <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $buku->publisher) }}" 
                       placeholder="Masukkan nama penerbit">
            </div>
            
            <div class="form-group">
                <label for="publication_date">Tanggal Terbit</label>
                <input type="date" id="publication_date" name="publication_date" 
                       value="{{ old('publication_date', $buku->publication_date ? $buku->publication_date->format('Y-m-d') : '') }}">
            </div>
            
            <div class="form-group">
                <label for="total_copies">Jumlah Eksemplar *</label>
                <input type="number" id="total_copies" name="total_copies" 
                       value="{{ old('total_copies', $buku->total_copies) }}" min="1" required 
                       placeholder="Jumlah eksemplar">
                <small style="color: #6c757d;">Tersedia saat ini: {{ $buku->available_copies }}</small>
            </div>
            
            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $buku->price) }}" 
                       min="0" step="100" placeholder="Masukkan harga">
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4" 
                      placeholder="Masukkan deskripsi buku">{{ old('description', $buku->description) }}</textarea>
        </div>
        
        <div class="grid grid-2">
            <div class="form-group">
                <label for="cover_image">Gambar Sampul</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*">
                <small style="color: #6c757d;">Maksimal 2MB. Kosongkan untuk tetap menggunakan gambar saat ini.</small>
            </div>
            
            <div class="form-group">
                <label>Sampul Saat Ini</label>
                @if($buku->cover_image)
                    <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="Sampul saat ini" 
                         style="width: 100px; height: 130px; object-fit: cover; border: 1px solid #ddd;">
                @else
                    <p style="color: #6c757d;">Tidak ada gambar sampul</p>
                @endif
            </div>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;">
                <input type="checkbox" name="is_active" value="1" 
                       {{ old('is_active', $buku->is_active) ? 'checked' : '' }} style="width: auto;">
                Buku aktif (tersedia untuk dipinjam)
            </label>
        </div>
        
        <div class="text-right">
            <a href="{{ route('books.show', $buku) }}" class="btn" style="background: #6c757d;">Batal</a>
            <button type="submit" class="btn btn-warning">Perbarui Buku</button>
        </div>
    </form>
</div>
@endsection