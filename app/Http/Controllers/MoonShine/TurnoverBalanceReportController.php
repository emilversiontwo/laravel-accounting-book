<?php

namespace App\Http\Controllers\MoonShine;

use App\Http\Controllers\Controller;
use App\Modules\Transaction\Services\TurnoverBalanceReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TurnoverBalanceReportController extends Controller
{
    public function __construct(
        private readonly TurnoverBalanceReportService $service,
    ) {
    }

    public function index(Request $request): View
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->input('to', now()->toDateString()))->endOfDay();

        $report = $this->service->build($from, $to);

        return view('moonshine.reports.turnover-balance', $report);
    }
}
