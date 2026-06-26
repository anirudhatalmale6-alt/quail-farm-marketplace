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
            $table->boolean('buyer_payment_confirmed')->default(false)->after('payment_status');
            $table->timestamp('buyer_payment_confirmed_at')->nullable()->after('buyer_payment_confirmed');
            $table->boolean('seller_payment_confirmed')->default(false)->after('buyer_payment_confirmed_at');
            $table->timestamp('seller_payment_confirmed_at')->nullable()->after('seller_payment_confirmed');
            $table->string('payment_reference')->nullable()->after('seller_payment_confirmed_at');
            $table->enum('payment_type', ['direct', 'credit'])->default('direct')->after('payment_reference');
            $table->boolean('is_credit_order')->default(false)->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_payment_confirmed',
                'buyer_payment_confirmed_at',
                'seller_payment_confirmed',
                'seller_payment_confirmed_at',
                'payment_reference',
                'payment_type',
                'is_credit_order',
            ]);
        });
    }
};
