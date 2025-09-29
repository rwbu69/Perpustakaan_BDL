<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    // Middleware is now handled in routes or via attributes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Buku::active();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && in_array($request->get('type'), ['light_novel', 'manga'])) {
            $query->where('type', $request->get('type'));
        }

        // Filter by availability
        if ($request->has('available') && $request->get('available') == '1') {
            $query->available();
        }

        $books = $query->orderBy('title')->paginate(12);

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:buku,isbn',
            'type' => 'required|in:light_novel,manga',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'total_copies' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['available_copies'] = $data['total_copies'];

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        $buku->load(['peminjaman.user']);
        return view('books.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        return view('books.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:buku,isbn,' . $buku->id,
            'type' => 'required|in:light_novel,manga',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'total_copies' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($buku->cover_image) {
                Storage::disk('public')->delete($buku->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        // Adjust available copies if total copies changed
        if ($data['total_copies'] != $buku->total_copies) {
            $difference = $data['total_copies'] - $buku->total_copies;
            $data['available_copies'] = max(0, $buku->available_copies + $difference);
        }

        $buku->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        // Check if book has active borrowings
        if ($buku->activeBorrowings()->count() > 0) {
            return redirect()->route('books.index')
                ->with('error', 'Cannot delete book with active borrowings!');
        }

        // Delete cover image if exists
        if ($buku->cover_image) {
            Storage::disk('public')->delete($buku->cover_image);
        }

        $buku->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }

    /**
     * Borrow a book
     */
    public function borrow(Request $request, Buku $buku)
    {
        if (!$buku->isAvailable()) {
            return back()->with('error', 'This book is not available for borrowing.');
        }

        // Check if user already has this book borrowed
        $existingBorrowing = auth()->user()->activeBorrowings()
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingBorrowing) {
            return back()->with('error', 'You already have this book borrowed.');
        }

        $borrowing = \App\Models\Peminjaman::createBorrowing(auth()->id(), $buku->id);

        if ($borrowing) {
            return back()->with('success', 'Book borrowed successfully! Please return within 7 days.');
        }

        return back()->with('error', 'Failed to borrow book. Please try again.');
    }
}
