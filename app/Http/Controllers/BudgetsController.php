<?php

namespace App\Http\Controllers;

use App\Models\Budgets;
use App\Http\Requests\StoreBudgetsRequest;
use App\Http\Requests\UpdateBudgetsRequest;
use App\Services\BudgetsService;
use App\Services\CategoryService;
use App\Services\TransactionService;

class BudgetsController extends Controller
{
    protected $categoryService;
    protected $budgetsService;
    protected $transactionService;

    public function __construct(CategoryService $categoryService, BudgetsService $budgetsService, TransactionService $transactionService)
    {
        $this->categoryService = $categoryService;
        $this->budgetsService = $budgetsService;
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getExpenseCategory();
        $budgets = $this->budgetsService->getAllBudgets();
        $data = $this->budgetsService->getDashboardData();
        return view('budgets.index', compact('categories', 'budgets', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudgetsRequest $request)
    {
        $data = $request->validated();
        $this->budgetsService->create($data);
        return redirect()->route('budgets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budgets $budget)
    {
        $budget = $this->budgetsService->getBudgetById($budget->id);
        return $budget->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budgets $budgets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetsRequest $request, Budgets $budget)
    {
        $data = $request->validated();
        $this->budgetsService->update($budget, $data);

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budgets $budget)
    {
        $this->budgetsService->deleteBudget($_POST, $budget);
        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully');
    }

    public function getBudgetByCategory()
    {
        return $this->budgetsService->getBudgetByCategory($_GET['category']);
    }

    public function getTransactionByBudget()
    {
        $transactions = $this->transactionService->getTransactionByBudget($_GET['id']);
        return $transactions->toJson(JSON_PRETTY_PRINT);
    }

    public function getBudgetBreakdown()
    {
        $id = session('user_id');
        return $this->budgetsService->getBudgetBreakdown($id);
    }

    public function getMonthlySpendingTrend()
    {
        $id = session('user_id');
        return $this->budgetsService->getMonthlySpendingTrend($id);
    }

    public function getBudgetVsActual()
    {
        $id = session('user_id');
        return $this->categoryService->getBudgetVsActual($id);
    }

    public function getOverBudgetAlerts()
    {
        $id = session('user_id');
        return $this->categoryService->getOverBudgetAlerts($id);
    }

    public function checkBudget(){
        $transactions = $this->transactionService->checkBudget($_GET['id']);
        return $transactions;
    }
}
