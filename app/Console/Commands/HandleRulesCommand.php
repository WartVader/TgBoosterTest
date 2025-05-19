<?php

namespace App\Console\Commands;

use App\Models\Rule;
use App\Services\RuleService;
use Illuminate\Console\Command;

class HandleRulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle-rules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handling all rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var RuleService $ruleService */
        $ruleService = app(RuleService::class);
        $rules = Rule::query()->where('is_active', '=', true)->get();

        foreach($rules as $rule) {
            $ruleService->handleRule($rule);
        }
    }
}
