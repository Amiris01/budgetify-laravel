<?php

namespace App\Services;

use App\Models\Budgets;
use App\Models\Transaction;
use App\Models\Wallets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetsService
{

    public function getAllBudgets($perPage = 5)
    {
        $userId = session('user_id');
        return Budgets::where('user_id', $userId)
            ->with('categories')
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        $userId = Session::get('user_id');
        $data['user_id'] = $userId;
        $data['current_amount'] = $data['total_amount1'];
        $formattedData = [];
        foreach ($data as $key => $value) {
            $newKey = preg_replace('/1$/', '', $key);
            $formattedData[$newKey] = $value;
        }

        Alert::success('Budget Created', 'Budget has been created successfully.');
        return Budgets::create($formattedData);
    }

    public function getBudgetById($id)
    {
        return Budgets::with('categories')->find($id);
    }

    public function update(Budgets $budget, array $data)
    {
        $budget->update($data);
        Alert::success('Budget Updated', 'Budget has been updated successfully.');
        return $budget;
    }

    public function getBudgetByCategory($category)
    {
        $userId = session('user_id');
        $budgets = Budgets::where('user_id', $userId)
            ->where('category', $category)
            ->select('id', 'title')
            ->get();

        return response()->json($budgets);
    }

    public function getDashboardData()
    {
        $data = [];
        $id = session('user_id');

        $totalIncome = Transaction::where('user_id', $id)
            ->where('trans_type', 'Income')
            ->sum('amount');
        $data['totalIncome'] = $totalIncome ? $totalIncome : 0;

        $totalExpenses = Transaction::where('user_id', $id)
            ->where('trans_type', 'Expense')
            ->whereNotNull('budget_id')
            ->sum('amount');
        $data['totalExpenses'] = $totalExpenses ? $totalExpenses : 0;

        $totalBalance = Budgets::where('user_id', $id)
            ->sum('current_amount');
        $data['totalBalance'] = $totalBalance ? $totalBalance : 0;

        $totalBalance = Wallets::where('user_id', $id)->sum('amount');

        if ($totalBalance > 0) {
            $data['savingsRate'] = (($totalBalance - $data['totalExpenses']) / $totalBalance) * 100;
        } else {
            $data['savingsRate'] = 0;
        }

        return $data;
    }

    public function getBudgetBreakdown($id)
    {
        $data = Budgets::where('user_id', $id)
            ->join('category', 'budgets.category', '=', 'category.id')
            ->select('category.name as category_name', DB::raw('SUM(budgets.total_amount) as total'))
            ->groupBy('category.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getMonthlySpendingTrend($id)
    {
        $data = Transaction::where('user_id', $id)
            ->where('trans_type', 'Expense')
            ->select(DB::raw("DATE_FORMAT(trans_date, '%Y-%m') as month"), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function deleteBudget($data, Budgets $budget)
    {
        if (isset($data['transactionOption'])) {
            $transactionOption = $data['transactionOption'];
        } else {
            $transactionOption = 'retain';
        }

        try {
            DB::beginTransaction();

            switch ($transactionOption) {
                case 'nullify':
                    $this->nullifyTransactions($budget);
                    break;
                case 'delete':
                    $this->deleteTransactions($budget);
                    break;
                case 'retain':
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid transaction option: {$transactionOption}");
            }

            $budget->delete();

            DB::commit();

            Alert::success('Budget deleted', 'Event successfully deleted along with associated data based on your choices.');
            return response()->json([
                'status' => 'success',
                'message' => 'Event successfully deleted along with associated data based on your choices.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Cannot Delete Budget.', 'An error occurred: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function nullifyTransactions(Budgets $budget)
    {
        Transaction::where('budget_id', $budget->id)
            ->update(['budget_id' => null]);
    }

    private function deleteTransactions(Budgets $budget)
    {
        $transactions = Transaction::where('budget_id', $budget->id)
            ->get();

        foreach ($transactions as $transaction) {
            $amount = $transaction->amount;
            $transType = $transaction->trans_type;

            if ($transType == 'Expense') {
                $transaction->wallet()->increment('amount', $amount);
                if ($transaction->budget) {
                    $transaction->budget()->increment('current_amount', $amount);
                }
            } elseif ($transType == 'Income') {
                $transaction->wallet()->decrement('amount', $amount);
                if ($transaction->budget) {
                    $transaction->budget()->decrement('current_amount', $amount);
                }
            }

            $transaction->delete();
        }
    }
}
