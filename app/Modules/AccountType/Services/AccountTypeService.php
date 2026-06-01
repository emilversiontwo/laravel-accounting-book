<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Services;

use App\Exceptions\NotFoundException;
use App\Models\AccountType;
use App\Modules\AccountType\Dto\AccountTypeDto;
use App\Modules\AccountType\Dto\AccountTypeMassDestroyDto;
use App\Modules\AccountType\Dto\AccountTypeStoreDto;
use App\Modules\AccountType\Dto\AccountTypeUpdateDto;
use App\Support\Traits\ResolvesModelsTrait;
use Illuminate\Database\Eloquent\Collection;

class AccountTypeService
{
    use ResolvesModelsTrait;

    /**
     * Get all Account Types
     * @return Collection
     */
    public function index(): Collection
    {
        return AccountType::query()->get();
    }

    /**
     * Get Account Type by id or fail
     * @throws NotFoundException
     */
    public function show(AccountTypeDto $dto): AccountType
    {
        /** @var AccountType $accountType */
        $accountType = $this->resolveOrFail(AccountType::class, $dto->id);
        return $accountType;
    }

    /**
     * Create new Account Type
     * @param AccountTypeStoreDto $dto
     * @return AccountType
     */
    public function store(AccountTypeStoreDto $dto): AccountType
    {
        $accountType = new AccountType();

        $accountType->name = $dto->name;
        $accountType->category = $dto->category->getValue();
        $accountType->normal_balance_side = $dto->normalBalanceSide->getValue();
        $accountType->allow_negative_balance = $dto->allowNegativeBalance;
        $accountType->is_active = $dto->isActive;

        $accountType->save();

        return $accountType;
    }

    /**
     * Update Account type or nothing
     * @param AccountTypeUpdateDto $dto
     * @return AccountType
     * @throws NotFoundException
     */
    public function update(AccountTypeUpdateDto $dto): AccountType
    {
        /** @var AccountType $accountType */
        $accountType = $this->resolveOrFail(AccountType::class, $dto->id);

        $fillable = array_filter([
            ...$dto->toSneakedCaseArray(),
        ], fn ($value) => $value !== null);

        if (!empty($fillable)) {
            $accountType->fill($fillable);
            $accountType->save();
        }

        return $accountType;
    }

    /**
     * Destroy the Account Type
     * @param AccountTypeDto $dto
     * @return void
     * @throws NotFoundException
     */
    public function destroy(AccountTypeDto $dto): void
    {
        /** @var AccountType $accountType */
        $accountType = $this->resolveOrFail(AccountType::class, $dto->id);

        $accountType->delete();
    }

    /**
     * Destroy many Account Types
     * @param AccountTypeMassDestroyDto $dto
     * @return void
     */
    public function massDestroy(AccountTypeMassDestroyDto $dto): void
    {
        AccountType::query()->whereIn('id', $dto->ids)->delete();
    }
}
