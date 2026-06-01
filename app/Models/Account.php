<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property AccountType $accountType
 * @property int $account_type_id
 * @property Account $parentAccount
 * @property int $parent_account_id
 * @property string $code
 * @property string $name
 * @property bool $is_active
 */
class Account extends Model
{
    protected $fillable = [
        'account_type_id',
        'parent_account_id',
        'code',
        'name',
        'is_active',
    ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
