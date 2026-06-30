<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->integer('loyalty_points_per_100')->default(1)->after('loyalty_rate');
            $table->decimal('loyalty_point_value', 5, 2)->default(1.00)->after('loyalty_points_per_100');
            $table->integer('min_redeem_points')->default(100)->after('loyalty_point_value');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points_per_100', 'loyalty_point_value', 'min_redeem_points']);
        });
    }
};
