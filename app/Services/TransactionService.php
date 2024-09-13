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
        // Handle file upload
        if (isset($data['attachment_expense']) && $data['attachment_expense'] instanceof UploadedFile) {
            $filePath = $data['attachment_expense']->store('attachments', 'public');
            $data['attachment_expense'] = $filePath;
            $transaction->attachment = $filePath;
        }

        // Update Event Association
        if (isset($data['event_expense'])) {
            if ((int) $data['event_expense'] != $transaction->id_ref) {
                // Remove expense from old event
                $oldEvent = Events::findOrFail($transaction->id_ref);
                $newOldBalance = $oldEvent->expenses - $transaction->amount;
                $oldEvent->update(['expenses' => $newOldBalance]);

                // Add expense to new event
                $newEvent = Events::findOrFail($data['event_expense']);
                $newNewBalance = $newEvent->expenses + $data['amount_expense'];
                $newEvent->update(['expenses' => $newNewBalance]);

                // Update transaction event reference
                $transaction->id_ref = $data['event_expense'];
            } else {
                // If the event remains the same, adjust expense within the same event
                $event = Events::findOrFail($transaction->id_ref);
                $event->update(['expenses' => $event->expenses - $transaction->amount + $data['amount_expense']]);
            }
        }

        // Update Wallet Association
        if ((int) $data['wallet_expense'] != $transaction->wallet_id) {
            // Deduct the old transaction amount from the old wallet
            $oldWallet = Wallets::findOrFail($transaction->wallet_id);
            $newOldWalletBalance = $oldWallet->amount - $transaction->amount;
            $oldWallet->update(['amount' => $newOldWalletBalance]);

            // Add the new transaction amount to the new wallet
            $newWallet = Wallets::findOrFail($data['wallet_expense']);
            $newNewWalletBalance = $newWallet->amount + $data['amount_expense'];
            $newWallet->update(['amount' => $newNewWalletBalance]);

            // Update the wallet reference in the transaction
            $transaction->wallet_id = $data['wallet_expense'];
        } else {
            // If the wallet remains the same but the amount has changed
            $wallet = Wallets::findOrFail($transaction->wallet_id);

            // Calculate the difference between the old and new amounts
            $amountDifference = $transaction->amount - $data['amount_expense'];

            // Update the wallet balance with the difference
            $wallet->update(['amount' => $wallet->amount + $amountDifference]);
        }

        // Update Budget Association
        if ($data['allocate_budget_update'] == 'on') {
            if ($data['update_budget'] != $transaction->budget_id) {
                // If the budget has changed, revert old budget and update new budget
                if ($transaction->budget_id != null) {
                    $oldBudget = $this->budgetsService->getBudgetById($transaction->budget_id);
                    if ($oldBudget) {
                        $oldBalance = $oldBudget->current_amount + $transaction->amount;
                        $oldBudget->update(['current_amount' => $oldBalance]);
                    } else {
                        throw new \Exception('Old Budget not found');
                    }
                }

                // Update the new budget
                $newBudget = $this->budgetsService->getBudgetById($data['update_budget']);
                if ($newBudget) {
                    $newBalance = $newBudget->current_amount - $data['amount_expense'];
                    $newBudget->update(['current_amount' => $newBalance]);
                    $transaction->budget_id = $data['update_budget']; // Update transaction with new budget ID
                } else {
                    throw new \Exception('New Budget not found');
                }
            } else {
                // If the budget remains the same, just update the amount difference
                $budget = $this->budgetsService->getBudgetById($transaction->budget_id);
                if ($budget) {
                    $amountDifference = $transaction->amount - $data['amount_expense'];
                    $newBalance = $budget->current_amount + $amountDifference;
                    $budget->update(['current_amount' => $newBalance]);
                } else {
                    throw new \Exception('Budget not found');
                }
            }
        } else {
            // If budget allocation is turned off, remove it from the current budget
            if ($transaction->budget_id != null) {
                $oldBudget = $this->budgetsService->getBudgetById($transaction->budget_id);
                if ($oldBudget) {
                    $oldBalance = $oldBudget->current_amount + $transaction->amount;
                    $oldBudget->update(['current_amount' => $oldBalance]);
                } else {
                    throw new \Exception('Old Budget not found');
                }
            }
            $transaction->budget_id = null;
        }

        $transaction->update([
            'category' => $data['category_expense'],
            'amount' => $data['amount_expense'],
            'description' => $data['desc_expense'],
            'trans_date' => $data['trans_date_expense'],
        ]);

        return $transaction;
    }
}
