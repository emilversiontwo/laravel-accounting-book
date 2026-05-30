<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Enums;

enum AccountTypeCategoryEnum: string
{
    case Asset = 'asset';

    case Liability = 'liability';

    case Equity = 'equity';

    case Revenue = 'revenue';

    case Expense = 'expense';

    public static function fromValue(string $param): ?self
    {
        return match ($param) {
            self::Asset->value => self::Asset,
            self::Liability->value => self::Liability,
            self::Equity->value => self::Equity,
            self::Revenue->value => self::Revenue,
            self::Expense->value => self::Expense,
            default => null,
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
