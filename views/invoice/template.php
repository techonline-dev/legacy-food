<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Invoice') ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            margin: 0;
            padding: 40px;
            color: #1f2937;
            background: #f9fafb;
            font-size: 13px;
            line-height: 1.5;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 48px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #bc944c;
            padding-bottom: 24px;
            margin-bottom: 28px;
        }
        .logo-text {
            font-family: Georgia, serif;
            font-size: 24px;
            font-weight: bold;
            color: #07160d;
            letter-spacing: 2px;
        }
        .logo-sub {
            font-size: 10px;
            color: #bc944c;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .inv-title {
            text-align: right;
        }
        .inv-title h1 {
            margin: 0;
            font-size: 22px;
            color: #111827;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .inv-title p {
            margin: 4px 0 0 0;
            color: #6b7280;
            font-size: 12px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }
        .box-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            color: #bc944c;
            margin-bottom: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        th {
            background: #f7f1e1;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #07160d;
            border-bottom: 1px solid #e5dcc7;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        .totals-table {
            width: 320px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 12px;
            border: none;
        }
        .totals-table .grand-total td {
            font-size: 16px;
            font-weight: bold;
            color: #07160d;
            border-top: 2px solid #bc944c;
            padding-top: 10px;
        }
        .seal-stamp {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #6b7280;
        }
        .print-btn {
            background: #bc944c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; align-items: center;">
        <a href="<?= url('account/orders') ?>" style="color: #4b5563; text-decoration: none; font-size: 12px;">← Back to Orders</a>
        <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="header-row">
            <div>
                <div class="logo-text">LEGACY FOOD</div>
                <div class="logo-sub">Crafted in South India</div>
                <p style="margin: 8px 0 0 0; color: #4b5563; font-size: 11px; line-height: 1.5;">
                    <?= nl2br(e(setting('address', '#286, 4th Cross, 8th Main, 4th Phase, Dollars Colony, Bangalore 560078, Karnataka, India'))) ?><br>
                    <strong>GSTIN:</strong> <?= e(setting('gst_number', '29ABCDE1234F1Z5')) ?> | <?= e(setting('contact_email', 'contact@legacyfood.in')) ?>
                </p>
            </div>
            <div class="inv-title">
                <h1>TAX INVOICE</h1>
                <p><strong>Invoice #:</strong> <?= e($order['invoice']['invoice_number'] ?? ('INV-' . $order['id'])) ?></p>
                <p><strong>Date:</strong> <?= date('d M Y', strtotime($order['invoice']['invoice_date'] ?? $order['created_at'])) ?></p>
                <p><strong>Order #:</strong> <?= e($order['order_number']) ?></p>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">
            <div>
                <div class="box-title">Billed & Shipped To:</div>
                <strong><?= e($order['shipping_address']['full_name']) ?></strong><br>
                <?= e($order['shipping_address']['address_line1']) ?><br>
                <?php if (!empty($order['shipping_address']['address_line2'])): ?>
                    <?= e($order['shipping_address']['address_line2']) ?><br>
                <?php endif; ?>
                <?= e($order['shipping_address']['city']) ?>, <?= e($order['shipping_address']['state']) ?> - <?= e($order['shipping_address']['postal_code']) ?><br>
                Phone: <?= e($order['shipping_address']['phone']) ?><br>
                Email: <?= e($order['guest_email'] ?? '') ?>
            </div>
            <div>
                <div class="box-title">Payment & Shipping Info:</div>
                <strong>Payment Method:</strong> <?= strtoupper($order['payment_method']) ?><br>
                <strong>Payment Status:</strong> <?= ucfirst($order['payment_status']) ?><br>
                <strong>Order Status:</strong> <?= ucfirst($order['status']) ?><br>
                <?php if (!empty($order['tracking_number'])): ?>
                    <strong>Courier:</strong> <?= e($order['shipping_courier']) ?><br>
                    <strong>Tracking #:</strong> <?= e($order['tracking_number']) ?><br>
                <?php endif; ?>
            </div>
        </div>

        <!-- Table of Items -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Item Description</th>
                    <th style="width: 15%;">SKU</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 12%; text-align: right;">Unit Price</th>
                    <th style="width: 13%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <strong><?= e($item['product_name']) ?></strong>
                            <?php if (!empty($item['variant_name'])): ?>
                                <span style="color: #6b7280; font-size: 11px;">(<?= e($item['variant_name']) ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-family: monospace; font-size: 11px;"><?= e($item['sku']) ?></td>
                        <td style="text-align: center;"><?= $item['quantity'] ?></td>
                        <td style="text-align: right;"><?= currency_format($item['price']) ?></td>
                        <td style="text-align: right; font-weight: 600;"><?= currency_format($item['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td style="text-align: right;">Subtotal:</td>
                <td style="text-align: right; font-weight: 600;"><?= currency_format($order['subtotal']) ?></td>
            </tr>
            <?php if ($order['discount_amount'] > 0): ?>
                <tr>
                    <td style="text-align: right; color: #047857;">Coupon Discount <?= !empty($order['coupon_code']) ? '(' . e($order['coupon_code']) . ')' : '' ?>:</td>
                    <td style="text-align: right; color: #047857; font-weight: 600;">-<?= currency_format($order['discount_amount']) ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td style="text-align: right;">Shipping Charges:</td>
                <td style="text-align: right;"><?= $order['shipping_amount'] > 0 ? currency_format($order['shipping_amount']) : 'FREE' ?></td>
            </tr>
            <?php 
                $gstRate = (float)setting('gst_percentage', 5.00);
                $isInclusive = setting('tax_inclusive', '1') == '1';
                $halfRate = round($gstRate / 2, 2);
            ?>
            <tr>
                <td style="text-align: right;">CGST (<?= $halfRate ?>%) + SGST (<?= $halfRate ?>%) [<?= $gstRate ?>%<?= $isInclusive ? ' Incl.' : '' ?>]:</td>
                <td style="text-align: right;"><?= currency_format($order['tax_amount']) ?></td>
            </tr>
            <tr class="grand-total">
                <td style="text-align: right;">Grand Total:</td>
                <td style="text-align: right;"><?= currency_format($order['total_amount']) ?></td>
            </tr>
        </table>

        <!-- Footer Seal -->
        <div class="seal-stamp">
            <div>
                <strong>Thank you for supporting pure South Indian dairy craftsmanship!</strong><br>
                This is a computer generated invoice and does not require physical signature.
            </div>
            <div style="text-align: right;">
                <span style="display: inline-block; border: 2px dashed #bc944c; padding: 6px 14px; border-radius: 8px; color: #bc944c; font-weight: bold; text-transform: uppercase;">
                    PAID / CERTIFIED PURE
                </span>
            </div>
        </div>
    </div>
</body>
</html>
