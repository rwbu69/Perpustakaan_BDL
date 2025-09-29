@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
<h1>Selamat Datang, {{ auth()->user()->name }}!</h1>

<div class="grid grid-3 mb-20">
    <div class="card text-center">
        <h3>{{ $activeBorrowings->count() }}</h3>
        <p>Sedang Dipinjam</p>
    </div>
    
    <div class="card text-center">
        <h3>{{ $borrowingHistory->count() }}</h3>
        <p>Total Peminjaman</p>
    </div>
    
    <div class="card text-center">
        <h3 style="color: #e74c3c;">Rp {{ number_format($totalFines, 0, ',', '.') }}</h3>
        <p>Total Denda</p>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <h3>Buku yang Sedang Dipinjam</h3>
        @if($activeBorrowings->count() > 0)
            @foreach($activeBorrowings as $borrowing)
            <div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;">
                <h4>{{ $borrowing->buku->title }}</h4>
                <p><strong>Penulis:</strong> {{ $borrowing->buku->author }}</p>
                <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</p>
                <p><strong>Dipinjam:</strong> {{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</p>
                
                @if($borrowing->isOverdue())
                    <p style="color: #e74c3c;"><strong>TERLAMBAT!</strong> {{ $borrowing->getDaysOverdue() }} hari</p>
                    <p style="color: #e74c3c;"><strong>Denda:</strong> Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}</p>
                @endif
                
                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Kembalikan Buku</button>
                </form>
            </div>
            @endforeach
        @else
            <p>Anda belum meminjam buku apapun.</p>
            <a href="{{ route('books.index') }}" class="btn">Jelajahi Buku</a>
        @endif
    </div>
    
    <div class="card">
        <h3>Buku Tersedia</h3>
        @if($availableBooks->count() > 0)
            @foreach($availableBooks as $book)
            <div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;">
                <h4>{{ $book->title }}</h4>
                <p><strong>Penulis:</strong> {{ $book->author }}</p>
                <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $book->type)) }}</p>
                <p><strong>Tersedia:</strong> {{ $book->available_copies }}/{{ $book->total_copies }}</p>
                
                <div style="margin-top: 10px;">
                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm">Lihat Detail</a>
                    @if($book->isAvailable())
                        <form method="POST" action="{{ route('books.borrow', $book) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Pinjam</button>
                        </form>
                    @else
                        <span class="btn btn-sm" style="background: #ccc;">Tidak Tersedia</span>
                    @endif
                </div>
            </div>
            @endforeach
            <div class="text-center mt-20">
                <a href="{{ route('books.index') }}" class="btn">Jelajahi Semua Buku</a>
            </div>
        @else
            <p>Tidak ada buku tersedia saat ini.</p>
        @endif
    </div>
</div>

<div class="card">
    <h3>Riwayat Peminjaman Terbaru</h3>
    @if($borrowingHistory->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Dipinjam</th>
                    <th>Jatuh Tempo/Dikembalikan</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowingHistory as $borrowing)
                <tr>
                    <td>{{ $borrowing->buku->title }}</td>
                    <td>{{ $borrowing->tanggal_pinjam->format('d M Y') }}</td>
                    <td>
                        @if($borrowing->tanggal_kembali_aktual)
                            {{ $borrowing->tanggal_kembali_aktual->format('d M Y') }}
                        @else
                            {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                        @endif
                    </td>
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
        <div class="text-center mt-20">
            <a href="{{ route('borrowings.my') }}" class="btn">Lihat Semua Peminjaman Saya</a>
        </div>
    @else
        <p>Belum ada riwayat peminjaman.</p>
    @endif
</div>
@endsection