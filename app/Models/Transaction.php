<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = ['user_id', 'wallet_id', 'budget_id', 'id_ref', 'table_ref', 'amount', 'description', 'category', 'trans_date', 'attachment', 'trans_type'];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallets::class, 'id', 'wallet_id');
    }

    public function budget(): HasOne
    {
        return $this->HasOne(Budgets::class, 'id', 'budget_id');
    }

    public function event(): HasOne
    {
        return $this->HasOne(Events::class, 'id', 'id_ref');
    }

    public function categories(): HasOne
    {
        return $this->HasOne(Category::class, 'id', 'category');
    }
}
