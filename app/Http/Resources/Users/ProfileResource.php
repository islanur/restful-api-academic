<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;
    public $account;

    public function __construct($status, $message, $resource, $account)
    {

        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->account = $account;
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'user account' => $this->whenNotNull($this->account),
            'profile' => $this->whenNotNull($this->resource),
        ];
    }
}
