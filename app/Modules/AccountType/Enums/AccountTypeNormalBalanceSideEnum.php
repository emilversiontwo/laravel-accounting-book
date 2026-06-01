<?php

declare(strict_types=1);

namespace App\Modules\AccountType\Enums;

enum AccountTypeNormalBalanceSideEnum: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public static function fromValue(string $param): self
    {
        return match ($param) {
            self::Debit->value => self::Debit,
            self::Credit->value => self::Credit,
            default => null,
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
