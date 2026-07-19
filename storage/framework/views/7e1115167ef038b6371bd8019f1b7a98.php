<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }" class="h-full overflow-hidden"
    lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    x-data="{ 
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
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
        <div class="flex h-screen w-full overflow-hidden">
            <div class="flex-1 flex flex-col min-h-0 mt-14 sm:mt-0 overflow-hidden">
                <?php echo e($slot); ?>

            </div>
        </div>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/layouts/app/base.blade.php ENDPATH**/ ?>