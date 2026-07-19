<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }"
    lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    x-data="{
        theme: localStorage.getItem('theme') || 'light',
        init() {
            this.applyTheme();
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });
        },
        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    }"
    :data-theme="theme"
>
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <style>
            @page { size: A4 portrait; margin: 0.5in; }
            @media print {
                html, body { margin: 0; padding: 0; }
            }
            html, body { font-family: Calibri, Roboto, ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="bg-base-100 antialiased">
        <?php if (isset($component)) { $__componentOriginaladf7d5283c6c06cb103ae62523e6a412 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaladf7d5283c6c06cb103ae62523e6a412 = $attributes; } ?>
<?php $component = WireUi\Components\Dialog\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dialog\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['z-index' => 'z-50','blur' => 'sm']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaladf7d5283c6c06cb103ae62523e6a412)): ?>
<?php $attributes = $__attributesOriginaladf7d5283c6c06cb103ae62523e6a412; ?>
<?php unset($__attributesOriginaladf7d5283c6c06cb103ae62523e6a412); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaladf7d5283c6c06cb103ae62523e6a412)): ?>
<?php $component = $__componentOriginaladf7d5283c6c06cb103ae62523e6a412; ?>
<?php unset($__componentOriginaladf7d5283c6c06cb103ae62523e6a412); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal3dde83133891f87f89e964628fb558b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3dde83133891f87f89e964628fb558b6 = $attributes; } ?>
<?php $component = WireUi\Components\Notifications\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Notifications\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['z-index' => 'z-50']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $attributes = $__attributesOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__attributesOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $component = $__componentOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__componentOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>
        <?php echo e($slot); ?>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html>
<?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/layouts/print.blade.php ENDPATH**/ ?>