<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Dto;

use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use App\Support\Dto\Dto;

class AccountTypeUpdateDto extends AccountTypeDto
{
    public ?string $name = null;

    public ?AccountTypeCategoryEnum $category = null;

    public ?AccountTypeNormalBalanceSideEnum $normalBalanceSide = null;

    public ?bool $allowNegativeBalance = null;

    public ?bool $isActive = null;
}
