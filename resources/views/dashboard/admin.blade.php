@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h1>Dashboard Admin</h1>

<div class="grid grid-4 mb-20">
    <div class="card text-center">
        <h3>{{ $totalBooks }}</h3>
        <p>Total Buku</p>
        <a href="{{ route('books.index') }}" class="btn btn-sm">Lihat Buku</a>
    </div>
    
    <div class="card text-center">
        <h3>{{ $totalUsers }}</h3>
        <p>Pengguna Terdaftar</p>
    </div>
    
    <div class="card text-center">
        <h3>{{ $activeBorrowings }}</h3>
        <p>Peminjaman Aktif</p>
        <a href="{{ route('borrowings.index') }}" class="btn btn-sm">Lihat Semua</a>
    </div>
    
    <div class="card text-center">
        <h3 style="color: #e74c3c;">{{ $overdueBorrowings }}</h3>
        <p>Buku Terlambat</p>
        <a href="{{ route('borrowings.overdue') }}" class="btn btn-sm btn-danger">Lihat Terlambat</a>
    </div>
</div>

<div class="grid grid-2">
        <div class="card">
        <h3>Peminjaman Terbaru</h3>
        @if($recentBorrowings->count() > 0)
            <div style="max-height: 300px; overflow-y: auto;">
                @foreach($recentBorrowings as $borrowing)
                    <div style="padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                        <div>
                            <strong>{{ $borrowing->buku->title }}</strong><br>
                            <small>oleh {{ $borrowing->user->name }}</small>
                        </div>
                        <div style="text-align: right;">
                            <small>{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</small><br>
                            <span class="badge badge-{{ $borrowing->status == 'dipinjam' ? 'primary' : 'success' }}">
                                {{ $borrowing->status == 'dipinjam' ? 'Dipinjam' : 'Dikembalikan' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Belum ada peminjaman.</p>
        @endif
    </div>
    
    <div class="card">
        <h3>Buku Terlambat</h3>
        @if($overdueList->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Buku</th>
                        <th>Hari Terlambat</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueList as $overdue)
                    <tr>
                        <td>{{ $overdue->user->name }}</td>
                        <td>{{ $overdue->buku->title }}</td>
                        <td>{{ $overdue->getDaysOverdue() }} hari</td>
                        <td>Rp {{ number_format($overdue->calculateFine(), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada buku terlambat. Bagus!</p>
        @endif
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Aksi Cepat</h3>
    </div>
    <div style="display: flex; gap: 10px; margin-top: 15px;">
        <a href="{{ route('books.create') }}" class="btn btn-success">Tambah Buku Baru</a>
        <a href="{{ route('borrowings.create') }}" class="btn">Buat Peminjaman</a>
        <a href="{{ route('borrowings.overdue') }}" class="btn btn-warning">Cek Terlambat</a>
        <a href="{{ route('borrowings.index') }}" class="btn">Kelola Peminjaman</a>
    </div>
</div>
@endsection