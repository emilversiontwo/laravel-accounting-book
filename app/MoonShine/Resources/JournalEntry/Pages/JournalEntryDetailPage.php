<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\JournalEntry\Pages;

use App\Models\Account;
use App\Models\Transaction;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\JournalEntry\JournalEntryResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use Throwable;


/**
 * @extends DetailPage<JournalEntryResource>
 */
class JournalEntryDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Select::make('Transaction', 'transaction_id')
                ->options(fn() => Transaction::query()
                    ->pluck('description', 'id')
                    ->toArray()
                ),

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
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
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
            ...parent::bottomLayer()
        ];
    }
}
