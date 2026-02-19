<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quantum Security Audit - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

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

        .animate-spin-slow {
            animation: spin 8s linear infinite;
        }

        .animate-spin-reverse {
            animation: spin-reverse 12s linear infinite;
        }

        .animate-pulse-slow {
            animation: pulse 4s ease-in-out infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spin-reverse {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(0.95);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotateX(0deg);
            }

            50% {
                transform: translateY(-15px) rotateX(5deg);
            }
        }

        /* Scanning Line */
        .scan-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            box-shadow: 0 0 15px #3b82f6;
            animation: scan-move 2.5s ease-in-out infinite;
        }

        @keyframes scan-move {

            0%,
            100% {
                top: 0%;
                opacity: 0;
            }

            50% {
                top: 100%;
                opacity: 1;
            }
        }

        /* Neural Mesh nodes */
        .node {
            r: 1.5;
            fill: #3b82f6;
            opacity: 0.4;
        }

        .link {
            stroke: #3b82f6;
            stroke-width: 0.5;
            opacity: 0.1;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
    </style>
</head>

<body class="bg-black text-white antialiased selection:bg-primary/30">

    <!-- Neural Mesh Background -->
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <svg width="100%" height="100%" class="absolute inset-0">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(59, 130, 246, 0.1)" stroke-width="0.5" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />

            <!-- Animated Nodes -->
            <g class="nodes">
                <circle class="node" cx="10%" cy="20%">
                    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="3s" repeatCount="indefinite" />
                </circle>
                <circle class="node" cx="80%" cy="15%">
                    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="4s" repeatCount="indefinite" />
                </circle>
                <circle class="node" cx="45%" cy="85%">
                    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="5s" repeatCount="indefinite" />
                </circle>
                <circle class="node" cx="90%" cy="75%">
                    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="4s" repeatCount="indefinite" />
                </circle>
                <circle class="node" cx="15%" cy="65%">
                    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="3s" repeatCount="indefinite" />
                </circle>
            </g>
        </svg>
    </div>

    <!-- UI Core -->
    <div class="fixed inset-0 z-50 flex flex-col items-center justify-center p-6" x-data="{ 
            progress: 0, 
            status: 'INITIALIZING GATEWAY',
            logs: [],
            verified: false,
            
            async init() {
                const sequences = [
                    { p: 10, msg: 'BOOTSTRAPPING ENCLAVE...', t: 300 },
                    { p: 25, msg: 'ESTABLISHING QUANTUM LINK...', t: 700 },
                    { p: 38, msg: 'SYNCING BLOCKCHAIN LEDGER...', t: 1100 },
                    { p: 52, msg: 'COLLECTING ENTROPY...', t: 1500 },
                    { p: 68, msg: 'AUDITING SYSTEM PRIVILEGES...', t: 1900 },
                    { p: 85, msg: 'FINALIZING HANDSHAKE...', t: 2300 },
                    { p: 100, msg: 'DECRYPTION COMPLETE. WELCOME.', t: 2700 }
                ];

                for (const step of sequences) {
                    setTimeout(() => {
                        this.progress = step.p;
                        this.status = step.msg;
                        this.logs.unshift({ msg: step.msg, color: 'text-blue-400' });
                        if (step.p === 100) this.verified = true;
                    }, step.t);
                }

                // Add random technical jitter logs
                const jitters = [
                    'SIG: RSA-4096 VALIDATED',
                    'MEM: 0x4F92 -> 0x1A22 SYNC',
                    'NODE: GH-WEST-1 CONNECTED',
                    'IP: 192.168.0.1 TRUSTED',
                    'ENC: AES-256-GCM READY'
                ];

                jitters.forEach((j, i) => {
                    setTimeout(() => {
                        this.logs.unshift({ msg: j, color: 'text-slate-500' });
                    }, 500 + (i * 450));
                });

                setTimeout(() => {
                    window.location.href = '<?php echo e($redirect_to); ?>';
                }, 3200);
            }
         }" x-init="init()">

        <!-- 3D Security Core -->
        <div class="perspective-2000 mb-16">
            <div class="relative w-64 h-64 preserve-3d animate-float flex items-center justify-center">

                <!-- Outer Ring (3D Rotate Y) -->
                <div class="absolute inset-0 rounded-full border border-blue-500/20 preserve-3d animate-spin-slow"
                    style="transform: rotateX(70deg);"></div>

                <!-- Secondary Ring (3D Rotate X) -->
                <div class="absolute inset-0 rounded-full border border-indigo-500/20 preserve-3d animate-spin-reverse"
                    style="transform: rotateY(70deg);"></div>

                <!-- Glow Background -->
                <div class="absolute w-32 h-32 bg-blue-500/10 blur-[60px] rounded-full"></div>

                <!-- Core Hexagon Container -->
                <div class="relative z-10 w-32 h-32 flex items-center justify-center bg-blue-500/5 rounded-3xl border border-blue-500/20 shadow-[inset_0_0_20px_rgba(59,130,246,0.1)] backdrop-blur-md transition-all duration-700"
                    :class="verified ? 'scale-110 border-emerald-500/50 shadow-emerald-500/20' : ''">

                    <!-- Shield SVG -->
                    <svg class="w-16 h-16 transition-all duration-500"
                        :class="verified ? 'text-emerald-400 filter drop-shadow-[0_0_15px_#10b981]' : 'text-blue-500 opacity-80'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>

                    <!-- Scanning Bar -->
                    <div class="absolute inset-x-4 top-0 overflow-hidden rounded-full" x-show="!verified">
                        <div class="scan-line"></div>
                    </div>
                </div>

                <!-- Pulsing data points around the core -->
                <div class="absolute w-4 h-4 bg-blue-500/30 rounded-full blur-[2px] animate-pulse-slow"
                    style="top: 10%; right: 20%;"></div>
                <div class="absolute w-3 h-3 bg-indigo-500/30 rounded-full blur-[2px] animate-pulse-slow"
                    style="bottom: 15%; left: 25%; animation-delay: 1s;"></div>
            </div>
        </div>

        <!-- Meta Information & Progress -->
        <div class="w-full max-w-xl space-y-10 relative z-10">
            <div class="text-center">
                <h1 class="text-3xl font-black uppercase tracking-widest text-white mb-2"
                    x-text="verified ? 'AUTHENTICATED' : 'SECURITY AUDIT'"></h1>
                <div class="flex items-center justify-center gap-3">
                    <span class="w-8 h-[1px] bg-gradient-to-r from-transparent to-blue-500"></span>
                    <p class="text-[10px] font-mono font-bold text-blue-400 tracking-[0.4em] uppercase" x-text="status">
                    </p>
                    <span class="w-8 h-[1px] bg-gradient-to-l from-transparent to-blue-500"></span>
                </div>
            </div>

            <!-- Enhanced Progress Matrix -->
            <div class="relative p-1 glass-panel rounded-full overflow-hidden">
                <div class="h-2 rounded-full transition-all duration-500 ease-out shadow-[0_0_20px_rgba(59,130,246,0.4)]"
                    :class="verified ? 'bg-gradient-to-r from-emerald-600 to-teal-400' : 'bg-gradient-to-r from-blue-600 to-indigo-400'"
                    :style="{ width: progress + '%' }"></div>
            </div>

            <!-- Grid Terminal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    class="glass-panel rounded-2xl p-6 h-48 overflow-hidden font-mono text-[10px] flex flex-col gap-2 shadow-2xl">
                    <div class="flex justify-between items-center mb-2 opacity-50 border-b border-white/5 pb-2">
                        <span class="font-bold tracking-widest">SYSTEM_LOGS</span>
                        <span class="text-[8px] tracking-tighter">EST_0x82...</span>
                    </div>
                    <div class="flex-1 space-y-2 overflow-y-auto custom-scrollbar">
                        <template x-for="(log, index) in logs" :key="index">
                            <div class="flex gap-3" :class="index === 0 ? 'scale-105 origin-left' : 'opacity-40'">
                                <span class="text-primary font-bold">»</span>
                                <span :class="log.color" x-text="log.msg"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Status Matrix Table -->
                <div class="glass-panel rounded-2xl p-6 h-48 flex flex-col justify-between shadow-2xl">
                    <div class="flex justify-between items-center opacity-50 border-b border-white/5 pb-2">
                        <span class="font-bold tracking-widest text-[10px]">AUTH_MATRIX</span>
                        <div class="flex gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500/50"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500/50 animate-pulse"></div>
                        </div>
                    </div>
                    <div class="space-y-4 py-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] uppercase tracking-wider text-slate-500">Identity Scan</span>
                            <span class="text-[9px] font-bold text-blue-400"
                                x-text="progress > 30 ? 'VALID' : 'WAITING...'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] uppercase tracking-wider text-slate-500">Payload Integrity</span>
                            <span class="text-[9px] font-bold text-blue-400"
                                x-text="progress > 60 ? 'SECURE' : 'SCANNING...'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] uppercase tracking-wider text-slate-500">Access Token</span>
                            <span class="text-[9px] font-bold text-blue-400"
                                x-text="progress > 90 ? 'GRANTED' : 'VERIFYING...'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Corner Accents -->
        <div class="fixed top-10 left-10 p-4 border-l border-t border-blue-500/20 rounded-tl-3xl opacity-30">
            <p class="text-[8px] font-mono tracking-[0.3em] uppercase">Security Level: Platinum</p>
        </div>
        <div class="fixed bottom-10 right-10 p-4 border-r border-b border-blue-500/20 rounded-br-3xl opacity-30">
            <p class="text-[8px] font-mono tracking-[0.3em] uppercase text-right">Node ID: GH-A<?php echo e(rand(100, 999)); ?></p>
        </div>
    </div>
</body>

</html><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views/admin/verify.blade.php ENDPATH**/ ?>