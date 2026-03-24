<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label' => null, 'error' => null]));

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

foreach (array_filter((['label' => null, 'error' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="space-y-2 group">
    <?php if($label): ?>
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1"><?php echo e($label); ?></label>
    <?php endif; ?>

    <div class="relative">
        <select <?php echo e($attributes->merge(['class' => 'premium-select' . ($error ? ' border-rose-500 focus:ring-rose-500/5 focus:border-rose-500' : '')])); ?>>
            <?php echo e($slot); ?>

        </select>
    </div>

    <?php if($error): ?>
        <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1"><?php echo e($error); ?></p>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\components\select.blade.php ENDPATH**/ ?>