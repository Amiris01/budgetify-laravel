<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike'],
            ['name' => 'Adidas'],
            ['name' => 'Puma'],
            ['name' => 'Under Armour'],
            ['name' => 'Reebok'],
            ['name' => 'New Balance'],
            ['name' => 'Asics'],
            ['name' => 'Converse'],
            ['name' => 'Vans'],
            ['name' => "Levi's"],
            ['name' => 'Tommy Hilfiger'],
            ['name' => 'Calvin Klein'],
            ['name' => 'Guess'],
            ['name' => 'Hugo Boss'],
            ['name' => 'Ralph Lauren'],
            ['name' => 'Diesel'],
            ['name' => 'Champion'],
            ['name' => 'Lacoste'],
            ['name' => 'Abercrombie & Fitch'],
            ['name' => 'Hollister'],
            ['name' => 'American Eagle'],
            ['name' => 'Bershka'],
            ['name' => 'Stradivarius'],
            ['name' => 'Pull & Bear'],
            ['name' => 'Zara'],
            ['name' => 'Mango'],
            ['name' => 'Uniqlo'],
            ['name' => 'H&M'],
            ['name' => 'Forever 21'],
            ['name' => 'Topshop'],
            ['name' => 'River Island'],
            ['name' => 'Gap'],
            ['name' => 'Old Navy'],
            ['name' => 'J.Crew'],
            ['name' => 'Banana Republic'],
            ['name' => 'Brooks Brothers'],
            ['name' => 'Tumi'],
            ['name' => 'Michael Kors'],
            ['name' => 'Kate Spade'],
            ['name' => 'Coach'],
            ['name' => 'Prada'],
            ['name' => 'Gucci'],
            ['name' => 'Versace'],
            ['name' => 'Burberry'],
            ['name' => 'Chanel'],
            ['name' => 'Louis Vuitton'],
            ['name' => 'Hermès'],
            ['name' => 'Balenciaga'],
            ['name' => 'Fendi'],
            ['name' => 'Valentino'],
            ['name' => 'Yves Saint Laurent'],
            ['name' => 'Marc Jacobs'],
            ['name' => 'Dolce & Gabbana'],
            ['name' => 'Kenzo'],
            ['name' => 'Paul Smith'],
            ['name' => 'Acne Studios'],
        ];

        // Insert data into the brands table
        DB::table('brands')->insert($brands);
    }
}
