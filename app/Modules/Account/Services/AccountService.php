<?php

declare(strict_types=1);

namespace App\Modules\Account\Services;

use App\Exceptions\NotFoundException;
use App\Models\Account;
use App\Models\AccountType;
use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountMassDestroyDto;
use App\Modules\Account\Dto\AccountStoreDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use App\Support\Traits\ResolvesModelsTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AccountService
{
    use ResolvesModelsTrait;

    /**
     * Get all Accounts
     * @return Collection
     */
    public function index(): Collection
    {
        return Account::query()->get();
    }

    /**
     * Get Account by id or fail
     * @throws NotFoundException
     */
    public function show(AccountDto $dto): Account
    {
        /** @var Account $account */
        $account = $this->resolveOrFail(Account::class, $dto->id);
        return $account;
    }

    /**
     * Create new Account
     * @param AccountStoreDto $dto
     * @return Account
     * @throws NotFoundException
     */
    public function store(AccountStoreDto $dto): Account
    {
        /** @var AccountType $accountType */
        $accountType = $this->resolveOrFail(AccountType::class, $dto->accountTypeId);

        $account = new Account();

        $account->accountType()->associate($accountType);

        if ($dto->parentAccountId !== null) {

            /** @var Account $parentAccount */
            $parentAccount = $this->resolveOrFail(Account::class ,$dto->parentAccountId);
            $account->parentAccount()->associate($parentAccount);
        }

        $account->code = $dto->code;
        $account->name = $dto->name;
        $account->is_active = $dto->isActive;

        $account->save();

        return $account;
    }

    /**
     * Update Account or nothing
     * @param AccountUpdateDto $dto
     * @return Account
     * @throws NotFoundException
     */
    public function update(AccountUpdateDto $dto): Account
    {
        if (isset($dto->parentAccountId)){
            $this->resolveOrFail(Account::class, $dto->parentAccountId);
        }

        $this->resolveOrFail(AccountType::class, $dto->accountTypeId);

        /** @var Account $account */
        $account = $this->resolveOrFail(Account::class, $dto->id);

        $fillable = array_filter(
            $dto->toSneakedCaseArray(),
            fn ($value, $key) => $key !== 'id' && $value !== null,
            ARRAY_FILTER_USE_BOTH
        );

        if (!empty($fillable)) {
            $account->fill($fillable);
            if (isset($dto->parentAccountId)) {
                if ($dto->parentAccountId == null) {
                    $account->parentAccount()->dissociate();
                }
            }
            $account->save();
        }

        return $account;
    }

    /**
     * Delete Account
     * @param AccountDto $dto
     * @return void
     * @throws NotFoundException
     */
    public function destroy(AccountDto $dto): void
    {
        /** @var Account $account */
        $account = $this->resolveOrFail(Account::class, $dto->id);

        $account->delete();
    }

    /**
     * Delete Accounts
     * @param AccountMassDestroyDto $dto
     * @return void
     */
    public function massDestroy(AccountMassDestroyDto $dto): void
    {
        Account::query()->whereIn('id', $dto->ids)->delete();
    }
}
