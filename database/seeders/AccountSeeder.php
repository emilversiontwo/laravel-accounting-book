<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::query()->truncate();

        $account = new Account();
        $account->accountType()->associate(AccountType::query()->first());
        $account->code = '60';
        $account->name = 'SomeTestAccount';
        $account->is_active = true;

        $account->save();

        $account = new Account();
        $account->accountType()->associate(AccountType::query()->first());
        $account->code = '70';
        $account->name = 'SomeSecondTestAccount';
        $account->is_active = false;

        $account->save();
    }
}
