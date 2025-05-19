<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdStat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ad = Ad::create([
            'name' => 'Test ad',
            'budget' => 100,
            'cpm' => 1,
            'is_active' => true
        ]);
        AdStat::create([
            'ad_id' => $ad->id,
            'views' => 100,
            'clicks' => 100,
            'spent' => 10
        ]);
    }
}
