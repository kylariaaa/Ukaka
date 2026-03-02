<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KylariaSHOP — Toko hobby figure, acrylic stand, dan merchandise anime terpercaya.">
    <title>{{ $title ?? 'KylariaSHOP' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-inter min-h-screen flex flex-col">

{{-- NAVBAR --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900 tracking-tight hover:text-orange transition-colors">
                KylariaSHOP
            </a>

            {{-- Search Bar --}}
            @if($showSearch)
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <form action="{{ route('products.index') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('images/search-icon.png') }}" alt="Search" class="w-4 h-4 text-gray-400">
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tobat apa hari ini?" class="w-full pl-10 pr-4 py-2 bg-gray-100 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-orange/50 focus:border-orange transition-all">
                </form>
            </div>
            @endif

            {{-- Right side icons --}}
            <div class="flex items-center gap-4">
                {{-- Cart icon --}}
                <a href="{{ route('cart') }}" class="relative p-2 text-gray-800 hover:text-orange transition-colors">
                    <img src="{{ asset('images/carticon.png') }}" alt="Cart" class="w-6 h-6 hover:scale-110 transition-transform">
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-orange text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- History icon --}}
                <a href="{{ route('payment-history') }}" class="p-2 text-gray-800 hover:text-orange transition-colors" title="Riwayat Pembayaran">
                    <img src="{{ asset('images/history-pay.png') }}" alt="History" class="w-6 h-6 hover:scale-110 transition-transform">
                </a>

                {{-- Divider --}}
                <div class="w-px h-6 bg-gray-200"></div>

                {{-- Profile Dropdown --}}
                <div class="relative" id="profile-dropdown">
                    <button onclick="toggleDropdown()" class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors py-2 px-1">
                        <span>PROFILE</span>
                        <img src="{{ asset('images/drop-icon.png') }}" alt="▼" class="w-4 h-4 text-gray-400">
                    </button>

                    <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                        @guest
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange/5 hover:text-orange transition-colors">
                                <img src="{{ asset('images/login-icon.png') }}" alt="Login" class="w-4 h-4">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange/5 hover:text-orange transition-colors">
                                <img src="{{ asset('images/regis-icon.png') }}" alt="Register" class="w-4 h-4">
                                Register
                            </a>
                        @else
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <img src="{{ asset('images/logout-icon.png') }}" alt="Logout" class="w-4 h-4">
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
<div class="bg-green-50 border-b border-green-200 px-4 py-3">
    <div class="max-w-7xl mx-auto flex items-center gap-2">
        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif
@if(session('error'))
<div class="bg-red-50 border-b border-red-200 px-4 py-3">
    <div class="max-w-7xl mx-auto flex items-center gap-2">
        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
</div>
@endif

{{-- Main Content --}}
<main class="flex-1">
    {{ $slot }}
</main>

{{-- FOOTER --}}
<footer class="bg-white border-t border-gray-100 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Brand --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Kylaria SHOP</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-4">
                    Kylaria Shop is a place that sells hobby toys ranging from figures to costumes.
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-orange/10 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-orange/10 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-orange/10 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Payment --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Payment</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center justify-center bg-gray-50 rounded-lg px-3 py-2 w-full h-10">
                        <img src="{{ asset('images/paypal.png') }}" alt="PayPal" class="max-h-5 object-contain">
                    </div>
                    <div class="flex items-center justify-center bg-gray-50 rounded-lg px-3 py-2 w-full h-10">
                        <img src="{{ asset('images/dana.png') }}" alt="DANA" class="max-h-5 object-contain">
                    </div>
                    <div class="flex items-center justify-center bg-gray-50 rounded-lg px-3 py-2 w-full h-10">
                        <img src="{{ asset('images/bca.png') }}" alt="BCA" class="max-h-5 object-contain">
                    </div>
                    <div class="flex items-center justify-center bg-gray-50 rounded-lg px-3 py-2 w-full h-10">
                        <img src="{{ asset('images/mandiri.jpg') }}" alt="Mandiri" class="max-h-5 object-contain mix-blend-multiply rounded-sm">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Coming Soon.....</p>
            </div>

            {{-- Support --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-gray-500 hover:text-orange transition-colors">Contact</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-orange transition-colors">Support</a></li>
                    <li><a href="#" class="text-sm text-gray-500 hover:text-orange transition-colors">Legal</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} KylariaSHOP. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profile-dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('dropdown-menu').classList.add('hidden');
        }
    });
</script>
</body>
</html>
