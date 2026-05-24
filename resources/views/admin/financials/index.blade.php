@extends('layouts.admin')

@section('title', 'Financial Reports')

@section('content')
    <div class="space-y-6 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 ring-1 ring-green-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-blue-900 dark:text-white">Financial Reports</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Revenue analysis and profit margins.</p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-full border border-emerald-100 dark:border-emerald-800/50">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Live
                    Sync</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Gross Revenue -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-700 dark:from-indigo-500 dark:to-indigo-600 border border-slate-100 dark:border-slate-800 rounded-3xl shadow-lg shadow-indigo-500/10 group p-6">
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="relative z-10">
                    <p class="text-[8px] font-bold text-indigo-100 uppercase tracking-[0.2em] mb-2">Gross Revenue</p>
                    <h3 class="text-2xl font-black text-white mb-3">GHC {{ number_format($stats['total_revenue'], 2) }}</h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/10 text-white text-[8px] font-bold uppercase">
                        Total Sales
                    </div>
                </div>
            </div>

            <!-- Transaction Charges -->
            <div
                class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl shadow-sm hover:shadow-md transition-all group p-6">
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="relative z-10">
                    <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-2">
                        Gateway Charges</p>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3">GHC
                        {{ number_format($stats['total_charges'], 2) }}
                    </h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[8px] font-bold uppercase">
                        Platform Fees
                    </div>
                </div>
            </div>

            <!-- Referral Commissions -->
            <div
                class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl shadow-sm hover:shadow-md transition-all group p-6">
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="relative z-10">
                    <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-2">
                        Paid Commissions</p>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3">GHC
                        {{ number_format($stats['total_commissions'], 2) }}
                    </h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[8px] font-bold uppercase">
                        Referral Payouts
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-emerald-600 to-emerald-700 dark:from-emerald-500 dark:to-emerald-600 rounded-3xl shadow-lg shadow-emerald-500/20 group p-6">
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="relative z-10">
                    <p class="text-[8px] font-bold text-emerald-100 uppercase tracking-[0.2em] mb-2">Net Platform Gain</p>
                    <h3 class="text-2xl font-black text-white mb-3">GHC {{ number_format($stats['net_profit'], 2) }}</h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/10 text-white text-[8px] font-bold uppercase">
                        Net Earnings
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Verification Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-50 dark:border-slate-800 pb-4 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ledger & Wallet Balance Audit</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                        Financial Integrity Guard
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 font-medium">Last audited: {{ $auditStats['last_audit'] ?? 'Never' }}</span>
                </div>
            </div>

            @if($auditStats['unbalanced'])
                <div class="flex items-start gap-4 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-800/40 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-rose-800 dark:text-rose-400">Ledger Discrepancy Alert!</h4>
                        <p class="text-xs text-rose-600 dark:text-rose-400/80 mt-1">
                            The cumulative transaction ledger does not balance with current wallet allocations. Mismatch Amount: <strong class="font-bold">GHS {{ number_format($auditStats['mismatch_amount'], 2) }}</strong>. Please run <code>php artisan audit:wallets</code> for complete details.
                        </p>
                    </div>
                </div>
                
                @if(!empty($auditStats['audit_logs']))
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800">
                                <th class="py-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">User</th>
                                <th class="py-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">Wallet Balance</th>
                                <th class="py-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">Ledger Balance</th>
                                <th class="py-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right">Difference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-xs">
                            @foreach($auditStats['audit_logs'] as $log)
                            <tr>
                                <td class="py-3 font-medium text-slate-900 dark:text-white">ID {{ $log['user_id'] }} - {{ $log['name'] }}</td>
                                <td class="py-3 text-right text-slate-700 dark:text-slate-300">GHS {{ number_format($log['current_balance'], 2) }}</td>
                                <td class="py-3 text-right text-slate-700 dark:text-slate-300">GHS {{ number_format($log['ledger_balance'], 2) }}</td>
                                <td class="py-3 text-right font-bold text-rose-600">GHS {{ number_format($log['difference'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @else
                <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/40 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-emerald-800 dark:text-emerald-400">All Ledger Records Balanced</h4>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400/80 mt-1">
                            The automated auditing mechanism has verified that all system wallets perfectly balance with the double-entry transactions log database. Ledger integrity is 100% verified.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly Analysis -->
    <div
        class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div
            class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Monthly Revenue Breakdown</h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                    Historical Data</p>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                            Month / Period</th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                            Revenue (GHS)</th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                            Profit (GHS)</th>
                        <th
                            class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                            Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($monthlyData as $data)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-sm text-slate-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($data->month . '-01')->format('F Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-base font-black text-slate-900 dark:text-white">
                                    {{ number_format($data->revenue, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-base font-black text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($data->profit, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 text-emerald-500 font-black text-[10px] uppercase tracking-tighter">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 10l7-7 7 7" stroke-width="3"></path>
                                    </svg>
                                    Growth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-24 text-center text-slate-400 dark:text-slate-700">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 17v-2m3 2v-4m3 2v-6m-8-2h10a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z">
                                    </path>
                                </svg>
                                <p class="font-bold text-sm uppercase tracking-widest italic">Insufficient Data</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection