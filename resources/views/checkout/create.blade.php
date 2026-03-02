<x-app-layout title="Checkout - KylariaSHOP" :showSearch="false">

    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header Navigation --}}
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-300">
            <div class="flex items-center gap-4">
                <a href="{{ route('cart') }}" class="text-gray-900 hover:text-orange transition-colors">
                    <img src="{{ asset('images/back-icon.png') }}" alt="Back" class="w-6 h-6">
                </a>
                <span class="text-xl font-bold tracking-wide">KylariaSHOP</span>
            </div>
            <div class="flex items-center">
                <div class="h-8 w-px bg-gray-400 mr-4"></div>
                <a href="{{ route('home') }}" class="text-lg font-bold hover:text-orange uppercase tracking-wider">HOME</a>
            </div>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            {{-- Hidden payment method input --}}
            <input type="hidden" name="payment_method" id="selected-payment" value="">

            {{-- Total + Address Row --}}
            <div class="flex flex-col lg:flex-row gap-6 mb-10">

                {{-- Left: TOTAL Box --}}
                <div class="w-full lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-lg border border-gray-300 overflow-hidden shadow-sm h-full flex flex-col min-h-[220px]">
                        <div class="py-3 border-b border-gray-300 text-center">
                            <h2 class="text-[#FF5722] text-2xl font-black uppercase tracking-widest">TOTAL</h2>
                        </div>
                        <div class="flex-grow flex items-center justify-center p-6">
                            <span class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight">
                                {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Address Textarea --}}
                <div class="flex-1">
                    <div class="bg-white rounded-lg border border-gray-300 overflow-hidden shadow-sm h-full min-h-[220px]">
                        <textarea
                            name="address"
                            id="address"
                            class="w-full h-full min-h-[220px] border-none focus:ring-0 p-5 text-gray-800 resize-none font-medium text-base"
                            placeholder="Enter your address :"
                            required
                        >{{ old('address') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Payment Method Selection --}}
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Pilih Metode Pembayaran</p>
                <div class="flex flex-wrap gap-3" id="payment-options">

                    {{-- PayPal --}}
                    <button type="button"
                        data-method="paypal"
                        onclick="selectPayment('paypal')"
                        class="payment-btn flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-gray-200 bg-white hover:border-[#FF5722] transition-all cursor-pointer min-w-[130px]">
                        <svg class="w-20 h-6" viewBox="0 0 124 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z" fill="#253B80"/>
                            <path d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z" fill="#179BD7"/>
                            <path d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2.065c-.713.502-1.557.883-2.507 1.136-.921.245-1.977.369-3.14.369h-.747a2.243 2.243 0 0 0-2.214 1.892l-.056.301-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z" fill="#253B80"/>
                        </svg>
                    </button>

                    {{-- DANA --}}
                    <button type="button"
                        data-method="dana"
                        onclick="selectPayment('dana')"
                        class="payment-btn flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-gray-200 bg-white hover:border-[#FF5722] transition-all cursor-pointer min-w-[120px]">
                        <span class="text-[#108be8] font-black text-2xl tracking-tight">DANA</span>
                    </button>

                    {{-- BCA (Coming Soon) --}}
                    <button type="button"
                        disabled
                        class="payment-btn flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 cursor-not-allowed min-w-[120px] opacity-60">
                        <span class="text-gray-500 font-bold text-lg">BCA <span class="text-xs font-normal">(Soon)</span></span>
                    </button>

                    {{-- Mandiri (Coming Soon) --}}
                    <button type="button"
                        disabled
                        class="payment-btn flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 cursor-not-allowed min-w-[140px] opacity-60">
                        <span class="text-gray-500 font-bold text-lg">Mandiri <span class="text-xs font-normal">(Soon)</span></span>
                    </button>

                </div>
                {{-- Payment validation error --}}
                <p id="payment-error" class="text-red-500 text-sm mt-2 hidden">Silakan pilih metode pembayaran terlebih dahulu.</p>
            </div>

            {{-- Checkout Button --}}
            <div class="flex justify-end mb-16">
                <button type="submit" id="checkout-btn"
                    onclick="return validatePayment()"
                    class="bg-[#FF8A65] hover:bg-[#FF7043] active:bg-[#E64A19] text-white font-black text-2xl py-4 px-14 rounded-xl transition-colors shadow-sm tracking-widest uppercase">
                    CHECKOUT
                </button>
            </div>

            <hr class="border-gray-200 mb-12">

            {{-- Footer --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-gray-600 pb-8">

                {{-- About --}}
                <div class="flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kylaria SHOP</h3>
                    <p class="text-sm font-medium leading-relaxed mb-6">
                        Kylaria Shop is a place that sells hobby toys ranging from figures to costumes.
                    </p>
                    <div class="flex gap-4 items-center">
                        <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Payment logos --}}
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Payment</h3>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/2560px-PayPal.svg.png" alt="PayPal" class="h-5 object-contain">
                        </div>
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/2560px-Logo_dana_blue.svg.png" alt="DANA" class="h-5 object-contain">
                        </div>
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" alt="BCA" class="h-7 object-contain">
                        </div>
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-5 object-contain">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 italic">Coming Soon.....</p>
                </div>

                {{-- Support --}}
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2 text-sm font-medium">
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition-colors">Legal</a></li>
                    </ul>
                </div>

            </div>

        </form>
    </div>

    <script>
        function selectPayment(method) {
            // Update hidden input
            document.getElementById('selected-payment').value = method;

            // Reset all buttons
            document.querySelectorAll('.payment-btn[data-method]').forEach(btn => {
                btn.classList.remove('border-[#FF5722]', 'bg-orange-50', 'shadow-md');
                btn.classList.add('border-gray-200', 'bg-white');
            });

            // Highlight selected
            const selected = document.querySelector(`.payment-btn[data-method="${method}"]`);
            if (selected) {
                selected.classList.remove('border-gray-200', 'bg-white');
                selected.classList.add('border-[#FF5722]', 'bg-orange-50', 'shadow-md');
            }

            // Hide error if shown
            document.getElementById('payment-error').classList.add('hidden');
        }

        function validatePayment() {
            const method = document.getElementById('selected-payment').value;
            if (!method) {
                document.getElementById('payment-error').classList.remove('hidden');
                document.getElementById('payment-options').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
            return true;
        }
    </script>

</x-app-layout>
