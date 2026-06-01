<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Dto;

use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use App\Support\Dto\Dto;

class AccountTypeDto extends Dto
{
    public int $id;
}
