@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Detail Peminjaman</h1>
    <a href="{{ auth()->user()->isAdmin() ? route('borrowings.index') : route('borrowings.my') }}" class="btn">Kembali</a>
</div>

<div class="grid grid-2">
    <div class="card">
        <h3>Informasi Buku</h3>
        
        @if($borrowing->buku->cover_image)
            <img src="{{ asset('storage/' . $borrowing->buku->cover_image) }}" alt="{{ $borrowing->buku->title }}" 
                 style="width: 200px; height: 250px; object-fit: cover; margin-bottom: 15px;">
        @endif
        
        <h4>{{ $borrowing->buku->title }}</h4>
        <p><strong>Penulis:</strong> {{ $borrowing->buku->author }}</p>
        <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</p>
        @if($borrowing->buku->publisher)
            <p><strong>Penerbit:</strong> {{ $borrowing->buku->publisher }}</p>
        @endif
        @if($borrowing->buku->isbn)
            <p><strong>ISBN:</strong> {{ $borrowing->buku->isbn }}</p>
        @endif
        
        <div class="mt-20">
            <a href="{{ route('books.show', $borrowing->buku) }}" class="btn btn-sm">Lihat Detail Buku</a>
        </div>
    </div>
    
    <div class="card">
        <h3>Borrowing Information</h3>
        
        @if(auth()->user()->isAdmin())
            <p><strong>Borrower:</strong> {{ $borrowing->user->name }} ({{ $borrowing->user->username }})</p>
            @if($borrowing->user->email)
                <p><strong>Email:</strong> {{ $borrowing->user->email }}</p>
            @endif
            @if($borrowing->user->phone)
                <p><strong>Phone:</strong> {{ $borrowing->user->phone }}</p>
            @endif
        @endif
        
        <p><strong>Borrowed Date:</strong> {{ $borrowing->tanggal_pinjam->format('l, M d, Y') }}</p>
        <p><strong>Due Date:</strong> {{ $borrowing->tanggal_kembali_rencana->format('l, M d, Y') }}</p>
        
        @if($borrowing->tanggal_kembali_aktual)
            <p><strong>Returned Date:</strong> {{ $borrowing->tanggal_kembali_aktual->format('l, M d, Y') }}</p>
        @endif
        
        <p><strong>Status:</strong> 
            <span class="btn btn-sm 
                @if($borrowing->status == 'dipinjam') 
                    @if($borrowing->isOverdue()) btn-danger @else btn-warning @endif
                @elseif($borrowing->status == 'dikembalikan') btn-success
                @else btn-danger @endif">
                @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
                    Overdue ({{ $borrowing->getDaysOverdue() }} days)
                @else
                    {{ ucfirst($borrowing->status) }}
                @endif
            </span>
        </p>
        
        @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h4 style="margin: 0 0 10px 0;">⚠️ Overdue Notice</h4>
                <p><strong>Days Overdue:</strong> {{ $borrowing->getDaysOverdue() }} days</p>
                <p><strong>Current Fine:</strong> Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}</p>
                <p style="margin: 0;"><strong>Fine Rate:</strong> Rp 7,000 per day</p>
            </div>
        @endif
        
        @if($borrowing->denda > 0)
            <p><strong>Fine Amount:</strong> 
                <span style="color: #e74c3c; font-weight: bold;">
                    Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                </span>
            </p>
        @endif
        
        @if($borrowing->catatan)
            <p><strong>Notes:</strong></p>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 3px; margin-top: 5px;">
                {{ $borrowing->catatan }}
            </div>
        @endif
        
        <!-- Actions -->
        <div style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px;">
            @if($borrowing->status == 'dipinjam')
                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Return Book</button>
                </form>
            @endif
            
            @if(auth()->user()->isAdmin())
                <button onclick="toggleNotesForm()" class="btn" style="margin-top: 10px;">{{ $borrowing->catatan ? 'Edit' : 'Add' }} Notes</button>
                
                <div id="notesForm" class="{{ $borrowing->catatan ? '' : 'hidden' }}" style="margin-top: 15px;">
                    <form method="POST" action="{{ route('borrowings.update', $borrowing) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="catatan">Admin Notes</label>
                            <textarea id="catatan" name="catatan" rows="3" 
                                      placeholder="Add notes about this borrowing">{{ $borrowing->catatan }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm">Save Notes</button>
                        <button type="button" onclick="toggleNotesForm()" class="btn btn-sm" style="background: #6c757d;">Cancel</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleNotesForm() {
    const form = document.getElementById('notesForm');
    form.classList.toggle('hidden');
}
</script>
@endsection