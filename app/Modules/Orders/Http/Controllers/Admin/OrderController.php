<?php

declare(strict_types=1);

namespace App\Modules\Orders\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with('items')
            ->latest();

        // Filtro por status_local
        if ($request->filled('status')) {
            $query->where('status_local', $request->input('status'));
        }

        // Búsqueda por nombre, email, teléfono o shopify_order_id
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('recipient_full_name', 'like', "%{$search}%")
                    ->orWhere('recipient_email', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%")
                    ->orWhere('shopify_order_id', $search);
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'filters' => [
                'status' => $request->input('status', ''),
                'search' => $request->input('search', ''),
            ],
            'statusCounts' => [
                'all' => Order::count(),
                'pending_confirmation' => Order::where('status_local', 'pending_confirmation')->count(),
                'sent_to_shopify' => Order::where('status_local', 'sent_to_shopify')->count(),
                'fulfilled' => Order::where('status_local', 'fulfilled')->count(),
                'cancelled' => Order::where('status_local', 'cancelled')->count(),
            ],
        ]);
    }
}
