<?php ($hideNav = true); ?>


<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen flex items-center justify-center bg-[#f5f5f9] dark:bg-slate-950 p-4 transition-colors duration-300"
        x-data="{ 
                    loading: false,
                    submit() {
                        this.loading = true;
                        this.$refs.resetForm.submit();
                    }
                 }">
        <div class="w-full max-w-[400px] animate-in fade-in zoom-in duration-500">
            <!-- Logo Header -->
            <div class="flex flex-col items-center gap-3 mb-8">
                <div
                    class="w-14 h-14 bg-white dark:bg-slate-900 rounded-2xl shadow-sm flex items-center justify-center border border-slate-100 dark:border-slate-800 transition-transform hover:scale-105 duration-300">
                    <img src="<?php echo e(asset('favicon.ico')); ?>" alt="Logo" class="w-8 h-8">
                </div>
                <h1 class="text-2xl font-black tracking-tight text-slate-800 dark:text-slate-100 uppercase">
                    <?php echo e(config('app.name')); ?>

                </h1>
            </div>

            <!-- Reset Password Card -->
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-[0_2px_10px_0_rgba(67,89,113,0.1)] dark:shadow-none border-none overflow-hidden">
                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-200 mb-1">Reset Password 🔄</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Enter your new password below
                        </p>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-lg animate-in slide-in-from-top-2">
                            <p class="text-xs font-bold text-rose-600 uppercase tracking-wider"><?php echo e($errors->first()); ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.update')); ?>" x-ref="resetForm" @submit.prevent="submit"
                        class="space-y-5">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="token" value="<?php echo e($token); ?>">

                        <div class="space-y-2">
                            <label for="email"
                                class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 ml-1">Email</label>
                            <input id="email" type="email" name="email" value="<?php echo e($email ?? old('email')); ?>" required
                                autofocus
                                class="w-full h-11 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all text-sm text-slate-700 dark:text-slate-200 placeholder:text-slate-300"
                                placeholder="Enter your email">
                        </div>

                        <div class="space-y-2">
                            <label for="password"
                                class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 ml-1">New
                                Password</label>
                            <input id="password" type="password" name="password" required
                                class="w-full h-11 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all text-sm text-slate-700 dark:text-slate-200 placeholder:text-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 ml-1">Confirm
                                New Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full h-11 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:border-primary focus:ring-[3px] focus:ring-primary/10 transition-all text-sm text-slate-700 dark:text-slate-200 placeholder:text-slate-300">
                        </div>

                        <button type="submit" :disabled="loading"
                            class="w-full h-11 bg-primary text-white rounded-lg font-bold text-sm shadow-[0_4px_12px_rgba(105,108,255,0.4)] hover:bg-primary/90 hover:shadow-[0_4px_12px_rgba(105,108,255,0.5)] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                            <template x-if="!loading">
                                <span>Reset Password</span>
                            </template>
                            <template x-if="loading">
                                <div class="flex items-center gap-1 animate-pulse-text font-bold">
                                    <span>Resetting Password</span>
                                    <span class="flex items-center">
                                        <span class="animate-typing-dot" style="animation-delay: 0s">.</span>
                                        <span class="animate-typing-dot" style="animation-delay: 0.2s">.</span>
                                        <span class="animate-typing-dot" style="animation-delay: 0.4s">.</span>
                                    </span>
                                </div>
                            </template>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\auth\reset-password.blade.php ENDPATH**/ ?>