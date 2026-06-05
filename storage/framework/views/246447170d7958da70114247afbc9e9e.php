<?php if($announcement): ?>
    <div x-data="{ 
            showAnnouncement: false,
            announcementId: <?php echo e($announcement->id); ?>,
            init() {
                if (!localStorage.getItem('dismissed_announcement_' + this.announcementId)) {
                    // Small delay for better UX on page load
                    setTimeout(() => {
                        this.showAnnouncement = true;
                    }, 500);
                }
            },
            dismiss() {
                this.showAnnouncement = false;
                localStorage.setItem('dismissed_announcement_' + this.announcementId, 'true');
            }
        }"
        x-init="init()"
        x-show="showAnnouncement" 
        style="display: none;" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200 delay-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="dismiss()"></div>

        <!-- Modal Panel -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl w-full max-w-md relative z-10 border border-slate-100 dark:border-slate-800 overflow-hidden" 
             x-transition:enter="ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95">
            
            <?php
                $bgColors = [
                    'info' => 'bg-indigo-50 dark:bg-indigo-500/10',
                    'warning' => 'bg-amber-50 dark:bg-amber-500/10',
                    'danger' => 'bg-rose-50 dark:bg-rose-500/10',
                    'success' => 'bg-emerald-50 dark:bg-emerald-500/10',
                ];
                $iconColors = [
                    'info' => 'text-indigo-500',
                    'warning' => 'text-amber-500',
                    'danger' => 'text-rose-500',
                    'success' => 'text-emerald-500',
                ];
                $bgClass = $bgColors[$announcement->type] ?? $bgColors['info'];
                $iconClass = $iconColors[$announcement->type] ?? $iconColors['info'];
            ?>

            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between <?php echo e($bgClass); ?>">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm">
                        <?php if($announcement->type === 'info'): ?>
                            <svg class="w-5 h-5 <?php echo e($iconClass); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php elseif($announcement->type === 'warning'): ?>
                            <svg class="w-5 h-5 <?php echo e($iconClass); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <?php elseif($announcement->type === 'danger'): ?>
                            <svg class="w-5 h-5 <?php echo e($iconClass); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php else: ?>
                            <svg class="w-5 h-5 <?php echo e($iconClass); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Announcement</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5"><?php echo e($announcement->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>
                <button @click="dismiss()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-white/50 dark:bg-slate-800/50 rounded-xl transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2 leading-tight"><?php echo e($announcement->title); ?></h4>
                <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed space-y-4">
                    <?php echo nl2br(e($announcement->message)); ?>

                </div>

                <div class="mt-8 flex justify-end">
                    <button @click="dismiss()" class="w-full sm:w-auto px-6 py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 text-sm font-bold uppercase tracking-wider rounded-xl transition-colors shadow-lg shadow-slate-900/20 dark:shadow-white/20">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/components/announcement-modal.blade.php ENDPATH**/ ?>