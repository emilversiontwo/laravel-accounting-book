<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Account;

use App\Exceptions\NotFoundException;
use App\Models\Account;
use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountMassDestroyDto;
use App\Modules\Account\Dto\AccountStoreDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use App\Modules\Account\Mappers\AccountRequestMapper;
use App\Modules\Account\Services\AccountService;
use App\MoonShine\Resources\Account\Pages\AccountIndexPage;
use App\MoonShine\Resources\Account\Pages\AccountFormPage;
use App\MoonShine\Resources\Account\Pages\AccountDetailPage;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Crud\Resources\CrudResource;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends CrudResource<array, AccountIndexPage, AccountFormPage, AccountDetailPage>
 */
class AccountResource extends CrudResource
{
    protected AccountService $accountService;

    protected ?string $casterKeyName = 'id';

    protected string $title = 'Account';

    public function __construct(CoreContract $core, AccountService $accountService){
        parent::__construct($core);

        $this->accountService = $accountService;
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AccountIndexPage::class,
            AccountFormPage::class,
            AccountDetailPage::class,
        ];
    }

    /**
     * @return Collection<array-key, array>
     */
    public function getItems(): Collection
    {
        return $this->accountService->index();
    }

    public function findItem(bool $orFail = false): ?DataWrapperContract
    {
        $dto = new AccountDto([
            'id' => (int) $this->getItemID(),
        ]);

        try {
            $account = $this->accountService->show($dto);
        } catch (NotFoundException $_) {
            return null;
        }

        return $this->getCaster()->cast($account);
    }

    /**
     * @throws NotFoundException
     */
    public function save(DataWrapperContract $item, ?FieldsContract $fields = null): DataWrapperContract
    {
        $dto = $item->getOriginal() instanceof Model
            ? AccountRequestMapper::toUpdateDto(request(), $this->getItemID())
            : AccountRequestMapper::toStoreDto(request());

        $account = $item->getOriginal() instanceof Model
            ? $this->accountService->update($dto)
            : $this->accountService->store($dto);

        return $this->getCaster()->cast($account);
    }

    public function delete(DataWrapperContract $item, ?FieldsContract $fields = null): bool
    {
        $dto = AccountRequestMapper::toDto($this->getItemID());

        try {
            $this->accountService->destroy($dto);
        } catch (NotFoundException $_) {
            return false;
        }

        return true;
    }

    public function massDelete(array $ids): void
    {
        $dto = AccountRequestMapper::toMassDestroyDto($ids);

        $this->accountService->massDestroy($dto);
    }
}
