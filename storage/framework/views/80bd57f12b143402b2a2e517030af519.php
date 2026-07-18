<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
?>

<?php if (isset($component)) { $__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.main-container','data' => ['title' => 'Settings','breadcrumbs' => [
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Settings']
    ],'wire:model.live.debounce.300ms' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('main-container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Settings','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Settings']
    ]),'wire:model.live.debounce.300ms' => 'search']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid grid-cols-1 gap-6">
        <div class="py-2">
            <div class="card bg-base-200 border border-base-300 shadow-sm overflow-hidden sm:w-1/4 w-full py-2 px-3">
                <div class="p-4 space-y-4">
                    
                    <div>
                        <h3 class="text-sm font-bold text-base-content tracking-tight">Theme Selector</h3>
                        <p class="text-[11px] text-base-content/50 font-medium leading-none mt-1">Switch to your desired theme.</p>
                    </div>

                    
                    <button 
                        
                        @click="$wire.updateTheme(theme === 'light' ? 'dark' : 'light')" 
                        class="group relative flex items-center justify-between w-full p-2 rounded-lg bg-base-200/50 hover:bg-base-200 transition-all duration-200 active:scale-[0.97] border border-transparent hover:border-base-300"
                    >
                        <div class="flex items-center gap-3">
                            
                            <div class="flex items-center justify-center size-8 rounded-md bg-base-100 shadow-sm border border-base-300/30 text-primary">
                                <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'sun'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'theme === \'dark\'','class' => 'size-5 animate-in zoom-in duration-300','x-cloak' => true]); ?>
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
                                <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'moon'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'theme === \'light\'','class' => 'size-5 animate-in zoom-in duration-300','x-cloak' => true]); ?>
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
                            
                            <span class="text-xs font-semibold text-base-content/80" x-text="theme === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
                        </div>

                        
                        <div class="flex items-center">
                            <div class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse mr-2"></div>
                            <span class="text-[10px] font-bold uppercase tracking-tighter opacity-40" x-text="theme"></span>
                        </div>
                    </button>
                    <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'localStorage.removeItem(\'theme\'); location.reload();']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        Use System Settings
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
            </div>
            
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0)): ?>
<?php $attributes = $__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0; ?>
<?php unset($__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0)): ?>
<?php $component = $__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0; ?>
<?php unset($__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0); ?>
<?php endif; ?><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/20d4a144.blade.php ENDPATH**/ ?>