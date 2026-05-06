<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();

            // Shopify identity
            $table->string('shopify_product_id')->unique();
            $table->string('shopify_variant_id')->index();

            // Catalog data
            $table->string('title');
            $table->string('handle')->unique();
            $table->longText('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->text('tags')->nullable();
            $table->string('status')->default('active');

            // Pricing & stock
            $table->string('sku')->nullable()->index();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->integer('inventory_quantity')->default(0);
            $table->decimal('weight', 8, 3)->nullable();

            // Media
            $table->text('image_url')->nullable();
            $table->string('image_alt')->nullable();

            // Sync metadata
            $table->timestamp('shopify_updated_at')->nullable();
            $table->string('content_hash', 64)->nullable();
            $table->timestamp('imported_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
