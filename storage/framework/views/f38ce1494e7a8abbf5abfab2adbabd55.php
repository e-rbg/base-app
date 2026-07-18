<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Dashboard',
    'breadcrumbs' => []
]));

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

foreach (array_filter(([
    'title' => 'Dashboard',
    'breadcrumbs' => []
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-col h-screen w-full bg-base-100 overflow-hidden">
    <div class="shrink-0 z-20 bg-base-100/80 backdrop-blur-md border-b border-base-200">
        <div class="px-6 py-3 shadow-sm">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($breadcrumbs)): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('breadcrumbs', ['items' => $breadcrumbs]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3845128001-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex items-center justify-between gap-4 mt-1">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">
                    <?php echo e($title); ?>

                </h1>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attributes->has('wire:model')): ?>
                <div class="flex-1 max-w-md" x-show="isDesktop">
                    <label class="input input-bordered flex items-center gap-2 h-10 bg-base-200/50 border-none focus-within:ring-1 ring-primary">
                        <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'magnifying-glass'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 opacity-70']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                        <input
                            type="search"
                            x-on:keydown.window.cmd.k.prevent="$el.focus()"
                            x-on:keydown.window.ctrl.k.prevent="$el.focus()"
                            class="grow text-sm font-roboto"
                            placeholder="Search anything..."
                            <?php echo e($attributes->whereStartsWith('wire:model')); ?> 
                        />
                        <div class="flex gap-1 items-center opacity-50">
                            <kbd class="kbd kbd-sm text-[10px]">⌘</kbd>
                            <kbd class="kbd kbd-sm text-[10px]">K</kbd>
                        </div>
                    </label>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <main class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
        <div class="p-6 max-w-[1600px] mx-auto space-y-10">
            <?php echo e($slot); ?>

        </div>
    </main>
</div><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/components/main-container.blade.php ENDPATH**/ ?>