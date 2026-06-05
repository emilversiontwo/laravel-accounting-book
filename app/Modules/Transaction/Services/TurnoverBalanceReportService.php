<?php

declare(strict_types=1);

namespace App\Modules\Transaction\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class TurnoverBalanceReportService
{
    /**
     * @return array{
     *     from: Carbon,
     *     to: Carbon,
     *     rows: array<int, array<string, mixed>>,
     *     totals: array<string, string>
     * }
     */
    public function build(Carbon $from, Carbon $to): array
    {
        $accounts = Account::query()
            ->with('accountType')
            ->orderBy('code')
            ->get();

        $rows = [];

        foreach ($accounts as $account) {
            $rows[$account->id] = [
                'account' => $account,
                'opening_signed' => 0,
                'turnover_signed' => 0,
                'turnover_debit' => 0,
                'turnover_credit' => 0,
            ];
        }

        $entries = JournalEntry::query()
            ->with(['account.accountType', 'transaction'])
            ->whereHas('transaction', function ($query) use ($to): void {
                $query->where('status', 'posted')
                    ->whereDate('transaction_date', '<=', $to->toDateString());
            })
            ->orderBy('id')
            ->get();

        foreach ($entries as $entry) {
            $account = $entry->account;

            if (! isset($rows[$account->id])) {
                continue;
            }

            $amountCents = $this->toCents((string) $entry->amount);
            $signedCents = $entry->side === $account->accountType->normal_balance_side
                ? $amountCents
                : -$amountCents;

            $transactionDate = Carbon::parse($entry->transaction->transaction_date);

            if ($transactionDate->lt($from)) {
                $rows[$account->id]['opening_signed'] += $signedCents;
            } else {
                $rows[$account->id]['turnover_signed'] += $signedCents;

                if ($entry->side === 'debit') {
                    $rows[$account->id]['turnover_debit'] += $amountCents;
                } else {
                    $rows[$account->id]['turnover_credit'] += $amountCents;
                }
            }
        }

        $resultRows = [];
        $totals = [
            'opening_debit' => 0,
            'opening_credit' => 0,
            'turnover_debit' => 0,
            'turnover_credit' => 0,
            'closing_debit' => 0,
            'closing_credit' => 0,
        ];

        foreach ($rows as $row) {
            $account = $row['account'];
            $opening = $this->splitBalance($row['opening_signed'], $account->accountType->normal_balance_side);

            $closingSigned = $row['opening_signed'] + $row['turnover_signed'];
            $closing = $this->splitBalance($closingSigned, $account->accountType->normal_balance_side);

            $prepared = [
                'account' => $account,
                'opening_debit' => $this->fromCents($opening['debit']),
                'opening_credit' => $this->fromCents($opening['credit']),
                'turnover_debit' => $this->fromCents($row['turnover_debit']),
                'turnover_credit' => $this->fromCents($row['turnover_credit']),
                'closing_debit' => $this->fromCents($closing['debit']),
                'closing_credit' => $this->fromCents($closing['credit']),
            ];

            $resultRows[] = $prepared;

            $totals['opening_debit'] += $opening['debit'];
            $totals['opening_credit'] += $opening['credit'];
            $totals['turnover_debit'] += $row['turnover_debit'];
            $totals['turnover_credit'] += $row['turnover_credit'];
            $totals['closing_debit'] += $closing['debit'];
            $totals['closing_credit'] += $closing['credit'];
        }

        return [
            'from' => $from,
            'to' => $to,
            'rows' => $resultRows,
            'totals' => array_map(fn (int $value): string => $this->fromCents($value), $totals),
        ];
    }

    private function toCents(string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function fromCents(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }

    /**
     * @return array{debit:int, credit:int}
     */
    private function splitBalance(int $signedCents, string $normalBalanceSide): array
    {
        if ($signedCents >= 0) {
            return $normalBalanceSide === 'debit'
                ? ['debit' => $signedCents, 'credit' => 0]
                : ['debit' => 0, 'credit' => $signedCents];
        }

        $abs = abs($signedCents);

        return $normalBalanceSide === 'debit'
            ? ['debit' => 0, 'credit' => $abs]
            : ['debit' => $abs, 'credit' => 0];
    }
}
