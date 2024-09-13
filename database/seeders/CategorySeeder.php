<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Salary', 'tag' => 'Income'],
            ['name' => 'Bonus', 'tag' => 'Income'],
            ['name' => 'Interest Income', 'tag' => 'Income'],
            ['name' => 'Investment Income', 'tag' => 'Income'],
            ['name' => 'Rental Income', 'tag' => 'Income'],
            ['name' => 'Dividend', 'tag' => 'Income'],
            ['name' => 'Freelance Income', 'tag' => 'Income'],
            ['name' => 'Gift', 'tag' => 'Income'],
            ['name' => 'Refund', 'tag' => 'Income'],
            ['name' => 'Tax Return', 'tag' => 'Income'],
            ['name' => 'Groceries', 'tag' => 'Expense'],
            ['name' => 'Utilities', 'tag' => 'Expense'],
            ['name' => 'Rent', 'tag' => 'Expense'],
            ['name' => 'Transportation', 'tag' => 'Expense'],
            ['name' => 'Healthcare', 'tag' => 'Expense'],
            ['name' => 'Insurance', 'tag' => 'Expense'],
            ['name' => 'Dining Out', 'tag' => 'Expense'],
            ['name' => 'Entertainment', 'tag' => 'Expense'],
            ['name' => 'Education', 'tag' => 'Expense'],
            ['name' => 'Clothing', 'tag' => 'Expense'],
        ];

        foreach ($categories as $category) {
            DB::table('category')->insert([
                'name' => $category['name'],
                'tag' => $category['tag'],
            ]);
        }
    }
}
