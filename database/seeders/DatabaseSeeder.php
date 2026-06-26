<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CommissionSetting;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Admin User ---
        User::create([
            'name' => 'Admin',
            'email' => 'admin@quailconnect.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // --- Sample Farmers ---
        $farmer1 = User::create([
            'name' => 'James Hartley',
            'email' => 'james@sunnysidequail.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'status' => 'active',
            'farm_name' => 'Sunnyside Quail Farm',
            'phone' => '555-0101',
            'city' => 'Lancaster',
            'state' => 'PA',
            'country' => 'US',
            'bio' => 'Third-generation quail farmer specializing in organic, free-range quail eggs.',
        ]);

        $farmer2 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@greenmeadow.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'status' => 'active',
            'farm_name' => 'Green Meadow Aviary',
            'phone' => '555-0102',
            'city' => 'Austin',
            'state' => 'TX',
            'country' => 'US',
            'bio' => 'Specializing in Coturnix and Bobwhite quail breeds with certified organic practices.',
        ]);

        $farmer3 = User::create([
            'name' => 'Robert Chen',
            'email' => 'robert@goldenquail.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'status' => 'pending',
            'farm_name' => 'Golden Quail Ranch',
            'phone' => '555-0103',
            'city' => 'Sacramento',
            'state' => 'CA',
            'country' => 'US',
            'bio' => 'Large-scale quail operation supplying restaurants and retailers across California.',
        ]);

        // --- Sample Buyers ---
        User::create([
            'name' => 'Sarah Mitchell',
            'email' => 'sarah@farmtotable.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'status' => 'active',
            'business_name' => 'Farm to Table Distributors',
            'phone' => '555-0201',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'US',
        ]);

        User::create([
            'name' => 'David Kim',
            'email' => 'david@chefsdirect.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'status' => 'active',
            'business_name' => "Chef's Direct Supply",
            'phone' => '555-0202',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'US',
        ]);

        User::create([
            'name' => 'Lisa Nguyen',
            'email' => 'lisa@organicgoods.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'status' => 'pending',
            'business_name' => 'Organic Goods Market',
            'phone' => '555-0203',
            'city' => 'Portland',
            'state' => 'OR',
            'country' => 'US',
        ]);

        // --- Product Categories ---
        $catFreshEggs = Category::create([
            'name' => 'Fresh Eggs',
            'slug' => 'fresh-eggs',
            'type' => 'product',
            'icon' => null,
            'status' => true,
        ]);

        $catFertilized = Category::create([
            'name' => 'Fertilized Eggs',
            'slug' => 'fertilized-eggs',
            'type' => 'product',
            'icon' => null,
            'status' => true,
        ]);

        $catOrganic = Category::create([
            'name' => 'Organic Eggs',
            'slug' => 'organic-eggs',
            'type' => 'product',
            'icon' => null,
            'status' => true,
        ]);

        // --- Supply Categories ---
        Category::create([
            'name' => 'Feed',
            'slug' => 'feed',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Equipment',
            'slug' => 'equipment',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Veterinary',
            'slug' => 'veterinary',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Transport',
            'slug' => 'transport',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        // --- Sample Products ---
        Product::create([
            'user_id' => $farmer1->id,
            'category_id' => $catFreshEggs->id,
            'name' => 'Farm Fresh Coturnix Eggs',
            'slug' => 'farm-fresh-coturnix-eggs-' . Str::random(5),
            'description' => 'Premium quality Coturnix quail eggs, collected daily from free-range birds. Perfect for gourmet cooking and baking.',
            'price' => 8.50,
            'unit' => 'dozen',
            'quantity_available' => 200,
            'min_order' => 1,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer1->id,
            'category_id' => $catFertilized->id,
            'name' => 'Fertilized Hatching Eggs',
            'slug' => 'fertilized-hatching-eggs-' . Str::random(5),
            'description' => 'High fertility rate Coturnix hatching eggs. Shipped with care for successful incubation.',
            'price' => 15.00,
            'unit' => 'dozen',
            'quantity_available' => 100,
            'min_order' => 2,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer2->id,
            'category_id' => $catOrganic->id,
            'name' => 'Certified Organic Quail Eggs',
            'slug' => 'certified-organic-quail-eggs-' . Str::random(5),
            'description' => 'USDA certified organic quail eggs from pasture-raised Coturnix quail. No antibiotics, no hormones.',
            'price' => 12.00,
            'unit' => 'dozen',
            'quantity_available' => 150,
            'min_order' => 1,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer2->id,
            'category_id' => $catFreshEggs->id,
            'name' => 'Bobwhite Quail Eggs',
            'slug' => 'bobwhite-quail-eggs-' . Str::random(5),
            'description' => 'Delicate Bobwhite quail eggs, smaller than Coturnix but prized by gourmet chefs for their rich flavor.',
            'price' => 10.00,
            'unit' => 'dozen',
            'quantity_available' => 80,
            'min_order' => 3,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer3->id,
            'category_id' => $catFreshEggs->id,
            'name' => 'Bulk Quail Eggs - Restaurant Pack',
            'slug' => 'bulk-quail-eggs-restaurant-pack-' . Str::random(5),
            'description' => 'Bulk pack of 10 dozen fresh quail eggs, ideal for restaurants and food service. Consistent size and quality.',
            'price' => 70.00,
            'unit' => 'crate',
            'quantity_available' => 50,
            'min_order' => 1,
            'images' => [],
            'status' => 'active',
        ]);

        // --- Subscription Plans ---
        SubscriptionPlan::create([
            'name' => 'Free',
            'slug' => 'free',
            'description' => 'Get started with the basics. Perfect for new farmers joining the marketplace.',
            'price' => 0.00,
            'billing_cycle' => 'monthly',
            'max_listings' => 5,
            'features' => [
                'Up to 5 product listings',
                'Basic marketplace access',
                'Messaging system',
                'Community feed access',
            ],
            'is_featured' => false,
            'priority_search' => false,
            'credit_access' => false,
            'investment_access' => false,
            'analytics_access' => false,
            'is_active' => true,
        ]);

        SubscriptionPlan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Everything you need to scale your quail farming business.',
            'price' => 29.99,
            'billing_cycle' => 'monthly',
            'max_listings' => null,
            'features' => [
                'Unlimited product listings',
                'Full marketplace access',
                'Messaging system',
                'Community feed access',
                'Priority search placement',
                'Investment applications',
                'Advanced analytics',
                'Featured profile badge',
                'Credit system access',
            ],
            'is_featured' => true,
            'priority_search' => true,
            'credit_access' => true,
            'investment_access' => true,
            'analytics_access' => true,
            'is_active' => true,
        ]);

        // --- Commission Brackets ---
        CommissionSetting::create([
            'min_order_amount' => 0.00,
            'max_order_amount' => 100.00,
            'rate' => 8.00,
            'is_active' => true,
        ]);

        CommissionSetting::create([
            'min_order_amount' => 100.01,
            'max_order_amount' => 99999.99,
            'rate' => 4.00,
            'is_active' => true,
        ]);
    }
}
