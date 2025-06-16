<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_guest' => $this->is_guest,
            // Add other user attributes if needed for appleLogin
            'email' => $this->whenNotNull($this->email),
            'auth_id' => $this->whenNotNull($this->auth_id),
        ];
    }
}
