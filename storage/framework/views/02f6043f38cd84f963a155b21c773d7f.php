<<?php echo e($tag); ?> <?php echo e($attributes->class([
    'cursor-pointer outline-none outline-hidden inline-flex justify-center items-center group hover:shadow-xs',
    'transition-all ease-in-out duration-200 focus:ring-2 focus:ring-offset-2',
    'focus:ring-offset-background-white dark:focus:ring-offset-background-dark',
    'disabled:opacity-80 disabled:cursor-not-allowed',
    Arr::toRecursiveCssClasses($colorClasses),
    $roundedClasses,
    $sizeClasses,
])); ?>>
    <div class="shrink-0" <?php echo e($spinnerRemove); ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?>
            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => WireUi::component('icon')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => $icon,'class' => \Illuminate\Support\Arr::toCssClasses([$iconSizeClasses, 'shrink-0'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
        <?php else: ?>
            <?php echo e($label ?? $slot); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($spinner): ?>
        <?php if (isset($component)) { $__componentOriginal4cf70504f11b20ffb58b931a3e7b5291 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cf70504f11b20ffb58b931a3e7b5291 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'wireui-icon::components.spinner','data' => ['attributes' => $spinner,'class' => \Illuminate\Support\Arr::toCssClasses([$iconSizeClasses, 'shrink-0 animate-spin'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('wireui-icon::spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($spinner),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([$iconSizeClasses, 'shrink-0 animate-spin']))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cf70504f11b20ffb58b931a3e7b5291)): ?>
<?php $attributes = $__attributesOriginal4cf70504f11b20ffb58b931a3e7b5291; ?>
<?php unset($__attributesOriginal4cf70504f11b20ffb58b931a3e7b5291); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cf70504f11b20ffb58b931a3e7b5291)): ?>
<?php $component = $__componentOriginal4cf70504f11b20ffb58b931a3e7b5291; ?>
<?php unset($__componentOriginal4cf70504f11b20ffb58b931a3e7b5291); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</<?php echo e($tag); ?>>
<?php /**PATH C:\Users\elvon\Herd\base-app\vendor\wireui\wireui\src/Components/Button/views/mini.blade.php ENDPATH**/ ?>