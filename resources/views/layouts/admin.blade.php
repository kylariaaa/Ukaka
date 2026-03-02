<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - KylariaSHOP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F5F5] font-inter min-h-screen flex">

    {{-- SIDEBAR --}}
    {{-- Dark red color matched from design: #4A0505 --}}
    <aside class="w-64 bg-[#4A0505] min-h-screen flex flex-col justify-between py-12 px-6 fixed left-0 top-0">
        
        <div class="space-y-16">
            {{-- Logo --}}
            <div class="text-center">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-3xl font-black leading-tight tracking-wide block">
                    Kylaria<br>SHOP
                </a>
            </div>

            {{-- Nav Links --}}
            <nav class="space-y-6 pl-2">
                <a href="{{ route('admin.products.create') }}" class="flex items-center justify-between {{ request()->routeIs('admin.products.create') ? 'text-white font-bold' : 'text-[#E8E8E8] font-medium' }} hover:text-white transition-colors group">
                    <span class="text-lg">add product</span>
                    {{-- Plus Icon --}}
                    <img src="{{ asset('images/add-icon.png') }}" alt="Add" class="w-5 h-5 opacity-80 group-hover:opacity-100">
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') ? 'text-white font-bold' : 'text-[#E8E8E8] font-medium' }} hover:text-white transition-colors group">
                    <span class="text-lg">List product</span>
                    {{-- List Icon --}}
                    <img src="{{ asset('images/list-icon.png') }}" alt="List" class="w-5 h-5 opacity-80 group-hover:opacity-100">
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between {{ request()->routeIs('admin.orders.index') ? 'text-white font-bold' : 'text-[#E8E8E8] font-medium' }} hover:text-white transition-colors group">
                    <span class="text-lg">Products ordered</span>
                    {{-- Order Receipt Icon --}}
                    <img src="{{ asset('images/order-icon.png') }}" alt="Orders" class="w-5 h-5 opacity-80 group-hover:opacity-100">
                </a>
            </nav>
        </div>

        {{-- Logout --}}
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-[#FF0000] text-white font-bold text-lg py-3 rounded-xl hover:bg-red-600 transition-colors shadow-none text-center block">
                    LOGOUT
                </button>
            </form>
        </div>
        
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 ml-64 p-10 min-h-screen">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

</body>
</html>
