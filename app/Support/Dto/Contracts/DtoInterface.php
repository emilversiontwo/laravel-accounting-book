<?php

declare(strict_types=1);

namespace App\Support\Dto\Contracts;

interface DtoInterface
{
    public function __construct();

    public function toArray(): array;
}
