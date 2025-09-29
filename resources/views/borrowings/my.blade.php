@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<h1>Peminjaman Saya</h1>

@if($activeBorrowings->count() > 0)
<div class="card">
    <h3>Sedang Dipinjam ({{ $activeBorrowings->count() }})</h3>
    
    <div class="grid grid-2">
        @foreach($activeBorrowings as $borrowing)
        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
            <h4>{{ $borrowing->buku->title }}</h4>
            <p><strong>Penulis:</strong> {{ $borrowing->buku->author }}</p>
            <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</p>
            <p><strong>Dipinjam:</strong> {{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</p>
            
            @if($borrowing->isOverdue())
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 3px; margin: 10px 0;">
                    <strong>TERLAMBAT!</strong> {{ $borrowing->getDaysOverdue() }} hari<br>
                    <strong>Denda:</strong> Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                </div>
            @endif
            
            <div style="margin-top: 10px;">
                <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm">Lihat Detail</a>
                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Return Book</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card text-center">
    <h3>No Active Borrowings</h3>
    <p>You don't have any books currently borrowed.</p>
    <a href="{{ route('books.index') }}" class="btn">Browse Books</a>
</div>
@endif

<div class="card">
    <h3>Borrowing History</h3>
    
    @if($borrowingHistory->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Borrowed</th>
                    <th>Due/Returned</th>
                    <th>Status</th>
                    <th>Fine</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowingHistory as $borrowing)
                <tr>
                    <td>{{ $borrowing->buku->title }}</td>
                    <td>{{ $borrowing->buku->author }}</td>
                    <td>{{ $borrowing->tanggal_pinjam->format('M d, Y') }}</td>
                    <td>
                        @if($borrowing->tanggal_kembali_aktual)
                            {{ $borrowing->tanggal_kembali_aktual->format('M d, Y') }}
                        @else
                            {{ $borrowing->tanggal_kembali_rencana->format('M d, Y') }}
                        @endif
                    </td>
                    <td>
                        <span class="btn btn-sm 
                            @if($borrowing->status == 'dipinjam') 
                                @if($borrowing->isOverdue()) btn-danger @else btn-warning @endif
                            @elseif($borrowing->status == 'dikembalikan') btn-success
                            @else btn-danger @endif">
                            @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
                                Overdue
                            @else
                                {{ ucfirst($borrowing->status) }}
                            @endif
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
            @if($borrowingHistory->hasPages())
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                    @if($borrowingHistory->onFirstPage())
                        <span class="btn" style="background: #ccc;">Previous</span>
                    @else
                        <a href="{{ $borrowingHistory->previousPageUrl() }}" class="btn">Previous</a>
                    @endif
                    
                    <span>Page {{ $borrowingHistory->currentPage() }} of {{ $borrowingHistory->lastPage() }}</span>
                    
                    @if($borrowingHistory->hasMorePages())
                        <a href="{{ $borrowingHistory->nextPageUrl() }}" class="btn">Next</a>
                    @else
                        <span class="btn" style="background: #ccc;">Next</span>
                    @endif
                </div>
            @endif
        </div>
    @else
        <p>No borrowing history yet.</p>
    @endif
</div>
@endsection