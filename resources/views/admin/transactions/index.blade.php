@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
    <div class="space-y-8 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-500 ring-1 ring-sky-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-blue-900 dark:text-white">Full Transaction History
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">A complete history of all financial
                        activities on the platform.</p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-full border border-emerald-100 dark:border-emerald-800/50">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Status:
                    Verified</span>
            </div>
        </div>

        <!-- Filters -->
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800 transition-all">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="space-y-8">
                <div class="grid gap-6 md:grid-cols-4">
                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Type
                            Filter</label>
                        <select name="type"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                            <option value="">All Streams</option>
                            <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Inflow (Credit)
                            </option>
                            <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Outflow (Debit)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Current
                            Status</label>
                        <select name="status"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                            <option value="">All States</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Validating
                            </option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">Start
                            Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">End
                            Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                    </div>
                </div>

                <div class="flex gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                    <button type="submit"
                        class="flex-[2] h-12 bg-primary text-white rounded-xl font-bold text-[10px] uppercase shadow-xl shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all">Search
                        Records</button>
                    <a href="{{ route('admin.transactions.export', request()->all()) }}"
                        class="flex-1 h-12 bg-emerald-500 text-white rounded-xl font-bold text-[10px] uppercase shadow-xl shadow-emerald-500/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="h-12 px-8 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl font-bold text-[10px] uppercase flex items-center justify-center hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 transition-all">Clear
                        Filters</a>
                </div>
            </form>
        </div>

        <!-- Ledger Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div
                class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Transaction History</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                        Complete record of all financial transactions</p>
                </div>
            </div>


            {{-- Transactions Table --}}
            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th
                                class="px-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                User / Entity</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                Date & Time</th>
                            <th
                                class="px-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                Description / Reference</th>
                            <th
                                class="px-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                Amount</th>
                            <th
                                class="px-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                Export</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-900 dark:bg-slate-800 flex items-center justify-center text-white font-bold text-sm transition-transform group-hover:scale-105">
                                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-900 dark:text-white leading-none">
                                                {{ $transaction->user->name }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-500 mt-1.5">
                                                {{ $transaction->user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span
                                        class="text-sm font-bold text-slate-900 dark:text-white block">{{ $transaction->created_at->format('d M, Y') }}</span>
                                    <span
                                        class="text-[10px] text-slate-500 dark:text-slate-500 mt-1 uppercase">{{ $transaction->created_at->format('H:i A') }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 leading-relaxed max-w-xs">
                                        {{ $transaction->description }}
                                    </p>
                                    <span
                                        class="font-mono text-[9px] font-bold text-primary dark:text-primary uppercase tracking-widest mt-2 block italic">REF:
                                        {{ $transaction->reference }}</span>
                                </td>
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <span
                                        class="text-base font-black tabular-nums {{ $transaction->type === 'credit' ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }} GHC
                                        {{ number_format($transaction->amount, 2) }}
                                    </span>
                                    <p
                                        class="text-[9px] font-bold text-slate-400 dark:text-slate-600 uppercase tracking-tighter mt-1">
                                        {{ $transaction->type }} transaction
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $statuses = [
                                            'success' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600',
                                            'pending' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600',
                                            'failed' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600',
                                        ];
                                        $sc = $statuses[$transaction->status] ?? 'bg-slate-50 dark:bg-slate-800 text-slate-600';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-tight {{ $sc }}">{{ $transaction->status === 'pending' ? 'Validating' : $transaction->status }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <a href="{{ route('admin.invoices.download', $transaction->id) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-50 dark:border-slate-800">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection