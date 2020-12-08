<?php

namespace App\Exceptions;

use App\Contract\LoggableException;
use Exception;

final class InvalidOperationException extends Exception implements LoggableException
{

}