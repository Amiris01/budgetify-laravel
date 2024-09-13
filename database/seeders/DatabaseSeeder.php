<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(ApparelTypeSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(StyleSeeder::class);
        $this->call(WalletTypeSeeder::class);
        $this->call(FinancialInstituteSeeder::class);
        $this->call(CategorySeeder::class);
    }
}
