<?php

declare(strict_types=1);

namespace Tests\Unit\app\Modules\Account\Services;

use App\Exceptions\NotFoundException;
use App\Models\Account;
use App\Models\AccountType;
use App\Modules\Account\Dto\AccountDto;
use App\Modules\Account\Dto\AccountStoreDto;
use App\Modules\Account\Dto\AccountUpdateDto;
use App\Modules\Account\Services\AccountService;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AccountService $accountService;

    protected Account  $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AccountTypeSeeder::class,
            AccountSeeder::class,
        ]);

        $this->account = Account::query()->first();

        $this->accountService = app(AccountService::class);
    }

    #[Test]
    public function testIndexAccountsSuccess(): void
    {
        $accounts = $this->accountService->index();

        $this->assertDatabaseCount($this->account->getTable(), $accounts->count());
    }

    #[Test]
    public function testShowAccountSuccess(): void
    {
        $dto = new AccountDto([
            'id' => $this->account->id,
        ]);

        $accounts = $this->accountService->show($dto);

        $this->assertDatabaseHas($this->account->getTable(), $accounts->toArray());
    }

    #[Test]
    public function testShowAccountNotFound(): void
    {
        $dto = new AccountDto([
            'id' => Account::query()->count() + 10,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->show($dto);
    }

    #[Test]
    public function testStoreAccountSuccess(): void
    {
        $dto = new AccountStoreDto([
            'accountTypeId' => AccountType::query()->first()->id,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->accountService->store($dto);

        $this->assertDatabaseHas($this->account->getTable(), $dto->toSneakedCaseArray());
    }

    #[Test]
    public function testStoreAccountWithParentSuccess(): void
    {
        $dto = new AccountStoreDto([
            'accountTypeId' => AccountType::query()->first()->id,
            'parentAccountId' => $this->account->id,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->accountService->store($dto);

        $this->assertDatabaseHas($this->account->getTable(), $dto->toSneakedCaseArray());
    }

    #[Test]
    public function testStoreAccountNotFoundParentAccount(): void
    {
        $dto = new AccountStoreDto([
            'accountTypeId' => AccountType::query()->first()->id,
            'parentAccountId' => Account::query()->count() + 10,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->store($dto);
    }

    #[Test]
    public function testStoreAccountNotFoundAccountType(): void
    {
        $dto = new AccountStoreDto([
            'accountTypeId' => AccountType::query()->count() + 10,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->store($dto);
    }

    #[Test]
    public function testUpdateAccountSuccess(): void
    {
        $dto = new AccountUpdateDto([
            'id' => $this->account->id,
            'accountTypeId' => AccountType::query()->first()->id,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->assertDatabaseMissing($this->account->getTable(), $dto->toSneakedCaseArray());

        $this->accountService->update($dto);

        $this->assertDatabaseHas($this->account->getTable(), $dto->toSneakedCaseArray());
    }

    #[Test]
    public function testUpdateAccountWithParentSuccess(): void
    {
        $dto = new AccountUpdateDto([
            'id' => $this->account->id,
            'accountTypeId' => AccountType::query()->first()->id,
            'parentAccountId' => Account::query()->whereKeyNot($this->account->id)->first()->id,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->assertDatabaseMissing($this->account->getTable(), $dto->toSneakedCaseArray());

        $this->accountService->update($dto);

        $this->assertDatabaseHas($this->account->getTable(), $dto->toSneakedCaseArray());
    }

    #[Test]
    public function testUpdateAccountNotFoundParentAccount(): void
    {
        $dto = new AccountUpdateDto([
            'id' => $this->account->id,
            'accountTypeId' => AccountType::query()->first()->id,
            'parentAccountId' => Account::query()->count() + 10,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->update($dto);
    }

    #[Test]
    public function testUpdateAccountNotFoundAccountType(): void
    {
        $dto = new AccountUpdateDto([
            'id' => $this->account->id,
            'accountTypeId' => AccountType::query()->count() + 10,
            'code' => '10',
            'name' => 'Some name',
            'isActive' => false,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->update($dto);
    }

    #[Test]
    public function testDestroyAccountSuccess(): void
    {
        $dto = new AccountDto([
            'id' => $this->account->id,
        ]);

        $this->accountService->destroy($dto);

        $this->assertDatabaseMissing($this->account->getTable(), $this->account->toArray());
    }

    #[Test]
    public function testDestroyAccountNotFound(): void
    {
        $dto = new AccountDto([
            'id' => Account::query()->count() + 10,
        ]);

        $this->expectException(NotFoundException::class);

        $this->accountService->destroy($dto);
    }
}
