<x-app-layout title="{{ $product->name }} - KylariaSHOP">

    <div class="max-w-6xl mx-auto px-4 py-8">
        
        {{-- Breadcrumbs --}}
        <nav class="flex mb-8 text-sm text-gray-500">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-400">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl p-6 lg:p-10 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-10">
            
            {{-- Product Image --}}
            <div class="w-full md:w-1/2 flex justify-center items-start">
                <div class="rounded-2xl overflow-hidden border border-gray-200">
                    @if($product->image && file_exists(public_path($product->image)))
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover max-h-[500px]">
                    @else
                        <div class="w-full h-[400px] flex items-center justify-center bg-gray-100">
                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Product Details --}}
            <div class="w-full md:w-1/2 flex flex-col">
                
                {{-- Badges & Title --}}
                <div class="mb-4">
                    <div class="flex gap-2 mb-3">
                        @if($product->is_new)
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">NEW RELEASE</span>
                        @endif
                        @if($product->discount_price)
                            <span class="bg-orange text-white text-xs font-bold px-3 py-1 rounded-full">{{ $product->discount_percent }}% OFF</span>
                        @endif
                        @foreach($product->categories as $category)
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full uppercase border border-gray-200">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    
                    <h1 class="text-3xl lg:text-4xl font-black text-gray-900 leading-tight">{{ $product->name }}</h1>
                </div>

                {{-- Pricing --}}
                <div class="mb-6 pb-6 border-b border-gray-100">
                    @if($product->discount_price)
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-black text-red-600">{{ $product->rupiah_discount_price }}</span>
                            <span class="text-xl text-gray-400 line-through">{{ $product->rupiah_price }}</span>
                        </div>
                    @else
                        <span class="text-3xl font-black text-gray-900">{{ $product->rupiah_price }}</span>
                    @endif
                    <div class="mt-2 text-sm text-gray-500 font-medium">Stok Tersedia: <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->stock }}</span></div>
                </div>

                {{-- Description --}}
                <div class="mb-8 flex-grow">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Deskripsi Produk</h3>
                    <div class="prose prose-sm text-gray-600 leading-relaxed space-y-4">
                        <p>{{ $product->description ?? 'Belum ada deskripsi untuk produk ini.' }}</p>
                    </div>
                </div>

                {{-- Add to Cart Form --}}
                <div class="mt-auto bg-gray-50 p-6 border border-gray-100 rounded-2xl">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row items-end sm:items-center gap-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex flex-col w-full sm:w-1/3">
                                <label for="quantity" class="text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                                <div class="relative flex items-center">
                                    <button type="button" id="decrement-btn" class="bg-white border-y border-l border-gray-300 rounded-l-lg p-3 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-full bg-white border-y border-gray-300 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none font-bold" required>
                                    <button type="button" id="increment-btn" class="bg-white border-y border-r border-gray-300 rounded-r-lg p-3 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </div>

                            @auth
                                <button type="submit" class="w-full sm:w-2/3 bg-orange hover:bg-orange-dark text-white font-black py-3.5 px-6 rounded-xl transition-colors text-center shadow-lg shadow-orange/30 flex items-center justify-center gap-2 text-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    TAMBAH KE KERANJANG
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-full sm:w-2/3 bg-orange hover:bg-orange-dark text-white font-black py-3.5 px-6 rounded-xl transition-colors text-center shadow-lg shadow-orange/30 flex items-center justify-center gap-2 text-lg">
                                    LOGIN UNTUK MEMBELI
                                </a>
                            @endauth
                        </form>
                    @else
                        <div class="bg-red-50 text-red-600 border border-red-200 font-bold p-4 rounded-xl text-center">
                            MAAF, BARANG INI SEDANG KOSONG
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const incrementBtn = document.getElementById('increment-btn');
            const decrementBtn = document.getElementById('decrement-btn');

            if (quantityInput && incrementBtn && decrementBtn) {
                const maxStock = parseInt(quantityInput.getAttribute('max'));

                incrementBtn.addEventListener('click', function() {
                    let val = parseInt(quantityInput.value);
                    if (val < maxStock) {
                        quantityInput.value = val + 1;
                    }
                });

                decrementBtn.addEventListener('click', function() {
                    let val = parseInt(quantityInput.value);
                    if (val > 1) {
                        quantityInput.value = val - 1;
                    }
                });

                // Prevent manual input that exceeds limits
                quantityInput.addEventListener('change', function() {
                    let val = parseInt(this.value);
                    if (val > maxStock) this.value = maxStock;
                    if (val < 1 || isNaN(val)) this.value = 1;
                });
            }
        });
    </script>
</x-app-layout>
