<?php

declare(strict_types=1);

namespace App\Modules\Account\Dto;

class AccountUpdateDto extends AccountDto
{
    public ?int $accountTypeId = null;

    public ?int $parentAccountId;

    public ?string $code = null;

    public ?string $name = null;

    public ?bool $isActive = null;
}
