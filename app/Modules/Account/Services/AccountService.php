<?php

declare(strict_types=1);

namespace App\Modules\Account\Services;

use App\Exceptions\NotFoundException;
use App\Models\Account;
use App\Models\AccountType;
use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountStoreDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AccountService
{
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
        return $this->getAccountOrFail($dto->id);
    }

    /**
     * Create new Account
     * @param AccountStoreDto $dto
     * @return Account
     * @throws NotFoundException
     */
    public function store(AccountStoreDto $dto): Account
    {
        try {
            $accountType = AccountType::query()->findOrFail($dto->accountTypeId);
        } catch (ModelNotFoundException $e) {
            throw NotFoundException::make('Account Type with id -' . $dto->accountTypeId . ' not found', $e);
        }

        $account = new Account();

        $account->accountType()->associate($accountType);

        if ($dto->parentAccountId !== null) {
            $parentAccount = $this->getAccountOrFail($dto->parentAccountId);
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
        if (isset($dto->parentAccountId)) {
            if (!Account::query()->whereId($dto->parentAccountId)->exists()) {
                throw NotFoundException::make('Account with id -' . $dto->parentAccountId . ' not found');
            }
        }

        if ($dto->accountTypeId !== null) {
            if (!AccountType::query()->whereId($dto->accountTypeId)->exists()) {
                throw NotFoundException::make('Account Type with id -' . $dto->accountTypeId . ' not found');
            }
        }

        $account = $this->getAccountOrFail($dto->id);

        $fillable = array_filter([
            ...$dto->toSneakedCaseArray(),
        ], fn ($value) => $value !== null);

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
        $account = $this->getAccountOrFail($dto->id);

        $account->delete();
    }

    /**
     * Find Account by id or throw
     * @param int $id
     * @return Account
     * @throws NotFoundException
     */
    protected function getAccountOrFail(int $id): Account
    {
        try {
            $account = Account::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw NotFoundException::make('Account with id -' . $id . ' not found', $e);
        }

        return $account;
    }
}
