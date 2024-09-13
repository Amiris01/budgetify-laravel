<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialInstitute extends Model
{
    use HasFactory;

    protected $table = 'financial_institute';

    public function wallets(): BelongsTo
    {
        return $this->belongsTo(Wallets::class);
    }
}
