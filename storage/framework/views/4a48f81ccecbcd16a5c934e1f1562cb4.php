<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo e($title ?? config('app.name')); ?> | Base App</title>

<script>
    function getTheme() {
        const saved = localStorage.getItem('theme');
        if (saved) return saved;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.classList.toggle('dark', theme === 'dark');
    }

    // Apply immediately on first load
    applyTheme(getTheme());

    // Re-apply every time Livewire navigates
    document.addEventListener('livewire:navigated', () => {
        applyTheme(getTheme());
    });
</script>

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<script >window.Wireui = {cache: {},hook(hook, callback) {window.addEventListener(`wireui:${hook}`, () => callback())},dispatchHook(hook) {window.dispatchEvent(new Event(`wireui:${hook}`))}}</script>
<script src="http://base-app.test/wireui/assets/scripts" defer ></script>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?><?php /**PATH C:\Users\elvon\Herd\base-app\resources\views/partials/head.blade.php ENDPATH**/ ?>