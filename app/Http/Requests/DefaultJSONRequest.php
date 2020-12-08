<?php

namespace App\Http\Requests;

use App\Contract\JSONRequest;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Exceptions\HttpResponseException;

final class DefaultJSONRequest implements JSONRequest
{
    public function throwJSONResponseException(MessageBag $errors, int $httpCode)
    {
        throw new HttpResponseException(response()->json($errors, $httpCode));
    }
}