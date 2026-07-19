<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background: #4f46e5; padding: 30px; text-align: center; color: white; }
        .content { padding: 40px; text-align: center; }
        .code-display { font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #1f2937; background: #f3f4f6; padding: 20px; border-radius: 12px; margin: 20px 0; display: inline-block; width: 80%; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="text-center">
                <h2 class="text-2xl font-black">Verify your email</h2>
                <p class="mt-4 opacity-70">
                    Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                </p>

                <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="mt-6">
                    <?php echo csrf_field(); ?>
                    <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'label' => 'Resend Verification Email','type' => 'submit']); ?>
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
                </form>
            </div>
            <div class="footer">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
            </div>
        </div>
        
    </div>
</body>
</html><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>