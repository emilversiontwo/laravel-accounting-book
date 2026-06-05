<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $cash = Account::query()->where('code', '1000')->firstOrFail();
        $bank = Account::query()->where('code', '1100')->firstOrFail();
        $revenue = Account::query()->where('code', '4000')->firstOrFail();
        $expenses = Account::query()->where('code', '5000')->firstOrFail();

        DB::transaction(function () use ($cash, $bank, $revenue, $expenses): void {
            $transaction1 = Transaction::query()->updateOrCreate(
                [
                    'transaction_date' => '2026-01-10',
                    'description' => 'Cash sale',
                ],
                [
                    'status' => 'posted',
                ]
            );

            JournalEntry::query()->updateOrCreate(
                [
                    'transaction_id' => $transaction1->id,
                    'account_id' => $cash->id,
                    'side' => 'debit',
                ],
                [
                    'amount' => 1000.00,
                ]
            );

            JournalEntry::query()->updateOrCreate(
                [
                    'transaction_id' => $transaction1->id,
                    'account_id' => $revenue->id,
                    'side' => 'credit',
                ],
                [
                    'amount' => 1000.00,
                ]
            );

            $transaction2 = Transaction::query()->updateOrCreate(
                [
                    'transaction_date' => '2026-01-12',
                    'description' => 'Office rent payment',
                ],
                [
                    'status' => 'posted',
                ]
            );

            JournalEntry::query()->updateOrCreate(
                [
                    'transaction_id' => $transaction2->id,
                    'account_id' => $expenses->id,
                    'side' => 'debit',
                ],
                [
                    'amount' => 300.00,
                ]
            );

            JournalEntry::query()->updateOrCreate(
                [
                    'transaction_id' => $transaction2->id,
                    'account_id' => $bank->id,
                    'side' => 'credit',
                ],
                [
                    'amount' => 300.00,
                ]
            );
        });
    }
}
