<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="transition-colors duration-500">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Verification - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            overflow: hidden;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* 3D Scene Controls */
        .perspective-2000 {
            perspective: 2000px;
        }

        .preserve-3d {
            transform-style: preserve-3d;
        }

        /* Improved Bezier Spin Animations */
        .animate-spin-slow {
            animation: spin 12s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .animate-spin-reverse {
            animation: spin-reverse 16s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        .animate-pulse-slow {
            animation: pulse 4s ease-in-out infinite;
        }

        /* Smoother float with rotation */
        .animate-float {
            animation: float 8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotateX(0deg) rotateY(0deg);
            }
            50% {
                transform: translateY(-20px) rotateX(10deg) rotateY(5deg);
            }
        }

        /* Scanning Line */
        .scan-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            box-shadow: 0 0 20px #3b82f6;
            animation: scan-move 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        @keyframes scan-move {
            0% { top: -10%; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 110%; opacity: 0; }
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 10px;
        }

        /* Glass Panels */
        .glass-panel {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: all 0.4s ease;
        }

        /* Text Glow depending on theme */
        .dark .text-glow {
            text-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
        }
        .text-glow {
            text-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-[#0a0f1c] text-slate-800 dark:text-white antialiased selection:bg-primary/30 min-h-screen flex items-center justify-center transition-colors duration-500">

    <!-- Theme Toggle Helper (Floating action button for demoing light/dark mode easily) -->
    <div class="fixed top-4 right-4 z-[100]">
        <button onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');" class="p-2.5 rounded-full bg-white/40 dark:bg-black/40 backdrop-blur-md border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:scale-110 shadow-lg transition-all">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
    </div>

    <!-- Neural Mesh Background -->
    <div class="fixed inset-0 opacity-40 dark:opacity-20 pointer-events-none overflow-hidden transition-opacity duration-500">
        <svg width="100%" height="100%" class="absolute inset-0">
            <defs>
                <pattern id="tech-grid" width="60" height="60" patternUnits="userSpaceOnUse">
                    <path d="M 60 0 L 0 0 0 60" fill="none" stroke="currentColor" stroke-width="0.5" class="text-blue-500/20 dark:text-blue-400/20" />
                </pattern>
                <radialGradient id="mesh-glow-dark" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#0a0f1c" stop-opacity="0" />
                </radialGradient>
                <radialGradient id="mesh-glow-light" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.1" />
                    <stop offset="100%" stop-color="#f8fafc" stop-opacity="0" />
                </radialGradient>
            </defs>
            <rect width="100%" height="100%" fill="url(#tech-grid)" />
            
            <rect width="100%" height="100%" fill="url(#mesh-glow-light)" class="block dark:hidden" />
            <rect width="100%" height="100%" fill="url(#mesh-glow-dark)" class="hidden dark:block" />

            <!-- Dynamic Nodes -->
            <?php for($i = 0; $i < 15; $i++): ?>
                <circle cx="<?php echo e(rand(5, 95)); ?>%" cy="<?php echo e(rand(5, 95)); ?>%" r="1.5" fill="#3b82f6" opacity="0.6">
                    <animate attributeName="opacity" values="0.2;0.8;0.2" dur="<?php echo e(rand(3, 7)); ?>s" repeatCount="indefinite" />
                    <animate attributeName="r" values="1;2.5;1" dur="<?php echo e(rand(4, 9)); ?>s" repeatCount="indefinite" />
                </circle>
            <?php endfor; ?>
        </svg>
    </div>

    <!-- UI Container -->
    <div class="relative w-full max-w-7xl px-4 py-12 md:p-8 z-50 flex flex-col items-center justify-center min-h-screen"
        x-data="{ 
            progress: 0, 
            status: 'INITIALIZING GATEWAY',
            logs: [],
            verified: false,
            
            async init() {
                const sequences = [
                    { p: 8, msg: 'BOOTSTRAPPING ENCLAVE...', t: 300 },
                    { p: 22, msg: 'ESTABLISHING QUANTUM LINK...', t: 800 },
                    { p: 35, msg: 'SYNCING BLOCKCHAIN LEDGER...', t: 1300 },
                    { p: 48, msg: 'ENTROPY COLLECTION ACTIVE...', t: 1800 },
                    { p: 62, msg: 'SECURITY POLICY AUDIT...', t: 2300 },
                    { p: 80, msg: 'FINALIZING ENCRYPTION...', t: 2800 },
                    { p: 100, msg: 'DECRYPTION COMPLETE. WELCOME.', t: 3200 }
                ];

                for (const step of sequences) {
                    setTimeout(() => {
                        this.progress = step.p;
                        this.status = step.msg;
                        this.logs.unshift({ msg: step.msg, color: 'text-blue-600 dark:text-blue-400 font-bold' });
                        if (step.p === 100) this.verified = true;
                    }, step.t);
                }

                const techLogs = [
                    'RSA-4096: HANDSHAKE OK',
                    'MEM: SYNC 0x4F -> 0x1A',
                    'ENC: AES-256 INITIALIZED',
                    'NODE: GH-WEST-1 ACTIVE',
                    'GEO: TRACE SECURED',
                    'API: PULLING METADATA',
                    'AUTH: BCRYPT VALIDATED'
                ];

                techLogs.forEach((l, i) => {
                    setTimeout(() => {
                        this.logs.unshift({ msg: l, color: 'text-slate-400 dark:text-slate-500 text-[9px]' });
                    }, 400 + (i * 400));
                });

                setTimeout(() => {
                    window.location.href = '<?php echo e($redirect_to); ?>';
                }, 4000);
            }
         }" x-init="init()">

        <!-- 3D Security Core -->
        <div class="perspective-2000 mb-10 md:mb-16">
            <div class="relative w-48 h-48 md:w-64 md:h-64 preserve-3d animate-float flex items-center justify-center">

                <!-- Outer Rings (3D Effect) -->
                <div class="absolute inset-0 rounded-full border border-blue-500/30 dark:border-blue-500/10 preserve-3d animate-spin-slow"
                    style="transform: rotateX(70deg) rotateY(10deg);"></div>

                <div class="absolute inset-0 rounded-full border border-indigo-500/30 dark:border-indigo-500/10 preserve-3d animate-spin-reverse"
                    style="transform: rotateY(70deg) rotateX(10deg);"></div>

                <!-- Glow Background -->
                <div class="absolute w-24 h-24 md:w-32 md:h-32 bg-blue-500/20 dark:bg-blue-500/10 blur-[40px] md:blur-[60px] rounded-full transition-colors duration-500"
                    :class="verified ? 'bg-emerald-500/20 dark:bg-emerald-500/10' : ''">
                </div>

                <!-- Core Hexagon/Box Container -->
                <div class="relative z-10 w-28 h-28 md:w-36 md:h-36 flex items-center justify-center 
                            bg-white/50 dark:bg-blue-900/10 
                            rounded-[2rem] border border-blue-500/30 dark:border-blue-500/20 
                            shadow-[0_8px_32px_rgba(59,130,246,0.15)] dark:shadow-[inset_0_0_20px_rgba(59,130,246,0.1)] 
                            backdrop-blur-2xl transition-all duration-700"
                    :class="verified ? 'scale-110 !border-emerald-500/50 !shadow-[0_0_40px_rgba(16,185,129,0.3)] dark:!shadow-[inset_0_0_30px_rgba(16,185,129,0.2)]' : ''">

                    <!-- Shield SVG with Dynamic Color -->
                    <svg class="w-12 h-12 md:w-16 md:h-16 transition-all duration-500 drop-shadow-md dark:drop-shadow-none"
                        :class="verified ? 'text-emerald-500 dark:text-emerald-400 filter drop-shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'text-blue-600 dark:text-blue-500 opacity-90'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>

                    <!-- Scanning Bar Overlay -->
                    <div class="absolute inset-x-6 top-0 h-full overflow-hidden rounded-full pointer-events-none" x-show="!verified">
                        <div class="scan-line relative"></div>
                    </div>
                </div>

                <!-- Pulsing data points around the core -->
                <div class="absolute w-3 h-3 bg-blue-500/60 dark:bg-blue-500/40 rounded-full blur-[1px] dark:blur-[2px] animate-pulse"
                    style="top: 15%; right: 15%;"></div>
                <div class="absolute w-2 h-2 bg-indigo-500/60 dark:bg-indigo-500/40 rounded-full blur-[1px] dark:blur-[2px] animate-pulse"
                    style="bottom: 20%; left: 20%; animation-delay: 1.5s;"></div>
            </div>
        </div>

        <!-- Meta Information & Progress -->
        <div class="w-full max-w-3xl space-y-10 md:space-y-12 relative z-10">
            <div class="text-center px-4">
                <h1 class="text-2xl md:text-4xl font-black uppercase tracking-[0.3em] text-slate-800 dark:text-white mb-3 text-glow transition-colors duration-500"
                    x-text="verified ? 'AUTHENTICATED' : 'SECURITY AUDIT'"></h1>
                <div class="flex items-center justify-center gap-4">
                    <span class="hidden sm:block w-12 h-[1px] bg-gradient-to-r from-transparent to-blue-500/30 dark:to-blue-500/50"></span>
                    <p class="text-[9px] md:text-[10px] font-mono font-bold text-blue-600 dark:text-blue-400 tracking-[0.4em] uppercase"
                        x-text="status"></p>
                    <span class="hidden sm:block w-12 h-[1px] bg-gradient-to-l from-transparent to-blue-500/30 dark:to-blue-500/50"></span>
                </div>
            </div>

            <!-- Enhanced Progress Bar -->
            <div class="px-6 md:px-0">
                <div class="relative p-1 bg-white/60 dark:bg-white/5 rounded-full overflow-hidden border border-slate-200 dark:border-white/10 shadow-[0_4px_15px_rgba(0,0,0,0.05)] dark:shadow-2xl">
                    <div class="h-2 rounded-full transition-all duration-500 ease-out shadow-[0_0_15px_rgba(59,130,246,0.4)] dark:shadow-[0_0_20px_rgba(59,130,246,0.6)]"
                        :class="verified ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400'"
                        :style="{ width: progress + '%' }"></div>
                </div>
                <div class="flex justify-between mt-3 px-1">
                    <span class="text-[8px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Verification Progress</span>
                    <span class="text-[8px] font-mono font-bold text-blue-600 dark:text-blue-400" x-text="progress + '%'"></span>
                </div>
            </div>

            <!-- Multi-Panel Layout (Responsive Stack/Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 container mx-auto">
                <!-- System Logs Panel -->
                <div class="glass-panel bg-white/60 dark:bg-black/20 border border-slate-200 dark:border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.04)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.5)] rounded-[2rem] p-6 h-48 md:h-56 overflow-hidden font-mono text-[10px] flex flex-col gap-2 group hover:border-blue-400/30 dark:hover:border-blue-500/20">
                    <div class="flex justify-between items-center mb-3 opacity-60 dark:opacity-50 border-b border-slate-200 dark:border-white/10 pb-2">
                        <span class="font-bold tracking-widest flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            SYSTEM_LOGS
                        </span>
                        <span class="text-[8px] tracking-tighter text-slate-500">SEC_MOD_v4.2</span>
                    </div>
                    <div class="flex-1 space-y-2 overflow-y-auto custom-scrollbar pr-2">
                        <template x-for="(log, index) in logs" :key="index">
                            <div class="flex gap-3 transition-opacity duration-300"
                                :class="index === 0 ? 'text-blue-600 dark:text-blue-400' : 'opacity-60 dark:opacity-40 text-slate-700 dark:text-slate-300'">
                                <span class="font-bold">»</span>
                                <span :class="log.color" x-text="log.msg"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Security Matrix Panel -->
                <div class="glass-panel bg-white/60 dark:bg-black/20 border border-slate-200 dark:border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.04)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.5)] rounded-[2rem] p-6 h-48 md:h-56 flex flex-col justify-between group hover:border-blue-400/30 dark:hover:border-blue-500/20">
                    <div class="flex justify-between items-center opacity-60 dark:opacity-50 border-b border-slate-200 dark:border-white/10 pb-2">
                        <span class="font-bold tracking-widest text-[10px] text-slate-700 dark:text-slate-300">AUTH_MATRIX</span>
                        <div class="flex gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500/30 dark:bg-blue-500/20"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500/50 dark:bg-blue-500/40"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                        </div>
                    </div>
                    <div class="space-y-4 py-3">
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] uppercase tracking-wider text-slate-500">Identity Protocol</span>
                                <span class="text-[7px] text-slate-600 dark:text-slate-400 font-mono tracking-tighter"
                                    x-text="progress > 15 ? 'SHA-256 VALIDATED' : 'WAITING...'"></span>
                            </div>
                            <span class="text-[9px] font-bold px-3 py-1 rounded-lg bg-blue-500/10 dark:bg-blue-500/5"
                                :class="progress > 30 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600'"
                                x-text="progress > 30 ? 'PASSED' : 'SCAN'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] uppercase tracking-wider text-slate-500">Node Syncing</span>
                                <span class="text-[7px] text-slate-600 dark:text-slate-400 font-mono tracking-tighter text-right"
                                    x-text="progress > 55 ? 'NODES_SYNCHRONIZED' : 'PENDING...'"></span>
                            </div>
                            <span class="text-[9px] font-bold px-3 py-1 rounded-lg bg-blue-500/10 dark:bg-blue-500/5"
                                :class="progress > 60 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600'"
                                x-text="progress > 60 ? 'PASSED' : 'SYNC'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] uppercase tracking-wider text-slate-500">Privilege Escalation</span>
                                <span class="text-[7px] text-slate-600 dark:text-slate-400 font-mono tracking-tighter"
                                    x-text="progress > 85 ? 'TOKEN_PROVISIONED' : 'AUTHORIZING...'"></span>
                            </div>
                            <span class="text-[9px] font-bold px-3 py-1 rounded-lg bg-blue-500/10 dark:bg-blue-500/5"
                                :class="progress > 90 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600'"
                                x-text="progress > 90 ? 'APPROVED' : 'AUTH'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adaptive Corner Accents -->
        <div class="fixed top-6 left-6 md:top-10 md:left-10 p-3 md:p-4 border-l border-t border-blue-500/30 dark:border-blue-500/20 rounded-tl-3xl opacity-40 dark:opacity-30 select-none hidden sm:block">
            <p class="text-[7px] md:text-[8px] font-mono tracking-[0.3em] uppercase text-slate-600 dark:text-slate-400">Auth Level: Level-5 Clear</p>
        </div>
        <div class="fixed bottom-6 right-6 md:bottom-10 md:right-10 p-3 md:p-4 border-r border-b border-blue-500/30 dark:border-blue-500/20 rounded-br-3xl opacity-40 dark:opacity-30 select-none hidden sm:block text-right">
            <p class="text-[7px] md:text-[8px] font-mono tracking-[0.3em] uppercase text-slate-600 dark:text-slate-400">Node: GH-0X<?php echo e(rand(100, 999)); ?></p>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/admin/verify.blade.php ENDPATH**/ ?>