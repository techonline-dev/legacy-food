<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= e($meta_title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= asset('assets/css/custom.css') ?>">
</head>
<body class="bg-[#07160d] text-[#fdf6e3] min-h-screen flex items-center justify-center p-6">
    <div class="card-glow max-w-xl w-full p-8 md:p-10 rounded-3xl border border-[#bc944c]/30">
        <div class="text-center mb-8">
            <img src="<?= asset('assets/images/branding/header-logo-light.svg') ?>" class="h-10 mx-auto mb-4">
            <h1 class="font-display text-2xl font-bold text-[#fdf6e3]">System Diagnostics & Installer</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Verify MySQL database connection and load initial seed data.</p>
        </div>

        <div class="space-y-4 mb-8">
            <?php foreach ($status as $s): ?>
                <div class="p-3.5 rounded-xl text-xs flex items-center gap-3 <?= $s['type'] === 'success' ? 'bg-emerald-950/70 border border-emerald-500/30 text-emerald-300' : ($s['type'] === 'error' ? 'bg-rose-950/70 border border-rose-500/30 text-rose-300' : 'bg-white/[0.04] border border-[#bc944c]/20 text-[#fdf6e3]/80') ?>">
                    <span><?= $s['type'] === 'success' ? '✓' : ($s['type'] === 'error' ? '⚠' : 'ℹ') ?></span>
                    <span><?= e($s['msg']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($connected): ?>
            <form action="<?= url('install/run') ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="btn-gold w-full py-3 text-xs uppercase tracking-wider font-bold">
                    <?= $tablesCount > 0 ? 'Re-run Schema & Seed Database' : 'Install Database Schema & Seeds' ?>
                </button>
            </form>
        <?php else: ?>
            <div class="p-4 rounded-xl bg-amber-950/60 border border-amber-500/30 text-amber-200 text-xs">
                Please check your MySQL server in Laragon (Start All) and verify <code>.env</code> file credentials.
            </div>
        <?php endif; ?>

        <div class="mt-6 pt-6 border-t border-[#bc944c]/20 flex items-center justify-between text-xs text-[#fdf6e3]/50">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c]">← View Storefront</a>
            <a href="<?= url('admin/login') ?>" class="hover:text-[#bc944c]">Admin Portal →</a>
        </div>
    </div>
</body>
</html>
