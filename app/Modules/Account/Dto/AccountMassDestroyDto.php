<?php

declare(strict_types=1);

namespace App\Modules\Account\Dto;

use App\Support\Dto\Dto;

class AccountMassDestroyDto extends Dto
{
    /**
     * @var array<int>
     */
    public array $ids;
}
