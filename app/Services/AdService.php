<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\Rule;
use App\Models\RuleLog;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AdService
{
    public function getAdList($sort = null, $direction = null)
    {
        $query = Ad::query();

        if ($sort) {
            $query->orderBy($sort, $direction ?? 'asc');
        }

        return $query->paginate(10);
    }

    public function getChartData(Ad $ad, ?array $filter = null)
    {
        $query = RuleLog::query()
            ->select(['changes', 'created_at'])
            ->where('ad_id', $ad->id)
            ->whereNull('error_message')
            ->orderBy('created_at', 'desc');

        if ($filter) {
            foreach ($filter as $column) {
                if (isset($column['between'])) {
                    $query->whereBetween($column['name'], $column['between']);
                }
                if (isset($column['where'])) {
                    $query->where($column['name'], $column['where']);
                }
            }
        }

        return $query->paginate(50);

    }
}
