<x-admin-layout title="Daftar Produk">

    <div class="max-w-6xl mx-auto">

        <div class="bg-white/50 backdrop-blur-sm rounded-3xl p-4 md:p-8 border border-gray-200">

            {{-- Search Bar --}}
            <div style="margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid #e5e7eb;">
                <form action="{{ route('admin.products.index') }}" method="GET"
                    style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="position:relative; flex:1;">
                        <svg style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:#9ca3af;"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk berdasarkan nama atau deskripsi..." style="width:100%; padding:0.625rem 1rem 0.625rem 2.5rem; background:#fff;
                                        border:2px solid #d1d5db; border-radius:1rem; font-size:0.875rem;
                                        outline:none; transition:border-color 0.2s;"
                            onfocus="this.style.borderColor='#4A0505'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <button type="submit"
                        style="padding:0.625rem 1.25rem; background:#4A0505; color:#fff; font-weight:700;
                                font-size:0.875rem; border-radius:1rem; border:none; cursor:pointer; transition:background 0.2s;"
                        onmouseover="this.style.background='#320303'" onmouseout="this.style.background='#4A0505'">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.products.index') }}"
                            style="padding:0.625rem 1rem; font-size:0.875rem; font-weight:700; color:#4b5563;
                                                background:#e5e7eb; border-radius:1rem; text-decoration:none; transition:background 0.2s;"
                            onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">
                            Reset
                        </a>
                    @endif
                </form>
                @if(request('search'))
                    <p style="font-size:0.875rem; color:#6b7280; margin-top:0.5rem;">
                        Ditemukan <span style="font-weight:700;">{{ $products->total() }}</span> produk untuk
                        "<span style="font-weight:600;">{{ request('search') }}</span>"
                    </p>
                @endif
            </div>

            {{-- Horizontal scroll wrapper so the table doesn't break on mobile --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" style="min-width:640px">
                    <thead>
                        <tr class="border-b border-gray-300">
                            <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/4">Nama Produk</th>
                            <th class="py-4 px-4 font-black text-gray-900 text-lg w-1/3">Deskripsi Produk</th>
                            <th class="py-4 px-4 font-black text-gray-900 text-lg">Harga</th>
                            <th class="py-4 px-4 font-black text-gray-900 text-lg">Stok</th>
                            <th class="py-4 px-4 font-black text-gray-900 text-lg text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse($products as $product)
                            <tr class="hover:bg-white/40 transition-colors">
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                            class="w-12 h-12 rounded object-cover">
                                        <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description ?? '-' }}</p>
                                </td>
                                <td class="py-5 px-4">
                                    @if($product->discount_price)
                                        <span class="text-sm text-gray-400 line-through block">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="font-bold text-red-600">Rp
                                            {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-bold text-gray-900">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="py-5 px-4">
                                    <span
                                        class="font-bold {{ $product->stock > 0 ? 'text-gray-900' : 'text-red-600' }}">{{ $product->stock }}</span>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="flex flex-col items-end gap-2">
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-[#FF0000] hover:bg-red-600 text-white font-bold py-1.5 px-6 rounded-lg text-sm w-24 text-center transition-colors">
                                                HAPUS
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="bg-[#4A0505] hover:bg-[#310303] text-white font-bold py-1.5 px-6 rounded-lg text-sm w-24 text-center transition-colors">
                                            UBAH
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 font-medium">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:0.5rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
                {{ $products->links() }}
            </div>

        </div>
    </div>

</x-admin-layout>