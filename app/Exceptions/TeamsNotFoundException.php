<?php

namespace App\Exceptions;

use App\Contract\HttpException;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

final class TeamsNotFoundException extends Exception implements HttpException
{
    /**
     * TeamsNotFoundException constructor.
     * @param array $notFoundIds
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($notFoundIds = [], $code = 0, Throwable $previous = null)
    {
        $notFoundIdsStr = implode(', ', $notFoundIds);
        $notFound = ' not found';

        $message = count($notFoundIds) === 1
            ? 'Team ' . $notFoundIdsStr . ' was' . $notFound
            : 'Teams ' . $notFoundIdsStr . ' were' . $notFound;

        $code = JsonResponse::HTTP_NOT_FOUND;
        parent::__construct($message, $code, $previous);
    }
}