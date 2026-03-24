

<?php $__env->startSection('title', 'Profit Calculator'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-10">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white">Profit Portfolio</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Surgical analysis of your net earnings across
                    networks and agents.</p>
            </div>

            <form action="<?php echo e(route('admin.profit')); ?>" method="GET"
                class="flex flex-wrap items-end gap-3 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl shadow-sm">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo e($startDate); ?>"
                        class="block w-full px-3 py-2 text-xs font-bold rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">End Date</label>
                    <input type="date" name="end_date" value="<?php echo e($endDate); ?>"
                        class="block w-full px-3 py-2 text-xs font-bold rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/25">
                    Recalculate
                </button>
            </form>
        </div>

        
        <div class="grid gap-8 lg:grid-cols-4">
            
            <div class="lg:col-span-3 grid gap-6 grid-cols-1 md:grid-cols-3">
                <?php
                    $netColors = [
                        'mtn' => ['bg' => 'from-amber-400 to-amber-600', 'text' => 'text-amber-950', 'label' => 'MTN'],
                        'telecel' => ['bg' => 'from-rose-600 to-rose-700', 'text' => 'text-white', 'label' => 'Telecel'],
                        'airteltigo' => ['bg' => 'from-sky-600 to-sky-700', 'text' => 'text-white', 'label' => 'AT'],
                        'at' => ['bg' => 'from-sky-600 to-sky-700', 'text' => 'text-white', 'label' => 'AT'],
                    ];
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $networkProfits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $netProfit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $slug = strtolower($netProfit->network);
                        $color = $netColors[$slug] ?? ['bg' => 'from-slate-600 to-slate-700', 'text' => 'text-white', 'label' => strtoupper($netProfit->network)];
                    ?>
                    <div
                        class="p-8 rounded-[2rem] bg-gradient-to-br <?php echo e($color['bg']); ?> <?php echo e($color['text']); ?> shadow-xl transition-all hover:-translate-y-2 relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000">
                        </div>

                        <div class="relative z-10">
                            <h5 class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-2"><?php echo e($color['label']); ?>

                                Network Profit</h5>
                            <p class="text-3xl font-black tabular-nums">GHS <?php echo e(number_format($netProfit->total_profit, 2)); ?></p>

                            <div class="mt-8 pt-6 border-t border-black/5 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase opacity-60">Total Volume</p>
                                    <p class="text-sm font-bold"><?php echo e($netProfit->order_count); ?> Orders</p>
                                </div>
                                <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 opacity-40 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div
                        class="md:col-span-3 p-12 text-center bg-white dark:bg-slate-900 rounded-[2rem] border-2 border-dashed border-slate-100 dark:border-slate-800">
                        <p class="text-sm font-bold text-slate-400 italic">No network performance data for this period</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <div
                class="bg-indigo-600 dark:bg-indigo-500 text-white rounded-[2rem] p-8 shadow-2xl shadow-indigo-600/30 relative overflow-hidden group flex flex-col justify-center text-center">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] opacity-60 mb-3">Total Net Gain</p>
                    <h4 class="text-4xl font-black tabular-nums tracking-tighter">GHS
                        <?php echo e(number_format($totalProfitInRange, 2)); ?>

                    </h4>
                    <p
                        class="text-[10px] font-bold text-indigo-100 mt-4 uppercase tracking-widest bg-white/10 py-2 px-4 rounded-full inline-block">
                        Period Overview</p>
                </div>
            </div>
        </div>

        
        <div
            class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
            <div
                class="px-10 py-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                <div>
                    <h4 class="text-lg font-black text-slate-900 dark:text-white">Agent Contributions</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Direct Profit Generation
                        by User</p>
                </div>
                <div
                    class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-500/20">
                    Top 20 Performers
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th
                                class="px-10 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                User / Agent Details</th>
                            <th
                                class="px-10 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                Volume</th>
                            <th
                                class="px-10 py-5 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                Profit Contribution</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        <?php $__empty_1 = true; $__currentLoopData = $userProfits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $up): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all group">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 font-black text-sm group-hover:scale-110 transition-transform">
                                            <?php echo e(strtoupper(substr($up->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 dark:text-white"><?php echo e($up->name); ?></p>
                                            <p class="text-[11px] text-slate-400 font-medium"><?php echo e($up->email); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-center">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-[11px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-tight">
                                        <?php echo e($up->order_count); ?> completed orders
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 tabular-nums">GHS
                                        <?php echo e(number_format($up->total_profit, 2)); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-10 py-20 text-center text-slate-400 italic font-medium">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p>No agent performance data recorded for this period.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\admin\profit\index.blade.php ENDPATH**/ ?>