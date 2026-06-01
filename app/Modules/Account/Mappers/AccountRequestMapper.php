<?php

namespace App\Modules\Account\Mappers;

use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountMassDestroyDto;
use App\Modules\Account\Dto\AccountStoreDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use Illuminate\Http\Request;

class AccountRequestMapper
{
    /**
     * @param Request $request
     * @return AccountStoreDto
     */
    public static function toStoreDto(Request $request): AccountStoreDto
    {
        return new AccountStoreDto([
            'accountTypeId' => $request->integer('account_type_id') ,
            'parentAccountId' => self::normalizeParentId($request->input('parent_account_id')),
            'code' => $request->string('code')->trim()->toString(),
            'name' => $request->string('name')->trim()->toString(),
            'isActive' => $request->boolean('is_active'),
        ]);
    }

    /**
     * @param Request $request
     * @param int|string $id
     * @return AccountUpdateDto
     */
    public static function toUpdateDto(Request $request, int|string $id): AccountUpdateDto
    {
        return new AccountUpdateDto([
            'id' => (int) $id,
            ...self::toStoreDto($request)->toArray(),
        ]);
    }

    /**
     * @param int|string $id
     * @return AccountDto
     */
    public static function toDto(int|string $id): AccountDto
    {
        return new AccountDto([
            'id' => (int) $id,
        ]);
    }

    /**
     * @param array<int> $ids
     * @return AccountMassDestroyDto
     */
    public static function toMassDestroyDto(array $ids): AccountMassDestroyDto
    {
        return new AccountMassDestroyDto([
            'ids' => $ids,
        ]);
    }

    private static function normalizeParentId(mixed $value): ?int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT);
        return ($id && $id > 0) ? $id : null;
    }
}
