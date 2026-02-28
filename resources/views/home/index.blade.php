<x-app-layout title="KylariaSHOP — Hobby Figures & Merchandise">

    {{-- BANNER/HERO CAROUSEL --}}
    <section class="relative overflow-hidden bg-gray-900">
        <div class="banner-track flex" id="banner-track">
            @php
                $bannerColors = [
                    ['bg' => '#1a1a2e', 'accent' => '#FF8A4C', 'text' => 'Lunar Day Special', 'sub' => 'Diskon up to 15%'],
                    ['bg' => '#16213e', 'accent' => '#4CAF50', 'text' => 'New Arrivals 2025', 'sub' => 'Figure & Model Kit Terbaru'],
                    ['bg' => '#0f3460', 'accent' => '#e94560', 'text' => 'Flash Sale', 'sub' => 'Acrylic Stand & Merchandise'],
                ];
            @endphp
            @foreach($bannerColors as $i => $b)
            <div class="banner-slide flex-shrink-0 w-full h-56 md:h-64 flex items-center justify-center relative"
                 style="background-color: {{ $b['bg'] }};">
                <div class="text-center z-10">
                    <div class="inline-block px-4 py-1 rounded-full text-xs font-bold mb-4 text-white"
                         style="background-color: {{ $b['accent'] }};">
                        ✦ EXCLUSIVE
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-2">{{ $b['text'] }}</h2>
                    <p class="text-gray-300 text-sm">{{ $b['sub'] }}</p>
                </div>
                {{-- Decorative circles --}}
                <div class="absolute top-4 right-8 w-24 h-24 rounded-full opacity-10" style="background: {{ $b['accent'] }};"></div>
                <div class="absolute bottom-4 left-8 w-16 h-16 rounded-full opacity-10" style="background: {{ $b['accent'] }};"></div>
            </div>
            @endforeach
        </div>
        {{-- Dots --}}
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
            @foreach($bannerColors as $i => $b)
                <button onclick="goBanner({{ $i }})" class="banner-dot w-2 h-2 rounded-full bg-white/40 hover:bg-white transition-colors" id="dot-{{ $i }}"></button>
            @endforeach
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- CATEGORIES --}}
        <section class="mb-10">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Explore By Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($categories as $category)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-600 text-white rounded-lg px-4 py-3 min-w-[120px] text-sm font-bold flex items-center justify-center text-center leading-tight">
                            {{ $category->name }}
                        </div>
                        <div class="grid grid-cols-5 gap-2 flex-1">
                            @foreach($category->products->take(5) as $product)
                                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- FLASH SALE --}}
        @if($flashSales->count() > 0)
        <section class="mb-10">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Flash Sale Special Lunar Day! 🔥</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                {{-- Promo Card --}}
                <div class="bg-orange rounded-xl flex items-center justify-center p-4 min-h-[200px]">
                    <div class="text-center text-white">
                        <p class="text-xs font-semibold opacity-80 mb-1">Lunar Day</p>
                        <p class="text-xl font-black">Special</p>
                        <div class="mt-3 text-3xl">🎊</div>
                    </div>
                </div>
                {{-- Flash Sale Products --}}
                @foreach($flashSales as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
        @endif

        {{-- NEW RELEASES --}}
        @if($newReleases->count() > 0)
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Explore More! ✨</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($newReleases as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            {{-- Pagination --}}
            <div class="mt-6">
                {{ $newReleases->links() }}
            </div>
        </section>
        @endif

    </div>

    {{-- Banner JS --}}
    <script>
        let currentBanner = 0;
        const track = document.getElementById('banner-track');
        const dots  = document.querySelectorAll('.banner-dot');
        const total = {{ count($bannerColors) }};

        function goBanner(index) {
            currentBanner = index;
            track.style.transform = `translateX(-${index * 100}%)`;
            track.style.transition = 'transform 0.5s ease-in-out';
            dots.forEach((d, i) => {
                d.classList.toggle('bg-white', i === index);
                d.classList.toggle('bg-white/40', i !== index);
            });
        }

        // Auto-advance every 4 seconds
        setInterval(() => {
            goBanner((currentBanner + 1) % total);
        }, 4000);

        goBanner(0);
    </script>
</x-app-layout>
