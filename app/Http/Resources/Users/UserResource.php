<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;
    public $profile;
    public $address;

    public function __construct($status, $message, $resource, $profile = null, $address = null)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->profile = $profile;
        $this->address = $address;
    }

    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
            'profile' => $this->whenNotNull($this->profile),
            'address' => $this->whenNotNull($this->address),
        ];
    }
}
