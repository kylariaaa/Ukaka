<x-app-layout title="Shopping Cart — KylariaSHOP" :showSearch="false">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <img src="{{ asset('images/back-icon.png') }}" alt="Back" class="w-5 h-5">
            </a>
            <h1 class="text-xl font-bold text-gray-900">Keranjang Belanja</h1>
            @if(count($cartItems) > 0)
                <span class="bg-orange/10 text-orange text-xs font-bold px-2.5 py-1 rounded-full">{{ count($cartItems) }} item</span>
            @endif
        </div>

        @if(count($cartItems) > 0)
            {{-- Cart Items --}}
            <div class="space-y-3 mb-6">
                @foreach($cartItems as $index => $item)
                @php $product = $item['product']; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">

                    {{-- Product Image --}}
                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($product->image && file_exists(public_path($product->image)))
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Product Details --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</h3>

                        @if($item['is_costume'])
                            {{-- Costume: tampilkan harga diskon atau harga per hari --}}
                            @if($product->discount_price)
                                <p class="text-red-500 font-bold text-sm mt-0.5">
                                    IDR {{ number_format($product->discount_price, 0, ',', '.') }} / hari
                                    <span class="text-gray-400 font-normal line-through text-xs ml-1">IDR {{ number_format($product->price_per_day ?? $product->price, 0, ',', '.') }}</span>
                                </p>
                            @else
                                <p class="text-red-500 font-bold text-sm mt-0.5">
                                    IDR {{ number_format($product->price_per_day, 0, ',', '.') }} / hari
                                </p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">Stock: {{ $product->stock }}</p>

                            {{-- Rental Days Input --}}
                            <form action="{{ route('cart.update') }}" method="POST" class="mt-2" id="rental-form-{{ $loop->index }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs text-gray-500 font-medium">Jumlah:</span>
                                    <span class="text-xs font-bold text-gray-700">1</span>
                                    <span class="text-gray-300 mx-1">|</span>
                                    <span class="text-xs text-purple-600 font-semibold">🗓 Lama Sewa:</span>
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="changeRental({{ $loop->index }}, -1)"
                                            class="w-7 h-7 rounded-lg border border-gray-300 hover:border-purple-500 hover:text-purple-600 text-gray-600 flex items-center justify-center text-sm font-bold transition-colors">
                                            −
                                        </button>
                                        <span id="rental-display-{{ $loop->index }}" class="w-8 text-center text-sm font-semibold text-gray-800">
                                            {{ $item['rental_days'] }}
                                        </span>
                                        <input type="hidden" name="rental_days" id="rental-input-{{ $loop->index }}" value="{{ $item['rental_days'] }}">
                                        <button type="button" onclick="changeRental({{ $loop->index }}, 1)"
                                            class="w-7 h-7 rounded-lg border border-gray-300 hover:border-purple-500 hover:text-purple-600 text-gray-600 flex items-center justify-center text-sm font-bold transition-colors">
                                            +
                                        </button>
                                        <span class="text-xs text-gray-400 ml-1">hari (maks. 7)</span>
                                    </div>
                                </div>
                            </form>
                        @else
                            {{-- Produk biasa: harga & qty --}}
                            <p class="text-red-500 font-bold text-sm mt-0.5">
                                @if($product->discount_price)
                                    {{ $product->rupiah_discount_price }}
                                @else
                                    {{ $product->rupiah_price }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">Stock: {{ $product->stock }}</p>

                            {{-- Quantity controls --}}
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-500">Jumlah:</span>
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-1" id="qty-form-{{ $loop->index }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="qty-input-{{ $loop->index }}" value="{{ $item['quantity'] }}">

                                    <button type="button" onclick="changeQty({{ $loop->index }}, -1, {{ $product->stock }})"
                                        class="w-7 h-7 rounded-lg border border-gray-300 hover:border-orange hover:text-orange text-gray-600 flex items-center justify-center text-sm font-bold transition-colors">
                                        <img src="{{ asset('images/qtyminus-icon.png') }}" alt="-" class="w-3 h-3">
                                    </button>
                                    <span id="qty-display-{{ $loop->index }}" class="w-8 text-center text-sm font-semibold text-gray-800">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button type="button" onclick="changeQty({{ $loop->index }}, 1, {{ $product->stock }})"
                                        class="w-7 h-7 rounded-lg border border-gray-300 hover:border-orange hover:text-orange text-gray-600 flex items-center justify-center text-sm font-bold transition-colors">
                                        <img src="{{ asset('images/qtyplus-icon.png') }}" alt="+" class="w-3 h-3">
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-gray-400 mb-1">Subtotal</p>
                        @if($item['is_costume'])
                            @php $activePrice = $product->discount_price ?? $product->price_per_day ?? $product->price; @endphp
                            <p class="font-bold text-gray-800 text-sm">IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            <p class="text-xs text-purple-500">{{ $item['rental_days'] }} hari × IDR {{ number_format($activePrice, 0, ',', '.') }}</p>
                        @else
                            <p class="font-bold text-gray-800 text-sm">IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        @endif
                    </div>

                    {{-- Delete Button --}}
                    <form action="{{ route('cart.remove') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                            class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors">
                            <img src="{{ asset('images/delete-icon.png') }}" alt="Hapus" class="w-4 h-4">
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- Total & Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600 font-medium">Total Belanja</span>
                    <span class="text-xl font-black text-red-500">IDR {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 bg-orange/5 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500 font-bold mb-1">TOTAL: <span class="text-orange">IDR {{ number_format($total, 0, ',', '.') }}</span></p>
                    </div>
                    <a href="{{ route('checkout.create') }}"
                        class="flex-1 bg-orange hover:bg-orange-dark text-white font-black py-3 px-6 rounded-xl transition-colors text-center uppercase tracking-wide">
                        PAYMENT
                    </a>
                </div>
            </div>

        @else
            {{-- Empty Cart --}}
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <img src="{{ asset('images/carticon.png') }}" alt="Cart" class="w-12 h-12 opacity-40">
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Keranjangmu kosong</h3>
                <p class="text-sm text-gray-400 mb-6">Yuk, tambahkan produk ke keranjang dulu!</p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-3 px-8 rounded-xl transition-colors">
                    <img src="{{ asset('images/back-icon.png') }}" alt="Back" class="w-4 h-4">
                    Lanjut Belanja
                </a>
            </div>
        @endif
    </div>

    <script>
        // Quantity control untuk produk biasa
        function changeQty(index, delta, maxStock) {
            const input   = document.getElementById('qty-input-' + index);
            const display = document.getElementById('qty-display-' + index);
            let qty       = parseInt(input.value) + delta;
            if (qty < 1)        qty = 1;
            if (qty > maxStock) qty = maxStock;
            input.value         = qty;
            display.textContent = qty;
            document.getElementById('qty-form-' + index).submit();
        }

        // Rental days control untuk kostum (maks 7 hari)
        function changeRental(index, delta) {
            const input   = document.getElementById('rental-input-' + index);
            const display = document.getElementById('rental-display-' + index);
            let days      = parseInt(input.value) + delta;
            if (days < 1) days = 1;
            if (days > 7) days = 7;
            input.value         = days;
            display.textContent = days;
            document.getElementById('rental-form-' + index).submit();
        }
    </script>
</x-app-layout>
