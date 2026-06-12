<?php $__env->startSection('title', 'Ghana\'s Fast Data Vending Hub'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* ── Poppins site-wide override ── */
    body, .font-sans { font-family: 'Poppins', sans-serif; }

    /* ── Dark space hero background ── */
    .hero-space-bg {
        background: linear-gradient(135deg, #0f0c29 0%, #1a1060 30%, #0d1b4b 60%, #080e2a 100%);
        position: relative;
        overflow: hidden;
    }

    /* ── Starfield canvas ── */
    #starfield { position: absolute; inset: 0; z-index: 0; }

    /* ── Rocket float ── */
    @keyframes rocket-float {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-20px); }
    }
    @keyframes flame-flicker {
        0%, 100% { transform: scaleY(1) scaleX(1);    opacity: 1; }
        25%       { transform: scaleY(1.2) scaleX(.85); opacity: .9; }
        50%       { transform: scaleY(.88) scaleX(1.1); opacity: 1; }
        75%       { transform: scaleY(1.1) scaleX(.92); opacity: .85; }
    }
    @keyframes flame-mid-flicker {
        0%, 100% { transform: scaleY(1);   opacity: 1; }
        33%       { transform: scaleY(1.25); opacity: .8; }
        66%       { transform: scaleY(.82); opacity: 1; }
    }
    @keyframes particle-rise {
        0%   { transform: translateY(0)    scale(1); opacity: .85; }
        100% { transform: translateY(-90px) scale(0); opacity: 0; }
    }
    @keyframes stat-float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-7px); }
    }
    @keyframes glow-pulse {
        0%, 100% { opacity: .3; transform: scale(1); }
        50%       { opacity: .65; transform: scale(1.04); }
    }
    @keyframes earth-spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    .rocket-wrap   { animation: rocket-float 4s ease-in-out infinite; }
    .flame-outer   { animation: flame-flicker     .18s ease-in-out infinite; transform-origin: 50% 0; }
    .flame-mid     { animation: flame-mid-flicker  .22s ease-in-out infinite; transform-origin: 50% 0; }
    .flame-inner   { animation: flame-mid-flicker  .14s ease-in-out infinite .05s; transform-origin: 50% 0; }
    .ptcl          { animation: particle-rise linear infinite; transform-origin: center bottom; }
    .stat-1        { animation: stat-float 3.2s ease-in-out infinite; }
    .stat-2        { animation: stat-float 2.8s ease-in-out infinite .6s; }
    .stat-3        { animation: stat-float 3.6s ease-in-out infinite 1.1s; }
    .stat-4        { animation: stat-float 3.0s ease-in-out infinite .4s; }
    .glow-halo     { animation: glow-pulse 3s ease-in-out infinite; }
    .earth-ring    { animation: earth-spin 60s linear infinite; transform-origin: 50% 50%; }

    /* ── Hero gradient underline on keyword ── */
    .kw { color: #4f8cff; }

    /* ── Dotted connector lines ── */
    .connector-line {
        position: absolute;
        border: 1px dashed rgba(99,150,255,.25);
        pointer-events: none;
        z-index: 1;
    }

    /* ── Pricing / other section theming ── */
    .section-light { background: #f8f9ff; }
    .section-white { background: #ffffff; }

    /* ── Shimmer & Glow Badge ── */
    @keyframes shimmer {
        0% { transform: translateX(-150%) skewX(-25deg); }
        100% { transform: translateX(150%) skewX(-25deg); }
    }
    @keyframes badge-glow {
        0%, 100% { box-shadow: 0 0 8px rgba(16,185,129,0.15), inset 0 0 4px rgba(16,185,129,0.05); }
        50%       { box-shadow: 0 0 16px rgba(16,185,129,0.35), inset 0 0 8px rgba(16,185,129,0.15); }
    }
    .shimmer-badge {
        position: relative;
        overflow: hidden;
        animation: badge-glow 3s ease-in-out infinite;
    }
    .shimmer-badge::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(16,185,129,0.25), transparent);
        transform: translateX(-150%) skewX(-25deg);
        animation: shimmer 4.5s infinite ease-in-out;
        pointer-events: none;
    }
</style>


<div class="hero-space-bg w-full min-h-screen flex flex-col" style="padding-top: 80px;">

    <canvas id="starfield"></canvas>

    
    <div class="absolute bottom-[-500px] md:bottom-[-700px] left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] md:w-[1400px] md:h-[1400px] rounded-full pointer-events-none z-[1]" style="
        background: radial-gradient(circle at 50% 0%, #1e3a8a 0%, #0d153b 50%, #070a1e 100%);
        box-shadow: inset 0 30px 100px rgba(255,255,255,0.15),
                    inset 0 10px 40px rgba(79,140,255,0.25),
                    0 0 120px 30px rgba(59,130,246,0.3);
    ">
        
        <div class="absolute inset-0 rounded-full" style="
            background: radial-gradient(circle at 50% 0%, rgba(99,150,255,0.35) 0%, rgba(59,130,246,0.15) 40%, transparent 70%);
            filter: blur(15px);
        "></div>
        
        
        <div class="absolute inset-0 w-full h-full opacity-35 earth-ring">
            <svg class="w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
                
                <path d="M350,150 C420,120 480,180 500,220 C520,260 480,320 450,340 C420,360 380,320 330,350 C280,380 250,300 280,260 C310,220 280,180 350,150 Z" fill="#2563eb" opacity="0.5" filter="blur(2px)" />
                <path d="M650,250 C700,200 780,220 800,280 C820,340 760,380 720,400 C680,420 630,380 600,420 C570,460 520,410 550,360 C580,310 600,300 650,250 Z" fill="#1d4ed8" opacity="0.4" filter="blur(2px)" />
                <path d="M200,450 C250,420 300,460 320,500 C340,540 310,580 280,600 C250,620 220,580 180,610 C140,640 120,580 140,540 C160,500 150,480 200,450 Z" fill="#2563eb" opacity="0.4" filter="blur(2px)" />
                <path d="M450,550 C500,520 520,580 550,600 C580,620 600,680 570,720 C540,760 480,720 450,750 C420,780 400,720 420,680 C440,640 400,600 450,550 Z" fill="#1e40af" opacity="0.4" filter="blur(2px)" />

                
                <g stroke="#60a5fa" stroke-width="1.5" opacity="0.7">
                    <line x1="380" y1="200" x2="480" y2="240" stroke-dasharray="4,4" />
                    <line x1="480" y1="240" x2="630" y2="300" stroke-dasharray="4,4" />
                    <line x1="330" y1="330" x2="280" y2="500" stroke-dasharray="4,4" />
                    <line x1="630" y1="300" x2="720" y2="350" stroke-dasharray="4,4" />
                    <line x1="450" y1="580" x2="570" y2="700" stroke-dasharray="4,4" />
                    <line x1="280" y1="500" x2="450" y2="580" stroke-dasharray="4,4" />
                </g>
                <g fill="#93c5fd">
                    <circle cx="380" cy="200" r="5" class="animate-pulse" />
                    <circle cx="480" cy="240" r="6" />
                    <circle cx="630" cy="300" r="5" />
                    <circle cx="280" cy="500" r="6" class="animate-pulse" />
                    <circle cx="720" cy="350" r="5" />
                    <circle cx="450" cy="580" r="6" />
                    <circle cx="570" cy="700" r="5" class="animate-pulse" />
                </g>
            </svg>
        </div>
    </div>

    <div class="relative z-10 flex-1 flex items-center">
        <div class="w-full max-w-[1400px] mx-auto px-6 md:px-12 lg:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center min-h-[calc(100vh-80px)] py-10 lg:py-16">

                
                <div class="space-y-6 lg:space-y-8 text-center lg:text-left">

                    
                    <div class="shimmer-badge inline-flex items-center gap-2.5 px-4 py-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 text-xs font-semibold tracking-wide backdrop-blur-md mx-auto lg:mx-0">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span>Live 24/7 Data Vending</span>
                    </div>

                    
                    <div class="space-y-3">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.1] tracking-tight">
                            Data bundles<br>
                            that <span class="kw">launch</span> instantly.
                        </h1>
                        <p class="text-sm sm:text-[1rem] text-slate-300 leading-relaxed max-w-[500px] mx-auto lg:mx-0">
                            Get the cheapest high-speed data bundles for MTN, Telecel, and AirtelTigo. Instantly processed and delivered to your phone in seconds.
                        </p>
                    </div>

                    
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                        <a href="<?php echo e(url('/register')); ?>"
                           class="h-11 sm:h-12 px-6 sm:px-8 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold transition-all shadow-lg shadow-indigo-700/40 flex items-center gap-2 active:scale-95">
                            Explore Data
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="#pricing"
                           class="h-11 sm:h-12 px-6 sm:px-8 rounded-full border border-white/20 bg-white/5 hover:bg-white/10 text-white text-sm font-semibold transition-all flex items-center gap-2 active:scale-95">
                            See Platform
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-5 pt-4 border-t border-white/10">
                        <div>
                            <div class="flex text-yellow-400 text-sm">★★★★★★</div>
                            <p class="text-xs text-slate-400 font-medium">50k+ Happy Data Buyers</p>
                        </div>
                        <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
                        <div class="hidden sm:block">
                            <p class="text-lg font-bold text-white leading-none">4.9/5</p>
                            <p class="text-xs text-slate-400">Average Rating</p>
                        </div>
                    </div>

                </div>

                
                <div class="hidden lg:flex lg:order-2 relative items-center justify-center" style="min-height: 560px;">

                    
                    <div class="glow-halo absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-72 h-72 rounded-full" style="background: radial-gradient(circle, rgba(79,140,255,.22) 0%, rgba(100,80,255,.12) 50%, transparent 70%);"></div>
                    </div>

                    
                    <div class="connector-line" style="top:22%; left:18%; width:90px; transform: rotate(-20deg);"></div>
                    <div class="connector-line" style="top:18%; right:14%; width:80px; transform: rotate(18deg);"></div>
                    <div class="connector-line" style="bottom:22%; left:20%; width:75px; transform: rotate(15deg);"></div>
                    <div class="connector-line" style="bottom:18%; right:16%; width:85px; transform: rotate(-15deg);"></div>

                    
                    <div class="stat-1 absolute z-20" style="top: 5%; left: 2%;">
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 px-4 py-3.5 shadow-2xl min-w-[150px]">
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mb-2">Data Quality</p>
                            <div class="flex items-center gap-3">
                                <svg width="42" height="42" viewBox="0 0 42 42">
                                    <circle cx="21" cy="21" r="16" fill="none" stroke="rgba(255,255,255,.1)" stroke-width="4"/>
                                    <circle cx="21" cy="21" r="16" fill="none" stroke="#4f8cff" stroke-width="4"
                                            stroke-dasharray="100.53" stroke-dashoffset="12" stroke-linecap="round"
                                            transform="rotate(-90 21 21)"/>
                                </svg>
                                <div>
                                    <p class="text-base font-bold text-white leading-none">98.7%</p>
                                    <p class="text-[9px] text-slate-400">Accuracy</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="stat-2 absolute z-20" style="top: 2%; right: 2%;">
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 px-4 py-3.5 shadow-2xl min-w-[140px]">
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Data Delivered</p>
                            <p class="text-2xl font-bold text-white leading-none">5000 GB</p>
                            <p class="text-[9px] text-emerald-400 font-semibold mt-1">↑ 98%</p>
                            <div class="flex items-end gap-0.5 mt-2 h-6">
                                <div class="w-2 h-2 bg-indigo-400/40 rounded-sm"></div>
                                <div class="w-2 h-3 bg-indigo-400/50 rounded-sm"></div>
                                <div class="w-2 h-2.5 bg-indigo-400/50 rounded-sm"></div>
                                <div class="w-2 h-4 bg-indigo-400/70 rounded-sm"></div>
                                <div class="w-2 h-3.5 bg-indigo-500/80 rounded-sm"></div>
                                <div class="w-2 h-6 bg-indigo-500 rounded-sm"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="stat-3 absolute z-20" style="bottom: 8%; left: 2%;">
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 px-4 py-3.5 shadow-2xl min-w-[145px]">
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Data Points Processed</p>
                            <p class="text-2xl font-bold text-white leading-none">4.4B</p>
                            <p class="text-[9px] text-emerald-400 font-semibold mt-1">↑ 38.5%</p>
                            <svg class="mt-2" width="100" height="22" viewBox="0 0 100 22">
                                <defs>
                                    <linearGradient id="sg" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="#4f8cff"/>
                                        <stop offset="100%" stop-color="#4f8cff" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <polyline points="0,20 17,15 33,18 50,9 67,13 83,5 100,2"
                                          fill="none" stroke="#4f8cff" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                <polygon points="0,20 17,15 33,18 50,9 67,13 83,5 100,2 100,22 0,22"
                                         fill="url(#sg)" opacity="0.2"/>
                            </svg>
                        </div>
                    </div>

                    
                    <div class="stat-4 absolute z-20" style="bottom: 5%; right: 2%;">
                        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 px-4 py-3.5 shadow-2xl min-w-[130px]">
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mb-1">System Uptime</p>
                            <p class="text-2xl font-bold text-white leading-none">99.99%</p>
                            <div class="w-full h-1 bg-white/10 rounded-full mt-2.5 overflow-hidden">
                                <div class="h-full bg-indigo-400 rounded-full" style="width:99.99%"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="rocket-wrap relative z-10" style="width:220px; height:420px;">
                        <svg viewBox="0 0 220 420" width="220" height="420" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="rkt-body" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%"   stop-color="#b8c5e8"/>
                                    <stop offset="45%"  stop-color="#eef1f8"/>
                                    <stop offset="100%" stop-color="#9aabda"/>
                                </linearGradient>
                                <linearGradient id="rkt-nose" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#4f8cff"/>
                                    <stop offset="100%" stop-color="#b8c5e8"/>
                                </linearGradient>
                                <linearGradient id="rkt-fin" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%"   stop-color="#3b6de0"/>
                                    <stop offset="100%" stop-color="#1e3a8a"/>
                                </linearGradient>
                                <linearGradient id="fl-outer" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#fde68a"/>
                                    <stop offset="45%"  stop-color="#f59e0b"/>
                                    <stop offset="100%" stop-color="transparent"/>
                                </linearGradient>
                                <linearGradient id="fl-mid" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#fff7ed"/>
                                    <stop offset="55%"  stop-color="#f97316"/>
                                    <stop offset="100%" stop-color="transparent"/>
                                </linearGradient>
                                <linearGradient id="fl-inner" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#ffffff"/>
                                    <stop offset="65%"  stop-color="#fde68a"/>
                                    <stop offset="100%" stop-color="transparent"/>
                                </linearGradient>
                                <filter id="rkt-glow">
                                    <feGaussianBlur stdDeviation="4" result="b"/>
                                    <feComposite in="SourceGraphic" in2="b" operator="over"/>
                                </filter>
                            </defs>

                            
                            <ellipse cx="110" cy="395" rx="70" ry="15" fill="rgba(255,150,30,.15)"/>
                            <ellipse cx="110" cy="395" rx="40" ry="8"  fill="rgba(255,180,60,.20)"/>

                            
                            <polygon points="70,285 45,340 72,318" fill="url(#rkt-fin)" opacity=".92"/>
                            <polygon points="71,285 51,330 72,312" fill="#5b8af5" opacity=".35"/>

                            
                            <polygon points="150,285 175,340 148,318" fill="url(#rkt-fin)" opacity=".92"/>
                            <polygon points="149,285 169,330 148,312" fill="#5b8af5" opacity=".35"/>

                            
                            <rect x="72" y="145" width="76" height="165" rx="8" fill="url(#rkt-body)"/>
                            
                            <rect x="72" y="215" width="76" height="16" rx="0" fill="#4f8cff" opacity=".2"/>
                            
                            <text x="110" y="188" font-family="Poppins,sans-serif" font-size="7.5" font-weight="700"
                                  fill="#1e3a8a" text-anchor="middle" letter-spacing="2.5"
                                  transform="rotate(-90 110 188)">ROCKET DATA</text>

                            
                            <path d="M72,145 Q72,60 110,35 Q148,60 148,145 Z" fill="url(#rkt-nose)"/>
                            
                            <path d="M84,138 Q86,82 110,58 Q113,82 109,138 Z" fill="white" opacity=".22"/>

                            
                            <circle cx="110" cy="173" r="18" fill="#1e2a5e" stroke="#8fb3ff" stroke-width="3.5"/>
                            <circle cx="110" cy="173" r="13" fill="#172050"/>
                            <circle cx="104" cy="168" r="4"  fill="white" opacity=".35"/>
                            <circle cx="110" cy="173" r="18" fill="none" stroke="#4f8cff" stroke-width="1" opacity=".7"/>

                            
                            <rect x="88" y="305" width="44" height="12" rx="4" fill="#1e3a8a"/>
                            <rect x="93" y="311" width="34" height="6"  rx="3" fill="#3b6de0" opacity=".6"/>

                            
                            <g class="flame-outer" style="transform-origin: 110px 317px;">
                                <ellipse cx="110" cy="352" rx="27" ry="52" fill="url(#fl-outer)" opacity=".85" filter="url(#rkt-glow)"/>
                            </g>
                            
                            <g class="flame-mid" style="transform-origin: 110px 317px;">
                                <ellipse cx="110" cy="348" rx="17" ry="40" fill="url(#fl-mid)" opacity=".9"/>
                            </g>
                            
                            <g class="flame-inner" style="transform-origin: 110px 317px;">
                                <ellipse cx="110" cy="336" rx="8"  ry="22" fill="url(#fl-inner)"/>
                            </g>

                            
                            <circle class="ptcl" cx="96"  cy="360" r="3.5" fill="#f59e0b" opacity=".75" style="animation-duration:1.1s;animation-delay:0s;"/>
                            <circle class="ptcl" cx="116" cy="375" r="3"   fill="#fde68a" opacity=".65" style="animation-duration:.9s; animation-delay:.15s;"/>
                            <circle class="ptcl" cx="124" cy="355" r="2.5" fill="#fb923c" opacity=".55" style="animation-duration:1.3s;animation-delay:.3s;"/>
                            <circle class="ptcl" cx="102" cy="368" r="2.5" fill="#f97316" opacity=".70" style="animation-duration:.75s;animation-delay:.5s;"/>
                            <circle class="ptcl" cx="110" cy="380" r="4"   fill="#fef3c7" opacity=".45" style="animation-duration:1.0s;animation-delay:.22s;"/>
                            <circle class="ptcl" cx="88"  cy="370" r="2"   fill="#f59e0b" opacity=".60" style="animation-duration:.85s;animation-delay:.4s;"/>
                        </svg>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
(function(){
    const c = document.getElementById('starfield');
    const ctx = c.getContext('2d');
    function resize(){ c.width = c.offsetWidth; c.height = c.offsetHeight; }
    resize();
    window.addEventListener('resize', resize);
    const stars = Array.from({length:180}, () => ({
        x: Math.random(), y: Math.random(),
        r: Math.random()*.9+.3,
        a: Math.random(),
        s: Math.random()*.0005+.0002
    }));
    function draw(){
        ctx.clearRect(0,0,c.width,c.height);
        stars.forEach(s=>{
            s.a = (s.a + s.s) % (Math.PI*2);
            const op = .3 + .5*Math.abs(Math.sin(s.a));
            ctx.beginPath();
            ctx.arc(s.x*c.width, s.y*c.height, s.r, 0, Math.PI*2);
            ctx.fillStyle = `rgba(200,220,255,${op})`;
            ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
})();
</script>


<section id="pricing" class="py-24 section-light" x-data="{ activeNetwork: 'ALL' }">
    <div class="container mx-auto px-4 md:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <h2 class="text-2xl md:text-4xl font-semibold tracking-tight text-slate-900">Interactive Bundle Deck</h2>
            <p class="text-slate-500">Select a telecom network to filter available high-speed bundle cards instantly.</p>

            <div class="flex flex-wrap justify-center items-center gap-3 pt-6">
                <button @click="activeNetwork = 'ALL'"
                    :class="activeNetwork === 'ALL' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white hover:bg-slate-50 text-slate-600'"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold transition-all border border-slate-200">All Networks</button>
                <button @click="activeNetwork = 'MTN'"
                    :class="activeNetwork === 'MTN' ? 'bg-yellow-400 text-yellow-950 shadow-md' : 'bg-white hover:bg-slate-50 text-slate-600'"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold transition-all border border-slate-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 border border-yellow-500"></span>MTN
                </button>
                <button @click="activeNetwork = 'TELECEL'"
                    :class="activeNetwork === 'TELECEL' ? 'bg-red-600 text-white shadow-md' : 'bg-white hover:bg-slate-50 text-slate-600'"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold transition-all border border-slate-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>Telecel
                </button>
                <button @click="activeNetwork = 'AIRTELTIGO'"
                    :class="activeNetwork === 'AIRTELTIGO' ? 'bg-blue-600 text-white shadow-md' : 'bg-white hover:bg-slate-50 text-slate-600'"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold transition-all border border-slate-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>AirtelTigo
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $bundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $net = strtoupper($product->network);
                    if ($net === 'AT') $net = 'AIRTELTIGO';
                    $netMap = [
                        'MTN'        => ['bg'=>'bg-yellow-400','text'=>'text-yellow-950'],
                        'TELECEL'    => ['bg'=>'bg-red-600',   'text'=>'text-white'],
                        'AIRTELTIGO' => ['bg'=>'bg-blue-600',  'text'=>'text-white'],
                    ];
                    $th = $netMap[$net] ?? ['bg'=>'bg-slate-800','text'=>'text-white'];
                ?>
                <div x-show="activeNetwork === 'ALL' || activeNetwork === '<?php echo e($net); ?>'"
                     class="group bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col">
                    <div class="p-6 space-y-5 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase <?php echo e($th['bg']); ?> <?php echo e($th['text']); ?>"><?php echo e($product->network); ?></span>
                            <span class="px-3 py-1 rounded-lg text-[10px] font-semibold bg-slate-50 border border-slate-100 text-slate-500"><?php echo e($product->validity ?? 'No Expiry'); ?></span>
                        </div>
                        <div class="space-y-1.5">
                            <p class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Data Package</p>
                            <h3 class="text-xl font-bold text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors"><?php echo e($product->name); ?></h3>
                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mt-1"><?php echo e($product->data_amount); ?></div>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">Price</p>
                            <p class="text-2xl font-bold text-slate-900 tracking-tight">₵<?php echo e(number_format($product->price, 2)); ?></p>
                        </div>
                        <a href="<?php echo e(url('/login?redirect=purchase&bundle=' . $product->id)); ?>"
                           class="h-10 px-5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition-all shadow-sm flex items-center gap-1.5 group/btn">
                            Buy Now
                            <svg class="w-3 h-3 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-16 text-center">
                    <p class="text-sm font-semibold text-slate-500">No Packages Available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>



<section id="features" class="py-24 section-white border-t border-slate-100">
    <div class="container mx-auto px-4 md:px-8">
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
            <h2 class="text-2xl md:text-4xl font-semibold tracking-tight text-slate-900">Engineered to scale</h2>
            <p class="text-slate-500">Everything you need to buy, distribute, resell, or develop high-speed automated data products in Ghana.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Fulfillment API Engine','description' => 'Send request, get success. Our automated system queries operators and executes top-ups the instant payment verifies.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fulfillment API Engine','description' => 'Send request, get success. Our automated system queries operators and executes top-ups the instant payment verifies.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Custom Storefronts','description' => 'Become a reseller. Launch a white-labeled web application storefront and start accepting client data orders under your brand.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Custom Storefronts','description' => 'Become a reseller. Launch a white-labeled web application storefront and start accepting client data orders under your brand.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Developer Infrastructure','description' => 'Integrate data refills into your custom apps, ERP systems, or bot platforms using our RESTful API keys and webhooks.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Developer Infrastructure','description' => 'Integrate data refills into your custom apps, ERP systems, or bot platforms using our RESTful API keys and webhooks.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Prefix Validation Guard','description' => 'Stop losing money to invalid numbers. Our prefix validator automatically verifies networks before running API requests.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Prefix Validation Guard','description' => 'Stop losing money to invalid numbers. Our prefix validator automatically verifies networks before running API requests.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04L3 14.535a45.24 45.24 0 0012 8.192 45.24 45.24 0 0012-8.192l-.382-8.51z"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Analytics Suite','description' => 'Visualize wallet expenditures, average order execution speeds, and reseller margin reports via real-time charts.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Analytics Suite','description' => 'Visualize wallet expenditures, average order execution speeds, and reseller margin reports via real-time charts.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.feature-card','data' => ['title' => 'Paystack Wallet Top-ups','description' => 'Instant automated credit. Fund your user wallet securely via Paystack with cards, mobile money, or bank transfers.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('feature-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Paystack Wallet Top-ups','description' => 'Instant automated credit. Fund your user wallet securely via Paystack with cards, mobile money, or bank transfers.']); ?>
                 <?php $__env->slot('icon', null, []); ?> <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $attributes = $__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__attributesOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8)): ?>
<?php $component = $__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8; ?>
<?php unset($__componentOriginal8a1da09f823c4dc4ebcb3f0fdc9afbe8); ?>
<?php endif; ?>
        </div>
    </div>
</section>



<section class="py-24" style="background:#f3f4ff;">
    <div class="container mx-auto px-4 md:px-8">
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
            <h2 class="text-2xl md:text-4xl font-semibold tracking-tight text-slate-900">Seamless Setup</h2>
            <p class="text-slate-500">Start processing automated network bundles in three simple steps.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-indigo-600/20">1</div>
                <h3 class="text-lg font-semibold text-slate-900">Register Account</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Sign up instantly. Access your developer dashboard, wallet management tools, and storefront settings immediately.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-purple-600/20">2</div>
                <h3 class="text-lg font-semibold text-slate-900">Fund Wallet</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Add funds to your secure escrow wallet using Mobile Money or bank cards powered by Paystack.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-pink-600/20">3</div>
                <h3 class="text-lg font-semibold text-slate-900">Deploy &amp; Vend</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Initiate data purchases instantly, configure your public storefront, or grab your developer API keys to automate.</p>
            </div>
        </div>
    </div>
</section>



<section class="py-24 section-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-white pointer-events-none"></div>
    <div class="container mx-auto px-4 md:px-8 relative z-10 text-center max-w-3xl space-y-8">
        <h2 class="text-3xl md:text-5xl font-semibold tracking-tight text-slate-900 leading-tight">
            Ready to accelerate<br>your data business?
        </h2>
        <p class="text-slate-500 text-base md:text-lg max-w-xl mx-auto">
            Join developers and data resellers across Ghana. Integrate our API, launch your storefront, or buy instant data bundles at unmatched rates.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo e(url('/register')); ?>"
               class="h-14 px-10 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xl shadow-indigo-600/30 w-full sm:w-auto flex items-center justify-center transition-all hover:scale-105 active:scale-95">
                Get Started Now
            </a>
            <a href="https://wa.me/233000000000" target="_blank"
               class="h-14 px-10 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold w-full sm:w-auto flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.503-5.73-1.464L0 24zm6.059-3.567l.354.21c1.554.922 3.51 1.41 5.534 1.411 5.674 0 10.293-4.617 10.297-10.297.002-2.753-1.071-5.339-3.023-7.291-1.951-1.952-4.536-3.023-7.287-3.025-5.681 0-10.3 4.618-10.304 10.298-.002 1.936.505 3.829 1.468 5.489l.23.398-.98 3.578 3.661-.963z"/>
                </svg>
                Contact Sales
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/home.blade.php ENDPATH**/ ?>