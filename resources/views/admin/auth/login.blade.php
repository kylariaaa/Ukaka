<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — KylariaSHOP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter min-h-screen bg-gray-50 flex">

    {{-- Left Panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#4A0505] relative overflow-hidden flex-col items-center justify-end pb-12">
        <div class="absolute inset-0 bg-gradient-to-br from-[#4A0505] via-red-900 to-[#4A0505] z-10"></div>
        <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-red-500/20 blur-3xl z-0"></div>
        <div class="absolute bottom-20 right-10 w-48 h-48 rounded-full bg-orange-500/10 blur-3xl z-0"></div>
        <div class="relative z-20 text-center">
            <h1 class="text-4xl font-black text-white mb-2">Kylaria SHOP <span class="text-xs align-top font-bold text-red-200">ADMIN</span></h1>
            <p class="text-red-200 text-sm">Administrator Control Panel</p>
        </div>
    </div>

    {{-- Right Panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-100">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-2xl font-black text-[#4A0505]">Kylaria SHOP <span class="text-xs align-top font-bold text-red-700">ADMIN</span></h1>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="mb-7">
                    <h2 class="text-2xl font-black text-gray-900">Admin Login</h2>
                    <p class="text-sm text-gray-500 mt-1">Authorized access only</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Admin Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            placeholder="admin@kylariashop.com"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4A0505]/40 focus:border-[#4A0505] transition-all">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4A0505]/40 focus:border-[#4A0505] transition-all">
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-[#4A0505] hover:bg-red-900 text-white font-black py-3 rounded-xl transition-colors uppercase tracking-wider text-sm">
                        LOGIN TO DASHBOARD
                    </button>

                    <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                            &larr; Back to Shop
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
