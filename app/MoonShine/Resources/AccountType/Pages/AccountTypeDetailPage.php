<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AccountType\Pages;

use App\Modules\AccountType\Enums\AccountTypeCategoryEnum;
use App\Modules\AccountType\Enums\AccountTypeNormalBalanceSideEnum;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\AccountType\AccountTypeResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Enum;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;


/**
 * @extends DetailPage<AccountTypeResource>
 */
class AccountTypeDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Text::make('Name', 'name')
                ->required(),

            Enum::make('Category', 'category')
                ->attach(AccountTypeCategoryEnum::class),

            Enum::make('Normal Balance Side', 'normal_balance_side')
                ->attach(AccountTypeNormalBalanceSideEnum::class),

            Checkbox::make('Allow Negative Balance', 'allow_negative_balance'),

            Checkbox::make('Is Active', 'is_active'),
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
