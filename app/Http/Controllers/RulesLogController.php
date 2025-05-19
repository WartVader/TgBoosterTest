<?php

namespace App\Http\Controllers;

use App\Http\Resources\RuleLogCollection;
use App\Services\RuleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RulesLogController extends Controller
{
    protected RuleService $ruleService;

    public function __construct(RuleService $ruleService)
    {
        $this->ruleService = $ruleService;
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort') ?? 'created_at';
        $direction = $request->get('direction') ?? 'asc';
        $filter = $request->get('filter') ?? [
            [
                'name' => 'created_at',
                'between' => [
                    Carbon::now()->subDays(),
                    Carbon::now()->toDateTimeString()
                ]
            ]
        ];
        $logs = $this->ruleService->getLogList($sort, $direction, $filter);
        $logs = new RuleLogCollection($logs);

        return response()->json($logs);
    }
}
