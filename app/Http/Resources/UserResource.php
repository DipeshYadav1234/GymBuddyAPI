<?php

namespace App\Http\Resources;

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
        $date = new \DateTime($this->dob);
        $todayDate = now();
        $age = date_diff($todayDate,$date);
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'weight' => $this->weight,
            'height' => $this->height,
            'is_profile_complete' => $this->is_profile_complete,
            'dob' => $this->dob,
            'age' => $age->y.' years '.$age->m.' months'
        ];
    }
}
