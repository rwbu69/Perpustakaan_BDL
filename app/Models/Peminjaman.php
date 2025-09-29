<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'denda' => 'decimal:2',
    ];

    const FINE_PER_DAY = 7000; // 7000 rupiah per day

    /**
     * Get the user who borrowed the book
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed book
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'dipinjam') {
            return false;
        }
        
        return Carbon::now()->gt($this->tanggal_kembali_rencana);
    }

    /**
     * Calculate fine for overdue books
     */
    public function calculateFine(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $returnDate = $this->tanggal_kembali_aktual ?? Carbon::now();
        $daysOverdue = $this->tanggal_kembali_rencana->diffInDays($returnDate, false);
        
        if ($daysOverdue > 0) {
            return $daysOverdue * self::FINE_PER_DAY;
        }

        return 0;
    }

    /**
     * Get days overdue
     */
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $checkDate = $this->tanggal_kembali_aktual ?? Carbon::now();
        return max(0, $this->tanggal_kembali_rencana->diffInDays($checkDate, false));
    }

    /**
     * Return the book and calculate fine
     */
    public function returnBook(): void
    {
        $this->tanggal_kembali_aktual = Carbon::now();
        $this->denda = $this->calculateFine();
        $this->status = $this->denda > 0 ? 'terlambat' : 'dikembalikan';
        $this->save();

        // Increase available copies of the book
        $this->buku->return();
    }

    /**
     * Scope for overdue borrowings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali_rencana', '<', Carbon::now());
    }

    /**
     * Scope for active borrowings
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope for returned borrowings
     */
    public function scopeReturned($query)
    {
        return $query->whereIn('status', ['dikembalikan', 'terlambat']);
    }

    /**
     * Create a new borrowing record
     */
    public static function createBorrowing($userId, $bukuId, $borrowDays = 7): ?self
    {
        $buku = Buku::find($bukuId);
        
        if (!$buku || !$buku->isAvailable()) {
            return null;
        }

        $borrowing = self::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays($borrowDays),
            'status' => 'dipinjam',
        ]);

        // Decrease available copies
        $buku->borrow();

        return $borrowing;
    }
}
