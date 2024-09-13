<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApparelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apparelTypes = [
            ['name' => 'Shirt'],
            ['name' => 'Pants'],
            ['name' => 'Short Pants'],
            ['name' => 'Jeans'],
            ['name' => 'Jacket'],
            ['name' => 'Sweater'],
            ['name' => 'Hoodie'],
            ['name' => 'T-shirt'],
            ['name' => 'Blouse'],
            ['name' => 'Skirt'],
            ['name' => 'Dress'],
            ['name' => 'Suit'],
            ['name' => 'Blazer'],
            ['name' => 'Coat'],
            ['name' => 'Cardigan'],
            ['name' => 'Leggings'],
            ['name' => 'Overalls'],
            ['name' => 'Tracksuit'],
            ['name' => 'Swimwear'],
            ['name' => 'Underwear'],
        ];

        DB::table('apparel_type')->insert($apparelTypes);
    }
}
