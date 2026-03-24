<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($reseller->store_name ?? $reseller->name); ?>'s Data Hub - <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-20 space-y-8 md:space-y-16">
        
        <div class="text-center space-y-4 md:space-y-6">
            <div
                class="inline-flex items-center gap-3 px-4 py-2 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-[0.2em] animate-in fade-in slide-in-from-top-4 duration-1000">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                Official Reseller Store
            </div>
            <h1
                class="text-3xl md:text-6xl font-black tracking-tight text-slate-900 dark:text-white uppercase leading-none animate-in fade-in slide-in-from-bottom-4 duration-700">
                <?php echo e($reseller->store_name ?? $reseller->name); ?>'s <span class="text-primary block md:inline">Data
                    Hub</span>
            </h1>
            <p
                class="max-w-2xl mx-auto text-sm md:text-lg text-slate-500 dark:text-slate-400 font-medium animate-in fade-in slide-in-from-bottom-6 duration-1000 px-4">
                Select your preferred network and bundle size below to receive instant data delivery. Secure, fast, and
                reliable.
            </p>
        </div>

        
        <div class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 max-w-5xl mx-auto px-4">
            <?php $__currentLoopData = $networks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $network): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $net = strtoupper($network);
                    $colors = match ($net) {
                        'MTN' => [
                            'bg' => 'bg-yellow-400/10',
                            'border' => 'border-yellow-200 dark:border-yellow-900/30',
                            'text' => 'text-yellow-800',
                            'btn' => 'bg-yellow-400 text-black hover:bg-yellow-300',
                            'card_bg' => 'bg-gradient-to-br from-yellow-500 to-yellow-600 text-black',
                            'ring' => 'group-hover:ring-yellow-400'
                        ],
                        'TELECEL' => [
                            'bg' => 'bg-red-500/10',
                            'border' => 'border-red-200 dark:border-red-900/30',
                            'text' => 'text-red-600',
                            'btn' => 'bg-red-600 text-white hover:bg-red-500',
                            'card_bg' => 'bg-gradient-to-br from-red-600 to-red-700 text-white',
                            'ring' => 'group-hover:ring-red-600'
                        ],
                        'AT', 'AIRTELTIGO' => [
                            'bg' => 'bg-blue-600/10',
                            'border' => 'border-blue-200 dark:border-blue-900/30',
                            'text' => 'text-blue-600',
                            'btn' => 'bg-blue-600 text-white hover:bg-blue-500',
                            'card_bg' => 'bg-gradient-to-br from-blue-600 to-blue-700 text-white',
                            'ring' => 'group-hover:ring-blue-600'
                        ],
                        default => [
                            'bg' => 'bg-slate-100',
                            'border' => 'border-slate-200',
                            'text' => 'text-slate-900',
                            'btn' => 'bg-slate-900 text-white',
                            'card_bg' => 'bg-slate-800 text-white',
                            'ring' => 'group-hover:ring-slate-500'
                        ]
                    };
                ?>

                <a href="<?php echo e(route('store.buy', ['referral_code' => $reseller->referral_code, 'network' => $network])); ?>"
                    class="group relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border <?php echo e($colors['border']); ?> shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 p-8 flex flex-col items-center justify-center text-center gap-6 ring-2 ring-transparent <?php echo e($colors['ring']); ?>">

                    
                    <div
                        class="w-24 h-24 rounded-full <?php echo e($colors['bg']); ?> flex items-center justify-center shadow-inner mb-4 transition-transform duration-500 group-hover:scale-110">
                        <span class="text-3xl font-black <?php echo e($colors['text']); ?>"><?php echo e(substr($net, 0, 1)); ?></span>
                    </div>

                    <div class="space-y-2 relative z-10">
                        <h2 class="text-3xl font-black <?php echo e($colors['text']); ?>"><?php echo e($network); ?></h2>
                        <p class="text-sm font-bold opacity-60 uppercase tracking-widest text-slate-500">Instant Delivery
                        </p>
                    </div>

                    <div class="w-full mt-4">
                        <span
                            class="w-full py-4 rounded-xl font-black uppercase tracking-widest <?php echo e($colors['btn']); ?> shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <span>Buy Bundle</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </div>

                    
                    <div
                        class="absolute inset-0 opacity-0 group-hover:opacity-5 transition-opacity duration-500 pointer-events-none <?php echo e($colors['card_bg']); ?>">
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>


        
        <?php if(session('success')): ?>
            <div x-data="{ show: true }" x-show="show" x-cloak class="relative z-[110]">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 p-8 text-center shadow-2xl transition-all sm:max-w-sm w-full border border-slate-100 dark:border-slate-800"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                            
                            <div class="absolute inset-0 pointer-events-none opacity-50">
                                <div class="absolute top-0 left-1/4 w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                                <div
                                    class="absolute top-10 right-1/4 w-3 h-3 bg-purple-500 rounded-full animate-ping delay-100">
                                </div>
                                <div class="absolute bottom-10 left-10 w-2 h-2 bg-blue-500 rounded-full animate-bounce">
                                </div>
                                <div class="absolute top-1/2 right-4 w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                            </div>

                            <div class="relative">
                                <div
                                    class="w-24 h-24 bg-gradient-to-tr from-emerald-400 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30 animate-in zoom-in duration-500">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>

                                <h3
                                    class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">
                                    Payment Received!
                                </h3>
                                <p class="text-base font-bold text-slate-500 dark:text-slate-400 mb-8 px-2 leading-relaxed">
                                    <?php echo e(session('success')); ?>

                                </p>

                                <button @click="show = false"
                                    class="w-full h-14 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-slate-900/20">
                                    Start New Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div x-data="{ 
                show: false, 
                message: '', 
                type: 'success',
                init() {
                    <?php if(session('success')): ?>
                        this.trigger('<?php echo e(session('success')); ?>', 'success');
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        this.trigger('<?php echo e(session('error')); ?>', 'error');
                    <?php endif; ?>
                },
                trigger(msg, type = 'success') {
                    this.message = msg;
                    this.type = type;
                    this.show = true;
                    setTimeout(() => { this.show = false }, 5000);
                }
            }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-[120] min-w-[320px] max-w-sm w-full px-4" x-cloak>

            <div :class="type === 'success' ? 'bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 border-white/10' : 'bg-red-500/90 text-white border-red-400/20'"
                class="px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border flex items-center gap-4">

                <div x-show="type === 'success'"
                    class="shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div x-show="type === 'error'"
                    class="shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-80"
                        x-text="type === 'success' ? 'Success' : 'Error'"></h4>
                    <p class="text-sm font-bold leading-tight mt-0.5" x-text="message"></p>
                </div>
            </div>
        </div>


        
        <div class="pt-12 md:pt-20 border-t border-slate-100 dark:border-slate-800 text-center space-y-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Managed by
                <?php echo e($reseller->store_name ?? $reseller->name); ?>

            </p>
            <p class="text-xs text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest">Powered by
                <?php echo e(config('app.name')); ?> Integration Engine
            </p>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\reseller\store\index.blade.php ENDPATH**/ ?>