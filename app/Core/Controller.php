<?php

namespace App\Core;

abstract class Controller {
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    protected function view(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        $this->response->view($viewPath, $data, $layout);
    }

    protected function json(array $data, int $code = 200): void {
        $this->response->json($data, $code);
    }

    protected function redirect(string $url): void {
        $this->response->redirect($url);
    }

    protected function validate(array $rules): array {
        $errors = [];
        $data = $this->request->all();

        foreach ($rules as $field => $ruleString) {
            $ruleList = explode('|', $ruleString);
            $val = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && (empty($val) && $val !== '0')) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                } elseif ($rule === 'email' && !empty($val) && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Please provide a valid email address.";
                } elseif (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (!empty($val) && strlen((string)$val) < $min) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters.";
                    }
                } elseif (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (!empty($val) && strlen((string)$val) > $max) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " may not exceed {$max} characters.";
                    }
                } elseif ($rule === 'numeric' && !empty($val) && !is_numeric($val)) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be numeric.";
                }
            }
        }

        return $errors;
    }

    protected function validateCsrf(): bool {
        $token = $this->request->input('_csrf_token') ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        return verify_csrf(is_string($token) ? $token : null);
    }

    /**
     * Handle single media upload (images & videos supported)
     */
    protected function uploadMediaFile(array $file, string $folder = 'products', string $prefix = 'media', int $maxSizeMb = 100): ?array {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        $maxBytes = $maxSizeMb * 1024 * 1024;
        if (($file['size'] ?? 0) <= 0 || $file['size'] > $maxBytes) {
            return null;
        }
        if (!is_uploaded_file((string)$file['tmp_name'])) {
            return null;
        }

        $imageMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
        ];

        $videoMimes = [
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/ogg' => 'ogg',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-matroska' => 'mkv',
        ];

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file((string)$file['tmp_name']);
        if (!is_string($mime)) {
            return null;
        }

        $isImage = isset($imageMimes[$mime]);
        $isVideo = isset($videoMimes[$mime]);

        if (!$isImage && !$isVideo) {
            $origExt = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
            if (in_array($origExt, ['mp4', 'webm', 'ogg', 'mov', 'm4v'], true)) {
                $isVideo = true;
                $ext = $origExt;
            } elseif (in_array($origExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $isImage = true;
                $ext = $origExt === 'jpeg' ? 'jpg' : $origExt;
            } else {
                return null;
            }
        } else {
            $ext = $isImage ? $imageMimes[$mime] : $videoMimes[$mime];
        }

        $type = $isVideo ? 'video' : 'image';
        $subDir = $type === 'video' ? 'videos' : 'images';

        $targetDir = __DIR__ . '/../../public/assets/' . $subDir . '/' . trim($folder, '/\\') . '/';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $filename = $prefix . '_' . date('Ymd') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $targetDir . $filename;

        if (move_uploaded_file((string)$file['tmp_name'], $dest)) {
            @chmod($dest, 0644);
            return [
                'url' => url('assets/' . $subDir . '/' . trim($folder, '/\\') . '/' . $filename),
                'type' => $type,
                'filename' => $filename,
                'path' => $dest,
            ];
        }

        return null;
    }

    /**
     * Handle multiple media uploads (images and videos supported)
     */
    protected function uploadMultipleMediaFiles(array $files, string $folder = 'products', string $prefix = 'gallery', int $maxSizeMb = 100): array {
        $uploaded = [];
        if (!isset($files['name']) || !is_array($files['name'])) {
            return $uploaded;
        }

        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $single = [
                'name' => $files['name'][$i] ?? '',
                'type' => $files['type'][$i] ?? '',
                'tmp_name' => $files['tmp_name'][$i] ?? '',
                'error' => $files['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$i] ?? 0,
            ];
            $res = $this->uploadMediaFile($single, $folder, $prefix, $maxSizeMb);
            if ($res !== null) {
                $uploaded[] = $res;
            }
        }

        return $uploaded;
    }
}
