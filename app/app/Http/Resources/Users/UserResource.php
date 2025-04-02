<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "surname" => $this->surname,
            "patronymic" => $this->patronymic ?? null,
            "inn" => $this->inn ?? null,
            "snils" => $this->snils ?? null
        ];

    }
}
