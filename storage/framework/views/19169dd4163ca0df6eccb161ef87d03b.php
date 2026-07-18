<?php
use Livewire\Component;
use App\Models\User;
use App\Models\UserProfile;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Layout;
?>

<?php if (isset($component)) { $__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.main-container','data' => ['title' => 'Users','breadcrumbs' => [['url' => route('admin.dashboard'), 'label' => 'Dashboard'], ['label' => 'Users']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('main-container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Users','breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['url' => route('admin.dashboard'), 'label' => 'Dashboard'], ['label' => 'Users']])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <h2 class="text-2xl font-black text-base-content tracking-tight">User Directory</h2>
                <p class="text-xs font-bold uppercase tracking-widest opacity-40">Manage Roles & Access</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="flex-1 sm:w-64">
                    <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','icon' => 'magnifying-glass','placeholder' => 'Search users...','shadow' => 'none']); ?>
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
<?php $component->withAttributes(['primary' => true,'icon' => 'plus','label' => 'Add User','rounded' => true,'wire:click' => 'openCreateModal']); ?>
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

        
        <div class="hidden md:block bg-base-100 border border-base-200 rounded-[2rem] overflow-hidden shadow-sm">
            <table class="table table-lg w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6 pl-10">Name</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6">Role</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6">Status</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6 text-right pr-10">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200/50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="group hover:bg-base-200/30 transition-colors">
                            <td class="py-5 pl-10">
                                <div class="flex items-center gap-4">
                                    <?php
                                        $avatarUrl = $user->profile?->avatar
                                            ? asset('storage/' . $user->profile->avatar)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&background=random';
                                    ?>

                                    
                                    <div class="cursor-zoom-in hover:scale-110 transition-transform active:scale-95"
                                        @click="$dispatch('preview-image', { url: '<?php echo e($avatarUrl); ?>', title: '<?php echo e($user->full_name); ?>' })">

                                        <?php if (isset($component)) { $__componentOriginal2af325396ad71d1213e86b1b683fa104 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2af325396ad71d1213e86b1b683fa104 = $attributes; } ?>
<?php $component = WireUi\Components\Avatar\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Avatar\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'w-10 h-10','rounded' => 'lg','src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl)]); ?>
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

                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm leading-none"><?php echo e($user->full_name); ?></span>
                                        <span class="text-xs opacity-40 mt-1">@ <?php echo e($user->username); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                    $roleColor = match($user->role) {
                                        'super_admin' => 'negative',
                                        'admin' => 'primary',
                                        'editor' => 'info',
                                        default => 'secondary'
                                    };
                                ?>
                                <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($roleColor),'label' => ''.e(str_replace('_', ' ', strtoupper($user->role))).'','class' => 'text-[9px] font-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $attributes = $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $component = $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'positive' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status === 'active'),'warning' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status !== 'active'),'label' => ''.e($user->status).'','class' => 'text-[9px] font-black uppercase']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $attributes = $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $component = $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
                            </td>
                            <td class="text-right pr-10">
                                <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <?php if (isset($component)) { $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Mini::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Mini::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rounded' => true,'secondary' => true,'flat' => true,'icon' => 'pencil-square','wire:click' => 'openEditModal(\''.e($user->id).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $attributes = $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $component = $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Mini::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Mini::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rounded' => true,'warning' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status === 'active'),'flat' => true,'icon' => 'no-symbol','wire:click' => 'toggleStatus(\''.e($user->id).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $attributes = $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $component = $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Mini::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Mini::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rounded' => true,'negative' => true,'flat' => true,'icon' => 'trash','wire:click' => 'deleteUser(\''.e($user->id).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $attributes = $__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__attributesOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6)): ?>
<?php $component = $__componentOriginal2a262af96c483a1eb8dd58a827ff85d6; ?>
<?php unset($__componentOriginal2a262af96c483a1eb8dd58a827ff85d6); ?>
<?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="grid grid-cols-1 gap-4 md:hidden px-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="bg-base-100 border border-base-200 rounded-[2rem] p-5 shadow-sm active:bg-base-200/50 transition-all">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <?php
                                $avatarUrl = $user->profile?->avatar
                                    ? asset('storage/' . $user->profile->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&background=random';
                            ?>
                            <div class="cursor-zoom-in hover:scale-110 transition-transform active:scale-95"
                                @click="$dispatch('preview-image', { url: '<?php echo e($avatarUrl); ?>', title: '<?php echo e($user->full_name); ?>' })">

                                <?php if (isset($component)) { $__componentOriginal2af325396ad71d1213e86b1b683fa104 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2af325396ad71d1213e86b1b683fa104 = $attributes; } ?>
<?php $component = WireUi\Components\Avatar\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Avatar\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'w-10 h-10','rounded' => 'lg','src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarUrl)]); ?>
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
                            <div class="flex flex-col">
                                <span class="text-base font-black leading-tight text-base-content"><?php echo e($user->full_name); ?></span>
                                <span class="text-xs opacity-50 lowercase"><?php echo e($user->email); ?></span>

                                
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-[10px] font-bold text-primary uppercase tracking-tighter">@ <?php echo e($user->username); ?></span>
                                    <span class="opacity-20">•</span>
                                    <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'positive' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status === 'active'),'warning' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status !== 'active'),'label' => ''.e($user->status).'','class' => 'text-[8px] font-black uppercase px-1.5 h-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $attributes = $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $component = $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <?php
                            $roleColor = match($user->role) {
                                'super_admin' => 'negative',
                                'admin'       => 'primary',
                                'editor'      => 'info',
                                default       => 'secondary'
                            };
                        ?>
                        <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($roleColor),'label' => ''.e(str_replace('_', ' ', strtoupper($user->role))).'','class' => 'text-[8px] font-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $attributes = $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655)): ?>
<?php $component = $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655; ?>
<?php unset($__componentOriginalb3b4efb7fe41ab882e85629f7bd48655); ?>
<?php endif; ?>
                    </div>

                    
                    <div class="mt-5 flex justify-between items-center border-t border-base-200 pt-4">
                        <div class="flex gap-1">
                            <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'secondary' => true,'flat' => true,'icon' => 'pencil-square','label' => 'Edit','wire:click' => 'openEditModal(\''.e($user->id).'\')']); ?>
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
<?php $component->withAttributes(['xs' => true,'warning' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->status === 'active'),'flat' => true,'icon' => 'no-symbol','label' => 'Status','wire:click' => 'toggleStatus(\''.e($user->id).'\')']); ?>
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

                        <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['xs' => true,'negative' => true,'flat' => true,'icon' => 'trash','label' => 'Delete','wire:click' => 'deleteUser(\''.e($user->id).'\')']); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-10 text-center bg-base-100 border-2 border-dashed border-base-200 rounded-[2rem]">
                    <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'users'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-10 h-10 mx-auto opacity-20 mb-2']); ?>
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
                    <p class="text-xs font-bold uppercase tracking-widest opacity-30">No User Found</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="mt-4"><?php echo e($users->links()); ?></div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal6a7d148983ed3ace55a3e668006b80a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5 = $attributes; } ?>
