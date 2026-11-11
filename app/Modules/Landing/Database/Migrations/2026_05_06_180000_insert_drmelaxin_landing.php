<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // En una BD nueva el producto 3 todavía no existe. MySQL descartaba
        // la fila en silencio (INSERT IGNORE); SQLite, que usan los tests,
        // lanzaba error de clave foránea y rompía toda la suite.
        if (! DB::table('products')->where('id', 3)->exists()) {
            return;
        }

        DB::table('landings')->insertOrIgnore([
            'slug' => 'drmelaxin',
            'title' => 'Dr Melaxin TX Cream — Tratamiento despigmentante',
            'blade_view' => 'landings.drmelaxin',
            'product_id' => 3,
            'is_active' => true,
            'description' => 'Landing del tratamiento despigmentante con efecto laboratorio y video scroll-driven.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('landings')->where('slug', 'drmelaxin')->delete();
    }
};
