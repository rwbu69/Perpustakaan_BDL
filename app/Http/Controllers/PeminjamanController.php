<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;

class PeminjamanController extends Controller
{
    // Middleware is now handled in routes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Peminjaman::with(['user', 'buku']);

        // If user is not admin, only show their borrowings
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->has('status') && in_array($request->get('status'), ['dipinjam', 'dikembalikan', 'terlambat'])) {
            $query->where('status', $request->get('status'));
        }

        // Filter by overdue (admin only)
        if ($request->has('overdue') && $request->get('overdue') == '1' && $user->isAdmin()) {
            $query->overdue();
        }

        $borrowings = $query->latest()->paginate(15);

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource (Admin only).
     */
    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $books = Buku::active()->available()->orderBy('title')->get();

        return view('borrowings.create', compact('users', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'borrow_days' => 'required|integer|min:1|max:30',
        ]);

        $user = User::find($request->user_id);
        $buku = Buku::find($request->buku_id);

        if ($user->role !== 'user') {
            return back()->with('error', 'Selected user is not a regular user.');
        }

        if (!$buku->isAvailable()) {
            return back()->with('error', 'Selected book is not available.');
        }

        // Check if user already has this book
        $existingBorrowing = $user->activeBorrowings()
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingBorrowing) {
            return back()->with('error', 'User already has this book borrowed.');
        }

        $borrowing = Peminjaman::createBorrowing(
            $request->user_id,
            $request->buku_id,
            $request->borrow_days
        );

        if ($borrowing) {
            return redirect()->route('borrowings.index')
                ->with('success', 'Book borrowed successfully!');
        }

        return back()->with('error', 'Failed to create borrowing.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $borrowing)
    {
        $user = auth()->user();
        
        // Users can only see their own borrowings
        if (!$user->isAdmin() && $borrowing->user_id !== $user->id) {
            abort(403);
        }

        $borrowing->load(['user', 'buku']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Return a borrowed book
     */
    public function return(Peminjaman $borrowing)
    {
        $user = auth()->user();

        // Check permissions
        if (!$user->isAdmin() && $borrowing->user_id !== $user->id) {
            abort(403);
        }

        if ($borrowing->status !== 'dipinjam') {
            return back()->with('error', 'This book has already been returned.');
        }

        $borrowing->returnBook();

        $message = 'Book returned successfully!';
        if ($borrowing->denda > 0) {
            $message .= ' Fine: Rp ' . number_format($borrowing->denda, 0, ',', '.');
        }

        return back()->with('success', $message);
    }

    /**
     * Update borrowing notes (Admin only)
     */
    public function update(Request $request, Peminjaman $borrowing)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $borrowing->update([
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Notes updated successfully!');
    }

    /**
     * Show overdue borrowings (Admin only)
     */
    public function overdue()
    {
        $overdueBorrowings = Peminjaman::overdue()
            ->with(['user', 'buku'])
            ->get();

        return view('borrowings.overdue', compact('overdueBorrowings'));
    }

    /**
     * My borrowings (User view)
     */
    public function myBorrowings()
    {
        $user = auth()->user();
        
        $activeBorrowings = $user->activeBorrowings()->with('buku')->get();
        $borrowingHistory = $user->peminjaman()
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('borrowings.my', compact('activeBorrowings', 'borrowingHistory'));
    }
}
