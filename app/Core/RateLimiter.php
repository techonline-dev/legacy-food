<?php

namespace App\Core;

class RateLimiter {
    public static function attempt(string $key, int $maxAttempts, int $windowSeconds): bool {
        $path = self::path($key);
        if ($path === null) {
            return true;
        }
        $handle = @fopen($path, 'c+');
        if ($handle === false) {
            return true;
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                return true;
            }

            $raw = stream_get_contents($handle);
            $data = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
            $now = time();

            if (!is_array($data) || (int)($data['reset'] ?? 0) <= $now) {
                $data = ['count' => 0, 'reset' => $now + $windowSeconds];
            }

            if ((int)$data['count'] >= $maxAttempts) {
                return false;
            }

            $data['count'] = (int)$data['count'] + 1;
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data));
            fflush($handle);

            return true;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    public static function clear(string $key): void {
        $path = self::path($key, false);
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }

    private static function path(string $key, bool $createDir = true): ?string {
        $dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'legacyfood_ratelimit';
        if (!is_dir($dir)) {
            if (!$createDir) {
                return null;
            }
            @mkdir($dir, 0700, true);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return null;
        }
        return $dir . DIRECTORY_SEPARATOR . hash('sha256', $key) . '.json';
    }
}
