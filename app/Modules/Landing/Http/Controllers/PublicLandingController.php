<?php

declare(strict_types=1);

namespace App\Modules\Landing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Landing\Services\LandingService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicLandingController extends Controller
{
    public function __construct(
        private readonly LandingService $landings,
    ) {}

    public function show(string $slug): View
    {
        $landing = $this->landings->findActiveBySlug($slug);

        if ($landing === null) {
            throw new NotFoundHttpException();
        }

        // 1:1 landing↔producto. Si la landing no tiene producto asignado todavía,
        // caemos al primer producto importado (transitorio MVP). Si no hay productos
        // en absoluto, 404 — el admin debe importar antes de publicar landings.
        $product = $landing->product ?? Product::first();

        if ($product === null) {
            throw new NotFoundHttpException();
        }

        return view($landing->blade_view, [
            'landing' => $landing,
            'product' => $product,
        ]);
    }
}
