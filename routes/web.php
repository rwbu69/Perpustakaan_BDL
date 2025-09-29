<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin only routes (must come before parameterized routes)
    Route::middleware('admin')->group(function () {
        // Book management
        Route::get('/books/create', [BukuController::class, 'create'])->name('books.create');
        Route::post('/books', [BukuController::class, 'store'])->name('books.store');
        Route::get('/books/{buku}/edit', [BukuController::class, 'edit'])->name('books.edit');
        Route::put('/books/{buku}', [BukuController::class, 'update'])->name('books.update');
        Route::delete('/books/{buku}', [BukuController::class, 'destroy'])->name('books.destroy');
        
        // Borrowing management
        Route::get('/borrowings/create', [PeminjamanController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [PeminjamanController::class, 'store'])->name('borrowings.store');
        Route::put('/borrowings/{borrowing}', [PeminjamanController::class, 'update'])->name('borrowings.update');
        Route::get('/overdue', [PeminjamanController::class, 'overdue'])->name('borrowings.overdue');
    });
    
    // Books routes - all users can view (these come after specific routes)
    Route::get('/books', [BukuController::class, 'index'])->name('books.index');
    Route::get('/books/{buku}', [BukuController::class, 'show'])->name('books.show');
    Route::post('/books/{buku}/borrow', [BukuController::class, 'borrow'])->name('books.borrow');
    
    // Borrowings routes - all users
    Route::get('/borrowings', [PeminjamanController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [PeminjamanController::class, 'show'])->name('borrowings.show');
    Route::post('/borrowings/{borrowing}/return', [PeminjamanController::class, 'return'])->name('borrowings.return');
    Route::get('/my-borrowings', [PeminjamanController::class, 'myBorrowings'])->name('borrowings.my');
});
