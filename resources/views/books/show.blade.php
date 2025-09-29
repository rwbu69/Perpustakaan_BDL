@extends('layouts.app')

@section('title', $buku->title)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>{{ $buku->title }}</h1>
    <a href="{{ route('books.index') }}" class="btn">Kembali ke Daftar Buku</a>
</div>

<div class="grid grid-3">
    <div class="card">
        @if($buku->cover_image)
            <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="{{ $buku->title }}" 
                 style="width: 100%; height: 300px; object-fit: cover; margin-bottom: 15px;">
        @else
            <div style="width: 100%; height: 300px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border: 1px solid #ddd;">
                <span style="color: #6c757d; font-size: 2rem;">📚</span>
            </div>
        @endif
        
        @if(auth()->user()->isUser())
            @if($buku->isAvailable())
                <form method="POST" action="{{ route('books.borrow', $buku) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" style="width: 100%;">Pinjam Buku Ini</button>
                </form>
            @else
                <button class="btn" style="width: 100%; background: #ccc;" disabled>Tidak Tersedia</button>
            @endif
        @endif
    </div>
    
    <div style="grid-column: span 2;">
        <div class="card">
            <h2>Detail Buku</h2>
            
            <div class="grid grid-2 mt-20">
                <div>
                    <p><strong>Penulis:</strong> {{ $buku->author }}</p>
                    <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $buku->type)) }}</p>
                    <p><strong>Penerbit:</strong> {{ $buku->publisher ?? 'Tidak ditetapkan' }}</p>
                    <p><strong>Tanggal Terbit:</strong> {{ $buku->publication_date ? $buku->publication_date->format('d M Y') : 'Tidak ditetapkan' }}</p>
                </div>
                
                <div>
                    <p><strong>ISBN:</strong> {{ $buku->isbn ?? 'Tidak ditetapkan' }}</p>
                    <p><strong>Eksemplar Tersedia:</strong> {{ $buku->available_copies }}/{{ $buku->total_copies }}</p>
                    <p><strong>Status:</strong> 
                        <span class="btn btn-sm {{ $buku->is_active ? 'btn-success' : 'btn-danger' }}">
                            {{ $buku->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </p>
                    @if($buku->price)
                        <p><strong>Harga:</strong> Rp {{ number_format($buku->price, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            
            @if($buku->description)
                <div class="mt-20">
                    <strong>Deskripsi:</strong>
                    <p style="margin-top: 5px;">{{ $buku->description }}</p>
                </div>
            @endif
            
            @if(auth()->user()->isAdmin())
                <div class="mt-20" style="border-top: 1px solid #ddd; padding-top: 20px;">
                    <a href="{{ route('books.edit', $buku) }}" class="btn btn-warning">Edit Buku</a>
                    <form method="POST" action="{{ route('books.destroy', $buku) }}" style="display: inline; margin-left: 10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus Buku</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() && $buku->peminjaman->count() > 0)
<div class="card">
    <h3>Riwayat Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku->peminjaman as $borrowing)
            <tr>
                <td>{{ $borrowing->user->name }}</td>
                <td>{{ $borrowing->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</td>
                <td>{{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('d M Y') : '-' }}</td>
                <td>
                    <span class="btn btn-sm 
                        @if($borrowing->status == 'dipinjam') btn-warning
                        @elseif($borrowing->status == 'dikembalikan') btn-success
                        @else btn-danger @endif">
                        {{ $borrowing->status == 'dipinjam' ? 'Dipinjam' : ($borrowing->status == 'dikembalikan' ? 'Dikembalikan' : ucfirst($borrowing->status)) }}
                    </span>
                </td>
                <td>
                    @if($borrowing->denda > 0)
                        Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection