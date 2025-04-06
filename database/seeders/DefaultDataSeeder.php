<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create default business
        $business = Business::create([
            'name' => 'Default Business',
            'email' => 'business@example.com',
            'phone' => '+256700000000',
            'address' => 'Kampala, Uganda',
            // 'status' => 'active',
            'is_active' => true,
        ]);

        // Create default branch
        $branch = Branch::create([
            'name' => 'Main Branch',
            'code' => 'KAMPALA',
            'email' => 'branch@example.com',
            'phone' => '+256700000001',
            'address' => 'Kampala, Uganda',
            // 'status' => 'active',
            'is_active' => true,
            'business_id' => $business->id,
        ]);

        // Create default user
        User::create([
            'name' => 'Nicholas Katongole',
            'email' => 'katznicho@gmail.com',
            'password' => Hash::make('12345678'),
            'business_id' => $business->id,
            'branch_id' => $branch->id,
        ]);
    }
}
