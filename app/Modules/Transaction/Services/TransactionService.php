<?php

declare(strict_types=1);

namespace App\Modules\Transaction\Services;

use App\Models\JournalEntry;
use App\Models\Transaction;
use App\Modules\Transaction\Exceptions\TransactionAlreadyPostedException;
use App\Modules\Transaction\Exceptions\TransactionCannotBeDeletedException;
use App\Modules\Transaction\Exceptions\TransactionNotBalancedException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class TransactionService
{
    public function index(): Collection
    {
        return Transaction::query()
            ->with('journalEntries')
            ->latest('id')
            ->get();
    }

    public function show(int $id): Transaction
    {
        return Transaction::query()
            ->with(['journalEntries.account', 'journalEntries'])
            ->findOrFail($id);
    }

    public function store(array $data): Transaction
    {
        return Transaction::query()->create([
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'draft',
        ]);
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        if ($transaction->status === 'posted') {
            throw new TransactionAlreadyPostedException(
                'Posted transaction cannot be edited.'
            );
        }

        $transaction->update([
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'draft',
        ]);

        return $transaction->fresh();
    }

    public function destroy(Transaction $transaction): bool
    {
        if ($transaction->status === 'posted') {
            throw new TransactionCannotBeDeletedException(
                'Posted transaction cannot be deleted.'
            );
        }

        return (bool) $transaction->delete();
    }

    public function post(Transaction $transaction): Transaction
    {
        if ($transaction->status === 'posted') {
            throw new TransactionAlreadyPostedException(
                'Transaction already posted.'
            );
        }

        return DB::transaction(function () use ($transaction): Transaction {

            $transaction->load('journalEntries');

            $this->validateJournalEntries($transaction);

            $transaction->update([
                'status' => 'posted',
            ]);

            return $transaction->fresh();
        });
    }

    private function validateJournalEntries(Transaction $transaction): void
    {
        $entries = $transaction->journalEntries;

        if ($entries->count() < 2) {
            throw new TransactionNotBalancedException(
                'Transaction must contain at least 2 journal entries.'
            );
        }

        $debit = $entries
            ->where('side', 'debit')
            ->sum('amount');

        $credit = $entries
            ->where('side', 'credit')
            ->sum('amount');

        if (bccomp((string) $debit, (string) $credit, 2) !== 0) {
            throw new TransactionNotBalancedException(
                'Debit amount must equal credit amount.'
            );
        }
    }

    public function addJournalEntry(
        Transaction $transaction,
        int $accountId,
        string $side,
        string $amount,
    ): JournalEntry {
        if ($transaction->status === 'posted') {
            throw new TransactionAlreadyPostedException(
                'Cannot modify posted transaction.'
            );
        }

        return $transaction->journalEntries()->create([
            'account_id' => $accountId,
            'side' => $side,
            'amount' => $amount,
        ]);
    }

    public function removeJournalEntry(JournalEntry $journalEntry): bool
    {
        $journalEntry->load('transaction');

        if ($journalEntry->transaction->status === 'posted') {
            throw new TransactionAlreadyPostedException(
                'Cannot modify posted transaction.'
            );
        }

        return (bool) $journalEntry->delete();
    }

    public function getJournalEntry(int $id): JournalEntry
    {
        return JournalEntry::query()->findOrFail($id);
    }

    public function getJournalEntriesFormTransaction(Transaction $transaction): Collection
    {
        return $transaction->journalEntries()->get();
    }

    public function getJournalEntries(): Collection
    {
       return JournalEntry::query()->whereHas('transaction', function (EloquentBuilder $builder) {
           $builder->where('status', '=', 'draft');
       })->get();
    }
}

