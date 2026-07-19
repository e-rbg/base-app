<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invalidated): ?>
    <label <?php echo e($attributes->class('text-sm text-negative-600')); ?>>
        <?php echo e($slot->isEmpty() ? $message : $slot); ?>

    </label>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\elvon\Herd\base-app\vendor\wireui\wireui\src/Components/Errors/views/single.blade.php ENDPATH**/ ?>