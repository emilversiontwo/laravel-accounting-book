<?php

namespace App\Modules\Transaction\Services;

use App\Models\Transaction;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TransactionExportService
{
    public function downloadCsv(): StreamedResponse
    {
        return $this->download('csv');
    }

    public function downloadExcelCompatible(): StreamedResponse
    {
        return $this->download('xls');
    }

    private function download(string $format): StreamedResponse
    {
        $transactions = Transaction::query()
            ->with('journalEntries.account')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        $filename = sprintf(
            'transactions_%s.%s',
            now()->format('Ymd_His'),
            $format === 'csv' ? 'csv' : 'xls'
        );

        return response()->streamDownload(
            function () use ($transactions, $format): void {
                $output = fopen('php://output', 'wb');

                if ($format === 'csv') {
                    fwrite($output, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
                    fputcsv($output, [
                        'ID',
                        'Date',
                        'Description',
                        'Status',
                        'Debit total',
                        'Credit total',
                        'Entries',
                    ], ',');
                } else {
                    fwrite($output, implode("\t", [
                            'ID',
                            'Date',
                            'Description',
                            'Status',
                            'Debit total',
                            'Credit total',
                            'Entries',
                        ]) . "\n");
                }

                foreach ($transactions as $transaction) {
                    $debitTotal = number_format(
                        (float) $transaction->journalEntries->where('side', 'debit')->sum('amount'),
                        2,
                        '.',
                        ''
                    );

                    $creditTotal = number_format(
                        (float) $transaction->journalEntries->where('side', 'credit')->sum('amount'),
                        2,
                        '.',
                        ''
                    );

                    $entriesText = $transaction->journalEntries
                        ->map(static function ($entry): string {
                            return sprintf(
                                '%s #%s %s %0.2f',
                                $entry->side,
                                $entry->account?->code ?? $entry->account_id,
                                $entry->account?->name ?? 'Account',
                                (float) $entry->amount
                            );
                        })
                        ->implode(' | ');

                    $row = [
                        (string) $transaction->id,
                        optional($transaction->transaction_date)->format('Y-m-d'),
                        (string) ($transaction->description ?? ''),
                        (string) $transaction->status,
                        $debitTotal,
                        $creditTotal,
                        $entriesText,
                    ];

                    if ($format === 'csv') {
                        fputcsv($output, $row, ',');
                    } else {
                        fwrite($output, implode("\t", $row) . "\n");
                    }
                }

                fclose($output);
            },
            $filename,
            [
                'Content-Type' => $format === 'csv'
                    ? 'text/csv; charset=UTF-8'
                    : 'application/vnd.ms-excel; charset=UTF-8',
            ]
        );
    }
}
