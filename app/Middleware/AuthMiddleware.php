<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

class AuthMiddleware {
    public function handle(Request $request, Response $response): bool {
        $user = Auth::check() ? Auth::user() : null;
        if (!$user || ($user['status'] ?? '') !== 'active') {
            if (Auth::check()) {
                Auth::logout();
            }
            if ($request->isAjax()) {
                $response->json(['success' => false, 'error' => 'Authentication required', 'redirect' => url('login')], 401);
                return false;
            }
            flash('error', 'Please log in to access your account.');
            $response->redirect(url('login?redirect=' . urlencode($request->uri())));
            return false;
        }
        return true;
    }
}
