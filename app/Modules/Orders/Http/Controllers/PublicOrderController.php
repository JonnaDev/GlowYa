<?php

declare(strict_types=1);

namespace App\Modules\Orders\Http\Controllers;
use App\Modules\Orders\Http\Requests\StorePublicOrderRequest;
use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Landing\Services\LandingService;
use App\Modules\Orders\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class PublicOrderController extends Controller
{
    public function __construct(
        private readonly LandingService $landings,
    ) {}

    public function store(StorePublicOrderRequest $request, OrderService $orders, string $slug): RedirectResponse
    {
        $landing = $this->landings->findActiveBySlug($slug);

        if ($landing === null) {
            throw new NotFoundHttpException();
        }

        // Honeypot: el campo `extra_reference` está oculto para personas; solo
        // un bot que rellena todo el formulario lo envía con contenido.
        // Respondemos como si hubiera funcionado para no darle pistas, sin
        // crear la orden.
        if ($request->filled('extra_reference')) {
            Log::info('Pedido descartado por honeypot', [
                'slug' => $slug,
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->route('landing.show', ['slug' => $landing->slug])
                ->with('success', '¡Pedido recibido! Te llamaremos para confirmar.');
        }

        $validated = $request->validated();

        $product = $landing->product_id !== null
            ? Product::find($landing->product_id)
            : Product::first();

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
            ->route('landing.show', ['slug' => $landing->slug])
            ->with('success', "¡Pedido #{$order->id} recibido! Te llamaremos a {$order->recipient_phone} para confirmar.");
    }
}
