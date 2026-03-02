<x-admin-layout title="Tambah Produk">

    <div class="max-w-3xl mx-auto bg-transparent">
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/50 backdrop-blur-sm rounded-3xl p-10 border border-gray-200">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Name Product --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-900 font-black text-xl mb-2">Nama Produk</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
            </div>

            {{-- Description Product --}}
            <div class="mb-6">
                <label for="description" class="block text-gray-900 font-black text-xl mb-2">Deskripsi Product</label>
                <textarea id="description" name="description" rows="3" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">{{ old('description') }}</textarea>
            </div>
            
            {{-- Category & Stock --}}
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-gray-900 font-black text-xl mb-2">Kategori</label>
                    <select id="category_id" name="category_id" onchange="handleCategoryChange(this.value)" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stock" class="block text-gray-900 font-black text-xl mb-2">Stok</label>
                    <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', 10) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
                </div>
            </div>

            {{-- Sale Type Toggle --}}
            <div class="mb-6">
                <label class="block text-gray-900 font-black text-xl mb-2">Tipe Produk</label>
                <div class="flex gap-3 flex-wrap">
                    <button type="button" id="sale-none" onclick="selectSaleType('none')"
                        class="sale-type-btn px-6 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm">
                        Normal
                    </button>
                    <button type="button" id="sale-flash_sale" onclick="selectSaleType('flash_sale')"
                        class="sale-type-btn px-6 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm">
                        ⚡ Flash Sale
                    </button>
                    <button type="button" id="sale-lunar_day" onclick="selectSaleType('lunar_day')"
                        class="sale-type-btn px-6 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm">
                        🌙 Lunar Day
                    </button>
                </div>
                <input type="hidden" name="sale_type" id="sale_type_input" value="{{ old('sale_type', 'none') }}">
                <p class="text-xs text-gray-500 mt-2">Flash Sale dan Lunar Day akan ditampilkan di section khusus halaman utama.</p>
            </div>

            {{-- Price Per Day (hanya untuk Costume) --}}
            <div id="price-per-day-container" class="mb-6 hidden">
                <label for="price_per_day" class="block text-gray-900 font-black text-xl mb-2">Harga Sewa Per Hari (IDR)</label>
                <input type="number" id="price_per_day" name="price_per_day" min="0" value="{{ old('price_per_day') }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                <p class="text-xs text-gray-500 mt-1">Pengguna bisa sewa maks. 7 hari. Total = harga per hari × jumlah hari.</p>
            </div>

            {{-- Standard Price --}}
            <div class="mb-8" id="standard-price-container">
                <label for="price" class="block text-gray-900 font-black text-xl mb-2">Harga Produk</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
            </div>

            {{-- Discount Toggle --}}
            <div class="mb-6">
                <label class="block text-gray-900 font-black text-xl mb-2">Ada diskon?</label>
                <div class="flex gap-4">
                    <button type="button" id="btn-yes" class="px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32">YA</button>
                    <button type="button" id="btn-no" class="px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32">TIDAK</button>
                </div>
                <input type="hidden" name="has_discount" id="has_discount" value="no">
                <p class="text-xs text-gray-500 mt-1">Jika ada diskon, isi harga produk di atas sebagai harga asli, lalu masukkan harga diskonnya di bawah.</p>
            </div>

            {{-- Discount Pricing Fields --}}
            <div id="discount-fields" class="hidden mb-8">
                <label for="discount_price" class="block text-gray-900 font-black text-xl mb-2">Harga Diskon</label>
                <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" placeholder="Masukkan harga setelah diskon">
                <p class="text-xs text-gray-500 mt-1">Masukkan harga yang ingin ditampilkan kepada pembeli.</p>
            </div>

            {{-- Image Upload --}}
            <div class="mb-8 relative">
                <label class="block text-gray-900 font-black text-xl mb-2 uppercase">TAMBAH GAMBAR</label>
                <input type="file" name="image" id="image" class="hidden" accept="image/*">
                
                <button type="button" onclick="document.getElementById('image').click()" class="bg-[#0000FF] hover:bg-blue-700 text-white w-14 h-10 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
                <div id="image-name" class="text-sm text-gray-500 mt-2 italic"></div>
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>
            
            {{-- Submit --}}
            <div class="text-right">
                <button type="submit" class="bg-[#4A0505] hover:bg-[#320303] text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors text-lg">
                    Simpan Produk
                </button>
            </div>

        </form>
    </div>

    <script>
        // ---- Sale Type ----
        function selectSaleType(type) {
            document.getElementById('sale_type_input').value = type;
            document.querySelectorAll('.sale-type-btn').forEach(btn => {
                btn.className = 'sale-type-btn px-6 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm';
            });
            const active = document.getElementById('sale-' + type);
            if (active) active.className = 'sale-type-btn px-6 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm';
        }

        // ---- Category Change ----
        function handleCategoryChange(categoryId) {
            const select = document.getElementById('category_id');
            const selectedOption = select.options[select.selectedIndex];
            const slug = (selectedOption ? selectedOption.getAttribute('data-slug') : '') || '';
            const pricePerDayContainer = document.getElementById('price-per-day-container');
            const standardPriceContainer = document.getElementById('standard-price-container');
            const isCostume = slug.includes('kostum') || slug.includes('costume') || slug.includes('cosplay');
            if (isCostume) {
                pricePerDayContainer.classList.remove('hidden');
                // Untuk cosplay, harga sewa = harga asli, sembunyikan field harga produk biasa
                standardPriceContainer.classList.add('hidden');
            } else {
                pricePerDayContainer.classList.add('hidden');
                standardPriceContainer.classList.remove('hidden');
            }
        }

        // ---- Discount Toggle ----
        document.addEventListener('DOMContentLoaded', function() {
            const btnYes = document.getElementById('btn-yes');
            const btnNo = document.getElementById('btn-no');
            const hasDiscountInput = document.getElementById('has_discount');
            const discountFields = document.getElementById('discount-fields');
            const standardPriceContainer = document.getElementById('standard-price-container');
            const imageInput = document.getElementById('image');
            const imageNameDisplay = document.getElementById('image-name');

            btnYes.addEventListener('click', function() {
                btnYes.className = 'px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32';
                btnNo.className = 'px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32';
                hasDiscountInput.value = 'yes';
                discountFields.classList.remove('hidden');
                // Harga Produk tetap terlihat sebagai patokan harga asli
            });

            btnNo.addEventListener('click', function() {
                btnNo.className = 'px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32';
                btnYes.className = 'px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32';
                hasDiscountInput.value = 'no';
                discountFields.classList.add('hidden');
                // Harga Produk selalu terlihat
            });
            
            imageInput.addEventListener('change', function() {
                imageNameDisplay.textContent = this.files.length > 0 ? "Dipilih: " + this.files[0].name : "";
            });

            // Restore sale_type on old() back
            const savedType = document.getElementById('sale_type_input').value || 'none';
            selectSaleType(savedType);

            // Restore category show/hide
            handleCategoryChange(document.getElementById('category_id').value);
        });
    </script>
</x-admin-layout>
