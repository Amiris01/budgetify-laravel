<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialInstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutes = [
            'Maybank',
            'CIMB Bank',
            'Public Bank',
            'RHB Bank',
            'Hong Leong Bank',
            'AmBank',
            'Bank Islam',
            'Bank Muamalat',
            'Bank Rakyat',
            'Affin Bank',
            'Alliance Bank',
            'HSBC Bank Malaysia',
            'Standard Chartered Bank Malaysia',
            'UOB Malaysia',
            'OCBC Bank Malaysia',
            'Citibank Malaysia',
            'Al Rajhi Bank',
            'MBSB Bank',
            'Agrobank',
            'Bank Simpanan Nasional',
            'Kuwait Finance House Malaysia',
            'Bangkok Bank Malaysia',
            'Bank of China Malaysia',
            'BNP Paribas Malaysia',
            'Deutsche Bank Malaysia',
            'JP Morgan Chase Bank Malaysia',
            'Mizuho Bank Malaysia',
            'Sumitomo Mitsui Banking Corporation Malaysia',
            'MUFG Bank Malaysia',
            'Woori Bank Malaysia',
            'Industrial and Commercial Bank of China Malaysia',
            'China Construction Bank Malaysia',
            'Bangladesh Bank Malaysia',
            'India International Bank Malaysia',
            'Malaysian Industrial Development Finance Berhad',
            'AEON Credit Service Malaysia',
            'RHB Islamic Bank',
            'Maybank Islamic Bank',
            'CIMB Islamic Bank',
        ];

        foreach ($institutes as $institute) {
            DB::table('financial_institute')->insert([
                'name' => $institute,
            ]);
        }
    }
}
