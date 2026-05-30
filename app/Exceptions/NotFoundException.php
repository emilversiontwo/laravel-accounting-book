<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\AppLogicException;
use Override;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

class NotFoundException extends AppLogicException
{
    protected $code = ResponseCode::HTTP_NOT_FOUND;

    #[Override]
    public static function make(string $message, ?Throwable $previous = null, ?int $code = null): self
    {
        return new self(
            $message,
            $code ?? ResponseCode::HTTP_NOT_FOUND,
            $previous
        );
    }
}
