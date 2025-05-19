<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleRequest;
use App\Http\Resources\RuleApiResource;
use App\Http\Resources\RuleCollection;
use App\Http\Resources\RuleResource;
use App\Models\Rule;
use App\Services\RuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    protected RuleService $ruleService;

    public function __construct(RuleService $ruleService)
    {
        $this->ruleService = $ruleService;
    }

    public function getParams()
    {
        return new JsonResponse([
            'conditions' => [
                'columns' => Rule::CONDITION_COLUMNS
            ],
            'actions' =>[
                'columns' => Rule::ACTIONS_COLUMNS,
                'operations' => Rule::ACTIONS_OPERATIONS
            ]
        ]);
    }

    public function index(Request $request)
    {
        $rules = $this->ruleService->getRuleList($request->get('sort'), $request->get('direction'));
        $rules = new RuleCollection($rules);

        return response()->json($rules);
    }

    public function show(Rule $rule)
    {
        return new RuleApiResource($rule);
    }

    public function store(RuleRequest $request)
    {
        $data = $request->validated();

        try {
            $this->ruleService->createRule($data['conditions'], $data['actions']);
        } catch (\Exception $e) {
            return response('Что-то пошло не так', 500);
        }

        return response('Правило успешно создано', 201);
    }

    public function update(RuleRequest $request, Rule $rule)
    {
        $data = $request->validated();
        if ($rule->update($data)) {
            $response = 'Успешное обновление';
        } else {
            $response = 'Что-то пошло не так';
        }
        return response($response, 200);
    }

    public function handleRule(Rule $rule)
    {
        $this->ruleService->handleRule($rule);
    }
}
