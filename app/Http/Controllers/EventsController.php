<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Http\Requests\StoreEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use App\Services\EventsService;
use App\Services\TransactionService;

class EventsController extends Controller
{
    protected $eventsService;
    protected $transactionService;

    public function __construct(EventsService $eventsService, TransactionService $transactionService)
    {
        $this->eventsService = $eventsService;
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = $this->eventsService->getAllEvents();
        $data = $this->eventsService->getDashboardData();
        return view('events.index', compact('events', 'data'));
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
    public function store(StoreEventsRequest $request)
    {
        $data = $request->validated();
        $this->eventsService->create($data);
        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Events $event)
    {
        $event = $this->eventsService->getEventsById($event->id);
        return $event->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Events $events)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventsRequest $request, Events $event)
    {
        $data = $request->validated();
        $this->eventsService->update($event, $data);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Events $event)
    {
        $this->eventsService->deleteEvent($_POST, $event);
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }

    public function getTransactionsByEvent(){
        $transactions = $this->transactionService->getTransactionsByEvent($_GET['id']);
        return $transactions->toJson(JSON_PRETTY_PRINT);
    }

    public function checkEvent(){
        $transactions = $this->transactionService->checkEvent($_GET['id']);
        return $transactions;
    }
}
