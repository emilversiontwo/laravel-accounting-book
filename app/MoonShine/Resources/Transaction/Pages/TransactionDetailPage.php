<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Transaction\Pages;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Transaction;
use App\MoonShine\Resources\Transaction\TransactionResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Fieldset;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends DetailPage<TransactionResource>
 */
class TransactionDetailPage extends DetailPage
{
    protected function getJournalEntriesFields()
    {

        /** @var Transaction $transaction */
        $transaction = $this->getItem();

        if ($transaction->journalEntries()->get()->isEmpty()) {
            return [Text::make('No journal entries found')];
        }

        $result = $transaction->journalEntries()->get()->map(function (JournalEntry $entry) {
            return [
                'id' => $entry->id,
                'account_id' => $entry->account_id,
                'side' => $entry->side,
                'amount' => $entry->amount,

            ];
        })->toArray();

        return TableBuilder::make()
            ->items($result)
            ->fields([
                ID::make()->sortable(),
                Select::make('Account', 'account_id')
                    ->options(fn() => Account::query()
                        ->pluck('name', 'id')
                        ->toArray()
                    ),

                Select::make('Side', 'side')
                    ->options([
                        'debit' => 'Debit',
                        'credit' => 'Сredit',
                    ]),

                Number::make('Amount', 'amount')
                    ->min(0)
                    ->copy(),
            ]);
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param TableBuilder $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [

            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            Title::make('Journal Entries'),
            $this->getJournalEntriesFields(),
            ...parent::bottomLayer()
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Date::make('Transaction date', 'transaction_date')
                ->required(),

            Textarea::make('Description', 'description')
                ->nullable(),

            Select::make('Status', 'status')
                ->options([
                    'draft' => 'Draft',
                    'posted' => 'Posted',
                ])
                ->default('draft'),
        ];
    }
}
