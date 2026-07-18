<?php
use Livewire\Component;
use Illuminate\Support\Facades\Route;
?>

<div
    x-data="{
        isDesktop: window.innerWidth >= 768,
        init() {
            window.addEventListener('resize', () => {
                this.isDesktop = window.innerWidth >= 768;
            });
        }
    }"
    :class="(isDesktop && collapse) ? ' justify-center ' : ' overflow-x-hidden'"
    class="h-10 flex items-center space-x-10 px-3 justify-between max-w-full cursor-pointer hover:text-base-900"
>
    <div class="flex items-center space-x-2 w-2/3 pl-2">
        <div
            :class="isDesktop && collapse ? '<?php echo e($tooltipClass); ?>' : ''"
            class="flex items-center justify-center shrink-0 size-7 whitespace-nowrap text-sm"
            data-tip="User Profile"
        >
            <?php
                // Logic to get the avatar URL or fallback
                $avatarUrl = auth()->user()->profile?->avatar 
                    ? asset('storage/' . auth()->user()->profile->avatar) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullname) . '&background=random';
            ?>

            
            <?php if (isset($component)) { $__componentOriginal2af325396ad71d1213e86b1b683fa104 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2af325396ad71d1213e86b1b683fa104 = $attributes; } ?>
<?php $component = WireUi\Components\Avatar\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Avatar\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'w-7 h-7','rounded' => 'full','src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl),'alt' => ''.e(auth()->user()->fullname).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2af325396ad71d1213e86b1b683fa104)): ?>
<?php $attributes = $__attributesOriginal2af325396ad71d1213e86b1b683fa104; ?>
<?php unset($__attributesOriginal2af325396ad71d1213e86b1b683fa104); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2af325396ad71d1213e86b1b683fa104)): ?>
<?php $component = $__componentOriginal2af325396ad71d1213e86b1b683fa104; ?>
<?php unset($__componentOriginal2af325396ad71d1213e86b1b683fa104); ?>
<?php endif; ?>
        </div>

        <span
            class="whitespace-nowrap text-sm font-medium"
            x-cloak
            x-show="!isDesktop || (isDesktop && !collapse)"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <?php echo e('@' . auth()->user()->username); ?>

        </span>
    </div>

    <div x-show="isDesktop && !collapse" class="w-1/3 flex items-end">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
            <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
        </svg>
    </div>
</div><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/cf2c2fbf.blade.php ENDPATH**/ ?>