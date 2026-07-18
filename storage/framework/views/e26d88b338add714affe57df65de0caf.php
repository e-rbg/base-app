<?php
use Livewire\Component;
use Illuminate\Support\Facades\Route;
?>

<a 
    href="<?php echo e($this->resolved_url); ?>" 
    wire:navigate
    @click.stop
    x-data="{ 
        
        forceShow: <?php echo \Illuminate\Support\Js::from($forceShowLabel == true)->toHtml() ?>,
        
    }"
    
    <?php echo e($attributes->merge(['class' => $this->isActive() ? "$baseClass $activeClass" : $baseClass ])); ?>

>
    <div
        :class="isDesktop && collapse && !forceShow ? '<?php echo e($tooltipClass . ' absolute '); ?>' : ''"
        class="flex items-center justify-center flex-shrink-0 size-8 whitespace-nowrap text-sm z-100"
        data-tip="<?php echo e($label); ?>"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <?php echo $icon; ?>

            </svg>
        <?php else: ?>
            <div></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <span 
        class="whitespace-nowrap text-sm"
        x-cloak 
        x-show="!isDesktop || !collapse || forceShow"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        <?php echo e($label); ?>

    </span>
</a><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/5c68dcd3.blade.php ENDPATH**/ ?>