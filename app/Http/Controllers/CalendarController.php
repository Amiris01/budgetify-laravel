<?php

namespace App\Http\Controllers;

use App\Services\EventsService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $eventsService;

    public function __construct(EventsService $eventsService)
    {
        $this->eventsService = $eventsService;
    }

    public function getCalendarData(){
        $userId = session('user_id');
        return $this->eventsService->getCalendarJson($userId);
    }
}
