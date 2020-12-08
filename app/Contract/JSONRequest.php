<?php

namespace App\Contract;

use Illuminate\Contracts\Support\MessageBag;

interface JSONRequest
{
    public function throwJSONResponseException(MessageBag $errors, int $httpCode);
}