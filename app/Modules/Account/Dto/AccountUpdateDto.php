<?php

declare(strict_types=1);

namespace App\Modules\Account\Dto;

use App\Support\Dto\Dto;

class AccountUpdateDto extends Dto
{
    public int $id;

    public ?int $accountTypeId = null;

    public ?int $parentAccountId;

    public ?string $code = null;

    public ?string $name = null;

    public ?bool $isActive = null;
}
