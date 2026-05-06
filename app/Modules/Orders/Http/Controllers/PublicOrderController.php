<?php

declare(strict_types=1);

namespace App\Modules\Orders\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class PublicOrderController extends Controller
{
    public function noilLanding(): View
    {
        return view('landing-page-noil');
    }

    public function storeNoil(Request $request, OrderService $orders): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:100'],
            'id_number' => ['required', 'string', 'min:6', 'max:20'],
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'email' => ['required', 'email', 'max:120'],
            'department' => ['required', 'string', 'max:60'],
            'city' => ['required', 'string', 'max:80'],
            'neighborhood' => ['nullable', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:200'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = Product::first();

        if ($product === null) {
            return back()
                ->withErrors(['general' => 'No hay productos disponibles. Contáctanos por WhatsApp.'])
                ->withInput();
        }

        try {
            $order = $orders->createFromPayload([
                'recipient_full_name' => $validated['full_name'],
                'recipient_id_number' => $validated['id_number'],
                'recipient_phone' => $validated['phone'],
                'recipient_email' => $validated['email'],
                'recipient_department' => $validated['department'],
                'recipient_city' => $validated['city'],
                'recipient_neighborhood' => $validated['neighborhood'] ?? null,
                'recipient_address_line' => $validated['address'],
                'recipient_notes' => $validated['notes'] ?? null,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => (int) $validated['quantity']],
                ],
            ], source: 'web_form');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors(['general' => 'Hubo un problema procesando tu pedido. Intentá nuevamente o contactanos por WhatsApp.'])
                ->withInput();
        }

        return redirect()
            ->route('landing.noil')
            ->with('success', "¡Pedido #{$order->id} recibido! Te llamaremos a {$order->recipient_phone} para confirmar.");
    }
}
