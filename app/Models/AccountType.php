<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $name
 * @property string $category
 * @property string $normal_balance_side
 * @property bool $allow_negative_balance
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AccountType extends Model
{
    protected $fillable = [
        'name',
        'category',
        'normal_balance_side',
        'allow_negative_balance',
        'is_active',
    ];


    protected function casts(): array
    {
        return [
            'allow_negative_balance' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
