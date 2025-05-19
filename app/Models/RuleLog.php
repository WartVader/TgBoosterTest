<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'rule_id',
        'message',
        'error_message',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function rule()
    {
        return $this->hasOne(Rule::class, 'id', 'rule_id');
    }
}
