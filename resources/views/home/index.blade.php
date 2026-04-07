<x-app-layout title="KylariaSHOP — Hobby Figures & Merchandise">

    {{-- BANNER/HERO CAROUSEL --}}
    <section class="relative overflow-hidden bg-gray-900">
        <div class="banner-track flex" id="banner-track">
            @php
                $slides = [
                    ['img' => 'images/slideshow (1).png', 'accent' => '#FF8A4C', 'text' => 'Lunar Day Special', 'sub' => 'Diskon up to 15%'],
                    ['img' => 'images/slideshow (2).png', 'accent' => '#4CAF50', 'text' => 'New Arrivals 2025', 'sub' => 'Figure & Model Kit Terbaru'],
                    ['img' => 'images/slideshow (3).png', 'accent' => '#e94560', 'text' => 'Flash Sale', 'sub' => 'Acrylic Stand & Merchandise'],
                ];
            @endphp
            @foreach($slides as $i => $s)
                <div
                    class="banner-slide flex-shrink-0 w-full h-56 md:h-64 lg:h-80 relative bg-gray-900 flex items-center justify-center">
                    <img src="{{ asset($s['img']) }}" alt="Promo Slide {{ $i + 1 }}"
                        class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/60"></div>
                    <div class="text-center z-10 relative">
                        <div class="inline-block px-4 py-1 rounded-full text-xs font-bold mb-4 text-white shadow-md"
                            style="background-color: {{ $s['accent'] }};">
                            ✦ EXCLUSIVE
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-white mb-2 drop-shadow-md">{{ $s['text'] }}</h2>
                        <p class="text-gray-200 text-sm md:text-lg drop-shadow">{{ $s['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Dots --}}
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
            @foreach($slides as $i => $s)
                <button onclick="goBanner({{ $i }})"
                    class="banner-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-colors shadow-sm"
                    id="dot-{{ $i }}"></button>
            @endforeach
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- CATEGORIES --}}
        <section class="mb-10">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Explore By Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($categories as $category)
                    @if($category->productsDirect->count() > 0)
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center justify-between">
                                    <div class="bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-bold tracking-wide">
                                        {{ $category->name }}
                                    </div>
                                    <a href="{{ route('products.by-category', $category->slug) }}"
                                        class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors whitespace-nowrap">
                                        Lihat Semua →
                                    </a>
                                </div>
                                {{-- Product thumbnails: 3 mobile, 5 desktop --}}
                                <div class="cat-thumb-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem;">
                                    @foreach($category->productsDirect->take(5) as $ti => $product)
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="cat-thumb-item aspect-[3/4] rounded-lg overflow-hidden bg-gray-100 border border-gray-200 block hover:opacity-80 transition-opacity"
                                            data-ti="{{ $ti }}">
                                            @if($product->image && file_exists(public_path($product->image)))
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <style>
            /* Categories: 3 on mobile, 5 on desktop */
            @media (max-width: 639px) {
                .cat-thumb-grid { grid-template-columns: repeat(3, 1fr) !important; }
                .cat-thumb-item[data-ti="3"], .cat-thumb-item[data-ti="4"] { display: none !important; }
            }
            @media (min-width: 640px) {
                .cat-thumb-grid { grid-template-columns: repeat(5, 1fr) !important; }
                .cat-thumb-item { display: block !important; }
            }
        </style>

        {{-- SECTION DIVIDER --}}
        <hr style="border:none;border-top:2px solid #f3f4f6;margin:2rem 0;">

        {{-- LUNAR DAY --}}
        @if($lunarSales->count() > 0)
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Flash Sale Special Lunar Day! 🌙</h2>
                    <a href="{{ route('products.flash-sale') }}" class="text-sm font-semibold text-orange hover:text-orange/80 transition-colors">Lihat Semua →</a>
                </div>
                <div id="lunar-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">
                    {{-- Promo box (desktop only) --}}
                    <div class="sale-promo-box" style="background:linear-gradient(135deg,#f97316,#ea580c); border-radius:0.75rem; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:1.5rem; min-height:200px; display:none;">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem;">🌙</div>
                        <p style="color:rgba(255,255,255,0.85); font-size:0.75rem; font-weight:600; margin-bottom:0.25rem;">Lunar Day</p>
                        <p style="color:#fff; font-size:1.25rem; font-weight:900;">Special</p>
                        <p style="color:rgba(255,255,255,0.75); font-size:0.7rem; margin-top:0.5rem;">Diskon Terbesar!</p>
                    </div>
                    {{-- Products --}}
                    @foreach($lunarSales->take(4) as $i => $product)
                        <div class="sale-item" data-index="{{ $i }}">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION DIVIDER --}}
        <hr style="border:none;border-top:2px solid #f3f4f6;margin:2rem 0;">

        {{-- FLASH SALE --}}
        @if($flashSales->count() > 0)
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Flash Sale ⚡</h2>
                    <a href="{{ route('products.flash-sale') }}" class="text-sm font-semibold text-orange hover:text-orange/80 transition-colors">Lihat Semua →</a>
                </div>
                <div id="flash-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem;">
                    {{-- Promo box (desktop only) --}}
                    <div class="sale-promo-box" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8); border-radius:0.75rem; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:1.5rem; min-height:200px; display:none;">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem;">⚡</div>
                        <p style="color:rgba(255,255,255,0.85); font-size:0.75rem; font-weight:600; margin-bottom:0.25rem;">Flash Sale</p>
                        <p style="color:#fff; font-size:1.25rem; font-weight:900;">Terbatas!</p>
                        <p style="color:rgba(255,255,255,0.75); font-size:0.7rem; margin-top:0.5rem;">Stok Terbatas</p>
                    </div>
                    {{-- Products --}}
                    @foreach($flashSales->take(4) as $i => $product)
                        <div class="sale-item" data-index="{{ $i }}">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <style>
            /* Mobile: 2-col, hide promo box and cards 2 & 3 */
            @media (max-width: 639px) {
                #lunar-grid, #flash-grid { grid-template-columns: repeat(2, 1fr) !important; }
                .sale-promo-box { display: none !important; }
                .sale-item[data-index="2"], .sale-item[data-index="3"] { display: none !important; }
            }
            /* Desktop: 5-col (promo + 4 products), show all */
            @media (min-width: 640px) {
                #lunar-grid, #flash-grid { grid-template-columns: repeat(5, 1fr) !important; }
                .sale-promo-box { display: flex !important; flex-direction: column; }
                .sale-item { display: block !important; }
            }
        </style>

        {{-- SECTION DIVIDER --}}
        <hr style="border:none;border-top:2px solid #f3f4f6;margin:2rem 0;">

        {{-- NEW RELEASES --}}
        @if($newReleases->count() > 0)
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Explore More! ✨</h2>
                    <a href="{{ route('products.new-arrivals') }}"
                        class="text-sm font-semibold text-orange hover:text-orange/80 transition-colors">Lihat Semua →</a>
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
        const dots = document.querySelectorAll('.banner-dot');
        const total = {{ count($slides) }};

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