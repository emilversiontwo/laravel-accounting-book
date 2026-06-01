<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AccountType;

use App\Exceptions\NotFoundException;
use App\Models\AccountType;
use App\Modules\AccountType\Dto\AccountTypeDto;
use App\Modules\AccountType\Dto\AccountTypeMassDestroyDto;
use App\Modules\AccountType\Dto\AccountTypeStoreDto;
use App\Modules\AccountType\Dto\AccountTypeUpdateDto;
use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use App\Modules\AccountType\Mappers\AccountTypeRequestMapper;
use App\Modules\AccountType\Services\AccountTypeService;
use App\MoonShine\Resources\AccountType\Pages\AccountTypeIndexPage;
use App\MoonShine\Resources\AccountType\Pages\AccountTypeFormPage;
use App\MoonShine\Resources\AccountType\Pages\AccountTypeDetailPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Crud\Resources\CrudResource;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends CrudResource<array, AccountTypeIndexPage, AccountTypeFormPage, AccountTypeDetailPage>
 */
class AccountTypeResource extends CrudResource
{
    protected ?string $casterKeyName = 'id';

    protected string $title = 'AccountType';

    protected AccountTypeService $accountTypeService;

    public function __construct(CoreContract $contract, AccountTypeService $accountTypeService)
    {
        parent::__construct($contract);

        $this->accountTypeService = $accountTypeService;
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AccountTypeIndexPage::class,
            AccountTypeFormPage::class,
            AccountTypeDetailPage::class,
        ];
    }

    /**
     * @return Collection<array-key, array>
     */
    public function getItems(): Collection
    {
        return $this->accountTypeService->index();
    }

    public function findItem(bool $orFail = false): ?DataWrapperContract
    {
        $dto = AccountTypeRequestMapper::toDto($this->getItemID());

        try {
            $accountType = $this->accountTypeService->show($dto);
        } catch (NotFoundException $_) {
            return null;
        }

        return $this->getCaster()->cast($accountType);
    }

    /**
     * @throws NotFoundException
     */
    public function save(DataWrapperContract $item, ?FieldsContract $fields = null): DataWrapperContract
    {
        $dto = $item->getOriginal() instanceof Model
            ? AccountTypeRequestMapper::toUpdateDto(request(), $this->getItemID())
            : AccountTypeRequestMapper::toStoreDto(request());

        $accountType = $item->getOriginal() instanceof Model
            ? $this->accountTypeService->update($dto)
            : $this->accountTypeService->store($dto);

        return $this->getCaster()->cast($accountType);
    }

    public function delete(DataWrapperContract $item, ?FieldsContract $fields = null): bool
    {
        $dto = AccountTypeRequestMapper::toDto($this->getItemID());

        try {
            $this->accountTypeService->destroy($dto);
        } catch (NotFoundException $_) {
            return false;
        }

        return true;
    }

    public function massDelete(array $ids): void
    {
        $dto = AccountTypeRequestMapper::toMassDestroyDto($ids);

        $this->accountTypeService->massDestroy($dto);
    }
}
