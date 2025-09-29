@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>{{ auth()->user()->isAdmin() ? 'Semua Peminjaman' : 'Peminjaman Saya' }}</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('borrowings.create') }}" class="btn btn-success">Buat Peminjaman Baru</a>
    @endif
</div>

<!-- Filters -->
<div class="card">
    <form method="GET" action="{{ route('borrowings.index') }}">
        <div class="grid grid-3">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat Dikembalikan</option>
                </select>
            </div>
            
            @if(auth()->user()->isAdmin())
            <div class="form-group">
                <label for="overdue">Hanya Terlambat</label>
                <select id="overdue" name="overdue">
                    <option value="">Semua Peminjaman</option>
                    <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Hanya Terlambat</option>
                </select>
            </div>
            @endif
            
            <div class="form-group">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn">Filter</button>
                    <a href="{{ route('borrowings.index') }}" class="btn" style="background: #6c757d;">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

@if($borrowings->count() > 0)
    <div class="card">
        <table>
            <thead>
                <tr>
                    @if(auth()->user()->isAdmin())
                        <th>User</th>
                    @endif
                    <th>Book</th>
                    <th>Type</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Fine</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $borrowing)
                <tr>
                    @if(auth()->user()->isAdmin())
                        <td>{{ $borrowing->user->name }}</td>
                    @endif
                    <td>
                        <strong>{{ $borrowing->buku->title }}</strong><br>
                        <small>by {{ $borrowing->buku->author }}</small>
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</td>
                    <td>{{ $borrowing->tanggal_pinjam->format('M d, Y') }}</td>
                    <td>
                        {{ $borrowing->tanggal_kembali_rencana->format('M d, Y') }}
                        @if($borrowing->isOverdue() && $borrowing->status == 'dipinjam')
                            <br><small style="color: #e74c3c;">{{ $borrowing->getDaysOverdue() }} days overdue</small>
                        @endif
                    </td>
                    <td>{{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('M d, Y') : '-' }}</td>
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
                    <td>
                        <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm">View</a>
                        @if($borrowing->status == 'dipinjam')
                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Return</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="text-center mt-20">
            @if($borrowings->hasPages())
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                    @if($borrowings->onFirstPage())
                        <span class="btn" style="background: #ccc;">Previous</span>
                    @else
                        <a href="{{ $borrowings->previousPageUrl() }}" class="btn">Previous</a>
                    @endif
                    
                    <span>Page {{ $borrowings->currentPage() }} of {{ $borrowings->lastPage() }}</span>
                    
                    @if($borrowings->hasMorePages())
                        <a href="{{ $borrowings->nextPageUrl() }}" class="btn">Next</a>
                    @else
                        <span class="btn" style="background: #ccc;">Next</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
@else
    <div class="card text-center">
        <h3>No borrowings found</h3>
        <p>{{ request()->hasAny(['status', 'overdue']) ? 'Try adjusting your filters.' : 'No borrowings have been created yet.' }}</p>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('borrowings.create') }}" class="btn btn-success">Create First Borrowing</a>
        @endif
    </div>
@endif
@endsection