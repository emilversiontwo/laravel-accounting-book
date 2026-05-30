<?php

namespace Database\Seeders;

use App\Models\AccountType;
use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        AccountType::query()->truncate();

        $accountType = new AccountType();

        $accountType->name = 'TestAccountType';

        $accountType->category = AccountTypeCategoryEnum::Asset->getValue();

        $accountType->normal_balance_side = AccountTypeNormalBalanceSideEnum::Debit->getValue();

        $accountType->allow_negative_balance = false;

        $accountType->is_active = true;

        $accountType->save();
    }
}
