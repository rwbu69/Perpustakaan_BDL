@extends('layouts.app')

@section('title', 'Buku Terlambat')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Buku Terlambat ({{ $overdueBorrowings->count() }})</h1>
    <a href="{{ route('borrowings.index') }}" class="btn">Kembali ke Semua Peminjaman</a>
</div>

@if($overdueBorrowings->count() > 0)
    <div class="alert alert-warning">
        <strong>Catatan:</strong> Buku-buku ini telah melewati tanggal jatuh tempo. Denda dihitung Rp 7.000 per hari.
    </div>
    
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Book</th>
                    <th>Due Date</th>
                    <th>Days Overdue</th>
                    <th>Current Fine</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueBorrowings as $borrowing)
                <tr style="background: #fff3cd;">
                    <td>
                        <strong>{{ $borrowing->user->name }}</strong><br>
                        <small>{{ $borrowing->user->username }}</small>
                    </td>
                    <td>
                        @if($borrowing->user->email)
                            <small>{{ $borrowing->user->email }}</small><br>
                        @endif
                        @if($borrowing->user->phone)
                            <small>{{ $borrowing->user->phone }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $borrowing->buku->title }}</strong><br>
                        <small>by {{ $borrowing->buku->author }}</small><br>
                        <small>{{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</small>
                    </td>
                    <td>{{ $borrowing->tanggal_kembali_rencana->format('M d, Y') }}</td>
                    <td>
                        <span style="color: #e74c3c; font-weight: bold;">
                            {{ $borrowing->getDaysOverdue() }} days
                        </span>
                    </td>
                    <td>
                        <span style="color: #e74c3c; font-weight: bold;">
                            Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm">View</a>
                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Force Return</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td colspan="5" class="text-right">Total Outstanding Fines:</td>
                    <td style="color: #e74c3c;">
                        Rp {{ number_format($overdueBorrowings->sum(function($b) { return $b->calculateFine(); }), 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="card">
        <h3>Quick Actions</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('borrowings.index', ['status' => 'dipinjam']) }}" class="btn">View All Active Borrowings</a>
            <a href="{{ route('borrowings.create') }}" class="btn btn-success">Create New Borrowing</a>
        </div>
    </div>
@else
    <div class="card text-center">
        <h3>🎉 No Overdue Books!</h3>
        <p>All borrowed books are returned on time or still within the borrowing period.</p>
        <a href="{{ route('borrowings.index') }}" class="btn">View All Borrowings</a>
    </div>
@endif
@endsection