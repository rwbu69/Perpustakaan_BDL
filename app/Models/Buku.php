<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'type',
        'description',
        'publisher',
        'publication_date',
        'total_copies',
        'available_copies',
        'cover_image',
        'price',
        'is_active',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get all borrowings for this book
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Get active borrowings for this book
     */
    public function activeBorrowings()
    {
        return $this->peminjaman()->where('status', 'dipinjam');
    }

    /**
     * Check if book is available for borrowing
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->is_active;
    }

    /**
     * Decrease available copies when borrowed
     */
    public function borrow(): bool
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
            return true;
        }
        return false;
    }

    /**
     * Increase available copies when returned
     */
    public function return(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    /**
     * Scope for active books only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for available books only
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    /**
     * Scope for light novels
     */
    public function scopeLightNovels($query)
    {
        return $query->where('type', 'light_novel');
    }

    /**
     * Scope for manga
     */
    public function scopeManga($query)
    {
        return $query->where('type', 'manga');
    }
}
