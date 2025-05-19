<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'views',
        'clicks',
        'spent',
    ];

    protected $casts = [
        'spent' => 'float',
    ];
}
