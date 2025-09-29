<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Perpustakaan')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        header { background: #2c3e50; color: white; padding: 1rem 0; }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        nav ul { list-style: none; display: flex; gap: 20px; }
        nav a { color: white; text-decoration: none; padding: 5px 10px; border-radius: 3px; }
        nav a:hover { background: rgba(255,255,255,0.1); }
        
        /* Main content */
        main { padding: 20px 0; min-height: calc(100vh - 140px); }
        .card { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        /* Forms */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #3498db; }
        
        /* Buttons */
        .btn { display: inline-block; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border: none; border-radius: 3px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #219a52; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #d68910; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        tr:hover { background: #f8f9fa; }
        
        /* Alerts */
        .alert { padding: 10px; margin: 10px 0; border-radius: 3px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        
        /* Grid */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: 1fr 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
        
        /* Utility */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-20 { margin-bottom: 20px; }
        .mt-20 { margin-top: 20px; }
        .hidden { display: none; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content { flex-direction: column; gap: 10px; }
            nav ul { flex-wrap: wrap; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            table { font-size: 12px; }
            th, td { padding: 5px; }
        }
        
        /* Footer */
        footer { background: #2c3e50; color: white; text-align: center; padding: 20px 0; margin-top: auto; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Sistem Perpustakaan</div>
                @auth
                <nav>
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('books.index') }}">Buku</a></li>
                        @if(auth()->user()->isAdmin())
                            <li><a href="{{ route('borrowings.index') }}">Semua Peminjaman</a></li>
                            <li><a href="{{ route('borrowings.overdue') }}">Terlambat</a></li>
                        @else
                            <li><a href="{{ route('borrowings.my') }}">Peminjaman Saya</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Keluar ({{ auth()->user()->name }})</button>
                            </form>
                        </li>
                    </ul>
                </nav>
                @endauth
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistem Manajemen Perpustakaan. Fokus pada Light Novel & Manga Jepang.</p>
        </div>
    </footer>
</body>
</html>