<?php
use App\Models\Setting;

$defaultTitle = Setting::get('meta_title_default', 'LEGACY · Crafted in South India | Premium A2 Ghee & Pure Oils');
$defaultDesc = Setting::get('meta_description_default', 'Pure, traditionally churned butter ghee and wood-pressed oils rooted in purity, craftsmanship and generations of South Indian expertise.');
$defaultKeywords = Setting::get('meta_keywords_default', 'ghee, A2 ghee, bilona ghee, South India, premium ghee, wood pressed oils, coconut oil, groundnut oil, sesame oil, mustard oil');
$defaultRobots = Setting::get('meta_robots_default', 'index, follow');
$defaultOgImage = Setting::get('og_image_default') ?: asset('assets/images/branding/header-logo-dark.svg');
$gaId = Setting::get('google_analytics_id');
$gtmId = Setting::get('google_tag_manager_id');
$twitterHandle = Setting::get('twitter_handle', '@legacyfood');
$canonicalBase = Setting::get('canonical_url_base');

$pageTitle = $meta_title ?? $defaultTitle;
$pageDescription = $meta_description ?? $defaultDesc;
$pageKeywords = $meta_keywords ?? $defaultKeywords;
$pageRobots = $meta_robots ?? $defaultRobots;
$pageOgImage = $og_image ?? $defaultOgImage;

$requestPath = $_SERVER['REQUEST_URI'] ?? '/';
$canonicalUrl = $canonicalBase ? rtrim($canonicalBase, '/') . $requestPath : url($requestPath);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="<?= e($pageKeywords) ?>">
    <meta name="robots" content="<?= e($pageRobots) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= isset($product) ? 'product' : 'website' ?>">
    <meta property="og:site_name" content="<?= e(Setting::get('site_name', 'Legacy Food')) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:image" content="<?= e($pageOgImage) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= e($pageOgImage) ?>">
    <?php if ($twitterHandle): ?>
        <meta name="twitter:site" content="<?= e($twitterHandle) ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/branding/legacy-seal.svg') ?>">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "<?= url('/') ?>#organization",
          "name": "<?= e(Setting::get('site_name', 'Legacy Food')) ?>",
          "url": "<?= url('/') ?>",
          "logo": "<?= asset('assets/images/branding/header-logo-dark.svg') ?>",
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?= e(Setting::get('contact_phone', '+91 98765 43210')) ?>",
            "contactType": "customer service",
            "email": "<?= e(Setting::get('contact_email', 'care@legacyfood.in')) ?>"
          }
        },
        {
          "@type": "WebSite",
          "@id": "<?= url('/') ?>#website",
          "url": "<?= url('/') ?>",
          "name": "<?= e(Setting::get('site_name', 'Legacy Food')) ?>",
          "publisher": { "@id": "<?= url('/') ?>#organization" }
        }
        <?php if (isset($product)): ?>
        ,{
          "@type": "Product",
          "name": "<?= e($product['name']) ?>",
          "image": "<?= e($product['featured_image']) ?>",
          "description": "<?= e($product['short_description'] ?? '') ?>",
          "sku": "<?= e($product['sku']) ?>",
          "offers": {
            "@type": "Offer",
            "priceCurrency": "INR",
            "price": "<?= (float)($product['sale_price'] ?: $product['base_price']) ?>",
            "availability": "<?= ((int)$product['stock_quantity'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
            "url": "<?= url('product/' . $product['slug']) ?>"
          }
        }
        <?php endif; ?>
      ]
    }
    </script>

    <?php if (!empty($gtmId)): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= e($gtmId) ?>');</script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>

    <?php if (!empty($gaId)): ?>
    <!-- Google Analytics 4 (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= e($gaId) ?>');
    </script>
    <?php endif; ?>

    <!-- Tailwind CSS CDN with Custom Theme Extensions -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        legacy: {
                            gold: '#bc944c',
                            'gold-soft': '#d8bd99',
                            'gold-light': '#f3deb6',
                            green: '#0e2319',
                            'green-deep': '#07160d',
                            'green-dark': '#08130c',
                            'green-light': '#142d1c',
                            cream: '#fdf6e3',
                            'cream-soft': '#f3ead3',
                        }
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                        body: ['"Plus Jakarta Sans"', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/custom.css') ?>?v=2.2">

    <!-- Expose global JS variables -->
    <script>
        window.APP_URL = "<?= url('/') ?>";
        window.CSRF_TOKEN = "<?= csrf_token() ?>";
    </script>
</head>
<body class="bg-[#faf8f5] text-[#1c1917] antialiased selection:bg-[#bc944c] selection:text-[#07160d] min-h-screen flex flex-col justify-between relative">
    <!-- Noise Texture Overlay -->
    <div class="noise" aria-hidden="true"></div>

    <!-- Ambient Background Seals -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -right-20 top-10 hidden md:block h-[600px] w-[600px] opacity-[0.05]">
            <img src="<?= asset('assets/images/branding/legacy-seal.svg') ?>" class="w-full h-full" alt="">
        </div>
        <div class="absolute -left-20 bottom-10 hidden md:block h-[500px] w-[500px] opacity-[0.04]">
            <img src="<?= asset('assets/images/branding/legacy-seal.svg') ?>" class="w-full h-full" alt="">
        </div>
    </div>

    <!-- Top Luxury Header -->
    <?php include __DIR__ . '/../components/header.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 pt-24 md:pt-28">
        <div class="container-x">
            <?php include __DIR__ . '/../components/alerts.php'; ?>
        </div>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

    <!-- Slide-out Cart Drawer -->
    <?php include __DIR__ . '/../components/cart_drawer.php'; ?>

    <!-- Floating WhatsApp Button -->
    <?php include __DIR__ . '/../components/whatsapp_button.php'; ?>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <!-- Main Script -->
    <script src="<?= asset('assets/js/app.js') ?>?v=2.2"></script>
</body>
</html>
