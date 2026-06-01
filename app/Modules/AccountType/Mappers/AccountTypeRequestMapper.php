<?php

namespace App\Modules\AccountType\Mappers;

use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountMassDestroyDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use App\Modules\AccountType\Dto\AccountTypeDto;
use App\Modules\AccountType\Dto\AccountTypeMassDestroyDto;
use App\Modules\AccountType\Dto\AccountTypeStoreDto;
use App\Modules\AccountType\Dto\AccountTypeUpdateDto;
use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use Illuminate\Http\Request;

class AccountTypeRequestMapper
{
    /**
     * @param Request $request
     * @param int|string $id
     * @return AccountTypeUpdateDto
     */
    public static function toUpdateDto(Request $request, int|string $id): AccountTypeUpdateDto
    {
        return new AccountTypeUpdateDto([
            'id' => (int)$id,
            ...self::toStoreDto($request)->toArray(),
        ]);
    }

    /**
     * @param Request $request
     * @return AccountTypeStoreDto
     */
    public static function toStoreDto(Request $request): AccountTypeStoreDto
    {
        return new AccountTypeStoreDto([
            'name' => $request->string('name')->trim()->toString(),
            'category' => AccountTypeCategoryEnum::fromValue(
                $request
                    ->string('category')
                    ->trim()
                    ->toString()
            ),
            'normalBalanceSide' => AccountTypeNormalBalanceSideEnum::fromValue(
                $request
                    ->string('normal_balance_side')
                    ->trim()
                    ->toString()
            ),
            'allowNegativeBalance' => $request->boolean('allow_negative_balance'),
            'isActive' => $request->boolean('is_active'),
        ]);
    }

    /**
     * @param int|string $id
     * @return AccountTypeDto
     */
    public static function toDto(int|string $id): AccountTypeDto
    {
        return new AccountTypeDto([
            'id' => (int)$id,
        ]);
    }

    /**
     * @param array<int> $ids
     * @return AccountTypeMassDestroyDto
     */
    public static function toMassDestroyDto(array $ids): AccountTypeMassDestroyDto
    {
        return new AccountTypeMassDestroyDto([
            'ids' => $ids,
        ]);
    }
}
