<x-admin-layout title="Products Ordered">

    <div class="max-w-6xl mx-auto bg-transparent">
        
        <div class="bg-white/50 backdrop-blur-sm rounded-3xl p-8 border border-gray-200">
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/4">Name Product</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/4">Description Order</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg">Order</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg">Stock</th>
                        <th class="py-4 px-4 font-black text-gray-900 text-lg text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($orders as $order)
                        @foreach($order->orderItems as $item)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="py-5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item->product->image ?? 'images/placeholder-product.png') }}" class="w-12 h-12 rounded object-cover">
                                    <span class="font-semibold text-gray-800">{{ $item->product->name ?? 'Deleted Product' }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                <p class="text-sm font-bold text-gray-800">{{ $order->user->name }}</p>
                                <p class="text-xs text-gray-500">Code: {{ $order->order_code }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="py-5 px-4">
                                <span class="font-bold text-gray-900">{{ $item->quantity }} pcs</span>
                            </td>
                            <td class="py-5 px-4">
                                @if($item->product)
                                    <span class="font-bold {{ $item->product->stock > 0 ? 'text-gray-900' : 'text-red-600' }}">{{ $item->product->stock }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            {{-- Action buttons are rendered once per order, not per item, to avoid split actions --}}
                            @if($loop->first)
                            <td class="py-5 px-4 flex justify-end gap-2 flex-col space-y-2 items-end" rowspan="{{ count($order->orderItems) }}">
                                <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" onsubmit="return confirm('Tolak pesanan ini dan kembalikan stok?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-[#FF0000] hover:bg-red-600 text-white font-bold py-1.5 px-6 rounded-lg text-sm w-28 text-center transition-colors">
                                        REJECT
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-[#4A0505] hover:bg-[#310303] text-white font-bold py-1.5 px-6 rounded-lg text-sm w-28 text-center transition-colors">
                                        ACCEPT
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 font-medium">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>

        </div>
    </div>

</x-admin-layout>
