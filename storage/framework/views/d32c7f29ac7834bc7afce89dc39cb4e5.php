<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions;
?>

<div class="min-h-screen flex sm:items-center items-start sm:pt-0 pt-10 justify-center bg-base-200 px-4">
    <div class="card w-full max-w-md bg-base-100 shadow-2xl border border-base-300">
        <div class="card-body p-8">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-primary text-primary-content mb-3 shadow-md">
                    <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'lock-closed'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                </div>
                <h1 class="text-2xl font-bold text-base-content">Welcome Back</h1>
                <p class="text-sm text-base-content/60">Enter your account details to continue</p>
            </div>

            <form wire:submit="login" class="space-y-6">
                <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email Address','placeholder' => 'name@example.com','icon' => 'envelope','wire:model' => 'email']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $attributes = $__attributesOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__attributesOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $component = $__componentOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__componentOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal728935b8358d1c6f525570f19e1ac6de = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal728935b8358d1c6f525570f19e1ac6de = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Password::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Password::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Password','placeholder' => '••••••••','wire:model' => 'password']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal728935b8358d1c6f525570f19e1ac6de)): ?>
<?php $attributes = $__attributesOriginal728935b8358d1c6f525570f19e1ac6de; ?>
<?php unset($__attributesOriginal728935b8358d1c6f525570f19e1ac6de); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal728935b8358d1c6f525570f19e1ac6de)): ?>
<?php $component = $__componentOriginal728935b8358d1c6f525570f19e1ac6de; ?>
<?php unset($__componentOriginal728935b8358d1c6f525570f19e1ac6de); ?>
<?php endif; ?>

                <div class="flex items-center justify-between">
                    <?php if (isset($component)) { $__componentOriginal49f7089ef4c669895a04f5fadb180b38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49f7089ef4c669895a04f5fadb180b38 = $attributes; } ?>
<?php $component = WireUi\Components\Switcher\Checkbox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Switcher\Checkbox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Remember me','wire:model' => 'remember']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49f7089ef4c669895a04f5fadb180b38)): ?>
<?php $attributes = $__attributesOriginal49f7089ef4c669895a04f5fadb180b38; ?>
<?php unset($__attributesOriginal49f7089ef4c669895a04f5fadb180b38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49f7089ef4c669895a04f5fadb180b38)): ?>
<?php $component = $__componentOriginal49f7089ef4c669895a04f5fadb180b38; ?>
<?php unset($__componentOriginal49f7089ef4c669895a04f5fadb180b38); ?>
<?php endif; ?>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-focus transition-colors">
                        Forgot password?
                    </a>
                </div>

                <div class="pt-2">
                    <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','primary' => true,'full' => true,'lg' => true,'label' => 'Login to Dashboard','spinner' => 'login']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-base-300 text-center">
                <p class="text-sm text-base-content/60">
                    New here? 
                    <a href="<?php echo e(route('register')); ?>" class="font-semibold text-primary hover:underline" wire:navigate>Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/25185dfa.blade.php ENDPATH**/ ?>