<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletType extends Model
{
    use HasFactory;

    protected $table = 'wallet_type';

    public function wallets(): BelongsTo
    {
        return $this->belongsTo(Wallets::class);
    }
}
