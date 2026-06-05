<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\JournalEntry;

use App\Models\JournalEntry;
use App\Models\Transaction;
use App\Modules\Transaction\Exceptions\TransactionAlreadyPostedException;
use App\Modules\Transaction\Services\TransactionService;
use App\MoonShine\Resources\JournalEntry\Pages\JournalEntryIndexPage;
use App\MoonShine\Resources\JournalEntry\Pages\JournalEntryFormPage;
use App\MoonShine\Resources\JournalEntry\Pages\JournalEntryDetailPage;

use MoonShine\Crud\Resources\CrudResource;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends CrudResource<array, JournalEntryIndexPage, JournalEntryFormPage, JournalEntryDetailPage>
 */
class JournalEntryResource extends CrudResource
{
    protected ?string $casterKeyName = 'id';

    protected string $title = 'Draft JournalEntry';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            JournalEntryIndexPage::class,
            JournalEntryFormPage::class,
            JournalEntryDetailPage::class,
        ];
    }

    /**
     * @return Collection<array-key, array>
     */
    public function getItems(): Collection
    {
        return app(TransactionService::class)->getJournalEntries();
    }

    public function findItem(bool $orFail = false): ?DataWrapperContract
    {
        $journalEntry = app(TransactionService::class)->getJournalEntry((int)$this->getItemID());

        return $this->getCaster()->cast($journalEntry);
    }

    public function save(DataWrapperContract $item, ?FieldsContract $fields = null): DataWrapperContract
    {
        $id = (int) request()->integer('id', 0);
        $transactionId = (int) request()->integer('transaction_id');
        $accountId = (int) request()->integer('account_id');
        $side = request()->input('side');
        $amount = request()->input('amount');

        $transaction = Transaction::query()->findOrFail($transactionId);

        if ($id > 0) {
            $journalEntry = JournalEntry::query()->findOrFail($id);
            app(TransactionService::class)->removeJournalEntry($journalEntry);
            $journalEntry = app(TransactionService::class)->addJournalEntry(
                $transaction,
                $accountId,
                $side,
                $amount,
            );
        } else {
            $journalEntry = app(TransactionService::class)->addJournalEntry(
                $transaction,
                $accountId,
                $side,
                $amount,
            );
            $this->isRecentlyCreated = true;
        }

        return $this->getCaster()->cast($journalEntry);
    }

    /**
     * @throws TransactionAlreadyPostedException
     */
    public function delete(DataWrapperContract $item, ?FieldsContract $fields = null): bool
    {
        $journalEntry = JournalEntry::query()->findOrFail((int) $this->getItemID());
        return app(TransactionService::class)->removeJournalEntry($journalEntry);
    }

    public function massDelete(array $ids): void
    {
        //
    }
}
