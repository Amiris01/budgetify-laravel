<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $styles = [
            ['name' => 'Casual'],
            ['name' => 'Formal'],
            ['name' => 'Athletic'],
            ['name' => 'Business Casual'],
            ['name' => 'Vintage'],
            ['name' => 'Bohemian'],
            ['name' => 'Streetwear'],
            ['name' => 'Preppy'],
            ['name' => 'Chic'],
            ['name' => 'Hipster'],
            ['name' => 'Minimalist'],
            ['name' => 'Gothic'],
            ['name' => 'Sporty'],
            ['name' => 'Elegant'],
            ['name' => 'Classic'],
            ['name' => 'Punk'],
            ['name' => 'Grunge'],
            ['name' => 'Retro'],
            ['name' => 'Beachwear'],
            ['name' => 'Urban'],
            ['name' => 'Workwear'],
            ['name' => 'Summer'],
            ['name' => 'Winter'],
            ['name' => 'Spring'],
            ['name' => 'Autumn'],
            ['name' => 'Party'],
            ['name' => 'Festive'],
            ['name' => 'Loungewear'],
            ['name' => 'Travel'],
            ['name' => 'Resort'],
        ];

        // Insert data into the style table
        DB::table('style')->insert($styles);
    }
}
