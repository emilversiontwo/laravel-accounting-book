<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Assets',
                'category' => 'asset',
                'normal_balance_side' => 'debit',
                'allow_negative_balance' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Revenue',
                'category' => 'revenue',
                'normal_balance_side' => 'credit',
                'allow_negative_balance' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Expenses',
                'category' => 'expense',
                'normal_balance_side' => 'debit',
                'allow_negative_balance' => false,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            AccountType::query()->updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
