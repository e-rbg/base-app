<div <?php echo e($attributes->class([
    'shrink-0 inline-flex items-center justify-center overflow-hidden',
    data_get($colorClasses, 'border', '') => !$borderless,
    data_get($colorClasses, 'label', '') => !$src,
    $borderClasses => !$borderless,
    $sizeClasses => !$src,
    $roundedClasses,
])); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <span <?php echo e(WireUi::extractAttributes($label)->class([
            data_get($iconSizeClasses, 'label', 'text-base'),
            'font-medium text-white dark:text-gray-200',
        ])); ?>>
            <?php echo e($label); ?>

        </span>
    <?php elseif($src): ?>
        <img
            alt="<?php echo e($alt); ?>"
            src="<?php echo e($src); ?>"
            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'shrink-0 object-cover object-center',
                $roundedClasses,
                $sizeClasses,
            ]); ?>"
        />
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => WireUi::component('icon')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => $icon ?? 'user','solid' => true,'class' => \Illuminate\Support\Arr::toCssClasses([
                data_get($iconSizeClasses, 'icon', 'w-7 h-7'),
                'text-white dark:text-gray-200 shrink-0',
            ])]); ?>
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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\elvon\Herd\base-app\vendor\wireui\wireui\src/Components/Avatar/views/index.blade.php ENDPATH**/ ?>