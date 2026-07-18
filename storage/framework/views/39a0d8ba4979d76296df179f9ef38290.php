<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }" class="h-full overflow-hidden"
    lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    x-data="{ 
        
        theme: localStorage.getItem('theme') || <?php echo \Illuminate\Support\Js::from(auth()->user()->profile->preferences['theme'] ?? 'light')->toHtml() ?>,
        isDesktop: window.innerWidth >= 768,
        
        init() {
            // Combine all initialization logic here
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });

            window.addEventListener('resize', () => {
                this.isDesktop = window.innerWidth >= 768;
            });

            window.addEventListener('theme-updated', event => {
                this.theme = event.detail.theme;
                localStorage.setItem('theme', this.theme);
                this.applyTheme();
            });

            this.applyTheme();
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    :data-theme="theme"
>
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="h-full overflow-hidden bg-base-100 antialiased">
        
        <?php if (isset($component)) { $__componentOriginaladf7d5283c6c06cb103ae62523e6a412 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaladf7d5283c6c06cb103ae62523e6a412 = $attributes; } ?>
<?php $component = WireUi\Components\Dialog\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dialog\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['z-index' => 'z-150','blur' => 'sm']); ?>
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
<?php $component->withAttributes([]); ?>
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
        
        <div class="flex h-screen w-full overflow-hidden">
            <!-- Desktop View Sidebar-->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('desktop-view-sidebar', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2543169381-0', $__key);

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
            <!-- Main Content : at the right -->
            <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
                <!-- Mobile View Sidebar : Hidden on Desktop View-->
                <div x-data="{ open:false }" class="md:hidden flex-shrink-0">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('header-view-profile', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2543169381-1', $__key);

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
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('mobile-view-sidebar', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2543169381-2', $__key);

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
                </div>
                <div class="flex-1 flex flex-col min-h-0 mt-14 sm:mt-0 overflow-y-auto">
                    <?php echo e($slot); ?>

                </div>
            </main>
        </div>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/layouts/app/sidebar.blade.php ENDPATH**/ ?>