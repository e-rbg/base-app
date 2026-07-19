<?php if (isset($component)) { $__componentOriginal8a3f935235dee9c13e6f9e431872f03d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a3f935235dee9c13e6f9e431872f03d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::app.base','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::app.base'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div>
        <?php echo e($slot); ?>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a3f935235dee9c13e6f9e431872f03d)): ?>
<?php $attributes = $__attributesOriginal8a3f935235dee9c13e6f9e431872f03d; ?>
<?php unset($__attributesOriginal8a3f935235dee9c13e6f9e431872f03d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a3f935235dee9c13e6f9e431872f03d)): ?>
<?php $component = $__componentOriginal8a3f935235dee9c13e6f9e431872f03d; ?>
<?php unset($__componentOriginal8a3f935235dee9c13e6f9e431872f03d); ?>
<?php endif; ?><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/layouts/auth.blade.php ENDPATH**/ ?>