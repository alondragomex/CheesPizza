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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_type')->default('delivery')->after('customer_address');
            $table->decimal('subtotal', 8, 2)->default(0.00)->after('order_type');
            $table->decimal('discount_2x1', 8, 2)->default(0.00)->after('subtotal');
            $table->decimal('delivery_fee', 8, 2)->default(0.00)->after('discount_2x1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'subtotal', 'discount_2x1', 'delivery_fee']);
        });
    }
};
