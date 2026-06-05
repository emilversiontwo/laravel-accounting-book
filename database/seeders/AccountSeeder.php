<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $assetsType = AccountType::query()->where('name', 'Assets')->firstOrFail();
        $revenueType = AccountType::query()->where('name', 'Revenue')->firstOrFail();
        $expensesType = AccountType::query()->where('name', 'Expenses')->firstOrFail();

        $accounts = [
            [
                'account_type_id' => $assetsType->id,
                'parent_account_id' => null,
                'code' => '1000',
                'name' => 'Cash',
                'is_active' => true,
            ],
            [
                'account_type_id' => $assetsType->id,
                'parent_account_id' => null,
                'code' => '1100',
                'name' => 'Bank Account',
                'is_active' => true,
            ],
            [
                'account_type_id' => $revenueType->id,
                'parent_account_id' => null,
                'code' => '4000',
                'name' => 'Sales Revenue',
                'is_active' => true,
            ],
            [
                'account_type_id' => $expensesType->id,
                'parent_account_id' => null,
                'code' => '5000',
                'name' => 'Office Expenses',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            Account::query()->updateOrCreate(
                ['code' => $account['code']],
                $account
            );
        }
    }
}
