<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

class AdminMiddleware {
    public function handle(Request $request, Response $response): bool {
        $admin = Auth::adminCheck() ? Auth::admin() : null;
        if (!$admin || ($admin['status'] ?? '') !== 'active') {
            if (Auth::adminCheck()) {
                Auth::adminLogout();
            }
            if ($request->isAjax()) {
                $response->json(['success' => false, 'error' => 'Admin session expired', 'redirect' => url('admin/login')], 403);
                return false;
            }
            flash('error', 'Please log in with admin credentials to access the management portal.');
            $response->redirect(url('admin/login'));
            return false;
        }
        return true;
    }
}
