<?php

namespace App\Services;

use App\Models\Events;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class EventsService
{

    public function getAllEvents($perPage = 5)
    {
        $userId = session('user_id');
        return Events::where('user_id', $userId)->paginate($perPage);
    }

    public function create(array $data)
    {
        $userId = Session::get('user_id');
        $data['user_id'] = $userId;
        $data['expenses'] = 0;
        $data['income'] = 0;

        if (isset($data['attachment1']) && $data['attachment1'] instanceof UploadedFile) {
            $filePath = $data['attachment1']->store('attachments', 'public');
            $data['attachment1'] = $filePath;
        }

        $startTimestamp = Carbon::parse($data['start_date1'] . ' ' . $data['start_time1'])->format('Y-m-d H:i:s');
        $endTimestamp = Carbon::parse($data['end_date1'] . ' ' . $data['end_time1'])->format('Y-m-d H:i:s');

        $data['start_timestamp'] = $startTimestamp;
        $data['end_timestamp'] = $endTimestamp;

        $formattedData = [];
        foreach ($data as $key => $value) {
            $newKey = preg_replace('/1$/', '', $key);
            $formattedData[$newKey] = $value;
        }

        return Events::create($formattedData);
    }

    public function getEventsById($id)
    {
        return Events::findOrFail($id);
    }

    public function update(Events $event, array $data)
    {
        if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
            $filePath = $data['attachment']->store('attachments', 'public');
            $data['attachment'] = $filePath;
        }

        $startTimestamp = Carbon::parse($data['start_date'] . ' ' . $data['start_time'])->format('Y-m-d H:i:s');
        $endTimestamp = Carbon::parse($data['end_date'] . ' ' . $data['end_time'])->format('Y-m-d H:i:s');

        $data['start_timestamp'] = $startTimestamp;
        $data['end_timestamp'] = $endTimestamp;

        $event->update($data);
        return $event;
    }

    public function getCalendarJson($id)
    {
        $events = Events::where('user_id', $id)
            ->select('id', 'name', 'location', 'start_timestamp as start', 'end_timestamp as end')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => (int) $event->id,
                    'title' => $event->name,
                    'description' => $event->location,
                    'start' => $event->start,
                    'end' => $event->end,
                    'link' => ""
                ];
            });

        $filePath = public_path('events.json');
        $jsonData = json_encode($events, JSON_PRETTY_PRINT);

        if (File::put($filePath, $jsonData) === false) {
            return response()->json(['status' => 'error', 'message' => 'Unable to write to file.'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'File updated successfully.']);
    }

    public function getDashboardData()
    {
        $id = session('user_id');
        $data = [
            'totalEvents' => Events::where('user_id', $id)->count(),

            'upcomingEvents' => Events::where('user_id', $id)
                ->where('start_timestamp', '>=', Carbon::today())
                ->count(),

            'eventsThisMonth' => Events::where('user_id', $id)
                ->whereYear('start_timestamp', Carbon::now()->year)
                ->whereMonth('start_timestamp', Carbon::now()->month)
                ->count(),

            'totalExpenses' => Events::where('user_id', $id)
                ->sum('expenses') ?? 0,
        ];

        return $data;
    }

    public function getUserEvents()
    {
        $userId = session('user_id');
        return Events::where('user_id', $userId)
            ->whereNotIn('status', ['Cancelled', 'Completed'])
            ->get();
    }

    public function deleteEvent($data, Events $event)
    {
        $transactionOption = $data['transactionOption'];

        try {
            DB::beginTransaction();

            switch ($transactionOption) {
                case 'nullify':
                    $this->nullifyTransactions($event);
                    break;
                case 'delete':
                    $this->deleteTransactions($event);
                    break;
                case 'retain':
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid transaction option: {$transactionOption}");
            }

            $event->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Event successfully deleted along with associated data based on your choices.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function nullifyTransactions(Events $event)
    {
        Transaction::where('table_ref', 'events')
            ->where('id_ref', $event->id)
            ->update(['id_ref' => null, 'table_ref' => null]);
    }

    private function deleteTransactions(Events $event)
    {
        $transactions = Transaction::where('table_ref', 'events')
            ->where('id_ref', $event->id)
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
