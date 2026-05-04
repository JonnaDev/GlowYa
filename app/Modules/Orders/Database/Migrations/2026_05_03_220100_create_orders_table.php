<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();

            // Identity & traceability
            $table->string('idempotency_key')->unique();
            $table->string('shopify_order_id')->nullable()->index();
            $table->string('dropi_order_id')->nullable()->index();
            $table->enum('source', ['web_form', 'whatsapp_bot', 'admin_panel'])->default('web_form');

            // Recipient
            $table->string('recipient_full_name');
            $table->string('recipient_id_number')->index();
            $table->string('recipient_phone');
            $table->string('recipient_email')->index();
            $table->string('recipient_department');
            $table->string('recipient_city')->index();
            $table->string('recipient_neighborhood')->nullable();
            $table->string('recipient_address_line');
            $table->text('recipient_notes')->nullable();

            // Status & sync
            $table->enum('status_local', [
                'pending_confirmation',
                'confirmed',
                'sent_to_shopify',
                'fulfilled',
                'cancelled',
            ])->default('pending_confirmation');
            $table->string('status_shopify')->nullable();
            $table->string('status_dropi')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            // Amounts
            $table->decimal('total_amount', 12, 2);
            $table->decimal('shipping_cost_internal', 12, 2)->nullable();

            $table->timestamps();

            $table->index(['status_local', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
