<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
?>

<?php if (isset($component)) { $__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.main-container','data' => ['title' => 'Dashboard','breadcrumbs' => [
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        
    ],'wire:model.live.debounce.300ms' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('main-container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dashboard','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        
    ]),'wire:model.live.debounce.300ms' => 'search']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid grid-cols-1 gap-6">
        <!-- MAIN CONTENT HERE -->
        <div class="p-6 max-w-400 mx-auto space-y-10">
            <header class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-base-content">
                        System <span class="text-primary">Overview</span>
                    </h2>
                    <p class="text-base-content/60 font-medium">Reporting period: Oct 12 - Oct 19, 2025</p>
                </div>
                <div class="flex items-center gap-3">
                    <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'cloud-arrow-down','secondary' => true,'outline' => true,'label' => 'Export PDF']); ?>
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
                    <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'plus','primary' => true,'label' => 'Create Invoice','class' => 'shadow-lg shadow-primary/20']); ?>
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
            </header>

            <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <?php
                    $stats = [
                        ['label' => 'Total Users', 'value' => '12,842', 'color' => 'primary', 'trend' => '+14%'],
                        ['label' => 'Active Subscriptions', 'value' => '4,210', 'color' => 'secondary', 'trend' => '+2%'],
                        ['label' => 'Avg. Session', 'value' => '12m 4s', 'color' => 'accent', 'trend' => '-5%'],
                        ['label' => 'Pending Support', 'value' => '24', 'color' => 'error', 'trend' => 'Urgent'],
                    ];
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="card bg-base-100 shadow-sm border border-base-200 transition-all hover:border-<?php echo e($stat['color']); ?>/50">
                    <div class="card-body p-5">
                        <span class="text-xs font-bold opacity-50 uppercase tracking-widest"><?php echo e($stat['label']); ?></span>
                        <div class="flex items-center justify-between mt-2">
                            <h3 class="text-2xl font-black"><?php echo e($stat['value']); ?></h3>
                            <div class="badge badge-<?php echo e($stat['color']); ?> badge-outline text-[10px] font-bold"><?php echo e($stat['trend']); ?></div>
                        </div>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden h-80">
                    <div class="card-body">
                        <h3 class="text-lg font-bold">User Acquisition</h3>
                        <div class="flex-1 mt-4 bg-base-200/50 rounded-xl border-2 border-dashed border-base-300 flex items-center justify-center">
                            <p class="text-xs opacity-40 font-mono">[ Chart.js / Recharts Component ]</p>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden h-80">
                    <div class="card-body">
                        <h3 class="text-lg font-bold">Revenue Streams</h3>
                        <div class="flex-1 mt-4 bg-base-200/50 rounded-xl border-2 border-dashed border-base-300 flex items-center justify-center">
                            <p class="text-xs opacity-40 font-mono">[ Pie Chart Component ]</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black">Transaction History</h3>
                    <div class="join shadow-sm border border-base-200">
                        <button class="btn btn-sm join-item">All</button>
                        <button class="btn btn-sm join-item bg-base-100">Success</button>
                        <button class="btn btn-sm join-item bg-base-100">Pending</button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm">
                    <table class="table table-lg">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th class="text-xs uppercase opacity-50">Reference</th>
                                <th class="text-xs uppercase opacity-50">Customer</th>
                                <th class="text-xs uppercase opacity-50">Status</th>
                                <th class="text-xs uppercase opacity-50">Date</th>
                                <th class="text-right text-xs uppercase opacity-50">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = range(1, 8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-base-200/20 transition-colors">
                                <td class="font-mono text-xs opacity-60">#INV-<?php echo e(4000 + $i); ?></td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-8"><span><?php echo e($i); ?></span></div>
                                        </div>
                                        <span class="font-bold text-sm">Customer User <?php echo e($i); ?></span>
                                    </div>
                                </td>
                                <td><div class="badge badge-success badge-sm">Successful</div></td>
                                <td class="text-sm opacity-60">Oct 1<?php echo e($i); ?>, 2025</td>
                                <td class="text-right font-black">$ <?php echo e(number_format($i * 125, 2)); ?></td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                <div class="lg:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-lg font-bold mb-4">Upcoming Deployments</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-base-200/50 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/20 text-primary flex items-center justify-center font-bold">V4</div>
                                    <div>
                                        <p class="text-sm font-bold">Tailwind v4.0 Patch</p>
                                        <p class="text-xs opacity-50">Scheduled for 14:00 UTC</p>
                                    </div>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'label' => 'Manage','outline' => true]); ?>
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
                            </div>
                    </div>
                </div>

                <div class="card bg-primary text-primary-content shadow-xl shadow-primary/20">
                    <div class="card-body">
                        <h3 class="text-xl font-bold">Upgrade to Pro</h3>
                        <p class="text-sm opacity-80 leading-relaxed">Unlock advanced analytics, custom reports, and unlimited team members.</p>
                        <div class="card-actions mt-4">
                            <button class="btn btn-block bg-white text-primary border-none hover:bg-base-200">Go Premium</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT HERE -->
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
<?php endif; ?>

<?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/332f5326.blade.php ENDPATH**/ ?>