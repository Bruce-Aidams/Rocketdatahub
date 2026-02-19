<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title', 'description']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    class="group p-6 md:p-8 rounded-2xl md:rounded-[2rem] bg-white dark:bg-slate-900/50 backdrop-blur-sm border border-slate-100 dark:border-slate-800 hover:border-primary/20 dark:hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
    <div
        class="absolute -right-10 -top-10 w-32 h-32 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors">
    </div>
    <div
        class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/80 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-primary/10 group-hover:text-primary transition-all relative z-10">
        <?php echo e($icon); ?>

    </div>
    <h3 class="text-lg md:text-xl font-black mb-3 text-slate-900 dark:text-white relative z-10"><?php echo e($title); ?></h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed relative z-10"><?php echo e($description); ?></p>
</div><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views/components/feature-card.blade.php ENDPATH**/ ?>