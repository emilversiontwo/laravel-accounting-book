<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Account\Pages;

use App\Models\Account;
use App\Models\AccountType;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Account\AccountResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;


/**
 * @extends DetailPage<AccountResource>
 */
class AccountDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Select::make('Account Type', 'account_type_id')
                ->options(fn ($_) => AccountType::query()->pluck('name', 'id')->toArray()),

            Select::make('Parent Account', 'parent_account_id')
                ->options(fn ($_) => Account::query()->pluck('name', 'id')->toArray())
                ->nullable(),

            Text::make('Code', 'code'),

            Text::make('Name', 'name'),

            Checkbox::make('Active', 'is_active'),
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
