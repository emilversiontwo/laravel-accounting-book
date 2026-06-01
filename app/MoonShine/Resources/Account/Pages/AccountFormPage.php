<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Account\Pages;

use App\Models\Account;
use App\Models\AccountType;
use App\MoonShine\Resources\AccountType\AccountTypeResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Account\AccountResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;


/**
 * @extends FormPage<AccountResource>
 */
class AccountFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Select::make('Account Type', 'account_type_id')
                    ->options(fn ($_) => AccountType::query()
                        ->where('is_active', '=', true)
                        ->pluck('name', 'id')
                        ->toArray()),
                Select::make('Parent Account', 'parent_account_id')
                    ->options(fn ($_) => Account::query()
                        ->where('is_active', '=', true)
                        ->when((int)$this->getResource()->getItemID() > 0, function ($query) {
                            $query->whereKeyNot($this->getResource()->getItemID());
                        })
                        ->pluck('name', 'id')
                        ->toArray())
                    ->nullable()->default(null),
                Text::make('Code', 'code')->required(),
                Text::make('Name', 'name')->required(),
                Checkbox::make('Active', 'is_active')->default(true),
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
        return [
            'account_type_id' => [
                'required',
                'exists:account_types,id',
            ],
            'parent_account_id' => [
                'sometimes',
                'nullable',
                'exists:accounts,id',
            ],
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:accounts,name',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * @param  FormBuilder  $component
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
