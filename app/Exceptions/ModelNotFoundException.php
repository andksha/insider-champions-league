<?php

namespace App\Exceptions;

use App\Contract\HttpException;
use Exception;

final class ModelNotFoundException extends Exception implements HttpException
{
}