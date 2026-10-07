<?php

namespace App\Core;

class Mailer {
    public static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool {
        $fromEmail = config('mail.from.address', 'contact@legacyfood.in');
        $fromName = config('mail.from.name', 'Legacy Food');

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            "From: {$fromName} <{$fromEmail}>",
            "Reply-To: {$fromEmail}",
            'X-Mailer: PHP/' . phpversion()
        ];

        // If SMTP credentials configured and stream socket available, attempt SMTP
        $host = config('mail.host');
        $username = config('mail.username');
        $password = config('mail.password');

        if (!empty($host) && !empty($username) && !empty($password) && function_exists('fsockopen')) {
            try {
                return self::sendSmtp($toEmail, $subject, $htmlBody, $fromEmail, $fromName);
            } catch (\Throwable $t) {
                error_log("SMTP Error: " . $t->getMessage() . ". Falling back to mail()");
            }
        }

        // Standard mail() fallback
        return @mail($toEmail, $subject, $htmlBody, implode("\r\n", $headers));
    }

    private static function sendSmtp(string $to, string $subject, string $body, string $fromEmail, string $fromName): bool {
        $host = config('mail.host');
        $port = (int)config('mail.port', 587);
        $user = config('mail.username');
        $pass = config('mail.password');

        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) return false;

        $read = function() use ($socket) {
            $data = '';
            while ($str = fgets($socket, 515)) {
                $data .= $str;
                if (substr($str, 3, 1) === ' ') break;
            }
            return $data;
        };

        $write = function(string $cmd) use ($socket) {
            fputs($socket, $cmd . "\r\n");
        };

        $read();
        $write("EHLO " . ($host ?: 'localhost'));
        $read();
        $write("STARTTLS");
        $read();
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        $write("EHLO " . ($host ?: 'localhost'));
        $read();
        $write("AUTH LOGIN");
        $read();
        $write(base64_encode($user));
        $read();
        $write(base64_encode($pass));
        $read();
        $write("MAIL FROM: <{$fromEmail}>");
        $read();
        $write("RCPT TO: <{$to}>");
        $read();
        $write("DATA");
        $read();

        $headers = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n" .
                   "To: <{$to}>\r\n" .
                   "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n" .
                   "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n\r\n";

        $write($headers . $body . "\r\n.");
        $read();
        $write("QUIT");
        fclose($socket);
        return true;
    }

    public static function renderEmail(string $title, string $contentHtml, ?string $ctaText = null, ?string $ctaUrl = null): string {
        $logo = url('assets/images/branding/header-logo-dark.svg');
        $primaryGold = '#bc944c';
        $darkGreen = '#07160d';
        $siteUrl = url('/');

        $ctaButton = '';
        if ($ctaText && $ctaUrl) {
            $ctaButton = "
            <table role='presentation' border='0' cellpadding='0' cellspacing='0' style='margin: 28px 0;'>
                <tr>
                    <td align='center' style='border-radius: 8px; background-color: {$primaryGold};'>
                        <a href='{$ctaUrl}' target='_blank' style='display: inline-block; padding: 14px 28px; font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em;'>
                            {$ctaText}
                        </a>
                    </td>
                </tr>
            </table>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>{$title}</title>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 0; background-color: #fdf6e3; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #2c3e2e;'>
            <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color: #fdf6e3; padding: 30px 10px;'>
                <tr>
                    <td align='center'>
                        <table role='presentation' width='600' border='0' cellspacing='0' cellpadding='0' style='max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid rgba(188, 148, 76, 0.25); box-shadow: 0 10px 30px rgba(7, 22, 13, 0.06);'>
                            <!-- Header -->
                            <tr>
                                <td align='center' style='background-color: {$darkGreen}; padding: 32px 20px; border-bottom: 3px solid {$primaryGold};'>
                                    <h1 style='margin: 0; font-family: Georgia, serif; font-size: 26px; color: #fdf6e3; letter-spacing: 0.1em; text-transform: uppercase;'>
                                        LEGACY FOOD
                                    </h1>
                                    <div style='font-size: 12px; color: {$primaryGold}; letter-spacing: 0.2em; text-transform: uppercase; margin-top: 4px;'>
                                        Crafted in South India · Pure Heritage
                                    </div>
                                </td>
                            </tr>
                            <!-- Main Content -->
                            <tr>
                                <td style='padding: 36px 32px 24px 32px; font-size: 15px; line-height: 1.6;'>
                                    <h2 style='font-family: Georgia, serif; color: {$darkGreen}; margin-top: 0; font-size: 22px; font-weight: 600;'>
                                        {$title}
                                    </h2>
                                    {$contentHtml}
                                    {$ctaButton}
                                </td>
                            </tr>
                            <!-- Footer -->
                            <tr>
                                <td style='background-color: #f7f1e1; padding: 24px 32px; border-top: 1px solid rgba(188, 148, 76, 0.2); font-size: 12px; color: #6d6b63; line-height: 1.5;'>
                                    <p style='margin: 0 0 8px 0; font-weight: 600; color: {$darkGreen};'>Legacy Food</p>
                                    <p style='margin: 0 0 12px 0;'>#286, 4th Cross, 8th Main, Dollars Colony, Bangalore 560078 | WhatsApp: +91 98452 79936</p>
                                    <p style='margin: 0; font-size: 11px; color: #9c988e;'>© " . date('Y') . " Legacy Ghee. Pure, Traditionally Churned & Lab Certified.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";
    }

    public static function sendOrderConfirmation(array $order, array $items): bool {
        $title = "Order Confirmed: #" . $order['order_number'];
        $itemsHtml = "<table width='100%' border='0' cellspacing='0' cellpadding='8' style='border-collapse: collapse; margin-top: 16px; border: 1px solid #e5dcc7;'>
            <tr style='background-color: #f7f1e1; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;'>
                <th align='left' style='padding: 10px; border-bottom: 1px solid #e5dcc7;'>Item</th>
                <th align='center' style='padding: 10px; border-bottom: 1px solid #e5dcc7;'>Qty</th>
                <th align='right' style='padding: 10px; border-bottom: 1px solid #e5dcc7;'>Price</th>
            </tr>";

        foreach ($items as $item) {
            $variant = !empty($item['variant_name']) ? " (" . e($item['variant_name']) . ")" : "";
            $itemsHtml .= "<tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><strong>" . e($item['product_name']) . "</strong>{$variant}</td>
                <td align='center' style='padding: 10px; border-bottom: 1px solid #eee;'>" . (int)$item['quantity'] . "</td>
                <td align='right' style='padding: 10px; border-bottom: 1px solid #eee;'>" . currency_format($item['total']) . "</td>
            </tr>";
        }

        $itemsHtml .= "
            <tr><td colspan='2' align='right' style='padding: 8px 10px;'>Subtotal:</td><td align='right' style='padding: 8px 10px;'>" . currency_format($order['subtotal']) . "</td></tr>";
        if ($order['discount_amount'] > 0) {
            $itemsHtml .= "<tr><td colspan='2' align='right' style='padding: 8px 10px; color: #097545;'>Discount:</td><td align='right' style='padding: 8px 10px; color: #097545;'>-" . currency_format($order['discount_amount']) . "</td></tr>";
        }
        $itemsHtml .= "
            <tr><td colspan='2' align='right' style='padding: 8px 10px;'>Shipping:</td><td align='right' style='padding: 8px 10px;'>" . ($order['shipping_amount'] > 0 ? currency_format($order['shipping_amount']) : 'FREE') . "</td></tr>
            <tr style='font-size: 16px; font-weight: bold; background-color: #fdf6e3;'><td colspan='2' align='right' style='padding: 12px 10px; border-top: 2px solid #bc944c;'>Grand Total:</td><td align='right' style='padding: 12px 10px; border-top: 2px solid #bc944c; color: #07160d;'>" . currency_format($order['total_amount']) . "</td></tr>
        </table>";

        $body = "<p>Dear <strong>" . e($order['guest_name'] ?? 'Valued Customer') . "</strong>,</p>
        <p>Thank you for choosing Legacy Food! We have received your order and our artisan kitchen is preparing your pure heritage ghee and oils with the utmost care.</p>
        <div style='background-color: #faf5eb; border-left: 4px solid #bc944c; padding: 14px 18px; margin: 20px 0;'>
            <strong>Order Number:</strong> " . e($order['order_number']) . "<br>
            <strong>Payment Method:</strong> " . strtoupper($order['payment_method']) . " (" . ucfirst($order['payment_status']) . ")<br>
            <strong>Date:</strong> " . date('d M Y, h:i A') . "
        </div>
        {$itemsHtml}
        <p style='margin-top: 24px;'>You can track your order status anytime using the button below:</p>";

        $html = self::renderEmail("Order Confirmed: #" . $order['order_number'], $body, "View Your Order", url('account/order/' . $order['id']));
        
        $customerEmail = $order['guest_email'] ?? '';
        return self::send($customerEmail, $order['guest_name'] ?? 'Customer', $title, $html);
    }
}
