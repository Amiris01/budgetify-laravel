<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $walletTypes = [
            'Savings Account',
            'Current Account',
            'Credit Card',
            'Debit Card',
            'Prepaid Card',
            'Digital Wallet',
            'PayPal',
            'Cash',
            'Fixed Deposit',
            'Investment Account',
            'Business Account',
            'Joint Account',
            'Foreign Currency Account',
            'Loan Account',
            'Pension Fund',
            'Insurance',
            'Mutual Funds',
            'Stocks',
            'Bonds',
            'Gold',
            'Cryptocurrency Wallet',
            'Expense Account',
            'Travel Card',
            'Gift Card',
            'Health Savings Account',
            'Retirement Account',
            'Trust Fund',
            'Estate Planning',
            'Education Fund',
            'Emergency Fund',
            'Petty Cash',
            'Tax Savings Account',
            'Mortgage Account',
            'Charity Fund',
        ];

        foreach ($walletTypes as $type) {
            DB::table('wallet_type')->insert([
                'name' => $type,
            ]);
        }
    }
}
