<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\Rule;
use App\Models\RuleLog;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RuleService
{
    public function handleRule(Rule $rule)
    {
        $errMessage = null;

        try {
            $query = $this->getConditionQuery($rule->conditions);
            $this->executeAction($query, $rule->actions);
            $ads = $query->get();
            $message = "Успешная обработка";
        } catch (\Exception $e) {
            $errMessage = $e->getMessage();
            $message = "Ошибка при обработке";
            $ads = $ads ?? [null];
        }

        $this->logRule($rule, $ads, $message, $errMessage);
    }

    protected function logRule(Rule $rule, $ads, $message, $errMessage)
    {
        /** @var Ad|null $ad */
        foreach ($ads as $ad) {
            if (!$errMessage && $ad !== null) {
                foreach (Rule::ACTIONS_COLUMNS as $column => $ruColumn) {
                    $changes[$column] = [
                        'value' => $ad->$column,
                        'label' => $ruColumn
                    ];
                }
            } else {
                $changes = null;
            }

            RuleLog::create([
                'ad_id' => $ad?->id,
                'rule_id' => $rule->id,
                'message' => $message,
                'error_message' => $errMessage,
                'changes' => $changes
            ]);
        }
    }

    public function getRuleList($sort = null, $direction = null)
    {
        $query = Rule::query();

        if ($sort) {
            $query->orderBy($sort, $direction ?? 'asc');
        }

        return $query->paginate(10);
    }

    public function getLogList($sort = null, $direction = null, $filter = [])
    {
        $query = RuleLog::query()->with('rule');

        if ($sort) {
            $query->orderBy($sort, $direction ?? 'asc');
        }

        foreach ($filter as $column) {
            if (isset($column['between'])) {
                $query->whereBetween($column['name'], $column['between']);
            }
            if (isset($column['where'])) {
                $query->where($column['name'], $column['where']);
            }
        }

        return $query->paginate(10);
    }

    /**
     * @param array $conditions
     * @return Builder
     */
    protected function getConditionQuery(array $conditions): Builder
    {
        $adQuery = Ad::query();

        foreach ($conditions as $condition) {
            // проверка поля на связь с другой таблицей
            if (str_contains($condition['column'], '.')) {
                $column = explode('.', $condition['column']);
                $adQuery->whereHas($column[0], function ($q) use ($condition, $column) {
                    $q->where(
                        $column[1], // adStats.clicks
                        $condition['operator'], // >=
                        $condition['value'], // 10
                        $condition['logicalOperator'] // AND
                    );
                });
            } else {
                $adQuery->where(
                    $condition['column'], // cpm
                    $condition['operator'], // >=
                    $condition['value'], // 10
                    $condition['logicalOperator'] ?? 'and' // AND
                );
            }
        }

        return $adQuery;
    }

    protected function executeAction(Builder $query, array $actions): int
    {
        $executed = 0;

        foreach ($actions as $action) {
            $executed += $query->{$action['operation']}($action['column'], $action['amount']);
        }

        return $executed;
    }

    public function createRule($conditions, $actions)
    {
        return Rule::create([
            'conditions' => $conditions,
            'actions'    => $actions
        ]);
    }
}
