<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ad extends Model
{
    use HasFactory;

    protected $casts = [
        'budget' => 'float',
        'cpm' => 'float'
    ];

    public function adStats(): HasOne
    {
        return $this->hasOne(AdStat::class);
    }
}
