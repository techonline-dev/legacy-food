<?php

namespace App\Core;

class Razorpay {
    public static function configured(): bool {
        return razorpay_key_id() !== '' && razorpay_key_secret() !== '';
    }

    public static function createOrder(int $amountPaise, string $receipt, array $notes = []): array {
        if (!self::configured()) {
            throw new \RuntimeException('Payment gateway is not configured.');
        }

        $payload = json_encode([
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
            'notes' => $notes,
        ]);

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_USERPWD => razorpay_key_id() . ':' . razorpay_key_secret(),
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $status < 200 || $status >= 300) {
            error_log('Razorpay order creation failed: ' . ($error ?: ('HTTP ' . $status . ' ' . (string)$response)));
            throw new \RuntimeException('Unable to initiate online payment. Please try again.');
        }

        $data = json_decode((string)$response, true);
        if (!is_array($data) || empty($data['id'])) {
            throw new \RuntimeException('Invalid payment gateway response.');
        }

        return $data;
    }

    public static function verifySignature(string $gatewayOrderId, string $paymentId, string $signature): bool {
        $secret = razorpay_key_secret();
        if ($secret === '' || $gatewayOrderId === '' || $paymentId === '' || $signature === '') {
            return false;
        }
        $expected = hash_hmac('sha256', $gatewayOrderId . '|' . $paymentId, $secret);
        return hash_equals($expected, $signature);
    }
}
