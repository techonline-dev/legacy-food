-- ====================================================================
-- Legacy Food Database Seeds
-- Real Brand Data from https://www.legacyfood.in/
-- ====================================================================

-- 1. ROLES & PERMISSIONS
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Super Admin', 'super-admin', 'Full access to all system features'),
(2, 'Manager', 'manager', 'Can manage products, orders, and content'),
(3, 'Order Manager', 'order-manager', 'Can manage and process orders');

-- Default Super Admin (Password: Admin@LegacyFood2026)
-- Hash generated via password_hash('Admin@LegacyFood2026', PASSWORD_BCRYPT)
INSERT INTO `admins` (`id`, `role_id`, `name`, `email`, `password`, `phone`, `status`) VALUES
(1, 1, 'Legacy Food Administrator', 'admin@legacyfood.in', '$2y$12$fT7jP8r9wLwT1h5ZkK1IseGqA2BqjV8vLq7w6o9xM0iE2eLKSKph.', '+91 81234 50509', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 2. PRODUCT CATEGORIES
INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `is_active`) VALUES
(1, 'Ghee', 'ghee', 'Pure heritage ghee hand-churned from farm-fresh South Indian dairy butter. Golden, aromatic, granular, and 100% lab certified.', 'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640', 1, 1),
(2, 'Cold-Pressed Oils', 'cold-pressed-oils', 'Traditional wood-pressed oils extracted at low temperatures without heat, chemicals, or refinement to preserve vital micronutrients.', 'https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640', 2, 1);

