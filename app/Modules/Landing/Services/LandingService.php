<?php

declare(strict_types=1);

namespace App\Modules\Landing\Services;

use App\Modules\Landing\Models\Landing;
use Illuminate\Support\Collection;

class LandingService
{
    /**
     * @return Collection<int,Landing>
     */
    public function all(): Collection
    {
        return Landing::query()->orderBy('title')->get();
    }

    public function findActiveBySlug(string $slug): ?Landing
    {
        return Landing::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function findBySlug(string $slug): ?Landing
    {
        return Landing::query()->where('slug', $slug)->first();
    }

    public function activate(Landing $landing): Landing
    {
        $landing->update(['is_active' => true]);

        return $landing->fresh();
    }

    public function deactivate(Landing $landing): Landing
    {
        $landing->update(['is_active' => false]);

        return $landing->fresh();
    }

    public function toggle(Landing $landing): Landing
    {
        $landing->update(['is_active' => ! $landing->is_active]);

        return $landing->fresh();
    }
}
