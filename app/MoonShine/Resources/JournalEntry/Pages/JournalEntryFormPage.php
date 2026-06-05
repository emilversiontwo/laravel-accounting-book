<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\JournalEntry\Pages;

use App\Models\Account;
use App\Models\Transaction;
use App\MoonShine\Resources\JournalEntry\JournalEntryResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use Throwable;


/**
 * @extends FormPage<JournalEntryResource>
 */
class JournalEntryFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),

                Select::make('Transaction', 'transaction_id')
                    ->options(fn() => Transaction::query()
                        ->where('status', '=', 'draft')
                        ->pluck('description', 'id')
                        ->toArray()
                    ),

                Select::make('Account', 'account_id')
                    ->options(fn() => Account::query()
                        ->where('is_active', '=', true)
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
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [];
    }

    /**
     * @param FormBuilder $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
