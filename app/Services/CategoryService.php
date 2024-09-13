<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function getIncomeCategory()
    {
        return Category::where('tag', 'Income')->get();
    }

    public function getExpenseCategory()
    {
        return Category::where('tag', 'Expense')->get();
    }

    public function getBudgetVsActual($id)
    {
        $query = DB::table('category')
            ->select(
                'category.name as category',
                DB::raw('IFNULL(SUM(budgets.total_amount), 0) as budgeted'),
                DB::raw('IFNULL(SUM(transactions.amount), 0) as actual')
            )
            ->leftJoin('budgets', function ($join) use ($id) {
                $join->on('budgets.category', '=', 'category.id')
                    ->where('budgets.user_id', '=', $id);
            })
            ->leftJoin('transactions', function ($join) use ($id) {
                $join->on('transactions.category', '=', 'category.id')
                    ->where('transactions.user_id', '=', $id);
            })
            ->where('category.tag', 'Expense')
            ->groupBy('category.name')
            ->orderBy('category.name');

        $result = [
            'labels' => [],
            'budgeted' => [],
            'actual' => []
        ];

        foreach ($query->cursor() as $row) {
            $result['labels'][] = $row->category;
            $result['budgeted'][] = (float) $row->budgeted;
            $result['actual'][] = (float) $row->actual;
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function getOverBudgetAlerts($id)
    {
        $query = DB::table('category')
            ->select(
                'category.name as category',
                DB::raw('IFNULL(SUM(transactions.amount), 0) as actual'),
                'budgets.total_amount as budgeted'
            )
            ->join('budgets', function ($join) use ($id) {
                $join->on('category.id', '=', 'budgets.category')
                    ->where('budgets.user_id', $id);
            })
            ->leftJoin('transactions', function ($join) use ($id) {
                $join->on('transactions.category', '=', 'category.id')
                    ->where('transactions.user_id', $id);
            })
            ->groupBy('category.name', 'budgets.total_amount')
            ->havingRaw('actual > budgeted');

        $result = [];

        foreach ($query->cursor() as $row) {
            $amountOverBudget = number_format($row->actual - $row->budgeted, 2);
            $result[] = "You are over-budget in {$row->category} by RM {$amountOverBudget}";
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }
}
