<?php

declare(strict_types=1);

namespace App\Modules\Orders\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Landing\Services\LandingService;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Orders\Support\ColombiaDepartments;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class PublicOrderController extends Controller
{
    public function __construct(
        private readonly LandingService $landings,
    ) {}

    public function store(Request $request, OrderService $orders, string $slug): RedirectResponse
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

        $request->merge([
            'phone' => $this->normalizePhone($request->input('phone')),
            'id_number' => $this->digitsOnly($request->input('id_number')),
        ]);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s\'.\-]+$/u'],
            'id_number' => ['required', 'string', 'regex:/^\d{6,10}$/'],
            'phone' => ['required', 'string', 'regex:/^3\d{9}$/'],
            'email' => ['required', 'email', 'max:120'],
            'department' => ['required', 'string', Rule::in(ColombiaDepartments::LIST)],
            'city' => ['required', 'string', 'min:2', 'max:80'],
            'neighborhood' => ['nullable', 'string', 'max:80'],
            'address' => ['required', 'string', 'min:5', 'max:200'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], 
        
        [
            'full_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'id_number.regex' => 'La cédula debe tener entre 6 y 10 dígitos, sin letras.',
            'phone.regex' => 'El celular debe ser un número colombiano de 10 dígitos que empiece por 3.',
            'department.in' => 'Selecciona un departamento de la lista.',
        ]);

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

    /**
     * Deja el celular en 10 dígitos: quita espacios, guiones y el indicativo
     * +57 si viene incluido. Lo que no sea texto se devuelve tal cual para
     * que lo rechace la validación.
     */
    private function normalizePhone(mixed $value): mixed
    {
        $digits = $this->digitsOnly($value);

        if (is_string($digits) && strlen($digits) === 12 && str_starts_with($digits, '57')) {
            return substr($digits, 2);
        }

        return $digits;
    }

    /**
     * Quita separadores habituales (espacios, puntos, guiones, paréntesis, +)
     * sin tocar letras, para que "1.029.880.299" pase y "abc123" siga fallando.
     */
    private function digitsOnly(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return preg_replace('/[\s.\-()+]/', '', $value);
    }
}
