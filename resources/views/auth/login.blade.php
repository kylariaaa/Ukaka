<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KylariaSHOP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter min-h-screen bg-gray-50 flex">

    {{-- Left Panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 relative overflow-hidden flex-col items-center justify-end pb-12">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 z-10"></div>
        {{-- Decorative shapes --}}
        <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-orange/20 blur-3xl z-0"></div>
        <div class="absolute bottom-20 right-10 w-48 h-48 rounded-full bg-blue-500/10 blur-3xl z-0"></div>
        <div class="relative z-20 text-center">
            <h1 class="text-4xl font-black text-white mb-2">Kylaria SHOP</h1>
            <p class="text-gray-400 text-sm">Your hobby figures & merchandise store</p>
        </div>
    </div>

    {{-- Right Panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-100">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-2xl font-black text-gray-900">Kylaria SHOP</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="mb-7">
                    <h2 class="text-2xl font-black text-gray-900">Selamat Datang Kembali</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk ke akun KylariaSHOP kamu</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                placeholder="nama@email.com"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange/40 focus:border-orange transition-all">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                placeholder="••••••••"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange/40 focus:border-orange transition-all">
                        </div>
                        <div class="flex justify-end mt-1.5">
                            @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-orange transition-colors">
                                Forgot Your Password?
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-orange focus:ring-orange">
                        <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-orange hover:bg-orange-dark text-white font-black py-3 rounded-xl transition-colors uppercase tracking-wider text-sm">
                        LOGIN
                    </button>

                    <p class="text-center text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-orange hover:text-orange-dark font-semibold transition-colors">
                            Daftar di sini
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
