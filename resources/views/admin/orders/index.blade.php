@extends('layouts.admin')
@section('title', 'Order Management')

@php /** @var \Illuminate\Pagination\LengthAwarePaginator $orders */ @endphp

@section('content')
    <div class="space-y-6 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 ring-1 ring-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Order Management
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $orders->total() }} total orders</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative group">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-primary transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search ref, phone, name..."
                        value="{{ request('search') }}"
                        class="h-10 w-72 pl-10 pr-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium outline-none focus:ring-4 focus:ring-primary/10 transition-all">
                </div>

                {{-- New Order --}}
                <a href="{{ route('admin.orders.create') }}"
                    class="h-10 px-5 bg-primary text-white rounded-xl font-bold text-xs shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Order
                </a>

                {{-- Export --}}
                <a href="{{ route('admin.orders.export', request()->all()) }}"
                    class="h-10 px-5 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 active:scale-95 transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </a>
            </div>
        </div>

        {{-- Active filter banners --}}
        @if(request('user_id'))
            @php $filteredUser = \App\Models\User::find(request('user_id')); @endphp
            @if($filteredUser)
                <div class="flex items-center gap-3 bg-primary/5 border border-primary/10 px-4 py-2.5 rounded-2xl w-fit">
                    <div class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center font-black text-xs">
                        {{ strtoupper(substr($filteredUser->name, 0, 1)) }}
                    </div>
                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $filteredUser->name }}'s Orders</p>
                    <a href="{{ route('admin.orders') }}"
                        class="p-1 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg transition-all" title="Clear">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            @endif
        @endif

        {{-- Filters Row --}}
        <div class="flex flex-col gap-3">
            {{-- Status Tabs --}}
            <div class="flex flex-wrap gap-2 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl w-fit">
                @php
                    $statusMap = [
                        'all' => 'All',
                        'pending_payment' => 'Pending Payment',
                        'awaiting_transfer' => 'Awaiting Transfer',
                        'validation' => 'Validating',
                        'processing' => 'Processing',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
                    ];
                    $activeStatus = request('status', 'all');
                @endphp
                @foreach($statusMap as $val => $label)
                    <button onclick="filterBy('status', '{{ $val }}')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold capitalize transition-all whitespace-nowrap
                                                                            {{ $activeStatus === $val ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Per Page + Clear --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Per Page --}}
                <select onchange="filterBy('per_page', this.value)"
                    class="h-9 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest outline-none dark:text-slate-400">
                    @foreach([10, 25, 50, 100, 500] as $val)
                        <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }} / page
                        </option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['status', 'network', 'user_id', 'search']))
                    <a href="{{ route('admin.orders') }}"
                        class="h-9 px-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-100 transition-all flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </a>
                @endif

                <div class="ml-auto text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden lg:block">
                    Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}
                </div>
            </div>
        </div>

        {{-- Network Summary Cards --}}
        @php
            // Lowercase keys so the lookup is case-insensitive
            $netColorMap = [
                'mtn' => ['bg' => '#FBBF24', 'border' => '#F59E0B', 'text' => '#1C0F00', 'iconBg' => 'rgba(0,0,0,0.15)'],
                'telecel' => ['bg' => '#E11D48', 'border' => '#BE123C', 'text' => '#ffffff', 'iconBg' => 'rgba(255,255,255,0.2)'],
                'airteltigo' => ['bg' => '#0284C7', 'border' => '#0369A1', 'text' => '#ffffff', 'iconBg' => 'rgba(255,255,255,0.2)'],
                'at' => ['bg' => '#0284C7', 'border' => '#0369A1', 'text' => '#ffffff', 'iconBg' => 'rgba(255,255,255,0.2)'],
            ];
            $activeNetwork = request('network', 'all');
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-3">
            {{-- Total (All) Card --}}
            <div class="p-4 rounded-2xl border shadow-sm flex items-center justify-between cursor-pointer hover:scale-[1.02] active:scale-95 transition-all
                            {{ $activeNetwork === 'all' ? 'border-primary ring-2 ring-primary/30 bg-primary/5 text-primary' : 'border-primary/20 bg-primary/5 text-primary' }}"
                onclick="filterBy('network', 'all')">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-60">Total</p>
                    <p class="text-2xl font-black tabular-nums">{{ $totalFilteredOrders }}</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-primary/20 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>

            @foreach($networks as $net)
                @php
                    $count = $networkCounts[$net] ?? 0;
                    $nc = $netColorMap[strtolower($net)] ?? null;
                    $isActive = $activeNetwork === $net;
                    $cardStyle = $nc
                        ? "background:{$nc['bg']};border-color:{$nc['border']};color:{$nc['text']};"
                        : '';
                    $iconStyle = $nc ? "background:{$nc['iconBg']};" : '';
                @endphp
                @if($count > 0)
                    <div class="p-4 rounded-2xl border shadow-sm flex items-center justify-between cursor-pointer transition-all
                                                {{ $isActive ? 'ring-4 scale-[1.02]' : 'hover:scale-[1.02] active:scale-95' }}"
                        style="{{ $cardStyle }} {{ $isActive ? 'ring-color:rgba(255,255,255,0.4);' : '' }}"
                        onclick="filterBy('network', '{{ $net }}')">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-70">{{ $net }}</p>
                            <p class="text-2xl font-black tabular-nums">{{ $count }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="{{ $iconStyle }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Orders Table --}}
        <div
            class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Order
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bundle
                            </th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Cost</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors group"
                                id="order-row-{{ $order->id }}">

                                {{-- Order ID + Date --}}
                                <td class="px-5 py-3.5">
                                    <p class="font-mono text-xs font-bold text-primary">
                                        #{{ strtoupper(substr($order->reference, 4, 10)) }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $order->created_at->format('d M, H:i') }}
                                    </p>
                                </td>

                                {{-- Customer --}}
                                <td class="px-5 py-3.5">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-none">
                                        @if($order->source === 'storefront' && $order->guest_email)
                                            {{ $order->guest_email }}
                                        @else
                                            {{ $order->user->name ?? '—' }}
                                        @endif
                                    </p>
                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $order->recipient_phone }}</p>
                                </td>

                                {{-- Bundle --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $nc = $netColorMap[strtolower($order->bundle->network ?? '')] ?? null;
                                            $badgeStyle = $nc ? "background:{$nc['bg']};color:{$nc['text']};" : '';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase"
                                            style="{{ $badgeStyle ?: 'background:#e2e8f0;color:#475569;' }}">{{ $order->bundle->network ?? '?' }}</span>
                                        <span
                                            class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $order->bundle->name ?? '—' }}</span>
                                    </div>
                                </td>

                                {{-- Cost --}}
                                <td class="px-5 py-3.5 text-right">
                                    <p class="text-sm font-black text-slate-900 dark:text-white tabular-nums">
                                        GH₵{{ number_format($order->cost, 2) }}</p>
                                </td>

                                {{-- Status - Inline Select --}}
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $scMap = [
                                            'pending_payment' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 border-amber-200 dark:border-amber-800',
                                            'awaiting_transfer' => 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 border-orange-200 dark:border-orange-800',
                                            'validation' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 border-blue-200 dark:border-blue-800',
                                            'processing' => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 border-indigo-200 dark:border-indigo-800',
                                            'delivered' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 border-emerald-200 dark:border-emerald-700',
                                            'failed' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-700 border-rose-200 dark:border-rose-800',
                                        ];
                                        $sc = $scMap[$order->status] ?? 'bg-slate-50 dark:bg-slate-800 text-slate-600 border-slate-200 dark:border-slate-700';
                                    @endphp
                                    <select onchange="updateStatus({{ $order->id }}, this.value)"
                                        class="px-2 py-1 rounded-lg text-[10px] font-bold border outline-none cursor-pointer transition-all {{ $sc }}">
                                        @foreach(['pending_payment', 'awaiting_transfer', 'validation', 'processing', 'delivered', 'failed'] as $st)
                                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                                {{ $statusLabels[$st] ?? ucwords(str_replace('_', ' ', $st)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" title="Edit Order"
                                            class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-primary/10 text-slate-400 hover:text-primary transition-all flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <button onclick="deleteOrder({{ $order->id }})" title="Delete Order"
                                            class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-900/20 text-slate-400 hover:text-rose-600 transition-all flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-400 dark:text-slate-600">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-sm font-semibold">No orders found</p>
                                        @if(request()->anyFilled(['status', 'network', 'search', 'user_id']))
                                            <a href="{{ route('admin.orders') }}"
                                                class="text-xs font-black text-primary uppercase tracking-widest hover:underline">Clear
                                                Filters</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->isNotEmpty())
                <div class="px-5 py-3.5 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between gap-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Records {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}
                    </p>
                    {{ $orders->withQueryString()->links('pagination::simple-tailwind') }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        <script>
            // Filter helper — updates the URL and reload          s
            function filterBy(key, value) {
                const url = new URL(window.location.href);
                if (value === 'all' || value === '') {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, value);
                }
                url.searchParams.delete('page');
                window.location.href = url.href;
            }

            // Search on Enter
            document.getElementById('searchInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const url = new URL(window.location.href);
                    const val = this.value.trim();
                    if (val) {
                        url.searchParams.set('search', val);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.href;
                }
            });

            // Update order status via AJAX
            async function updateStatus(orderId, status) {
                try {
                    const response = await fetch(`{{ url('admin/orders') }}/${orderId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status })
                    });
                    if (response.ok) {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Order status updated.', type: 'success' } }));
                    } else {
                        const data = await response.json();
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Update failed', type: 'error' } }));
                    }
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Network error', type: 'error' } }));
                }
            }

            // Delete order
            async function deleteOrder(orderId) {
                if (!confirm('Delete this order? This will also remove associated transactions and cannot be undone.')) return;
                try {
                    const response = await fetch(`{{ url('admin/orders') }}/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        const row = document.getElementById('order-row-' + orderId);
                        if (row) row.remove();
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Order deleted.', type: 'success' } }));
                    } else {
                        const data = await response.json();
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Deletion failed', type: 'error' } }));
                    }
                } catch (e) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Network error', type: 'error' } }));
                }
            }
        </script>
    @endpush
@endsection