<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

class AppLogicException extends Exception
{
    protected $code = ResponseCode::HTTP_INTERNAL_SERVER_ERROR;

    public static function make(string $message, ?Throwable $previous = null, ?int $code = null): self
    {
        return new self($message, $code ?? ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}