<?php $component = WireUi\Components\Modal\Card::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Modal\Card::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($selected_id ? 'Edit User Profile' : 'Create New Account').'','wire:model' => 'userModal','z-index' => 'z-50','rounded' => '3xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            
            <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'First Name','wire:model' => 'first_name','placeholder' => 'John']); ?>
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
            <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Last Name','wire:model' => 'last_name','placeholder' => 'Doe']); ?>
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

            <div class="sm:col-span-2">
                <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Middle Name (Optional)','wire:model' => 'middle_name']); ?>
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
            </div>

            
            <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Username','prefix' => '@','wire:model' => 'username']); ?>
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
            <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email Address','icon' => 'envelope','wire:model' => 'email']); ?>
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

            
            <div class="sm:col-span-2 space-y-5 border-t border-base-200 pt-5 mt-2">
                <?php $isSuperAdmin = auth()->user()->role === 'super_admin'; ?>

                
                <div class="relative group">
                    <?php if (isset($component)) { $__componentOriginal49b3de13d927faa5a3ecd49fc0b06061 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49b3de13d927faa5a3ecd49fc0b06061 = $attributes; } ?>
<?php $component = WireUi\Components\Select\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Select\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Account Role','placeholder' => 'Select role','wire:model' => 'role','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            ['name' => 'Super Admin', 'id' => 'super_admin'],
                            ['name' => 'Admin', 'id' => 'admin'],
                            ['name' => 'Editor', 'id' => 'editor'],
                            ['name' => 'User', 'id' => 'user'],
                        ]),'option-label' => 'name','option-value' => 'id','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$isSuperAdmin)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49b3de13d927faa5a3ecd49fc0b06061)): ?>
<?php $attributes = $__attributesOriginal49b3de13d927faa5a3ecd49fc0b06061; ?>
<?php unset($__attributesOriginal49b3de13d927faa5a3ecd49fc0b06061); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49b3de13d927faa5a3ecd49fc0b06061)): ?>
<?php $component = $__componentOriginal49b3de13d927faa5a3ecd49fc0b06061; ?>
<?php unset($__componentOriginal49b3de13d927faa5a3ecd49fc0b06061); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isSuperAdmin): ?>
                        <div class="mt-1 flex items-center gap-1 text-amber-600 dark:text-amber-500">
                            <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'shield-exclamation'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5']); ?>
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
                            <p class="text-[10px] font-bold tracking-tight">Role management restricted.</p>
                        </div>
                        <div class="absolute inset-0 cursor-not-allowed" title="Super Admin Only"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="relative group border-t border-base-200 pt-4">
                    <?php if (isset($component)) { $__componentOriginal728935b8358d1c6f525570f19e1ac6de = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal728935b8358d1c6f525570f19e1ac6de = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Password::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Password::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => ''.e($selected_id ? 'Change Password' : 'Set Password').'','wire:model' => 'password','placeholder' => '••••••••','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$isSuperAdmin)]); ?>
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

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSuperAdmin): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected_id): ?>
                            <p class="mt-1 text-[10px] opacity-50 italic">Leave blank to keep current password.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <div class="mt-1 flex items-center gap-1 text-amber-600 dark:text-amber-500">
                            <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'lock-closed'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5']); ?>
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
                            <p class="text-[10px] font-bold tracking-tight">Password changes restricted.</p>
                        </div>
                        <div class="absolute inset-0 cursor-not-allowed" title="Super Admin Only"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

         <?php $__env->slot('footer', null, ['class' => 'flex items-center justify-end gap-x-3']); ?> 
            <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'label' => 'Cancel','x-on:click' => 'close','class' => 'font-bold']); ?>
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
<?php $component->withAttributes(['primary' => true,'label' => 'Save Account','wire:click' => 'save','spinner' => 'save','class' => 'font-bold px-6']); ?>
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
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $attributes = $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $component = $__componentOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>




 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0)): ?>
<?php $attributes = $__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0; ?>
<?php unset($__attributesOriginal49c6e2f29beb5c9b321af4eaea647fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0)): ?>
<?php $component = $__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0; ?>
<?php unset($__componentOriginal49c6e2f29beb5c9b321af4eaea647fb0); ?>
<?php endif; ?><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/ebbe4de2.blade.php ENDPATH**/ ?>