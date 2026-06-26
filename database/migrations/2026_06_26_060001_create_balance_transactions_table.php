<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'purchase', 'sale', 'refund', 'admin_credit', 'admin_debit', 'investment']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('description');
            $table->string('reference')->nullable();
            $table->foreignId('related_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
