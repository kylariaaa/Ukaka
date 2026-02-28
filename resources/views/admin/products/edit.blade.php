<x-admin-layout title="Edit Product">

    <div class="max-w-3xl mx-auto bg-transparent">
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white/50 backdrop-blur-sm rounded-3xl p-10 border border-gray-200">
            @csrf
            @method('PUT')
            
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
                <label for="name" class="block text-gray-900 font-black text-xl mb-2">Name Product</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-gray-900 font-black text-xl mb-2">Deskripsi Product</label>
                <textarea id="description" name="description" rows="3" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">{{ old('description', $product->description) }}</textarea>
            </div>
            
            {{-- Category & Stock --}}
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-gray-900 font-black text-xl mb-2">Category</label>
                    <select id="category_id" name="category_id" onchange="handleCategoryChange(this.value)" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stock" class="block text-gray-900 font-black text-xl mb-2">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $product->stock) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
                </div>
            </div>

            {{-- Sale Type Toggle --}}
            <div class="mb-6">
                <label class="block text-gray-900 font-black text-xl mb-2">Tipe Produk</label>
                <div class="flex gap-3 flex-wrap">
                    <button type="button" id="sale-none" onclick="selectSaleType('none')"
                        class="sale-type-btn px-6 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm">
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
                <input type="hidden" name="sale_type" id="sale_type_input" value="{{ old('sale_type', $product->sale_type ?? 'none') }}">
            </div>

            {{-- Price Per Day (Costume only) --}}
            <div id="price-per-day-container" class="mb-6 hidden">
                <label for="price_per_day" class="block text-gray-900 font-black text-xl mb-2">Harga Sewa Per Hari (IDR)</label>
                <input type="number" id="price_per_day" name="price_per_day" min="0" value="{{ old('price_per_day', $product->price_per_day) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                <p class="text-xs text-gray-500 mt-1">Pengguna bisa sewa maks. 7 hari. Total = harga per hari × jumlah hari.</p>
            </div>

            {{-- Standard Price --}}
            <div class="mb-8 {{ $product->discount_price ? 'hidden' : '' }}" id="standard-price-container">
                <label for="price" class="block text-gray-900 font-black text-xl mb-2">Price Product</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
            </div>

            {{-- Discount Toggle --}}
            <div class="mb-6">
                <label class="block text-gray-900 font-black text-xl mb-2">is there a discount?</label>
                <div class="flex gap-4">
                    <button type="button" id="btn-yes" class="px-8 py-2 rounded-xl {{ $product->discount_price ? 'text-white bg-[#0000FF]' : 'border border-gray-300 bg-white text-gray-700' }} font-bold uppercase shadow-sm w-32">YES</button>
                    <button type="button" id="btn-no" class="px-8 py-2 rounded-xl {{ !$product->discount_price ? 'text-white bg-[#0000FF]' : 'border border-gray-300 bg-white text-gray-700' }} font-bold uppercase shadow-sm w-32">NO</button>
                </div>
                <input type="hidden" name="has_discount" id="has_discount" value="{{ $product->discount_price ? 'yes' : 'no' }}">
                <p class="text-xs text-gray-500 mt-1">Berlaku juga untuk produk Flash Sale dan Lunar Day.</p>
            </div>

            {{-- Discount Fields --}}
            <div id="discount-fields" class="{{ $product->discount_price ? '' : 'hidden' }} space-y-6 mb-8">
                <div>
                    <label for="initial_price" class="block text-gray-900 font-black text-xl mb-2">Initial Price (Harga Asli)</label>
                    <input type="number" id="initial_price" name="initial_price" value="{{ old('initial_price', $product->discount_price ? $product->price : '') }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                </div>
                <div>
                    <label for="discount_price" class="block text-gray-900 font-black text-xl mb-2">Discount Price (Harga Setelah Diskon)</label>
                    <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="mb-8 relative">
                <label class="block text-gray-900 font-black text-xl mb-2 uppercase">ADD IMAGE (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="image" id="image" class="hidden" accept="image/*">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="document.getElementById('image').click()" class="bg-[#0000FF] hover:bg-blue-700 text-white w-14 h-10 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="h-16 rounded shadow-sm border border-gray-200">
                    @endif
                </div>
                <div id="image-name" class="text-sm text-gray-500 mt-2 italic"></div>
            </div>
            
            {{-- Submit --}}
            <div class="text-right flex gap-4 justify-end">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-8 rounded-xl shadow-md transition-colors text-lg">Cancel</a>
                <button type="submit" class="bg-[#4A0505] hover:bg-[#320303] text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors text-lg">Update Product</button>
            </div>

        </form>
    </div>

    <script>
        function selectSaleType(type) {
            document.getElementById('sale_type_input').value = type;
            document.querySelectorAll('.sale-type-btn').forEach(btn => {
                btn.className = 'sale-type-btn px-6 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm';
            });
            const active = document.getElementById('sale-' + type);
            if (active) active.className = 'sale-type-btn px-6 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm';
        }

        function handleCategoryChange(categoryId) {
            const select = document.getElementById('category_id');
            const selectedOption = select.options[select.selectedIndex];
            const slug = selectedOption ? selectedOption.getAttribute('data-slug') : '';
            const container = document.getElementById('price-per-day-container');
            const isCostume = slug.includes('kostum') || slug.includes('costume');
            isCostume ? container.classList.remove('hidden') : container.classList.add('hidden');
        }

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
                standardPriceContainer.classList.add('hidden');
            });

            btnNo.addEventListener('click', function() {
                btnNo.className = 'px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32';
                btnYes.className = 'px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32';
                hasDiscountInput.value = 'no';
                discountFields.classList.add('hidden');
                standardPriceContainer.classList.remove('hidden');
            });

            imageInput.addEventListener('change', function() {
                imageNameDisplay.textContent = this.files.length > 0 ? "Selected: " + this.files[0].name : "";
            });

            // Restore sale type active state
            selectSaleType(document.getElementById('sale_type_input').value || 'none');
            // Restore category
            handleCategoryChange(document.getElementById('category_id').value);
        });
    </script>
</x-admin-layout>
