<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Order;

class InvoiceController extends Controller {
    public function show(string $orderNumber): void {
        $order = Order::getByNumber($orderNumber);
        if (!$order) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Invoice Not Found']);
            return;
        }

        // Customer access check (if order belongs to a user, must be that user or admin)
        if (!empty($order['user_id']) && !Auth::adminCheck()) {
            if (!Auth::check() || Auth::id() != $order['user_id']) {
                $this->response->status(403);
                echo "Unauthorized invoice access.";
                return;
            }
        }

        $this->view('invoice.template', [
            'order' => $order,
            'title' => "Invoice #{$order['invoice']['invoice_number']} | Legacy Food"
        ], null); // Render without layout for pure printable view
    }
}
