<?php

namespace Tests\Unit\app\Modules\AccountType\Services;

use App\Exceptions\NotFoundException;
use App\Models\AccountType;
use App\Modules\AccountType\Dto\AccountTypeDto;
use App\Modules\AccountType\Dto\AccountTypeStoreDto;
use App\Modules\AccountType\Dto\AccountTypeUpdateDto;
use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use App\Modules\AccountType\Services\AccountTypeService;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AccountTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AccountTypeService $accountTypeService;

    protected AccountType $accountType;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AccountTypeSeeder::class);

        $this->accountType = AccountType::query()->first();

        $this->accountTypeService = app(AccountTypeService::class);
    }
    /*
     * index
        show
        store
        update
        destroy
     */

    #[Test]
    public function testIndexSuccess(): void
    {
        $accountTypes = $this->accountTypeService->index();

        $this->assertDatabaseCount($this->accountType->getTable(), $accountTypes->count());
    }

    #[Test]
    public function testShowSuccess(): void
    {
        $dto = new AccountTypeDto([
            'id' => $this->accountType->id,
        ]);

        $accountType = $this->accountTypeService->show($dto);

        $this->assertDatabaseHas($this->accountType->getTable(), $accountType->toArray());
    }

    #[Test]
    public function testShowNotFound(): void
    {
        $dto = new AccountTypeDto([
            'id' => AccountType::query()->count() + 10,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountTypeService->show($dto);
    }

    #[Test]
    public function testStoreSuccess(): void
    {
        $dto = new AccountTypeStoreDto([
            'name' => 'SomeTestType',
            'category' => AccountTypeCategoryEnum::Expense,
            'normalBalanceSide' => AccountTypeNormalBalanceSideEnum::Credit,
            'allowNegativeBalance' => true,
            'isActive' => true,
        ]);

        $accountType = $this->accountTypeService->store($dto);

        $this->assertDatabaseHas($this->accountType->getTable(), $accountType->toArray());
    }

    #[Test]
    public function testUpdateSuccess(): void
    {
        $dto = new AccountTypeUpdateDto([
            'id' => $this->accountType->id,
            'name' => 'SomeTestType',
            'category' => AccountTypeCategoryEnum::Expense,
            'normalBalanceSide' => AccountTypeNormalBalanceSideEnum::Credit,
            'allowNegativeBalance' => true,
            'isActive' => true,
        ]);

        $accountType = $this->accountTypeService->update($dto);

        $this->assertDatabaseHas($this->accountType->getTable(), $accountType->toArray());
    }

    #[Test]
    public function testUpdateNotFound(): void
    {
        $dto = new AccountTypeUpdateDto([
            'id' => AccountType::query()->count() + 10,
            'name' => 'SomeTestType',
            'category' => AccountTypeCategoryEnum::Expense,
            'normalBalanceSide' => AccountTypeNormalBalanceSideEnum::Credit,
            'allowNegativeBalance' => true,
            'isActive' => true,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountTypeService->update($dto);
    }

    #[Test]
    public function testDeleteSuccess(): void
    {
        $dto = new AccountTypeDto([
            'id' => $this->accountType->id,
        ]);

        $this->assertDatabaseHas($this->accountType->getTable(), $this->accountType->toArray());

        $this->accountTypeService->destroy($dto);

        $this->assertDatabaseMissing($this->accountType->getTable(), $this->accountType->toArray());
    }

    #[Test]
    public function testDeleteNotFound(): void
    {
        $dto = new AccountTypeDto([
            'id' => AccountType::query()->count() + 10,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountTypeService->destroy($dto);
    }
}
