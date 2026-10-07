<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware {
    public function handle(Request $request, Response $response): bool {
        if ($request->isPost() || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            $token = $request->input('_csrf_token') ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
            if (!verify_csrf($token)) {
                if ($request->isAjax()) {
                    $response->json(['success' => false, 'error' => 'Security token invalid or expired. Please refresh the page.'], 419);
                    return false;
                }
                flash('error', 'Security token expired. Please try again.');
                $response->redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
                return false;
            }
        }
        return true;
    }
}
