<x-app-layout title="{{ $pageTitle }} - KylariaSHOP">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Breadcrumbs & Title --}}
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900">{{ $pageTitle }}</h1>
            @if(request('search'))
                <p class="text-sm text-gray-500 mt-1">Ditemukan {{ $products->total() }} produk untuk pencarian Anda.</p>
            @endif
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Sidebar Filters --}}
            <aside class="w-full md:w-64 flex-shrink-0">
                <form action="{{ url()->current() }}" method="GET" id="filter-form" class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm sticky top-20">
                    
                    {{-- Preserve existing search if present --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Kategori</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="category" value="" onchange="this.form.submit()" 
                                       class="text-orange focus:ring-orange" {{ !request('category') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600">Semua Kategori</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" onchange="this.form.submit()"
                                           class="text-orange focus:ring-orange" {{ request('category') == $cat->slug ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Urutkan</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sort" value="newest" onchange="this.form.submit()"
                                       class="text-orange focus:ring-orange" {{ request('sort', 'newest') == 'newest' ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600">Terbaru</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sort" value="price_desc" onchange="this.form.submit()"
                                       class="text-orange focus:ring-orange" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600">Harga: Tinggi ke Rendah</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sort" value="price_asc" onchange="this.form.submit()"
                                       class="text-orange focus:ring-orange" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600">Harga: Rendah ke Tinggi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sort" value="name_asc" onchange="this.form.submit()"
                                       class="text-orange focus:ring-orange" {{ request('sort') == 'name_asc' ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600">Nama: A - Z</span>
                            </label>
                        </div>
                    </div>

                </form>
            </aside>

            {{-- Product Grid --}}
            <main class="flex-1">
                @if($products->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada produk ditemukan</h3>
                        <p class="text-gray-500 text-sm mb-6">Coba gunakan kata kunci pencarian atau filter kategori yang berbeda.</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-orange text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange/90 transition-colors">
                            Reset Filter
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </main>

        </div>
    </div>
</x-app-layout>
