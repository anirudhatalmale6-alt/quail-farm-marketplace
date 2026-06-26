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
            'balance' => 10000.00,
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
            'balance' => 2500.00,
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
            'balance' => 1800.00,
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
            'balance' => 950.00,
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
            'balance' => 5000.00,
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
            'balance' => 3200.00,
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
            'balance' => 1500.00,
        ]);

        // --- Sample Investors ---
        User::create([
            'name' => 'Marcus Thompson',
            'email' => 'marcus@quailinvest.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'status' => 'active',
            'company_name' => 'Quail Investments LLC',
            'phone' => '555-0301',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
            'bio' => 'Angel investor specializing in sustainable agriculture and quail farming operations.',
            'investment_budget' => 500000,
            'investment_interests' => 'Organic quail farms, large-scale egg production, sustainable farming practices',
            'balance' => 50000.00,
        ]);

        User::create([
            'name' => 'Patricia Wells',
            'email' => 'patricia@agriventures.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'status' => 'active',
            'company_name' => 'AgriVentures Capital',
            'phone' => '555-0302',
            'city' => 'Dallas',
            'state' => 'TX',
            'country' => 'US',
            'bio' => 'Venture capital firm focused on poultry and small livestock farming investments.',
            'investment_budget' => 1000000,
            'investment_interests' => 'Quail breeding programs, processing facilities, export operations',
            'balance' => 100000.00,
        ]);

        // --- Marketplace Categories (Mapp) ---
        // 1. Quail (live birds)
        $catQuail = Category::create([
            'name' => 'Quail',
            'slug' => 'quail',
            'type' => 'product',
            'icon' => null,
            'status' => true,
        ]);

        // 2. Quail Eggs
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

        // 3. Quail Food (feed/nutrition)
        Category::create([
            'name' => 'Quail Feed',
            'slug' => 'quail-feed',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Supplements & Vitamins',
            'slug' => 'supplements-vitamins',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Veterinary Medicine',
            'slug' => 'veterinary-medicine',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        // 4. Quail Transport
        Category::create([
            'name' => 'Transport Services',
            'slug' => 'transport-services',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Shipping Supplies',
            'slug' => 'shipping-supplies',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        // 5. Quail Farming Machines
        Category::create([
            'name' => 'Egg Incubators',
            'slug' => 'egg-incubators',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Brooders & Heaters',
            'slug' => 'brooders-heaters',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Cages & Housing',
            'slug' => 'cages-housing',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Feeders & Waterers',
            'slug' => 'feeders-waterers',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        Category::create([
            'name' => 'Egg Processing Equipment',
            'slug' => 'egg-processing-equipment',
            'type' => 'supply',
            'icon' => null,
            'status' => true,
        ]);

        // --- Sample Products ---
        // Live Quail product
        Product::create([
            'user_id' => $farmer1->id,
            'category_id' => $catQuail->id,
            'name' => 'Live Coturnix Quail - Breeding Pair',
            'slug' => 'live-coturnix-quail-breeding-pair-' . Str::random(5),
            'description' => 'Healthy breeding pair of Coturnix quail. Excellent egg layers, producing 200-300 eggs per year. Ideal for starting or expanding your flock.',
            'price' => 25.00,
            'unit' => 'pair',
            'quantity_available' => 30,
            'min_order' => 1,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer2->id,
            'category_id' => $catQuail->id,
            'name' => 'Bobwhite Quail - Day Old Chicks',
            'slug' => 'bobwhite-quail-day-old-chicks-' . Str::random(5),
            'description' => 'Day-old Bobwhite quail chicks, vaccinated and health-checked. Perfect for raising game birds or egg production.',
            'price' => 3.50,
            'unit' => 'each',
            'quantity_available' => 500,
            'min_order' => 25,
            'images' => [],
            'status' => 'active',
        ]);

        Product::create([
            'user_id' => $farmer1->id,
            'category_id' => $catFreshEggs->id,
            'name' => 'Farm Fresh Coturnix Eggs',
            'slug' => 'farm-fresh-coturnix-eggs-' . Str::random(5),
            'description' => 'Premium quality Coturnix quail eggs, collected daily from free-range birds. Perfect for gourmet cooking and baking.',
            'price' => 8.50,
            'sale_price' => 6.99,
            'is_on_sale' => true,
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
            'sale_price' => 9.50,
            'is_on_sale' => true,
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
