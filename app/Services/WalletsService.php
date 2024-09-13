<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WalletsService
{
    public function getAllWallets($perPage = 5)
    {
        $userId = session('user_id');
        return Wallets::where('user_id', $userId)
            ->with('walletType', 'financialInstitute')
            ->paginate($perPage);
    }

    public function create($data)
    {
        $userId = Session::get('user_id');
        $data['user_id'] = $userId;
        $data['is_active'] = true;
        $formattedData = [];
        foreach ($data as $key => $value) {
            $newKey = preg_replace('/1$/', '', $key);
            $formattedData[$newKey] = $value;
        }

        return Wallets::create($formattedData);
    }

    public function getWalletById($id)
    {
        return Wallets::with('walletType', 'financialInstitute')->find($id);
    }

    public function update(Wallets $wallet, array $data)
    {
        $wallet->update($data);
        return $wallet;
    }

    public function getUserWallets()
    {
        $userId = session('user_id');
        return Wallets::where('user_id', $userId)
            ->where('is_active', true)
            ->with(['walletType', 'financialInstitute'])
            ->get();
    }

    public function getExpenseBreakdown($id)
    {
        $data = Transaction::where('user_id', $id)
            ->where('trans_type', 'Expense')
            ->join('category', 'transactions.category', '=', 'category.id')
            ->select('category.name as category_name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('category.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getMonthlyIncomeTrend($id)
    {
        $data = Transaction::where('user_id', $id)
            ->where('trans_type', 'Income')
            ->select(DB::raw("DATE_FORMAT(trans_date, '%Y-%m') as month"), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(trans_date, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(trans_date, '%Y-%m')"), 'ASC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function checkActiveTransactions($id){
        $transactionCount = Transaction::where('wallet_id', $id)
        ->count();

        return $transactionCount > 0;
    }

    public function deleteWallet($id)
    {
        if ($this->checkActiveTransactions($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete the wallet. It has active transactions.'
            ]);
        }

        $wallet = Wallets::find($id);

        if ($wallet) {
            $wallet->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Wallet successfully deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No wallet found with the provided ID.'
            ]);
        }
    }
}
