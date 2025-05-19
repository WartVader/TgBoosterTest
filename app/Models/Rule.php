<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array conditions
 * @property array actions
 * @property boolean is_active
 * @property string humanize_rule
 */
class Rule extends Model
{
    use HasFactory;

    public const CONDITION_COLUMNS = [
        'budget'         => 'бюджет',
        'cpm'            => 'CPM',
        'adStats.views'  => 'просмотры',
        'adStats.spent'  => 'траты',
        'adStats.clicks' => 'клики',
    ];

    public const ACTIONS_COLUMNS = [
        'budget' => 'бюджет',
        'cpm'    => 'CPM',
    ];

    public const ACTIONS_OPERATIONS = [
        'increment' => 'увеличить',
        'decrement' => 'уменьшить'
    ];

    protected $fillable = [
        'conditions',
        'actions',
        'is_active'
    ];

    protected $casts = [
        'conditions' => 'array',
        'actions' => 'array',
    ];

    public function getHumanRuleAttribute()
    {
        $result = 'Если ';
        $i = 0;
        $countConditions = count($this->conditions);

        foreach ($this->conditions as $condition) {
            $i++;
            $ruColumn = self::CONDITION_COLUMNS[$condition['column']];
            $humanOperator = match ($condition['operator']) {
                '>=' => '≥',
                '<=' => '≤',
                default => $condition['operator']
            };
            $value = $condition['value'];
            $ruLogicOperator = match ($countConditions == $i ? null : $condition['operator']) {
                'and' => 'И',
                'or' => 'ИЛИ',
                default => ''
            };
            $result .= "$ruColumn $humanOperator $value $ruLogicOperator";
        }

        $result .= 'то ';

        foreach($this->actions as $action) {
            $ruOperation = self::ACTIONS_OPERATIONS[$action['operation']];
            $ruColumn = self::ACTIONS_COLUMNS[$action['column']];
            $amount = $action['amount'];
            $result .= "$ruOperation $ruColumn на $amount";
        }

        return $result;
    }
}
