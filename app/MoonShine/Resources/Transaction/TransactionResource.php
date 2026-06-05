<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Transaction;

use App\Models\Transaction;
use App\Modules\Transaction\Services\TransactionService;
use App\MoonShine\Resources\Transaction\Pages\TransactionIndexPage;
use App\MoonShine\Resources\Transaction\Pages\TransactionFormPage;
use App\MoonShine\Resources\Transaction\Pages\TransactionDetailPage;

use MoonShine\Crud\Resources\CrudResource;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\MoonShineRequest;

/**
 * @extends CrudResource<array, TransactionIndexPage, TransactionFormPage, TransactionDetailPage>
 */
class TransactionResource extends CrudResource
{
    protected ?string $casterKeyName = 'id';

    protected string $title = 'Transaction';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TransactionIndexPage::class,
            TransactionFormPage::class,
            TransactionDetailPage::class,
        ];
    }

    /**
     * @return Collection<array-key, array>
     */
    public function getItems(): Collection
    {
        return app(TransactionService::class)
            ->index()
            ->map(static fn (Transaction $transaction): array => $transaction->toArray());
    }

    public function findItem(bool $orFail = false): ?DataWrapperContract
    {
        $transaction = app(TransactionService::class)->show((int) $this->getItemID());

        return $this->getCaster()->cast($transaction);
    }

    public function save(DataWrapperContract $item, ?FieldsContract $fields = null): DataWrapperContract
    {
        $id = (int) request()->integer('id', 0);

        $payload = [
            'transaction_date' => request()->input('transaction_date'),
            'description' => request()->input('description'),
            'status' => request()->input('status', 'draft'),
        ];

        if ($id > 0) {
            if ($payload['status'] === 'posted') {
                $transaction = Transaction::query()->findOrFail($id);

                app(TransactionService::class)->post($transaction);
            } else {
                $transaction = app(TransactionService::class)->update(
                    Transaction::query()->findOrFail($id),
                    $payload
                );
            }
        } else {
            $transaction = app(TransactionService::class)->store($payload);
            $this->isRecentlyCreated = true;
        }

        return $this->getCaster()->cast($transaction->toArray());
    }

    public function delete(DataWrapperContract $item, ?FieldsContract $fields = null): bool
    {
        $id = (int) request()->integer('resourceItem', 0);

        if ($id <= 0) {
            $id = (int) request()->integer('id', 0);
        }

        if ($id <= 0) {
            return false;
        }

        $transaction = Transaction::query()->findOrFail($id);

        return app(TransactionService::class)->destroy($transaction);
    }

    public function massDelete(array $ids): void
    {
        Transaction::query()
            ->whereIn('id', $ids)
            ->get()
            ->each(fn (Transaction $transaction) => app(TransactionService::class)->destroy($transaction));
    }
}
