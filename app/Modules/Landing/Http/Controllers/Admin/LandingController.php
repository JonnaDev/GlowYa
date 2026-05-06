<?php

declare(strict_types=1);

namespace App\Modules\Landing\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Landing\Models\Landing;
use App\Modules\Landing\Services\LandingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function __construct(
        private readonly LandingService $landings,
    ) {}

    public function index(): Response
    {
        $landings = $this->landings->all()->map(fn (Landing $l): array => [
            'id' => $l->id,
            'slug' => $l->slug,
            'title' => $l->title,
            'blade_view' => $l->blade_view,
            'description' => $l->description,
            'is_active' => $l->is_active,
            'product_id' => $l->product_id,
            'public_url' => url('/'.$l->slug),
            'updated_at' => $l->updated_at?->toDateTimeString(),
        ])->all();

        return Inertia::render('Landing/Index', [
            'landings' => $landings,
        ]);
    }

    public function toggle(Landing $landing): RedirectResponse
    {
        $updated = $this->landings->toggle($landing);

        $state = $updated->is_active ? 'activada' : 'desactivada';

        return back()->with('success', "Landing «{$updated->title}» {$state}.");
    }
}
