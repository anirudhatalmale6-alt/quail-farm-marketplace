<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update role enum to include investor
        // SQLite doesn't enforce enum, so we just need to handle it in validation
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('remember_token');
            $table->boolean('is_online')->default(false)->after('last_seen_at');
            $table->string('profile_picture')->nullable()->after('avatar');
            $table->string('company_name')->nullable()->after('business_name');
            $table->string('website')->nullable()->after('company_name');
            $table->decimal('investment_budget', 12, 2)->nullable()->after('website');
            $table->text('investment_interests')->nullable()->after('investment_budget');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_seen_at',
                'is_online',
                'profile_picture',
                'company_name',
                'website',
                'investment_budget',
                'investment_interests',
            ]);
        });
    }
};
