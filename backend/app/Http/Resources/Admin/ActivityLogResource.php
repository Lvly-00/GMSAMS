<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'action_type' => $this->action_type,
            'module_name' => $this->module_name,
            'description' => $this->description,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'username' => $this->user?->username,
            ]),
            'role' => $this->whenLoaded('role', fn () => [
                'name' => $this->role?->name,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