-- 3. PRODUCTS
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `short_description`, `description`, `benefits`, `ingredients`, `specifications`, `featured_image`, `base_price`, `sale_price`, `stock_quantity`, `is_featured`, `is_bestseller`, `status`, `rating_cache`, `review_count_cache`, `accent_color`) VALUES
(
    1,
    1,
    'Cow Ghee',
    'cow-ghee',
    'LF-GHEE-COW-01',
    'Hand-churned from farm-fresh cow butter, sourced directly from South Indian farmers. Golden, granular, and rich in aroma.',
    '<p>LEGACY Cow Ghee is slow-cooked to golden perfection in small batches using traditional South Indian methods. Sourced directly from grassroots farmers across Karnataka and Tamil Nadu, we churn fresh cow butter rather than industrial cream, locking in the natural sweetness and nutty aroma of authentic homemade ghee.</p><p>Every jar undergoes rigorous GC (Gaschromat) testing at government-recognised NABL accredited laboratories to verify zero adulteration, zero preservatives, and absolute purity. Packed in premium UV-safe glass jars to retain peak freshness from our churn to your table.</p>',
    'Naturally rich in fat-soluble vitamins A, D, E, and K; contains butyric acid supporting gut health; natural granular texture confirms high-purity milk fat; high smoke point of 250°C ideal for everyday Indian cooking and roasting.',
    '100% Pure Clarified Cow Butterfat (Makkhan). No additives, colours, or synthetic flavours.',
    '{"Extraction Method": "Traditional slow-boil churning", "Packaging": "Airtight Glass Jar with Heritage Gold Lid", "Shelf Life": "9 Months from packaging", "Origin": "South India", "Certification": "NABL Accredited Lab & GC Tested"}',
    'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640',
    440.00,
    440.00,
    150,
    1,
    1,
    'published',
    4.9,
    48,
    '#bc944c'
),
(
    2,
    1,
    'Buffalo Ghee',
    'buffalo-ghee',
    'LF-GHEE-BUF-02',
    'Made from pure buffalo butter — rich, aromatic, and ideal for festive cooking and everyday indulgence.',
    '<p>Experience the decadent richness of LEGACY Buffalo Ghee. Crafted strictly from grass-fed buffalo milk butter, it features a creamy ivory texture and deep caramelized fragrance prized in traditional Indian sweet making and festive dishes.</p><p>With a naturally higher density of healthy fats and calcium, our buffalo ghee delivers wholesome nourishment and an irreplaceable depth of flavour. Every batch is NABL certified and free from industrial additives or palm fat blends.</p>',
    'High calcium and mineral content; velvety texture with rich fragrance; elevates flavour in biryanis, parathas, and traditional sweets; helps maintain stamina and energy.',
    '100% Pure Buffalo Butterfat. Free from artificial preservatives and emulsifiers.',
    '{"Extraction Method": "Bilona small-batch churning", "Packaging": "Airtight Glass Jar", "Shelf Life": "9 Months", "Origin": "South India", "Certification": "NABL Accredited & GC Tested"}',
    'https://cdn.sanity.io/images/dwps51kj/production/48fed0f03d52c668a46f7037a87f7fc032f66ca2-1080x1080.png?w=640',
    490.00,
    490.00,
    120,
    1,
    1,
    'published',
    4.8,
    32,
    '#097545'
),
(
    3,
    2,
    'Cold Pressed Groundnut Oil',
    'cold-pressed-groundnut-oil',
    'LF-OIL-GNUT-03',
    'Wood-pressed groundnut oil — nutty, wholesome and perfect for daily cooking.',
    '<p>Extracted the ancestral way using heavy wooden pestles (Vaagai Mara Chekku) running at low RPM, LEGACY Groundnut Oil retains all the inherent vitamin E, plant phytosterols, and delicate peanut flavours that high-heat industrial processing destroys.</p><p>Zero heat, zero hexane solvents, and zero chemical bleaching. A heart-healthy culinary cornerstone suitable for deep-frying, sauteing, and tempering.</p>',
    'Abundant in monounsaturated fatty acids (MUFA); rich natural source of Vitamin E and antioxidants; high smoke point for crisp frying; zero cholesterol.',
    '100% Pure Wood-Pressed Groundnuts (Peanuts). Unrefined, unbleached, and unheated.',
    '{"Method": "Traditional Mara Chekku Wood Pressed", "Temperature": "Extracted below 45°C", "Packaging": "Food-grade UV resistant bottle", "Shelf Life": "6 Months", "Certification": "Lab Certified 100% Pure"}',
    'https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640',
    390.00,
    390.00,
    100,
    1,
    1,
    'published',
    4.9,
    26,
    '#bc944c'
),
(
    4,
    2,
    'Cold Pressed Coconut Oil',
    'cold-pressed-coconut-oil',
    'LF-OIL-COCO-04',
    'Wood-pressed from sun-dried copra — fragrant, pure and unrefined.',
    '<p>LEGACY Cold Pressed Coconut Oil is pressed strictly from grade-A, sun-dried sulphur-free coconut copra harvested across South Indian coastal groves. Crystal clear when melted and glistening snowy white when solid, it carries an unmistakable heavenly aroma.</p><p>Packed with Lauric Acid and Medium Chain Triglycerides (MCTs) that promote sustained physical energy and immune defense, this multi-purpose oil is equally revered in authentic coastal cuisine, baking, and holistic Ayurvedic hair care.</p>',
    'Contains over 50% Lauric Acid; rapid metabolic energy from natural MCTs; supports strong immunity and microbial defense; unrefined goodness for hair and skin.',
    '100% Pure Wood-Pressed Coconut Copra.',
    '{"Method": "Traditional Mara Chekku", "Processing": "Unrefined, Non-deodorized, Sulphur-free", "Packaging": "Recyclable Bottle", "Shelf Life": "12 Months", "Certification": "NABL Lab Tested"}',
    'https://cdn.sanity.io/images/dwps51kj/production/b7b7a06feacc8ae1984f6bc2348aa779b8654136-810x1080.png?w=640',
    490.00,
    490.00,
    90,
    1,
    1,
    'published',
    5.0,
    39,
    '#097545'
),
(
    5,
    2,
    'Cold Pressed Sesame Oil',
    'cold-pressed-sesame-oil',
    'LF-OIL-SES-05',
    'Traditional wood-pressed gingelly oil — aromatic and rich in natural goodness.',
    '<p>Honoring centuries of Tamil and South Indian tradition, our gingelly oil is wood-pressed from naturally cultivated sesame seeds paired with authentic palm jaggery to moderate bitterness and enhance its golden warmth.</p><p>Teeming with Sesamol, Sesamolin, and restorative minerals, LEGACY Sesame Oil forms the soul of authentic pickles, podis, rasam tempering, and traditional oil-pulling rituals.</p>',
    'Powerful natural Sesamol antioxidants protect cellular health; supports optimal blood pressure and heart wellness; ideal for authentic South Indian curries and seasonings.',
    'Pure Sesame Seeds, Traditional Palm Jaggery.',
    '{"Method": "Wood Pressed Gingelly Chekku", "Processing": "Cold Pressed, Unrefined", "Packaging": "Seal-tight Bottle", "Shelf Life": "9 Months", "Certification": "100% Lab Verified"}',
    'https://cdn.sanity.io/images/dwps51kj/production/7ee80488f5f08c763cd7d52fa06dd9670069d5f3-810x1080.png?w=640',
    390.00,
    390.00,
    85,
    1,
    0,
    'published',
    4.8,
    19,
    '#d8bd99'
),
(
    6,
    2,
    'Cold Pressed Mustard Oil',
    'cold-pressed-mustard-oil',
    'LF-OIL-MUST-06',
    'Wood-pressed mustard oil — pungent, traditional and full of natural goodness.',
    '<p>Crafted from premium black mustard seeds crushed at ambient temperature, LEGACY Mustard Oil provides the bold, tingling pungency and distinctive golden sheen demanded by connoisseurs of traditional North and Eastern Indian cuisines.</p><p>Its high concentration of Alpha-Linolenic Acid (Omega-3) and natural allyl compounds stimulate appetite, boost digestive enzymes, and keep fried delicacies wonderfully crisp.</p>',
    'Contains heart-protective Omega-3 fatty acids; natural anti-inflammatory compounds; authentic sharp kick for mustard marinades and achars; aids digestion.',
    '100% Pure Black Mustard Seeds.',
    '{"Method": "Kachi Ghani Cold Pressed", "Packaging": "Tamper-evident bottle", "Shelf Life": "9 Months", "Origin": "India", "Certification": "NABL Lab Tested"}',
    'https://cdn.sanity.io/images/dwps51kj/production/128846a0f11e11e50e1e146552cea17484ccf217-810x1080.png?w=640',
    320.00,
    320.00,
    80,
    1,
    0,
    'published',
    4.7,
    15,
    '#bc944c'
);

