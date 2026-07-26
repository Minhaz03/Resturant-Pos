<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('plan_id');
            $table->decimal('amount', 10, 2)->nullable()->after('transaction_id');
        });
        
        \DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'canceled', 'expired', 'pending') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'amount']);
        });
        
        \DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'canceled', 'expired') DEFAULT 'active'");
    }
};
