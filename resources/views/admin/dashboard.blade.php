<x-admin-layout title="Admin Dashboard">

    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-black mb-3">Selamat Datang {{ auth()->user()->name }}!</h1>
        <p class="text-xl text-gray-800">Periksa kembali stok dan jangan lupa bersyukur</p>
    </div>

    {{-- Stats Cards Container --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Order Completed --}}
        <div class="bg-[#8A0505] rounded-xl p-8 flex flex-col items-center justify-center min-h-[250px] shadow-sm">
            <h2 class="text-white text-3xl font-black text-center leading-tight mb-4 uppercase">ORDER<br>COMPLETED</h2>
            <div class="text-white text-7xl font-black">{{ $completedOrdersCount }}</div>
        </div>

        {{-- Stock --}}
        <div class="bg-[#5C0A0A] rounded-xl p-8 flex flex-col items-center justify-center min-h-[250px] shadow-sm">
            <h2 class="text-white text-3xl font-black text-center mb-4 uppercase">STOCK</h2>
            <div class="text-white text-7xl font-black">{{ $totalStock }}</div>
        </div>

        {{-- Order Entered --}}
        <div class="bg-[#FF0000] rounded-xl p-8 flex flex-col items-center justify-center min-h-[250px] shadow-sm">
            <h2 class="text-white text-3xl font-black text-center leading-tight mb-4 uppercase">ORDER<br>ENTERED</h2>
            <div class="text-white text-7xl font-black">{{ $enteredOrdersCount }}</div>
        </div>

    </div>

</x-admin-layout>
