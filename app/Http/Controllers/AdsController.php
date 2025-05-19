<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdChartCollection;
use App\Http\Resources\AdCollection;
use App\Models\Ad;
use App\Services\AdService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    protected AdService $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    public function index(Request $request)
    {
        $ad = $this->adService->getAdList($request->get('sort'), $request->get('direction'));
        $ad = new AdCollection($ad);

        return response()->json($ad);
    }

    public function chart(Request $request, Ad $ad)
    {
        $filter = json_decode($request->get('filter'), true);
        $chartData = $this->adService->getChartData($ad, $filter);
        $chartData = new AdChartCollection($chartData);

        return response()->json($chartData);
    }
}
