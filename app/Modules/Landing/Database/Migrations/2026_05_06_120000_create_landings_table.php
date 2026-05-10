<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landings', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('blade_view');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'slug']);
        });

        // Convención: 1 landing : 1 producto. La blade vive en
        // resources/views/landings/{slug}.blade.php → notación Laravel `landings.{slug}`.
        // product_id se asigna desde el admin tras importar el producto desde Shopify.
        
        /*DB::table('landings')->insertOrIgnore([
            [
                'slug' => 'noil',
                'title' => 'NOIL — Starry Say Oiled Perfume Body Wash',
                'blade_view' => 'landings.noil',
                'product_id' => null,
                'is_active' => true,
                'description' => 'Landing MVP del jabón corporal con efecto perfume. Mono-producto.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        */
    }

    public function down(): void
    {
        Schema::dropIfExists('landings');
    }
};
