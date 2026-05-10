<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('shopify_fulfillment_id')->nullable()->after('dropi_order_id');
            $table->string('tracking_number')->nullable()->after('cancellation_reason');
            $table->string('tracking_url')->nullable()->after('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['shopify_fulfillment_id', 'tracking_number', 'tracking_url']);
        });
    }
};
