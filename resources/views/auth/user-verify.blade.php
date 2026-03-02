<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Required - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #000;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .glow-blue {
            box-shadow: 0 0 50px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>

<body class="bg-black text-white antialiased min-h-screen flex items-center justify-center p-4 md:p-6">
    <!-- Neural Background -->
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <svg width="100%" height="100%" class="absolute inset-0">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="relative w-full max-w-2xl z-10" x-data="{ requested: {{ session('success') ? 'true' : 'false' }} }">
        <!-- Main Card -->
        <div class="glass rounded-3xl md:rounded-[3rem] p-6 sm:p-8 md:p-12 text-center relative overflow-hidden">
            <!-- Animated Shield Icon -->
            <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 md:w-32 md:h-32 mb-6 sm:mb-8 animate-float">
                <div class="relative w-full h-full flex items-center justify-center">
                    <div class="absolute inset-0 bg-blue-500/10 blur-[30px] md:blur-[40px] rounded-full"></div>
                    <div
                        class="relative z-10 w-full h-full glass rounded-2xl md:rounded-[2rem] border border-blue-500/20 flex items-center justify-center glow-blue">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 text-blue-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4 sm:space-y-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-black tracking-tighter uppercase mb-2">Access
                        Restricted</h1>
                    <p
                        class="text-blue-500 font-black uppercase tracking-[0.2em] sm:tracking-[0.3em] text-[8px] sm:text-[10px] md:text-xs">
                        Security Protocols Active</p>
                </div>

                <div class="max-w-md mx-auto">
                    <p class="text-slate-400 text-xs sm:text-sm md:text-base leading-relaxed">
                        Your account is currently in a <strong>Pending Verification</strong> state. To maintain the
                        security of our data ecosystem, all new accounts must be manually approved by our security team.
                    </p>
                </div>

                <!-- Status Indicator -->
                <div
                    class="inline-flex items-center gap-2 sm:gap-3 px-4 sm:px-6 py-2 sm:py-3 bg-white/5 rounded-xl sm:rounded-2xl border border-white/10">
                    <div class="relative flex h-2 w-2 sm:h-3 sm:w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 sm:h-3 sm:w-3 bg-amber-500"></span>
                    </div>
                    <span class="text-[9px] sm:text-xs font-black uppercase tracking-widest text-amber-500">Status:
                        Awaiting Approval</span>
                </div>

                <!-- Action Button -->
                <div class="pt-4 sm:pt-8">
                    <template x-if="!requested">
                        <form action="{{ route('verification.request') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-8 sm:px-12 h-12 sm:h-14 bg-blue-600 rounded-xl sm:rounded-2xl text-[8px] sm:text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:bg-blue-700 hover:scale-[1.02] active:scale-95 shadow-2xl shadow-blue-600/20">
                                Verify My Identity
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-2 sm:ml-3 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M14 5l7 7-7 7" />
                                </svg>
                            </button>
                        </form>
                    </template>
                    <template x-if="requested">
                        <div class="flex flex-col items-center gap-3 sm:gap-4">
                            <div
                                class="px-6 sm:px-8 py-3 sm:py-4 bg-emerald-500/10 text-emerald-500 rounded-xl sm:rounded-2xl border border-emerald-500/20 text-[9px] sm:text-xs font-black uppercase tracking-widest">
                                Request Received
                            </div>
                            <p class="text-slate-500 text-[8px] sm:text-[10px] font-medium tracking-wide">Expected
                                approval time: 2-4 hours</p>
                        </div>
                    </template>
                </div>

                <!-- Secondary Links -->
                <div class="pt-8 sm:pt-12 flex items-center justify-center gap-6 sm:gap-8">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-colors">Sign
                            Out</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Meta -->
        <div class="mt-6 sm:mt-8 flex justify-between items-center px-2 sm:px-4">
            <div class="flex items-center gap-1.5">
                <div class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-blue-500"></div>
                <span class="font-mono text-[8px] sm:text-[9px] text-slate-500 uppercase">Enclave Node Gh-1</span>
            </div>
            <span class="font-mono text-[8px] sm:text-[9px] text-slate-500">ID:
                {{ strtoupper(substr(auth()->user()->id, 0, 8)) }}</span>
        </div>
    </div>
</body>

</html>