<x-admin-layout title="List Product">

    <div class="max-w-6xl mx-auto bg-transparent">
        
        <div class="bg-white/50 backdrop-blur-sm rounded-3xl p-8 border border-gray-200">
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/4">Name Product</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/3">Description product</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg">Price</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg">Stock</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($products as $product)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover">
                                <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-4">
                            @if($product->discount_price)
                                <span class="text-sm text-gray-400 line-through block">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="font-bold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                            @else
                                <span class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-4">
                            <span class="font-bold {{ $product->stock > 0 ? 'text-gray-900' : 'text-red-600' }}">{{ $product->stock }}</span>
                        </td>
                        <td class="py-5 px-4 flex justify-end gap-2 flex-col space-y-2 items-end">
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-[#FF0000] hover:bg-red-600 text-white font-bold py-1.5 px-6 rounded-lg text-sm w-24 text-center transition-colors">
                                    DELETE
                                </button>
                            </form>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-[#4A0505] hover:bg-[#310303] text-white font-bold py-1.5 px-6 rounded-lg text-sm w-24 text-center transition-colors">
                                EDIT
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 font-medium">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>

</x-admin-layout>
