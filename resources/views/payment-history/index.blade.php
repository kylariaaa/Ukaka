<x-app-layout title="Riwayat Pembayaran — HoobyShoop" :showSearch="false">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <img src="{{ asset('images/back-icon.png') }}" alt="Back" class="w-5 h-5">
            </a>
            <h1 class="text-xl font-bold text-gray-900">Riwayat Pembayaran</h1>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                @php
                    // Dihitung satu kali per order, dipakai di seluruh kartu
                    $isCancelled = in_array($order->status, ['rejected', 'expired'])
                        || ($order->status === 'process' && $order->sla_deadline && now()->isAfter($order->sla_deadline));
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Order Header --}}
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            {{-- Status Indicator Dot --}}
                            <div class="w-3 h-full min-h-[20px] rounded-full
                                {{ $order->status === 'finished' ? 'bg-green-500' : ($isCancelled ? 'bg-red-500' : 'bg-orange') }}"></div>
                            <div>
                                <p class="text-xs text-gray-500">Kode Order</p>
                                <p class="text-sm font-bold text-gray-800 font-mono">{{ $order->order_code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-sm font-bold text-orange">{{ $order->rupiah_total_price }}</p>
                        </div>
                    </div>

                    {{-- ⏰ SLA Countdown Banner — hanya tampil saat status 'process' --}}
                    @if($order->status === 'process' && $order->sla_deadline)
                        @php
                            $remainSec = now()->diffInSeconds($order->sla_deadline, false);
                        @endphp
                        @if($remainSec > 0)
                        <div class="mx-4 mt-3 mb-1 px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-2"
                             id="sla-banner-{{ $order->id }}">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-700">
                                Pesanan akan otomatis dibatalkan dalam
                                <span class="font-bold" id="sla-countdown-{{ $order->id }}">--</span>
                                jika admin belum merespons.
                            </p>
                        </div>
                        <script>
                            (function() {
                                var deadline = new Date('{{ $order->sla_deadline->toIso8601String() }}');
                                var el = document.getElementById('sla-countdown-{{ $order->id }}');
                                var banner = document.getElementById('sla-banner-{{ $order->id }}');
                                function tick() {
                                    var diff = Math.floor((deadline - Date.now()) / 1000);
                                    if (diff <= 0) {
                                        el.textContent = '0 detik';
                                        banner.classList.remove('bg-amber-50','border-amber-200');
                                        banner.classList.add('bg-red-50','border-red-300');
                                        el.closest('p').classList.replace('text-amber-700','text-red-700');
                                        el.closest('p').innerHTML = '⛔ SLA habis! Pesanan ini akan segera dibatalkan oleh sistem.';
                                        return;
                                    }
                                    var days  = Math.floor(diff / 86400);
                                    var hours = Math.floor((diff % 86400) / 3600);
                                    var mins  = Math.floor((diff % 3600) / 60);
                                    var secs  = diff % 60;
                                    var parts = [];
                                    if (days  > 0) parts.push(days  + ' hari');
                                    if (hours > 0) parts.push(hours + ' jam');
                                    if (mins  > 0) parts.push(mins  + ' menit');
                                    parts.push(secs + ' detik');
                                    el.textContent = parts.join(' ');
                                }
                                tick();
                                setInterval(tick, 1000);
                            })();
                        </script>
                        @else
                        <div class="mx-4 mt-3 mb-1 px-4 py-2.5 bg-red-50 border border-red-200 rounded-xl flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-red-700 font-semibold">⛔ SLA habis! Pesanan ini akan segera dibatalkan oleh sistem.</p>
                        </div>
                        @endif
                    @endif

                    {{-- Order Items --}}
                    @foreach($order->orderItems as $item)
                    @if($item->product)
                    <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 last:border-0">

                        {{-- Status Stripe --}}
                        <div class="w-1 self-stretch rounded-full
                            {{ $order->status === 'finished' ? 'bg-green-500' : ($isCancelled ? 'bg-red-500' : 'bg-orange') }}
                            flex-shrink-0"></div>

                        {{-- Product Image --}}
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            @if($item->product->image && file_exists(public_path($item->product->image)))
                                <img src="{{ asset($item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $item->product->name }}</h3>
                            <p class="text-red-500 font-bold text-sm mt-0.5">IDR {{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Select {{ $item->quantity }}</p>
                        </div>

                        {{-- Status Column --}}
                        <div class="flex flex-col items-center gap-1 flex-shrink-0 w-20">
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Status</p>
                            @if($order->status === 'finished')
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Finished" class="w-5 h-5">
                                </div>
                                <p class="text-xs font-semibold text-green-600">Finished</p>

                            @elseif($isCancelled)
                                {{-- Dibatalkan: rejected, expired, atau process yang SLA-nya sudah habis --}}
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center">
                                    <img src="{{ asset('images/cancel-icon.png') }}" alt="Dibatalkan" class="w-5 h-5">
                                </div>
                                <p class="text-xs font-semibold text-red-600">Dibatalkan</p>

                            @else
                                {{-- Masih diproses & SLA masih berlaku --}}
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center">
                                    <img src="{{ asset('images/proses-icon.png') }}" alt="Proses" class="w-5 h-5 animate-spin">
                                </div>
                                <p class="text-xs font-semibold text-gray-700">Proses</p>
                            @endif
                        </div>

                        {{-- Payment Method Column --}}
                        <div class="flex flex-col items-center gap-1 flex-shrink-0 w-24">
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Payment</p>
                            @php
                                $paymentStyles = [
                                    'paypal'  => ['label' => 'PayPal',  'color' => 'text-blue-700',  'bg' => 'bg-blue-50'],
                                    'dana'    => ['label' => 'DANA',    'color' => 'text-teal-600',  'bg' => 'bg-teal-50'],
                                    'bca'     => ['label' => 'BCA',     'color' => 'text-blue-800',  'bg' => 'bg-blue-50'],
                                    'mandiri' => ['label' => 'Mandiri', 'color' => 'text-yellow-700','bg' => 'bg-yellow-50'],
                                ];
                                $pm = $paymentStyles[$order->payment_method] ?? ['label' => ucfirst($order->payment_method), 'color' => 'text-gray-600', 'bg' => 'bg-gray-50'];
                            @endphp
                            <div class="px-3 py-1.5 rounded-lg {{ $pm['bg'] }}">
                                <span class="text-xs font-bold {{ $pm['color'] }}">{{ $pm['label'] }}</span>
                            </div>
                        </div>

                    </div>
                    @endif
                    @endforeach
                </div>
                @endforeach
            </div>

        @else
            {{-- Empty State --}}
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada riwayat pesanan</h3>
                <p class="text-sm text-gray-400 mb-6">Yuk, belanja dulu!</p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-3 px-8 rounded-xl transition-colors">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
