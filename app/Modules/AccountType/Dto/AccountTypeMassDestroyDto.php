<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Dto;

use App\Support\Dto\Dto;

class AccountTypeMassDestroyDto extends Dto
{
    /**
     * @var array<int>
     */
    public array $ids;
}
