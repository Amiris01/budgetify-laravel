<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Services\CategoryService;
use App\Services\EventsService;
use App\Services\TransactionService;
use App\Services\WalletsService;

class TransactionController extends Controller
{
    protected $transactionService;
    protected $walletsService;
    protected $categoryService;
    protected $eventsService;

    public function __construct(TransactionService $transactionService, WalletsService $walletsService, CategoryService $categoryService, EventsService $eventsService)
    {
        $this->transactionService = $transactionService;
        $this->walletsService = $walletsService;
        $this->categoryService = $categoryService;
        $this->eventsService = $eventsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = $this->transactionService->getAllTransaction();
        $wallets = $this->walletsService->getUserWallets();
        $incomeCategory = $this->categoryService->getIncomeCategory();
        $expenseCategory = $this->categoryService->getExpenseCategory();
        $userEvents = $this->eventsService->getUserEvents();
        $data = $this->transactionService->getTransactionData();
        return view('transactions.index', compact('transactions', 'wallets', 'incomeCategory', 'expenseCategory', 'userEvents', 'data'));
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
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $this->transactionService->create($data);
        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction = $this->transactionService->getTransactionById($transaction->id);
        return $transaction->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        if($data['trans_type'] == 'Income'){
            $this->transactionService->updateIncome($transaction, $data);
        }else{
            $this->transactionService->updateExpense($transaction, $data);
        }
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->transactionService->deleteTransaction($transaction);
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully');
    }

    public function getRecentTransaction()
    {
        $id = session('user_id');
        $transaction = $this->transactionService->getRecentTransaction($id);
        return $transaction->toJson(JSON_PRETTY_PRINT);
    }

    public function getTransactionTypeDist()
    {
        $id = session('user_id');
        return $this->transactionService->getTransactionTypeDist($id);
    }

    public function getNetWorthOverTime()
    {
        $id = session('user_id');
        return $this->transactionService->getNetWorthOverTime($id);
    }
}
