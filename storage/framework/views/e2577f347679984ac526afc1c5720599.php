<?php
use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;
?>

<div class="flex flex-col items-center py-10 print:py-0">
    
    <div class="mb-6 flex w-full max-w-[8.5in] items-center justify-between rounded-xl bg-white p-4 shadow-sm print:hidden">
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
<?php $component->withAttributes(['flat' => true,'gray' => true,'icon' => 'arrow-left','label' => 'Back','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.travel-orders'))]); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending'): ?>
                <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'warning' => true,'label' => 'Pending','icon' => 'clock']); ?>
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
            <?php elseif($order->status === 'approved'): ?>
                <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'success' => true,'label' => 'Approved','icon' => 'check']); ?>
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
            <?php elseif($order->status === 'disapproved'): ?>
                <?php if (isset($component)) { $__componentOriginalb3b4efb7fe41ab882e85629f7bd48655 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3b4efb7fe41ab882e85629f7bd48655 = $attributes; } ?>
<?php $component = WireUi\Components\Badge\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Badge\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'error' => true,'label' => 'Disapproved','icon' => 'x-mark']); ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="flex gap-2">
            <?php
                $isApprover = str_contains($order->approved_by_name, auth()->user()->full_name ?? '');
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending' && $isApprover): ?>
                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'icon' => 'check','label' => 'Approve','wire:click' => 'approveOrder']); ?>
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
<?php $component->withAttributes(['negative' => true,'icon' => 'x-mark','label' => 'Disapprove','wire:click' => 'disapproveOrder']); ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['black' => true,'icon' => 'printer','label' => 'Print Travel Order','onclick' => 'window.print()']); ?>
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
<?php $component->withAttributes(['secondary' => true,'icon' => 'folder-arrow-down','label' => 'Download PDF','href' => ''.e(route('travel-order.pdf', $order->id)).'?download=1']); ?>
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

    
    <div class="w-full max-w-[8.5in] border border-gray-200 bg-white p-[0.5in] shadow-2xl print:m-0 print:border-none print:p-0 print:shadow-none">

        
        <div class="flex items-center justify-between border-b-2 border-black pb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(storage_path('app/public/dar-logo.webp'))): ?>
                <img src="<?php echo e(asset('storage/dar-logo.webp')); ?>" class="h-16 w-auto">
            <?php else: ?>
                <div class="flex h-16 w-16 items-center justify-center border text-[8px]">DAR LOGO</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="text-center leading-[1.15]">
                <p class="m-0 text-[10px] uppercase tracking-widest">Republic of the Philippines</p>
                <h1 class="m-0 text-xl font-bold uppercase">Department of Agrarian Reform</h1>
                <p class="m-0 text-sm font-semibold">Provincial Agrarian Reform Office</p>
                <p class="m-0 text-xs">Davao de Oro</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(storage_path('app/public/bagong-pilipinas-logo.png'))): ?>
                <img src="<?php echo e(asset('storage/bagong-pilipinas-logo.png')); ?>" class="h-16 w-auto">
            <?php else: ?>
                <div class="flex h-16 w-16 items-center justify-center border text-[8px]">BP LOGO</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="my-8 flex items-center justify-center gap-6">
            <?php $docQr = $order->generateDocumentQrCode(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($docQr): ?>
                <div class="flex flex-col items-center flex-shrink-0">
                    <img src="<?php echo e($docQr); ?>" alt="Document QR" width="70" height="70" />
                    <p class="text-[6px] font-mono mt-0.5 text-gray-400">Scan to verify</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="text-center">
                <h2 class="text-3xl font-black">TRAVEL ORDER</h2>
                <p class="mt-1 font-mono">No. <span class="underline"><?php echo e($order->travel_order_no); ?></span></p>
            </div>
        </div>

        
        <div class="grid grid-cols-2 gap-y-3 text-sm">
            <div>
                <label class="text-[9px] font-black uppercase text-gray-400">Personnel Name</label>
                <p class="text-lg font-bold uppercase"><?php echo e($order->name); ?></p>
            </div>
            <div class="text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Order Date</label>
                <p class=""><?php echo e($order->travel_date->format('F d, Y')); ?></p>
            </div>

            <div class="">
                <label class="text-[9px] font-black uppercase text-gray-400">Position</label>
                <p class=""><?php echo e($order->position); ?></p>
            </div>
            <div class=" text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Station</label>
                <p class=""><?php echo e($order->station); ?></p>
            </div>

            <div class="">
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Type</label>
                <p class=""><?php echo e(str_replace('_', ' ', ucfirst($order->travel_type))); ?></p>
            </div>
            <div class="text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Period</label>
                <p class=""><?php echo e($order->departure_date->format('M d')); ?> – <?php echo e($order->return_date->format('M d, Y')); ?></p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->destination): ?>
            <div class="col-span-2">
                <label class="text-[9px] font-black uppercase text-gray-400">Destination</label>
                <p class=""><?php echo e($order->formattedDestination()); ?></p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->report_to): ?>
            <div class="col-span-2">
                <label class="text-[9px] font-black uppercase text-gray-400">Report To</label>
                <p class=""><?php echo e($order->report_to); ?></p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="">
            <label class="text-[9px] font-black uppercase text-gray-400">Purpose of Trip</label>
            <div class="mt-2 border border-gray-200 bg-gray-50 p-4">
                <ul class="list-disc space-y-2 pl-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->purpose_of_trip; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purpose): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="italic"><?php echo e($purpose); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        </div>

        
        <?php
            $vt = is_array($order->vehicle_type) ? $order->vehicle_type : [$order->vehicle_type];
            $isGovt = in_array('Government Vehicle', $vt);
            $others = array_filter($vt, fn($v) => $v !== 'Government Vehicle');
            $acc = is_array($order->accommodation_type) ? $order->accommodation_type : [$order->accommodation_type];
        ?>
        <div class="mt-6 grid grid-cols-3 gap-2 border-t border-gray-100 pt-4 text-xs">
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold"><?php echo e($order->transportation_means === 'Land' ? 'X' : ''); ?></div>
                <span class="uppercase">Land Travel</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold"><?php echo e($isGovt ? 'X' : ''); ?></div>
                <span class="uppercase">Govt Vehicle</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold"><?php echo e(count($others) ? 'X' : ''); ?></div>
                <span class="uppercase">Others: <span class="underline ml-1"><?php echo e(count($others) ? implode(', ', $others) : '________'); ?></span></span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold"><?php echo e(in_array('Live-out', $acc) ? 'X' : ''); ?></div>
                <span class="uppercase">Live-out</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold"><?php echo e(in_array('Live-in', $acc) ? 'X' : ''); ?></div>
                <span class="uppercase">Live-in</span>
            </div>
        </div>

        
        <div class="mt-20 grid grid-cols-2 gap-10 text-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->travel_type !== 'intra_municipal' && !empty($order->recommending_approval) && $order->recommending_approval !== 'N/A'): ?>
            <div>
                <p class="mb-5 text-left text-[9px] font-bold uppercase text-gray-400">Recommending Approval:</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->recommending_approved_at && $order->esignature_recommender_hash): ?>
                    <div class="mb-2 flex flex-col justify-center items-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($qrCode = \App\Models\TravelOrder::generateQrFromHash($order->esignature_recommender_hash)): ?>
                            <img src="<?php echo e($qrCode); ?>" alt="E-signature QR code" width="100" height="100" />
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="font-mono text-[8px] tracking-tight leading-none mt-1"><?php echo e($order->esignature_recommender_hash); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="border-b-2 border-black font-bold uppercase"><?php echo e($order->recommending_approval); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->recommending_position): ?>
                    <p class="text-xs italic"><?php echo e($order->recommending_position); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div>
                <p class="mb-5 text-left text-[9px] font-bold uppercase text-gray-400">Approved By:</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'approved' && $order->esignature_hash): ?>
                    <div class="mb-2 flex flex-col justify-center items-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($qrCode = \App\Models\TravelOrder::generateQrFromHash($order->esignature_hash)): ?>
                            <img src="<?php echo e($qrCode); ?>" alt="E-signature QR code" width="100" height="100" />
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="font-mono text-[8px] tracking-tight leading-none mt-1"><?php echo e($order->esignature_hash); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="border-b-2 border-black font-bold uppercase"><?php echo e($order->approved_by_name); ?></p>
                <p class="text-xs italic"><?php echo e($order->approved_by_position); ?></p>
            </div>
        </div>

        
        <div class="mt-8 pt-4 border-t border-gray-200">
            <p class="text-[8px] font-mono text-gray-400 text-center break-all">
                Verify this document: <?php echo e(route('verify.travel-order', $order)); ?>

            </p>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->esignature_hash): ?>
    <div class="mt-6 w-full max-w-[8.5in] rounded-xl bg-white p-4 shadow-sm print:hidden">
        <h3 class="mb-3 text-sm font-bold">Verify Signature</h3>
        <div class="flex items-end gap-3">
            <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'verifyInput','placeholder' => 'Enter the signature text from the document','icon' => 'finger-print','shadow' => 'none','class' => 'flex-1']); ?>
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
            <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'label' => 'Verify','wire:click' => 'verifySignature']); ?>
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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($verificationResult !== null): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($verificationResult): ?>
                <div class="mt-3 flex items-center gap-2 text-sm text-green-600">
                    <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'check-circle'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
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
<?php endif; ?> Signature is valid — matches the approved record.
                </div>
            <?php else: ?>
                <div class="mt-3 flex items-center gap-2 text-sm text-red-600">
                    <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'x-circle'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
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
<?php endif; ?> Signature does not match — possible forgery.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\elvon\Herd\base-app\storage\framework\views/livewire/views/a7fa481f.blade.php ENDPATH**/ ?>