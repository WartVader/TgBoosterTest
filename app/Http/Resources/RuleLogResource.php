<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuleLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rule_id' => $this->rule_id,
            'ad_id' => $this->ad_id,
            'rule' => $this->rule->human_rule,
            'message' => $this->message,
            'error_message' => $this->error_message,
            'changes' => $this->changes,
            'created_at' => $this->created_at->format('d.m.Y h:i:s')
        ];
    }
}
