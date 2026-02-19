@props(['title', 'description'])

<div
    class="group p-6 md:p-8 rounded-2xl md:rounded-[2rem] bg-white dark:bg-slate-900/50 backdrop-blur-sm border border-slate-100 dark:border-slate-800 hover:border-primary/20 dark:hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
    <div
        class="absolute -right-10 -top-10 w-32 h-32 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors">
    </div>
    <div
        class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/80 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-primary/10 group-hover:text-primary transition-all relative z-10">
        {{ $icon }}
    </div>
    <h3 class="text-lg md:text-xl font-black mb-3 text-slate-900 dark:text-white relative z-10">{{ $title }}</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed relative z-10">{{ $description }}</p>
</div>