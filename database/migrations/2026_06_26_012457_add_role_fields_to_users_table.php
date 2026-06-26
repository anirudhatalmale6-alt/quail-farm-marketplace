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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['farmer', 'buyer', 'admin', 'investor'])->default('buyer')->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('avatar')->nullable()->after('country');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('pending')->after('avatar');
            $table->text('bio')->nullable()->after('status');
            $table->string('farm_name')->nullable()->after('bio');
            $table->string('business_name')->nullable()->after('farm_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'city',
                'state',
                'country',
                'avatar',
                'status',
                'bio',
                'farm_name',
                'business_name',
            ]);
        });
    }
};
