<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permite guardar pedidos que nacen en Shopify fuera de este backend
 * (p. ej. los que crea el bot de WhatsApp) y llegan por el webhook
 * orders/create:
 *  - `source` acepta `shopify` para los que no se identifican como del bot.
 *  - cédula y correo pasan a opcionales, porque Shopify puede no traerlos.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->enum('source', ['web_form', 'whatsapp_bot', 'admin_panel', 'shopify'])
                ->default('web_form')
                ->change();
            $table->string('recipient_id_number')->nullable()->change();
            $table->string('recipient_email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->enum('source', ['web_form', 'whatsapp_bot', 'admin_panel'])
                ->default('web_form')
                ->change();
            $table->string('recipient_id_number')->nullable(false)->change();
            $table->string('recipient_email')->nullable(false)->change();
        });
    }
};
