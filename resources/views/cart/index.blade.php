<x-app-layout title="Shopping Cart — KylariaSHOP" :showSearch="false">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
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
                                    −
                                </button>
                                <span id="qty-display-{{ $loop->index }}" class="w-8 text-center text-sm font-semibold text-gray-800">
                                    {{ $item['quantity'] }}
                                </span>
                                <button type="button" onclick="changeQty({{ $loop->index }}, 1, {{ $product->stock }})"
                                    class="w-7 h-7 rounded-lg border border-gray-300 hover:border-orange hover:text-orange text-gray-600 flex items-center justify-center text-sm font-bold transition-colors">
                                    +
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-gray-400 mb-1">Subtotal</p>
                        <p class="font-bold text-gray-800 text-sm">IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>

                    {{-- Delete Button --}}
                    <form action="{{ route('cart.remove') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                            class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
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
                    <button onclick="document.getElementById('checkout-modal').classList.remove('hidden')"
                        class="flex-1 bg-orange hover:bg-orange-dark text-white font-black py-3 px-6 rounded-xl transition-colors text-center uppercase tracking-wide">
                        PAYMENT
                    </button>
                </div>
            </div>

            {{-- Checkout Modal --}}
            <div id="checkout-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-bold text-gray-900">Pilih Metode Pembayaran</h2>
                        <button onclick="document.getElementById('checkout-modal').classList.add('hidden')"
                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            @foreach([
                                ['value' => 'paypal', 'label' => 'PayPal', 'color' => 'text-blue-700', 'bg' => 'border-blue-200 hover:border-blue-400'],
                                ['value' => 'dana', 'label' => 'DANA', 'color' => 'text-teal-600', 'bg' => 'border-teal-200 hover:border-teal-400'],
                                ['value' => 'bca', 'label' => 'BCA', 'color' => 'text-blue-800', 'bg' => 'border-blue-200 hover:border-blue-400'],
                                ['value' => 'mandiri', 'label' => 'Mandiri', 'color' => 'text-yellow-700', 'bg' => 'border-yellow-200 hover:border-yellow-400'],
                            ] as $method)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $method['value'] }}" class="sr-only peer" required>
                                <div class="border-2 {{ $method['bg'] }} peer-checked:border-orange peer-checked:bg-orange/5 rounded-xl p-4 flex items-center gap-3 transition-all">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-xs {{ $method['color'] }}">
                                        {{ strtoupper(substr($method['label'], 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-sm {{ $method['color'] }}">{{ $method['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Pembayaran</span>
                                <span class="font-black text-orange">IDR {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-orange hover:bg-orange-dark text-white font-black py-3 rounded-xl transition-colors uppercase tracking-wide">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>

        @else
            {{-- Empty Cart --}}
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Keranjangmu kosong</h3>
                <p class="text-sm text-gray-400 mb-6">Yuk, tambahkan produk ke keranjang dulu!</p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-3 px-8 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Lanjut Belanja
                </a>
            </div>
        @endif
    </div>

    <script>
        function changeQty(index, delta, maxStock) {
            const input   = document.getElementById('qty-input-' + index);
            const display = document.getElementById('qty-display-' + index);
            let qty       = parseInt(input.value) + delta;
            if (qty < 1)        qty = 1;
            if (qty > maxStock) qty = maxStock;
            input.value   = qty;
            display.textContent = qty;
            document.getElementById('qty-form-' + index).submit();
        }
    </script>
</x-app-layout>
