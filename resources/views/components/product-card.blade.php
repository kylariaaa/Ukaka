@props(['product'])

<a href="{{ route('products.show', $product->slug) }}" class="block group cursor-pointer h-full">
<div class="bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">

    {{-- Product Image — fixed square container --}}
    <div class="relative overflow-hidden bg-gray-100 w-full flex-shrink-0" style="aspect-ratio:1/1;">
        @if($product->image && file_exists(public_path($product->image)))
            <img src="{{ asset($product->image) }}"
                 alt="{{ $product->name }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1 z-10">
            @if($product->discount_price)
                <span class="bg-orange text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">{{ $product->discount_percent }}%</span>
            @endif
        </div>
    </div>

    {{-- Product Info — flex-1 agar semua card sama tinggi --}}
    <div class="p-3 flex flex-col flex-1">

        {{-- Badge NEW (reserved space selalu ada agar alignment seragam) --}}
        <div class="mb-1.5 min-h-[1.25rem]">
            @if($product->is_new)
                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">NEW RELEASE</span>
            @endif
        </div>

        {{-- Nama produk: 2 baris max, konsisten --}}
        <h3 class="text-sm font-semibold text-gray-800 mb-1.5 leading-snug line-clamp-2 min-h-[2.5rem]" title="{{ $product->name }}">
            {{ $product->name }}
        </h3>

        {{-- Pricing --}}
        <div class="mb-3 flex-1">
            @if($product->discount_price)
                <p class="text-xs text-gray-400 line-through leading-tight">{{ $product->rupiah_price }}</p>
                <p class="text-sm font-bold text-red-500">{{ $product->rupiah_discount_price }}</p>
            @else
                <p class="text-xs text-transparent leading-tight select-none">—</p>
                <p class="text-sm font-bold text-red-500">{{ $product->rupiah_price }}</p>
            @endif
        </div>

        {{-- Add to Cart --}}
        @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                    class="w-full bg-orange hover:bg-orange-dark text-white text-xs font-bold py-2 rounded-lg transition-colors text-center uppercase tracking-wide">
                    READY STOK
                </button>
            </form>
        @else
            <div class="mt-auto block w-full bg-orange hover:bg-orange-dark text-white text-xs font-bold py-2 rounded-lg transition-colors text-center uppercase tracking-wide">
                READY STOK
            </div>
        @endauth
    </div>

</div>
</a>
