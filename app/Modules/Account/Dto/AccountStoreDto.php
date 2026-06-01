<?php

declare(strict_types=1);

namespace App\Modules\Account\Dto;

use App\Support\Dto\Dto;

class AccountStoreDto extends Dto
{
    public int $accountTypeId;

    public ?int $parentAccountId = null;

    public string $code;

    public string $name;

    public bool $isActive;
}
