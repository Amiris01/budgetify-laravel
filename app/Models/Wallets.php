<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $fillable = ['user_id', 'name', 'amount', 'is_active', 'wallet_type', 'fin_institute', 'description'];

    public function financialInstitute()
    {
        return $this->belongsTo(FinancialInstitute::class, 'fin_institute', 'id');
    }

    public function walletType()
    {
        return $this->belongsTo(WalletType::class, 'wallet_type', 'id');
    }
}
