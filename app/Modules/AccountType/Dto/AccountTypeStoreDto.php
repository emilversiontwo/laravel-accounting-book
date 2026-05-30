<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Dto;

use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use App\Support\Dto\Dto;

class AccountTypeStoreDto extends Dto
{
    public string $name;

    public AccountTypeCategoryEnum $category;

    public AccountTypeNormalBalanceSideEnum $normalBalanceSide;

    public bool $allowNegativeBalance;

    public bool $isActive;
}
