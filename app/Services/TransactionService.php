<?php

namespace App\Services;

use App\Models\Budgets;
use App\Models\Events;
use App\Models\Transaction;
use App\Models\Wallets;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TransactionService
{

    protected $walletsService;
    protected $budgetsService;
    protected $eventsService;

    public function __construct(WalletsService $walletsService, BudgetsService $budgetsService, EventsService $eventsService)
    {
        $this->walletsService = $walletsService;
        $this->budgetsService = $budgetsService;
        $this->eventsService = $eventsService;
    }

    public function getAllTransaction($perPage = 5)
    {
        $userId = session('user_id');
        return Transaction::where('user_id', $userId)
            ->with('wallet', 'budget', 'event')
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        $userId = Session::get('user_id');
        $data['user_id'] = $userId;

        if ($data['trans_type'] == 'Income') {
            if (isset($data['attachment1']) && $data['attachment1'] instanceof UploadedFile) {
                $filePath = $data['attachment1']->store('attachments', 'public');
                $data['attachment1'] = $filePath;
            }

            if (!empty($data['table_ref1'])) {
                if ($data['table_ref1'] == 'events') {
                    $data['id_ref'] = $data['event_id1'];
                    $event = $this->eventsService->getEventsById($data['event_id1']);
                    if ($event) {
                        $newBalance = $event->income + $data['amount1'];
                        $event->update(['income' => $newBalance]);
                    } else {
                        throw new \Exception('Event not found');
                    }
                } else {
                    $data['id_ref'] = null;
                }
            }

            $formattedData = [];
            foreach ($data as $key => $value) {
                $newKey = preg_replace('/1$/', '', $key);
                $formattedData[$newKey] = $value;
            }

            $wallet = $this->walletsService->getWalletById($formattedData['wallet_id']);
            if ($wallet) {
                $newBalance = $wallet->amount + $formattedData['amount'];
                $wallet->update(['amount' => $newBalance]);
            } else {
                throw new \Exception('Wallet not found');
            }
        } else {
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('attachments', 'public');
                $data['attachment'] = $filePath;
            }

            if (!empty($data['table_ref'])) {
                if ($data['table_ref'] == 'events') {
                    $data['id_ref'] = $data['event_id'];
                    $event = $this->eventsService->getEventsById($data['event_id']);
                    if ($event) {
                        $newBalance = $event->expenses + $data['amount'];
                        $event->update(['expenses' => $newBalance]);
                    } else {
                        throw new \Exception('Event not found');
                    }
                } else {
                    $data['id_ref'] = null;
                }
            }

            $wallet = $this->walletsService->getWalletById($data['wallet_id']);
            if ($wallet) {
                $newBalance = $wallet->amount - $data['amount'];
                $wallet->update(['amount' => $newBalance]);
            } else {
                throw new \Exception('Wallet not found');
            }

            if (!empty($data['budget_id'])) {
                $budget = $this->budgetsService->getBudgetById($data['budget_id']);
                if ($budget) {
                    $newBalance = $budget->current_amount - $data['amount'];
                    $budget->update(['current_amount' => $newBalance]);
                } else {
                    throw new \Exception('Budget not found');
                }
            }
        }

        if ($data['trans_type'] == 'Income') {
            return Transaction::create($formattedData);
        } else {
            return Transaction::create($data);
        }
    }

    public function getTransactionById($id)
    {
        return Transaction::with('categories', 'wallet', 'budget', 'event')->find($id);
    }

    public function getTransactionsByEvent($id)
    {
        return Transaction::with('categories', 'wallet', 'budget', 'event')
            ->where(['id_ref' => $id, 'table_ref' => 'events'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTransactionByBudget($id)
    {
        return Transaction::with('categories', 'wallet', 'budget', 'event')
            ->where(['budget_id' => $id])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTransactionByWallet($id)
    {
        return Transaction::with('categories', 'wallet', 'budget', 'event')
            ->where(['wallet_id' => $id])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTransactionData()
    {

        $id = session('user_id');
        $data = [];

        $data['totalTransaction'] = Transaction::where('user_id', $id)->count();

        $data['totalIncome'] = Transaction::where('user_id', $id)
            ->where('trans_type', 'Income')
            ->sum('amount');

        $data['totalExpenses'] = Transaction::where('user_id', $id)
            ->where('trans_type', 'Expense')
            ->sum('amount');

        $data['totalBalance'] = Wallets::where('user_id', $id)->sum('amount');

        return $data;
    }

    public function getRecentTransaction($id)
    {
        return Transaction::with('categories', 'wallet', 'budget', 'event')
            ->where(['user_id' => $id])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTransactionTypeDist($id)
    {

        $incomeCount = Transaction::where('user_id', $id)
            ->where('trans_type', 'Income')
            ->count();

        $expenseCount = Transaction::where('user_id', $id)
            ->where('trans_type', 'Expense')
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'incomeCount' => $incomeCount,
                'expenseCount' => $expenseCount
            ]
        ]);
    }

    public function getNetWorthOverTime($id)
    {
        $data = Transaction::select(
            DB::raw("DATE_FORMAT(trans_date, '%Y-%m') as month"),
            DB::raw("SUM(amount) as net_worth")
        )
            ->where('user_id', $id)
            ->where('trans_type', 'Income')
            ->groupBy(DB::raw("DATE_FORMAT(trans_date, '%Y-%m')"))
            ->orderBy('trans_date')
            ->get();

        $result = ['months' => [], 'netWorth' => []];
        foreach ($data as $row) {
            $result['months'][] = $row->month;
            $result['netWorth'][] = (float) $row->net_worth;
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function checkEvent($id)
    {
        $transactionCount = Transaction::where('table_ref', 'events')
            ->where('id_ref', $id)
            ->count();

        $response = [
            'hasTransactions' => $transactionCount > 0
        ];

        return response()->json($response);
    }

    public function deleteTransaction(Transaction $transaction)
    {

        $transaction = Transaction::with('categories', 'wallet', 'budget', 'event')
            ->where(['id' => $transaction->id])
            ->first();

        if ($transaction->trans_type == 'Expense') {
            if ($transaction->table_ref == 'events' && $transaction->id_ref) {
                $event = Events::find($transaction->id_ref);
                if ($event) {
                    $event->decrement('expenses', $transaction->amount);
                }
            }

            if ($transaction->budget_id) {
                $budget = Budgets::find($transaction->budget_id);
                if ($budget) {
                    $budget->increment('current_amount', $transaction->amount);
                }
            }

            if ($transaction->wallet_id) {
                $wallet = Wallets::find($transaction->wallet_id);
                if ($wallet) {
                    $wallet->increment('amount', $transaction->amount);
                }
            }
        } else if ($transaction->trans_type == 'Income') {
            if ($transaction->table_ref == 'events' && $transaction->id_ref) {
                $event = Events::find($transaction->id_ref);
                if ($event) {
                    $event->decrement('income', $transaction->amount);
                }
            }
            if ($transaction->wallet_id) {
                $wallet = Wallets::find($transaction->wallet_id);
                if ($wallet) {
                    $wallet->decrement('amount', $transaction->amount);
                }
            }
        }

        $transaction->delete();
    }

    public function checkBudget($id)
    {
        $transactionCount = Transaction::where('budget_id', $id)
            ->count();

        $response = [
            'hasTransactions' => $transactionCount > 0
        ];

        return response()->json($response);
    }

    public function updateIncome(Transaction $transaction, array $data)
    {
        if (isset($data['attachment_income']) && $data['attachment_income'] instanceof UploadedFile) {
            $filePath = $data['attachment_income']->store('attachments', 'public');
            $data['attachment_income'] = $filePath;
        }

        if ((int) $data['event_id_income'] != $transaction->id_ref) {
            $data['id_ref'] = $data['event_id_income'];
            $oldEvent = Events::findOrFail($transaction->id_ref);
            $newOldBalance = $oldEvent->income - $transaction->amount;
            $oldEvent->update(['income' => $newOldBalance]);

            $newEvent = Events::findOrFail($data['event_id_income']);
            $newNewBalance = $newEvent->income + $data['amount_income'];
            $newEvent->update(['income' => $newNewBalance]);

            $transaction->id_ref = $data['event_id_income'];
        } else {
            $event = Events::findOrFail($transaction->id_ref);
            $event->update(['income' => $event->income - $transaction->amount + $data['amount_income']]);
        }

        if ((int) $data['wallet_id_income'] != $transaction->wallet_id) {
            $oldWallet = Wallets::findOrFail($transaction->wallet_id);
            $newOldWalletBalance = $oldWallet->amount - $transaction->amount;
            $oldWallet->update(['amount' => $newOldWalletBalance]);

            $newWallet = Wallets::findOrFail($data['wallet_id_income']);
            $newNewWalletBalance = $newWallet->amount + $data['amount_income'];
            $newWallet->update(['amount' => $newNewWalletBalance]);

            $transaction->wallet_id = $data['wallet_id_income'];
        } else {
            $wallet = Wallets::findOrFail($transaction->wallet_id);
            $wallet->update(['amount' => $wallet->amount - $transaction->amount + $data['amount_income']]);
        }

        $formattedData = [];

        foreach ($data as $key => $value) {
            if (strpos($key, '_income') !== false) {
                $newKey = str_replace('_income', '', $key);
                $formattedData[$newKey] = $value;
            } else {
                $formattedData[$key] = $value;
            }
        }

        $transaction->update($formattedData);
        return $transaction;
    }

    public function updateExpense(Transaction $transaction, array $data)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            $oldAmount = $transaction->amount;
            $newAmount = $data['amount_expense'];
            $amountDifference = $oldAmount - $newAmount;

            // Update the transaction first
            $transaction->fill([
                'category' => $data['category_expense'],
                'amount' => $newAmount,
                'description' => $data['desc_expense'],
                'trans_date' => $data['trans_date_expense'],
            ]);

            // Handle file upload
            if (isset($data['attachment_expense']) && $data['attachment_expense'] instanceof UploadedFile) {
                $filePath = $data['attachment_expense']->store('attachments', 'public');
                $transaction->attachment = $filePath;
            }

            // Update Event Association
            if (isset($data['event_expense'])) {
                $newEventId = (int) $data['event_expense'];
                if ($newEventId != $transaction->id_ref) {
                    // Remove expense from old event
                    $oldEvent = Events::findOrFail($transaction->id_ref);
                    $oldEvent->expenses -= $oldAmount;
                    $oldEvent->save();

                    // Add expense to new event
                    $newEvent = Events::findOrFail($newEventId);
                    $newEvent->expenses += $newAmount;
                    $newEvent->save();

                    $transaction->id_ref = $newEventId;
                } else {
                    // If the event remains the same, adjust expense within the same event
                    $event = Events::findOrFail($transaction->id_ref);
                    $event->expenses -= $amountDifference;
                    $event->save();
                }
            }

            // Update Wallet Association
            $newWalletId = (int) $data['wallet_expense'];
            if ($newWalletId != $transaction->wallet_id) {
                // Add the old transaction amount back to the old wallet
                $oldWallet = Wallets::findOrFail($transaction->wallet_id);
                $oldWallet->amount += $oldAmount;
                $oldWallet->save();

                // Subtract the new transaction amount from the new wallet
                $newWallet = Wallets::findOrFail($newWalletId);
                $newWallet->amount -= $newAmount;
                $newWallet->save();

                $transaction->wallet_id = $newWalletId;
            } else {
                // If the wallet remains the same but the amount has changed
                $wallet = Wallets::findOrFail($transaction->wallet_id);
                if ($data['amount_expense'] > $wallet->amount) {
                    $wallet->amount -= $amountDifference;
                } else {
                    $wallet->amount += $amountDifference;
                }
                $wallet->save();
            }

            // Update Budget Association
            if (isset($data['allocate_budget_update'])) {
                if ($data['allocate_budget_update'] == 'on') {
                    $newBudgetId = $data['update_budget'];
                    if ($newBudgetId != $transaction->budget_id) {
                        // If the budget has changed, revert old budget and update new budget
                        if ($transaction->budget_id != null) {
                            $oldBudget = $this->budgetsService->getBudgetById($transaction->budget_id);
                            $oldBudget->current_amount += $oldAmount;
                            $oldBudget->save();
                        }

                        // Update the new budget
                        $newBudget = $this->budgetsService->getBudgetById($newBudgetId);
                        $newBudget->current_amount -= $newAmount;
                        $newBudget->save();
                        $transaction->budget_id = $newBudgetId;
                    } else {
                        // If the budget remains the same, just update the amount difference
                        $budget = $this->budgetsService->getBudgetById($transaction->budget_id);
                        $budget->current_amount += $amountDifference;
                        $budget->save();
                    }
                } else {
                    // If budget allocation is turned off, remove it from the current budget
                    if ($transaction->budget_id != null) {
                        $oldBudget = $this->budgetsService->getBudgetById($transaction->budget_id);
                        $oldBudget->current_amount += $oldAmount;
                        $oldBudget->save();
                        $transaction->budget_id = null;
                    }
                }
            } else {
                if ($transaction->budget_id != null) {
                    $oldBudget = $this->budgetsService->getBudgetById($transaction->budget_id);
                    $oldBudget->current_amount += $oldAmount;
                    $oldBudget->save();
                }
                $transaction->budget_id = null;
            }

            $transaction->save();

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
