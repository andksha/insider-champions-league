<?php

namespace App\Exceptions;

use App\Contract\HttpException;
use Exception;
use Illuminate\Http\JsonResponse;

final class DTOException extends Exception implements HttpException
{
    protected $code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;
}