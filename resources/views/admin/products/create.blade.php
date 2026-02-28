<x-admin-layout title="Add Product">

    <div class="max-w-3xl mx-auto bg-transparent">
        
        <form action="{{ route('admin.dashboard') }}" method="POST" enctype="multipart/form-data" class="bg-white/50 backdrop-blur-sm rounded-3xl p-10 border border-gray-200">
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
                <label for="name" class="block text-gray-900 font-black text-xl mb-2">Name Product</label>
                <input type="text" id="name" name="name" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
            </div>

            {{-- Description Product --}}
            <div class="mb-6">
                <label for="description" class="block text-gray-900 font-black text-xl mb-2">Deskripsi Product</label>
                <textarea id="description" name="description" rows="3" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors"></textarea>
            </div>
            
            {{-- Category & Stock (Additional needed fields not in mockup but required logically) --}}
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-gray-900 font-black text-xl mb-2">Category</label>
                    <select id="category_id" name="category_id" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stock" class="block text-gray-900 font-black text-xl mb-2">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" value="10" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors" required>
                </div>
            </div>

            {{-- Standard Price --}}
            <div class="mb-8" id="standard-price-container">
                <label for="price" class="block text-gray-900 font-black text-xl mb-2">Price Product</label>
                <input type="number" id="price" name="price" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
            </div>

            {{-- Discount Toggle --}}
            <div class="mb-6">
                <label class="block text-gray-900 font-black text-xl mb-2">is there a discount?</label>
                <div class="flex gap-4">
                    <button type="button" id="btn-yes" class="px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32">YES</button>
                    <button type="button" id="btn-no" class="px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32">NO</button>
                </div>
                <input type="hidden" name="has_discount" id="has_discount" value="no">
            </div>

            {{-- Discount Pricing Fields (Hidden by default) --}}
            <div id="discount-fields" class="hidden space-y-6 mb-8">
                <div>
                    <label for="initial_price" class="block text-gray-900 font-black text-xl mb-2">initial price</label>
                    <input type="number" id="initial_price" name="initial_price" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                </div>
                <div>
                    <label for="discount_price" class="block text-gray-900 font-black text-xl mb-2">Discount Price</label>
                    <input type="number" id="discount_price" name="discount_price" class="w-full bg-transparent border-2 border-gray-300 rounded-2xl px-4 py-3 text-lg focus:outline-none focus:border-[#4A0505] transition-colors">
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="mb-8 relative">
                <label class="block text-gray-900 font-black text-xl mb-2 uppercase">ADD IMAGE</label>
                <input type="file" name="image" id="image" class="hidden" accept="image/*">
                
                <button type="button" onclick="document.getElementById('image').click()" class="bg-[#0000FF] hover:bg-blue-700 text-white w-14 h-10 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
                <div id="image-name" class="text-sm text-gray-500 mt-2 italic"></div>
            </div>
            
            {{-- Submit --}}
            <div class="text-right">
                <button type="submit" class="bg-[#4A0505] hover:bg-[#320303] text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors text-lg">
                    Save Product
                </button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnYes = document.getElementById('btn-yes');
            const btnNo = document.getElementById('btn-no');
            const hasDiscountInput = document.getElementById('has_discount');
            const discountFields = document.getElementById('discount-fields');
            const standardPriceContainer = document.getElementById('standard-price-container');
            const imageInput = document.getElementById('image');
            const imageNameDisplay = document.getElementById('image-name');

            btnYes.addEventListener('click', function() {
                // Set 'YES' active state (blue)
                btnYes.className = 'px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32';
                // Set 'NO' inactive state (white)
                btnNo.className = 'px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32';
                
                hasDiscountInput.value = 'yes';
                discountFields.classList.remove('hidden');
                standardPriceContainer.classList.add('hidden');
            });

            btnNo.addEventListener('click', function() {
                // Set 'NO' active state (blue)
                btnNo.className = 'px-8 py-2 rounded-xl text-white font-bold bg-[#0000FF] uppercase shadow-sm w-32';
                // Set 'YES' inactive state (white)
                btnYes.className = 'px-8 py-2 rounded-xl border border-gray-300 font-bold text-gray-700 bg-white hover:bg-gray-50 uppercase shadow-sm w-32';
                
                hasDiscountInput.value = 'no';
                discountFields.classList.add('hidden');
                standardPriceContainer.classList.remove('hidden');
            });
            
            imageInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    imageNameDisplay.textContent = "Selected: " + this.files[0].name;
                } else {
                    imageNameDisplay.textContent = "";
                }
            });
        });
    </script>
</x-admin-layout>
