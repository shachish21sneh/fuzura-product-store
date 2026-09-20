<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReplacementTimelineService;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    protected ReplacementTimelineService $timelineService;

    public function __construct(ReplacementTimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function index(Request $request)
    {
        $serial = $request->query('serial_number');
        $result = null;

        if (!empty($serial)) {
            $result = $this->timelineService->getTimeline($serial);
        }

        return view('admin.search.index', [
            'searchedSerial' => $serial,
            'result' => $result,
        ]);
    }
}
