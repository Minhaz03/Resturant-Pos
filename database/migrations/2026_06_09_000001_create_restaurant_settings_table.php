<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('tagline')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('currency', 10)->default('BDT');
            $table->string('currency_symbol', 10)->default('৳');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('tax_name')->default('VAT');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('date_format')->default('d/m/Y');
            $table->string('time_format')->default('H:i');
            $table->text('receipt_footer')->nullable();
            $table->string('invoice_prefix')->default('INV-');
            $table->string('order_prefix')->default('ORD-');
            $table->boolean('loyalty_enabled')->default(true);
            $table->decimal('loyalty_rate', 5, 2)->default(1.00);
            $table->string('mail_from_name')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
