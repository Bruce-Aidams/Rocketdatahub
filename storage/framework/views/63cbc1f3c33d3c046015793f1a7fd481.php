

<?php $__env->startSection('title', 'Manual Order Entry'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white uppercase tracking-tight">Manual
                    Order Entry</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-widest font-bold text-[10px]">
                    Administrative Order Override</p>
            </div>
            <a href="<?php echo e(route('admin.orders')); ?>"
                class="h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
        </div>

        <div
            class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="bg-gradient-to-br from-primary/10 to-transparent p-8 md:p-12">
                <form action="<?php echo e(route('admin.orders.store')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-1">Customer
                                Account</label>
                            <div class="relative group">
                                <select name="user_id" required
                                    class="w-full h-14 px-5 bg-white dark:bg-slate-950 border-none rounded-2xl text-sm font-bold text-slate-900 dark:text-white shadow-sm focus:ring-4 focus:ring-primary/10 transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>Select Customer Account</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-1">Recipient
                                Number</label>
                            <input type="tel" name="recipient_phone" required placeholder="024XXXXXXX" maxlength="10"
                                class="w-full h-14 px-5 bg-white dark:bg-slate-950 border-none rounded-2xl text-sm font-bold text-slate-900 dark:text-white shadow-sm focus:ring-4 focus:ring-primary/10 transition-all placeholder:font-normal placeholder:text-slate-400 font-mono tracking-widest <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-2 ring-rose-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 uppercase"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-1">Data Bundle
                                Type</label>
                            <div class="relative group">
                                <select name="bundle_id" required
                                    class="w-full h-14 px-5 bg-white dark:bg-slate-950 border-none rounded-2xl text-sm font-bold text-slate-900 dark:text-white shadow-sm focus:ring-4 focus:ring-primary/10 transition-all appearance-none cursor-pointer font-mono">
                                    <option value="" disabled selected>Select Data Bundle</option>
                                    <?php $__currentLoopData = $bundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $network => $networkBundles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="<?php echo e(strtoupper($network)); ?> NETWORK" class="font-black text-primary">
                                            <?php $__currentLoopData = $networkBundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bundle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bundle->id); ?>"><?php echo e($bundle->name); ?> - GHC
                                                    <?php echo e(number_format($bundle->price, 2)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-1">Initial
                                Status</label>
                            <div class="relative group">
                                <select name="status" required
                                    class="w-full h-14 px-5 bg-white dark:bg-slate-950 border-none rounded-2xl text-sm font-bold text-slate-900 dark:text-white shadow-sm focus:ring-4 focus:ring-primary/10 transition-all appearance-none cursor-pointer">
                                    <option value="pending">Validating (Pending)</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed (Delivered)</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-1">Payment
                                Context</label>
                            <div
                                class="p-4 bg-slate-100/50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                                <p
                                    class="text-[10px] font-bold text-slate-500 dark:text-slate-400 leading-relaxed uppercase tracking-tight">
                                    Orders created by admins are treated as <span
                                        class="text-primary font-black">Pre-Authorized</span> orders. Ledger will be
                                    adjusted based on the price assigned to the target account's role.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full h-16 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.3em] hover:bg-primary dark:hover:bg-primary dark:hover:text-white transition-all shadow-2xl active:scale-[0.98] flex items-center justify-center gap-3 group">
                            <span>Create Order</span>
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\admin\orders\create.blade.php ENDPATH**/ ?>