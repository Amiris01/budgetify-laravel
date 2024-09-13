<?php

namespace App\Http\Controllers;

use App\Models\Wallets;
use App\Http\Requests\StoreWalletsRequest;
use App\Http\Requests\UpdateWalletsRequest;
use App\Services\FinancialInstituteService;
use App\Services\TransactionService;
use App\Services\WalletsService;
use App\Services\WalletTypeService;

class WalletsController extends Controller
{

    protected $walletsService;
    protected $walletTypeService;
    protected $financialInstituteService;
    protected $transactionService;

    public function __construct(WalletsService $walletsService, WalletTypeService $walletTypeService, FinancialInstituteService $financialInstituteService, TransactionService $transactionService)
    {
        $this->walletsService = $walletsService;
        $this->walletTypeService = $walletTypeService;
        $this->financialInstituteService = $financialInstituteService;
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = $this->walletsService->getAllWallets();
        $wallet_type = $this->walletTypeService->getAllWalletType();
        $fin_institute = $this->financialInstituteService->getAllFinancialInstitute();
        return view('wallets.index', compact('wallets', 'wallet_type', 'fin_institute'));
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
    public function store(StoreWalletsRequest $request)
    {
        $data = $request->validated();
        $this->walletsService->create($data);
        return redirect()->route('wallets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallets $wallet)
    {
        $wallet = $this->walletsService->getWalletById($wallet->id);
        return $wallet->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallets $wallets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletsRequest $request, Wallets $wallet)
    {
        $data = $request->validated();
        $this->walletsService->update($wallet, $data);

        return redirect()->route('wallets.index')->with('success', 'Wallet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallets $wallet)
    {
        $this->walletsService->deleteWallet($wallet->id);
        return redirect()->route('wallets.index')->with('success', 'Wallet deleted successfully');
    }

    public function getTransactionByWallet()
    {
        $transactions = $this->transactionService->getTransactionByWallet($_GET['id']);
        return $transactions->toJson(JSON_PRETTY_PRINT);
    }

    public function getExpenseBreakdown()
    {
        $id = session('user_id');
        return $this->walletsService->getExpenseBreakdown($id);
    }

    public function getMonthlyIncomeTrend()
    {
        $id = session('user_id');
        return $this->walletsService->getMonthlyIncomeTrend($id);
    }
}
