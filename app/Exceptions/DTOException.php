<?php

namespace App\Exceptions;

use App\Contract\ApplicationException;
use Exception;
use Illuminate\Http\JsonResponse;

final class DTOException extends Exception implements ApplicationException
{
    protected $code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;
}