-- 4. PRODUCT VARIANTS (Sizes & Prices from Reference Site)
INSERT INTO `product_variants` (`id`, `product_id`, `name`, `sku`, `price`, `sale_price`, `stock_quantity`, `weight_grams`, `is_default`, `image`) VALUES
-- Cow Ghee
(1, 1, '250 ml', 'LF-GHEE-COW-250ML', 440.00, 440.00, 50, 250, 1, 'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640'),
(2, 1, '500 ml', 'LF-GHEE-COW-500ML', 790.00, 790.00, 50, 500, 0, 'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640'),
(3, 1, '1 L', 'LF-GHEE-COW-1L', 1490.00, 1490.00, 50, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640'),

-- Buffalo Ghee
(4, 2, '250 ml', 'LF-GHEE-BUF-250ML', 490.00, 490.00, 40, 250, 1, 'https://cdn.sanity.io/images/dwps51kj/production/48fed0f03d52c668a46f7037a87f7fc032f66ca2-1080x1080.png?w=640'),
(5, 2, '500 ml', 'LF-GHEE-BUF-500ML', 890.00, 890.00, 40, 500, 0, 'https://cdn.sanity.io/images/dwps51kj/production/48fed0f03d52c668a46f7037a87f7fc032f66ca2-1080x1080.png?w=640'),
(6, 2, '1000 ml', 'LF-GHEE-BUF-1000ML', 1590.00, 1590.00, 40, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/48fed0f03d52c668a46f7037a87f7fc032f66ca2-1080x1080.png?w=640'),

-- Groundnut Oil
(7, 3, '500 ml', 'LF-OIL-GNUT-500ML', 390.00, 390.00, 50, 500, 1, 'https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640'),
(8, 3, '1 L', 'LF-OIL-GNUT-1L', 680.00, 680.00, 50, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640'),

-- Coconut Oil
(9, 4, '500 ml', 'LF-OIL-COCO-500ML', 490.00, 490.00, 45, 500, 1, 'https://cdn.sanity.io/images/dwps51kj/production/b7b7a06feacc8ae1984f6bc2348aa779b8654136-810x1080.png?w=640'),
(10, 4, '1 L', 'LF-OIL-COCO-1L', 940.00, 940.00, 45, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/b7b7a06feacc8ae1984f6bc2348aa779b8654136-810x1080.png?w=640'),

-- Sesame Oil
(11, 5, '500 ml', 'LF-OIL-SES-500ML', 390.00, 390.00, 40, 500, 1, 'https://cdn.sanity.io/images/dwps51kj/production/7ee80488f5f08c763cd7d52fa06dd9670069d5f3-810x1080.png?w=640'),
(12, 5, '1 L', 'LF-OIL-SES-1L', 790.00, 790.00, 45, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/7ee80488f5f08c763cd7d52fa06dd9670069d5f3-810x1080.png?w=640'),

-- Mustard Oil
(13, 6, '500 ml', 'LF-OIL-MUST-500ML', 320.00, 320.00, 40, 500, 1, 'https://cdn.sanity.io/images/dwps51kj/production/128846a0f11e11e50e1e146552cea17484ccf217-810x1080.png?w=640'),
(14, 6, '1 L', 'LF-OIL-MUST-1L', 540.00, 540.00, 40, 1000, 0, 'https://cdn.sanity.io/images/dwps51kj/production/128846a0f11e11e50e1e146552cea17484ccf217-810x1080.png?w=640');

-- 5. BANNERS
INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `mobile_image`, `cta_text`, `cta_url`, `type`, `sort_order`, `status`) VALUES
(1, 'Pure Heritage In Every Drop', 'Slow churned South Indian ghee made strictly from fresh farm butter. NABL certified and zero adulteration.', 'https://www.legacyfood.in/hero-desktop-1.png', 'https://www.legacyfood.in/hero-desktop-1.png', 'Shop Heritage Ghee', '/shop?category=ghee', 'hero', 1, 'active'),
(2, 'Traditional Wood Pressed Oils', 'Cold pressed from sun-dried seeds and coconuts. 0% chemicals, 100% wholesome nutrition.', 'https://www.legacyfood.in/hero-desktop-2.png', 'https://www.legacyfood.in/hero-desktop-2.png', 'Explore Oils', '/shop?category=cold-pressed-oils', 'hero', 2, 'active'),
(3, 'Festive Special: Pure Ghee Offer', 'Enjoy 10% instant off on your first order. Use code WELCOME10 at checkout.', 'https://www.legacyfood.in/Benefits.png', 'https://www.legacyfood.in/Benefits.png', 'Claim Discount', '/shop', 'promotional', 3, 'active');

-- 6. TESTIMONIALS (From Reference Site)
INSERT INTO `testimonials` (`id`, `customer_name`, `location`, `rating`, `review`, `sort_order`, `is_active`) VALUES
(1, 'Priya Venkatesh', 'Bengaluru', 5, 'I\'ve tried many ghees but this is the one I keep coming back to. The aroma when it hits the pan is unlike anything else.', 1, 1),
(2, 'Srinivasan Raman', 'Chennai', 5, 'Pure, granular, exactly how my mother used to describe real ghee. The taste is incomparable.', 2, 1),
(3, 'Ananya Reddy', 'Hyderabad', 5, 'Once I switched to Legacy, I couldn\'t go back. The difference in flavour is night and day. Even their wood-pressed coconut oil is unmatched.', 3, 1),
(4, 'Deepak Nair', 'Kochi', 5, 'You can immediately tell by the golden granular texture that this is authentic farm churned butter ghee, not industrialized oil blends.', 4, 1);

-- 7. FAQS (From Reference Site)
INSERT INTO `faqs` (`id`, `category`, `question`, `answer`, `sort_order`, `is_active`) VALUES
(1, 'Ghee', 'What makes Legacy ghee different from supermarket ghee?', 'Legacy ghee is made from farm butter sourced directly from South Indian farmers — not reconstituted cream or imported fat. Every batch is GC tested and NABL certified. Most supermarket ghee doesn\'t go through this level of verification.', 1, 1),
(2, 'Ghee', 'Is this the same as A2 or bilona ghee?', 'Legacy ghee is made from pure farm butter, churned traditionally and lab-tested for purity. Honest ghee — made right, tested right, with no synthetic gimmicks.', 2, 1),
(3, 'Quality', 'Why is there granulation in the ghee?', 'Granulation is a natural characteristic of genuine, unadulterated cow ghee. It\'s a good sign — it means no hydrogenated fats or additives have been mixed in.', 3, 1),
(4, 'Storage', 'How should I store Legacy ghee?', 'Keep it in a cool, dry place away from direct sunlight. No refrigeration needed. Always use a dry spoon to maintain freshness.', 4, 1),
(5, 'Oils', 'Are your oils tested too?', 'Yes. All our cold-pressed oils are tested for purity at NABL-accredited laboratories. Wood-pressing preserves the natural nutrients and flavour that heat-processed oils lose.', 5, 1),
(6, 'Orders', 'How do I place an order?', 'You can order directly through our secure online checkout with UPI, Card, Net Banking or Cash on Delivery, or tap the WhatsApp button to speak directly with our team.', 6, 1);

-- 8. CMS PAGES
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_active`) VALUES
(
    1,
    'About Us',
    'about-us',
    '<h2>Rooted in Purity and South Indian Heritage</h2><p>LEGACY was born from a simple belief — that the best ghee in the world is still made in South Indian kitchens, by people who treat it as more than just a product.</p><p>We work directly with dairy farmers across South India, sourcing fresh butter before it ever sees a middleman. What goes into our ghee is as fresh as it gets.</p><h3>Crafted the Way Nature Intended</h3><p>We source farm butter directly from South Indian farmers, churn in small batches, test every jar at NABL-accredited labs, and pack in glass because your ghee deserves nothing less.</p><p>The art of churning butter and making ghee has been passed down through generations here. The standards have never changed. This is not industrial ghee. It never was.</p><h3>Key Milestones</h3><ul><li><strong>Est. 2011:</strong> Founded as an artisanal family dairy kitchen.</li><li><strong>50,000+ Jars:</strong> Delivered to happy families across India.</li><li><strong>4.5★ Average:</strong> Across all verified customer reviews.</li><li><strong>100% NABL Accredited:</strong> Gaschromat (GC) tested for absolute purity.</li></ul>',
    'About Legacy Food | South Indian Heritage Ghee & Pure Oils',
    'Discover the story of Legacy Food. Rooted in purity, small batch farm butter churning, and NABL lab certification since 2011.',
    1
),
(
    2,
    'Privacy Policy',
    'privacy-policy',
    '<h2>Privacy Policy</h2><p>At Legacy Food, accessible from legacyfood.in, one of our main priorities is the privacy of our visitors. This Privacy Policy document outlines the types of information collected and how we use it.</p><h3>Information We Collect</h3><p>We collect personal information such as your name, delivery address, email address, and phone number when you place an order or register an account. We do not store sensitive payment card credentials on our servers; transactions are encrypted and processed securely by RBI-compliant payment gateways.</p><h3>How We Use Your Data</h3><ul><li>To process, pack, and deliver your orders efficiently.</li><li>To provide real-time WhatsApp and SMS tracking updates.</li><li>To communicate crucial order notifications, invoices, and customer service responses.</li><li>To protect against fraud and ensure platform integrity.</li></ul>',
    'Privacy Policy | Legacy Food',
    'Read the Legacy Food privacy policy regarding data collection, order processing, and payment security.',
    1
),
(
    3,
    'Terms and Conditions',
    'terms-and-conditions',
    '<h2>Terms and Conditions</h2><p>Welcome to Legacy Food. By accessing or using our website and placing orders, you agree to comply with and be bound by the following terms.</p><h3>Orders and Pricing</h3><p>All prices listed on legacyfood.in are in Indian Rupees (INR) and include applicable taxes. We reserve the right to revise prices or cancel orders in cases of inventory discrepancies or typographical errors, in which case any amount paid will be refunded in full.</p><h3>Product Usage</h3><p>Our ghee and cold-pressed oils are natural food products. Variations in aroma or granulation across seasonal batches are natural markers of authentic artisanal preparation.</p>',
    'Terms and Conditions | Legacy Food',
    'Review the official terms and conditions for using the Legacy Food e-commerce platform.',
    1
),
(
    4,
    'Shipping Policy',
    'shipping-policy',
    '<h2>Shipping Policy</h2><p>We ship our pure heritage ghee and cold-pressed oils safely across India using reliable courier partners.</p><h3>Shipping Charges</h3><ul><li><strong>FREE Shipping:</strong> On all orders above ₹999.</li><li><strong>Standard Shipping:</strong> A flat ₹60 fee applies for orders below ₹999.</li></ul><h3>Delivery Timelines</h3><ul><li><strong>Bangalore & South India Metros:</strong> 2 - 3 business days.</li><li><strong>Rest of India:</strong> 4 - 6 business days.</li></ul><p>Every jar is safely packaged in multi-layer shockproof protective cushioning to ensure glass jars arrive undamaged.</p>',
    'Shipping Policy | Legacy Food',
    'Understand our delivery timeframes, free shipping thresholds, and packaging guarantees.',
    1
),
(
    5,
    'Return and Refund Policy',
    'return-refund-policy',
    '<h2>Return and Refund Policy</h2><p>Your satisfaction and peace of mind are our foremost priorities.</p><h3>Damaged or Broken in Transit</h3><p>Because our products are packaged in premium glass containers, in the rare event of transit breakage or packaging compromise, simply send us a photo within 48 hours of delivery at <a href="mailto:contact@legacyfood.in">contact@legacyfood.in</a> or on WhatsApp at +91 98452 79936. We will dispatch an immediate free replacement or process a 100% full refund.</p><h3>Cancellation</h3><p>Orders can be cancelled before dispatch directly from your customer dashboard or by notifying our support team.</p>',
    'Return & Refund Policy | Legacy Food',
    'Learn about our replacement guarantee and hassle-free return and refund policy.',
    1
);

-- 9. SETTINGS (General, Branding, Contacts, Payments, Taxes, Shipping)
INSERT INTO `settings` (`group_name`, `key_name`, `value`) VALUES
('general', 'site_name', 'Legacy Food'),
('general', 'site_tagline', 'Crafted in South India | Pure Heritage Ghee & Oils'),
('general', 'currency', 'INR'),
('general', 'currency_symbol', '₹'),
('branding', 'logo_light', '/assets/images/branding/header-logo-light.svg'),
('branding', 'logo_dark', '/assets/images/branding/header-logo-dark.svg'),
('branding', 'seal_svg', '/assets/images/branding/legacy-seal.svg'),
('branding', 'favicon', '/assets/images/branding/favicon.png'),
('contact', 'contact_email', 'Contact@legacyfood.in'),
('contact', 'contact_phone', '+91 81234 50509'),
('contact', 'whatsapp_number', '919845279936'),
('contact', 'whatsapp_message', 'Hi Legacy, I\'d like to know more about your heritage ghee.'),
('contact', 'address', '#286, 4th Cross, 8th Main, 4th Phase, Dollars Colony, Bangalore 560078'),
('contact', 'gst_number', '29ABCDE1234F1Z5'),
('social', 'instagram_url', 'https://www.instagram.com/legacy.ghee?utm_source=qr'),
('social', 'facebook_url', 'https://www.facebook.com/share/1E2eLKSKph/?mibextid=wwXIfr'),
('shipping', 'free_shipping_min', '999.00'),
('shipping', 'standard_shipping_charge', '60.00'),
('tax', 'gst_percentage', '5.00'),
('tax', 'tax_inclusive', '1'),
('payment', 'razorpay_enabled', '1'),
('payment', 'razorpay_key_id', 'rzp_test_legacyfood123'),
('payment', 'razorpay_key_secret', 'legacyfood_secret_demo'),
('payment', 'cod_enabled', '1'),
('payment', 'cod_extra_charge', '0.00'),
('seo', 'meta_title_default', 'LEGACY · Crafted in South India | Premium A2 Ghee & Cold Pressed Oils'),
('seo', 'meta_description_default', 'LEGACY — heritage ghee crafted in South India. Pure, traditionally churned butter ghee rooted in purity, craftsmanship and generations of expertise. NABL certified & GC tested.'),
('seo', 'meta_keywords_default', 'ghee, A2 ghee, bilona ghee, South India, premium ghee, Legacy ghee, cold pressed groundnut oil, wood pressed coconut oil');

-- 10. SHIPPING METHODS
INSERT INTO `shipping_methods` (`id`, `name`, `cost`, `free_threshold`, `estimated_days`, `is_active`) VALUES
(1, 'Standard Delivery (Surface)', 60.00, 999.00, '3-5 Business Days', 1),
(2, 'Express Priority Courier', 120.00, 1999.00, '1-2 Business Days', 1);

-- 11. COUPONS
INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_amount`, `max_discount_amount`, `usage_limit_total`, `usage_limit_per_user`, `is_active`) VALUES
(1, 'WELCOME10', 'percentage', 10.00, 400.00, 200.00, 1000, 1, 1),
(2, 'LEGACY100', 'fixed', 100.00, 1000.00, 100.00, 500, 1, 1),
(3, 'FESTIVE15', 'percentage', 15.00, 1500.00, 350.00, 300, 1, 1);

-- 12. INITIAL INVENTORY SYNC
INSERT INTO `inventory` (`product_id`, `variant_id`, `quantity`, `low_stock_alert`)
SELECT `product_id`, `id`, `stock_quantity`, 10 FROM `product_variants`;

-- 13. SAMPLE REVIEWS
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `title`, `review_text`, `is_verified_purchase`, `status`) VALUES
(1, 'Venkatesh Rao', 5, 'Real homemade aroma!', 'The graininess and golden colour reminded me of the ghee my grandmother churned in our native village in Karnataka. Splendid quality.', 1, 'approved'),
(2, 'Sunita Swaminathan', 5, 'Unbeatable for festival sweets', 'Used Legacy buffalo ghee for Mysore Pak and Diwali sweets this season. The richness was unmatched, guests could not stop praising the aroma.', 1, 'approved'),
(3, 'Arun Nair', 5, 'Authentic cold pressed flavour', 'You can clearly smell roasted groundnuts in this oil. None of that bland refined oil smell. Highly recommended.', 1, 'approved'),
(4, 'Deepa Krishnan', 5, 'Purest coconut oil I have found', 'Sweet fresh coconut fragrance without any chemical smell. Pure white when solidified in cool mornings. Love the packaging too!', 1, 'approved');
