<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>

<?php if (isset($component)) { $__componentOriginal63775216eddce010301c56953af18d03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal63775216eddce010301c56953af18d03 = $attributes; } ?>
<?php $component = WireUi\Components\TimePicker\Selector::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('time-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TimePicker\Selector::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal63775216eddce010301c56953af18d03)): ?>
<?php $attributes = $__attributesOriginal63775216eddce010301c56953af18d03; ?>
<?php unset($__attributesOriginal63775216eddce010301c56953af18d03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal63775216eddce010301c56953af18d03)): ?>
<?php $component = $__componentOriginal63775216eddce010301c56953af18d03; ?>
<?php unset($__componentOriginal63775216eddce010301c56953af18d03); ?>
<?php endif; ?><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/d3d4c3fe9305a89c6950521650f75093.blade.php ENDPATH**/ ?>