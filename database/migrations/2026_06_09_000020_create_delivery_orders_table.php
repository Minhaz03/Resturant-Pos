<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('delivery_address');
            $table->string('delivery_phone');
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'on_way', 'delivered', 'failed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('tracking_code')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
