<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'inventory_quantity' => 'integer',
        'weight' => 'decimal:3',
        'shopify_updated_at' => 'datetime',
        'imported_at' => 'datetime',
    ];

    protected function tagsArray(): Attribute
    {
        return Attribute::get(
            fn (): array => $this->tags
                ? array_map('trim', explode(',', $this->tags))
                : []
        );
    }
}
